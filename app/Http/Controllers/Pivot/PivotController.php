<?php

namespace App\Http\Controllers\Pivot;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use Illuminate\Http\Request;
use App\Services\PivotService;


class PivotController extends Controller
{
    protected $pivot;

    public function __construct(PivotService $pivot)
    {
        $this->pivot = $pivot;
    }

    

    // Endpoint for Postman
    public function sendPayment(Request $request)
    {
        // Validate request from Postman
        $request->validate([
            'serviceCode' => 'required|string',
            'msisdn' => 'required|string',
            'accountNumber' => 'required|string',
            'amount' => 'required|numeric',
            'chargeAmount' => 'nullable|numeric',
            'currencyCode' => 'required|string',
            'countryCode' => 'required|string',
            'customerName' => 'nullable|string',
            'narration' => 'nullable|string',
            'extraData' => 'nullable|array',
        ]);

        // dd($request->all());

        // 1️⃣ Authenticate
        $auth = $this->pivot->authenticate();

        if (isset($auth['error'])) {
            return response()->json($auth, 500);
        }

        $token = $auth['tokenResponse']['accessToken'];

          date_default_timezone_set('Africa/Lagos');

        // Generate merchant transaction ID: 13-15 chars
        $merchantTransactionId = 'TXN_' . substr(uniqid(), 0, 10);

        $payload = [
            "serviceCode" => $request->serviceCode,
            "msisdn" => $request->msisdn,
            "accountNumber" => $request->accountNumber,
            "merchantTransactionId" => $merchantTransactionId,
            "amount" => $request->amount,
            "chargeAmount" => $request->chargeAmount,
            "narration" => $request->narration ?? "Payment",
            "currencyCode" => $request->currencyCode,
            "countryCode" => $request->countryCode,
            "customerName" => $request->customerName ?? "Customer",
            "extraData" => isset($request->extraData) ? json_decode(json_encode($request->extraData), true) : null
        ];

      
        
        // 3️⃣ Send payment
        logger('Pivot REQUEST', $payload);

        $payment = $this->pivot->postTransaction($token, $payload);

        logger('Pivot RESPONSE', $payment);



        return response()->json($payment);
    }


      // Query payment status
    public function queryPaymentStatus(Request $request)
    {
        $request->validate([
            'transactionId' => 'required|string'
        ]);

        // 1️⃣ Authenticate
        $auth = $this->pivot->authenticate();

        if (isset($auth['error'])) {
            return response()->json($auth, 500);
        }

        $token = $auth['tokenResponse']['accessToken'];

        // 2️⃣ Build payload
        $payload = [
            'transactionId' => $request->transactionId
        ];

        // 3️⃣ Send query request
        logger('Pivot REQUEST', $payload);

        $response = $this->pivot->queryPaymentStatus($token, $payload);

        logger('Pivot RESPONSE', $response);



        return response()->json($response);
    }


public function accountValidation(Request $request)
{
    $auth = $this->pivot->authenticate();

    if (isset($auth['error'])) {
        return response()->json($auth, 500);
    }

    $token = $auth['tokenResponse']['accessToken'];

    $payload = [
        "serviceCode" => $request->serviceCode,
        "msisdn" => $request->msisdn,
        "accountNumber" => $request->accountNumber,
        "extraData" => isset($request->extraData) ? json_decode(json_encode($request->extraData), true) : null

    ];

    logger('Pivot REQUEST', $payload);

    $response = $this->pivot->accountValidation($token, $payload);

        logger('Pivot RESPONSE', $response);



    // Example Pivot response handling
    return response()->json([
        'accountName' => $response['customerNames'] ?? null,
        'raw' => $response
    ]);
}



    public function cardPayment(Request $request)
    {
        $request->validate([
            'serviceCode' => 'required|string',
            'msisdn' => 'required|string',
            'accountNumber' => 'required|string',
            'amount' => 'required|numeric',
            'currencyCode' => 'required|string',
            'countryCode' => 'required|string',
            'extraData.cardHolderName' => 'required|string',
            'extraData.expiryMonth' => 'required|digits:2',
            'extraData.expiryYear' => 'required|digits:4',
        ]);

        // 🔐 Authenticate
        $auth = $this->pivot->authenticate();

        if (isset($auth['error'])) {
            return response()->json($auth, 500);
        }

        // ✅ SAFE TOKEN EXTRACTION
        if (!isset($auth['tokenResponse']['accessToken'])) {
            return response()->json([
                'error' => 'Pivot auth failed',
                'auth_response' => $auth
            ], 500);
        }

        $token = $auth['tokenResponse']['accessToken'];

        // Generate merchant ID (13–15 chars)
        $merchantTransactionId = substr(
            'TXN' . time() . rand(100, 999),
            0,
            15
        );

        $payload = [
            "serviceCode" => $request->serviceCode,
            "msisdn" => ltrim($request->msisdn, '+'),
            "accountNumber" => $request->accountNumber,
            "amount" => $request->amount,
            "chargeAmount" => 0,
            "narration" => $request->narration ?? "Card payment",
            "currencyCode" => $request->currencyCode,
            "countryCode" => $request->countryCode,
            "merchantTransactionId" => $merchantTransactionId,
            "customerName" => $request->customerName ?? "Customer",
            "requestOrigin" => "API",
            "paymentMode" => "MOBILE",
            "extraData" => $request->extraData ?? [],
        ];

              logger('Pivot REQUEST', $payload);

        $response = $this->pivot->postCardPayment($token, $payload);
        logger('Pivot RESPONSE', $response);



        return response()->json($response);
    }





}

