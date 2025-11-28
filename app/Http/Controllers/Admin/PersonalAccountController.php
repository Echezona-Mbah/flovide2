<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\BankAccount;
use App\Models\Beneficia;
use App\Models\Customer;
use App\Models\Personal;
use App\Models\Subaccount;
use App\Models\VirtualCards;
use App\Traits\CurrencyHelper;
use Illuminate\Http\Request;

class PersonalAccountController extends Controller
{
        use CurrencyHelper;

    
    public function index(Request $request)
    {
        $allpersonal = Personal::where('typeofuser', 'personal')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10); // you can adjust per page
        return view('admin.personalaccount', compact('allpersonal'));
    }

    public function find($id)
{
    $user = Personal::findOrFail($id);

    $balances = Balance::where('user_id', $user->id)->get();
    $virtualCards = VirtualCards::where('user_id', $user->id)->get();
    $beneficia = Beneficia::where('user_id', $user->id)->get();
    $customer = Customer::where('user_id', $user->id)->get();
    $bankAccount = BankAccount::where('user_id', $user->id)->get();
    $Subaccount = Subaccount::where('user_id', $user->id)->get();

    foreach ($balances as $bal) {
        $bal->currency_info = $this->getCountryCodeFromCurrency($bal->currency);
    }

    return view('admin.personalaccountdetail', compact(
        'user',
        'balances',
        'virtualCards',
        'beneficia',
        'customer',
        'bankAccount',
        'Subaccount'
    ));
}
}
