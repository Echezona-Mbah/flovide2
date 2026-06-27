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
use App\Services\FidelityService;
use App\Services\BlaaizService;
use Illuminate\Support\Facades\DB;
use App\Models\UserCurrencyFee;

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

    // Currency fees
    $currencyFees = UserCurrencyFee::where('user_id', $user->id)
        ->get()
        ->keyBy('currency');

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
        'Subaccount',
        'currencyFees'
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


public function downloadDocument(Request $request, $id)
{
    $user = User::findOrFail($id);

    $field = $request->query('field');

    $allowedFields = [
        'cac_certificate', 'valid_id', 'tin', 'utility_bill',
        'proof_of_identity', 'ownership_document',
        'organisational_chart', 'register_of_directors', 'formation_document',
    ];

    if (!in_array($field, $allowedFields)) {
        abort(403, 'Invalid document field.');
    }

    $relativePath = $user->$field;

    if (!$relativePath) {
        abort(404, 'Document not found.');
    }

    $fullPath = storage_path('app/public/' . $relativePath);

    if (!file_exists($fullPath)) {
        // try public path as fallback
        $fullPath = public_path('storage/' . $relativePath);
    }

    if (!file_exists($fullPath)) {
        abort(404, 'File does not exist on disk.');
    }

    $filename = basename($fullPath);

    return response()->download($fullPath, $filename);
}




// public function submitToFidelity(Request $request, $id, FidelityService $fidelity)
// {
//     $user = User::findOrFail($id);

//     // dd($user);

//     if ($user->virtual_account_number) {
//         return back()->with('error', 'This business already has a Fidelity virtual account.');
//     }

//     $response = $fidelity->generateStaticVirtualAccount([
//         'first_name'    => $user->firstname ?? $user->business_name,
//         'last_name'     => $user->lastname,
//         'email'         => $user->email,
//         'bvn'           => $user->bvn,
//         'nin'           => $user->nin,
//         'phone_number'  => $user->business_phone ?? $user->person_phone,
//         'date_of_birth' => $user->date_of_birth,
//     ]);

//     if (!$response['success']) {
//         $msg = $response['data']['messageCode'] ?? 'Failed to create Fidelity virtual account.';
//         return back()->with('error', $msg);
//     }

//     $accountInfo = $response['data']['data']['accountInformation'] ?? [];
//     $processId   = $response['data']['data']['processId'] ?? null;

//     $user->virtual_account_number = $accountInfo['accountNumber'] ?? null;
//     $user->virtual_account_name   = $accountInfo['accountName'] ?? null;
//     $user->virtual_account_bank   = $accountInfo['bankName'] ?? null;
//     $user->fidelty_process_id     = $processId;
//     $user->save();

//     return back()->with('success', 'Fidelity virtual account created successfully: ' . ($accountInfo['accountNumber'] ?? ''));
// }



public function submitToFidelity(Request $request, $id, FidelityService $fidelity)
{
    $user = User::findOrFail($id);

    $currency = $request->input('currency', 'NGN');

    //dd($currency);

    // ── Find (or create) the NGN balance wallet for this user ──────────────
    $balance = Balance::where('user_id', $user->id)
        ->where('currency', $currency)
        ->first();

    if ($balance->virtual_account_number) {
        return back()->with('error', 'This wallet already has a Fidelity virtual account.');
    }

    $response = $fidelity->generateStaticVirtualAccount([
        'first_name'    => $user->firstname ?? $user->business_name,
        'last_name'     => $user->lastname,
        'email'         => $user->email,
        'bvn'           => $user->bvn,
        'nin'           => $user->nin,
        'phone_number'  => $user->business_phone ?? $user->person_phone,
        'date_of_birth' => $user->date_of_birth,
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


public function submitToBlaaiz(Request $request, $id, BlaaizService $blaaiz)
{
    $user = User::findOrFail($id);

    if ($user->blaaiz_id) {
        return back()->with('error', 'This business is already registered with Blaaiz.');
    }

    $payload = [
        'type'                  => 'business',
        'email'                 => $user->email,
        'country'               => $user->countries_id ?? 'CA',
        'phone'                 => $user->business_phone,
        'business_name'         => $user->business_name,
        'registration_number'   => $user->registration_number,
        'incorporation_country' => $user->countries_id ?? 'CA',
        'business_type'         => $user->business_type,
        'business_description'  => $user->nature_of_business,
        'website'                => $user->company_url,
    ];

    $payload = array_filter($payload, fn($v) => !is_null($v) && $v !== '');

    $response = $blaaiz->createCustomer($payload);

    if (!$response['success']) {
        $msg = $response['data']['message'] ?? 'Failed to register business with Blaaiz.';
        return back()->with('error', $msg);
    }

    $responseData = $response['data']['data'] ?? $response['data'];

    $user->blaaiz_id = $responseData['id'] ?? null;
    $user->save();

    return back()->with('success', 'Business registered with Blaaiz successfully: ' . ($responseData['id'] ?? ''));
}








public function getCurrencyFees(User $user)
{
    $fees = UserCurrencyFee::where('user_id', $user->id)
        ->get()
        ->keyBy('currency');

    return $fees;
}

public function updateCurrencyFee(Request $request, $id, $currency)
{
    abort_unless(in_array(strtoupper($currency), UserCurrencyFee::CURRENCIES), 404);

    $request->validate([
        'collection_enabled'  => 'boolean',
        'collection_percent'  => 'numeric|min:0|max:100',
        'collection_fixed'    => 'numeric|min:0',
        'collection_min'      => 'numeric|min:0',
        'collection_max'      => 'numeric|min:0',
        'payout_enabled'      => 'boolean',
        'payout_percent'      => 'numeric|min:0|max:100',
        'payout_fixed'        => 'numeric|min:0',
        'payout_min'          => 'numeric|min:0',
        'payout_max'          => 'numeric|min:0',
    ]);

    $fee = UserCurrencyFee::updateOrCreate(
        ['user_id' => $id, 'currency' => strtoupper($currency)],
        [
            'collection_enabled'  => $request->boolean('collection_enabled'),
            'collection_percent'  => $request->input('collection_percent', 0),
            'collection_fixed'    => $request->input('collection_fixed', 0),
            'collection_min'      => $request->input('collection_min', 0),
            'collection_max'      => $request->input('collection_max', 0),
            'payout_enabled'      => $request->boolean('payout_enabled'),
            'payout_percent'      => $request->input('payout_percent', 0),
            'payout_fixed'        => $request->input('payout_fixed', 0),
            'payout_min'          => $request->input('payout_min', 0),
            'payout_max'          => $request->input('payout_max', 0),
        ]
    );

    return response()->json([
        'success' => true,
        'message' => strtoupper($currency) . ' fees updated successfully.',
        'data'    => $fee,
    ]);
}



}
