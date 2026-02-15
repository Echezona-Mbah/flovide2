<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Beneficia;
use App\Models\Countries;
use App\Models\TeamMembers;
use Illuminate\Http\Request;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Ibanq\IbanqBeneficiaryController;



class AddBeneficiariesController extends Controller
{
        protected $beneficiaryService;

    public function index(Request $request)
    {
        $user = auth()->user();
        $team = TeamMembers::where('user_id', $user->id)->first();
        $ownerId = $team ? $team->owner_id : $user->id;
    
        $beneficias = Beneficia::where('user_id', $ownerId)
        ->paginate(8);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Beneficia records retrieved successfully',
                'success' => 'Beneficia records retrieved successfully',
                'data' => $beneficias,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 200);
        }
    
        return view('business.beneficiaries', compact('beneficias'));
    }

    public function allBeneficia(Request $request)
    {
        $beneficias = Beneficia::all(); 

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'All Beneficia records retrieved successfully',
                'success' => true,
                'data' => $beneficias,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 200);
        }

        return view('business.all-beneficiaries', compact('beneficias'));
    }




    public function create()
    {
           $user = auth()->user();
        $team = TeamMembers::where('user_id', $user->id)->first();
        $ownerId = $team ? $team->owner_id : $user->id;


        $response = Http::withToken(env('OHENTPAY_API_KEY'))
            ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/countries');

        $countries = [];

        if ($response->successful()) {
            $countries = $response->json(); // this gives the array of country objects
        }

        $banks = Bank::all();
        $beneficiaries = Beneficia::where('user_id', $ownerId)->paginate(8);

        return view('business.add_beneficia', compact('countries', 'banks', 'beneficiaries'));
    }



  public function store(Request $request)
{
    $userId = auth('api')->id() ?? auth()->id();
    $user   = auth()->user();

    // Check role
    $team = TeamMembers::where('user_id', $user->id)->first();
    $role = $team ? $team->role : 'Owner';

    if (!in_array($role, ['Owner', 'Admin'])) {
        $msg = 'Only the business owner or an admin can add beneficiaries.';
        return $request->expectsJson()
            ? response()->json(['message' => $msg], 403)
            : redirect()->back()->with('error', $msg);
    }

    // Add country & currency to request if needed
    $request->merge([
        'country'  => $request->input('country'),
        'currency' => $request->input('currency'),
    ]);

//    $response = $this->beneficiaryService->createBeneficiary($request, $userId);

       $ibanqController = new IbanqBeneficiaryController(app(\App\Services\IbanqAuthService::class));

    return $ibanqController->createBeneficiary($request);


    // // Call the createBeneficiary function
    // $response = $this->createBeneficiary($request);

    // // Decode the JSON response if it's JSON
    // $responseData = $response instanceof \Illuminate\Http\JsonResponse
    //     ? $response->getData(true)['data'] ?? []
    //     : [];

    // // Store in database if API call successful
    // if (!empty($responseData) && $response->getData(true)['success']) {
    //     $beneficia = Beneficia::create([
    //         'recipient_id'     => $responseData['id'] ?? null,
    //         'country'          => $responseData['country'] ?? $request->country,
    //         'alias'            => $responseData['alias'] ?? $request->uniqueReference,
    //         'type'             => $responseData['type'] ?? $request->type,
    //         'account_name'     => $request->bankDetails['accountName'] ?? null,
    //         'account_number'   => $request->bankDetails['accountNumber'] ?? null,
    //         'bank'             => $responseData['bank_account']['bank_name'] ?? null,
    //         'currency'         => $request->currency,
    //         'user_id'          => $userId,
    //         'default_reference'=> 'Invoice',
    //     ]);
    // }

    // // Return same response from API or redirect
    // return $request->expectsJson()
    //     ? $response
    //     : redirect()->back()->with('success', 'Beneficiary created successfully.');
}



// public function store(Request $request)
// {
//     $userId = auth('api')->id() ?? auth()->id();
//     $user   = auth()->user();

//     $team = TeamMembers::where('user_id', $user->id)->first();
//     $role = $team ? $team->role : 'Owner';

