<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    // Business
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
            ]], 401);
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
        $countries = \App\Models\Countries::all();  
        $beneficiaries = \App\Models\Beneficia::where('user_id', $account->id)->get();
        $payoutAccounts = \App\Models\BankAccount::where('user_id', $account->id)->get();
        $virtualCards = \App\Models\VirtualCards::where('user_id', $account->id)->where('status', 'active')->get();


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
                    ? asset('storage/'.$account->profile_picture) 
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
                ]], 401);
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

        // ✅ Fetch extra lists
        $countries = \App\Models\Countries::all();  
        $beneficiaries = \App\Models\Beneficia::where('personal_id', $account->id)->get();
        $payoutAccounts = \App\Models\BankAccount::where('personal_id', $account->id)->get();
        $virtualCards = \App\Models\VirtualCards::where('personal_id', $account->id)->where('status', 'active')->get();

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
                    ? asset('storage/'.$account->profile_picture) 
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
                'token' => $token,
            ]
        ], 200);
    }



    
}
