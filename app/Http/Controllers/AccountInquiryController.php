<?php

namespace App\Http\Controllers;

use App\Services\PivotService;
use App\Services\PayazaService;
use App\Services\OrchardService;
use Illuminate\Http\Request;

class AccountInquiryController extends Controller
{
    protected $orchard;
    protected $payaza;
    protected $pivot;

    public function __construct(PivotService $pivot, PayazaService $payaza, OrchardService $orchard)
    {
        $this->pivot = $pivot;
        $this->payaza = $payaza;
        $this->orchard = $orchard;
    }

    // public function unifiedAccountInquiry(Request $request)
    // {
    //     $request->validate([
    //         'currency' => 'required|string|size:3',
    //         'bank_code' => 'required|string',
    //         'account_number' => 'required|string',
    //     ]);

    //     $currency = strtoupper($request->currency);
    //     $bankCode = strtoupper($request->bank_code);

    //     if ($currency === 'UGX' && env('PIVOT_ENABLED', false)) {
    //         $auth = $this->pivot->authenticate();

    //         if (isset($auth['error'])) {
    //             return response()->json($auth, 500);
    //         }

    //         $token = $auth['tokenResponse']['accessToken'];

    //         $mobileCodes = ['MTN', 'AIRTEL'];

    //         if (in_array($bankCode, $mobileCodes, true)) {
    //             $payload = [
    //                 'serviceCode' => env('PIVOT_UGX_MOBILE_SERVICE'),
    //                 'accountNumber' => $request->account_number,
    //                 'msisdn' => $request->account_number,
    //                 'extraData' => [
    //                     'amount' => '0',
    //                 ],
    //             ];
    //         } else {
    //             $payload = [
    //                 'serviceCode' => env('PIVOT_UGX_BANK_SERVICE'),
    //                 'accountNumber' => $request->account_number,
    //                 'msisdn' => $request->account_number,
    //                 'extraData' => [
    //                     'bankSortCode' => $request->bank_code,
    //                     'amount' => '0',
    //                 ],
    //             ];
    //         }

    //         $response = $this->pivot->accountValidation($token, $payload);

    //         return response()->json([
    //             'provider' => 'pivot',
    //             'accountName' => $response['customerNames'] ?? null,
    //             'data' => $response,
    //         ]);
    //     }

    //     if ($currency === 'GHS' && env('APP_MOBILE', false)) {
    //         $payload = [
    //             'customer_number' => $request->account_number,
    //             'exttrid' => uniqid('APPM_'),
    //             'service_id' => env('ORCHARD_SERVICE_ID'),
    //             'nw' => 'BNK',
    //             'bank_code' => $request->bank_code,
    //             'trans_type' => 'AII',
    //             'ts' => now()->utc()->format('Y-m-d H:i:s'),
    //         ];

    //         $response = $this->orchard->accountInquiry($payload);

    //         return response()->json([
    //             'provider' => 'appmobile',
    //             'data' => $response,
    //         ]);
    //     }

    //     $payazaCurrencies = ['UGX', 'NGN', 'TZS', 'KES', 'XOF', 'XAF', 'ZAR', 'GHS'];

    //     if (in_array($currency, $payazaCurrencies, true) && env('PAYAZA_ENABLED', false)) {
    //         $response = $this->payaza->accountEnquiry(
    //             $currency,
    //             $request->bank_code,
    //             $request->account_number
    //         );

    //         return response()->json([
    //             'provider' => 'payaza',
    //             'data' => $response,
    //         ]);
    //     }

