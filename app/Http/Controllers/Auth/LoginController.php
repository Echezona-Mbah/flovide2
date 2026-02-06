<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use App\Models\Subaccount;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;
use App\Models\LoginActivity;

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


    // Business


    // -----------------------
    // Business login -> returns dashboard + token
    // -----------------------
    // public function loginUser(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string',
    //     ]);

    //     if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         return response()->json([
    //             'data' => [
    //                 'message' => 'Invalid credentials'
    //             ]
    //         ], 401);
    //     }

    //     $account = Auth::user();

    //     if ($account->email_verified_status !== 'yes') {
    //         Auth::logout();
    //         return response()->json([
    //             'data' => [
    //                 'message' => 'Email not verified. Please verify your email.',
    //                 'status' => 'unverified',
    //                 'verify_url' => url("/api/auth/verify-email/{$account->email}"),
    //             ]
    //         ], 403);
    //     }

    //     $token = $account->createToken('User API Token')->plainTextToken;

    //     // Notification
    //     try {
    //         $account->notify(new GeneralNotification(
    //             "Login Successful ✅",
    //             "Hello {$account->firstname}, you just logged in to your Flovide account at " . now()->format('Y-m-d H:i:s')
    //         ));
    //     } catch (\Throwable $e) {
    //         // swallow notification errors — logging is optional
    //         \Log::warning("Notification failed on login: " . $e->getMessage());
    //     }

    //     // BALANCES
    //     $balances = Balance::where('user_id', $account->id)->get();

    //     // RECENT TRANSACTIONS (last 3)
    //     $transactions = TransactionHistory::where('user_id', $account->id)
    //         ->latest()
    //         ->take(3)
    //         ->get()
    //         ->map(function ($t) {
    //             return [
    //                 'type'      => $t->type,
    //                 'date'      => $t->created_at->format('Y-m-d H:i:s'),
    //                 'sender'    => $t->sender ?? 'N/A',
    //                 'recipient' => $t->recipient ?? 'N/A',
    //                 'amount'    => ($t->currency_symbol ?? '') . number_format($t->amount, 2),
    //                 'currency'  => $t->currency,
    //                 'status'    => $t->status,
    //                 'reference' => $t->reference,
    //                 'recipient_details' => [
    //                     'alias'          => $t->recipient_alias,
    //                     'account_name'   => $t->recipient_account_name,
    //                     'account_number' => $t->recipient_account_number,
    //                     'bank_name'      => $t->recipient_bank_name,
    //                     'bank_currency'  => $t->recipient_bank_currency,
    //                 ]
    //             ];
    //         });

    //     // CHART: sums per month for last 3 months
    //     $chartData = TransactionHistory::where('user_id', $account->id)
    //         ->where('created_at', '>=', now()->subMonths(3))
    //         ->select(
    //             DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
    //             DB::raw("SUM(amount) as total_amount")
    //         )
    //         ->groupBy('month')
    //         ->orderBy('month', 'asc')
    //         ->get();

    //     // Extra resources
    //     $teamMembership = TeamMembers::where('user_id', $account->id)->first();
    //     $countries = Countries::all();
    //     $beneficiaries = Beneficia::where('user_id', $account->id)->get();
    //     $payoutAccounts = BankAccount::where('user_id', $account->id)->get();
    //     $virtualCards = VirtualCards::where('user_id', $account->id)->where('status', 'active')->get();
    //     $subaccounts = Subaccount::where('user_id', $account->id)->get();

    //     // RECORD LOGIN ACTIVITY (non-blocking)
    //     // $this->recordLoginActivity($request, $account);

    //     // Build response (dashboard nested)
    //     return response()->json([
    //         'data' => [
    //             'dashboard' => [
    //                 'account_type' => 'business',
    //                 'business' => [
    //                     'id' => $account->id,
    //                     'firstname' => $account->firstname,
    //                     'lastname' => $account->lastname,
    //                     'email' => $account->email,
    //                     'currency' => $account->currency,
    //                     'profile_url' => $account->profile_picture ? asset($account->profile_picture) : null,
    //                     'email_verified_status' => $account->email_verified_status,
    //                 ],
    //                 'balances' => $balances,
    //                 'recent_transactions' => $transactions,
    //                 'chart' => $chartData,
    //                 'countries' => $countries,
    //                 'beneficiaries' => $beneficiaries,
    //                 'payout_accounts' => $payoutAccounts,
    //                 'virtual_cards' => $virtualCards,
    //                 'subaccounts' => $subaccounts,
    //                 'owner_id' => $teamMembership ? ($teamMembership->userOwner->id ?? null) : $account->id,
    //                 'role' => $teamMembership ? ($teamMembership->role ?? 'member') : 'Owner',
    //             ],
    //             'token' => $token,
    //         ]
    //     ], 200);
    // }

    // -----------------------
    // Personal login -> returns dashboard + token
    // -----------------------
    // public function loginPersonal(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string',
    //     ]);

    //     $account = Personal::where('email', $request->email)->first();

    //     if (!$account || !Hash::check($request->password, $account->password)) {
    //         return response()->json([
    //             'data' => [
    //                 'message' => 'Invalid credentials'
    //             ]
    //         ], 401);
    //     }

    //     if ($account->email_verified_status !== 'yes') {
    //         return response()->json([
    //             'data' => [
    //                 'message' => 'Email not verified. Please verify your email.',
    //                 'status' => 'unverified',
    //                 'verify_url' => url("/api/auth/verify-email/{$account->email}"),
    //             ]
    //         ], 403);
    //     }

    //     $token = $account->createToken('PersonalToken')->plainTextToken;

    //     try {
    //         $account->notify(new GeneralNotification(
    //             "Login Successful ✅",
    //             "Hello {$account->firstname}, you just logged in to your Flovide account at " . now()->format('Y-m-d H:i:s')
    //         ));
    //     } catch (\Throwable $e) {
    //         \Log::warning("Notification failed on personal login: " . $e->getMessage());
    //     }

    //     // BALANCES
    //     $balances = Balance::where('personal_id', $account->id)->get();

    //     // RECENT TRANSACTIONS (last 3)
    //     $transactions = TransactionHistory::where('personal_id', $account->id)
    //         ->latest()
    //         ->take(3)
    //         ->get()
    //         ->map(function ($t) {
    //             return [
    //                 'type'      => $t->type,
    //                 'date'      => $t->created_at->format('Y-m-d H:i:s'),
    //                 'sender'    => $t->sender ?? 'N/A',
    //                 'recipient' => $t->recipient ?? 'N/A',
    //                 'amount'    => ($t->currency_symbol ?? '') . number_format($t->amount, 2),
    //                 'currency'  => $t->currency,
    //                 'status'    => $t->status,
    //                 'reference' => $t->reference,
    //                 'recipient_details' => [
    //                     'alias'          => $t->recipient_alias,
    //                     'account_name'   => $t->recipient_account_name,
    //                     'account_number' => $t->recipient_account_number,
    //                     'bank_name'      => $t->recipient_bank_name,
    //                     'bank_currency'  => $t->recipient_bank_currency,
    //                 ]
    //             ];
    //         });

    //     // CHART
    //     $chartData = TransactionHistory::where('personal_id', $account->id)
    //         ->where('created_at', '>=', now()->subMonths(3))
    //         ->select(
    //             DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
    //             DB::raw("SUM(amount) as total_amount")
    //         )
    //         ->groupBy('month')
    //         ->orderBy('month', 'asc')
    //         ->get();

    //     // Extra resources
    //     $countries = Countries::all();
    //     $beneficiaries = Beneficia::where('personal_id', $account->id)->get();
    //     $payoutAccounts = BankAccount::where('personal_id', $account->id)->get();
    //     $virtualCards = VirtualCards::where('personal_id', $account->id)->where('status', 'active')->get();
    //     $subaccounts = Subaccount::where('personal_id', $account->id)->get();

    //     // RECORD LOGIN ACTIVITY (non-blocking)
    //     // $this->recordLoginActivity($request, $account);

    //     return response()->json([
    //         'data' => [
    //             'dashboard' => [
    //                 'account_type' => 'personal',
    //                 'personal' => [
    //                     'id' => $account->id,
    //                     'firstname' => $account->firstname,
    //                     'lastname' => $account->lastname,
    //                     'email' => $account->email,
    //                     'currency' => $account->currency,
    //                     'profile_url' => $account->profile_picture ? asset($account->profile_picture) : null,
    //                     'email_verified_status' => $account->email_verified_status,
    //                 ],
    //                 'balances' => $balances,
    //                 'recent_transactions' => $transactions,
    //                 'chart' => $chartData,
    //                 'countries' => $countries,
    //                 'beneficiaries' => $beneficiaries,
    //                 'payout_accounts' => $payoutAccounts,
    //                 'virtual_cards' => $virtualCards,
    //                 'subaccounts' => $subaccounts,
    //             ],
    //             'token' => $token,
    //         ]
    //     ], 200);
    // }

