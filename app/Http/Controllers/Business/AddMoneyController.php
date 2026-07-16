<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Countries;
use App\Models\Currency;
use App\Models\TeamMembers;
use App\Notifications\GeneralNotification;
use App\Traits\CurrencyHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionHistory;
use App\Services\BlaaizService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AddMoneyController extends Controller

{
  use CurrencyHelper;

  public function index(Request $request)
{
    $countries = Countries::all();

    $user    = auth()->user();
    $team    = TeamMembers::where('user_id', $user->id)->first();
    $ownerId = $team ? $team->owner_id : $user->id;
    $mode    = session('mode', 'live');

    $balances = Balance::where('user_id', $ownerId)
        ->where('mode', $mode)
        ->get();

    foreach ($balances as $balance) {
        $meta = $this->getCountryCodeFromCurrency($balance->currency);
        $balance->currency_meta = $meta ?? [
            'country' => strtolower(substr($balance->currency, 0, 2)),
            'symbol'  => $balance->currency,
            'name'    => $balance->currency,
        ];
    }

    // ✅ currencies from currencies table
    $allCurrencies = Currency::all()->map(function ($c) {
        $countryCode = strtolower($c->country_code ?? substr($c->code, 0, 2));

        return [
            'country_name' => $c->name,
            'code' => $c->code,
            'symbol' => $c->symbol ?? '',
            'flag' => "https://flagcdn.com/w20/{$countryCode}.png",
        ];
    })->values()->all();

    return view('business.add_money', compact(
        'countries',
        'balances',
        'allCurrencies',
        'mode'
    ));
}

public function interacDetails(Request $request)
{
    $user = Auth::user();
    $balanceId = $request->query('balance_id');
    $amount    = (float) $request->query('amount', 0);

    $balance = Balance::where('user_id', $user->id)
        ->where('id', $balanceId)
        ->first();

    if (!$balance) {
        return redirect()->route('add_money')->with('error', 'Balance account not found.');
    }

    $currency = strtoupper($balance->currency);

    $fee       = 0;
    $netAmount = $amount;
    $feeLabel  = null;

    if ($currency === 'CAD') {
        $userFee = \App\Models\UserCurrencyFee::where('user_id', $user->id)
            ->where('currency', $currency)
            ->first();

        if ($userFee && $userFee->collection_enabled) {
            $fee       = $userFee->calcCollectionFee($amount);
            $netAmount = round($amount - $fee, 2);
            $feeLabel  = $this->describeFee($userFee, 'collection', $currency);
        }
    }

    Log::info('[Interac Details] Fee computed', [
        'user_id'  => $user->id,
        'currency' => $currency,
        'amount'   => $amount,
        'fee'      => $fee,
        'net'      => $netAmount,
    ]);

    return view('business.interac_details', compact(
        'balance', 'amount', 'fee', 'netAmount', 'feeLabel'
    ));
}



    // ── Interac ──────────────────────────────────────────────────────────────

public function topupWithInterac(Request $request, BlaaizService $blaaiz)
{
    Log::info('[Interac Initiate] Request received', $request->all());
   
    $isApi = $request->expectsJson();
    $mode  = session('mode', 'live');

    // ── Block web-based deletion while in Test mode ──────────────────────
    if (!$isApi && $mode === 'test') {
        $message = 'Collection in Test Mode is only available via the API.';
        return redirect()->back()->withErrors(['message' => $message]);
    }

    $user = Auth::user();
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated',
            'code'    => 'UNAUTHENTICATED',
            'data'    => null,
        ], 401);
    }

    Log::info('[Interac Initiate] Authenticated user', ['user_id' => $user->id, 'email' => $user->email]);

    $request->validate([
        'balance' => 'required|string',   // ← UUID, not integer
        'amount'  => 'required|numeric|min:1',
        'email'   => 'required|email',
        'transfer_fee'  => 'nullable|numeric',
    ]);

    $balance = Balance::where('user_id', $user->id)
        ->where('id', $request->balance)
        ->first();

    Log::info('[Interac Initiate] Balance lookup', [
        'found'      => (bool) $balance,
        'balance_id' => $request->balance,
        'user_id'    => $user->id,
    ]);

    if (!$balance) {
        return response()->json([
            'success' => false,
            'message' => 'Balance account not found.',
            'code'    => 'BALANCE_NOT_FOUND',
            'data'    => null,
        ], 404);
    }

    $currency = strtoupper($balance->currency);
    $amount   = (float) $request->amount;

    // ── Interac is CAD only ─────────────────────────────────────────────
    if ($currency !== 'CAD') {
        $msg = 'Interac top-up is only available for CAD wallets.';
        return $isApi
            ? response()->json(['success' => false, 'message' => $msg, 'code' => 'INTERAC_CAD_ONLY', 'data' => null], 422)
            : back()->withInput()->with('error', $msg);
    }

    // ── Platform fee — based on CAD collection settings ─────────────────
    $platformFee = 0;
    $collectionFee = 0;

    $userFee = \App\Models\UserCurrencyFee::where('user_id', $user->id)
        ->where('currency', $currency)
        ->first();

    Log::info('[Interac Initiate] Collection fee lookup', [
        'user_id'  => $user->id,
        'currency' => $currency,
        'found'    => (bool) $userFee,
        'enabled'  => $userFee->collection_enabled ?? null,
    ]);

    if (!$userFee || !$userFee->collection_enabled) {
        $msg = "Contact your marketer to enable collection pricing for {$currency}.";
        return $isApi
            ? response()->json(['success' => false, 'message' => $msg, 'code' => 'COLLECTION_DISABLED', 'data' => null], 422)
            : back()->withInput()->with('error', $msg);
    }

    if ($userFee->collection_min > 0 && $amount < $userFee->collection_min) {
        $msg = "Minimum top-up for {$currency} is " . number_format($userFee->collection_min, 2);
        return $isApi
            ? response()->json(['success' => false, 'message' => $msg, 'code' => 'BELOW_COLLECTION_MIN', 'data' => null], 422)
            : back()->withInput()->with('error', $msg);
    }

    if ($userFee->collection_max > 0 && $amount > $userFee->collection_max) {
        $msg = "Maximum top-up for {$currency} is " . number_format($userFee->collection_max, 2);
        return $isApi
            ? response()->json(['success' => false, 'message' => $msg, 'code' => 'ABOVE_COLLECTION_MAX', 'data' => null], 422)
            : back()->withInput()->with('error', $msg);
    }

    // Fee calculated on the top-up amount (in CAD)
    $collectionFee = round(
        ($amount * $userFee->collection_percent / 100) + $userFee->collection_fixed,
        2
    );

    $platformFee = $collectionFee;
    $netAmount   = round($amount - $platformFee, 2); // what actually lands in the wallet

        if ($amount <= $platformFee) {
            $msg = "Amount must be greater than the platform fee of " . number_format($platformFee, 2) . " {$currency}.";
            return response()->json([
                'success' => false,
                'message' => $msg,
                'code'    => 'AMOUNT_BELOW_FEE',
                'data'    => [
                    'amount'       => $amount,
                    'platform_fee' => $platformFee,
                ],
            ], 422);
        }

    Log::info('[Interac Initiate] Fee computed', [
        'amount'         => $amount,
        'collection_fee' => $collectionFee,
        'net_amount'     => $netAmount,
    ]);

    $payload = [
        'email'  => $request->email,
        'amount' => $amount,
    ];

    $response = $blaaiz->initiateInteracMoneyRequest($payload);

    Log::info('[Interac Initiate] Blaaiz response', [
        'success' => $response['success'],
        'status'  => $response['status'],
        'data'    => $response['data'],
    ]);

    if (!$response['success']) {
        $errorMsg = $response['data']['message']
            ?? $response['data']['error_description']
            ?? 'Interac request failed. Please try again.';

        return response()->json([
            'success' => false,
            'message' => $errorMsg,
            'code'    => 'INTERAC_REQUEST_FAILED',
            'data'    => $response['data'] ?? null,
        ], $response['status']);
    }

    $responseData = $response['data'] ?? [];

    $transaction = TransactionHistory::create([
        'user_id'           => $user->id,
        'balance_id'        => $balance->id,
        'payment_provider'  => 'interac',
        'transaction_type'  => 'payment',
        'method'            => 'credit',
        'type'              => 'credit',
        'payment_method'    => 'auto',
        'sender'            => $request->email,
        'amount'            => $amount,
        'fees'              => $platformFee,
        'platform_fee'      => $platformFee,
        'recipient_amount'  => $netAmount,
        'currency'          => $balance->currency,
        'status'            => 'pending',
        'reference'         => $responseData['reference'],
        'payment_reference' => $responseData['reference'],
        'order_id'          => $responseData['transaction_id'],
    ]);

    $user->notify(new GeneralNotification(
        "Interac Top-up Submitted 🎉",
        "Your Interac deposit of {$amount} {$balance->currency} has been submitted. Ref: {$responseData['reference']}"
    ));

    return response()->json([
        'success' => true,
        'message' => 'Interac deposit submitted successfully.',
        'code'    => 'INTERAC_TOPUP_SUBMITTED',
        'data'    => $transaction,
    ], 200);
}

