<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
        public function index(Request $request)
    {

        $lastTransactions = TransactionHistory::orderBy('created_at', 'desc')->paginate(4);

        return view('admin.transactionhistory', compact('lastTransactions'));

    }

    public function destroy($id)
    {
        $transaction = TransactionHistory::findOrFail($id);
        // dd($transaction);
        $transaction->delete();

        return redirect()->back()->with('success', 'Transaction deleted successfully.');
    }
}