//     public function recordLoginActivity(Request $request, $user)
// {
//     try {
//         $agent = new Agent();

//         $ip = $request->ip();
//         $device  = $agent->device() ?: 'Unknown Device';
//         $browser = $agent->browser() ?: 'Unknown Browser';
//         $os      = $agent->platform() ?: 'Unknown OS';

//         // Default location
//         $location = 'Unknown';

//         // Fetch location from IP-API (non-blocking)
//         try {
//             $response = @file_get_contents("http://ip-api.com/json/{$ip}");
//             $details = json_decode($response, true);

//             if (!empty($details) && $details['status'] === 'success') {
//                 $city = $details['city'] ?? '';
//                 $country = $details['country'] ?? '';
//                 $location = trim($city . ', ' . $country, ', ');
//             }
//         } catch (\Throwable $e) {
//             $location = 'Unknown';
//         }

//         // Identify account type
//         $userId = property_exists($user, 'user_type') && $user->user_type == 'personal'
//             ? null
//             : $user->id;

//         $personalId = property_exists($user, 'user_type') && $user->user_type == 'personal'
//             ? $user->id
//             : null;

//         LoginActivity::create([
//             'user_id'     => $userId,
//             'personal_id' => $personalId,
//             'ip_address'  => $ip,
//             'device'      => $device,
//             'browser'     => $browser,
//             'os'          => $os,
//             'location'    => $location,
//             'login_time'  => now(),
//         ]);

//     } catch (\Throwable $e) {
//         \Log::error("Login activity failed: " . $e->getMessage());
//     }
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

        $token = $account->createToken('User API Token')->plainTextToken;


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
            // dd($chartData);


        //RECORD LOGIN ACTIVITY
        // $this->recordLoginActivity($request, $account);
        
        return response()->json([
            'data' => [
                'account_type' => 'business',
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
                'token' => $token,
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


        return response()->json([
            'data' => [
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
                'balances' => $balances,
                'transactions' => $transactions,
                'chart' => $chartData,
                'countries' => $countries,
                'beneficiaries' => $beneficiaries,
                'payout_accounts' => $payoutAccounts,
                'virtual_cards' => $virtualCards,
                'subaccounts' => $subaccounts,
                'token' => $token,
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
