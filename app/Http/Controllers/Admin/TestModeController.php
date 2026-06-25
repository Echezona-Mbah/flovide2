<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Personal;
use App\Models\TransactionHistory;
use App\Models\Balance;
use App\Models\Beneficia;
use Illuminate\Http\Request;

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
