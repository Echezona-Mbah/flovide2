<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
   public function personalTransactions(Request $request)
{
    $personalId = auth('personal-api')->id();

    $transactions = TransactionHistory::where('personal_id', $personalId)
        ->latest()
        ->take(4)
        ->get()
        ->map(function ($t) {
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
        });

    return $request->expectsJson()
        ? response()->json([
            'success' => true,
            'message' => 'Personal transactions retrieved successfully',
            'code' => 'PERSONAL_TRANSACTIONS_FETCHED',
            'data' => $transactions
        ], 200)
        : view('transactions.personal', compact('transactions'));
}




  public function filterPersonalTransactions(Request $request, $status)
{
    $personalId = auth('personal-api')->id();

    $transactions = TransactionHistory::where('personal_id', $personalId)
        ->where(function ($query) use ($status) {
            if (in_array($status, ['pending', 'successful', 'failed'])) {
                $query->where('status', $status);
            } elseif (in_array($status, ['withdraw', 'deposit'])) {
                $query->where('type', $status);
            }
        })
        ->latest()
        ->get();

    return $request->expectsJson()
        ? response()->json([
            'success' => true,
            'message' => 'Filtered transactions retrieved successfully',
            'code' => 'TRANSACTIONS_FILTERED',
            'data' => [
                'filter' => $status,
                'transactions' => $transactions
            ]
        ], 200)
        : view('transactions.filter', compact('transactions', 'status'));
}


public function showAllTransactions(Request $request)
{
    $personal = auth('personal-api')->user();

    if (! $personal) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
            'code' => 'UNAUTHORIZED',
            'data' => null
        ], 401);
    }

    $transactions = TransactionHistory::where('personal_id', $personal->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'success' => true,
        'message' => 'Personal transactions retrieved successfully.',
        'code' => 'PERSONAL_TRANSACTIONS_FETCHED',
        'data' => $transactions
    ], 200);
}



}
