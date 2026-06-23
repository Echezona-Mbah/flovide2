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
    return view('business.interac_details');
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

    $payload = [
        'email'  => $request->email,
        'amount' => $request->amount,
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
        'user_id'          => $user->id,
        'balance_id'       => $balance->id,
        'payment_provider' => 'interac',
        'transaction_type' => 'payment',
        'method'           => 'credit',
        'payment_method'   => 'auto',
        'sender'           => $request->email,
        'amount'           => $request->amount,
        'currency'         => $balance->currency,
        'status'           => 'pending',
        'reference'        => $responseData['reference'],
        'payment_reference'=> $responseData['reference'],
        'order_id'         => $responseData['transaction_id'],
    ]);

    $user->notify(new GeneralNotification(
        "Interac Top-up Submitted 🎉",
        "Your Interac deposit of {$request->amount} {$balance->currency} has been submitted. Ref: {$responseData['reference']}"  // ← fixed double $
    ));

    return response()->json([
        'success' => true,
        'message' => 'Interac deposit submitted successfully.',
        'code'    => 'INTERAC_TOPUP_SUBMITTED',
        'data'    => $transaction,
    ], 200);
}





// public function topupWithInterac(Request $request, BlaaizService $blaaiz)
//     {
//         $user = Auth::user();
//         if (!$user) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Unauthenticated',
//                 'code'    => 'UNAUTHENTICATED',
//                 'data'    => null,
//             ], 401);
//         }

//         // Log::info('[Interac Business] Request received', $request->all());

//         $request->validate([
//             'balance'         => 'required',
//             'amount'          => 'required|numeric|min:1',
//             'email'           => 'required|email',
//             'interac_type'    => 'required|string|in:standard,auto',
//             'security_answer' => 'nullable|string|max:255',
//         ]);

//         if ($request->interac_type === 'standard' && empty($request->security_answer)) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Security answer is required for standard Interac transfers.',
//                 'code'    => 'INTERAC_SECURITY_ANSWER_REQUIRED',
//                 'data'    => null,
//             ], 422);
//         }

//         $balance = Balance::where('user_id', $user->id)
//             ->where('id', $request->balance)
//             ->first();

//         if (!$balance) {
//             // Log::warning('[Interac Business] Balance not found', [
//             //     'user_id'    => $user->id,
//             //     'balance_id' => $request->balance,
//             // ]);
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Balance account not found.',
//                 'code'    => 'BALANCE_NOT_FOUND',
//                 'data'    => null,
//             ], 404);
//         }

//         $referenceNumber = 'ITC-' . strtoupper(Str::random(10));
//         // Log::info('[Interac Business] Generated reference', ['reference' => $referenceNumber]);

//         $payload = [
//             'reference_number' => $referenceNumber,
//             'email'            => $request->email,
//         ];

//         if ($request->interac_type === 'standard' && !empty($request->security_answer)) {
//             $payload['security_answer'] = $request->security_answer;
//             // Log::info('[Interac Business] Security answer included');
//         }

//         // Log::info('[Interac Business] Sending payload to Blaaiz', $payload);

//         $response = $blaaiz->acceptInteracMoneyRequest($payload);

//         // Log::info('[Interac Business] Blaaiz response', [
//         //     'success' => $response['success'],
//         //     'status'  => $response['status'],
//         //     'data'    => $response['data'],
//         // ]);

//         if (!$response['success']) {
//             // Log::warning('[Interac Business] Blaaiz rejected request', $response['data'] ?? []);

//             $errorMsg = $response['data']['message']
//                 ?? $response['data']['error_description']
//                 ?? 'Interac request failed. Please try again.';

//             return response()->json([
//                 'success' => false,
//                 'message' => $errorMsg,
//                 'code'    => 'INTERAC_REQUEST_FAILED',
//                 'data'    => $response['data'] ?? null,
//             ], $response['status']);
//         }

//         $responseData = $response['data'] ?? [];

//         $transaction = TransactionHistory::create([
//             'user_id'          => $user->id,
//             'balance_id'       => $balance->id,
//             'payment_provider' => 'interac',
//             'transaction_type' => 'payment',
//             'method'           => 'credit',
//             'payment_method'   => $request->interac_type,
//             'sender'           => $request->email,
//             'amount'           => $request->amount,
//             'currency'         => $balance->currency,
//             'status'           => 'pending',
//             'reference'        => $referenceNumber,
//             'payment_reference'=> $responseData['reference'] ?? $referenceNumber,
//             'order_id'         => $responseData['id'] ?? null,
//         ]);

//         // Log::info('[Interac Business] Transaction saved', [
//         //     'transaction_id' => $transaction->id,
//         //     'reference'      => $referenceNumber,
//         //     'amount'         => $request->amount,
//         //     'currency'       => $balance->currency,
//         // ]);

//         $user->notify(new GeneralNotification(
//             "Interac Top-up Submitted 🎉",
//             "Your Interac deposit of {$request->amount} {$balance->currency} has been submitted. Ref: {$referenceNumber}"
//         ));

//         return response()->json([
//             'success' => true,
//             'message' => 'Interac deposit submitted successfully.',
//             'code'    => 'INTERAC_TOPUP_SUBMITTED',
//             'data'    => $transaction,
//         ], 200);
//     }






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


}
