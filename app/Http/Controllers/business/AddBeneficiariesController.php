<?php

namespace App\Http\Controllers\business;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Beneficia;
use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class AddBeneficiariesController extends Controller
{
    public function index(Request $request)
    {
        $ownerId = session('owner_id');
    
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
        $user = Auth::user();


        $response = Http::withToken(env('OHENTPAY_API_KEY'))
            ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/countries');

        $countries = [];

        if ($response->successful()) {
            $countries = $response->json(); // this gives the array of country objects
        }

        $banks = Bank::all();
        $beneficiaries = Beneficia::where('user_id', $user->id)->paginate(8);

        return view('business.add_beneficia', compact('countries', 'banks', 'beneficiaries'));
    }



    public function store(Request $request)
    {
        $country = $request->input('country');
        $currency = $request->input('currency');

        // Start with rules that apply to all
        $rules = [
            'account_type' => 'required|string|max:255',
            'country' => 'required|string',
            'currency' => 'required|string',
            'account_name' => 'required|string|max:255',
        ];
        // dd($request->all());

        // Conditional validation
        if ($country === 'NG') {
            $rules['bank_id'] = 'required|string|max:255';
            $rules['account_number_input'] = 'required|string';
        } elseif ($currency === 'GBP') {
            $rules['sort_code'] = 'required|string|max:255';
            $rules['account_number'] = 'required|string|max:255';
            $rules['address'] = 'required|string|max:255';
            $rules['city'] = 'required|string|max:255';
            $rules['state'] = 'required|string|max:255';
            $rules['zipcode'] = 'required|string|max:20';
        } elseif (in_array($currency, ['USD', 'EUR'])) {
            $rules['bic'] = 'required|string|max:255';
            $rules['account_number'] = 'required|string';
            $rules['address'] = 'required|string|max:255';
            $rules['city'] = 'required|string|max:255';
            $rules['state'] = 'required|string|max:255';
            $rules['zipcode'] = 'required|string|max:20';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422)
                : redirect()->back()->withErrors($validator)->withInput();
        }

        // Choose the correct account number field
            $accountNumber = $country === 'NG' 
            ? $request->input('account_number_input') 
            : $request->input('account_number');
            $accountName = $request->input('account_name');

            
            // 🔍 Check if this account already exists for the current user
            $exists = Beneficia::where('account_name', $accountName)
            ->where('account_number', $accountNumber)
            ->where('user_id', Auth::id())
            ->exists();

            if ($exists) {
            $errorMessage = 'This beneficiary already exists with the same account name and number.';

            return $request->expectsJson()
                ? response()->json(['message' => $errorMessage], 409)
                : redirect()->back()->withErrors(['duplicate' => $errorMessage])->withInput();
            }

        $payload = [
            'country' => $country,
            'currency' => $currency,
            'alias' => $request->account_name,
            'type' => $request->account_type,
            'account_name' => $request->account_name,
            'account_number' => $accountNumber,
        ];
        // dd($payload);
        // Add country-specific fields
        if ($country === 'NG') {
            $payload['bank_id'] = $request->input('bank_id');
        } elseif ($currency === 'GBP') {
            $payload['sort_code'] = $request->input('sort_code');
            $payload['address'] = $request->input('address');
            $payload['city'] = $request->input('city');
            $payload['state'] = $request->input('state');
            $payload['zipcode'] = $request->input('zipcode');
            // Do NOT include BIC for GBP
        } elseif (in_array($currency, ['USD', 'EUR'])) {
            $payload['bic'] = $request->input('bic');
            $payload['address'] = $request->input('address');
            $payload['city'] = $request->input('city');
            $payload['state'] = $request->input('state');
            $payload['zipcode'] = $request->input('zipcode');
        }

        // Optional: remove null/empty fields
        $payload = array_filter($payload, fn($value) => !is_null($value) && $value !== '');

        Log::info('Payload sent to OhentPay:', ['payload' => $payload]);
        //dd($payload);
        $ohentResponse = Http::withToken(env('OHENTPAY_API_KEY'))
            ->post(env('OHENTPAY_BASE_URL') . '/recipients', $payload);

        if (!$ohentResponse->successful()) {
            $errorResponse = $ohentResponse->json();
            $errorMessage = $errorResponse['message'] ?? 'Failed to create recipient on OhentPay';

            Log::error('OhentPay recipient creation failed', ['response' => $errorResponse]);

            return redirect()->back()->with('api_error', $errorMessage)->withInput();
        }

        $responseData = $ohentResponse->json();

        $beneficia = Beneficia::create([
            'recipient_id'       => $responseData['id'],
            'country'            => $responseData['country'],
            'alias'              => $responseData['alias'],
            'type'               => $responseData['type'],
            'account_name'       => $responseData['bank_account']['account_name'] ?? null,
            'account_number'     => $responseData['bank_account']['account_number'] ?? null,
            'bank'               => $responseData['bank_account']['bank_name'] ?? null,
            'currency'           => $responseData['bank_account']['currency'] ?? null,
            'created_at'         => now(),
            'user_id'            => $request->user()?->id ?? Auth::id(),
            'default_reference'  => 'Invoice',
        ]);

        return $request->expectsJson()
            ? response()->json([
                'message' => 'Beneficia created successfully',
                'success' => 'Beneficia created successfully',
                 'data' => $beneficia
                ], 201)
            : redirect()->route('add_beneficias.create')->with('success', 'Beneficia created successfully.');
    }



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



    // public function createRecipient(Request $request)
    // {
    //     $request->validate([
    //         'country' => 'required|string',
    //         'currency' => 'required|string',
    //         'alias' => 'required|string',
    //         'type' => 'required|in:personal,business',
    //         'account_name' => 'required|string',
    //         'sort_code' => 'required|string',
    //         'account_number' => 'required|string',
    //     ]);

    //     $payload = $request->only([
    //         'country', 'currency', 'alias', 'type',
    //         'account_name', 'sort_code', 'account_number'
    //     ]);

    //     $response = Http::withToken(env('OHENTPAY_API_KEY'))->post(
    //         env('OHENTPAY_BASE_URL') . '/recipients', $payload
    //     );

    //     if ($request->expectsJson()) {
    //         return response()->json($response->json(), $response->status());
    //     }

    //     if ($response->successful()) {
    //         return back()->with('success', 'Recipient created successfully.');
    //     } else {
    //         return back()->with('error', 'Failed to create recipient.');
    //     }
    // }
    

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
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
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
                'status' => 'success',
                'banks' => $response->json()
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fetch banks',
            'details' => $response->json()
        ], $response->status());
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
