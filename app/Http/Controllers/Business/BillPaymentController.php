<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\AirtimeNetwork;
use App\Models\Balance;
use App\Models\BillPayment;
use App\Models\DstvPlans;
use App\Models\ElectricityCompany;
use App\Models\TeamMembers;
use App\Traits\CurrencyHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class BillPaymentController extends Controller
{
        use CurrencyHelper;


        public function store(Request $request)
    {
        $formType = $request->input('form_type');

        switch ($formType) {
            case 'dstv':
                return $this->handleDstv($request);
            case 'electricity':
                return $this->handleElectricity($request);
            case 'data':
                return $this->handleData($request);
            default:
                return back()->with('error', 'Invalid bill payment type.');
        }
    }


    public function index(Request $request)
    {
        // $ownerId = session('owner_id');
         $user = auth()->user();
        $team = TeamMembers::where('user_id', $user->id)->first();
        $ownerId = $team ? $team->owner_id : $user->id;
        $payments = BillPayment::where('user_id',$ownerId)->latest()->get();
        $serviceID = 'dstv'; 
        $variations = DstvPlans::all();
        $electricitydata = ElectricityCompany::all();
        $airTimedata = AirtimeNetwork::all();
         $balances = Balance::where('user_id',$ownerId)->get();

        foreach ($balances as $balance) {
            $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
        }


        if ($request->expectsJson()) {
            return response()->json([
                'payments' => $payments,
                'variations' => $variations,
            ], 200);
        }
        return view('business.add_billpayment', compact('payments', 'variations', 'electricitydata', 'airTimedata','balances'));
    }
    

    public function indexElectricity(Request $request)
    {
        // $ownerId = session('owner_id');
        $user = auth()->user();

        $payments = BillPayment::where('user_id', $user->id)
            ->latest()
            ->get();

        if ($request->expectsJson()) {
            return response()->json(['data' => $payments], 200);
        }

        return view('billpayments.index', compact('payments'));
    }
    


    // Verify smartcard or biller code via VTpass API
    public function verify(Request $request)
    {
        $request->validate([
            'billers_code' => 'required|string|min:6',
        ]);
    
        $response = Http::withBasicAuth(env('VTPASS_USERNAME'), env('VTPASS_PASSWORD'))
            ->post(env('VTPASS_API_URL') . '/merchant-verify', [
                'billersCode' => $request->billers_code,
                'serviceID' => 'dstv', // Hardcoded for DSTV
                'type' => 'smartcard',
            ]);
    
        if (!$response->successful()) {
            return response()->json([
                'message' => 'Failed to verify smartcard',
                'error' => $response->json()
            ], 400);
        }
    
        return response()->json($response->json());
    }

    public function getVariations(Request $request)
    {
        // $user = auth()->user();
        $variations = DstvPlans::all();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'data' => $variations
                ]
            ], 200);
        }

        return view('dstv.plans', compact('variations'));
    }
    
    // Store payment and call VTpass pay API
    public function handleDstv(Request $request)
    {
        $rules = [
            'service_id' => 'required|string',
            'billers_code' => 'required|string',
            'variation_code' => 'required|string',
            'phone' => 'required|string',
            'balance' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = auth()->user();

        // ✅ Check user role
        $team = TeamMembers::where('user_id', $user->id)->first();
        $role = $team ? $team->role : 'Owner';

        if (!in_array($role, ['Owner', 'Admin'])) {
            $msg = 'Ask the owner to make you admin so you can buy.';
            return $request->expectsJson()
                ? response()->json(['message' => $msg], 403)
                : redirect()->back()->with('error', $msg);
        }

        $variation = DstvPlans::where('code', $request->variation_code)->first();

        if (!$variation) {
            $msg = 'Variation not found';
            return $request->expectsJson()
                ? response()->json(['message' => $msg], 404)
                : redirect()->back()->with('error', $msg);
        }

        $amount = $variation->amount;
        $currency = $variation->currency ?? 'NGN';

        // ✅ OWNER'S balance, not team member
        $ownerId = $team ? $team->owner_id : $user->id;

        $balance = Balance::where('user_id', $ownerId)
            ->where('id', $request->balance)
            ->first();

        if (!$balance || $balance->amount < $amount) {
            $msg = 'Insufficient balance or balance not found';
            return $request->expectsJson()
                ? response()->json(['message' => $msg], 400)
                : redirect()->back()->with('error', $msg);
        }

        $request_id = uniqid('vtpass_');

        $payload = [
            'request_id' => $request_id,
            'serviceID' => $request->service_id,
            'billersCode' => $request->billers_code,
            'variation_code' => $request->variation_code,
            'amount' => $amount,
            'phone' => $request->phone,
            'currency' => $currency,
        ];

        $response = Http::withBasicAuth(env('VTPASS_USERNAME'), env('VTPASS_PASSWORD'))
            ->post(env('VTPASS_API_URL') . '/pay', $payload)
            ->json();

        $status = ($response['code'] ?? '') === '000' ? 'success' : 'failed';
        $transaction = $response['content']['transactions'] ?? [];

        // Save transaction under OWNER (!!!)
        $payment = BillPayment::create([
            'user_id' => $ownerId,
            'request_id' => $request_id,
            'service_id' => $request->service_id,
            'variation_code' => $request->variation_code,
            'billers_code' => $request->billers_code,
            'amount' => $transaction['amount'] ?? $amount,
            'phone' => $transaction['phone'] ?? $request->phone,
            'currency' => $currency,
            'response' => $response,
            'status' => $status,
        ]);

        if ($status === 'success') {
            $balance->amount -= $amount;
            $balance->save();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Payment processed',
                'status' => $status,
                'data' => $payment
            ], 201);
        }

        return redirect()->route('bill_payment')->with('success', 'Payment processed: ' . $status);
    }



    // Delete a payment record
