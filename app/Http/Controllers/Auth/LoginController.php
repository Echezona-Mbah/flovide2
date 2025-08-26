<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

<<<<<<< HEAD

    public function login(Request $request)
=======
    // Business
    public function loginUser(Request $request)
>>>>>>> 4214aa702807e3d23954c2ccb8c80301d29082d1
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

<<<<<<< HEAD
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if ($user->email_verified_status !== 'yes') {
                Auth::logout(); 
                return response()->json([
                    'message' => 'Email not verified. Please verify your email.',
                    'status' => 'unverified',
                    'verify_url' => url("/api/auth/verify-email/{$user->email}"),
                ], 403);
            }

            $token = $user->createToken('API Token')->plainTextToken;

            $filteredUser = [
                'id' => $user->id,
                'business_name' => $user->business_name,
                'registration_number' => $user->registration_number,
                'incorporation_date' => $user->incorporation_date,
                'business_type' => $user->business_type,
                'company_url' => $user->company_url,
                'industry' => $user->industry,
                'annual_turnover' => $user->annual_turnover,
                'street_address' => $user->street_address,
                'city' => $user->city,
                'trading_address' => $user->trading_address,
                'nature_of_business' => $user->nature_of_business,
                'trading_street_address' => $user->trading_street_address,
                'trading_city' => $user->trading_city,
                'state' => $user->state,
                'typeofuser' => $user->typeofuser,
                'bvn' => $user->bvn,
                'email' => $user->email,
                'email_verified_status' => $user->email_verified_status,
                'forgot_password_token' => $user->forgot_password_token,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];

=======
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $account = Auth::user();

        if ($account->email_verified_status !== 'yes') {
            Auth::logout();
>>>>>>> 4214aa702807e3d23954c2ccb8c80301d29082d1
            return response()->json([
                'data' => [
                    'message' => 'Email not verified. Please verify your email.',
                    'status' => 'unverified',
                    'verify_url' => url("/api/auth/verify-email/{$account->email}"),
                ]
            ], 403);
        }

        $token = $account->createToken('User API Token')->plainTextToken;

        $balances = \App\Models\Balance::where('user_id', $account->id)->get();
        $transactions = \App\Models\TransactionHistory::where('user_id', $account->id)
            ->latest()->take(4)->get();

        $chartData = \App\Models\TransactionHistory::where('user_id', $account->id)
            ->where('created_at', '>=', now()->subMonths(3))
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("SUM(amount) as total_amount")
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $teamMembership = \App\Models\TeamMembers::where('user_id', $account->id)->first();

        return response()->json([
            'data' => [
                'account_type' => 'business',
                'business' => [
                    'id' => $account->id,
                    'firstname' => $account->firstname,
                    'lastname' => $account->lastname,
                    'email' => $account->email,
                    'currency' => $account->currency,
                    'email_verified_status' => $account->email_verified_status,
                ],
                'balances' => $balances,
                'transactions' => $transactions,
                'chart' => $chartData,
                'owner_id' => $teamMembership ? ($teamMembership->userOwner->id ?? null) : $account->id,
                'role' => $teamMembership ? ($teamMembership->role ?? 'member') : 'Owner',
                'token' => $token,
            ]
        ], 200);
    }

<<<<<<< HEAD
=======
    public function loginPersonal(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Manually authenticate since we're not using session guard here
        $account = Personal::where('email', $request->email)->first();

        if (!$account || !Hash::check($request->password, $account->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
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

        // ✅ Create Sanctum token
        $token = $account->createToken('PersonalToken')->plainTextToken;

        $balances = \App\Models\Balance::where('personal_id', $account->id)->get();
        $transactions = \App\Models\TransactionHistory::where('personal_id', $account->id)
            ->latest()->take(4)->get();

        $chartData = \App\Models\TransactionHistory::where('personal_id', $account->id)
            ->where('created_at', '>=', now()->subMonths(3))
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("SUM(amount) as total_amount")
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json([
            'data' => [
                'account_type' => 'personals',
                'personal' => [
                    'id' => $account->id,
                    'firstname' => $account->firstname,
                    'lastname' => $account->lastname,
                    'email' => $account->email,
                    'currency' => $account->currency,
                    'email_verified_status' => $account->email_verified_status,
                ],
                'balances' => $balances,
                'transactions' => $transactions,
                'chart' => $chartData,
                'token' => $token,
            ]
        ], 200);
    }


>>>>>>> 4214aa702807e3d23954c2ccb8c80301d29082d1
    
}
