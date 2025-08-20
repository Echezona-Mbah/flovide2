<?php

namespace App\Http\Controllers\business;

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
        $user = Auth::user();
        $bankAccounts = BankAccount::where('user_id', $user->id)->get();
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
                return response()->json(['message' => 'No bank account found.'], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Bank accounts and countries retrieved successfully.',
                'data' => $bankAccounts,
                'countries' => $countries,
            ], 200);
        }

        return view('business.payouts', ['bankAccounts' => $bankAccounts, 'countries' => $countries, 'banks' => $banks]);
    }


    public function store(Request $request)
    {
        $isDynamic = $request->input('formDynamicFields') === 'true';

        if (!$isDynamic) {
            // Static fields (NGN, GHS, KES accounts)
            $validated = $request->validate([
                'account_name' => 'required|string|max:255',
                'account_number' => ['required', 'regex:/^\d{10}$/'],
                'bank_country' => 'required|string|max:10',
                'bank_name' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[\pL\s]+$/u',
                    function ($attribute, $value, $fail) {
                        if (preg_match('/^\d+$/', $value)) {
                            $fail('The bank name cannot be just numbers.');
                        }
                    }
                ],
                'type' => 'required|string|max:10',
                'currency' => 'nullable|string|size:3',
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
                'bank_country' => 'required|string|max:10',
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

        $user = Auth::user();

        $isFirst = BankAccount::where('user_id', $user->id)->count() === 0;

        $bankAccount = BankAccount::create([
            'user_id' => $user->id,
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
            'status' => 'success',
            'message' => 'Bank account added successfully.',
            'data' => [
                'id' => $bankAccount->id,
                'account_name' => $bankAccount->account_name,
                'account_number' => isset($validated['account_number']) ? substr($validated['account_number'], -4): null,
                'bank_name' => $bankAccount->bank_name,
            ]
        ], 201);
    }


    public function destroy($id)
    {
        $account = BankAccount::find($id);

        if (!$account) {
            return response()->json([
                'message' => 'Bank account not found.'
            ], 404);
        }

        if ($account->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        $account->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account deleted successfully.'
        ], 200);
    }



    public function destroyAll()
    {
        $user = Auth::user();

        $deletedCount = BankAccount::where('user_id', $user->id)->delete();

        if ($deletedCount === 0) {
            return response()->json(['message' => 'No accounts to delete.'], 404);
        } else {

            return response()->json([
                'message' => 'All your bank accounts have been deleted.',
                'deleted_count' => $deletedCount
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'bank_country' => 'required|string',
            'account_number' => ['required', 'regex:/^\d{10}$/'],
            'account_name' => 'required|string',
        ]);

        $bankAccount = BankAccount::find($id);

        if (!$bankAccount) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Bank account not found.'], 404);
            }
            return redirect()->back()->with('error', 'Bank account not found.');
        }

        if ($bankAccount->user_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Update the bank account
        $bankAccount->update([
            'bank_name' => $request->bank_name,
            'bank_country' => $request->bank_country,
            'account_number' => Crypt::encryptString($request->account_number),
            'account_name' => $request->account_name
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Bank account updated successfully.',
                'data' => $bankAccount
            ]);
        }

        return redirect()->route('business.edit', $id)->with('success', 'Bank account updated successfully.');
    }


    public function edit($id)
    {
        // $bankAccount = BankAccount::findOrFail($id);
        $bankAccount = BankAccount::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')->firstOrFail();
        $response = Http::withToken(env('OHENTPAY_API_KEY'))->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/countries');
        $countries = [];

        if ($response->successful()) {
            $countries = $response->json();
            // dd($countries);
        }

        // Fetch all banks
        $banks = Bank::all();

        if ($bankAccount->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $allUserAccounts = BankAccount::where('user_id', Auth::id())->get();

        return view('business.editPayout', compact('bankAccount', 'allUserAccounts', 'countries', 'banks'));
    }

    public function setDefault(Request $request, $id)
    {
        // $account = BankAccount::findOrFail($id);
        $account = BankAccount::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->firstOrFail();

        if ($account->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        BankAccount::where('user_id', Auth::id())->update(['default' => false]);
        $account->default = true;
        $account->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Payout account set.',
            'default_account_id' => $account->id,
        ]);
    }


    public function fetchBanks(Request $request)
    {
        $countryCurrency = $request->get('countryCurrency'); // Default to NG
        $currency = $request->get('currency'); // Default to NGN

        $response = Http::withToken(env('OHENTPAY_API_KEY'))
            ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/bankfields', [
                'country' => $countryCurrency,
                'currency' => $currency
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

        if ($request->expectsJson()) {
            return response()->json($response->json(), $response->status());
        }

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Payout account validated successfully.',
                'data' => $response->json()
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid payout account.',
            'data' => $response->json()
        ], $response->status());
    }

}
