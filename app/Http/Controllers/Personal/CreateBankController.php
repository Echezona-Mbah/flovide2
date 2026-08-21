<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Countries;
use Illuminate\Support\Facades\Http;
use App\Models\Balance;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Traits\CurrencyHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionHistory;
use Barryvdh\DomPDF\Facade\Pdf;
// use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Log;



class CreateBankController extends Controller
{
    use CurrencyHelper;

public function create(Request $request)
{
    $currencies = Currency::all();
    $personalId = auth('personal-api')->id();
    $balances   = Balance::where('personal_id', $personalId)->get();

    foreach ($balances as $balance) {
        $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
    }

    if ($request->expectsJson()) {
        if ($balances->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No balance created for this personal account',
                'code' => 'BALANCES_EMPTY',
                'data' => [
                    'personal_id' => $personalId,
                    'balances' => []
                ]
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data for add bank form loaded successfully',
            'code' => 'ADD_BANK_DATA_LOADED',
            'data' => [
                'personal_id' => $personalId,
                'balances' => $balances,
                'currencies' => $currencies
            ]
        ], 200);
    }

    return view('business.add_bank', compact('currencies', 'balances'));
}


public function createBalance(Request $request)
{
    $request->validate([
        'name'     => 'required|string',
        'currency' => 'required|string',
    ]);

    $personalId = auth('personal-api')->id();
    $currency   = strtoupper($request->currency);

       // 🔎 Check the currency exists and is active
    $currencyRecord = Currency::where('code', $currency)
        ->where('is_active', true)
        ->first();

    if (!$currencyRecord) {
        $errorMessage = "{$currency} is not currently available for new balances.";

        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code'    => 'CURRENCY_NOT_ACTIVE',
                'data'    => null,
            ], 422)
            : redirect()->back()->withErrors(['message' => $errorMessage]);
    }
    $exists = Balance::where('personal_id', $personalId)
        ->where('currency', $currency)
        ->exists();

    if ($exists) {
        $errorMessage = "You already have a $currency balance. Duplicates are not allowed.";

        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'DUPLICATE_BALANCE',
                'data' => null
            ], 400)
            : redirect()->back()->withErrors(['message' => $errorMessage]);
    }

    $balance = Balance::create([
        'personal_id' => $personalId,
        'name'        => $request->name,
        'currency'    => $currency,
        'amount'      => 0,
    ]);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Balance created successfully.',
            'code' => 'BALANCE_CREATED',
            'data' => $balance
        ], 201);
    }

    return redirect()->route('add_account.create')->with('success', 'Account created successfully.');
}




public function getUserTotalBalance(Request $request)
{
    $personalId = auth('personal-api')->id();
    $total      = Balance::where('personal_id', $personalId)->sum('amount');

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Personal total balance fetched successfully',
            'code' => 'TOTAL_BALANCE_FETCHED',
            'data' => [
                'personal_id' => $personalId,
                'total_balance' => $total
            ]
        ], 200);
    }

    return null;
}


public function updateBalance(Request $request)
{
    $request->validate([
        'balance_id' => 'required',
        'name'       => 'required|string|max:255',
    ]);

    $personalId = auth('personal-api')->id();

    $balance = Balance::where('id', $request->balance_id)
        ->where('personal_id', $personalId)
        ->first();

    if (!$balance) {
        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => 'Balance not found.',
                'code' => 'BALANCE_NOT_FOUND',
                'data' => null
            ], 404)
            : redirect()->back()->with('error', 'Balance not found.');
    }

    $balance->name = $request->name;
    $balance->save();

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Balance updated successfully.',
            'code' => 'BALANCE_UPDATED',
            'data' => $balance
        ], 200);
    }

    return null;
}


