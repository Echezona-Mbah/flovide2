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
use Illuminate\Support\Str;

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
        $countries = CountryRule::where('is_active', true)->get();

        $countryRules  = [];
        $currencyRules = [];

        foreach ($countries as $c) {
            $countryRules[$c->country_iso] = [
                'currency' => $c->currency_iso,
                'rules'    => $c->rules,
            ];

            $currencyRules[$c->currency_iso] = [
                'country' => $c->country_iso,
                'rules'   => $c->rules,
            ];
        }

        $pivotServices = [
            'ugx_bank_service'   => env('PIVOT_UGX_BANK_SERVICE'),
            'ugx_mobile_service' => env('PIVOT_UGX_MOBILE_SERVICE'),
        ];
        // dd($countryRules);
        return view('business.add_beneficia', compact(
            'countries',
            'countryRules',
            'currencyRules',
            'pivotServices'
        ));
    }





    public function banks(Request $request)
    {
        $currency     = strtoupper($request->currency ?? '');
        $countryInput = strtoupper($request->country ?? '');
        $provider     = strtolower($request->provider ?? ''); // 👈 GET PROVIDER

        $banks = Bank::query()

            // Filter by country
            ->when($countryInput, function($q) use ($countryInput) {
                $q->where(function($query) use ($countryInput) {
                    $query->where('country_iso', $countryInput)
                        ->orWhereRaw('LEFT(country_iso,2) = ?', [substr($countryInput,0,2)])
                        ->orWhereRaw('LEFT(country_iso,3) = ?', [substr($countryInput,0,3)]);
                });
            })

            // 🔒 FILTER BY PROVIDER IF SENT
            ->when($provider, function($q) use ($provider) {
                $q->where('provider', $provider);
            })

            ->orderBy('name')
            ->get(['id','name','bank_code','type','country_iso','provider']);

        return response()->json($banks);
    }

    public function getBanks(Request $request)
    {
        $country  = strtoupper($request->input('country'));   // e.g., NG
        $provider = $request->input('provider');              // e.g., payaza or pivot

        if (!$country || !$provider) {
            return response()->json([
                'success' => false,
                'message' => 'Country and provider are required'
            ], 422);
        }

        $banks = \App\Models\Bank::where('country_iso', $country)
                    ->where('provider', $provider)
                    ->get(['name', 'bank_code', 'sort_code', 'type']);

        return response()->json([
            'success' => true,
            'data' => $banks
        ]);
    }

    public function getBankAPI(Request $request)
    {
        $country  = strtoupper($request->input('country'));   
        $currency = strtoupper($request->input('currency'));              

        if (!$country || !$currency) {
           return response()->json([
                'success' => false,
                'message' => 'Country and currency are required',
                'code' => 'VALIDATION_ERROR',
                'data' => null
            ], 422);

        }

        $pivotEnabled   = env('PIVOT_ENABLED', false);
        $payazaEnabled  = env('PAYAZA_ENABLED', false);
        $appMobile      = env('APP_MOBILE', false);

        $banksQuery = \App\Models\Bank::where('country_iso', $country)
                        ->where('currency', $currency);

        /**
         * 🎯 UGX LOGIC (FIXED)
         */
        if ($currency === 'UGX') {
            if ($pivotEnabled) {
                // ✅ Only Pivot
                $banksQuery->where('provider', 'pivot');
            } else {
                // ✅ Only Payaza
                $banksQuery->where('provider', 'payaza');
            }
        }

        /**
         * 🎯 GHS LOGIC
         */
        if ($currency === 'GHS') {
            if ($appMobile) {
                // ❌ Hide Payaza
                $banksQuery->where('provider', '!=', 'payaza');
            } else {
                // ✅ Only Payaza (optional, if you want strict control)
                $banksQuery->where('provider', 'payaza');
            }
        }

        /**
         * 🚫 Global Payaza Disable
         */
        if (!$payazaEnabled) {
            $banksQuery->where('provider', '!=', 'payaza');
        }

        $banks = $banksQuery->get(['name', 'bank_code', 'sort_code', 'type']);

        return response()->json([
            'success' => true,
            'message' => 'Banks fetched',
            'code' => 'BANKS_FETCHED',
            'data' => $banks
        ], 200);

    }



    public function allBeneficia(Request $request)
    {
        $beneficias = Beneficia::all(); 

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All Beneficia records retrieved successfully',
                'code' => 'BENEFICIA_FETCHED',
                'data' => $beneficias
            ], 200);

        }

        return view('business.all-beneficiaries', compact('beneficias'));
    }


    

    public function store(Request $request)
    {
        $isApi = $request->expectsJson();

        $authUser = auth('api')->user() ?? auth()->user();

            if (!$authUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                    'code' => 'UNAUTHORIZED',
                    'data' => null
                ], 401);
            }

            $team = TeamMembers::where('user_id', $authUser->id)->first();

            $ownerId = $team ? $team->owner_id : $authUser->id;
            $memberId = $team ? $authUser->id : null; // only set when team member created it
            $role = $team ? $team->role : 'Owner';

        if (!in_array($role, ['Owner', 'Admin'])) {
            $msg = 'Only the business owner or an admin can add beneficiaries.';
            return $isApi
            ? response()->json([
                'success' => false,
                'message' => $msg,
                'code' => 'FORBIDDEN',
                'data' => null
            ], 403)
            : back()->with('error', $msg);
        }




        /* ================= VALIDATION ================= */
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
            return $isApi
                ? response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'code' => 'VALIDATION_ERROR',
                    'data' => $validator->errors()
                ], 422)
                : back()->withErrors($validator)->withInput();
        }

        /* ================= EXTRACT DATA ================= */
        $bank       = $request->input('bank', []);
        $countryIso = strtoupper($bank['country']);
        $currency   = strtoupper($bank['currency']);
        $method     = $request->transfer_method;

        /* ================= PROVIDER DETECTION ================= */

        $PAYAZA_CURRENCIES = ['NGN','TZS','KES','XOF','XAF','ZAR','GHS'];

        $pivotEnabled     = filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN);
        $payazaEnabled    = filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN);
        $appmobileEnabled = filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN);

        $provider = null;

        /*
        |--------------------------------------------------------------------------
        | GHS LOGIC (PRIORITY: APP MOBILE → PAYAZA)
        |--------------------------------------------------------------------------
        */
        if ($currency === 'GHS') {

            if ($appmobileEnabled) {

                $provider = 'app_mobile';

            } elseif ($payazaEnabled) {

                $provider = 'payaza';

            } else {

                return $isApi
                ? response()->json([
                    'success' => false,
                    'message' => 'No provider enabled for UGX',
                    'code' => 'PROVIDER_DISABLED',
                    'data' => null
                ], 403)
                : back()->with('error', 'No provider enabled for UGX');
        }
        }
        

        /*
        |--------------------------------------------------------------------------
        | UGX LOGIC
        |--------------------------------------------------------------------------
        */
        elseif ($currency === 'UGX') {

            if ($pivotEnabled) {

                $provider = 'pivot';

            } elseif ($payazaEnabled) {

                $provider = 'payaza';
                $method   = 'mobile';

            } else {
             return $isApi
                ? response()->json([
                    'success' => false,
                    'message' => 'No provider enabled for UGX',
                    'code' => 'PROVIDER_DISABLED',
                    'data' => null
                ], 403)
                : back()->with('error', 'No provider enabled for UGX');
        }
        }

        /*
        |--------------------------------------------------------------------------
        | PAYAZA OTHER CURRENCIES
        |--------------------------------------------------------------------------
        */
        elseif (in_array($currency, $PAYAZA_CURRENCIES)) {

            if ($payazaEnabled) {

                $provider = 'payaza';

            } else {
                  return $isApi
                ? response()->json([
                    'success' => false,
                    'message' => 'flovide Payaza disabled',
                    'code' => 'PROVIDER_DISABLED',
                    'data' => null
                ], 403)
                : back()->with('error', 'flovide Payaza disabled');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULT → PIVOT
        |--------------------------------------------------------------------------
        */
        else {

            if ($pivotEnabled) {

                $provider = 'pivot';

            } else {
                 return $isApi
                ? response()->json([
                    'success' => false,
                    'message' => 'Pivot disabled',
                    'code' => 'PROVIDER_DISABLED',
                    'data' => null
                ], 403)
                : back()->with('error', 'Pivot disabled');
        }
        }

                //dd($request->all());

        /* ================= DETERMINE BANK / MOBILE ================= */
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

            if ($mobileNumber !== null) {
                $mobileNumber = trim($mobileNumber);

                if (str_starts_with($mobileNumber, '+')) {
                    $mobileNumber = substr($mobileNumber, 1);
                }
            }

            $bankRow = Bank::where('bank_code', $bank['bankCode'] ?? null)->first();
            $bankName = $bankRow?->name ?? 'mobile';
        }
         //dd($request->all());

        /* ================= STORE ================= */

        try {

            $beneficia = Beneficia::create([
                'country'   => $countryIso,
                'currency'  => $currency,
                'type'      => $request->type,
                'first_names'       => $request->firstNames ?? null,
                'last_name'         => $request->lastName ?? null,
                'beneficiary_name'  => $request->name ?? null,
                'account_number' => $bank['accountNumber'] ?? null,
                'account_name'   => $bank['accountHolder'] ?? null,
                'phone' => $mobileNumber,
                'bank'  => $bankName,
                'transfer_method' => $method,
                'bank_code'       => $bank['bankCode'] ?? null,
                'provider'        => $provider,
                'unique_reference'   => strtoupper(\Str::random(7)),
                'customer_reference' => strtoupper(\Str::random(7)),
                'recipient_id' => \Str::uuid(),
                'account_id'   => \Str::uuid(),

                'user_id' => $ownerId,
                'created_by_member_id' => $memberId,
            ]);

            return $isApi
            ? response()->json([
                'success' => true,
                'message' => 'Beneficiary created successfully',
                'code' => 'BENEFICIARY_CREATED',
                'data' => $beneficia
            ], 201)
            : back()->with('success', 'Beneficiary created successfully');

        } catch (\Exception $e) {
            logger('Beneficiary Store Error: '.$e->getMessage());

            return $isApi
            ? response()->json([
                'success' => false,
                'message' => 'Failed to create beneficiary',
                'code' => 'BENEFICIARY_CREATE_FAILED',
                'data' => null
            ], 500)
            : back()->with('error', 'Failed to create beneficiary');
        }
    }






   


    public function edit($id)
    {
        $user = auth()->user();
        $beneficia = Beneficia::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        $countries = Countries::all();
        $banks = Bank::all();
        $beneficiaries = Beneficia::where('user_id', $user->id)->paginate(3);
    
        return view('business.edit_beneficia', compact('beneficia', 'countries', 'banks', 'beneficiaries'));
    }
    
    



public function destroy(Request $request, $id)
{
    $beneficia = Beneficia::findOrFail($id);

    // Only allow owner
    if ($beneficia->user_id != Auth::id()) {
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


    
}
