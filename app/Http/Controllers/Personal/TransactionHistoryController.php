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
                'status' => 'success',
                'data'   => $transactions
            ])
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
              'data'=>[
                  'status' => 'success',
                'filter' => $status,
                'data'   => $transactions
              ]
            ])
            : view('transactions.filter', compact('transactions', 'status'));
    }

}
