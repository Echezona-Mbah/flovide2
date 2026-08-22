<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Personal;
use App\Models\Balance;
use App\Models\TransactionHistory;
use App\Traits\CurrencyHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class StatementController extends Controller
{
    use CurrencyHelper;

    public function index($id)
    {
        $user = User::with('balances')->findOrFail($id);
        return view('admin.businessstatement', compact('user'));
    }

    public function personalStatement($id)
    {
        $personal = Personal::with('balances')->findOrFail($id);
        return view('admin.personalstatement', compact('personal'));
    }

    // Get statement for a balance
    public function balanceStatement(Request $request, $id) {
        try {
            Log::info('Admin balance statement request initiated', [
                'balance_id' => $id,
                'admin_id' => Auth::id(),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);

            $balance = Balance::findOrFail($id);
            $user = User::find($balance->user_id) ?? Personal::find($balance->personal_id);
            $mode = $balance->mode ?? 'live';

            $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);

            $startDate = $request->filled('start_date') 
                ? \Carbon\Carbon::parse($request->start_date)->startOfDay() 
                : \Carbon\Carbon::now()->subDays(30)->startOfDay();

            $endDate = $request->filled('end_date') 
                ? \Carbon\Carbon::parse($request->end_date)->endOfDay() 
                : \Carbon\Carbon::now()->endOfDay();

            $transactions = TransactionHistory::where('balance_id', $balance->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'asc')
                ->get();

            $isCredit = function ($tx) {
                $type = strtolower($tx->type ?? '');
                return in_array($type, ['credit']) || str_contains($type, 'credit');
            };

            $openingBalance = TransactionHistory::where('balance_id', $balance->id)
                ->where('created_at', '<', $startDate)
                ->get()
                ->reduce(function ($carry, $tx) use ($isCredit) {
                    return $carry + ($isCredit($tx) ? $tx->amount : -$tx->amount);
                }, 0);

            $totalDebit  = $transactions->filter(fn ($tx) => !$isCredit($tx))->sum('amount');
            $totalCredit = $transactions->filter(fn ($tx) => $isCredit($tx))->sum('amount');
            $closingBalance = $openingBalance + $totalCredit - $totalDebit;

            Log::info('Admin balance statement generated successfully', [
                'balance_id'        => $balance->id,
                'user_id'           => $user->id ?? null,
                'transaction_count' => $transactions->count(),
                'closing_balance'   => $closingBalance,
            ]);

            return view('business.statement', compact(
                'balance', 'transactions', 'user',
                'startDate', 'endDate',
                'openingBalance', 'totalDebit', 'totalCredit', 'closingBalance'
            ));
        } catch (\Throwable $e) {
            Log::error('Failed to generate admin balance statement', [
                'balance_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Unable to generate statement: ' . $e->getMessage());
        }
    }
}

