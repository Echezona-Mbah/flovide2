<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Countries;
use App\Models\Customer;
use App\Models\TeamMembers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class AddCustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $team = TeamMembers::where('user_id', $user->id)->first();
        $ownerId = $team ? $team->owner_id : $user->id;
        $customers = Customer::where('user_id', $ownerId)->paginate(5);
      

        if ($request->expectsJson()) {
            return response()->json([
               'data'=>[
                 'message' => 'customers records retrieved successfully',
                'data' => $customers,
                'method' => $request->method(),
                'url' => $request->fullUrl()
               ]
            ], 200);
        }
        return view('business.customer', compact('customers'));
    }


    public function show($id)
    {
        $customer = Customer::with('country')->find($id); 
    
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
    
        return response()->json([
            'data' => [
                'name' => $customer->customer_name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'created_at' => $customer->created_at,
                'bank' => $customer->bank,
                'account_name' => $customer->account_name,
                'bank_country' => optional($customer->country)->name, // returns country name or null
                'account_number' => $customer->account_number,
            ]
        ]);
    }
    public function create() {
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
        $customers = Customer::where('user_id', $ownerId)->paginate(5);
        $customer = null; // Add this line
        return view('business.add_customer', compact('countries', 'banks', 'customers', 'customer'));
    }

    public function store(Request $request)
    {
        $userId = auth('api')->id() ?? auth()->id();
        $user = auth()->user();

        $team = TeamMembers::where('user_id', $user->id)->first();
        $role = $team ? $team->role : 'Owner'; 

        if (!in_array($role, ['Owner', 'Admin'])) {
            $msg = 'Only the business owner or an admin can add Customers.';
            return $request->expectsJson()
                ? response()->json(['message' => $msg], 403)
                : redirect()->back()->with('error', $msg);
        }

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

        // ✅ Prevent duplicate beneficiary
        $exists = Customer::where('account_name', $accountName)
            ->where('account_number', $accountNumber)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            $errorMessage = 'This customer already exists with the same account name and number.';

            return $request->expectsJson()
                ? response()->json([
                    'errors' => [
                        'message' => [$errorMessage]
                    ]
                ], 409)
                : redirect()->back()->withErrors(['duplicate' => $errorMessage])->withInput();
        }

        // ✅ Build payload
        $payload = [
            'country'        => $country,
            'currency'       => $currency,
            'alias'          => $accountName,
            'type'           => $request->account_type,
            'account_name'   => $accountName,
            'account_number' => $accountNumber,
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

        // ✅ Save beneficiary locally
        $customer = Customer::create([
            'recipient_id'      => $responseData['id'],
            'country'           => $responseData['country'],
            'alias'             => $responseData['alias'],
            'type'              => $responseData['type'],
            'account_name'      => $responseData['bank_account']['account_name'] ?? null,
            'account_number'    => $responseData['bank_account']['account_number'] ?? null,
            'bank'              => $responseData['bank_account']['bank_name'] ?? null,
            'currency'          => $responseData['bank_account']['currency'] ?? null,
            'user_id'           => $userId,
            'default_reference' => 'Invoice',
        ]);

        // ✅ Notify user
        $user = \App\Models\User::find($userId);
        if ($user) {
            $user->notify(new GeneralNotification(
                "New customer Added 🎉",
                "You successfully added {$customer->account_name} ({$customer->account_number}) as a customer."
            ));
        }

        return $request->expectsJson()
            ? response()->json([
            'data'=>[
                'message' => 'customer created successfully',
                'data'    => $customer
            ]
            ], 200)
            : redirect()->route('add_customer.create')->with('success', 'customer created successfully.');
    }

    
    public function json($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }
    public function edit($id)
    {
        $user = auth()->user();
        $team = TeamMembers::where('user_id', $user->id)->first();
        $ownerId = $team ? $team->owner_id : $user->id;
        $customer = Customer::findOrFail($id);
        $countries = Countries::all();
        $banks = Bank::all();
        $customeres = Customer::where('user_id', $ownerId)->paginate(5);
        return view('business.edit_customer', compact('customer', 'countries', 'banks','customeres'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        // ✅ Team / Role Check
        $team = TeamMembers::where('user_id', $user->id)->first();
        $role = $team ? $team->role : 'Owner';

        if (!in_array($role, ['Owner', 'Admin'])) {
            $msg = 'Only the business owner or an admin can add beneficiaries.';
            return $request->expectsJson()
                ? response()->json(['message' => $msg], 403)
                : redirect()->back()->with('error', $msg);
        }

        // ✅ Check ownership of the customer record
        $customer = Customer::findOrFail($id);

        if ($customer->user_id != auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data'=>[
                        'message' => 'Unauthorized to edit this customer',
                    ]
                ], 403);
            } else {
                return redirect()->back()->withErrors(['message' => 'Unauthorized to edit this customer']);
            }
        }

        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'bank' => 'nullable|string',
            'country_id' => 'required|exists:countries,id',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                'data'=>[
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
                ], 422);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        // ✅ Update customer
        $customer->update($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'data'=>[
                    'message' => 'Customer updated successfully',
                'data' => $customer,
                'method' => $request->method(),
                'url' => $request->fullUrl()
                ]
            ]);
        }

        return redirect()->route('customer')->with('success', 'Customer updated successfully');
    }


    public function destroy(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->user_id != auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized to delete this customer',
                ], 403);
            } else {
                return redirect()->back()->withErrors(['message' => 'Unauthorized to delete this customer']);
            }
        }
        $customer->delete();
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Customer deleted successfully',
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]);
        }
        return redirect()->route('customer')->with('status', 'Customer deleted successfully');
    }


    public function search(Request $request)
    {
        $query = $request->query('query');
    
        $customers = Customer::where('customer_name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->get();
    
        return response()->json($customers);
    }

    public function exportCsv()
    {
        $customers = Customer::all(); // ✅ Not ->find($id)

    
        $filename = "customers.csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
    
        $columns = ['Customer Name', 'Email', 'Phone', 'Date Added'];
    
        $callback = function () use ($customers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
    
            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->customer_name,
                    $customer->email,
                    $customer->phone,
                    $customer->created_at->format('M d, Y'),
                ]);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }


    public function fetchBanks(Request $request)
    {
        $country = $request->get('country'); // Default to NG
        $currency = $request->get('currency'); // Default to NGN

        $response = Http::withToken(env('OHENTPAY_API_KEY'))
            ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/bankfields', [
                'country' => $country,
                'currency' => $currency
            ]);

        // dd($response->successful());


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
}
