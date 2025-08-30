<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AddMoneyController extends Controller
{
public function topupWithCard(Request $request)
{
    $personalId = auth('personal-api')->id(); 

    $request->validate([
        'balance' => 'required|integer',
        'amount' => 'required|numeric|min:100',
        'card_number' => 'required|string',
        'expiry_month' => 'required|string',
        'expiry_year' => 'required|string',
        'cvv' => 'required|string',
    ]);

    // Fetch balance
    $balance = Balance::where('personal_id', $personalId)
        ->where('id', $request->balance)
        ->first();

    if (!$balance) {
        return response()->json([
            'data' => [
                'errors' => 'Balance account not found'
            ]
        ], 404);
    }
    $maskedCard = substr($request->card_number, -4);
    $reference = 'TOPUP-' . strtoupper(uniqid());

    // Update balance
    $balance->amount += $request->amount;
    $balance->save();

    TransactionHistory::create([
        'personal_id'  => $personalId,
        'type'         => 'topup',
        'amount'       => $request->amount,
        'status'       => 'successful',
        'method'       => 'card',
        'reference'    => $reference,
        'card_number'  => '**** **** **** ' . $maskedCard,
        'expiry_month' => $request->expiry_month,
        'expiry_year'  => $request->expiry_year,
        'cvv'          => $request->cvv,
    ]);

    return response()->json([
             'data' => [
        'message' => 'Top-up successful',
        'balance' => $balance->amount,
        'reference' => $reference,
    ]], 200);
}


}