// public function topupWithInterac(Request $request, BlaaizService $blaaiz)
// {
//     Log::info('[Interac Initiate] Request received', $request->all());

//       $isApi = $request->expectsJson();
//     $mode  = session('mode', 'live');

//     // ── Block web-based deletion while in Test mode ──────────────────────
//     if (!$isApi && $mode === 'test') {
//         $message = 'Collection in Test Mode is only available via the API.';
//         return redirect()->back()->withErrors(['message' => $message]);
//     }

//     $user = Auth::user();
//     if (!$user) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Unauthenticated',
//             'code'    => 'UNAUTHENTICATED',
//             'data'    => null,
//         ], 401);
//     }

//     Log::info('[Interac Initiate] Authenticated user', ['user_id' => $user->id, 'email' => $user->email]);

//     $request->validate([
//         'balance' => 'required|string',   // ← UUID, not integer
//         'amount'  => 'required|numeric|min:1',
//         'email'   => 'required|email',
//     ]);

//     $balance = Balance::where('user_id', $user->id)
//         ->where('id', $request->balance)
//         ->first();

//     Log::info('[Interac Initiate] Balance lookup', [
//         'found'      => (bool) $balance,
//         'balance_id' => $request->balance,
//         'user_id'    => $user->id,
//     ]);

