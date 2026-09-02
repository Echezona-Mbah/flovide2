<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\BankAccount;
use App\Models\Beneficia;
use App\Models\Customer;
use App\Models\Subaccount;
use App\Models\TeamMembers;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\VirtualCards;
use Illuminate\Http\Request;
use App\Traits\CurrencyHelper;
use App\Services\FidelityService;
use App\Services\BlaaizService;
use Illuminate\Support\Facades\DB;
use App\Models\UserCurrencyFee;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminBroadcastEmail;
use App\Models\InteracAutoDeposit;

class BusinessAccountController extends Controller
{
    use CurrencyHelper;


    protected FirebaseNotificationService $firebase;

    public function __construct(FirebaseNotificationService $firebase)
    {
        $this->firebase = $firebase;
    }


    // ── Shared notification helper ──────────────────────────────────────────
    protected function sendComplianceNotification(User $user, string $title, string $body, array $data = []): void
    {
        if (empty($user->device_token)) {
            return;
        }

        $sent = $this->firebase->sendToToken($user->device_token, $title, $body, array_merge([
            'type' => 'compliance',
        ], $data));

        if (!$sent) {
            Log::warning('Compliance push notification failed', [
                'user_id' => $user->id,
                'title' => $title,
            ]);
        }
    }

    // ── Human-readable labels for each status field ─────────────────────────
    protected function complianceFieldLabel(string $field): string
    {
        return match ($field) {
            'cac_status'                    => 'CAC Document',
            'bvn_status'                     => 'BVN',
            'valid_id_status'               => 'Valid ID',
            'tin_status'                     => 'TIN Document',
            'utility_bill_status'           => 'Utility Bill',
            'proof_of_identity_status'      => 'Proof of Identity',
            'ownership_status'              => 'Ownership Document',
            'organisational_chart_status'   => 'Organisational Chart',
            'register_of_directors_status'  => 'Register of Directors',
            'formation_document_status'     => 'Formation Document',
            'nin_status'                     => 'NIN',
            'identity_verification_status'  => 'Identity Verification',
            'selfie_verification_status'    => 'Selfie Verification',
            default => str_replace('_', ' ', ucfirst(str_replace('_status', '', $field))),
        };
    }


// public function index(Request $request)
// {
//     $search = $request->search;

//     $allUser = User::where('typeofuser', 'business')
//         ->when($search, function ($query) use ($search) {
//             $query->where(function ($q) use ($search) {
//                 $q->where('business_name', 'like', "%{$search}%")
//                   ->orWhere('firstname', 'like', "%{$search}%")
//                   ->orWhere('lastname', 'like', "%{$search}%")
//                   ->orWhere('email', 'like', "%{$search}%")
//                   ->orWhere('city', 'like', "%{$search}%")
//                   ->orWhere('business_phone', 'like', "%{$search}%");
//             });
//         })
//         ->orderBy('created_at', 'desc')
//         ->paginate(10)
//         ->withQueryString(); // keeps search during pagination
//         $unreadReferralAlerts = \App\Models\AdminNotification::whereNull('read_at')
//         ->where('type', 'referral_bonus')
//         ->latest()
//         ->get();

        

        

//     return view('admin.businessaccount', compact('allUser','search','unreadReferralAlerts'));
// }

public function index(Request $request)
{
    $search = $request->search;

    $allowedPerPage = [25, 50, 100, 250, 500];
    $perPage = (int) $request->input('per_page', 25);

    if (!in_array($perPage, $allowedPerPage, true)) {
        $perPage = 25;
    }

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
        ->paginate($perPage)
        ->withQueryString();

    $unreadReferralAlerts = \App\Models\AdminNotification::whereNull('read_at')
        ->where('type', 'referral_bonus')
        ->latest()
        ->get();

    return view('admin.businessaccount', compact('allUser', 'search', 'unreadReferralAlerts', 'perPage', 'allowedPerPage'));
}

public function edit($id)
{
    $user = User::findOrFail($id);
            $unreadReferralAlerts = \App\Models\AdminNotification::whereNull('read_at')
        ->where('type', 'referral_bonus')
        ->latest()
        ->get();
        $referrals = User::where('referred_by', $user->id)->get()->map(function ($ref) {
        $progress = app(\App\Services\ReferralBonusService::class)->getProgress($ref);
            return [
                'model'    => $ref,
                'progress' => $progress,
            ];
        });
    return view('admin.businessaccountedit', compact('user','unreadReferralAlerts','referrals'));
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

    $balances = Balance::where('user_id', $user->id)
        ->liveMode()
        ->get();
    $virtualCards = VirtualCards::where('user_id', $user->id)->get();
    $teamMembers = TeamMembers::where('owner_id', $user->id)->get();
    $beneficia = Beneficia::where('user_id', $user->id)
        ->liveMode()
        ->get();
    $customer = Customer::where('user_id', $user->id)->get();
    $bankAccount = BankAccount::where('user_id', $user->id)->get();
    $Subaccount = Subaccount::where('user_id', $user->id)->get();

    // Currency fees
    $currencyFees = UserCurrencyFee::where('user_id', $user->id)
        ->get()
        ->keyBy('currency');
    $autoDeposits = InteracAutoDeposit::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    foreach ($balances as $bal) {
        $bal->currency_info = $this->getCountryCodeFromCurrency($bal->currency);
    }
            $unreadReferralAlerts = \App\Models\AdminNotification::whereNull('read_at')
        ->where('type', 'referral_bonus')
        ->latest()
        ->get();

            $referrals = User::where('referred_by', $user->id)->get()->map(function ($ref) {
        $progress = app(\App\Services\ReferralBonusService::class)->getProgress($ref);
            return [
                'model'    => $ref,
                'progress' => $progress,
            ];
        });
    return view('admin.businessaccountdetail', compact(
        'user',
        'balances',
        'virtualCards',
        'teamMembers',
        'beneficia',
        'customer',
        'bankAccount',
        'Subaccount',
        'currencyFees',
        'unreadReferralAlerts',
        'referrals',
        'autoDeposits'
    ));
}





public function updateStatus(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'field' => 'required|string',
        'status' => 'required|string',
    ]);

    $allowedFields = [
        'cac_status',
        'bvn_status',
        'valid_id_status',
        'tin_status',
        'utility_bill_status',
        'proof_of_identity_status',
        'ownership_status',
        'organisational_chart_status',
        'register_of_directors_status',
        'formation_document_status',
        'nin_status',

        // Identity verification
        'identity_verification_status',
        'selfie_verification_status',
    ];


    if (!in_array($request->field, $allowedFields, true)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid status field.'
        ], 422);
    }

    $allowedStatuses = [
        'pending',
        'under review',
        'confirmed',
        'rejected',
    ];

    if (!in_array($request->status, $allowedStatuses, true)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid verification status.'
        ], 422);
    }

    $field = $request->field;
    $status = $request->status;

    $user->$field = $status;
    $user->save();

    $admin = Auth::guard('admin')->user();

    Log::info('User verification status updated', [
        'admin_id' => $admin?->id,
        'user_id' => $user->id,
        'admin_email' => $admin?->email,
        'field' => $field,
        'old_status' => $user->getOriginal($field),
        'new_status' => $status,
        'ip_address' => $request->ip(),
    ]);

    // Push notification on compliance status change
    // Only notify for actual document/verification status fields (fields
    // ending in "_status"), and only for meaningful outcomes.
    if (str_ends_with($field, '_status')) {
        $label = $this->complianceFieldLabel($field);


        if ($status === 'confirmed') {
            $this->sendComplianceNotification(
                $user,
                "{$label} Approved",
                "Your {$label} has been reviewed and approved.",
                ['document' => $field, 'status' => $status]
            );
        } elseif ($status === 'rejected') {
            $this->sendComplianceNotification(
                $user,
                "{$label} Rejected",
                "Your {$label} was reviewed and could not be approved. Please check your account for details.",
                ['document' => $field, 'status' => $status]
            );
        }
        // Intentionally not notifying for 'under_review' or other
        // in-progress states here, since the compliance controller
        // already notifies the user at upload time.
    }

    return response()->json([
        'success' => true,
        'label' => ucfirst(str_replace('_', ' ', $status)),
        'class' => match ($status) {
            'confirmed' => 'bg-success',
            'under review' => 'bg-warning',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        }
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
            'note'   => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($userId);

        $balance = Balance::where('id', $balanceId)
            ->where('user_id', $user->id)
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
                'user_id'          => $user->id,
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
                'recipient_account_name' => $user->business_name ?? ($user->firstname . ' ' . $user->lastname),
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

public function updateAutoDepositStatus(Request $request, $userId, $emailId)
{
    $request->validate([
        'status' => 'required|in:active,inactive,pending',
    ]);

    $user = User::findOrFail($userId);

    $entry = InteracAutoDeposit::where('id', $emailId)
        ->where('user_id', $user->id)
        ->firstOrFail();

    $entry->status = $request->status;
    $entry->save();

    Log::info('Interac Auto Deposit status updated by admin', [
        'admin_id'    => Auth::guard('admin')->id(),
        'user_id'     => $user->id,
        'entry_id'    => $entry->id,
        'email'       => $entry->email,
        'new_status'  => $entry->status,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Status updated successfully.',
        'data'    => [
            'id'     => $entry->id,
            'status' => $entry->status,
        ],
    ]);
}







public function getCurrencyFees(User $user)
{
    $fees = UserCurrencyFee::where('user_id', $user->id)
        ->get()
        ->keyBy('currency');

    return $fees;
}

// public function updateCurrencyFee(Request $request, $id, $currency)
// {
//     abort_unless(in_array(strtoupper($currency), UserCurrencyFee::CURRENCIES), 404);

//     $request->validate([
//         'collection_enabled'  => 'boolean',
//         'collection_percent'  => 'numeric|min:0|max:100',
//         'collection_fixed'    => 'numeric|min:0',
//         'collection_min'      => 'numeric|min:0',
//         'collection_max'      => 'numeric|min:0',
//         'payout_enabled'      => 'boolean',
//         'payout_percent'      => 'numeric|min:0|max:100',
//         'payout_fixed'        => 'numeric|min:0',
//         'payout_min'          => 'numeric|min:0',
//         'payout_max'          => 'numeric|min:0',
//     ]);

//     $fee = UserCurrencyFee::updateOrCreate(
//         ['user_id' => $id, 'currency' => strtoupper($currency)],
//         [
//             'collection_enabled'  => $request->boolean('collection_enabled'),
//             'collection_percent'  => $request->input('collection_percent', 0),
//             'collection_fixed'    => $request->input('collection_fixed', 0),
//             'collection_min'      => $request->input('collection_min', 0),
//             'collection_max'      => $request->input('collection_max', 0),
//             'payout_enabled'      => $request->boolean('payout_enabled'),
//             'payout_percent'      => $request->input('payout_percent', 0),
//             'payout_fixed'        => $request->input('payout_fixed', 0),
//             'payout_min'          => $request->input('payout_min', 0),
//             'payout_max'          => $request->input('payout_max', 0),
//         ]
//     );

//     return response()->json([
//         'success' => true,
//         'message' => strtoupper($currency) . ' fees updated successfully.',
//         'data'    => $fee,
//     ]);
// }


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

    $user = User::findOrFail($id);
    $currencyCode = strtoupper($currency);

    // ── Snapshot the previous state so we can tell what actually changed ──
    $existing = UserCurrencyFee::where('user_id', $id)
        ->where('currency', $currencyCode)
        ->first();

    $newCollection = [
        'collection_enabled' => $request->boolean('collection_enabled'),
        'collection_percent' => (float) $request->input('collection_percent', 0),
        'collection_fixed'   => (float) $request->input('collection_fixed', 0),
        'collection_min'     => (float) $request->input('collection_min', 0),
        'collection_max'     => (float) $request->input('collection_max', 0),
    ];

    $newPayout = [
        'payout_enabled' => $request->boolean('payout_enabled'),
        'payout_percent' => (float) $request->input('payout_percent', 0),
        'payout_fixed'   => (float) $request->input('payout_fixed', 0),
        'payout_min'     => (float) $request->input('payout_min', 0),
        'payout_max'     => (float) $request->input('payout_max', 0),
    ];

    $collectionChanged = !$existing || collect($newCollection)->some(
        fn ($val, $key) => (float) $existing->$key !== (float) $val
            && !is_bool($existing->$key)
    ) || (!$existing || (bool) $existing->collection_enabled !== $newCollection['collection_enabled']);

    $payoutChanged = !$existing || collect($newPayout)->some(
        fn ($val, $key) => (float) $existing->$key !== (float) $val
            && !is_bool($existing->$key)
    ) || (!$existing || (bool) $existing->payout_enabled !== $newPayout['payout_enabled']);

    $fee = UserCurrencyFee::updateOrCreate(
        ['user_id' => $id, 'currency' => $currencyCode],
        array_merge($newCollection, $newPayout)
    );

    // ── Notify the business owner about the change ──────────────────────
    if ($collectionChanged) {
        $this->sendFeeUpdateNotification($user, $currencyCode, 'collection', $newCollection['collection_enabled']);
    }
    if ($payoutChanged) {
        $this->sendFeeUpdateNotification($user, $currencyCode, 'payout', $newPayout['payout_enabled']);
    }

    return response()->json([
        'success' => true,
        'message' => $currencyCode . ' fees updated successfully.',
        'data'    => $fee,
    ]);
}

// ── Shared: notify user their fee settings changed ──────────────────────
protected function sendFeeUpdateNotification(User $user, string $currency, string $side, bool $enabled): void
{
    if (empty($user->device_token)) {
        return;
    }

    $label = ucfirst($side); // "Collection" or "Payout"
    $status = $enabled ? 'updated' : 'disabled';

    $sent = $this->firebase->sendToToken(
        $user->device_token,
        "{$currency} {$label} Fees {$status}",
        "Your {$currency} {$side} fee settings have been {$status} by the admin.",
        [
            'type'     => 'fee_update',
            'currency' => $currency,
            'side'     => $side,
            'enabled'  => $enabled ? '1' : '0',
        ]
    );

    if (!$sent) {
        Log::warning('Fee update push notification failed', [
            'user_id'  => $user->id,
            'currency' => $currency,
            'side'     => $side,
        ]);
    }
}

public function toggleBalanceLock(Request $request, $userId, $balanceId)
{
    $request->validate([
        'reason' => 'nullable|string|max:255',
    ]);

    $user = User::findOrFail($userId);

    $balance = Balance::where('id', $balanceId)
        ->where('user_id', $user->id)
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
