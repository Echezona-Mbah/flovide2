<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\TransactionHistory;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AddMoneyController extends Controller
{
public function topupWithCard(Request $request)
{
    $personal = auth('personal-api')->user();
    if (!$personal) {
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

    $balance = Balance::where('personal_id', $personal->id)
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

    $balance->amount += $request->amount;
    $balance->save();

    TransactionHistory::create([
        'personal_id'  => $personal->id,
        'type'         => 'topup',
        'amount'       => $request->amount,
        'status'       => 'successful',
        'method'       => 'card',
        'reference'    => $reference,
        'card_number'  => '**** **** **** ' . $maskedCard,
        'expiry_month' => $request->expiry_month, 
        'expiry_year'  => $request->expiry_year, 
        // 'cvv'       => null, // ← do NOT store CVV
    ]);

    $personal->notify(new GeneralNotification(
        "Top-up Successful 🎉",
        "You topped up {$request->amount} to your wallet using card ending {$maskedCard}. Ref: {$reference}"
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
