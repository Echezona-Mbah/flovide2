<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\BankAccount;
use App\Models\Beneficia;
use App\Models\Customer;
use App\Models\Subaccount;
use App\Models\TeamMembers;
use App\Models\User;
use App\Models\VirtualCards;
use Illuminate\Http\Request;
use App\Traits\CurrencyHelper;


class BusinessAccountController extends Controller
{
        use CurrencyHelper;


    public function index(Request $request)
    {
        $allUser = User::where('typeofuser', 'business')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10); // you can adjust per page
        return view('admin.businessaccount', compact('allUser'));
    }

public function find($id)
{
    $user = User::findOrFail($id);

    $balances = Balance::where('user_id', $user->id)->get();
    $virtualCards = VirtualCards::where('user_id', $user->id)->get();
    $teamMembers = TeamMembers::where('owner_id', $user->id)->get();
    $beneficia = Beneficia::where('user_id', $user->id)->get();
    $customer = Customer::where('user_id', $user->id)->get();
    $bankAccount = BankAccount::where('user_id', $user->id)->get();
    $Subaccount = Subaccount::where('user_id', $user->id)->get();

    foreach ($balances as $bal) {
        $bal->currency_info = $this->getCountryCodeFromCurrency($bal->currency);
    }

    return view('admin.businessaccountdetail', compact(
        'user',
        'balances',
        'virtualCards',
        'teamMembers',
        'beneficia',
        'customer',
        'bankAccount',
        'Subaccount'
    ));
}




    public function updateStatus(Request $request, $id)
{
    $user = User::findOrFail($id);

    $field = $request->field;
    $status = $request->status;

    $user->$field = $status;
    $user->save();

    return response()->json([
        'success' => true,
        'label' => ucfirst(str_replace('_', ' ', $status)),
        'class' => $status === 'confirmed' ? 'bg-success' :
                   ($status === 'under_review' ? 'bg-warning' :
                   ($status === 'rejected' ? 'bg-danger' : 'bg-secondary'))
    ]);
}



}
