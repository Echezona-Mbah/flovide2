<?php

namespace App\Http\Controllers\business;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidAmountBasedOnType;
use App\Rules\ValidTransactionReference;

// use Carbon\Carbon;

class TransactionHistoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:' . (request()->is('api/*') ? 'sanctum' : 'web'));
    }

    public function transaction()
    {
        $transactions = TransactionHistory::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Transactions retrieved successfully.',
                'data' => $transactions
            ]);
        } else {

            $latestTransaction = $transactions->first();
            // return view('business.transactionHistory', ['transactions' => $transactions]);
            return view('business.transactionHistory', compact('transactions', 'latestTransaction'));
        }
    }


    public function showAllTransactions()
    {
        $transactions = TransactionHistory::orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'All transactions retrieved successfully.',
            'data' => $transactions
        ]);
    }


    public function UserTransaction($id)
    {
        $transaction = TransactionHistory::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found.'
            ], 404);
        }

        return response()->json([
            'message' => 'User Transaction retrieved successfully.',
            'data' => $transaction
        ]);
    }


    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:credit,debit'],
            'sender' => ['required', 'string', 'max:255'],
            'sender_id' => ['nullable', 'exists:users,id'],
            'recipient' => ['required', 'string', 'max:255'],
            'recipient_id' => ['nullable', 'exists:users,id'],
            'method' => ['required', 'in:transfer,withdrawal,deposit'],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:1000000000',
                new ValidAmountBasedOnType($request->type, $request->currency)
            ],
            'currency' => ['required', 'string', 'in:USD,GBP,EUR,NGN,CAD,AUD'],
            'status' => ['required', 'in:pending,successful,failed'],
            'reference' => [
                'required',
                'string',
                'unique:transactions_history,reference',
                new ValidTransactionReference()
            ],
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
