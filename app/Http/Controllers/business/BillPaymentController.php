<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BillPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;


class BillPaymentController extends Controller
{

    public function index(Request $request)
    {
        $payments = BillPayment::where('user_id', $request->user()?->id ?? auth()->id())
            ->latest()
            ->get();

        if ($request->expectsJson()) {
            return response()->json(['data' => $payments], 200);
        }

        return view('billpayments.index', compact('payments'));
    }

    public function indexElectricity(Request $request)
    {
        $payments = BillPayment::where('user_id', $request->user()?->id ?? auth()->id())
            ->latest()
            ->get();

        if ($request->expectsJson()) {
            return response()->json(['data' => $payments], 200);
        }

        return view('billpayments.index', compact('payments'));
    }


    public function getVariations(Request $request)
    {
        $request->validate([
            'service_id' => 'required|string',
        ]);
    
        $url = env('VTPASS_API_URL') . '/service-variations?serviceID=' . $request->service_id;
    
        $response = Http::withBasicAuth(env('VTPASS_USERNAME'), env('VTPASS_PASSWORD'))
            ->get($url)
            ->json();
    
        if ($request->expectsJson()) {
            return response()->json($response);
        }
    
        return view('billpayments.variations', ['response' => $response]);
    }


    


    // Verify smartcard or biller code via VTpass API
    public function verify(Request $request)
    {
        $rules = [
            'billers_code' => 'required|string',
            'service_id' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $response = Http::withBasicAuth(env('VTPASS_USERNAME'), env('VTPASS_PASSWORD'))
        ->post(env('VTPASS_API_URL') . '/merchant-verify', [
            'billersCode' => $request->billers_code,
            'serviceID' => $request->service_id,
            'type' => 'smartcard',
        ])->json();
       // dd(env('VTPASS_API_URL') . '/merchant-verify');


        if ($request->expectsJson()) {
            return response()->json($response);
        }

        return view('billpayments.verify', ['response' => $response]);
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
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $response = Http::withBasicAuth(env('VTPASS_USERNAME'), env('VTPASS_PASSWORD'))
        ->post(env('VTPASS_API_URL') . '/merchant-verify', [
            'billersCode' => $request->billers_code,
            'serviceID' => $request->service_id,
            'type' => $request->type,
        ])->json();


        if ($request->expectsJson()) {
            return response()->json($response);
        }

        return view('billpayments.verify', ['response' => $response]);
    }

    // Store payment and call VTpass pay API
    public function store(Request $request)
    {
        $rules = [
            'service_id' => 'required|string',
            'billers_code' => 'required|string',
            'variation_code' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'phone' => 'required|string',
            'currency' => 'required|string',

        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request_id = uniqid('vtpass_');

        $payload = [
            'request_id' => $request_id,
            'serviceID' => $request->service_id,
            'billersCode' => $request->billers_code,
            'variation_code' => $request->variation_code,
            'amount' => $request->amount,
            'phone' => $request->phone,
            'currency' => $request->currency,

        ];

        $response = Http::withBasicAuth(env('VTPASS_USERNAME'), env('VTPASS_PASSWORD'))
            ->post(env('VTPASS_API_URL') . '/pay', $payload)
            ->json();

        $status = ($response['code'] ?? '') === '000' ? 'success' : 'failed';

        $payment = BillPayment::create([
            'user_id' => $request->user()?->id ?? auth()->id(),
            'request_id' => $request_id,
            'service_id' => $request->service_id,
            'variation_code' => $request->variation_code,
            'billers_code' => $request->billers_code,
            'amount' => $request->amount,
            'phone' => $request->phone,
            'currency' => $request->currency,
            'response' => $response,
            'status' => $status,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Payment processed',
                'status' => $status,
                'data' => $payment
            ], 201);
        }

        return redirect()->route('billpayments.index')->with('success', 'Payment processed: ' . $status);
    }

    // Delete a payment record
    public function destroy(Request $request, BillPayment $billPayment)
    {
        if ($billPayment->user_id !== ($request->user()?->id ?? auth()->id())) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized');
        }

        $billPayment->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Payment deleted successfully'], 200);
        }

        return redirect()->route('billpayments.index')->with('success', 'Payment deleted successfully.');
    }

    // public function recharge(Request $request)
    // {
    //     $request_id = uniqid();
    //     $response = Http::withBasicAuth(env('VTPASS_USERNAME'), env('VTPASS_PASSWORD'))
    //         ->post(env('VTPASS_API_URL') . '/pay', [
    //             'request_id' => $request_id,
    //             'serviceID' => 'dstv',
    //             'billersCode' => $request->smartcard,
    //             'variation_code' => $request->variation_code,
    //             'amount' => $request->amount,
    //             'phone' => $request->phone,
    //         ])->json();

    //     return response()->json($response);
    // }

}
