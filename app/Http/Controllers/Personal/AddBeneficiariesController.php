<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Beneficia;
use App\Models\Countries;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AddBeneficiariesController extends Controller
{
    
public function index(Request $request)
{
    $personalId = auth('personal-api')->id(); 

    $beneficias = Beneficia::where('personal_id', $personalId)
        ->paginate(8);

    if ($request->expectsJson()) {
        return response()->json([
             'data' =>[
            'message' => 'Personal Beneficia records retrieved successfully',
            'success' => true,
            'data' => $beneficias,
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ]], 200);
    }

    return view('personal.beneficiaries', compact('beneficias'));
}

public function allBeneficia(Request $request)
{
    $personalId = auth('personal-api')->id();

    $beneficias = Beneficia::where('personal_id', $personalId)->get();

    if ($request->expectsJson()) {
        return response()->json([
         'data' =>[
            'message' => 'All Personal Beneficia records retrieved successfully',
            'success' => true,
            'data' => $beneficias,
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ]], 200);
    }

    return view('personal.all-beneficiaries', compact('beneficias'));
}

public function store(Request $request)
{
    $personalId = auth('personal-api')->id(); 

    $country = $request->input('country');
    $currency = $request->input('currency');

  /*
    |--------------------------------------------------------------------------
    | Base Validation Rules
    |--------------------------------------------------------------------------
    */
    $rules = [
        'account_type' => 'required|string|max:255',
        'country'      => 'required|string',
        'currency'     => 'required|string',
        'account_name' => 'required|string|max:255',
    ];

    /*
    |--------------------------------------------------------------------------
    | Conditional Validation Rules
    |--------------------------------------------------------------------------
    */

    // 🇦🇱 ALBANIA (AL)
    if ($country === 'AL' && in_array($currency, ['EUR', 'USD', 'GBP'])) {

        $rules['iban']    = 'required|string|max:34';
        $rules['bic']     = 'required|string|max:255';
        $rules['address'] = 'required|string|max:255';
        $rules['city']    = 'required|string|max:255';
        $rules['state']   = 'required|string|max:255';
        $rules['zipcode'] = 'required|string|max:20';

    }

    // 🇦🇸 AMERICAN SAMOA (AS)
    if ($country === 'AS' && in_array($currency, ['USD', 'EUR', 'GBP'])) {

        $rules['account_number'] = 'required|string|max:255';
        $rules['bic']            = 'required|string|max:255';
        $rules['address']        = 'required|string|max:255';
        $rules['city']           = 'required|string|max:255';
        $rules['state']          = 'required|string|max:255';
        $rules['zipcode']        = 'required|string|max:20';
    }

    // 🇳🇴 NORWAY (NO)
    if ($country === 'NO' && in_array($currency, ['NOK', 'USD', 'GBP', 'EUR'])) {

        $rules['iban']    = 'required|string|max:34';
        $rules['bic']     = 'required|string|max:255';
        $rules['address'] = 'required|string|max:255';
        $rules['city']    = 'required|string|max:255';
        $rules['state']   = 'required|string|max:255';
        $rules['zipcode'] = 'required|string|max:20';

    }

    // 🇳🇬 NIGERIA
    elseif ($country === 'NG' && $currency === 'NGN') {

        $rules['bank_id']              = 'required|string|max:255';
        $rules['account_number_input'] = 'required|digits:10';

    }

    // 🇬🇧 UNITED KINGDOM
    elseif ($currency === 'GBP') {

        $rules['sort_code']      = 'required|string|max:255';
        $rules['account_number'] = 'required|string|max:255';
        $rules['address']        = 'required|string|max:255';
        $rules['city']           = 'required|string|max:255';
        $rules['state']          = 'required|string|max:255';
        $rules['zipcode']        = 'required|string|max:20';

    }

    // 🇺🇸 🇪🇺 USD / EUR
    elseif (in_array($currency, ['USD', 'EUR'])) {

        $rules['bic']            = 'required|string|max:255';
        $rules['account_number'] = 'required|string|max:255';
        $rules['address']        = 'required|string|max:255';
        $rules['city']           = 'required|string|max:255';
        $rules['state']          = 'required|string|max:255';
        $rules['zipcode']        = 'required|string|max:20';

    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return $request->expectsJson()
            ? response()->json(['errors' => $validator->errors()], 422)
            : redirect()->back()->withErrors($validator)->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Choose Correct Account Identifier
    |--------------------------------------------------------------------------
    */
    if ($country === 'NG' && $currency === 'NGN') {
        $accountNumber = $request->input('account_number_input');
    } elseif (in_array($country, ['NO', 'AL'])) {
        $accountNumber = $request->input('iban'); // Norway & Albania
    } else {
        // Covers AS, UK, USD, EUR, etc.
        $accountNumber = $request->input('account_number');
    }

    $accountName = $request->input('account_name');

    // 🔍 Check if this account already exists for the personal user
    $exists = Beneficia::where('account_name', $accountName)
        ->where('account_number', $accountNumber)
        ->where('personal_id', $personalId)
        ->exists();

    if ($exists) {
        $errorMessage = 'This beneficiary already exists with the same account name and number.';

        return $request->expectsJson()
            ? response()->json([
                 'data' =>[
                'errors' => $errorMessage
            ]], 409)
            : redirect()->back()->withErrors(['duplicate' => $errorMessage])->withInput();
    }

     /*
    |--------------------------------------------------------------------------
    | Base Payload
    |--------------------------------------------------------------------------
    */
    $payload = [
        'country'      => $country,
        'currency'     => $currency,
        'alias'        => $accountName,
        'type'         => $request->account_type,
        'account_name' => $accountName,
    ];

    /*
    |--------------------------------------------------------------------------
    | Country Specific Payload
    |--------------------------------------------------------------------------
    */

    // 🇳🇬 Nigeria
    if ($country === 'NG') {

        $payload['account_number'] = $accountNumber;
        $payload['bank_id']        = $request->input('bank_id');

    }
    // 🇦🇱 Albania payload
    if ($country === 'AL') {

        $payload['iban']    = $request->input('iban');
        $payload['bic']     = $request->input('bic');
        $payload['address'] = $request->input('address');
        $payload['city']    = $request->input('city');
        $payload['state']   = $request->input('state');
        $payload['zipcode'] = $request->input('zipcode');

    }

    elseif ($country === 'AS') {

        $payload['account_number'] = $accountNumber;
        $payload['bic']            = $request->input('bic');
        $payload['address']        = $request->input('address');
        $payload['city']           = $request->input('city');
        $payload['state']          = $request->input('state');
        $payload['zipcode']        = $request->input('zipcode');
    }


    
    // 🇳🇴 Norway
    elseif ($country === 'NO') {

        $payload['iban']    = $request->input('iban');
        $payload['bic']     = $request->input('bic');
        $payload['address'] = $request->input('address');
        $payload['city']    = $request->input('city');
        $payload['state']   = $request->input('state');
        $payload['zipcode'] = $request->input('zipcode');

    }

    // 🇬🇧 UK
    elseif ($currency === 'GBP') {

        $payload['account_number'] = $accountNumber;
        $payload['sort_code']      = $request->input('sort_code');
        $payload['address']        = $request->input('address');
        $payload['city']           = $request->input('city');
        $payload['state']          = $request->input('state');
        $payload['zipcode']        = $request->input('zipcode');

    }

    // 🇺🇸 🇪🇺 USD / EUR
    elseif (in_array($currency, ['USD', 'EUR'])) {

        $payload['account_number'] = $accountNumber;
        $payload['bic']            = $request->input('bic');
        $payload['address']        = $request->input('address');
        $payload['city']           = $request->input('city');
        $payload['state']          = $request->input('state');
        $payload['zipcode']        = $request->input('zipcode');

    }

    // Remove empty values
    $payload = array_filter($payload, fn ($v) => $v !== null && $v !== '');

    Log::info('Payload sent to OhentPay:', $payload);

    /*
    |--------------------------------------------------------------------------
    | Send to OhentPay
    |--------------------------------------------------------------------------
    */
    $ohentResponse = Http::withToken(env('OHENTPAY_API_KEY'))
        ->post(env('OHENTPAY_BASE_URL') . '/recipients', $payload);

    if (!$ohentResponse->successful()) {
        $error = $ohentResponse->json();
        Log::error('OhentPay recipient creation failed', $error);

        return $request->expectsJson()
            ? response()->json(['errors' => $error['message'] ?? 'Failed to create recipient'], 500)
            : redirect()->back()->with('api_error', $error['message'] ?? 'Failed')->withInput();
    }

    $responseData = $ohentResponse->json();


    $beneficia = Beneficia::create([
        'recipient_id'      => $responseData['id'],
        'country'           => $responseData['country'],
        'alias'             => $responseData['alias'],
        'type'              => $responseData['type'],
        'account_name'      => $responseData['bank_account']['account_name'] ?? null,
        'account_number'    => $responseData['bank_account']['account_number'] ?? null,
        'bank'              => $responseData['bank_account']['bank_name'] ?? null,
        'currency'          => $responseData['bank_account']['currency'] ?? null,
        'created_at'        => now(),
        'personal_id'       => $personalId,
        'default_reference' => 'Invoice',
    ]);

       $personal = \App\Models\Personal::find($personalId);
    if ($personal) {
        $personal->notify(new GeneralNotification(
            "New Beneficiary Added 🎉",
            "You successfully added {$beneficia->account_name} ({$beneficia->account_number}) as a beneficiary."
        ));
    }

    return $request->expectsJson()
        ? response()->json([
           'data'=>[
             'message' => 'Personal Beneficia created successfully',
            'success' => true,
            'data'    => $beneficia
           ]
        ], 201)
        : redirect()->route('personal.beneficia.index')->with('success', 'Beneficia created successfully.');
}




 

public function destroy(Request $request, $id)
{
    $personalId = auth('personal-api')->id(); 
    $beneficia = Beneficia::find($id);

    if ($beneficia->personal_id != $personalId) {
        $message = 'Unauthorized to delete this beneficia';
        return $request->expectsJson()
            ? response()->json([
               'data'=>[
                 'status'  => 'error',
                'errors' => $message
               ]
            ], 403)
            : redirect()->back()->withErrors(['message' => $message]);
    }

    $ohentResponse = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('OHENTPAY_API_KEY'),
        'Accept'        => 'application/json',
    ])->delete(env('OHENTPAY_BASE_URL') . '/recipients/' . $beneficia->recipient_id);

    logger()->info('OhentPay delete response', [
        'recipient_id' => $beneficia->recipient_id,
        'response'     => $ohentResponse->body()
    ]);

    if ($ohentResponse->successful() && $ohentResponse->json('result') === 'ok') {
        $beneficia->delete();

        $successMessage = 'Beneficia deleted successfully from both system and OhentPay';
        return $request->expectsJson()
            ? response()->json([
              'data'=>[
                'status'  => 'success',
                'success' => $successMessage,
                'method'  => $request->method(),
                'url'     => $request->fullUrl()
              ]
            ])
            : redirect()->route('personal.beneficia.index')->with('status', $successMessage);
    }

    $errorMessage = 'Failed to delete recipient from OhentPay';
    return $request->expectsJson()
        ? response()->json([
            'data'=>[
            'status'  => 'error',
            'message' => $errorMessage,
            'error'   => $ohentResponse->json(),
            ]
        ], 500)
        : redirect()->back()->withErrors(['message' => $errorMessage]);
}


    
    
    public function search(Request $request)
    {
        $query = $request->input('query');
    
        $beneficiaries = Beneficia::with('country')
            ->where('account_name', 'like', "%{$query}%")
            ->orWhere('bank', 'like', "%{$query}%")
            ->orWhere('account_number', 'like', "%{$query}%")
            ->orWhereHas('country', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->get();
    
        return response()->json($beneficiaries);
    }


    

    public function validateRecipient(Request $request)
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
            rtrim(env('OHENTPAY_BASE_URL'), '/') . '/recipients/validate', 
            $payload
        );

        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'message' => $response->successful() 
                                    ? 'Recipient validated successfully' 
                                    : 'Recipient validation failed',
                    'success' => $response->successful(),
                    'data' => $response->json(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], $response->status());
        }

        if ($response->successful()) {
            return back()->with('success', 'Recipient account created successfully.');
        } else {
            return back()->with('errors', 'Invalid bank or account number.');
        }
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
                'data' => [
                    'message' => 'Country list retrieved successfully',
                    'success' => true,
                    'data' => $response->json(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 200);
        }

        return response()->json([
            'data' => [
                'errors' => 'Failed to fetch country list',
                'success' => false,
                'data' => $response->json(),
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ], $response->status());
    }


    public function fetchcurrencylist(Request $request)
    {
        $currency_code = $request->get('currency_code', 'NG');
        $currency_name = $request->get('currency_name', 'NGN'); 

        $response = Http::withToken(env('OHENTPAY_API_KEY'))
            ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/currencies', [
                'country_name' => $currency_code,
                'currency_name' => $currency_name
            ]);

        if ($response->successful()) {
            return response()->json([
                'data' => [
                    'message' => 'Currency list retrieved successfully',
                    'success' => true,
                    'data' => $response->json(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 200);
        }

        return response()->json([
            'data' => [
                'errors' => 'Failed to fetch currency list',
                'success' => false,
                'data' => $response->json(),
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ], $response->status());
    }


    public function fetchBankss(Request $request)
    {
        $country = $request->input('country');
        $currency = $request->input('currency');

        $rules = [
            'country' => 'required|string',
            'currency' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $request->expectsJson()
                ? response()->json([
                    'data' => [
                        'errors' => 'Validation failed',
                        'success' => false,
                        'errors' => $validator->errors(),
                        'method' => $request->method(),
                        'url' => $request->fullUrl()
                    ]
                ], 422)
                : redirect()->back()->withErrors($validator)->withInput();
        }

        $response = Http::withToken(env('OHENTPAY_API_KEY'))
            ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/bankfields', [
                'country' => $country,
                'currency' => $currency
            ]);

        if ($response->successful()) {
            return response()->json([
                'data' => [
                    'message' => 'Banks retrieved successfully',
                    'success' => true,
                    'data' => $response->json(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 200);
        }

        return response()->json([
            'data' => [
                'errors' => 'Failed to fetch banks',
                'success' => false,
                'data' => $response->json(),
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ], $response->status());
    }


    public function fetchBanks(Request $request)
    {
        $country  = $request->get('country');
        $currency = $request->get('currency');

        $response = Http::withToken(env('OHENTPAY_API_KEY'))
            ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/bankfields', [
                'country'  => $country,
                'currency' => $currency
            ]);

        if (!$response->successful()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch bank fields',
                'details' => $response->json()
            ], $response->status());
        }

        $fields = collect($response->json());

        // ✅ Check if bank select exists
        $bankField = $fields->firstWhere('name', 'bank_id');

        // ✅ If currency supports banks
        if ($bankField && $bankField['type'] === 'select') {

            $banks = collect($bankField['options'])->map(function ($bank) {
                return [
                    'id'   => $bank['value'],
                    'name' => $bank['label'],
                    'code' => $bank['bank_code']
                ];
            });

            return response()->json([
                'status'  => 'success',
                'message' => 'Banks retrieved',
                'has_bank'=> true,
                'fields'   => $fields->pluck('name'),
                'banks' => $banks
            ]);
        }

        // ❌ If currency does NOT require bank
        return response()->json([
            'status'   => 'success',
            'message'  => 'No bank required for this currency',
            'has_bank' => false,
            'fields'   => $fields->pluck('name')
        ]);
    }


    
}
