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
                'type'      => $t->type,
                'date'      => $t->created_at->format('Y-m-d H:i:s'),
                'sender'    => $t->sender ?? 'N/A',
                'recipient' => $t->recipient ?? 'N/A',
                'amount'    => $t->currency_symbol . number_format($t->amount, 2),
                'currency'  => $t->currency,
                'status'    => $t->status,
                'reference' => $t->reference,
                'recipient_details' => [
                    'alias'          => $t->recipient_alias,
                    'account_name'   => $t->recipient_account_name,
                    'account_number' => $t->recipient_account_number,
                    'bank_name'      => $t->recipient_bank_name,
                    'bank_currency'  => $t->recipient_bank_currency,
                ]
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


}
