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
use App\Models\CountryRule;

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
    public function create()
    {
        $user = auth()->user();
        $team = TeamMembers::where('user_id', $user->id)->first();
        $ownerId = $team ? $team->owner_id : $user->id;

        $countries = CountryRule::all();

        // 🔥 Build JS-friendly array
        $countryRules = [];

        foreach ($countries as $c) {
            $countryRules[$c->country_iso] = [
                'currency' => $c->currency_iso,
                'rules' => $c->rules,
            ];
        }

        return view('business.add_beneficia', compact('countries', 'countryRules'));
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




   


public function store(Request $request)
{
    // Get logged-in business user (works for web + api)
    $userId = auth('api')->id() ?? auth()->id();
    $user   = auth('api')->user() ?? auth()->user();

    if (!$user) {
        $msg = 'You must be logged in to add a beneficiary.';
        return $request->expectsJson()
            ? response()->json(['message' => $msg], 401)
            : redirect()->back()->with('error', $msg);
    }

    // Check team role
    $team = TeamMembers::where('user_id', $user->id)->first();
    $role = $team ? $team->role : 'Owner';

    if (!in_array($role, ['Owner', 'Admin'])) {
        $msg = 'Only the business owner or an admin can add beneficiaries.';
        return $request->expectsJson()
            ? response()->json(['message' => $msg], 403)
            : redirect()->back()->with('error', $msg);
    }

    // dd($request->all());

    // Call IFX controller
    $ibanq = new \App\Http\Controllers\Ibanq\IbanqBeneficiaryController(
        app(\App\Services\IbanqAuthService::class)
    );

    return $ibanq->createBeneficiary($request, $user->id, null);

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


    
}
