<?php

namespace App\Http\Controllers\Payaza;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PayazaService;

class PayoutController extends Controller
{
    protected $payaza;

    public function __construct(PayazaService $payaza)
    {
        $this->payaza = $payaza;
    }

public function sendPayout(Request $request)
{
    $payload = [
        "transaction_type" => "nuban",
        "service_payload" => [
            "payout_amount" => 100,
            "transaction_pin" => 1111,
            "account_reference" => "1010000009",
            "currency" => "NGN",
            "country" => "NGA",
            "payout_beneficiaries" => [
                [
                    "credit_amount" => 100,
                    "account_number" => "9207067319",
                    "account_name" => "John Doe",
                    "bank_code" => "000013",
                    "narration" => "Test Payout",
                    "transaction_reference" => "TXN_" . time(),
                    "sender" => [
                        "sender_name" => "Jane Doe",
                        "sender_id" => "",
                        "sender_phone_number" => "01234595",
                        "sender_address" => "123, Ace Street"
                    ]
                ]
            ]
        ]
    ];

    // Call Payaza
    $response = $this->payaza->initiatePayout($payload);

    return response()->json($response);
}
}