    //     return response()->json([
    //         'message' => 'No provider available for this currency.',
    //         'currency' => $currency,
    //     ], 422);
    // }



//     public function unifiedAccountInquiry(Request $request)
// {
//     $request->validate([
//         'currency' => 'required|string|size:3',
//         'bank_code' => 'required|string',
//         'account_number' => 'required|string',
//     ]);

//     $currency = strtoupper($request->currency);
//     $bankCode = strtoupper($request->bank_code);

//     if ($currency === 'UGX' && env('PIVOT_ENABLED', false)) {
//         $auth = $this->pivot->authenticate();

//         if (isset($auth['error'])) {
//             return response()->json([
//                 'success' => false,
//                 'message' => $auth['error'] ?? 'Pivot authentication failed',
//                 'code' => 'PIVOT_AUTH_FAILED',
//                 'data' => $auth
//             ], 500);
//         }

//         $token = $auth['tokenResponse']['accessToken'];
//         $mobileCodes = ['MTN', 'AIRTEL'];

//         if (in_array($bankCode, $mobileCodes, true)) {
//             $payload = [
//                 'serviceCode' => env('PIVOT_UGX_MOBILE_SERVICE'),
//                 'accountNumber' => $request->account_number,
//                 'msisdn' => $request->account_number,
//                 'extraData' => [
//                     'amount' => '0',
//                 ],
//             ];
//         } else {
//             $payload = [
//                 'serviceCode' => env('PIVOT_UGX_BANK_SERVICE'),
//                 'accountNumber' => $request->account_number,
//                 'msisdn' => $request->account_number,
//                 'extraData' => [
//                     'bankSortCode' => $request->bank_code,
//                     'amount' => '0',
//                 ],
//             ];
//         }

//         $response = $this->pivot->accountValidation($token, $payload);

//         return response()->json([
//             'success' => true,
//             'message' => 'Account inquiry successful',
//             'code' => 'ACCOUNT_INQUIRY_SUCCESS',
//             'data' => [
//                 // 'provider' => 'pivot',
//                 'accountName' => $response['customerNames'] ?? null,
//                 // 'raw' => $response,
//             ]
//         ], 200);
//     }

//     if ($currency === 'GHS' && env('APP_MOBILE', false)) {
//         $payload = [
//             'customer_number' => $request->account_number,
//             'exttrid' => uniqid('APPM_'),
//             'service_id' => env('ORCHARD_SERVICE_ID'),
//             'nw' => 'BNK',
//             'bank_code' => $request->bank_code,
//             'trans_type' => 'AII',
//             'ts' => now()->utc()->format('Y-m-d H:i:s'),
//         ];

//         $response = $this->orchard->accountInquiry($payload);

//         return response()->json([
//             'success' => true,
//             'message' => 'Account inquiry successful',
//             'code' => 'ACCOUNT_INQUIRY_SUCCESS',
//             'data' => [
//                 'provider' => 'appmobile',
//                 'raw' => $response
//             ]
//         ], 200);
//     }

//     $payazaCurrencies = ['UGX', 'NGN', 'TZS', 'KES', 'XOF', 'XAF', 'ZAR', 'GHS'];

//     if (in_array($currency, $payazaCurrencies, true) && env('PAYAZA_ENABLED', false)) {
//         $response = $this->payaza->accountEnquiry(
//             $currency,
//             $request->bank_code,
//             $request->account_number
//         );

//         return response()->json([
//             'success' => true,
//             'message' => 'Account inquiry successful',
//             'code' => 'ACCOUNT_INQUIRY_SUCCESS',
//             'data' => [
//                 'provider' => 'payaza',
//                 'raw' => $response
//             ]
//         ], 200);
//     }

//     return response()->json([
//         'success' => false,
//         'message' => 'No provider available for this currency.',
//         'code' => 'PROVIDER_NOT_AVAILABLE',
//         'data' => [
//             'currency' => $currency
//         ]
//     ], 422);
// }

public function unifiedAccountInquiry(Request $request)
{
    $request->validate([
        'currency' => 'required|string|size:3',
        'bank_code' => 'required|string',
        'account_number' => 'required|string',
    ]);

    $currency = strtoupper($request->currency);
    $bankCode = strtoupper($request->bank_code);

    if ($currency === 'UGX' && env('PIVOT_ENABLED', false)) {
        $auth = $this->pivot->authenticate();

        if (isset($auth['error'])) {
            return response()->json([
                'success' => false,
                'message' => $auth['error'] ?? 'Pivot authentication failed',
                'code' => 'PIVOT_AUTH_FAILED',
                'data' => $auth
            ], 500);
        }

        $token = $auth['tokenResponse']['accessToken'];
        $mobileCodes = ['MTN', 'AIRTEL'];

        if (in_array($bankCode, $mobileCodes, true)) {
            $payload = [
                'serviceCode' => env('PIVOT_UGX_MOBILE_SERVICE_VALIDATION'),
                'accountNumber' => $request->account_number,
                'msisdn' => $request->account_number,
                'extraData' => [
                    'amount' => '0',
                ],
            ];
        } else {
            $payload = [
                'serviceCode' => env('PIVOT_UGX_BANK_SERVICE'),
                'accountNumber' => $request->account_number,
                'msisdn' => $request->account_number,
                'extraData' => [
                    'bankSortCode' => $request->bank_code,
                    'amount' => '0',
                ],
            ];
        }

        $response = $this->pivot->accountValidation($token, $payload);

        return response()->json([
            'success' => true,
            'message' => 'Account inquiry successful',
            'code' => 'ACCOUNT_INQUIRY_SUCCESS',
            'data' => [
                'account_name' => $response['customerNames'] ?? null,
            ]
        ], 200);
    }

    if ($currency === 'GHS' && env('APP_MOBILE', false)) {
        $payload = [
            'customer_number' => $request->account_number,
            'exttrid' => uniqid('APPM_'),
            'service_id' => env('ORCHARD_SERVICE_ID'),
            'nw' => 'BNK',
            'bank_code' => $request->bank_code,
            'trans_type' => 'AII',
            'ts' => now()->utc()->format('Y-m-d H:i:s'),
        ];

        $response = $this->orchard->accountInquiry($payload);

        return response()->json([
            'success' => true,
            'message' => 'Account inquiry successful',
            'code' => 'ACCOUNT_INQUIRY_SUCCESS',
            'data' => [
                'account_name' => data_get($response, 'data.response_content.account_name'),
            ]
        ], 200);
    }

    $payazaCurrencies = ['UGX', 'NGN', 'TZS', 'KES', 'XOF', 'XAF', 'ZAR', 'GHS'];

    if (in_array($currency, $payazaCurrencies, true) && env('PAYAZA_ENABLED', false)) {
        $response = $this->payaza->accountEnquiry(
            $currency,
            $request->bank_code,
            $request->account_number
        );

        return response()->json([
            'success' => true,
            'message' => 'Account inquiry successful',
            'code' => 'ACCOUNT_INQUIRY_SUCCESS',
            'data' => [
                'account_name' => data_get($response, 'data.response_content.account_name'),
            ]
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => 'No provider available for this currency.',
        'code' => 'PROVIDER_NOT_AVAILABLE',
        'data' => [
            'currency' => $currency
        ]
    ], 422);
}





}