public function index(Request $request)
{
    $personalId = auth('personal-api')->id();
    $balances   = Balance::where('personal_id', $personalId)->get();

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'All balances retrieved successfully',
            'code' => 'BALANCES_FETCHED',
            'data' => [
                'personal_id' => $personalId,
                'balances' => $balances,
            ]
        ], 200);
    }

    return null;
}


 public function dashboardapi(Request $request)
{
    $account = auth('personal-api')->user();

    if (!$account) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }

    $balances = Balance::where('personal_id', $account->id)
            ->orderBy('created_at', 'asc')
            ->get();

    $defaultBalance = $balances->first();
        $defaultCurrency = strtoupper($defaultBalance?->currency ?? 'USD');

        $totalBalance = 0;

        foreach ($balances as $balance) {
            $balanceCurrency = strtoupper($balance->currency);
            $balanceAmount = (float) $balance->amount;

            if ($balanceAmount <= 0) {
                continue;
            }

            if ($balanceCurrency === $defaultCurrency) {
                $totalBalance += $balanceAmount;
                continue;
            }

            $rate = ExchangeRate::whereHas('fromCurrency', function ($q) use ($balanceCurrency) {
                    $q->where('code', $balanceCurrency);
                })
                ->whereHas('toCurrency', function ($q) use ($defaultCurrency) {
                    $q->where('code', $defaultCurrency);
                })
                ->first();

            if ($rate) {
                $totalBalance += $balanceAmount * (float) $rate->rate;
            }
        }

    $transactions = TransactionHistory::where('personal_id', $account->id)
        ->latest()
        ->take(4)
        ->get()
        ->map(function ($t) {
            return [
                'type'      => $t->type,
                'date'      => $t->created_at->format('Y-m-d H:i:s'),
                'sender'    => $t->sender ?? 'N/A',
                'recipient' => $t->recipient ?? 'N/A',
                'amount'    => $t->currency_symbol . number_format($t->amount, 2),
                'currency'  => $t->currency,
                'status'    => $t->status,
                'reference' => $t->reference,
                'method' => $t->method,
                'recipient_details' => [
                    'alias'          => $t->recipient_alias,
                    'account_name'   => $t->recipient_account_name,
                    'account_number' => $t->recipient_account_number,
                    'bank_name'      => $t->recipient_bank_name,
                    'bank_currency'  => $t->recipient_bank_currency,
                    'recipient_amount'  => $t->recipient_amount,
                    'fees'  => $t->fees,
                ]
            ];
        });

    $months = collect(range(0, 2))->map(function ($i) {
        return now()->subMonths($i)->format('Y-m');
    })->reverse()->values();

    $dbData = TransactionHistory::where('personal_id', $account->id)
        ->where('created_at', '>=', now()->subMonths(3))
        ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as total_amount")
        ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
        ->pluck('total_amount', 'month');

    $chartData = $months->map(function ($m) use ($dbData) {
        return [
            'month' => $m,
            'total_amount' => $dbData[$m] ?? 0,
        ];
    });

    // ✅ Exchange rates included
    $exchangeRates = ExchangeRate::with(['fromCurrency:id,code', 'toCurrency:id,code'])
        ->get()
        ->map(function ($r) {
            return [
                'from_currency' => $r->fromCurrency->code ?? null,
                'to_currency' => $r->toCurrency->code ?? null,
                'rate' => (float) $r->rate,
                'transfer_fee' => (float) $r->transfer_fee,
                'updated_at' => $r->updated_at?->format('Y-m-d H:i:s'),
            ];
        });
    $currencies = Currency::select('code','currency_code', 'name', 'symbol', 'country_code', 'is_active')
        ->get()
        ->map(function ($c) {
            return [
                'code' => $c->code,
                'currency_code' => $c->currency_code,
                'name' => $c->name,
                'symbol' => $c->symbol,
                'country_code' => strtolower($c->country_code ?? ''),
                'is_active' => $c->is_active ? 'true' : 'false',
            ];
        })
        ->values()
        ->toArray();

    //kyc status
    $identityStatus = strtolower($account->identity_verification_status ?? 'pending');
    $selfieStatus = strtolower($account->selfie_verification_status ?? 'pending');

    if ($identityStatus === 'completed' && $selfieStatus === 'completed') {
        $kycStatus = 'completed';
    } elseif ($identityStatus === 'pending' && $selfieStatus === 'pending') {
        $kycStatus = 'pending';
    } else {
        $kycStatus = 'review';
    }

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Dashboard data fetched successfully',
            'data' => [
                'app_update' => [
                    'latest_version' => config('services.latest_version'),
                    'force_update'   => config('services.force_update', true),
                ],
                'profile_status' => [
                    'proof_address' => $account->proof_address_status ?? 'pending',
                    'kyc' => $kycStatus,
                ],
                'total_balance' => number_format($totalBalance, 2, '.', ''),
                'total_balance_currency' => $defaultCurrency,
                'balances'        => $balances,
                'chart_data'      => $chartData,
                'recent_history'  => $transactions,
                'exchange_rates'  => $exchangeRates,
                'currencies' => $currencies,
            ]
        ], 200);
    }

    return view('dashboard.index', [
        'totalBalance' => $totalBalance,
        'balances' => $balances,
        'chartData' => $chartData,
        'transactions' => $transactions,
        'exchangeRates' => $exchangeRates,
        'currencies' => $currencies,
    ]);
}
    


