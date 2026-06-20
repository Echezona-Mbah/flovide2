<?php

namespace App\Http\Controllers\Business;

use App\Models\TeamMembers;
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

    // public function transaction()
    // {
    //     $transactions = TransactionHistory::where('user_id', Auth::id())
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     if (request()->wantsJson()) {
    //         return response()->json([
    //             'message' => 'Transactions retrieved successfully.',
    //             'data' => $transactions
    //         ]);
    //     } else {

    //         $latestTransaction = $transactions->first();
    //         // return view('business.transactionHistory', ['transactions' => $transactions]);
    //         return view('business.transactionHistory', compact('transactions', 'latestTransaction'));
    //     }
    // }


   public function transaction()
{
    $user = auth()->user();
    $team = TeamMembers::where('user_id', $user->id)->first();
    $ownerId = $team ? $team->owner_id : $user->id;

    $transactions = TransactionHistory::where('user_id', $ownerId)
    ->orderBy('created_at', 'desc')
    ->paginate(12);


    if (request()->wantsJson()) {
        $transactions = $transactions->map(function ($t) {
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

        return response()->json([
            'success' => true,
            'message' => 'Transactions retrieved successfully.',
            'code' => 'TRANSACTIONS_FETCHED',
            'data' => $transactions
        ], 200);
    }

    $latestTransaction = $transactions->first();
    return view('business.transactionHistory', compact('transactions', 'latestTransaction'));
}




public function showAllTransactions()
{
    $transactions = TransactionHistory::orderBy('created_at', 'desc')->get();

    return response()->json([
        'success' => true,
        'message' => 'All transactions retrieved successfully.',
        'code' => 'ALL_TRANSACTIONS_FETCHED',
        'data' => $transactions
    ], 200);
}




public function UserTransaction($id)
{
        $user = auth()->user();
    $team = TeamMembers::where('user_id', $user->id)->first();
    $ownerId = $team ? $team->owner_id : $user->id;
    $transaction = TransactionHistory::where('user_id', $ownerId)
        ->where('id', $id)
        ->first();

    if (!$transaction) {
        return response()->json([
            'success' => false,
            'message' => 'Transaction not found.',
            'code' => 'TRANSACTION_NOT_FOUND',
            'data' => null
        ], 404);
    }

    $data = [
        'type'      => $transaction->type,
        'date'      => $transaction->created_at->format('Y-m-d H:i:s'),
        'sender'    => $transaction->sender ?? 'N/A',
        'recipient' => $transaction->recipient ?? 'N/A',
        'amount'    => $transaction->currency_symbol . number_format($transaction->amount, 2),
        'currency'  => $transaction->currency,
        'status'    => $transaction->status,
        'reference' => $transaction->reference,
        'method' => $transaction->method,
        'recipient_details' => [
            'alias'          => $transaction->recipient_alias,
            'account_name'   => $transaction->recipient_account_name,
            'account_number' => $transaction->recipient_account_number,
            'bank_name'      => $transaction->recipient_bank_name,
            'bank_currency'  => $transaction->recipient_bank_currency,
            'recipient_amount'  => $transaction->recipient_amount,
            'fees'  => $transaction->fees,
        ]
    ];

    return response()->json([
        'success' => true,
        'message' => 'User Transaction retrieved successfully.',
        'code' => 'TRANSACTION_FETCHED',
        'data' => $data
    ], 200);
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
        'success' => true,
        'message' => 'Transaction recorded successfully.',
        'code' => 'TRANSACTION_CREATED',
        'data' => $transaction
    ], 201);
}

}
