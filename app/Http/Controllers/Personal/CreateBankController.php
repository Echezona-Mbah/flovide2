<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Countries;
use Illuminate\Support\Facades\Http;
use App\Models\Balance;
use App\Traits\CurrencyHelper;
use Illuminate\Support\Facades\Auth;

class CreateBankController extends Controller
{
    use CurrencyHelper;

public function create(Request $request)
{
    $countries  = Countries::all();
    $personalId = auth('personal-api')->id();
    $balances   = Balance::where('personal_id', $personalId)->get();

    foreach ($balances as $balance) {
        $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
    }

    if ($request->expectsJson()) {
        if ($balances->isEmpty()) {
            return response()->json([
                'data' => [
                    'status'      => 'error',
                    'personal_id' => $personalId,
                    'errors'     => 'No balance created for this personal account',
                    'balances'    => [],
                ]
            ], 200);
        }

        return response()->json([
            'data' => [
                'status'      => 'success',
                'personal_id' => $personalId,
                'message'     => 'Data for add bank form loaded successfully',
                'balances'    => $balances,
            ]
        ], 200);
    }

    return view('business.add_bank', compact('countries', 'balances'));
}

public function createBalance(Request $request)
{
    $request->validate([
        'name'     => 'required|string',
        'currency' => 'required|string',
    ]);

    $personalId = auth('personal-api')->id();
    $currency   = strtoupper($request->currency);

    // 🔎 Check if user already has money in any balance
    $hasMoney = Balance::where('personal_id', $personalId)
        ->where('amount', '>', 0)
        ->exists();

    if (!$hasMoney) { // ❌ Block if no funds exist
        $errorMessage = 'You must have funds in at least one balance before creating a new one';

        return $request->expectsJson()
            ? response()->json([
                'data' => [
                    'status' => 'error',
                    'errors' => $errorMessage
                ]
            ], 400)
            : redirect()->back()->withErrors(['message' => $errorMessage]);
    }

    // 🔎 Check if user already has this currency
    $exists = Balance::where('personal_id', $personalId)
        ->where('currency', $currency)
        ->exists();

    if ($exists) {
        $errorMessage = "You already have a $currency balance. Duplicates are not allowed.";

        return $request->expectsJson()
            ? response()->json([
                'data' => [
                    'status' => 'error',
                    'errors' => $errorMessage
                ]
            ], 400)
            : redirect()->back()->withErrors(['message' => $errorMessage]);
    }

    // ✅ Create new balance
    $balance = Balance::create([
        'personal_id' => $personalId,
        'name'        => $request->name,
        'currency'    => $currency,
        'amount'      => 0,
    ]);

    if ($request->expectsJson()) {
        return response()->json([
            'data' => [
                'status'  => 'success',
                'message' => 'Balance created successfully.',
                'data'    => $balance,
            ]
        ], 201);
    }

    return redirect()->route('add_account.create')->with('success', 'Account created successfully.');
}




public function getUserTotalBalance(Request $request)
{
    $personalId = auth('personal-api')->id();
    $total      = Balance::where('personal_id', $personalId)->sum('amount');

    if ($request->expectsJson()) {
        return response()->json([
            'data' => [
                'status'       => 'success',
                'personal_id'  => $personalId,
                'message'      => 'Personal total balance fetched successfully',
                'total_balance'=> $total,
            ]
        ], 200);
    }
}

public function updateBalance(Request $request)
{
    $request->validate([
        'balance_id' => 'required',
        'name'       => 'required|string|max:255',
    ]);

    $personalId = auth('personal-api')->id();

    $balance = Balance::where('id', $request->balance_id)
        ->where('personal_id', $personalId)
        ->first();

    if (!$balance) {
        return $request->expectsJson()
            ? response()->json([
                'data' => [
                    'status'      => 'error',
                    'personal_id' => $personalId,
                    'errors'     => 'Balance not found.',
                ]
            ], 404)
            : redirect()->back()->with('error', 'Balance not found.');
    }

    $balance->name = $request->name;
    $balance->save();

    if ($request->expectsJson()) {
        return response()->json([
            'data' => [
                'status'      => 'success',
                'personal_id' => $personalId,
                'message'     => 'Balance updated successfully.',
                'balance'     => $balance,
            ]
        ], 200);
    }
}

public function index(Request $request)
{
    $personalId = auth('personal-api')->id();
    $balances   = Balance::where('personal_id', $personalId)->get();

    if ($request->expectsJson()) {
        return response()->json([
            'data' => [
                'status'      => 'success',
                'personal_id' => $personalId,
                'message'     => 'All balances retrieved successfully',
                'balances'    => $balances,
            ]
        ], 200);
    }
}

    public function dashboardapi(Request $request)
{
    $account = auth('personal-api')->id();

    if (!$account) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }

    $balances = \App\Models\Balance::where('personal_id', $account)->get();
    $transactions = \App\Models\TransactionHistory::where('personal_id', $account)
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
                'status'    => $t->status,
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


    // -------------------------
    // 3️⃣ CHART DATA (LAST 3 MONTHS)
    // -------------------------
    $months = collect(range(0, 2))->map(function ($i) {
        return now()->subMonths($i)->format('Y-m');
    })->reverse()->values();

    $dbData = \App\Models\TransactionHistory::where('personal_id', $account)
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


    // -------------------------
    // 4️⃣ RETURN JSON FOR API
    // -------------------------
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Dashboard data fetched successfully',
            'data' => [
                'balances'     => $balances,
                'chart_data'   => $chartData,
                'recent_history' => $transactions,
            ]
        ], 200);
    }

    // -------------------------
    // 5️⃣ RETURN WEB VIEW
    // -------------------------
    return view('dashboard.index', [
        'balances' => $balances,
        'chartData' => $chartData,
        'transactions' => $transactions,
    ]);
}

    


}
