<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AddMoneyController extends Controller
{

      public function index(Request $request)
    {
        // $ownerId = session('owner_id');
        $user = Auth::user();
        $balances = Balance::where('user_id',$user->id)->get();
    
        return view('business.add_money', compact('balances'));
    }



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
