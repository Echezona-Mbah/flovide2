<?php

namespace App\Http\Controllers\Personal;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\BankAccount;
use App\Models\Bank;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class addBankAccountController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:' . (request()->is('api/*') ? 'sanctum' : 'web'));
    }

    public function payouts(Request $request)
    {
        $user = Auth::guard('personal-api')->user();
        $bankAccounts = BankAccount::where('personal_id', $user->id)->get();
        $response = Http::withToken(env('OHENTPAY_API_KEY'))->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/countries');
        $countries = [];

        if ($response->successful()) {
            $countries = $response->json();
            // dd($countries);
        }
        // Fetch all banks
        $banks = Bank::all();

        if ($request->expectsJson()) {
            if ($bankAccounts->isEmpty()) {
                return response()->json([
                    'data' => [
                        'message' => 'No bank account found.'
                    ]
                ], 404);
            }

            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Bank accounts and countries retrieved successfully.',
                    'bankaccounts' => $bankAccounts,
                    'countries' => $countries
                ]
            ], 200);
        }

        // return view('business.payouts', ['bankAccounts' => $bankAccounts, 'countries' => $countries, 'banks' => $banks]);
    }


    public function store(Request $request)
    {
        $isDynamic = $request->input('formDynamicFields') === 'true';

        if (!$isDynamic) {
            // Static fields (NGN, GHS, KES accounts)
            $validated = $request->validate([
                'account_name' => 'required|string|max:255',
                'account_number' => ['required', 'regex:/^\d{10}$/'],
                'bank_country' => 'required|string|max:50',
                'bank_name' => 'required|string|max:255',
                'type' => 'required|string|max:10',
                'currency' => 'required|string|size:3',
                'bic' => 'nullable|string|size:8|regex:/^[A-Za-z0-9]{8,11}$/',
                'iban' => 'nullable|string|max:34|regex:/^[A-Za-z0-9]+$/',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'zipcode' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
            ]);
        } else {
            // Dynamic fields (AU_AUD, US_USD, etc.)
            $validated = $request->validate([
                'account_name' => 'required|string|max:255',
                'bank_country' => 'required|string|max:50',
                'currency' => 'required|string|size:3', // ISO 4217 currency code
                'bic' => 'required|string|size:8|regex:/^[A-Za-z0-9]{8,11}$/', // SWIFT/BIC
                'iban' => 'required|string|max:34|regex:/^[A-Za-z0-9]+$/', // IBAN format
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'zipcode' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'type' => 'required|string|max:10',
            ]);
        }

        $user = Auth::guard('personal-api')->user();

        $isFirst = BankAccount::where('personal_id', $user->id)->count() === 0;

        $bankAccount = BankAccount::create([
            'personal_id' => $user->id,
            'account_name' => $validated['account_name'] ?? null,
            'account_number' => isset($validated['account_number']) ? Crypt::encryptString($validated['account_number']) : "",
            'bank_country' => $validated['bank_country'] ?? "",
            'bank_name' => $validated['bank_name'] ?? "",
            'currency' => $validated['currency'],
            'bic' => isset($validated['bic']) ? Crypt::encryptString($validated['bic']) : null,
            'iban' => isset($validated['iban']) ? Crypt::encryptString($validated['iban']) : null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'zipcode' => $validated['zipcode'] ?? null,
            'recipient_address' => $validated['address'] ?? null,
            'type' => $validated['type'] ?? null,
            'default' => $isFirst
        ]);

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Bank account added successfully.',
                'bankaccount' => [
                    'id' => $bankAccount->id,
                    'account_name' => $bankAccount->account_name,
                    'account_number' => isset($validated['account_number']) ? substr($validated['account_number'], -4): null,
                    'bank_name' => $bankAccount->bank_name,
                ]
            ]
        ], 201);
    }


    public function destroy($id)
    {
        $account = BankAccount::find($id);

        if (!$account) {
            return response()->json([
                'data' => [
                    'message' => 'Bank account not found.'
                ]
            ], 404);
        }

        $user = Auth::guard('personal-api')->user();

        if ($account->personal_id !== $user->id) {
            return response()->json([
                'data' => [
                    'message' => 'Unauthorized.'
                ]
            ], 403);
        }

        $account->delete();

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Bank account deleted successfully.'
            ]
        ], 200);
    }



    public function destroyAll()
    {
        $user = Auth::guard('personal-api')->user();

        $deletedCount = BankAccount::where('personal_id', $user->id)->delete();

        if ($deletedCount === 0) {
            return response()->json([
                'data' => [
                    'message' => 'No accounts to delete.'
                ]
            ], 404);
        } else {

            return response()->json([
                'data' => [
                    'message' => 'All your bank accounts have been deleted.',
                    'deleted_count' => $deletedCount
                ]
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $type = $request->input('type');
        if(!$type) {
            return response()->json([
                'data' => [
                    'message' => 'Account type is required.'
                ]
            ], 400);
        }
        // Validate the request
        if ($type === 'local') {
            $request->validate([
                'bank_name' => 'required|string|max:255',
                'bank_country' => 'required|string|max:10',
                'account_number' => ['required', 'regex:/^\d{10}$/'],
                'account_name' => 'required|string|max:255',
                'currency' => 'required|string|size:3',
            ]);
        } elseif ($type === 'foreign') {
            $request->validate([
                'bank_country' => 'required|string|max:10',
                'bic' => 'required|string|size:8|regex:/^[A-Za-z0-9]{8,11}$/', // SWIFT/BIC
                'iban' => 'required|string|max:34|regex:/^[A-Za-z0-9]+$/', // IBAN format
                'account_name' => 'required|string|max:255',
                'city' => 'required|string|max:25',
                'state' => 'required|string|max:25',
                'address' => 'required|string|max:255',
                'zipcode' => 'required|string|max:20',
                'currency' => 'required|string|size:3',
            ]);

        }else{
            return response()->json([
                'data' => [
                    'message' => 'Invalid account type.'
                ]
            ], 400);
        }

        $bankAccount = BankAccount::find($id);

        if (!$bankAccount) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => [
                        'message' => 'Bank account not found.'
                    ]
                ], 404);
            }
            // return redirect()->back()->with('error', 'Bank account not found.');
        }

        $user = Auth::guard('personal-api')->user();

        if ($bankAccount->personal_id !== $user->id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => [
                        'message' => 'Unauthorized.'
                    ]
                ], 403);
            }
            // return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Update the bank account
        $bankAccount->update([
            'bank_name' => $request->bank_name ?? "",
            'bank_country' => $request->bank_country ?? "",
            'account_number' => Crypt::encryptString($request->iban) ?? "",
            'account_name' => $request->account_name ?? "",
            'currency' => $request->currency ?? null,
            'bic' => isset($request->bic) ? Crypt::encryptString($request->bic) : null,
            'iban' => isset($request->iban) ? Crypt::encryptString($request->iban) : null,
            'city' => $request->city ?? null,
            'state' => $request->state ?? null,
            'recipient_address' => $request->address ?? null,
            'zipcode' => $request->zipcode ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'message' => 'Bank account updated successfully.',
                    'data' => $bankAccount
                ]
            ]);
        }

        // return redirect()->route('business.edit', $id)->with('success', 'Bank account updated successfully.');
    }


    public function edit($id)
    {
        // $bankAccount = BankAccount::findOrFail($id);

        $user = Auth::guard('personal-api')->user();

        $bankAccount = BankAccount::where('id', $id)
            ->where('personal_id', $user->id)
            ->whereNull('deleted_at')->firstOrFail();
        $response = Http::withToken(env('OHENTPAY_API_KEY'))->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/countries');
        $countries = [];

        if ($response->successful()) {
            $countries = $response->json();
            // dd($countries);
        }

        // Fetch all banks
        $banks = Bank::all();

        if ($bankAccount->personal_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $allUserAccounts = BankAccount::where('personal_id', $user->id)->get();

        //api response
        if (request()->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Bank account retrieved successfully.',
                    'data' => $bankAccount,
                    'countries' => $countries,
                    'banks' => $banks
                ]
            ], 200);
        }

        // return view('business.editPayout', compact('bankAccount', 'allUserAccounts', 'countries', 'banks'));
    }

    public function setDefault(Request $request, $id)
    {
        $user = Auth::guard('personal-api')->user();
        // $account = BankAccount::findOrFail($id);
        $account = BankAccount::where('id', $id)
            ->where('personal_id', $user->id)->whereNull('deleted_at')->firstOrFail();

        if ($account->personal_id !== $user->id) {
            return response()->json([
                'data' => [
                    'message' => 'Unauthorized'
                ]
            ], 403);
        }

        BankAccount::where('personal_id', $user->id)->update(['default' => false]);
        $account->default = true;
        $account->save();

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Payout account set.',
                'default_account_id' => $account->id
            ]
        ]);
    }


    public function fetchlocalBanks(Request $request)
    {
        $countryCurrency = strtolower($request->get('countryCurrency')); // Default to NG
        $currency = strtolower($request->get('currency')); // Default to NGN

        try {
            $response = Http::withToken(env('OHENTPAY_API_KEY'))
                ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/bankfields', [
                    'country' => $countryCurrency,
                    'currency' => $currency
                ]);

            if ($response->successful()) {
                return response()->json([
                    'data' => [
                        'status' => 'success',
                        'message' => 'Bank fields fetched successfully.',
                        'fields' => $response->json()
                    ]
                ]);
            }

            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => 'Failed to fetch bank fields',
                    'details' => $response->json()
                ]
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => 'Failed to fetch bank fields',
                    'details' => $e->getMessage()
                ]
            ], 500);
        }
    }

    public function validatePayoutAccountName(Request $request)
    {
        $request->validate([
            'country' => 'required|string',
            'currency' => 'required|string',
            'bank_id' => 'required|string',
            'account_number' => 'required|string',
        ]);

        $payload = $request->only([
            'country', 'currency', 'bank_id', 'account_number'
        ]);

        $response = Http::withToken(env('OHENTPAY_API_KEY'))->post(
            rtrim(env('OHENTPAY_BASE_URL'), '/') . '/recipients/validate', $payload
        );

        $data = $response->json();

        if ($response->successful()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Payout account validated successfully.',
                    'account_name' => $data['account_name'] ?? null,
                    'payout' => $data
                ]
            ], 200);
        }


        return response()->json([
            'data' => [
                'status' => 'error',
                'message' => 'Invalid payout account.',
                'data' => $response->json()
            ]
        ], $response->status());
    }

}
