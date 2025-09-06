<?php

namespace App\Http\Controllers\business;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\BankAccount;
use App\Models\Bank;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        $country = $request->input('country'); // e.g NG/GH/US
        $currency = $request->input('currency'); // e.g NGN/USD/CAD/GHS/KES
        
        // Start with rules that apply to all
        $rules = [
            'account_type' => 'required|string|max:255',
            'bank_country' => 'required|string|max:255',
            'country' => 'required|string',
            'currency' => 'required|string',
            'account_name' => 'required|string|max:255',
        ];
        
        $allowedCountries = ['NG', 'GH', 'KE'];
        $LocalAllowedCurrencies = ['NGN', 'GHS', 'KES'];

        if(in_array($currency, ['USD', 'EUR'])){
            $rules['account_number'] = 'required|string';
            $rules['address'] = 'required|string|max:255';
            $rules['city'] = 'required|string|max:255';
            $rules['state'] = 'required|string|max:255';
            $rules['zipcode'] = 'required|string|max:20';
        }else if($currency === "GBP"){
            // $rules['iban'] = 'required|string|max:255';
            $rules['sort_code'] = 'required|string|max:255';
            $rules['account_number'] = 'required|string|max:255';
            $rules['address'] = 'required|string|max:255';
            $rules['city'] = 'required|string|max:255';
            $rules['state'] = 'required|string|max:255';
            $rules['zipcode'] = 'required|string|max:20';
        }else if(in_array($country, $allowedCountries) && in_array($currency, $LocalAllowedCurrencies)){
            $rules['bank_code'] = 'required|string|max:20';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($request->expectsJson()){
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'errors' => $validator->errors(),
                        'message' => 'Validation failed'
                    ]
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        // Check if this payout account already exists for the current user
        $exists = BankAccount::where('account_name', $request->input('account_name') )
        ->where('account_number', $request->input('account_number'))->where('user_id', $user->id)->exists();

        if ($exists) {
            if(request()->expectsJson()){
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Payout account already exists.'
                    ]
                ], 409);
            }
            return redirect()->back()->withErrors(['duplicate' => 'Payout account already exists.'])->withInput();
        }

        $accountNumber = ($country === "NG") ? $request->input("account_number") : $request->input("iban");
        //paload for 
        $payload = [
            'country' => $request->input('country'),
            'currency' => $request->input('currency'),
            'alias' => $request->input('account_name'),
            'type' => $request->input('account_type'),
            'account_name' => $request->input('account_name'),
            'account_number' => $accountNumber
        ];

        if (in_array($currency, ['USD', 'EUR'])) {
            $payload['bic'] = $request->input('bic');
            $payload['address'] = $request->input('address');
            $payload['city'] = $request->input('city');
            $payload['state'] = $request->input('state');
            $payload['zipcode'] = $request->input('zipcode');
        }elseif($currency === 'GBP'){
            $payload['sort_code'] = $request->input('sort_code');
            $payload['address'] = $request->input('address');
            $payload['city'] = $request->input('city');
            $payload['state'] = $request->input('state');
            $payload['zipcode'] = $request->input('zipcode');
        }else if(in_array($country, $allowedCountries) && in_array($currency, $LocalAllowedCurrencies)){
            // $payload['sort_code'] = $request->input('bank_code');
            $payload['bank_id'] = $request->input('bank_code');
        }
        //filter the payload
        $payload = array_filter($payload, fn($value) => !is_null($value) && $value !== '');

        //log payload data
        Log::info('Payload sent to OhentPay:', ['payload' => $payload]);

        //send payload to ohentpay api
        $ohentPay_response = Http::withToken(env('OHENTPAY_API_KEY'))->post(env('OHENTPAY_BASE_URL') . '/recipients', $payload);
        
        if (!$ohentPay_response->successful()) {
            $errorResponse = $ohentPay_response->json();
            $errorMessage = $errorResponse['message'] ?? 'Failed to create recipient on OhentPay';
            //log error responses from api
            Log::error('OhentPay recipient creation failed', ['response' => $errorResponse]);

            return redirect()->back()->with('api_error', $errorMessage)->withInput();
        }

        //ohentpay api response
        $responseData = $ohentPay_response->json();
        //check for default account
        $isFirst = BankAccount::where('user_id', $user->id)->count() === 0;

        $bankAccount = BankAccount::create([
            'user_id' => $user->id,
            'account_type' => $responseData['type'],
            'account_name' => $responseData['bank_account']['account_name'] ?? null,
            'account_number' => $responseData['bank_account']['account_number'] ?? "",
            'bank_country' => $request->input('bank_country'),
            'bank_name' => $responseData['bank_account']['bank_name'] ?? "",
            'currency' => $responseData['bank_account']['currency'],
            'bic' => $request->input('bic') ?? null,
            'iban' => $request->input('iban') ?? null,
            'city' => $request->input('city') ?? null,
            'state' => $request->input('state') ?? null,
            'zipcode' => $request->input('zipcode') ?? null,
            'recipient_address' => $request->input('address') ?? null,
            'recipient_id' => $responseData['id'],
            'default' => $isFirst
        ]);

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Bank account successfully added.',
                'bankaccount' => [
                    'id' => $bankAccount->id,
                    'account_type' => $bankAccount->account_type,
                    'account_name' => $bankAccount->account_name,
                    'account_number' => $request->input('account_number') ? substr($request->input('account_number'), -4): null,
                    'bank_name' => $bankAccount->bank_name,
                ]
            ]
        ], 200);
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
        $type = $request->input('type');
        if(!$type) {
            return response()->json(['message' => 'Account type is required.'], 400);
        }
        // Validate the request
        if ($type === 'local') {
            $request->validate([
                'bank_name' => 'required|string|max:255',
                'bank_country' => 'required|string|max:10',
                'account_number' => ['required', 'regex:/^\d{10}$/'],
                'account_name' => 'required|string|max:255',
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
            ]);

        }else{
            return response()->json(['message' => 'Invalid account type.'], 400);
        }

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
            'bank_name' => $request->bank_name ?? "",
            'bank_country' => $request->bank_country ?? "",
            'account_number' => Crypt::encryptString($request->iban) ?? "",
            'account_name' => $request->account_name ?? "",
            'bic' => isset($request->bic) ? Crypt::encryptString($request->bic) : null,
            'iban' => isset($request->iban) ? Crypt::encryptString($request->iban) : null,
            'city' => $request->city ?? null,
            'state' => $request->state ?? null,
            'recipient_address' => $request->address ?? null,
            'zipcode' => $request->zipcode ?? null,
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

        //api response
        if (request()->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Bank account retrieved successfully.',
                'data' => $bankAccount,
                'countries' => $countries,
                'banks' => $banks,
            ], 200);
        }

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
                    'status' => 'success',
                    'message' => 'Bank fields fetched successfully.',
                    'fields' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch bank fields',
                'details' => $response->json()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch bank fields',
                'details' => $e->getMessage()
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
                'status' => 'success',
                'message' => 'Payout account validated successfully.',
                'account_name' => $data['account_name'] ?? null,
                'data' => $data
            ], 200);
        }


        return response()->json([
            'status' => 'error',
            'message' => 'Invalid payout account.',
            'data' => $response->json()
        ], $response->status());
    }

}
