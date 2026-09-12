<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Balance;
use App\Models\Currency;
use App\Models\TransactionHistory;
use App\Traits\CurrencyHelper;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;


class CreateBankController extends Controller
{
      use CurrencyHelper;



public function create(Request $request)
{
    $user = auth()->user();
    $mode = $request->input('mode', session('mode', 'live'));

    $balances = Balance::where('user_id', $user->id)
        ->where('mode', $mode)
        ->get();

    $existingCurrencies = $balances
        ->pluck('currency')
        ->map(fn ($currency) => strtoupper($currency))
        ->toArray();

    $currencies = Currency::whereNotIn('code', $existingCurrencies)->get();

    foreach ($balances as $balance) {
        $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
    }

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Data for add bank form loaded successfully',
            'code' => 'ADD_BANK_DATA_LOADED',
            'data' => [
                'mode' => $mode,
                'currencies' => $currencies,
                'balances' => $balances,
            ]
        ], 200);
    }

    return view('business.add_bank', compact('currencies', 'balances', 'mode'));
}




public function createBalance(Request $request)
{


    $request->validate([
        'name'     => 'required|string',
        'currency' => 'required|string',
        'mode'     => 'nullable|in:live,test',
    ]);

    $userId   = Auth::id() ?? $request->user_id;
    $currency = strtoupper($request->currency);
    $mode     = $request->input('mode', session('mode', 'live'));

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

    // 🔎 Check if user already has this currency IN THIS MODE
    $exists = Balance::where('user_id', $userId)
        ->where('currency', $currency)
        ->where('mode', $mode)
        ->exists();

    if ($exists) {
        $errorMessage = "You already have a {$currency} balance in " . ucfirst($mode) . " mode. Duplicates are not allowed.";

        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code'    => 'DUPLICATE_BALANCE',
                'data'    => null,
            ], 400)
            : redirect()->back()->withErrors(['message' => $errorMessage]);
    }


    //  dd('lllll');


    $balance = Balance::create([
        'user_id'  => $userId,
        'name'     => $request->name,
        'currency' => $currency,
        'mode'     => $mode,
        'amount'   => 0,
    ]);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Balance created successfully.',
            'code'    => 'BALANCE_CREATED',
            'mode'    => $mode,
            'data'    => $balance,
        ], 201);
    }

    return redirect()->route('add_account.create')->with('success', ucfirst($mode) . ' account created successfully.');
}


public function getUserTotalBalance(Request $request)
{
    $user = auth()->user();
    $mode = $request->input('mode', session('mode', 'live'));

    $team    = TeamMembers::where('user_id', $user->id)->first();
    $ownerId = $team ? $team->owner_id : $user->id;

    $total = Balance::where('user_id', $ownerId)
        ->where('mode', $mode)
        ->sum('amount');

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'User total balance fetched successfully',
            'code' => 'TOTAL_BALANCE_FETCHED',
            'data' => [
                'user_id' => $ownerId,
                'mode' => $mode,
                'total_balance' => $total
            ]
        ], 200);
    }

    return view('business.user_balance_total', [
        'total' => $total
    ]);
}   



public function UpdateBalance(Request $request)
{
    $request->validate([
        'balance_id' => 'required',
        'name' => 'required|string|max:255',
    ]);

    $balanceId = $request->input('balance_id');
    $newName = $request->input('name');
    $mode = $request->input('mode', session('mode', 'live'));

    $balance = Balance::where('id', $balanceId)
        ->where('mode', $mode)
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

    $balance->name = $newName;
    $balance->save();

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Balance updated successfully.',
            'code' => 'BALANCE_UPDATED',
            'data' => $balance
        ], 200);
    }

    return redirect()->back()->with('success', 'Balance updated successfully.');
}


    public function index(Request $request)
        {
            $balances = Balance::all(); // Eager load user if needed

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'All balances retrieved successfully',
                    'success' => true,
                    'data' => $balances,
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                ], 200);
            }

            return view('business.all_balances', compact('balances'));
        }