//     if (!in_array($role, ['Owner', 'Admin'])) {
//         $msg = 'Only the business owner or an admin can add beneficiaries.';
//         return $request->expectsJson()
//             ? response()->json(['message' => $msg], 403)
//             : redirect()->back()->with('error', $msg);
//     }

//     $country  = $request->input('country');
//     $currency = $request->input('currency');

//     /*
//     |--------------------------------------------------------------------------
//     | Base Validation Rules
//     |--------------------------------------------------------------------------
//     */
//     $rules = [
//         'account_type' => 'required|string|max:255',
//         'country'      => 'required|string',
//         'currency'     => 'required|string',
//         'account_name' => 'required|string|max:255',
//     ];

//     /*
//     |--------------------------------------------------------------------------
//     | Conditional Validation Rules
//     |--------------------------------------------------------------------------
//     */

//     // 🇦🇱 ALBANIA (AL)
//     if ($country === 'AL' && in_array($currency, ['EUR', 'USD', 'GBP'])) {

//         $rules['iban']    = 'required|string|max:34';
//         $rules['bic']     = 'required|string|max:255';
//         $rules['address'] = 'required|string|max:255';
//         $rules['city']    = 'required|string|max:255';
//         $rules['state']   = 'required|string|max:255';
//         $rules['zipcode'] = 'required|string|max:20';

//     }

//     // 🇦🇸 AMERICAN SAMOA (AS)
//     if ($country === 'AS' && in_array($currency, ['USD', 'EUR', 'GBP'])) {

//         $rules['account_number'] = 'required|string|max:255';
//         $rules['bic']            = 'required|string|max:255';
//         $rules['address']        = 'required|string|max:255';
//         $rules['city']           = 'required|string|max:255';
//         $rules['state']          = 'required|string|max:255';
//         $rules['zipcode']        = 'required|string|max:20';
//     }

//     // 🇳🇴 NORWAY (NO)
//     if ($country === 'NO' && in_array($currency, ['NOK', 'USD', 'GBP', 'EUR'])) {

//         $rules['iban']    = 'required|string|max:34';
//         $rules['bic']     = 'required|string|max:255';
//         $rules['address'] = 'required|string|max:255';
//         $rules['city']    = 'required|string|max:255';
//         $rules['state']   = 'required|string|max:255';
//         $rules['zipcode'] = 'required|string|max:20';

//     }

//     // 🇳🇬 NIGERIA
//     elseif ($country === 'NG' && $currency === 'NGN') {

//         $rules['bank_id']              = 'required|string|max:255';
//         $rules['account_number_input'] = 'required|digits:10';

//     }

//     // 🇬🇧 UNITED KINGDOM
//     elseif ($currency === 'GBP') {

//         $rules['sort_code']      = 'required|string|max:255';
//         $rules['account_number'] = 'required|string|max:255';
//         $rules['address']        = 'required|string|max:255';
//         $rules['city']           = 'required|string|max:255';
//         $rules['state']          = 'required|string|max:255';
//         $rules['zipcode']        = 'required|string|max:20';

//     }

//     // 🇺🇸 🇪🇺 USD / EUR
//     elseif (in_array($currency, ['USD', 'EUR'])) {

//         $rules['bic']            = 'required|string|max:255';
//         $rules['account_number'] = 'required|string|max:255';
//         $rules['address']        = 'required|string|max:255';
//         $rules['city']           = 'required|string|max:255';
//         $rules['state']          = 'required|string|max:255';
//         $rules['zipcode']        = 'required|string|max:20';

//     }

//     $validator = Validator::make($request->all(), $rules);

//     if ($validator->fails()) {
//         return $request->expectsJson()
//             ? response()->json(['errors' => $validator->errors()], 422)
//             : redirect()->back()->withErrors($validator)->withInput();
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | Choose Correct Account Identifier
//     |--------------------------------------------------------------------------
//     */
//     if ($country === 'NG' && $currency === 'NGN') {
//         $accountNumber = $request->input('account_number_input');
//     } elseif (in_array($country, ['NO', 'AL'])) {
//         $accountNumber = $request->input('iban'); // Norway & Albania
//     } else {
//         // Covers AS, UK, USD, EUR, etc.
//         $accountNumber = $request->input('account_number');
//     }

