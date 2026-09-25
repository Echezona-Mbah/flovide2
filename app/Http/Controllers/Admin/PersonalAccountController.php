<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\BankAccount;
use App\Models\Beneficia;
use App\Models\Customer;
use App\Models\Personal;
use App\Models\Subaccount;
use App\Models\Currency;
use App\Models\TransactionHistory;
use App\Models\VirtualCards;
use App\Traits\CurrencyHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FidelityService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\RateLimiter;


class PersonalAccountController extends Controller
{
    use CurrencyHelper;

    protected FirebaseNotificationService $firebase;

    public function __construct(FirebaseNotificationService $firebase)
    {
        $this->firebase = $firebase;
    }

    // Shared notification helper
    protected function sendComplianceNotification(Personal $user, string $title, string $body, array $data = []): void
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

    // Human-readable labels for each status field
    protected function complianceFieldLabel(string $field): string
    {
        return match ($field) {
            'bvn_status' => 'BVN',
            'nin_status' => 'NIN',
            'identity_verification_status' => 'Identity Verification',
            'selfie_verification_status' => 'Selfie Verification',
            default => str_replace('_', ' ', ucfirst(str_replace('_status', '', $field))),
        };
    }

    
    // public function index(Request $request)
    // {
    //         $search = $request->search;

