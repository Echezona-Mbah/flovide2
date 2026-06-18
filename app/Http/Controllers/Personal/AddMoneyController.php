<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Balance;
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

    Log::info('[Interac Personal] Authenticated user', [   // ← fix: was passing $personal object
        'personal_id' => $personal->id,
        'email'       => $personal->email,
    ]);

    $request->validate([
        'balance' => 'required|string',
        'amount'  => 'required|numeric|min:1',
        'email'   => 'required|email',
    ]);

    $balance = Balance::where('personal_id', $personal->id)
        ->where('id', $request->balance)
        ->first();

    Log::info('[Interac Personal] Balance lookup', [       // ← fix: was passing $balance object
        'found'      => (bool) $balance,
        'balance_id' => $request->balance,
        'personal_id'=> $personal->id,
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
        'personal_id'      => $personal->id,
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

    $personal->notify(new GeneralNotification(
        "Interac Top-up Submitted 🎉",
        "Your Interac deposit of {$request->amount} {$balance->currency} has been submitted. Ref: {$responseData['reference']}"
    ));

    return response()->json([
        'success' => true,
        'message' => 'Interac deposit submitted successfully.',
        'code'    => 'INTERAC_TOPUP_SUBMITTED',
        'data'    => $transaction,
    ], 200);
}

// public function topupWithInteracc(Request $request, BlaaizService $blaaiz)
//     {
//         $personal = auth('personal-api')->user();
//         if (!$personal) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Unauthenticated',
//                 'code'    => 'UNAUTHENTICATED',
//                 'data'    => null,
//             ], 401);
//         }

//          Log::info('[Interac Personal] Request received', $request->all());

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

//         $balance = Balance::where('personal_id', $personal->id)
//             ->where('id', $request->balance)
//             ->first();


//         if (!$balance) {
//             // Log::warning('[Interac Personal] Balance not found', [
//             //     'personal_id' => $personal->id,
//             //     'balance_id'  => $request->balance,
//             // ]);
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Balance account not found.',
//                 'code'    => 'BALANCE_NOT_FOUND',
//                 'data'    => null,
//             ], 404);
//         }

//         $referenceNumber = 'ITC-' . strtoupper(Str::random(10));
//         // Log::info('[Interac Personal] Generated reference', ['reference' => $referenceNumber]);

//         $payload = [
//             'reference_number' => $referenceNumber,
//             'email'            => $request->email,
//         ];

//         if ($request->interac_type === 'standard' && !empty($request->security_answer)) {
//             $payload['security_answer'] = $request->security_answer;
//             // Log::info('[Interac Personal] Security answer included');
//         }

//         Log::info('[Interac Personal] Sending payload to Blaaiz', $payload);

//         $response = $blaaiz->acceptInteracMoneyRequest($payload);

//         // Log::info('[Interac Personal] Blaaiz response', [
//         //     'success' => $response['success'],
//         //     'status'  => $response['status'],
//         //     'data'    => $response['data'],
//         // ]);

//         if (!$response['success']) {
//             // Log::warning('[Interac Personal] Blaaiz rejected request', $response['data'] ?? []);

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

//         // $balance->amount += $request->amount;
//         // $balance->save();

//         // Log::info('[Interac Personal] Balance credited', [
//         //     'balance_id'  => $balance->id,
//         //     'amount'      => $request->amount,
//         //     'new_balance' => $balance->amount,
//         // ]);

//         $responseData = $response['data'] ?? [];

//         $transaction = TransactionHistory::create([
//             'personal_id'      => $personal->id,
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

//         // Log::info('[Interac Personal] Transaction saved', [
//         //     'transaction_id' => $transaction->id,
//         //     'reference'      => $referenceNumber,
//         //     'amount'         => $request->amount,
//         //     'currency'       => $balance->currency,
//         // ]);

//         $personal->notify(new GeneralNotification(
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