//     $accountName = $request->input('account_name');


//     /*
//     |--------------------------------------------------------------------------
//     | Prevent Duplicate Beneficiary
//     |--------------------------------------------------------------------------
//     */
//     $exists = Beneficia::where('account_name', $accountName)
//         ->where('account_number', $accountNumber)
//         ->where('user_id', $userId)
//         ->exists();

//     if ($exists) {
//         $errorMessage = 'This beneficiary already exists with the same account name and number.';
//         return $request->expectsJson()
//             ? response()->json(['errors' => ['message' => [$errorMessage]]], 409)
//             : redirect()->back()->withErrors(['duplicate' => $errorMessage])->withInput();
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | Base Payload
//     |--------------------------------------------------------------------------
//     */
//     $payload = [
//         'country'      => $country,
//         'currency'     => $currency,
//         'alias'        => $accountName,
//         'type'         => $request->account_type,
//         'account_name' => $accountName,
//     ];

//     /*
//     |--------------------------------------------------------------------------
//     | Country Specific Payload
//     |--------------------------------------------------------------------------
//     */

//     // 🇳🇬 Nigeria
//     if ($country === 'NG') {

//         $payload['account_number'] = $accountNumber;
//         $payload['bank_id']        = $request->input('bank_id');

//     }
//     // 🇦🇱 Albania payload
//     if ($country === 'AL') {

//         $payload['iban']    = $request->input('iban');
//         $payload['bic']     = $request->input('bic');
//         $payload['address'] = $request->input('address');
//         $payload['city']    = $request->input('city');
//         $payload['state']   = $request->input('state');
//         $payload['zipcode'] = $request->input('zipcode');

//     }

//     elseif ($country === 'AS') {

//         $payload['account_number'] = $accountNumber;
//         $payload['bic']            = $request->input('bic');
//         $payload['address']        = $request->input('address');
//         $payload['city']           = $request->input('city');
//         $payload['state']          = $request->input('state');
//         $payload['zipcode']        = $request->input('zipcode');
//     }


    
//     // 🇳🇴 Norway
//     elseif ($country === 'NO') {

//         $payload['iban']    = $request->input('iban');
//         $payload['bic']     = $request->input('bic');
//         $payload['address'] = $request->input('address');
//         $payload['city']    = $request->input('city');
//         $payload['state']   = $request->input('state');
//         $payload['zipcode'] = $request->input('zipcode');

//     }

//     // 🇬🇧 UK
//     elseif ($currency === 'GBP') {

//         $payload['account_number'] = $accountNumber;
//         $payload['sort_code']      = $request->input('sort_code');
//         $payload['address']        = $request->input('address');
//         $payload['city']           = $request->input('city');
//         $payload['state']          = $request->input('state');
//         $payload['zipcode']        = $request->input('zipcode');

//     }

//     // 🇺🇸 🇪🇺 USD / EUR
//     elseif (in_array($currency, ['USD', 'EUR'])) {

//         $payload['account_number'] = $accountNumber;
//         $payload['bic']            = $request->input('bic');
//         $payload['address']        = $request->input('address');
//         $payload['city']           = $request->input('city');
//         $payload['state']          = $request->input('state');
//         $payload['zipcode']        = $request->input('zipcode');

//     }

//     // Remove empty values
//     $payload = array_filter($payload, fn ($v) => $v !== null && $v !== '');

//     Log::info('Payload sent to OhentPay:', $payload);

//     /*
//     |--------------------------------------------------------------------------
//     | Send to OhentPay
//     |--------------------------------------------------------------------------
//     */
//     $ohentResponse = Http::withToken(env('OHENTPAY_API_KEY'))
//         ->post(env('OHENTPAY_BASE_URL') . '/recipients', $payload);

//     if (!$ohentResponse->successful()) {
//         $error = $ohentResponse->json();
//         Log::error('OhentPay recipient creation failed', $error);