//     if (!$balance) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Balance account not found.',
//             'code'    => 'BALANCE_NOT_FOUND',
//             'data'    => null,
//         ], 404);
//     }

//     $payload = [
//         'email'  => $request->email,
//         'amount' => $request->amount,
//     ];

//     $response = $blaaiz->initiateInteracMoneyRequest($payload);

//     Log::info('[Interac Initiate] Blaaiz response', [
//         'success' => $response['success'],
//         'status'  => $response['status'],
//         'data'    => $response['data'],
//     ]);

//     if (!$response['success']) {
//         $errorMsg = $response['data']['message']
//             ?? $response['data']['error_description']
//             ?? 'Interac request failed. Please try again.';

//         return response()->json([
//             'success' => false,
//             'message' => $errorMsg,
//             'code'    => 'INTERAC_REQUEST_FAILED',
//             'data'    => $response['data'] ?? null,
//         ], $response['status']);
//     }

//     $responseData = $response['data'] ?? [];

//     $transaction = TransactionHistory::create([
//         'user_id'          => $user->id,
//         'balance_id'       => $balance->id,
//         'payment_provider' => 'interac',
//         'transaction_type' => 'payment',
//         'method'           => 'credit',
//         'type'              => 'credit',
//         'payment_method'   => 'auto',
//         'sender'           => $request->email,
//         'amount'           => $request->amount,
//         'currency'         => $balance->currency,
//         'status'           => 'pending',
//         'reference'        => $responseData['reference'],
//         'payment_reference'=> $responseData['reference'],
//         'order_id'         => $responseData['transaction_id'],
//     ]);

//     $user->notify(new GeneralNotification(
//         "Interac Top-up Submitted 🎉",
//         "Your Interac deposit of {$request->amount} {$balance->currency} has been submitted. Ref: {$responseData['reference']}"  // ← fixed double $
//     ));

//     return response()->json([
//         'success' => true,
//         'message' => 'Interac deposit submitted successfully.',
//         'code'    => 'INTERAC_TOPUP_SUBMITTED',
//         'data'    => $transaction,
//     ], 200);
// }






public function topupWithCard(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['data' => ['errors' => 'Unauthenticated']], 401);
    }

    $request->validate([
        'balance'      => 'required|integer',
        'amount'       => 'required|numeric|min:100',
        'card_number'  => 'required|string',
        'expiry_month' => 'required|string',
        'expiry_year'  => 'required|string',
        'cvv'          => 'required|string', 
    ]);

    $balance = Balance::where('user_id', $user->id)
        ->where('id', $request->balance)
        ->first();

    if (!$balance) {
        return response()->json([
            'data' => ['errors' => 'Balance account not found']
        ], 404);
    }

    $digitsOnly = preg_replace('/\D/', '', $request->card_number);
    $maskedCard = substr($digitsOnly, -4);
    $reference  = 'TOPUP-' . strtoupper(uniqid());

    // Update balance
    $balance->amount += $request->amount;
    $balance->save();

    // --- CREATE TRANSACTION HISTORY RECORD ---
    \App\Models\TransactionHistory::create([
        'user_id'       => $user->id,
        'balance_id'    => $balance->id,
        'type'          => 'credit', // top-ups are usually 'credit'
        'amount'        => $request->amount,
        'currency'      => $balance->currency,
        'status'        => 'success',
        'reference'     => $reference,
        'transaction_type' => 'topup_card',
        'card_number'   => '**** **** **** ' . $maskedCard,
        'expiry_month'  => $request->expiry_month,
        'expiry_year'   => $request->expiry_year,
        'cvv'           => '***',
        'method'        => 'card',
    ]);

    $user->notify(new GeneralNotification(
        "Top-up Successful 🎉",
        "You topped up ₦{$request->amount} to your wallet using card ending {$maskedCard}. Ref: {$reference}"
    ));

    return response()->json([
        'data' => [
            'message'   => 'Top-up successful',
            'balance'   => $balance->amount,
            'reference' => $reference,
        ]
    ], 200);
}


