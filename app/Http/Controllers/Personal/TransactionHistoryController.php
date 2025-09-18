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
            ->get();

        return $request->expectsJson()
            ? response()->json([
                'data'=>[
                'status' => 'success',
                'data'   => $transactions
                ]
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