public function dashboardapi(Request $request)
{
    $account = Auth::user();

    if (!$account) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }

        $balances = \App\Models\Balance::where('user_id', $account->id)
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

            $rate = \App\Models\ExchangeRate::whereHas('fromCurrency', function ($q) use ($balanceCurrency) {
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

        $autoDepositsByBalance = \App\Models\InteracAutoDeposit::where('user_id', $account->id)
            ->get()
            ->groupBy('balance_id');

        foreach ($balances as $balance) {
            if (strtoupper($balance->currency) === 'CAD') {
                $balance->interac_autodeposit_emails = ($autoDepositsByBalance->get($balance->id) ?? collect())
                    ->map(function ($ad) {
                        return [
                            'id'       => $ad->id,
                            'email'    => $ad->email,
                            'status'   => $ad->status,
                            'added_at' => optional($ad->added_at)->format('Y-m-d H:i:s'),
                        ];
                    })
                    ->values();
            } else {
                $balance->interac_autodeposit_emails = [];
            }
        }
        $defaultBalance = $balances->first();

   // dd($totalBalance);

    $transactions = \App\Models\TransactionHistory::where('user_id', $account->id)
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

    $dbData = \App\Models\TransactionHistory::where('user_id', $account->id)
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

    // ✅ Exchange rates
    $exchangeRates = \App\Models\ExchangeRate::with(['fromCurrency:id,code', 'toCurrency:id,code'])
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

        $currencies = \App\Models\Currency::select('code','currency_code', 'name', 'symbol', 'country_code', 'is_active')
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


        $currencyFees = \App\Models\UserCurrencyFee::where('user_id', $account->id)
        ->get()
        ->map(function ($f) {
            return [
                'currency' => $f->currency,
                'collection' => [
                    'enabled'   => $f->collection_enabled,
                    'percent'   => $f->collection_percent,
                    'fixed'     => $f->collection_fixed,
                    'min'       => $f->collection_min,
                    'max'       => $f->collection_max,
                    'fee_label' => $this->describeFees($f, 'collection', $f->currency),
                ],
                'payout' => [
                    'enabled'   => $f->payout_enabled,
                    'percent'   => $f->payout_percent,
                    'fixed'     => $f->payout_fixed,
                    'min'       => $f->payout_min,
                    'max'       => $f->payout_max,
                    'fee_label' => $this->describeFees($f, 'payout', $f->currency),
                ],
            ];
        })
        ->values();


    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Dashboard data fetched successfully',
            'data' => [
                'app_update' => [                                              // ← ADD HERE
                    'latest_version' => config('services.latest_version'),
                    'force_update'   => config('services.force_update', true),
                ],
                'account_status' => [
                    'is_locked' => (bool) $account->is_locked,
                ],
                'total_balance' => number_format($totalBalance, 2, '.', ''),
                'total_balance_currency' => $defaultCurrency,
                'balances'       => $balances,
                'default_interac_autodeposit_email' => 'payments@flovide.com',
                'chart_data'     => $chartData,
                'recent_history' => $transactions,
                'exchange_rates' => $exchangeRates, // ✅ added
                'currencies' => $currencies,
                'currency_fees' => $currencyFees,

            ]
        ], 200);
    }

    return view('dashboard.index', [
        'totalBalance' => $totalBalance,
        'balances' => $balances,
        'chartData' => $chartData,
        'transactions' => $transactions,
        'exchangeRates' => $exchangeRates, // optional for web view
        'currencies' => $currencies,
    ]);
}
    
    
    
    // public function show(Request $request, $id)
    // {
    //     $user = auth()->user();
    //     $mode = $request->input('mode', session('mode', 'live'));

    //     $balance = Balance::where('id', $id)
    //         ->where('user_id', $user->id)
    //         ->where('mode', $mode)
    //         ->firstOrFail();

    //     $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);

    //     // Attach virtual account info from user
    //     $balance->virtual_account_number = $user->virtual_account_number ?? null;
    //     $balance->virtual_account_name   = $user->virtual_account_name   ?? null;
    //     $balance->virtual_account_bank   = $user->virtual_account_bank   ?? null;

    //     $transactions = TransactionHistory::where('balance_id', $balance->id)
    //         ->where('mode', $mode)
    //         ->select([
    //             'id', 'type', 'transaction_type','amount', 'currency',
    //             'fees', 'status', 'reference', 'order_id', 'sender',
    //             'recipient_account_name', 'method', 'created_at'
    //         ])
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(15);
        

    //     return view('business.balance-detail', compact('balance', 'transactions', 'mode'));
    // }

    public function show(Request $request, $id)
{
    $user = auth()->user();
    $mode = $request->input('mode', session('mode', 'live'));

    $balance = Balance::where('id', $id)
        ->where('user_id', $user->id)
        ->where('mode', $mode)
        ->firstOrFail();

    $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);

    $balance->virtual_account_number = $user->virtual_account_number ?? null;
    $balance->virtual_account_name   = $user->virtual_account_name   ?? null;
    $balance->virtual_account_bank   = $user->virtual_account_bank   ?? null;

    $autoDeposits = strtoupper($balance->currency) === 'CAD'
    ? \App\Models\InteracAutoDeposit::where('balance_id', $balance->id)->orderBy('created_at', 'desc')->get()
    : collect();
        

    $transactions = TransactionHistory::where('balance_id', $balance->id)
        ->where('mode', $mode)
        ->select([
            'id', 'type', 'transaction_type', 'amount', 'currency',
            'fees', 'status', 'reference', 'order_id', 'sender',
            'recipient_account_name', 'method', 'created_at'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

    return view('business.balance-detail', compact('balance', 'transactions', 'mode', 'autoDeposits'));
}

    // ── Statement PDF ──────────────────────────────────────────────────────────
    // public function statement(Request $request, $id)
    // {
    //     $request->validate([
    //         'start_date' => 'required|date',
    //         'end_date'   => 'required|date|after_or_equal:start_date',
    //     ]);

    //     $user = auth()->user();
    //     $mode = session('mode', 'live');

    //     $balance = Balance::where('id', $id)
    //         ->where('user_id', $user->id)
    //         ->firstOrFail();


    //     $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);

    //     $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
    //     $endDate   = \Carbon\Carbon::parse($request->end_date)->endOfDay();

    //     $transactions = TransactionHistory::where('balance_id', $balance->id)
    //         ->where('mode', $mode)
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->orderBy('created_at', 'asc')
    //         ->get();


    //         // ── Helper: determine if a tx is credit ──────────────────────────────
    //     $isCredit = function ($tx) {
    //         $type = strtolower($tx->type ?? '');
    //         return in_array($type, ['credit']) ||
    //             str_contains($type, 'credit');
    //         // withdrawal, swap, debit, payment = not credit
    //     };

    //     // ── Opening balance ───────────────────────────────────────────────────
    //     $openingBalance = TransactionHistory::where('balance_id', $balance->id)
    //         ->where('created_at', '<', $startDate)
    //         ->get()
    //         ->reduce(function ($carry, $tx) use ($isCredit) {
    //             return $carry + ($isCredit($tx) ? $tx->amount : -$tx->amount);
    //         }, 0);

    //     $totalDebit  = $transactions->filter(fn($tx) => !$isCredit($tx))->sum('amount');
    //     $totalCredit = $transactions->filter(fn($tx) =>  $isCredit($tx))->sum('amount');
    //     $closingBalance = $openingBalance + $totalCredit - $totalDebit;
    //     //dd( $openingBalance);


    //     return view('business.statement', compact(
    //         'balance', 'transactions', 'user',
    //         'startDate', 'endDate',
    //         'openingBalance', 'totalDebit', 'totalCredit', 'closingBalance'
    //     ));
    // }

    // public function statement(Request $request, $id)
    // {
    //     $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
    //         'start_date' => 'required|date',
    //         'end_date'   => 'required|date|after_or_equal:start_date',
    //     ]);

    //     if ($validator->fails()) {
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

    //     $user = auth()->user();
    //     $mode = session('mode', 'live');

    //     $balance = Balance::where('id', $id)
    //         ->where('user_id', $user->id)
    //         ->first();

    //     if (!$balance) {
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

    //     $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
    //     $endDate   = \Carbon\Carbon::parse($request->end_date)->endOfDay();

    //     $transactions = TransactionHistory::where('balance_id', $balance->id)
    //         ->where('mode', $mode)
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->orderBy('created_at', 'asc')
    //         ->get();

    //     // ── Helper: determine if a tx is credit ──────────────────────────────
    //     $isCredit = function ($tx) {
    //         $type = strtolower($tx->type ?? '');
    //         return in_array($type, ['credit']) ||
    //             str_contains($type, 'credit');
    //         // withdrawal, swap, debit, payment = not credit
    //     };

    //     // ── Opening balance ───────────────────────────────────────────────────
    //     $openingBalance = TransactionHistory::where('balance_id', $balance->id)
    //         ->where('created_at', '<', $startDate)
    //         ->get()
    //         ->reduce(function ($carry, $tx) use ($isCredit) {
    //             return $carry + ($isCredit($tx) ? $tx->amount : -$tx->amount);
    //         }, 0);

    //     $totalDebit  = $transactions->filter(fn($tx) => !$isCredit($tx))->sum('amount');
    //     $totalCredit = $transactions->filter(fn($tx) =>  $isCredit($tx))->sum('amount');
    //     $closingBalance = $openingBalance + $totalCredit - $totalDebit;

    //     if ($request->expectsJson()) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Statement fetched successfully',
    //             'code' => 'STATEMENT_FETCHED',
    //             'data' => [
    //                 'balance' => $balance,
    //                 'transactions' => $transactions,
    //                 'start_date' => $startDate->format('Y-m-d'),
    //                 'end_date' => $endDate->format('Y-m-d'),
    //                 'opening_balance' => number_format($openingBalance, 2, '.', ''),
    //                 'total_debit' => number_format($totalDebit, 2, '.', ''),
    //                 'total_credit' => number_format($totalCredit, 2, '.', ''),
    //                 'closing_balance' => number_format($closingBalance, 2, '.', ''),
    //             ]
    //         ], 200);
    //     }

    //     return view('business.statement', compact(
    //         'balance', 'transactions', 'user',
    //         'startDate', 'endDate',
    //         'openingBalance', 'totalDebit', 'totalCredit', 'closingBalance'
    //     ));
    // }

    public function statement(Request $request, $id)
    {
        Log::info('Business statement: request received', [
            'balance_id' => $id,
            'query' => $request->query(),
            'expects_json' => $request->expectsJson(),
            'accept_header' => $request->header('Accept'),
            'path' => $request->path(),
        ]);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'mode'       => 'nullable|in:live,test',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'code' => 'VALIDATION_ERROR',
                'data' => $validator->errors(),
            ], 422);
        }

        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'code' => 'UNAUTHENTICATED',
                'data' => null,
            ], 401);
        }

        $mode = $request->input('mode', session('mode', 'live'));

        $balance = Balance::where('id', $id)
            ->where('user_id', $user->id)
            ->where('mode', $mode)
            ->first();

        if (!$balance) {
            return response()->json([
                'success' => false,
                'message' => 'Balance not found',
                'code' => 'BALANCE_NOT_FOUND',
                'data' => null,
            ], 404);
        }

        $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);

        $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
        $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();

        $transactions = TransactionHistory::where('balance_id', $balance->id)
            ->where('mode', $mode)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get();

        $isCredit = function ($tx) {
            $type = strtolower($tx->type ?? '');
            return in_array($type, ['credit']) || str_contains($type, 'credit');
        };

        $openingBalance = TransactionHistory::where('balance_id', $balance->id)
            ->where('mode', $mode)
            ->where('created_at', '<', $startDate)
            ->get()
            ->reduce(function ($carry, $tx) use ($isCredit) {
                return $carry + ($isCredit($tx) ? $tx->amount : -$tx->amount);
            }, 0);

        $totalDebit = $transactions->filter(fn ($tx) => !$isCredit($tx))->sum('amount');
        $totalCredit = $transactions->filter(fn ($tx) => $isCredit($tx))->sum('amount');
        $closingBalance = $openingBalance + $totalCredit - $totalDebit;

        if ($request->is('api/*') || $request->expectsJson()) {
            try {
                $pdf = Pdf::loadView('business.statement', compact(
                    'balance',
                    'transactions',
                    'user',
                    'startDate',
                    'endDate',
                    'openingBalance',
                    'totalDebit',
                    'totalCredit',
                    'closingBalance',
                    'mode'
                ))->setPaper('a4');

                $filename = sprintf(
                    'Flovide-Business-Statement-%s-%s-to-%s.pdf',
                    $balance->currency,
                    $startDate->format('Ymd'),
                    $endDate->format('Ymd')
                );

                return response($pdf->output(), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]);

            } catch (\Throwable $e) {
                Log::error('Business statement PDF generation failed', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'code' => 'PDF_GENERATION_FAILED',
                    'data' => null,
                ], 500);
            }
        }

        return view('business.statement', compact(
            'balance',
            'transactions',
            'user',
            'startDate',
            'endDate',
            'openingBalance',
            'totalDebit',
            'totalCredit',
            'closingBalance',
            'mode'
        ));
    }

        private function describeFees(\App\Models\UserCurrencyFee $userFee, string $side, string $currency): string
    {
        $percent = $userFee->{"{$side}_percent"};
        $fixed   = $userFee->{"{$side}_fixed"};

        if ($percent > 0 && $fixed > 0) {
            return "{$percent}% + " . number_format($fixed, 2) . " {$currency}";
        }
        if ($percent > 0) {
            return "{$percent}%";
        }
        if ($fixed > 0) {
            return number_format($fixed, 2) . " {$currency} flat";
        }
        return "No fee";
    }



   public function interacAutoDepositSettings(Request $request, $id)
{
    $user = auth()->user();
    $mode = $request->input('mode', session('mode', 'live'));

    $balance = Balance::where('id', $id)
        ->where('user_id', $user->id)
        ->where('mode', $mode)
        ->firstOrFail();

    if (strtoupper($balance->currency) !== 'CAD') {
        return redirect()->route('balance.show', $balance->id)
            ->with('error', 'Interac Auto Deposit is only available for CAD wallets.');
    }

    $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);

    $autoDeposits = \App\Models\InteracAutoDeposit::where('balance_id', $balance->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('business.interac_autodeposit_settings', compact('balance', 'autoDeposits'));
}

