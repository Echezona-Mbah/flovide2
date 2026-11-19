<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Personal;
use App\Models\TransactionHistory;
use App\Models\Balance;
use App\Models\Beneficia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestModeController extends Controller
{
    public function index($id)
    {
        $user = User::findOrFail($id);

        $transactions = TransactionHistory::where('user_id', $user->id)
            ->testMode()
            ->paginate(10, ['*'], 'transactions_page');
        
        $balances = Balance::where('user_id', $user->id)
            ->testMode()
            ->paginate(10, ['*'], 'balances_page');

        $beneficiaries = Beneficia::where('user_id', $user->id)
            ->testMode()
            ->paginate(10, ['*'], 'beneficiaries_page');

        return view('admin.testmode', compact(
            'user',
            'transactions',
            'balances',
            'beneficiaries'
        ));
    }

    public function addTestMoney(Request $request, $userId, $balanceId)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:1000000000', 'regex:/^\d+(\.\d{1,2})?$/'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::findOrFail($userId);

        return DB::transaction(function () use ($validated, $user, $balanceId, $request) {
            $balance = Balance::query()
                ->where('id', $balanceId)
                ->where('user_id', $user->id)
                ->where('mode', 'test')
                ->lockForUpdate()
                ->firstOrFail();

            if ($balance->is_locked) {
                return back()->withErrors(['amount' => 'This test balance is locked and cannot be funded.']);
            }

            $amount = (float) $validated['amount'];
            $balance->amount = (float) $balance->amount + $amount;
            $balance->save();

            TransactionHistory::create([
                'user_id' => $user->id,
                'balance_id' => $balance->id,
                'type' => 'credit',
                'method' => 'test mode funding',
                'amount' => $amount,
                'currency' => $balance->currency,
                'status' => 'success',
                'reference' => 'test-fund-' . Str::uuid(),
                'payment_provider' => 'admin',
                'mode' => 'test',
                'sender' => 'Admin',
                'recipient_account_name' => $user->business_name ?: $user->name,
                'total_amount' => $amount,
                'fees' => 0,
                'payment_reference' => $validated['note'] ?? null,
            ]);

            return back()->with(
                'success',
                number_format($amount, 2) . ' ' . $balance->currency . ' added to the test balance.'
            );
        });
    }


    public function personalTestMode($id)
    {
        $user = Personal::findOrFail($id);

        $transactions = TransactionHistory::where('personal_id', $user->id)
            ->testMode()
            ->paginate(10, ['*'], 'transactions_page');
        
        $balances = Balance::where('personal_id', $user->id)
            ->testMode()
            ->paginate(10, ['*'], 'balances_page');

        $beneficiaries = Beneficia::where('personal_id', $user->id)
            ->testMode()
            ->paginate(10, ['*'], 'beneficiaries_page');

        return view('admin.personaltestmode', compact(
            'user',
            'transactions',
            'balances',
            'beneficiaries'
        ));
    }
}
