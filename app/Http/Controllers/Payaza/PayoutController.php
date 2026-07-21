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
        // Get the numeric account reference for NGN
        $accountReference = $this->payaza->getAccountReference('NGN');
        $transactionReference = "TXN_" . time();

        // dd($accountReference);

        if (!$accountReference) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to retrieve account reference from Payaza'
            ], 500);
        }

        // Prepare payload
        $payload = [
            "transaction_type" => "nuban",
            "service_payload" => [
                "payout_amount" => 100,
                "transaction_pin" => 123123, // your merchant transaction pin
                "account_reference" => $accountReference,
                "currency" => "NGN",
                "country" => "NGA",
                "payout_beneficiaries" => [
                    [
                        "credit_amount" => 500,
                        "account_number" => "6322069407", // 10 digits for NGN
                        "account_name" => "Mbah Echezona Ernest",
                        "bank_code" => "000007",
                        "narration" => "Test Payout",
                        "transaction_reference" => $transactionReference,
                        "sender" => [
                            "sender_name" => "Echezona Doe",
                            "sender_id" => "233",
                            "sender_phone_number" => "01234595",
                            "sender_address" => "123, Ace Street"
                        ]
                    ]
                ]
            ]
        ];

        // Call Payaza
        $response = $this->payaza->initiatePayout($payload);

       return response()->json([
        'reference' => $transactionReference,
        'payaza' => $response
        ]);
    }

        public function sendviapayaza(Request $request)
    {
        // Get the numeric account reference for NGN
        $accountReference = $this->payaza->getAccountReference('NGN');
        $transactionReference = "TXN_" . time();

        //dd($accountReference);

        if (!$accountReference) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to retrieve account reference from Payaza'
            ], 500);
        }

        // Prepare payload
        $payload = [
            "transaction_type" => "nuban",
            "service_payload" => [
                "payout_amount" => 100,
                "transaction_pin" => 124567, // your merchant transaction pin
                "account_reference" => $accountReference,
                "currency" => "NGN",
                "country" => "NGA",
                "payout_beneficiaries" => [
                    [
                        "credit_amount" => 100,
                        "account_number" => "9207067319", // 10 digits for NGN
                        "account_name" => "John Doe",
                        "bank_code" => "000013",
                        "narration" => "Test Payout",
                        "transaction_reference" => $transactionReference,
                        "sender" => [
                            "sender_name" => "Echezona Doe",
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

       return response()->json([
        'reference' => $transactionReference,
        'payaza' => $response
        ]);
    }


    public function transactionStatus(Request $request)
    {
        $request->validate([
            'reference' => 'required|string'
        ]);

        $reference = $request->reference;

        $response = $this->payaza->getTransactionStatus($reference);

        return response()->json($response);
    }

    public function accountEnquiry(Request $request)
    {
        $request->validate([
            'currency' => 'required|string',
            'account_number' => 'required|string',
            'bank_code' => 'required|string', // required for both
        ]);


        $response = $this->payaza->accountEnquiry(
            $request->currency,
            $request->bank_code,   // always use bank_code
            $request->account_number,
        );
        return response()->json($response);
    }

//     public function accountEnquiry(Request $request)
// {
//     $request->validate([
//         'currency' => 'required|string',
//         'type' => 'required|string|in:bank,mobile',
//         'account_number' => 'required|string',
//         'bank_code' => 'sometimes|required_if:type,bank|string',
//         'provider' => 'sometimes|required_if:type,mobile|string',
//     ]);

//     $type = $request->type;

//     $response = $this->payaza->accountEnquiry(
//         $request->currency,
//         $type === 'bank' ? $request->bank_code : $request->provider,
//         $request->account_number,
//         $type
//     );

//     return response()->json($response);
// }

    public function getBanks(Request $request)
    {

        $request->validate([
            'currency' => 'required|string'
        ]);
        $currency = $request->currency;
        $response = $this->payaza->getBanks($currency);
        return response()->json($response);
    }




}