// public function statement(Request $request, $id)
// {
//     Log::info('Statement: request received', [
//         'balance_id' => $id,
//         'query' => $request->query(),
//         'expects_json' => $request->expectsJson(),
//         'accept_header' => $request->header('Accept'),
//     ]);

//     $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
//         'start_date' => 'required|date',
//         'end_date'   => 'required|date|after_or_equal:start_date',
//     ]);

//     if ($validator->fails()) {
//         Log::warning('Statement: validation failed', [
//             'errors' => $validator->errors()->toArray(),
//         ]);

//         if ($request->expectsJson()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Validation error',
//                 'code' => 'VALIDATION_ERROR',
//                 'data' => $validator->errors()
//             ], 422);
//         }

//         return back()->withErrors($validator)->withInput();
//     }

//     $user = auth()->guard('personal-api')->user() ?? auth()->user();

//     Log::info('Statement: auth resolved', [
//         'user_id' => $user?->id,
//         'guard_used' => auth()->guard('personal-api')->check() ? 'personal-api' : 'default',
//     ]);

//     if (!$user) {
//         Log::error('Statement: no authenticated user found');

//         if ($request->expectsJson()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Unauthenticated.',
//                 'code' => 'UNAUTHENTICATED',
//                 'data' => null
//             ], 401);
//         }

//         abort(401);
//     }

//     $balance = Balance::where('id', $id)
//         ->where('personal_id', $user->id)
//         ->first();

//     Log::info('Statement: balance lookup', [
//         'balance_id' => $id,
//         'personal_id' => $user->id,
//         'found' => $balance ? true : false,
//         'balance_currency' => $balance->currency ?? null,
//     ]);

//     if (!$balance) {
//         Log::warning('Statement: balance not found or not owned by user', [
//             'balance_id' => $id,
//             'personal_id' => $user->id,
//         ]);

//         if ($request->expectsJson()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Balance not found',
//                 'code' => 'BALANCE_NOT_FOUND',
//                 'data' => null
//             ], 404);
//         }

//         abort(404);
//     }

//     $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);

//     Log::debug('Statement: currency meta resolved', [
//         'currency' => $balance->currency,
//         'meta' => $balance->currency_meta,
//     ]);

//     $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
//     $endDate   = \Carbon\Carbon::parse($request->end_date)->endOfDay();

//     Log::info('Statement: date range parsed', [
//         'start_date' => $startDate->toDateTimeString(),
//         'end_date' => $endDate->toDateTimeString(),
//     ]);

//     $transactions = TransactionHistory::where('balance_id', $balance->id)
//         ->whereBetween('created_at', [$startDate, $endDate])
//         ->orderBy('created_at', 'asc')
//         ->get();

//     Log::info('Statement: transactions fetched', [
//         'balance_id' => $balance->id,
//         'count' => $transactions->count(),
//     ]);

//     $isCredit = function ($tx) {
//         $type = strtolower($tx->type ?? '');
//         return in_array($type, ['credit']) || str_contains($type, 'credit');
//     };

//     $openingBalance = TransactionHistory::where('balance_id', $balance->id)
//         ->where('created_at', '<', $startDate)
//         ->get()
//         ->reduce(function ($carry, $tx) use ($isCredit) {
//             return $carry + ($isCredit($tx) ? $tx->amount : -$tx->amount);
//         }, 0);

//     $totalDebit  = $transactions->filter(fn($tx) => !$isCredit($tx))->sum('amount');
//     $totalCredit = $transactions->filter(fn($tx) =>  $isCredit($tx))->sum('amount');
//     $closingBalance = $openingBalance + $totalCredit - $totalDebit;

//     Log::info('Statement: totals calculated', [
//         'opening_balance' => $openingBalance,
//         'total_debit' => $totalDebit,
//         'total_credit' => $totalCredit,
//         'closing_balance' => $closingBalance,
//     ]);

//     // ── API request: generate and return the PDF directly ──────────────────
//     if ($request->expectsJson()) {
//         Log::info('Statement: generating PDF for API response', [
//             'balance_id' => $balance->id,
//         ]);

//         try {
//             $pdf = Pdf::loadView('personal.statement_pdf', compact(
//                 'balance', 'transactions', 'startDate', 'endDate',
//                 'openingBalance', 'totalDebit', 'totalCredit', 'closingBalance'
//             ))->setPaper('a4');

