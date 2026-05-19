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
            'success' => true,
            'message' => 'Personal Beneficia records retrieved successfully',
            'code' => 'PERSONAL_BENEFICIA_FETCHED',
            'data' => $beneficias
        ], 200);
    }

    return view('personal.beneficiaries', compact('beneficias'));
}


public function allBeneficia(Request $request)
{
    $personalId = auth('personal-api')->id();

    $beneficias = Beneficia::where('personal_id', $personalId)->get();

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'All Personal Beneficia records retrieved successfully',
            'code' => 'PERSONAL_BENEFICIA_ALL_FETCHED',
            'data' => $beneficias
        ], 200);
    }

    return view('personal.all-beneficiaries', compact('beneficias'));
}


public function store(Request $request)
{
    $isApi = $request->expectsJson();

    $personal = auth('personal-api')->user();
    if (!$personal) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
            'code' => 'UNAUTHORIZED',
            'data' => null
        ], 401);
    }

    $personalId = $personal->id;

    $validator = \Validator::make($request->all(), [
        'type' => 'required|in:individual,corporate',
        'firstNames' => 'nullable|required_if:type,individual|string|max:100',
        'lastName'   => 'nullable|required_if:type,individual|string|max:100',
        'name'       => 'nullable|required_if:type,corporate|string|max:200',
        'transfer_method' => 'required|in:bank,mobile',
        'bank.country'        => 'required|string|min:2|max:3',
        'bank.currency'       => 'required|string|size:3',
        'bank.accountHolder'  => 'required|string|max:100',
        'bank.accountNumber'  => 'nullable|string|max:34',
        'bank.bankCode'       => 'nullable|string|max:20',
        'bank.mobileNumber'   => 'nullable|string|max:30',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'code' => 'VALIDATION_ERROR',
            'data' => $validator->errors()
        ], 422);
    }

    $bank       = $request->input('bank', []);
    $countryIso = strtoupper($bank['country']);
    $currency   = strtoupper($bank['currency']);
    $method     = $request->transfer_method;

    $PAYAZA_CURRENCIES = ['NGN','TZS','KES','XOF','XAF','ZAR','GHS'];
    $pivotEnabled     = filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN);
    $payazaEnabled    = filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN);
    $appmobileEnabled = filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN);

    $provider = null;

    if ($currency === 'GHS') {
        if ($appmobileEnabled) $provider = 'app_mobile';
        elseif ($payazaEnabled) $provider = 'payaza';
        else return response()->json([
            'success' => false,
            'message' => 'No provider enabled for GHS',
            'code' => 'PROVIDER_DISABLED',
            'data' => null
        ], 403);
    } elseif ($currency === 'UGX') {
        if ($pivotEnabled) $provider = 'pivot';
        elseif ($payazaEnabled) { $provider = 'payaza'; $method = 'mobile'; }
        else return response()->json([
            'success' => false,
            'message' => 'No provider enabled for UGX',
            'code' => 'PROVIDER_DISABLED',
            'data' => null
        ], 403);
    } elseif (in_array($currency, $PAYAZA_CURRENCIES)) {
        if ($payazaEnabled) $provider = 'payaza';
        else return response()->json([
            'success' => false,
            'message' => 'Payaza disabled',
            'code' => 'PROVIDER_DISABLED',
            'data' => null
        ], 403);
    } else {
        if ($pivotEnabled) $provider = 'pivot';
        else return response()->json([
            'success' => false,
            'message' => 'Pivot disabled',
            'code' => 'PROVIDER_DISABLED',
            'data' => null
        ], 403);
    }

    $bankName     = null;
    $mobileNumber = null;
    if ($method === 'bank') {
        $bankRow = Bank::where(function ($q) use ($bank) {
            $q->where('bank_code', $bank['bankCode'] ?? null)
              ->orWhere('sort_code', $bank['bankCode'] ?? null);
        })->first();
        $bankName = $bankRow?->name;
    }

    if ($method === 'mobile') {
        $mobileNumber = $bank['mobileNumber'] ?? null;
        $bankRow = Bank::where('bank_code', $bank['bankCode'] ?? null)->first();
        $bankName = $bankRow?->name ?? 'mobile';
    }

    try {
        $beneficia = Beneficia::create([
            'country'   => $countryIso,
            'currency'  => $currency,
            'type'      => $request->type,
            'first_names'       => $request->firstNames ?? null,
            'last_name'         => $request->lastName ?? null,
            'beneficiary_name'  => $request->name ?? null,
            'account_number'    => $bank['accountNumber'] ?? null,
            'account_name'      => $bank['accountHolder'] ?? null,
            'phone'             => $mobileNumber,
            'bank'              => $bankName,
            'transfer_method'   => $method,
            'bank_code'         => $bank['bankCode'] ?? null,
            'provider'          => $provider,
            'unique_reference'   => strtoupper(\Str::random(7)),
            'customer_reference' => strtoupper(\Str::random(7)),
            'recipient_id'       => \Str::uuid(),
            'account_id'         => \Str::uuid(),
            'personal_id' => $personalId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Beneficiary created successfully',
            'code' => 'BENEFICIARY_CREATED',
            'data' => $beneficia
        ], 201);

    } catch (\Exception $e) {
        logger('Beneficiary Store Error: '.$e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to create beneficiary',
            'code' => 'BENEFICIARY_CREATE_FAILED',
            'data' => null
        ], 500);
    }
}


 

public function destroy(Request $request, $id)
{
    $personalId = auth('personal-api')->id();
    $beneficia = Beneficia::findOrFail($id);

    // Only allow owner
    if ($beneficia->personal_id != $personalId) {
        $message = 'Unauthorized to delete this beneficiary';

        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => $message,
                'code' => 'FORBIDDEN',
                'data' => null
            ], 403)
            : redirect()->back()->withErrors(['message' => $message]);
    }

    try {
        $beneficia->delete();

        $successMessage = 'Beneficiary deleted successfully';

        return $request->expectsJson()
            ? response()->json([
                'success' => true,
                'message' => $successMessage,
                'code' => 'BENEFICIARY_DELETED',
                'data' => null
            ], 200)
            : redirect()->back()->with('success', $successMessage);

    } catch (\Exception $e) {
        logger($e);

        $errorMessage = 'Failed to delete beneficiary';

        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'BENEFICIARY_DELETE_FAILED',
                'data' => null
            ], 500)
            : redirect()->back()->withErrors(['message' => $errorMessage]);
    }
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