//         return $request->expectsJson()
//             ? response()->json(['errors' => $error['message'] ?? 'Failed to create recipient'], 500)
//             : redirect()->back()->with('api_error', $error['message'] ?? 'Failed')->withInput();
//     }

//     $responseData = $ohentResponse->json();

//     /*
//     |--------------------------------------------------------------------------
//     | Save Beneficiary
//     |--------------------------------------------------------------------------
//     */
//     $beneficia = Beneficia::create([
//         'recipient_id'   => $responseData['id'],
//         'country'        => $responseData['country'],
//         'alias'          => $responseData['alias'],
//         'type'           => $responseData['type'],
//         'account_name'   => $responseData['bank_account']['account_name'] ?? $accountName,
//         'account_number' => $accountNumber,
//         'bank'           => $responseData['bank_account']['bank_name'] ?? null,
//         'currency'       => $currency,
//         'user_id'        => $userId,
//         'default_reference' => 'Invoice',
//     ]);

//     /*
//     |--------------------------------------------------------------------------
//     | Notify User
//     |--------------------------------------------------------------------------
//     */
//     if ($user) {
//         $user->notify(new GeneralNotification(
//             'New Beneficiary Added 🎉',
//             "You successfully added {$beneficia->account_name}."
//         ));
//     }

//     return $request->expectsJson()
//         ? response()->json(['message' => 'Beneficiary created successfully', 'data' => $beneficia], 200)
//         : redirect()->route('add_beneficias.create')->with('success', 'Beneficiary created successfully.');
// }





    // public function edit($id)
    // {
    //     $user = auth()->user();
    //     $beneficia = Beneficia::where('user_id', $user->id)->where('id', $id)->firstOrFail();
    //     $countries = Countries::all();
    //     $banks = Bank::all();
    //     $beneficiaries = Beneficia::where('user_id', $user->id)->paginate(3);
    
    //     return view('business.edit_beneficia', compact('beneficia', 'countries', 'banks', 'beneficiaries'));
    // }
    
    

    // public function update(Request $request, $id)
    // {
    //     //dd($request->all());
    //     $rules = [
    //         'bank' => 'required|string|max:255',
    //         'country_id' => 'required|exists:countries,id',
    //         'account_number' => 'required|string',
    //         'account_name' => 'required|string|max:255',
    //     ];

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         if ($request->expectsJson()) {
    //             return response()->json([
    //                 'message' => 'Validation failed',
    //                 'errors' => $validator->errors(),
    //                 'method' => $request->method(),
    //                 'url' => $request->fullUrl()
    //             ], 422);
    //         } else {
    //             return redirect()->back()->withErrors($validator)->withInput();
    //         }
    //     }

    //     $beneficia = Beneficia::findOrFail($id);

    //     // Optional: Authorize that the user owns this record
    //     if ($beneficia->user_id !== ($request->user()?->id ?? auth()->id())) {
    //         abort(403, 'Unauthorized');
    //     }

    //     $beneficia->update([
    //         'bank' => $request->bank,
    //         'country_id' => $request->country_id,
    //         'account_number' => $request->account_number,
    //         'account_name' => $request->account_name,
    //     ]);

    //     if ($request->expectsJson()) {
    //         return response()->json([
    //             'message' => 'Beneficia updated successfully',
    //             'data' => $beneficia,
    //             'method' => $request->method(),
    //             'url' => $request->fullUrl()
    //         ], 200);
    //     }

    //     return redirect()->route('beneficias')->with('success', 'Beneficia updated successfully.');
    // }


    public function destroy(Request $request, $id)
    {
        $beneficia = Beneficia::findOrFail($id);
    
        if ($beneficia->user_id != Auth::id()) {
            $message = 'Unauthorized to delete this beneficia';
            return $request->expectsJson()
                ? response()->json(['message' => $message], 403)
                : redirect()->back()->withErrors(['message' => $message]);
        }
    
        $ohentResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OHENTPAY_API_KEY'),
            'Accept' => 'application/json',
        ])->delete(env('OHENTPAY_BASE_URL') . '/recipients/' . $beneficia->recipient_id);
    
        logger()->info('OhentPay delete response', [
            'recipient_id' => $beneficia->recipient_id,
            'response' => $ohentResponse->body()
        ]);
    
        if ($ohentResponse->successful() && $ohentResponse->json('result') === 'ok') {
            $beneficia->delete();
    
            $successMessage = 'Beneficia deleted successfully from both system and OhentPay';
            return $request->expectsJson()
                ? response()->json([
                    'message' => $successMessage,
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ])
                : redirect()->route('customers.index')->with('status', $successMessage);
        }
    
        $errorMessage = 'Failed to delete recipient from OhentPay';
        return $request->expectsJson()
            ? response()->json([
                'message' => $errorMessage,
                'error' => $ohentResponse->json(),
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
            rtrim(env('OHENTPAY_BASE_URL'), '/') . '/recipients/validate', $payload
        );

        if ($request->expectsJson()) {
            return response()->json($response->json(), $response->status());
        }

        if ($response->successful()) {
            return back()->with('success', 'Recipient account created successfully.');
        } else {
            return back()->with('error', 'Invalid bank or account number.');
        }
    }

    



    // public function listRecipients(Request $request)
    // {
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('OHENTPAY_API_KEY'),
    //         'Accept' => 'application/json',
    //     ])->get(env('OHENTPAY_BASE_URL') . '/recipients');

    //     if ($response->successful()) {
    //         $recipients = $response->json();

    //         // API response
    //         if ($request->expectsJson()) {
    //             return response()->json($recipients);
    //         }

    //         // Web view
    //         return view('recipients.index', ['recipients' => $recipients]);
    //     }

    //     return response()->json([
    //         'error' => 'Failed to fetch recipients',
    //         'details' => $response->body(),
    //     ], $response->status());
    // }





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


    // public function fetchBankss(Request $request)
    // {

    //     $country = $request->input('country');
    //     $currency = $request->input('currency');
    //     $rules = [
    //             'country' => 'required|string',
    //             'currency' => 'required|string',
    //     ];
    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //             return $request->expectsJson()
    //                 ? response()->json([
    //                     'message' => 'Validation failed',
    //                     'errors' => $validator->errors()
    //                 ], 422)
    //                 : redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $response = Http::withToken(env('OHENTPAY_API_KEY'))
    //         ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/bankfields', [
    //             'country' => $country,
    //             'currency' => $currency
    //         ]);

    //     if ($response->successful()) {
    //         return response()->json([
    //             'status' => 'success',
    //             'banks' => $response->json()
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => 'error',
    //         'message' => 'Failed to fetch banks',
    //         'details' => $response->json()
    //     ], $response->status());
    // }

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



    // public function fetchBanks(Request $request)
    // {
    //     $country = $request->get('country'); 
    //     $currency = $request->get('currency'); 

    //     $response = Http::withToken(env('OHENTPAY_API_KEY'))
    //         ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/bankfields', [
    //             'country' => $country,
    //             'currency' => $currency
    //         ]);

    //     dd($response->json());


    //     if ($response->successful()) {
    //         return response()->json([
    //             'status' => 'success',
    //             'fields' => $response->json()
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => 'error',
    //         'message' => 'Failed to fetch bank fields',
    //         'details' => $response->json()
    //     ], $response->status());
    // }

//     public function fetchBanks(Request $request)
// {
//     $country  = $request->get('country');
//     $currency = $request->get('currency');

//     $response = Http::withToken(env('OHENTPAY_API_KEY'))
//         ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/bankfields', [
//             'country'  => $country,
//             'currency' => $currency
//         ]);

//     if ($response->successful()) {

//         // Extract only the "name" field
//         $names = collect($response->json())->pluck('name');

//         return response()->json([
//             'status'  => 'success',
//             'message' => 'Bank fields retrieved',
//             'fields'  => $names
//         ]);
//     }

//     return response()->json([
//         'status'  => 'error',
//         'message' => 'Failed to fetch bank fields',
//         'details' => $response->json()
//     ], $response->status());
// }


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
