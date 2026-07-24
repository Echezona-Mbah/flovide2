<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\BankAccount;
use App\Models\Beneficia;
use App\Models\Customer;
use App\Models\Personal;
use App\Models\Subaccount;
use App\Models\TransactionHistory;
use App\Models\VirtualCards;
use App\Traits\CurrencyHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FidelityService;


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


    $balances = Balance::where('personal_id', $user->id)
        ->liveMode()
        ->get();
    $virtualCards = VirtualCards::where('user_id', $user->id)->get();
    $beneficia = Beneficia::where('personal_id', $user->id)
        ->liveMode()
        ->get();
    $customer = Customer::where('user_id', $user->id)->get();
    $bankAccount = BankAccount::where('user_id', $user->id)->get();
    $Subaccount = Subaccount::where('user_id', $user->id)->get();

    foreach ($balances as $bal) {
        $bal->currency_info = $this->getCountryCodeFromCurrency($bal->currency);
    }

      $referrals = Personal::where('referred_by', $user->id)->get()->map(function ($ref) {
    $progress = app(\App\Services\ReferralBonusService::class)->getProgress($ref);
        return [
            'model'    => $ref,
            'progress' => $progress,
        ];
    });

    return view('admin.personalaccountdetail', compact(
        'user',
        'balances',
        'virtualCards',
        'beneficia',
        'customer',
        'bankAccount',
        'Subaccount',
        'referrals',
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
    if ($balance->is_locked) {
        return back()->withErrors(['error' => 'This balance is locked and cannot be modified. Unlock it first.']);
    }

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

          // ── Record in transaction history ──────────────────────────────────
            TransactionHistory::create([
                'personal_id'          => $personal->id,
                'balance_id'       => $balance->id,
                'type'             => $mode === 'add' ? 'credit' : 'withdrawal',
                'method'           => $mode === 'add' ? 'credit'  : 'withdrawal',
                'amount'           => $amount,
                'currency'         => $balance->currency,
                'status'           => 'success',
                'reference'        => 'admin-' . \Illuminate\Support\Str::uuid(),
                'payment_provider' => 'admin',
                'mode'             => $balance->mode ?? 'live',
                'sender'           => 'Admin',
                'sender_id'        => auth()->id(),
                'recipient_account_name' => $personal->firstname ?? ($personal->firstname . ' ' . $personal->lastname),
                'total_amount'     => $amount,
                'fees'             => 0,
                // Store the note in failure_reason field (or add a note column)
                'payment_reference'   => $request->note ?? null,
            ]);


        DB::commit();

        $action = $mode === 'remove' ? 'removed from' : 'added to';
        return back()->with('success', number_format($amount, 2) . " {$balance->currency} {$action} {$balance->name} successfully.");
    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Balance update failed: ' . $e->getMessage()]);
    }
}



public function submitToFidelity(Request $request, $id, FidelityService $fidelity)
{
    $personal = Personal::findOrFail($id);

    $currency = $request->input('currency', 'NGN');

    // ── Find (or create) the NGN balance wallet for this personal account ──
    $balance = Balance::where('personal_id', $personal->id)
        ->where('currency', $currency)
        ->first();

    // if (!$balance) {
    //     $balance = Balance::create([
    //         'personal_id' => $personal->id,
    //         'currency'    => $currency,
    //         'amount'      => 0,
    //         'name'        => $currency . ' Wallet',
    //     ]);
    // }

    if ($balance->virtual_account_number) {
        return back()->with('error', 'This wallet already has a Fidelity virtual account.');
    }

    $response = $fidelity->generateStaticVirtualAccount([
        'first_name'    => $personal->firstname,
        'last_name'     => $personal->lastname,
        'email'         => $personal->email,
        'bvn'           => null,
        'nin'           => null,
        'phone_number'  => $personal->person_phone,
        'date_of_birth' => $personal->date_of_birth,
    ]);

    if (!$response['success']) {
        $msg = $response['data']['messageCode'] ?? 'Failed to create Fidelity virtual account.';
        return back()->with('error', $msg);
    }

    $accountInfo = $response['data']['data']['accountInformation'] ?? [];
    $processId   = $response['data']['data']['processId'] ?? null;

    $balance->virtual_account_number = $accountInfo['accountNumber'] ?? null;
    $balance->virtual_account_name   = $accountInfo['accountName'] ?? null;
    $balance->virtual_account_bank   = $accountInfo['bankName'] ?? null;
    $balance->fidelty_process_id     = $processId;
    $balance->save();

    return back()->with('success', 'Fidelity virtual account created successfully: ' . ($accountInfo['accountNumber'] ?? ''));
}


// public function submitToFidelity(Request $request, $id, FidelityService $fidelity)
// {
//     $personal = Personal::findOrFail($id);

//     $currency = $request->input('currency', 'NGN');

//     // ── Find the NGN balance wallet for this personal account ──
//     $balance = Balance::where('personal_id', $personal->id)
//         ->where('currency', $currency)
//         ->first();

//     if (!$balance) {
//         return back()->with('error', 'No wallet found for this currency. Please create one first.');
//     }

//     if ($balance->virtual_account_number) {
//         return back()->with('error', 'This wallet already has a Fidelity virtual account.');
//     }

//     $response = $fidelity->generateDynamicVirtualAccount(10000, 30);

//     if (!$response['success']) {
//         $msg = $response['data']['messageCode'] ?? 'Failed to create Fidelity virtual account.';
//         return back()->with('error', $msg);
//     }

//     $accountInfo = $response['data']['data']['accountInformation'] ?? [];
//     $processId   = $response['data']['data']['processId'] ?? null;

//     $balance->virtual_account_number = $accountInfo['accountNumber'] ?? null;
//     $balance->virtual_account_name   = $accountInfo['accountName'] ?? null;
//     $balance->virtual_account_bank   = $accountInfo['bankName'] ?? null;
//     $balance->fidelty_process_id     = $processId;
//     $balance->save();

//     return back()->with('success', 'Fidelity virtual account created successfully: ' . ($accountInfo['accountNumber'] ?? ''));
// }


public function toggleBalanceLock(Request $request, $personalId, $balanceId)
{
    $request->validate([
        'reason' => 'nullable|string|max:255',
    ]);

    $personal = Personal::findOrFail($personalId);

    $balance = Balance::where('id', $balanceId)
        ->where('personal_id', $personal->id)
        ->firstOrFail();

    $balance->is_locked = !$balance->is_locked;
    $balance->locked_reason = $balance->is_locked ? ($request->reason ?? 'Locked by admin') : null;
    $balance->locked_at = $balance->is_locked ? now() : null;
    $balance->locked_by = $balance->is_locked ? auth()->id() : null;
    $balance->save();

    $state = $balance->is_locked ? 'locked' : 'unlocked';

    return back()->with('success', "{$balance->name} ({$balance->currency}) has been {$state}.");
}

}