// // ── Currency Fee Lookup ─────────────────────────────────────────────────

public function getCurrencyFee(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated',
            'code'    => 'UNAUTHENTICATED',
            'data'    => null,
        ], 401);
    }

    $request->validate([
        'currency' => 'required|string|in:' . implode(',', \App\Models\UserCurrencyFee::CURRENCIES),
        'type'     => 'nullable|string|in:collection,payout',
        'amount'   => 'nullable|numeric|min:0',
    ]);

    $currency = strtoupper($request->currency);
    $type     = $request->type;
    $amount   = $request->filled('amount') ? (float) $request->amount : null;

    $userFee = \App\Models\UserCurrencyFee::where('user_id', $user->id)
        ->where('currency', $currency)
        ->first();

    Log::info('[CurrencyFee Lookup]', [
        'user_id'  => $user->id,
        'currency' => $currency,
        'type'     => $type,
        'amount'   => $amount,
        'found'    => (bool) $userFee,
    ]);

    if (!$userFee) {
        $msg = "No fee settings found for {$currency}. Contact your marketer.";
        return response()->json([
            'success' => false,
            'message' => $msg,
            'code'    => 'FEE_SETTINGS_NOT_FOUND',
            'data'    => null,
        ], 404);
    }

    $build = function (string $side) use ($userFee, $currency, $amount) {
        $enabled = (bool) $userFee->{"{$side}_enabled"};
        $min     = $userFee->{"{$side}_min"};
        $max     = $userFee->{"{$side}_max"};

        $out = [
            'enabled'      => $enabled,
            'min'          => $min,
            'max'          => $max,
            'fee_label'    => $this->describeFee($userFee, $side, $currency), // e.g. "2.5 CAD" or "1.5%"
        ];

        if ($amount !== null) {
            $calc = $side === 'collection'
                ? $userFee->calcCollectionFee($amount)
                : $userFee->calcPayoutFee($amount);

            $out['amount']     = $amount;
            $out['fee']        = $calc;
            $out['net_amount'] = round($amount - $calc, 2);
        }

        return $out;
    };

    $data = ['currency' => $currency];

    if ($type === 'collection') {
        $data['collection'] = $build('collection');
    } elseif ($type === 'payout') {
        $data['payout'] = $build('payout');
    } else {
        $data['collection'] = $build('collection');
        $data['payout']     = $build('payout');
    }

    return response()->json([
        'success' => true,
        'message' => 'Fee settings retrieved successfully.',
        'code'    => 'FEE_SETTINGS_FOUND',
        'data'    => $data,
    ], 200);
}

// ── Helper: human-readable fee, no percent/fixed leaked ─────────────────

private function describeFee(\App\Models\UserCurrencyFee $userFee, string $side, string $currency): string
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


// ── Currency Fee Lookup (Collection only) ────────────────────────────────

public function getCurrencyFees(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated',
            'code'    => 'UNAUTHENTICATED',
            'data'    => null,
        ], 401);
    }

    $userFees = \App\Models\UserCurrencyFee::where('user_id', $user->id)->get();

    Log::info('[CurrencyFee Lookup]', [
        'user_id' => $user->id,
        'count'   => $userFees->count(),
    ]);

    if ($userFees->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No fee settings found. Contact your marketer.',
            'code'    => 'FEE_SETTINGS_NOT_FOUND',
            'data'    => null,
        ], 404);
    }

    $data = $userFees->map(function ($userFee) {
        return [
            'currency'  => $userFee->currency,
            'enabled'   => (bool) $userFee->collection_enabled,
            'min'       => $userFee->collection_min,
            'max'       => $userFee->collection_max,
            'fee_label' => $this->describeFees($userFee, 'collection', $userFee->currency),
        ];
    })->values();

    return response()->json([
        'success' => true,
        'message' => 'Collection fee settings retrieved successfully.',
        'code'    => 'FEE_SETTINGS_FOUND',
        'data'    => $data,
    ], 200);
}

// ── Helper: human-readable fee, no percent/fixed leaked ─────────────────

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

}