//             $filename = sprintf(
//                 'Flovide-Statement-%s-%s-to-%s.pdf',
//                 $balance->currency,
//                 $startDate->format('Ymd'),
//                 $endDate->format('Ymd')
//             );

//             Log::info('Statement: PDF generated successfully', [
//                 'filename' => $filename,
//             ]);

//             return $pdf->download($filename);

//         } catch (\Throwable $e) {
//             Log::error('Statement: PDF generation failed', [
//                 'message' => $e->getMessage(),
//                 'file' => $e->getFile(),
//                 'line' => $e->getLine(),
//                 'trace' => $e->getTraceAsString(),
//             ]);

//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to generate statement PDF',
//                 'code' => 'PDF_GENERATION_FAILED',
//                 'data' => null
//             ], 500);
//         }
//     }

//     // ── Web request: render the Blade view as before ───────────────────────
//     Log::info('Statement: rendering web view (non-JSON request)');

//     return view('personal.statement_pdf', compact(
//         'balance', 'transactions', 'user',
//         'startDate', 'endDate',
//         'openingBalance', 'totalDebit', 'totalCredit', 'closingBalance'
//     ));
// }


public function statement(Request $request, $id)
{
    Log::info('Statement: request received', [
        'balance_id' => $id,
        'query' => $request->query(),
        'expects_json' => $request->expectsJson(),
        'accept_header' => $request->header('Accept'),
        'path' => $request->path(),
    ]);

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
    ]);

    if ($validator->fails()) {
        Log::warning('Statement: validation failed', [
            'errors' => $validator->errors()->toArray(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'code' => 'VALIDATION_ERROR',
            'data' => $validator->errors(),
        ], 422);
    }

    $user = auth()->guard('personal-api')->user() ?? auth()->user();

    if (!$user) {
        Log::error('Statement: no authenticated user found');

        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated.',
            'code' => 'UNAUTHENTICATED',
            'data' => null,
        ], 401);
    }

    $balance = Balance::where('id', $id)
        ->where('personal_id', $user->id)
        ->first();

    if (!$balance) {
        Log::warning('Statement: balance not found or not owned by user', [
            'balance_id' => $id,
            'personal_id' => $user->id,
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Balance not found',
            'code' => 'BALANCE_NOT_FOUND',
            'data' => null,
        ], 404);
    }

    $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);

    $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
    $endDate   = \Carbon\Carbon::parse($request->end_date)->endOfDay();

    $transactions = TransactionHistory::where('balance_id', $balance->id)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'asc')
        ->get();

    $isCredit = function ($tx) {
        $type = strtolower($tx->type ?? '');
        return in_array($type, ['credit']) || str_contains($type, 'credit');
    };

    $openingBalance = TransactionHistory::where('balance_id', $balance->id)
        ->where('created_at', '<', $startDate)
        ->get()
        ->reduce(function ($carry, $tx) use ($isCredit) {
            return $carry + ($isCredit($tx) ? $tx->amount : -$tx->amount);
        }, 0);

    $totalDebit = $transactions->filter(fn ($tx) => !$isCredit($tx))->sum('amount');
    $totalCredit = $transactions->filter(fn ($tx) => $isCredit($tx))->sum('amount');
    $closingBalance = $openingBalance + $totalCredit - $totalDebit;

    Log::info('Statement: totals calculated', [
        'opening_balance' => $openingBalance,
        'total_debit' => $totalDebit,
        'total_credit' => $totalCredit,
        'closing_balance' => $closingBalance,
    ]);

    if ($request->is('api/*') || $request->expectsJson()) {
        try {
            $pdf = Pdf::loadView('personal.statement_pdf', compact(
                'balance',
                'transactions',
                'user',
                'startDate',
                'endDate',
                'openingBalance',
                'totalDebit',
                'totalCredit',
                'closingBalance'
            ))->setPaper('a4');

            $filename = sprintf(
                'Flovide-Statement-%s-%s-to-%s.pdf',
                $balance->currency,
                $startDate->format('Ymd'),
                $endDate->format('Ymd')
            );

            Log::info('Statement: PDF generated successfully', [
                'filename' => $filename,
            ]);

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);

        } catch (\Throwable $e) {
            Log::error('Statement: PDF generation failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(), // temporary, helps you see the real error
                'code' => 'PDF_GENERATION_FAILED',
                'data' => null,
            ], 500);
        }
    }

    return view('personal.statement_pdf', compact(
        'balance',
        'transactions',
        'user',
        'startDate',
        'endDate',
        'openingBalance',
        'totalDebit',
        'totalCredit',
        'closingBalance'
    ));
}

}