public function destroy(Request $request, BillPayment $billPayment)
{
    $user = auth()->user();

    // Get the real owner of this user (from team_members)
    $team = \App\Models\TeamMembers::where('user_id', $user->id)->first();
    $ownerId = $team ? $team->owner_id : $user->id; // If team member, get the business owner. If not, user is owner.

    // ✅ Only owner can delete
    if ($billPayment->user_id !== $ownerId) {
        $msg = 'Only the business owner can delete this payment';
        return $request->expectsJson()
            ? response()->json(['message' => $msg], 403)
            : abort(403, $msg);
    }

    $billPayment->delete();

    if ($request->expectsJson()) {
        return response()->json(['message' => 'Payment deleted successfully'], 200);
    }

    return redirect()->route('billpayments.index')
        ->with('success', 'Payment deleted successfully.');
}




    public function getElectricityVariations(Request $request)
    {
        $variations = ElectricityCompany::all();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'data' => $variations
                ]
            ], 200);
        }
    }

    public function verifyElectricity(Request $request)
    {
        $rules = [
            'billers_code' => 'required|string',
            'service_id' => 'required|string',
            'type' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $response = Http::withBasicAuth(
            env('VTPASS_USERNAME'),
            env('VTPASS_PASSWORD')
        )->post(
            env('VTPASS_API_URL') . '/merchant-verify',
            [
                'billersCode' => $request->billers_code,
                'serviceID' => $request->service_id,
                'type' => $request->type,
            ]
        );

        return response()->json([
            'message' => $response->successful()
                ? 'Verification successful.'
                : ($response->json()['message'] ?? 'Verification failed.'),
            'data' => $response->json(),
            'status' => $response->status(),
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ], $response->status());
    }


   public function handleElectricity(Request $request)
{
    $rules = [
        'service_id' => 'required|string',
        'billers_code' => 'required|string',
        'variation_code' => 'required|string',
        'amount' => 'required|numeric|min:0',
        'phone' => 'required|string',
        'balance' => 'required|string',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = auth()->user();

    // ✅ Check role - only owner or admin can continue
    $team = TeamMembers::where('user_id', $user->id)->first();
    $role = $team ? $team->role : 'Owner'; // if no record, user is the owner

    if (!in_array($role, ['Owner', 'Admin'])) {
        $msg = 'Only the business owner or an admin can make payments.';
        return $request->expectsJson()
            ? response()->json(['message' => $msg], 403)
            : redirect()->back()->with('error', $msg);
    }

    // ✅ Determine whose balance to use (always owner's balance)
    $ownerId = $team ? $team->owner_id : $user->id;

    $balance = Balance::where('user_id', $ownerId)
        ->where('id', $request->balance)
        ->first();

    if (!$balance || $balance->amount < $request->amount) {
        $msg = 'Insufficient balance or balance not found';
        return $request->expectsJson()
            ? response()->json(['message' => $msg], 400)
            : redirect()->back()->with('error', $msg);
    }

    $request_id = uniqid('vtpass_');

    $payload = [
        'request_id' => $request_id,
        'serviceID' => $request->service_id,
        'billersCode' => $request->billers_code,
        'variation_code' => $request->variation_code,
        'amount' => $request->amount,
        'phone' => $request->phone,
    ];

    $response = Http::withBasicAuth(
        env('VTPASS_USERNAME'),
        env('VTPASS_PASSWORD')
    )->post(env('VTPASS_API_URL') . '/pay', $payload)->json();

    $status = ($response['code'] ?? '') === '000' ? 'success' : 'failed';
    $transaction = $response['content']['transactions'] ?? [];

    $payment = BillPayment::create([
        'user_id' => $ownerId, // ✅ Store as owner payment
        'request_id' => $request_id,
        'service_id' => $request->service_id,
        'variation_code' => $request->variation_code,
        'billers_code' => $request->billers_code,
        'amount' => $transaction['amount'] ?? $request->amount,
        'phone' => $transaction['phone'] ?? $request->phone,
        'response' => $response,
        'status' => $status,
    ]);

    if ($status === 'success') {
        $balance->amount -= $request->amount;
        $balance->save();
    }

    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Payment processed',
            'status' => $status,
            'data' => $payment
        ], 200);
    }

    return redirect()->route('bill_payment')->with('success', 'Electricity bill payment ' . $status);
}




    public function getDataServiceId(Request $request)
{
    $variations = AirtimeNetwork::all();

    if ($request->expectsJson()) {
        return response()->json([
            'data' => [
                'status' => 'success',
                'data' => $variations
            ]
        ], 200);
    }

    return view('dstv.plans', compact('variations'));
}
    public function getDateVariations(Request $request)
    {
        $request->validate([
            'service_id' => 'required|string',
        ]);
    
        $url = env('VTPASS_API_URL') . '/service-variations?serviceID=' . $request->service_id;
    
        try {
            $response = Http::withBasicAuth(
                env('VTPASS_USERNAME'),
                env('VTPASS_PASSWORD')
            )->get($url);
    
            $responseBody = $response->json();
    
            if (!$response->successful()) {
                return response()->json([
                    'message' => $responseBody['response_description'] ?? 'Failed to fetch variations',
                    'data' => [],
                    'status' => $response->status(),
                ], $response->status());
            }
    
            return response()->json([
                'message' => 'Service variations fetched successfully',
                'data' => $responseBody,
                'status' => 200,
            ]);
    
        } catch (\Exception $e) {
            Log::error('Variation Fetch Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error connecting to VTPass API',
                'data' => [],
                'status' => 500,
            ], 500);
        }
    }
    public function handleData(Request $request)
    {
        // ✅ Allow only owner or admin
        $user = auth()->user();
        if (!in_array($user->role, ['Owner', 'Admin'])) {
            $msg = 'Unauthorized: Only owner or admin can perform this action.';
            return $request->expectsJson()
                ? response()->json(['message' => $msg], 403)
                : redirect()->back()->with('error', $msg);
        }

        //  dd($request->all());
        $rules = [
            'service_id' => 'required|string',
            'billers_code' => 'required|string',
            'variation_code' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'balance' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $balance = Balance::where('user_id', $user->id)
            ->where('id', $request->balance)
            ->first();

        if (!$balance || $balance->amount < $request->amount) {
            $msg = 'Insufficient balance or balance not found';
            return $request->expectsJson()
                ? response()->json(['message' => $msg], 400)
                : redirect()->back()->with('error', $msg);
        }

        $request_id = uniqid('vtpass_');

        $payload = [
            'request_id' => $request_id,
            'serviceID' => $request->service_id,
            'billersCode' => $request->billers_code,
            'variation_code' => $request->variation_code,
            'amount' => $request->amount,
            'phone' => $request->billers_code,
        ];

        $response = Http::withBasicAuth(
            env('VTPASS_USERNAME'),
            env('VTPASS_PASSWORD')
        )->post(env('VTPASS_API_URL') . '/pay', $payload)->json();

        $status = ($response['code'] ?? '') === '000' ? 'success' : 'failed';
        $transaction = $response['content']['transactions'] ?? [];

        $payment = BillPayment::create([
            'user_id' => $user->id,
            'request_id' => $request_id,
            'service_id' => $request->service_id,
            'variation_code' => $request->variation_code,
            'billers_code' => $request->billers_code,
            'amount' => $transaction['amount'] ?? $request->amount,
            'phone' => $transaction['phone'] ?? $request->billers_code,
            'response' => $response,
            'status' => $status,
        ]);

        if ($status === 'success') {
            $balance->amount -= $request->amount;
            $balance->save();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Payment processed',
                'status' => $status,
                'data' => $payment
            ], 200);
        }

        return redirect()->route('bill_payment')->with('success', 'Data purchase ' . $status);
    }


    public function getUserBillPayments(Request $request)
    {
     $user = auth()->user();
        $team = TeamMembers::where('user_id', $user->id)->first();
        $ownerId = $team ? $team->owner_id : $user->id;

        $billPayments = BillPayment::where('user_id', $ownerId)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
            'data' => [ 
                'status' => 'success',
                'data' => $billPayments
            ]], 200);
        }

        return view('billpayments.index', compact('billPayments'));
    }



    

    



}