    //     $allpersonal = Personal::where('typeofuser', 'personal')
    //                   ->when($search, function ($query) use ($search) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('firstname', 'like', "%{$search}%")
    //               ->orWhere('lastname', 'like', "%{$search}%")
    //               ->orWhere('country', 'like', "%{$search}%")
    //               ->orWhere('email', 'like', "%{$search}%")
    //               ->orWhere('city', 'like', "%{$search}%")
    //               ->orWhere('person_phone', 'like', "%{$search}%");
    //         });
    //     })
    //     ->orderBy('created_at', 'desc')
    //     ->paginate(10)
    //     ->withQueryString(); // keeps search during pagination
    //     return view('admin.personalaccount', compact('allpersonal'));
    // }

public function index(Request $request)
{
    $search = $request->search;

    $allowedPerPage = [25, 50, 100, 250, 500];
    $perPage = (int) $request->input('per_page', 25);

    if (!in_array($perPage, $allowedPerPage, true)) {
        $perPage = 25;
    }

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
        ->paginate($perPage)
        ->withQueryString();

    return view('admin.personalaccount', compact('allpersonal', 'perPage', 'allowedPerPage'));
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

public function updateStatus(Request $request, $id) {

    $user = Personal::findOrFail($id);

    $request->validate([
        'field' => 'required|string',
        'status' => 'required|string',
    ]);

    $allowedFields = [
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


    //Currency
    $currencies = Currency::where('is_active', true)->get();


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

    $promoCodes = \App\Models\PromoCode::where('owner_type', 'personal')
    ->where('owner_id', $user->id)
    ->orderBy('created_at', 'desc')
    ->get()
    ->map(function ($promo) {
        $promo->redemption_count = \App\Models\PromoCodeRedemption::where('promo_code_id', $promo->id)->count();
        $promo->total_rewarded   = \App\Models\PromoCodeRedemption::where('promo_code_id', $promo->id)->sum('reward_amount');
        return $promo;
    });

    return view('admin.personalaccountdetail', compact(
        'user',
        'balances',
        'virtualCards',
        'beneficia',
        'currencies',
        'customer',
        'bankAccount',
        'Subaccount',
        'referrals',
        'promoCodes'
    ));
}



public function createBalance(Request $request, $userId) {
    
    $request->validate([
        'name' => 'required|string|max:255',
        'currency' => 'required|string|max:10',
        'mode' => 'required|in:live,test',
    ]);

    $adminId = Auth::id();
    $currency = strtoupper($request->currency);
    $mode = $request->input('mode');

    // Rate Limiting
    $rateLimitKey = 'admin-create-balance|' . $adminId . '|' . $userId . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {

        $seconds = RateLimiter::availableIn($rateLimitKey);

        Log::warning('Admin balance creation rate limit exceeded.', [
            'admin_id' => $adminId,
            'personal_id' => $userId,
            'ip' => $request->ip(),
            'currency' => $currency,
            'mode' => $mode,
        ]);

        $errorMessage = "Too many balance creation attempts. Please try again in {$seconds} seconds.";

        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'TOO_MANY_REQUESTS',
                'data' => null,
            ], 429)
            : redirect()->back()
                ->withErrors(['message' => $errorMessage])
                ->withInput();
    }

    RateLimiter::hit($rateLimitKey, 60);

    try {

        $balance = DB::transaction(function () use ($userId, $adminId, $currency, $mode, $request) {

            $user = Personal::where('id', $userId)
                ->lockForUpdate()
                ->first();

            if (!$user) {
                throw new \RuntimeException('User not found.');
            }

            //Check that the currency is active.
            $currencyRecord = Currency::where('code', $currency)
                ->where('is_active', true)
                ->first();

            if (!$currencyRecord) {
                throw new \RuntimeException(
                    "{$currency} is not currently available for new balances."
                );
            }

            //Prevent duplicate balances.
            $exists = Balance::where('personal_id', $userId)
                ->where('currency', $currency)
                ->where('mode', $mode)
                ->exists();

            if ($exists) {
                Log::warning('Admin attempted to create duplicate balance.', [
                    'admin_id' => $adminId,
                    'personal_id' => $userId,
                    'currency' => $currency,
                    'mode' => $mode,
                    'ip' => $request->ip(),
                ]);

                throw new \RuntimeException(
                    "The user already has a {$currency} balance for {$mode} mode."
                );
            }

            //Admin is allowed to create additional balances.
            $balance = Balance::create([
                'personal_id' => $userId,
                'name' => $request->name,
                'currency' => $currency,
                'mode' => $mode,
                'amount' => 0,
            ]);

            Log::info('Admin created additional user balance.', [
                'admin_id' => $adminId,
                'personal_id' => $userId,
                'balance_id' => $balance->id,
                'name' => $balance->name,
                'currency' => $balance->currency,
                'mode' => $balance->mode,
                'amount' => $balance->amount,
                'ip' => $request->ip(),
            ]);

            return $balance;
        });

        $successMessage = 'Balance created successfully for the user.';

        return $request->expectsJson()
            ? response()->json([
                'success' => true,
                'message' => $successMessage,
                'code' => 'BALANCE_CREATED',
                'data' => $balance,
            ], 201)
            : redirect()
                ->back()
                ->with('success', $successMessage);

    } catch (\RuntimeException $e) {

        $errorMessage = $e->getMessage();

        Log::warning('Admin balance creation rejected.', [
            'admin_id' => $adminId,
            'personal_id' => $userId,
            'currency' => $currency,
            'mode' => $mode,
            'ip' => $request->ip(),
            'reason' => $errorMessage,
        ]);

        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'BALANCE_CREATION_REJECTED',
                'data' => null,
            ], 422)
            : redirect()
                ->back()
                ->withErrors(['message' => $errorMessage])
                ->withInput();

    } catch (\Throwable $e) {

        Log::error('Admin balance creation failed unexpectedly.', [
            'admin_id' => $adminId,
            'personal_id' => $userId,
            'currency' => $currency,
            'mode' => $mode,
            'ip' => $request->ip(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        $errorMessage = 'Unable to create the balance at the moment. Please try again later.';

        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'BALANCE_CREATION_FAILED',
                'data' => null,
            ], 500)
            : redirect()
                ->back()
                ->withErrors(['message' => $errorMessage])
                ->withInput();
    }
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
        'bvn'           => $personal->bvn,
        'nin'           => $personal->nin,
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



public function toggleUserLock(Request $request, $id) {

    $admin = Auth::guard('admin')->user();

    if (!$admin) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized action.',
        ], 401);
    }

    $user = Personal::where('typeofuser', 'personal')->find($id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Personal user not found.',
        ], 404);
    }

    try {

        $oldStatus = (bool) $user->is_locked;
        $newStatus = !$oldStatus;

        DB::transaction(function () use ($user, $newStatus) {
            $user->is_locked = $newStatus;
            $user->save();
        });

        $state = $newStatus ? 'locked' : 'unlocked';

        Log::info('Personal user lock status updated', [
            'admin_id' => $admin->id,
            'user_id' => $user->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'action' => $state,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'is_locked' => $newStatus,
            'message' => "User account has been {$state} successfully.",
        ], 200);

    } catch (\Throwable $e) {

        Log::error('Failed to update Personal user lock status', [
            'admin_id' => $admin->id,
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Unable to update the account status. Please try again.',
        ], 500);
    }
}



}
