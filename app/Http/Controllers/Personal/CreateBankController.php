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

    $balance = Balance::create([
        'personal_id' => $personalId,
        'name'        => $request->name,
        'currency'    => $request->currency,
        'amount'      => 0,
    ]);

    if ($request->expectsJson()) {
        return response()->json([
            'data' => [
                'status'      => 'success',
                'personal_id' => $personalId,
                'message'     => 'Balance created successfully.',
                'balance'     => $balance,
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

    


}
