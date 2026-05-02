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
use Illuminate\Support\Facades\DB;

class BusinessAccountController extends Controller
{
        use CurrencyHelper;


public function index(Request $request)
{
    $search = $request->search;

    $allUser = User::where('typeofuser', 'business')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('business_phone', 'like', "%{$search}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString(); // keeps search during pagination

    return view('admin.businessaccount', compact('allUser','search'));
}

public function edit($id)
{
    $user = User::findOrFail($id);
    return view('admin.businessaccountedit', compact('user'));
}


public function update(Request $request,$id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'email' => 'required|email',
        'firstname' => 'nullable|string|max:100',
        'lastname' => 'nullable|string|max:100',
        'business_name' => 'nullable|string|max:255',
        'person_phone' => 'nullable|string|max:255',
        'business_phone' => 'nullable|string|max:255',
        'registration_number' => 'nullable|string|max:255',
        'business_type' => 'nullable|string|max:255',
        'industry' => 'nullable|string|max:255',
        'company_url' => 'nullable|string|max:255',
        'incorporation_date' => 'nullable|string|max:255',
        'annual_turnover' => 'nullable|string|max:255',
        'street_address' => 'nullable|string|max:255',
        'trading_address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:255',
        'countries_id' => 'nullable|string|max:255',
        'currency' => 'nullable|string|max:255',
        'referral_code' => 'nullable|string|max:255',
        'referral_link' => 'nullable|string|max:255',

    ]);

    // dd( $request->all());


    $data = $request->except('_token');

    if($request->hasFile('profile_picture')){
        $file = $request->file('profile_picture');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('profile_pictures/business'),$filename);
        $data['profile_picture'] = 'profile_pictures/business/'.$filename;
    }
    $user->update($data);

    return back()->with('success','User updated successfully');
}

public function deactivate($id)
{
    $user = User::findOrFail($id);

    $user->deletestatus = $user->deletestatus == 'active' ? 'deactivated' : 'active';

    $user->save();

    return back()->with('success','User status updated');
}


public function destroy($id)
{
    $user = User::findOrFail($id);

    $user->delete();

    return back()->with('success','User deleted successfully');
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



 public function addMoney(Request $request, $userId, $balanceId)
    {
        return $this->updateBalanceAmount($request, $userId, $balanceId, 'add');
    }

    public function removeMoney(Request $request, $userId, $balanceId)
    {
        return $this->updateBalanceAmount($request, $userId, $balanceId, 'remove');
    }

    private function updateBalanceAmount(Request $request, $userId, $balanceId, string $mode)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($userId);

        $balance = Balance::where('id', $balanceId)
            ->where('user_id', $user->id)
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
