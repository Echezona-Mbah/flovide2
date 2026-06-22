<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Personal;

class UserTransactionHistoryController extends Controller
{
    public function history(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $search = $request->input('search');

        $query = TransactionHistory::where('user_id', $user->id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'LIKE', "%{$search}%")
                  ->orWhere('amount', 'LIKE', "%{$search}%")
                  ->orWhere('type', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%")
                  ->orWhere('method', 'LIKE', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(15)->appends(['search' => $search]);

        return view('admin.history', compact('transactions', 'user'));
    }


    public function personalHistory(Request $request, $id)
    {
        $personal = Personal::findOrFail($id);
        $search = $request->input('search');

        $query = TransactionHistory::where('personal_id', $personal->id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'LIKE', "%{$search}%")
                  ->orWhere('amount', 'LIKE', "%{$search}%")
                  ->orWhere('type', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%")
                  ->orWhere('method', 'LIKE', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(15)->appends(['search' => $search]);

        return view('admin.personal_history', compact('transactions', 'personal'));
    }
}
