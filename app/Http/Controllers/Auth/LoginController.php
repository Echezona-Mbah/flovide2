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
use App\Models\Bank;
use App\Models\CountryRule;
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
                'success' => false,
                'message' => 'Invalid credentials',
                'code' => 'INVALID_CREDENTIALS',
                'data' => null
            ], 401);
        }

        $account = Auth::user();

        if ($account->email_verified_status !== 'yes') {
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'Email not verified. Please verify your email.',
                'code' => 'EMAIL_NOT_VERIFIED',
                'data' => [
                    'status' => 'unverified',
                    'verify_url' => url("/api/auth/verify-email/{$account->email}")
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
            'success' => true,
            'message' => 'OTP sent to your email',
            'code' => 'OTP_SENT',
            'data' => [
                'status' => 'otp_required',
                'email' => $account->email
            ]
        ], 200);
    }

   public function verifyUserLoginOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'device_token' => 'nullable|string|max:1000',

        ]);

        $account = User::where('email', $request->email)->first();

        if (!$account || !$account->login_otp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP not requested',
                'code' => 'OTP_NOT_REQUESTED',
                'data' => null
            ], 400);
        }

        if (now()->gt($account->login_otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired',
                'code' => 'OTP_EXPIRED',
                'data' => null
            ], 400);
        }

        if ($account->login_otp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
                'code' => 'OTP_INVALID',
                'data' => null
            ], 400);
        }

        // Clear OTP
        $account->update([
            'login_otp' => null,
            'login_otp_expires_at' => null,
            'device_token' => $request->filled('device_token') ? $request->device_token : $account->device_token,

        ]);

        // Generate token
        $token = $account->createToken('BusinessToken')->plainTextToken;

        $account->notify(new GeneralNotification(
            "Login Successful",
            "Hello {$account->firstname}, you just logged in to your Flovide account at " . now()->format('Y-m-d H:i:s')
        ));

        $balances = \App\Models\Balance::where('user_id', $account->id)
        ->orderBy('created_at', 'asc')
        ->get();

        $defaultBalance = $balances->first();
        $defaultCurrency = strtoupper($defaultBalance?->currency ?? $account->currency ?? 'USD');

        $totalBalance = 0;

        foreach ($balances as $balance) {
            $balanceCurrency = strtoupper($balance->currency);
            $balanceAmount = (float) $balance->amount;

            if ($balanceAmount <= 0) {
                continue;
            }

            if ($balanceCurrency === $defaultCurrency) {
                $totalBalance += $balanceAmount;
                continue;
            }

            $rate = \App\Models\ExchangeRate::whereHas('fromCurrency', function ($q) use ($balanceCurrency) {
                    $q->where('code', $balanceCurrency);
                })
                ->whereHas('toCurrency', function ($q) use ($defaultCurrency) {
                    $q->where('code', $defaultCurrency);
                })
                ->first();

            if ($rate) {
                $totalBalance += $balanceAmount * (float) $rate->rate;
            }
        }


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
                    'method' => $t->method,
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
        $beneficiaries = \App\Models\Beneficia::where('user_id', $account->id)->get();
        $payoutAccounts = \App\Models\BankAccount::where('user_id', $account->id)->get();
        $virtualCards = \App\Models\VirtualCards::where('user_id', $account->id)->where('status', 'active')->get();
        $subaccounts = Subaccount::where('user_id', $account->id)->get();
        $tokenResponse = app(\App\Http\Controllers\Business\ComplianceController::class)->getSumsubToken()->getData(true);
        $countryrule = CountryRule::where('is_active', 1)->select('country_iso', 'country_name', 'currency_iso')->get();
        $currencies = \App\Models\Currency::select('code', 'name', 'symbol', 'country_code')
            ->get()
            ->map(function ($c) {
                return [
                    'code' => $c->code,
                    'name' => $c->name,
                    'symbol' => $c->symbol,
                    'country_code' => strtolower($c->country_code ?? ''),
                ];
            })
            ->values()
            ->toArray();
        $exchangeRates = \App\Models\ExchangeRate::with(['fromCurrency:id,code', 'toCurrency:id,code'])->get()
            ->map(function ($r) {
                return [
                    'from_currency' => $r->fromCurrency->code ?? null,
                    'to_currency' => $r->toCurrency->code ?? null,
                    'rate' => (float) $r->rate,
                    'transfer_fee' => (float) $r->transfer_fee,
                    'updated_at' => $r->updated_at?->format('Y-m-d H:i:s'),
                ];
            });

        // $exchangeRatesLastUpdated = \App\Models\ExchangeRate::max('updated_at');


        return response()->json([
            'success' => true,
            'message' => 'Login verified successfully',
            'code' => 'LOGIN_VERIFIED',
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
                    'referral_code' => $account->referral_code,
                    'referral_link' => $account->referral_link,
                    'email_verified_status' => $account->email_verified_status,

                ],
                'currencies' => $currencies,
                'exchange_rates' => $exchangeRates,
                // 'exchange_rates_last_updated' => optional($exchangeRatesLastUpdated)->format('Y-m-d H:i:s'),
                'countryrule' => $countryrule,
                'compliance' => $account->complianceStatus($tokenResponse),
                'balances' => $balances,
                'total_balance' => number_format($totalBalance, 2, '.', ''),
                'total_balance_currency' => $defaultCurrency,
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

        if ($account->login_otp_expires_at && now()->diffInSeconds($account->login_otp_expires_at->subMinutes(5)) < 300) {
            return response()->json([
                'success' => false,
                'message' => 'You can request a new OTP after 5 minutes.',
                'code' => 'OTP_RATE_LIMIT',
                'data' => null
            ], 429);
        }

        $otp = rand(100000, 999999);
        $account->update([
            'login_otp' => $otp,
            'login_otp_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($account->email)->send(new LoginOtpMail($account->business_name, $otp));
        $account->notify(new GeneralNotification(
            "Your Login OTP",
            "Your new OTP is: {$otp}. It expires in 5 minutes."
        ));

        return response()->json([
            'success' => true,
            'message' => 'OTP resent successfully',
            'code' => 'OTP_RESENT',
            'data' => [
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
                'success' => false,
                'message' => 'Invalid credentials',
                'code' => 'INVALID_CREDENTIALS',
                'data' => null
            ], 401);
        }

        if ($account->email_verified_status !== 'yes') {
            return response()->json([
                'success' => false,
                'message' => 'Email not verified. Please verify your email.',
                'code' => 'EMAIL_NOT_VERIFIED',
                'data' => [
                    'status' => 'unverified',
                    'verify_url' => url("/api/auth/verify-email/{$account->email}")
                ]
            ], 403);
        }

        $otp = rand(100000, 999999);

        $account->update([
            'login_otp' => $otp,
            'login_otp_expires_at' => now()->addMinutes(5),
        ]);

        $account->notify(new GeneralNotification(
            "Your Login OTP",
            "Your OTP is: {$otp}. It expires in 5 minutes."
        ));

        Mail::to($account->email)->send(new LoginOtpMail($account->firstname, $otp));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your email',
            'code' => 'OTP_SENT',
            'data' => [
                'status' => 'otp_required',
                'email' => $account->email
            ]
        ], 200);
    }


    public function verifyLoginOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'device_token' => 'nullable|string|max:1000',
        ]);

        $account = Personal::where('email', $request->email)->first();

        if (!$account || !$account->login_otp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP not requested',
                'code' => 'OTP_NOT_REQUESTED',
                'data' => null
            ], 400);
        }

        if (now()->gt($account->login_otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired',
                'code' => 'OTP_EXPIRED',
                'data' => null
            ], 400);
        }

        if ($account->login_otp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
                'code' => 'OTP_INVALID',
                'data' => null
            ], 400);
        }

        $account->update([
            'login_otp' => null,
            'login_otp_expires_at' => null,
            'device_token' => $request->filled('device_token') ? $request->device_token : $account->device_token,

        ]);

        $token = $account->createToken('PersonalToken')->plainTextToken;

        $account->notify(new GeneralNotification(
            "Login Successful",
            "Hello {$account->firstname}, you just logged in to your Flovide account at " . now()->format('Y-m-d H:i:s')
        ));

        $balances = \App\Models\Balance::where('personal_id', $account->id) ->orderBy('created_at', 'asc')
        ->get();

        $defaultBalance = $balances->first();
        $defaultCurrency = strtoupper($defaultBalance?->currency ?? $account->currency ?? 'USD');

        $totalBalance = 0;

        foreach ($balances as $balance) {
            $balanceCurrency = strtoupper($balance->currency);
            $balanceAmount = (float) $balance->amount;

            if ($balanceAmount <= 0) {
                continue;
            }

            if ($balanceCurrency === $defaultCurrency) {
                $totalBalance += $balanceAmount;
                continue;
            }

            $rate = \App\Models\ExchangeRate::whereHas('fromCurrency', function ($q) use ($balanceCurrency) {
                    $q->where('code', $balanceCurrency);
                })
                ->whereHas('toCurrency', function ($q) use ($defaultCurrency) {
                    $q->where('code', $defaultCurrency);
                })
                ->first();

            if ($rate) {
                $totalBalance += $balanceAmount * (float) $rate->rate;
            }
        }

        $transactions = \App\Models\TransactionHistory::where('personal_id', $account->id)
            ->latest()->take(4)->get()->map(function ($t) {
                return [
                    'type'      => $t->type,
                    'date'      => $t->created_at->format('Y-m-d H:i:s'),
                    'sender'    => $t->sender ?? 'N/A',
                    'recipient' => $t->recipient ?? 'N/A',
                    'amount'    => $t->currency_symbol . number_format($t->amount, 2),
                    'currency'  => $t->currency,
                    'status' => $t->status,
                    'method' => $t->method,
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

        $countryResponse = $this->fetchcountrylist($request);
        $countries = $countryResponse->getData();
        $beneficiaries = \App\Models\Beneficia::where('personal_id', $account->id)->get();
        $payoutAccounts = \App\Models\BankAccount::where('personal_id', $account->id)->get();
        $virtualCards = \App\Models\VirtualCards::where('personal_id', $account->id)->where('status', 'active')->get();
        $subaccounts = Subaccount::where('personal_id', $account->id)->get();
        $tokenResponse = app(\App\Http\Controllers\Personal\ComplianceController::class)
            ->getSumsubToken($account);
        $countryrule = CountryRule::where('is_active', 1)
            ->select('country_iso', 'country_name', 'currency_iso')
            ->get();
        $currencies = \App\Models\Currency::select('code', 'name', 'symbol', 'country_code')
            ->get()
            ->map(function ($c) {
                return [
                    'code' => $c->code,
                    'name' => $c->name,
                    'symbol' => $c->symbol,
                    'country_code' => strtolower($c->country_code ?? ''),
                ];
            })
            ->values()
            ->toArray();
        $exchangeRates = \App\Models\ExchangeRate::with(['fromCurrency:id,code', 'toCurrency:id,code'])->get()
            ->map(function ($r) {
                return [
                    'from_currency' => $r->fromCurrency->code ?? null,
                    'to_currency' => $r->toCurrency->code ?? null,
                    'rate' => (float) $r->rate,
                    'transfer_fee' => (float) $r->transfer_fee,
                    'updated_at' => $r->updated_at?->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'code' => 'LOGIN_VERIFIED',
            'data' => [
                'token' => $token,
                'account_type' => 'personals',
                'personal' => [
                    'id' => $account->id,
                    'firstname' => $account->firstname,
                    'lastname' => $account->lastname,
                    'email' => $account->email,
                    'currency' => $account->currency,
                    'profile_url' => $account->profile_picture ? asset($account->profile_picture) : null,
                    'email_verified_status' => $account->email_verified_status,
                    'referral_code' => $account->referral_code,
                    'referral_link' => $account->referral_link,
                ],
                'currencies' => $currencies,
                'exchange_rates' => $exchangeRates,
                'countryrule' => $countryrule,
                'compliance' => $account->complianceStatus($tokenResponse),
                'balances' => $balances,
                'total_balance' => number_format($totalBalance, 2, '.', ''),
                'total_balance_currency' => $defaultCurrency,
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

        if ($account->login_otp_expires_at && now()->diffInSeconds($account->login_otp_expires_at->subMinutes(5)) < 60) {
            return response()->json([
                'success' => false,
                'message' => 'You can request a new OTP after 60 seconds.',
                'code' => 'OTP_RATE_LIMIT',
                'data' => null
            ], 429);
        }

        $otp = rand(100000, 999999);

        $account->update([
            'login_otp' => $otp,
            'login_otp_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($account->email)->send(new LoginOtpMail($account->firstname, $otp));

        $account->notify(new GeneralNotification(
            "Your Login OTP",
            "Your new OTP is: {$otp}. It expires in 5 minutes."
        ));

        return response()->json([
            'success' => true,
            'message' => 'OTP resent successfully',
            'code' => 'OTP_RESENT',
            'data' => [
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
