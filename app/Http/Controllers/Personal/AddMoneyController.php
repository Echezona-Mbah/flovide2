<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Currency;
use App\Models\TransactionHistory;
use App\Notifications\GeneralNotification;
use App\Services\BlaaizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AddMoneyController extends Controller
{




    // ── Interac ──────────────────────────────────────────────────────────────


    public function topupWithInteracc(Request $request, BlaaizService $blaaiz)
{
    //dd('sssssss');
    Log::info('[Interac Personal] Request received', $request->all());

    $personal = auth('personal-api')->user();
    if (!$personal) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated',
            'code'    => 'UNAUTHENTICATED',
            'data'    => null,
        ], 401);
    }

    Log::info('[Interac Personal] Authenticated user', [
        'personal_id' => $personal->id,
        'email'       => $personal->email,
    ]);

    $request->validate([
        'balance' => 'required|string',
        'amount'  => 'required|numeric|min:1',
        'email'   => 'required|email',
        'transfer_fee'  => 'nullable|numeric',
    ]);

    $balance = Balance::where('personal_id', $personal->id)
        ->where('id', $request->balance)
        ->first();

    Log::info('[Interac Personal] Balance lookup', [
        'found'       => (bool) $balance,
        'balance_id'  => $request->balance,
        'personal_id' => $personal->id,
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
        return response()->json([
            'success' => false,
            'message' => $msg,
            'code'    => 'INTERAC_CAD_ONLY',
            'data'    => null,
        ], 422);
    }

    // ── Fee — from global Currency config (personal has no per-user fee row) ──
    $currencyRow = \App\Models\Currency::where('is_active', true)
        ->where(function ($q) use ($currency) {
            $q->where('code', $currency)
              ->orWhere('currency_code', $currency);
        })
        ->first();

    Log::info('[Interac Personal] Currency fee lookup', [
        'currency' => $currency,
        'found'    => (bool) $currencyRow,
    ]);

    if (!$currencyRow) {
        $msg = "Collection is not available for {$currency}.";
        return response()->json([
            'success' => false,
            'message' => $msg,
            'code'    => 'COLLECTION_DISABLED',
            'data'    => null,
        ], 422);
    }

    if ($currencyRow->min_amount > 0 && $amount < $currencyRow->min_amount) {
        $msg = "Minimum top-up for {$currency} is " . number_format($currencyRow->min_amount, 2);
        return response()->json([
            'success' => false,
            'message' => $msg,
            'code'    => 'BELOW_COLLECTION_MIN',
            'data'    => null,
        ], 422);
    }

    if ($currencyRow->max_amount > 0 && $amount > $currencyRow->max_amount) {
        $msg = "Maximum top-up for {$currency} is " . number_format($currencyRow->max_amount, 2);
        return response()->json([
            'success' => false,
            'message' => $msg,
            'code'    => 'ABOVE_COLLECTION_MAX',
            'data'    => null,
        ], 422);
    }

    // Flat fee, no percent for personal
    $platformFee = (float) $currencyRow->collection_fee;
    $netAmount   = round($amount - $platformFee, 2);

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

    Log::info('[Interac Personal] Fee computed', [
        'amount'       => $amount,
        'platform_fee' => $platformFee,
        'net_amount'   => $netAmount,
    ]);

    $payload = [
        'email'  => $request->email,
        'amount' => $amount,
    ];

    $response = $blaaiz->initiateInteracMoneyRequest($payload);

    Log::info('[Interac Personal] Blaaiz response', [
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
        'personal_id'       => $personal->id,
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

    $personal->notify(new GeneralNotification(
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


//   public function topupWithInteracc(Request $request, BlaaizService $blaaiz)
// {
//     Log::info('[Interac Personal] Request received', $request->all());

//     $personal = auth('personal-api')->user();
//     if (!$personal) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Unauthenticated',
//             'code'    => 'UNAUTHENTICATED',
//             'data'    => null,
//         ], 401);
//     }

//     Log::info('[Interac Personal] Authenticated user', [   // ← fix: was passing $personal object
//         'personal_id' => $personal->id,
//         'email'       => $personal->email,
//     ]);

//     $request->validate([
//         'balance' => 'required|string',
//         'amount'  => 'required|numeric|min:1',
//         'email'   => 'required|email',
//         'transfer_fee'  => 'nullable|numeric',
//     ]);

//     $balance = Balance::where('personal_id', $personal->id)
//         ->where('id', $request->balance)
//         ->first();

//     Log::info('[Interac Personal] Balance lookup', [       // ← fix: was passing $balance object
//         'found'      => (bool) $balance,
//         'balance_id' => $request->balance,
//         'personal_id'=> $personal->id,
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

//     Log::info('[Interac Personal] Blaaiz response', [
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
//         'personal_id'      => $personal->id,
//         'balance_id'       => $balance->id,
//         'payment_provider' => 'interac',
//         'transaction_type' => 'payment',
//         'type'           => 'credit',
//         'method'           => 'credit',
//         'payment_method'   => 'auto',
//         'sender'           => $request->email,
//         'amount'           => $request->amount,
//         'currency'         => $balance->currency,
//         'status'           => 'pending',
//         'reference'        => $responseData['reference'],
//         'payment_reference'=> $responseData['reference'],
//         'order_id'         => $responseData['transaction_id'],
//     ]);

//     $personal->notify(new GeneralNotification(
//         "Interac Top-up Submitted 🎉",
//         "Your Interac deposit of {$request->amount} {$balance->currency} has been submitted. Ref: {$responseData['reference']}"
//     ));

//     return response()->json([
//         'success' => true,
//         'message' => 'Interac deposit submitted successfully.',
//         'code'    => 'INTERAC_TOPUP_SUBMITTED',
//         'data'    => $transaction,
//     ], 200);
// }


public function getCurrencyFee(Request $request)
{

    $request->validate([
        'currency' => 'required|string',
        'type'     => 'nullable|string|in:collection',
        'amount'   => 'nullable|numeric|min:0',
    ]);

    $currency = strtoupper($request->currency);
    $type     = $request->type;
    $amount   = $request->filled('amount') ? (float) $request->amount : null;



    // 2) Personal user — fall back to global Currency config
    $currencyRow = \App\Models\Currency::where('is_active', true)
        ->where(function ($q) use ($currency) {
            $q->where('code', $currency)
              ->orWhere('currency_code', $currency);
        })
        ->first();

    Log::info('[CurrencyFee Lookup - personal fallback]', [
        'currency' => $currency,
        'found'    => (bool) $currencyRow,
    ]);

    if (!$currencyRow) {
        $msg = "No fee settings found for {$currency}.";
        return response()->json([
            'success' => false,
            'message' => $msg,
            'code'    => 'FEE_SETTINGS_NOT_FOUND',
            'data'    => null,
        ], 404);
    }

    if ($type === 'payout') {
        $msg = "Payout is not available for personal accounts.";
        return response()->json([
            'success' => false,
            'message' => $msg,
            'code'    => 'PAYOUT_NOT_SUPPORTED',
            'data'    => null,
        ], 422);
    }

    $collection = [
        'enabled'   => (bool) $currencyRow->is_active,
        'min'       => (float) $currencyRow->min_amount,
        'max'       => (float) $currencyRow->max_amount,
        'fee_label' => $currencyRow->collection_fee > 0
            ? number_format($currencyRow->collection_fee, 2) . " {$currency} flat"
            : "No fee",
    ];

    if ($amount !== null) {
        $fee = (float) $currencyRow->collection_fee;
        $collection['amount']     = $amount;
        $collection['fee']        = $fee;
        $collection['net_amount'] = round($amount - $fee, 2);
    }

    return response()->json([
        'success' => true,
        'message' => 'Fee settings retrieved successfully.',
        'code'    => 'FEE_SETTINGS_FOUND',
        'data'    => [
            'currency'     => $currency,
            'account_type' => 'personal',
            'collection'   => $collection,
        ],
    ], 200);
}





    // public function topupWithCard(Request $request)
    // {
    //     $personal = auth('personal-api')->user();
    //     if (!$personal) {
    //         return response()->json(['data' => ['errors' => 'Unauthenticated']], 401);
    //     }

    //     $request->validate([
    //         'balance'      => 'required|integer',
    //         'amount'       => 'required|numeric|min:100',
    //         'card_number'  => 'required|string',
    //         'expiry_month' => 'required|string',
    //         'expiry_year'  => 'required|string',
    //         'cvv'          => 'required|string',
    //     ]);

    //     $balance = Balance::where('personal_id', $personal->id)
    //         ->where('id', $request->balance)
    //         ->first();

    //     if (!$balance) {
    //         return response()->json([
    //             'data' => ['errors' => 'Balance account not found']
    //         ], 404);
    //     }

    //     $digitsOnly = preg_replace('/\D/', '', $request->card_number);
    //     $maskedCard = substr($digitsOnly, -4);
    //     $reference  = 'TOPUP-' . strtoupper(uniqid());

    //     $balance->amount += $request->amount;
    //     $balance->save();

    //     TransactionHistory::create([
    //         'personal_id'      => $personal->id,
    //         'balance_id'       => $balance->id,
    //         'type'             => 'credit',
    //         'amount'           => $request->amount,
    //         'currency'         => $balance->currency,
    //         'status'           => 'success',
    //         'reference'        => $reference,
    //         'transaction_type' => 'topup_card',
    //         'card_number'      => '**** **** **** ' . $maskedCard,
    //         'expiry_month'     => $request->expiry_month,
    //         'expiry_year'      => $request->expiry_year,
    //         'cvv'              => '***',
    //         'method'           => 'card',
    //     ]);

    //     $personal->notify(new GeneralNotification(
    //         "Top-up Successful 🎉",
    //         "You topped up {$request->amount} to your wallet using card ending {$maskedCard}. Ref: {$reference}"
    //     ));

    //     return response()->json([
    //         'data' => [
    //             'message'   => 'Top-up successful',
    //             'balance'   => $balance->amount,
    //             'reference' => $reference,
    //         ]
    //     ], 200);
    // }

}