// public function saveInteracAutoDepositEmail(Request $request, $id)
// {
//     $user = auth()->user();
//     $mode = $request->input('mode', session('mode', 'live'));

//     $validated = $request->validate([
//         'email' => 'required|email|max:255',
//     ]);

//     $balance = Balance::where('id', $id)
//         ->where('user_id', $user->id)
//         ->where('mode', $mode)
//         ->first();

//     if (!$balance) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Balance not found.',
//             'code'    => 'BALANCE_NOT_FOUND',
//         ], 404);
//     }

//     if (strtoupper($balance->currency) !== 'CAD') {
//         return response()->json([
//             'success' => false,
//             'message' => 'Interac Auto Deposit is only available for CAD wallets.',
//             'code'    => 'CAD_ONLY',
//         ], 422);
//     }

//     $exists = \App\Models\InteracAutoDeposit::where('balance_id', $balance->id)
//         ->where('email', $validated['email'])
//         ->exists();

//     if ($exists) {
//         return response()->json([
//             'success' => false,
//             'message' => 'This email is already registered for this wallet.',
//             'code'    => 'DUPLICATE_EMAIL',
//         ], 422);
//     }

//     $autoDeposit = \App\Models\InteracAutoDeposit::create([
//         'user_id'    => $user->id,
//         'balance_id' => $balance->id,
//         'email'      => $validated['email'],
//         'status'     => 'pending',
//         'added_at'   => now(),
//     ]);

