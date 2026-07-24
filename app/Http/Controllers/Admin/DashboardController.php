<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLoginLog;
use App\Models\Personal;
use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $totalPersonals = Personal::count();
        $finalTotal = $totalUsers + $totalPersonals;

        // Chart transactions
        $transactions = TransactionHistory::selectRaw("DATE(created_at) as date, SUM(amount) as total")
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Total income
        $totalIncome = TransactionHistory::sum('amount');

        // Income % (progress)
        $target = 100000;
        $incomePercent = $target > 0 ? ($totalIncome / $target) * 100 : 0;

        // 👉 Last 7 transactions
        $lastTransactions = TransactionHistory::latest()->take(7)->get();

        $loginLocations = AdminLoginLog::whereNotNull('latitude')
    ->get(['latitude','longitude','ip_address','country','city']);

    $unreadReferralAlerts = \App\Models\AdminNotification::whereNull('read_at')
    ->where('type', 'referral_bonus')
    ->latest()
    ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPersonals',
            'finalTotal',
            'transactions',
            'totalIncome',
            'incomePercent',
            'lastTransactions',
            'loginLocations',
            'unreadReferralAlerts',
        ));
}



public function logout()
{
    Auth::guard('admin')->logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
}


}
