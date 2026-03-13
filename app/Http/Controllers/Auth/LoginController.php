<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Personal;
use App\Models\Subaccount;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;
use App\Models\LoginActivity;
use App\Mail\LoginOtpMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Http;


class LoginController extends Controller
{
    // public function recordLoginActivity($request, $user)
    // {
    //     $agent = new Agent();

    //     // Get user details
    //     $ip = $request->ip();
    //     $device = $agent->device();
    //     $browser = $agent->browser();
    //     $os = $agent->platform();

    //     //Get Location using IP-API
    //     $location = null;
    //     try {
    //         $json = @file_get_contents("http://ip-api.com/json/{$ip}");
    //         $details = json_decode($json, true);
    //         if ($details && $details['status'] === 'success') {
    //             $location = $details['city'] . ', ' . $details['country'];
    //         }
    //     } catch (\Exception $e) {
    //         $location = null;
    //     }

    //     // Save record
    //     LoginActivity::create([
    //         'user_id' => $user->id,
    //         'personal_id' => null,
    //         'ip_address' => $ip,
    //         'device' => $device ?: 'Unknown Device',
    //         'browser' => $browser ?: 'Unknown Browser',
    //         'os' => $os ?: 'Unknown OS',
    //         'location' => $location,
    //         'login_time' => now(),
    //     ]);
    // }


 
    
    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'data' => [
                    'message' => 'Invalid credentials'
                ]
            ], 401);
        }

        $account = Auth::user();

        if ($account->email_verified_status !== 'yes') {
            Auth::logout();
            return response()->json([
                'data' => [
                    'message' => 'Email not verified. Please verify your email.',
                    'status' => 'unverified',
                    'verify_url' => url("/api/auth/verify-email/{$account->email}"),
                ]
            ], 403);
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        $account->update([
            'login_otp' => $otp,
            'login_otp_expires_at' => now()->addMinutes(5),
        ]);

        // Send OTP via email
        Mail::to($account->email)->send(new LoginOtpMail($account->business_name, $otp));
        
        //send notification
        $account->notify(new GeneralNotification(
            "Your Login OTP",
            "Your OTP is: {$otp}. It expires in 5 minutes."
        ));

        return response()->json([
            'data' => [
                'message' => 'OTP sent to your email',
                'status' => 'otp_required',
                'email' => $account->email
            ]
        ], 200);
    }

    public function verifyUserLoginOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        $account = User::where('email', $request->email)->first();

        if (!$account || !$account->login_otp) {
            return response()->json([
                'data' => ['message' => 'OTP not requested']
            ], 400);
        }

        if (now()->gt($account->login_otp_expires_at)) {
            return response()->json([
                'data' => ['message' => 'OTP expired']
            ], 400);
        }

        if ($account->login_otp != $request->otp) {
            return response()->json([
                'data' => ['message' => 'Invalid OTP']
            ], 400);
        }

        // Clear OTP
        $account->update([
            'login_otp' => null,
            'login_otp_expires_at' => null,
        ]);

        // Generate token
        $token = $account->createToken('BusinessToken')->plainTextToken;

        // Notify login success
        $account->notify(new GeneralNotification(
            "Login Successful ✅",
            "Hello {$account->firstname}, you just logged in to your Flovide account at " . now()->format('Y-m-d H:i:s')
        ));


        $balances = \App\Models\Balance::where('user_id', $account->id)->get();
        
        $transactions = \App\Models\TransactionHistory::where('user_id', $account->id)
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($t) {
                return [
                    'type'      => $t->type,
                    'date'      => $t->created_at->format('Y-m-d H:i:s'),
                    'sender'    => $t->sender ?? 'N/A',
                    'recipient' => $t->recipient ?? 'N/A',
                    'amount'    => $t->currency_symbol . number_format($t->amount, 2),
                    'currency'  => $t->currency,
                    'status' => $t->status,
                    'type' => $t->type,
                    'reference' => $t->reference,
                    'recipient_details' => [
                        'alias'          => $t->recipient_alias,
                        'account_name'   => $t->recipient_account_name,
                        'account_number' => $t->recipient_account_number,
                        'bank_name'      => $t->recipient_bank_name,
                        'bank_currency'  => $t->recipient_bank_currency,
                    ]
                ];
            });


        // $chartData = \App\Models\TransactionHistory::where('user_id', $account->id)
        //     ->where('created_at', '>=', now()->subMonths(3))
        //     ->select(
        //         DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
        //         DB::raw("SUM(amount) as total_amount")
        //     )
        //     ->groupBy('month')
        //     ->orderBy('month', 'asc')
        //     ->get();

        $months = collect(range(0, 2))->map(function ($i) {
            return now()->subMonths($i)->format('Y-m');
        })->reverse()->values();

        $dbData = \App\Models\TransactionHistory::where('user_id', $account->id)
            ->where('created_at', '>=', now()->subMonths(3))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as total_amount")
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->pluck('total_amount', 'month');

        $chartData = $months->map(function ($m) use ($dbData) {
            return [
                'month' => $m,
                'total_amount' => $dbData[$m] ?? 0,
            ];
        });

        $teamMembership = \App\Models\TeamMembers::where('user_id', $account->id)->first();
        $countryResponse = $this->fetchcountrylist($request);
        $countries = $countryResponse->getData();
        // $countries = \App\Models\Countries::all();
        $beneficiaries = \App\Models\Beneficia::where('user_id', $account->id)->get();
        $payoutAccounts = \App\Models\BankAccount::where('user_id', $account->id)->get();
        $virtualCards = \App\Models\VirtualCards::where('user_id', $account->id)->where('status', 'active')->get();
        $subaccounts = Subaccount::where('user_id', $account->id)->get();
        $tokenResponse = app(\App\Http\Controllers\Business\ComplianceController::class)
                    ->getSumsubToken()
                    ->getData(true);
        // dd($chartData);


        //RECORD LOGIN ACTIVITY
        // $this->recordLoginActivity($request, $account);
        
        return response()->json([
            'data' => [
                'account_type' => 'business',
                'token' => $token,
                'business' => [
                    'id' => $account->id,
                    'firstname' => $account->firstname,
                    'lastname' => $account->lastname,
                    'email' => $account->email,
                    'currency' => $account->currency,
                    'profile_url' => $account->profile_picture
                        ? asset($account->profile_picture)
                        : null,
                    'email_verified_status' => $account->email_verified_status,
                ],
                'compliance' => $account->complianceStatus($tokenResponse),
                'balances' => $balances,
                'transactions' => $transactions,
                'chart' => $chartData,
                'countries' => $countries,
                'beneficiaries' => $beneficiaries,
                'payout_accounts' => $payoutAccounts,
                'virtual_cards' => $virtualCards,
                'subaccounts' => $subaccounts,
                'owner_id' => $teamMembership ? ($teamMembership->userOwner->id ?? null) : $account->id,
                'role' => $teamMembership ? ($teamMembership->role ?? 'member') : 'Owner',
            ]
        ], 200);

    }

    public function resendUserLoginOtp(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $account = User::where('email', $request->email)->first();

        // Rate limit: 60 seconds cooldown
        if ($account->login_otp_expires_at && now()->diffInSeconds($account->login_otp_expires_at->subMinutes(5)) < 60) {
            return response()->json([
                'data' => [
                    'message' => 'You can request a new OTP after 60 seconds.'
                ]
            ], 429);
        }

        // Generate new OTP
        $otp = rand(100000, 999999);
        $account->update([
            'login_otp' => $otp,
            'login_otp_expires_at' => now()->addMinutes(5),
        ]);

        // Send email + notification
        Mail::to($account->email)->send(new LoginOtpMail($account->business_name, $otp));
        $account->notify(new GeneralNotification(
            "Your Login OTP",
            "Your new OTP is: {$otp}. It expires in 5 minutes."
        ));

        return response()->json([
            'data' => [
                'message' => 'OTP resent successfully',
                'status' => 'otp_required'
            ]
        ], 200);
    }


    public function loginPersonal(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $account = Personal::where('email', $request->email)->first();

        if (!$account || !Hash::check($request->password, $account->password)) {
            return response()->json([
                'data' => [
                    'message' => 'Invalid credentials'
                ]
            ], 401);
        }

        if ($account->email_verified_status !== 'yes') {
            return response()->json([
                'data' => [
                    'message' => 'Email not verified. Please verify your email.',
                    'status' => 'unverified',
                    'verify_url' => url("/api/auth/verify-email/{$account->email}"),
                ]
            ], 403);
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        $account->update([
            'login_otp' => $otp,
            'login_otp_expires_at' => now()->addMinutes(5),
        ]);

        // Send OTP notification
        $account->notify(new GeneralNotification(
            "Your Login OTP",
            "Your OTP is: {$otp}. It expires in 5 minutes."
        ));

        //Send OTP email
        Mail::to($account->email)->send(new LoginOtpMail($account->firstname, $otp));

        return response()->json([
            'data' => [
                'message' => 'OTP sent to your email',
                'status' => 'otp_required',
                'email' => $account->email
            ]
        ], 200);
    }

    public function verifyLoginOtp(Request $request){
        
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        $account = Personal::where('email', $request->email)->first();

        if (!$account || !$account->login_otp) {
            return response()->json([
                'data' => ['message' => 'OTP not requested']
            ], 400);
        }

        if (now()->gt($account->login_otp_expires_at)) {
            return response()->json([
                'data' => ['message' => 'OTP expired']
            ], 400);
        }

        if ($account->login_otp != $request->otp) {
            return response()->json([
                'data' => ['message' => 'Invalid OTP']
            ], 400);
        }

        // Clear OTP
        $account->update([
            'login_otp' => null,
            'login_otp_expires_at' => null,
        ]);

        // Generate token after OTP success
        $token = $account->createToken('PersonalToken')->plainTextToken;


        $account->notify(new GeneralNotification(
            "Login Successful ✅",
            "Hello {$account->firstname}, you just logged in to your Flovide account at " . now()->format('Y-m-d H:i:s')
        ));

        // Fetch balances, transactions, chart data
        $balances = \App\Models\Balance::where('personal_id', $account->id)->get();

        $transactions = \App\Models\TransactionHistory::where('personal_id', $account->id)->latest()->take(4)->get()->map(function ($t) {
            return [
                'type'      => $t->type,
                'date'      => $t->created_at->format('Y-m-d H:i:s'),
                'sender'    => $t->sender ?? 'N/A',
                'recipient' => $t->recipient ?? 'N/A',
                'amount'    => $t->currency_symbol . number_format($t->amount, 2),
                'currency'  => $t->currency,
                'status' => $t->status,
                'type' => $t->type,
                'reference' => $t->reference,
                'recipient_details' => [
                    'alias'          => $t->recipient_alias,
                    'account_name'   => $t->recipient_account_name,
                    'account_number' => $t->recipient_account_number,
                    'bank_name'      => $t->recipient_bank_name,
                    'bank_currency'  => $t->recipient_bank_currency,
                ]
            ];
        });

        $months = collect(range(0, 2))->map(function ($i) {
            return now()->subMonths($i)->format('Y-m');
        })->reverse()->values();

        $dbData = \App\Models\TransactionHistory::where('personal_id', $account->id)
            ->where('created_at', '>=', now()->subMonths(3))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as total_amount")
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->pluck('total_amount', 'month');

        $chartData = $months->map(function ($m) use ($dbData) {
            return [
                'month' => $m,
                'total_amount' => $dbData[$m] ?? 0,
            ];
        });


        // ✅ Fetch extra lists
        $countryResponse = $this->fetchcountrylist($request);
        $countries = $countryResponse->getData();
        $beneficiaries = \App\Models\Beneficia::where('personal_id', $account->id)->get();
        $payoutAccounts = \App\Models\BankAccount::where('personal_id', $account->id)->get();
        $virtualCards = \App\Models\VirtualCards::where('personal_id', $account->id)->where('status', 'active')->get();
        $subaccounts = Subaccount::where('personal_id', $account->id)->get();
        $tokenResponse = app(\App\Http\Controllers\Personal\ComplianceController::class)
    ->getSumsubToken($account); // already an array


        return response()->json([
            'data' => [
                'message' => 'Login successful',
                'token' => $token,
                'account_type' => 'personals',
                'personal' => [
                    'id' => $account->id,
                    'firstname' => $account->firstname,
                    'lastname' => $account->lastname,
                    'email' => $account->email,
                    'currency' => $account->currency,
                    'profile_url' => $account->profile_picture
                        ? asset($account->profile_picture)
                        : null,
                    'email_verified_status' => $account->email_verified_status,
                ],
                'compliance' => $account->complianceStatus($tokenResponse),
                'balances' => $balances,
                'transactions' => $transactions,
                'chart' => $chartData,
                'countries' => $countries,
                'beneficiaries' => $beneficiaries,
                'payout_accounts' => $payoutAccounts,
                'virtual_cards' => $virtualCards,
                'subaccounts' => $subaccounts,
            ]
        ], 200);

    }

    public function resendLoginOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:personals,email'
        ]);

        $account = Personal::where('email', $request->email)->first();

        //check if last OTP was sent less than 60 seconds ago
        if ($account->login_otp_expires_at && now()->diffInSeconds($account->login_otp_expires_at->subMinutes(5)) < 60) {
            return response()->json([
                'data' => [
                    'message' => 'You can request a new OTP after 60 seconds.'
                ]
            ], 429); // Too many requests
        }

        // Generate new OTP
        $otp = rand(100000, 999999);

        $account->update([
            'login_otp' => $otp,
            'login_otp_expires_at' => now()->addMinutes(5),
        ]);

        // Send OTP via email
        Mail::to($account->email)->send(new LoginOtpMail($account->firstname, $otp));

        // Send in-app notification
        $account->notify(new GeneralNotification(
            "Your Login OTP",
            "Your new OTP is: {$otp}. It expires in 5 minutes."
        ));

        return response()->json([
            'data' => [
                'message' => 'OTP resent successfully',
                'status' => 'otp_required'
            ]
        ], 200);
    }



    public function fetchcountrylist(Request $request)
    {
        $country_name = $request->get('country_name', 'NG'); 
        $alpha2 = $request->get('alpha2', 'NGN'); 

        $response = Http::withToken(env('OHENTPAY_API_KEY'))
            ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/countries', [
                'country_name' => $country_name,
                'alpha2' => $alpha2
            ]);

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'fields' => $response->json()
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fetch bank fields',
            'details' => $response->json()
        ], $response->status());
    }
}
