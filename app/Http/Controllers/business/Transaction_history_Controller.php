<?php

namespace App\Http\Controllers\business;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\Auth;

class Transaction_history_Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    
    public function showalltransaction()
    {
        $transactions = TransactionHistory::where('user_id', Auth::user()->id)->orderBy('time', 'desc')->get();

        return response()->json([
            'message' => 'Transactions retrieved successfully.',
            'data' => $transactions
        ]);
    }

    public function storetransaction(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:credit,debit',
            'date' => 'required|date',
            'sender' => 'nullable|string|max:255',
            'recipient' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0|max:1000000000',
            'status' => 'required|in:pending,successful,failed',
            'reference' => 'required|string|unique:transactions_history,reference',
            'time' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $transaction = TransactionHistory::create(array_merge($validated, [
            'user_id' => Auth::user()->id
        ]));

        return response()->json([
            'message' => 'Transaction recorded successfully.',
            'data' => $transaction
        ], 201);
    }
}