//     Log::info('[Interac AutoDeposit] Email added', [
//         'balance_id' => $balance->id,
//         'user_id'    => $user->id,
//         'email'      => $validated['email'],
//     ]);

//     return response()->json([
//         'success' => true,
//         'message' => 'Interac Auto Deposit email added successfully.',
//         'code'    => 'AUTODEPOSIT_EMAIL_ADDED',
//         'data'    => [
//             'id'       => $autoDeposit->id,
//             'email'    => $autoDeposit->email,
//             'status'   => $autoDeposit->status,
//             'added_at' => $autoDeposit->added_at->toDateTimeString(),
//         ],
//     ], 200);
// }

public function saveInteracAutoDepositEmail(Request $request, $id)
{
    $wantsJson = $request->expectsJson() || $request->is('api/*');

    $respond = function (array $payload, int $status = 200, array $errors = []) use ($request, $wantsJson) {
        if ($wantsJson) {
            return response()->json($payload, $status);
        }

        if (!($payload['success'] ?? false)) {
            return redirect()->back()
                ->withErrors($errors ?: ['message' => $payload['message']])
                ->withInput()
                ->with('error', $payload['message']);
        }

        return redirect()->back()
            ->with('success', $payload['message'])
            ->with('interac_auto_deposit', $payload['data'] ?? null);
    };

    $user = auth()->user();

    if (!$user) {
        return $respond([
            'success' => false,
            'message' => 'Unauthenticated.',
            'code' => 'UNAUTHENTICATED',
        ], 401);
    }

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'email' => 'required|email|max:255',
    ]);

    if ($validator->fails()) {
        return $respond([
            'success' => false,
            'message' => 'Validation error.',
            'code' => 'VALIDATION_ERROR',
            'errors' => $validator->errors(),
        ], 422, $validator->errors()->toArray());
    }

    $validated = $validator->validated();
    $mode = $request->input('mode', session('mode', 'live'));

    $balance = Balance::where('id', $id)
        ->where('user_id', $user->id)
        ->where('mode', $mode)
        ->first();

    if (!$balance) {
        return $respond([
            'success' => false,
            'message' => 'Balance not found.',
            'code' => 'BALANCE_NOT_FOUND',
        ], 404);
    }

    if (strtoupper($balance->currency) !== 'CAD') {
        return $respond([
            'success' => false,
            'message' => 'Interac Auto Deposit is only available for CAD wallets.',
            'code' => 'CAD_ONLY',
        ], 422);
    }

    $exists = \App\Models\InteracAutoDeposit::where('balance_id', $balance->id)
        ->where('email', $validated['email'])
        ->exists();

    if ($exists) {
        return $respond([
            'success' => false,
            'message' => 'This email is already registered for this wallet.',
            'code' => 'DUPLICATE_EMAIL',
        ], 422, ['email' => 'This email is already registered for this wallet.']);
    }

    $autoDeposit = \App\Models\InteracAutoDeposit::create([
        'user_id' => $user->id,
        'balance_id' => $balance->id,
        'email' => $validated['email'],
        'status' => 'pending',
        'added_at' => now(),
    ]);

    Log::info('[Interac AutoDeposit] Email added', [
        'balance_id' => $balance->id,
        'user_id' => $user->id,
        'email' => $validated['email'],
    ]);

    return $respond([
        'success' => true,
        'message' => 'Interac Auto Deposit email added successfully.',
        'code' => 'AUTODEPOSIT_EMAIL_ADDED',
        'data' => [
            'id' => $autoDeposit->id,
            'email' => $autoDeposit->email,
            'status' => $autoDeposit->status,
            'added_at' => optional($autoDeposit->added_at)->toDateTimeString(),
        ],
    ], 200);
}

public function deleteInteracAutoDepositEmail(Request $request, $id, $emailId)
{
    $user = auth()->user();

    $entry = \App\Models\InteracAutoDeposit::where('id', $emailId)
        ->where('balance_id', $id)
        ->where('user_id', $user->id)
        ->first();

    if (!$entry) {
        return response()->json([
            'success' => false,
            'message' => 'Entry not found.',
            'code'    => 'NOT_FOUND',
        ], 404);
    }

    $entry->delete();

    return response()->json([
        'success' => true,
        'message' => 'Removed successfully.',
        'code'    => 'AUTODEPOSIT_EMAIL_DELETED',
    ], 200);
}

    
}
