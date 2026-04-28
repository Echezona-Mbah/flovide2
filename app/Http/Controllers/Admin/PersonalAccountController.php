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
use Illuminate\Support\Facades\DB;

class PersonalAccountController extends Controller
{
        use CurrencyHelper;

    
    public function index(Request $request)
    {
            $search = $request->search;

        $allpersonal = Personal::where('typeofuser', 'personal')
                      ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('person_phone', 'like', "%{$search}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString(); // keeps search during pagination
        return view('admin.personalaccount', compact('allpersonal'));
    }



    public function edit($id)
{
    $user = Personal::findOrFail($id);
    return view('admin.personalaccountedit', compact('user'));
}


public function update(Request $request,$id)
{
    $user = Personal::findOrFail($id);

    $request->validate([
        'email' => 'required|email',
        'firstname' => 'nullable|string|max:100',
        'lastname' => 'nullable|string|max:100',
        'person_phone' => 'nullable|string|max:255',
        'street_address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:255',
        'country' => 'nullable|string|max:255',
        'currency' => 'nullable|string|max:255',
        // 'referral_code' => 'nullable|string|max:255',
        // 'referral_link' => 'nullable|string|max:255',

    ]);

    // dd( $request->all());


    $data = $request->except('_token');

    if($request->hasFile('profile_picture')){
        $file = $request->file('profile_picture');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('profile_pictures/personal'),$filename);
        $data['profile_picture'] = 'profile_pictures/personal/'.$filename;
    }

     //dd($data);
    $user->update($data);

    return back()->with('success','User updated successfully');
}




public function deactivate($id)
{
    $user = Personal::findOrFail($id);

    $user->deletestatus = $user->deletestatus == 'active' ? 'deactivated' : 'active';

    $user->save();

    return back()->with('success','User status updated');
}


public function destroy($id)
{
    $user = Personal::findOrFail($id);

    $user->delete();

    return back()->with('success','User deleted successfully');
}



    public function find($id)
{
    $user = Personal::findOrFail($id);
        // dd($user);


    $balances = Balance::where('personal_id', $user->id)->get();
    $virtualCards = VirtualCards::where('user_id', $user->id)->get();
    $beneficia = Beneficia::where('personal_id', $user->id)->get();
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


public function addMoney(Request $request, $personalId, $balanceId)
{
    return $this->updateBalanceAmount($request, $personalId, $balanceId, 'add');
}

public function removeMoney(Request $request, $personalId, $balanceId)
{
    return $this->updateBalanceAmount($request, $personalId, $balanceId, 'remove');
}

private function updateBalanceAmount(Request $request, $personalId, $balanceId, string $mode)
{
    $request->validate([
        'amount' => 'required|numeric|min:0.01',
        'note' => 'nullable|string|max:255',
    ]);

    $personal = Personal::findOrFail($personalId);

    // If your balances table uses user_id for personal, keep user_id.
    // If it uses personal_id, change this where clause accordingly.
    $balance = Balance::where('id', $balanceId)
        ->where('personal_id', $personal->id)
        ->firstOrFail();

    $amount = (float) $request->amount;

    DB::beginTransaction();
    try {
        if ($mode === 'remove') {
            if ((float) $balance->amount < $amount) {
                return back()->withErrors(['error' => 'Insufficient balance for deduction.']);
            }
            $balance->amount = (float) $balance->amount - $amount;
        } else {
            $balance->amount = (float) $balance->amount + $amount;
        }

        $balance->save();
        DB::commit();

        $action = $mode === 'remove' ? 'removed from' : 'added to';
        return back()->with('success', number_format($amount, 2) . " {$balance->currency} {$action} {$balance->name} successfully.");
    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Balance update failed: ' . $e->getMessage()]);
    }
}





}
