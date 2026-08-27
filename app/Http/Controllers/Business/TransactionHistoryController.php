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

 

// public function transaction(Request $request)
// {
//     $user    = auth()->user();
//     $team    = TeamMembers::where('user_id', $user->id)->first();
//     $ownerId = $team ? $team->owner_id : $user->id;
//     $mode    = session('mode', 'live');

//     $search = $request->input('search');
//     $filter = $request->input('filter');

//     $transactions = TransactionHistory::where('user_id', $ownerId)
//         ->where('mode', $mode)
//         ->when($search, function ($q) use ($search) {
//             $q->where(function ($q2) use ($search) {
//                 $q2->where('reference', 'like', "%{$search}%")
//                    ->orWhere('sender', 'like', "%{$search}%")
//                    ->orWhere('recipient_account_name', 'like', "%{$search}%")
//                    ->orWhere('amount', 'like', "%{$search}%")
//                    ->orWhere('currency', 'like', "%{$search}%");
//             });
//         })
//         ->when($filter, function ($q) use ($filter) {
//             if (in_array($filter, ['credit', 'withdrawal', 'swap'])) {
//                 $q->where('type', $filter);
//             } elseif (in_array($filter, ['success', 'failed', 'pending'])) {
//                 $q->where('status', $filter);
//             }
//         })
//         ->orderBy('created_at', 'desc')
//         ->paginate(12)
//         ->withQueryString();

//     if ($request->wantsJson()) {
//         return response()->json([
//             'success' => true,
//             'message' => 'Transactions retrieved successfully.',
//             'code'    => 'TRANSACTIONS_FETCHED',
//             'data'    => $transactions->map(function ($t) {
//                 return [
//                     'type'             => $t->type,
//                     'date'             => $t->created_at->format('Y-m-d H:i:s'),
//                     'sender'           => $t->sender ?? 'N/A',
//                     'recipient'        => $t->recipient_account_name ?? 'N/A',
//                     'amount'           => number_format($t->amount, 2),
//                     'currency'         => $t->currency,
//                     'status'           => $t->status,
//                     'reference'        => $t->reference,
//                     'method'           => $t->method,
//                     'mode'             => $t->mode,
//                     'fees'             => $t->fees,
//                     'swap_from_currency' => $t->swap_from_currency,
//                     'swap_to_currency'   => $t->swap_to_currency,
//                     'swap_from_amount'   => $t->swap_from_amount,
//                     'swap_to_amount'     => $t->swap_to_amount,
//                     'swap_rate'          => $t->swap_rate,
//                     'recipient_details' => [
//                         'account_name'     => $t->recipient_account_name,
//                         'account_number'   => $t->recipient_account_number,
//                         'bank_name'        => $t->recipient_bank_name,
//                         'bank_currency'    => $t->recipient_bank_currency,
//                         'recipient_amount' => $t->recipient_amount,
//                         'fees'             => $t->fees,
//                     ],
//                 ];
//             }),
//         ], 200);
//     }

//     return view('business.transactionHistory', compact('transactions', 'mode'));
// }
public function transaction(Request $request)
{
    $user    = auth()->user();
    $team    = TeamMembers::where('user_id', $user->id)->first();
    $ownerId = $team ? $team->owner_id : $user->id;
    $mode    = session('mode', 'live');

    $search = $request->input('search');
    $filter = $request->input('filter');

    $allowedPerPage = [12, 25, 50, 100, 250, 500];
    $perPage = (int) $request->input('per_page', 12);

    if (!in_array($perPage, $allowedPerPage, true)) {
        $perPage = 12;
    }

    $transactions = TransactionHistory::where('user_id', $ownerId)
        ->where('mode', $mode)
        ->when($search, function ($q) use ($search) {
            $q->where(function ($q2) use ($search) {
                $q2->where('reference', 'like', "%{$search}%")
                   ->orWhere('sender', 'like', "%{$search}%")
                   ->orWhere('recipient_account_name', 'like', "%{$search}%")
                   ->orWhere('amount', 'like', "%{$search}%")
                   ->orWhere('currency', 'like', "%{$search}%");
            });
        })
        ->when($filter, function ($q) use ($filter) {
            if (in_array($filter, ['credit', 'withdrawal', 'swap'])) {
                $q->where('type', $filter);
            } elseif (in_array($filter, ['success', 'failed', 'pending'])) {
                $q->where('status', $filter);
            }
        })
        ->orderBy('created_at', 'desc')
        ->paginate($perPage)
        ->withQueryString();

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Transactions retrieved successfully.',
            'code'    => 'TRANSACTIONS_FETCHED',
            'data'    => $transactions->map(function ($t) {
                return [
                    'type'             => $t->type,
                    'date'             => $t->created_at->format('Y-m-d H:i:s'),
                    'sender'           => $t->sender ?? 'N/A',
                    'recipient'        => $t->recipient_account_name ?? 'N/A',
                    'amount'           => number_format($t->amount, 2),
                    'currency'         => $t->currency,
                    'status'           => $t->status,
                    'reference'        => $t->reference,
                    'method'           => $t->method,
                    'mode'             => $t->mode,
                    'fees'             => $t->fees,
                    'swap_from_currency' => $t->swap_from_currency,
                    'swap_to_currency'   => $t->swap_to_currency,
                    'swap_from_amount'   => $t->swap_from_amount,
                    'swap_to_amount'     => $t->swap_to_amount,
                    'swap_rate'          => $t->swap_rate,
                    'recipient_details' => [
                        'account_name'     => $t->recipient_account_name,
                        'account_number'   => $t->recipient_account_number,
                        'bank_name'        => $t->recipient_bank_name,
                        'bank_currency'    => $t->recipient_bank_currency,
                        'recipient_amount' => $t->recipient_amount,
                        'fees'             => $t->fees,
                    ],
                ];
            }),
        ], 200);
    }

    return view('business.transactionHistory', compact('transactions', 'mode', 'perPage', 'allowedPerPage'));
}

public function showAllTransactions()
{
     $user    = auth()->user();
        $team    = TeamMembers::where('user_id', $user->id)->first();
        $ownerId = $team ? $team->owner_id : $user->id;
        $mode    = session('mode', 'live');

        $transactions = TransactionHistory::where('user_id', $ownerId)
            ->where('mode', $mode)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

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
            'type'         => ['required', 'in:credit,debit'],
            'sender'       => ['required', 'string', 'max:255'],
            'sender_id'    => ['nullable', 'exists:users,id'],
            'recipient'    => ['required', 'string', 'max:255'],
            'recipient_id' => ['nullable', 'exists:users,id'],
            'method'       => ['required', 'in:transfer,withdrawal,deposit'],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:1000000000',
                new ValidAmountBasedOnType($request->type, $request->currency)
            ],
            'currency' => ['required', 'string', 'in:USD,GBP,EUR,NGN,CAD,AUD'],
            'status'   => ['required', 'in:pending,successful,failed'],
            'reference' => [
                'required',
                'string',
                'unique:transactions_history,reference',
                new ValidTransactionReference()
            ],
        ]);

        $transaction = TransactionHistory::create(array_merge($validated, [
            'user_id' => Auth::user()->id,
            'mode'    => session('mode', 'live'),
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Transaction recorded successfully.',
            'code'    => 'TRANSACTION_CREATED',
            'data'    => $transaction
        ], 201);
    }

}
