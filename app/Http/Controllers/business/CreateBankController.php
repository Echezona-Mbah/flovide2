<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Balance;
use App\Traits\CurrencyHelper;
use Illuminate\Support\Facades\Auth;


class CreateBankController extends Controller
{
      use CurrencyHelper;

    // public function create() {
    //     $countries = Countries::all();
    //     $user = auth()->user();
    //     $balances = Balance::where('user_id', $user->id)->get();
    //     foreach ($balances as $balance) {
    //     $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
    //     }
    
    //     return view('business.add_bank', compact('countries', 'balances'));
    // }
    public function create(Request $request)
    {
        $countries = Countries::all();
        $user = auth()->user();
        $balances = Balance::where('user_id', $user->id)->get();


        // Add currency meta if balances exist
        foreach ($balances as $balance) {
            $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
        }

        // If API request
        if ($request->expectsJson()) {
            if ($balances->isEmpty()) {
                return response()->json([
                    'message' => 'No balance created for this user',
                    'success' => false,
                    'data' => [
                        'countries' => $countries,
                        'balances' => [],
                    ],
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ], 200);
            }

            return response()->json([
                'message' => 'Data for add bank form loaded successfully',
                'success' => true,
                'data' => [
                    'countries' => $countries,
                    'balances' => $balances
                ],
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 200);
        }

        return view('business.add_bank', compact('countries', 'balances'));
    }


    public function createBalance(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'currency' => 'required|string',
        ]);

        $userId = Auth::id() ?? $request->user_id;

        $balance = Balance::create([
            'user_id' => $userId,
            'name' => $request->name,
            'currency' => $request->currency,
            'balance' => 0,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Balance created successfully.',
                'data' => $balance
            ], 201);
        }

        return redirect()->route('add_account.create')->with('success', 'Account created successfully.');
    }

    // public function createBalance(Request $request)
    // {

    //     $request->validate([
    //         'name' => 'required|string',
    //         'currency' => 'required|string',
    //     ]);
    
    //     $payload = $request->only(['name', 'currency']);
    
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('OHENTPAY_API_KEY'),
    //         'Accept' => 'application/json',
    //         'Content-Type' => 'application/json',
    //     ])->post(env('OHENTPAY_BASE_URL') . '/balances', $payload);
    
    //     if ($response->successful()) {
    //         return response()->json($response->json());
    //     } else {

    //         return redirect()->route('add_account.create')->with('success', 'Account Create successfully.');

    //     }

    // }


    public function getUserTotalBalance(Request $request)
    {
        $user = auth()->user();

        $total = Balance::where('user_id', $user->id)->sum('amount');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User total balance fetched successfully',
                'success' => true,
                'user_id' => $user->id,
                'total_balance' => $total,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 200);
        }

        return view('business.user_balance_total', [
            'total' => $total
        ]);
    }



    public function UpdateBalance(Request $request)
    {
        $request->validate([
            'balance_id' => 'required', 
            'name' => 'required|string|max:255',
        ]);

        $balanceId = $request->input('balance_id');
        $newName = $request->input('name');

        $balance = Balance::where('id', $balanceId)->first();

        if (!$balance) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Balance not found.'], 404)
                : redirect()->back()->with('error', 'Balance not found.');
        }

        $balance->name = $newName;
        $balance->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Balance updated successfully.',
                'data' => $balance,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 200);
        }

        return redirect()->back()->with('success', 'Balance updated successfully.');
    }

    public function index(Request $request)
        {
            $balances = Balance::all(); // Eager load user if needed

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'All balances retrieved successfully',
                    'success' => true,
                    'data' => $balances,
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                ], 200);
            }

            return view('business.all_balances', compact('balances'));
        }

    

    // private function getCountryCodeFromCurrency($currency)
    // {
    //     $map = [
    //         'NGN' => ['symbol' => '₦', 'country' => 'ng'],
    //         'USD' => ['symbol' => '$', 'country' => 'us'],
    //         'KES' => ['symbol' => 'KSh', 'country' => 'ke'],
    //         'GHS' => ['symbol' => '₵', 'country' => 'gh'],
    //         'ZAR' => ['symbol' => 'R', 'country' => 'za'],
    //         'GBP' => ['symbol' => '£', 'country' => 'gb'],
    //         'EUR' => ['symbol' => '€', 'country' => 'eu'],
    //         'CAD' => ['symbol' => 'C$', 'country' => 'ca'],
    //         'CZK' => ['symbol' => 'Kč', 'country' => 'cz'],
    //         'DKK' => ['symbol' => 'kr', 'country' => 'dk'],
    //         'AUD' => ['symbol' => 'A$', 'country' => 'au'],
    //         'SEK' => ['symbol' => 'kr', 'country' => 'se'],
    //         'RON' => ['symbol' => 'lei', 'country' => 'ro'],
    //         'PLN' => ['symbol' => 'zł', 'country' => 'pl'],
    //         'CHF' => ['symbol' => 'CHF', 'country' => 'ch'],
    //         'HUF' => ['symbol' => 'Ft', 'country' => 'hu'],
    //         'NOK' => ['symbol' => 'kr', 'country' => 'no'],
    //     ];

    //     return $map[strtoupper($currency)] ?? ['symbol' => '', 'country' => 'us'];
    // }

    // public function UpdateBalance(Request $request)
    // {
    //     $request->validate([
    //         'balance_id' => 'required|string',
    //         'name' => 'required|string|max:255',
    //     ]);
    
    //     $balanceId = $request->input('balance_id');
    //     $newName = $request->input('name');
    
    //     $url = rtrim(env('OHENTPAY_BASE_URL'), '/') . '/balances/' . $balanceId;
    
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('OHENTPAY_API_KEY'),
    //         'Accept' => 'application/json',
    //         'Content-Type' => 'application/json',
    //     ])->patch($url, [
    //         'name' => $newName,
    //     ]);
    
    //     if ($request->expectsJson()) {
    //         return response()->json([
    //             'message' => $response->successful()
    //                 ? 'Balance name updated successfully.'
    //                 : ($response->json()['message'] ?? 'Failed to update balance name.'),
    //             'data' => $response->json()?? null,
    //             'method' => $request->method(),
    //             'url' => $request->fullUrl()
    //         ], $response->status());
    //     }
    
    //     // Web response
    //     if ($response->successful()) {
    //         return redirect()->back()->with('success', 'Balance name updated successfully.');
    //     } else {
    //         return redirect()->back()->with('error', 'Failed to update balance name: ' . $response->body());
    //     }
    // }
    
    
    





    
}
