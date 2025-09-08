<?php

namespace App\Http\Controllers\business;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Subaccount;
use App\Models\Bank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function subaccount(Request $request)
    {
     
        $user = Auth::user();
        $subaccounts = Subaccount::where('user_id', $user->id)->get();
        $response = Http::withToken(env('OHENTPAY_API_KEY'))->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/countries');
        $countries = [];

        if ($response->successful()) {
            $countries = $response->json();
            // dd($countries);
        }
        // Fetch all banks
        $banks = Bank::all();
        
        if ($request->expectsJson()) {
            if ($subaccounts->isEmpty()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'You have not added any subaccounts yet.'
                    ]
                ], 404);
            }

            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Bank accounts and countries retrieved successfully.',
                    'subaccounts' => $subaccounts,
                    'countries' => $countries
                ]
            ], 200);
        }

        if ($subaccounts->isEmpty()) {
            session()->flash('info', 'You have not added any subaccounts yet.');
        }
        // dd(' $user');

        return view('business.subaccount', [
            'subaccounts' => $subaccounts,
            'countries' => $countries,
            'banks' => $banks
        ]);

        
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
        // Check if this Subaccount already exists for the current user
        $exists = Subaccount::where('account_name', $request->input('account_name') )
        ->where('account_number', $request->input('account_number'))->where('user_id', $user->id)->exists();

        if ($exists) {
            if(request()->expectsJson()){
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Subaccount already exists.'
                    ]
                ], 409);
            }
            return redirect()->back()->withErrors(['duplicate' => 'Subaccount already exists.'])->withInput();
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
        $isFirst = Subaccount::where('user_id', $user->id)->count() === 0;

        $Subaccount = Subaccount::create([
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
                'message' => 'Subaccount successfully added.',
                'Subaccount' => [
                    'id' => $Subaccount->id,
                    'account_type' => $Subaccount->account_type,
                    'account_name' => $Subaccount->account_name,
                    'account_number' => $request->input('account_number') ? substr($request->input('account_number'), -4): null,
                    'bank_name' => $Subaccount->bank_name,
                ]
            ]
        ], 200);

        // Web request response
        // return redirect()->route('business.subaccount')->with('success', 'Subaccount created successfully.');
    }

    public function edit($id)
    {
        $subaccount = Subaccount::findOrFail($id);

        if ($subaccount->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $bankAccount = Subaccount::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')->firstOrFail();

        // Fetch all countries
        $response = Http::withToken(env('OHENTPAY_API_KEY'))->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/countries');
        $countries = [];

        if ($response->successful()) {
            $countries = $response->json();
            // dd($countries);
        }

        // Fetch all banks
        $banks = Bank::all();

        //api response
        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $subaccount,
                'countries' => $countries,
                'banks' => $banks,
            ], 200);
        }

        $allUserSubAccounts = Subaccount::where('user_id', Auth::id())->get();

        return view('business.editSubaccount', compact('subaccount', 'allUserSubAccounts', 'countries', 'banks'));
    }


    public function show()
    {
        $user = Auth::user();
        $subaccounts = Subaccount::where('user_id', $user->id)->get();

        if ($subaccounts->isEmpty()) {
            return response()->json(['message' => 'No subaccounts found.'], 404);
        }

        return response()->json([
            'message' => 'Subaccounts retrieved successfully.',
            'data' => $subaccounts
        ]);
    }


    public function update(Request $request, $id)
    {
        $subaccount = Subaccount::find($id);

        // Check if subaccount exists and belongs to the authenticated user
        if (!$subaccount || $subaccount->user_id !== Auth::id()) {
            return response()->json(['message' => 'Subaccount not found or unauthorized.'], 403);
        }



        $validated = $request->validate([
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
            'bank_country' => 'required|string',
            'account_number' => ['required', 'regex:/^\d{10}$/'],
            'account_name' => 'required|string'
        ]);

        $subaccount->bank_name = $validated['bank_name'];
        $subaccount->bank_country = $validated['bank_country'];
        $subaccount->account_number = Crypt::encryptString($validated['account_number']);
        $subaccount->account_name = $validated['account_name'];
        $subaccount->save();

        return response()->json([
            'message' => 'Subaccount updated successfully.',
            'data' => $subaccount
        ]);
    }

    public function destroy($id)
    {
        $subaccount = Subaccount::find($id);

        // Check if subaccount exists and belongs to the authenticated user
        if (!$subaccount || $subaccount->user_id !== Auth::user()->id) {
            return response()->json(['message' => 'Subaccount not found or unauthorized.'], 403);
        }

        $subaccount->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Subaccount deleted successfully.',
        ]);
    }


    public function destroyAll()
    {
        $user = Auth::user();

        $deletedCount = Subaccount::where('user_id', $user->id)->delete();

        if($deletedCount === 0){
            return response()->json(["status" => "error", "message" => "No Subaccount to delete"], 404);
        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'All your bank accounts have been deleted.',
                'deleted_count' => $deletedCount
            ], 200);
        }
    }

    public function setDefault(Request $request, $id)
    {
        $account = Subaccount::findOrFail($id);

        if ($account->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Subaccount::where('user_id', Auth::id())->update(['default' => false]);
        $account->default = true;
        $account->save();

        return response()->json([
            'message' => 'Payout Sub Account Set.',
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
