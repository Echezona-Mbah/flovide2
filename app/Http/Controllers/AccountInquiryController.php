<?php

namespace App\Http\Controllers;

use App\Services\PivotService;
use App\Services\PayazaService;
use App\Services\OrchardService;
use App\Services\OhentPayService;
use App\Services\ProviderRoutingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountInquiryController extends Controller
{
    protected $orchard;
    protected $payaza;
    protected $pivot;
    protected $ohentpay;
    protected $routing;

    public function __construct(
        PivotService $pivot,
        PayazaService $payaza,
        OrchardService $orchard,
        OhentPayService $ohentpay,
        ProviderRoutingService $routing
    ) {
        $this->pivot    = $pivot;
        $this->payaza   = $payaza;
        $this->orchard  = $orchard;
        $this->ohentpay = $ohentpay;
        $this->routing  = $routing;
    }

    public function unifiedAccountInquiry(Request $request)
    {
        // ── Only 3 inputs needed, no matter which provider ends up handling it ──
        $request->validate([
            'currency'        => 'required|string|size:3',
            'bank_code'       => 'required|string',
            'account_number'  => 'required|string',
        ]);

        $currency = strtoupper($request->currency);
        $bankCode = strtoupper($request->bank_code);

        $resolvedProvider = $this->routing->resolveForCurrency($currency, $request->transfer_method);


        if (!$resolvedProvider) {
            return response()->json([
                'success' => false,
                'message' => 'No provider available for this currency.',
                'code' => 'PROVIDER_NOT_AVAILABLE',
                'data' => ['currency' => $currency]
            ], 422);
        }

        switch ($resolvedProvider->key) {

            case 'ohentpay':
                // Look up the synced bank row using just currency + bank_code —
                // country is derived from that row, never from the frontend.
                $bankRow = $this->ohentpay->resolveBank($bankCode, $currency);

                Log::info('[AccountInquiry] OhentPay bank resolution', [
                    'input_bank_code' => $bankCode,
                    'currency'        => $currency,
                    'found'           => (bool) $bankRow,
                    'country_iso'     => $bankRow?->country_iso,
                    'provider_bank_id'=> $bankRow?->provider_bank_id,
                ]);

                if (!$bankRow || !$bankRow->provider_bank_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unrecognised bank code for this currency.',
                        'code' => 'BANK_CODE_NOT_FOUND',
                        'data' => null
                    ], 422);
                }

                $response = $this->ohentpay->validateRecipient([
                    'country'        => $bankRow->country_iso,     // ← derived, not sent by frontend
                    'currency'       => $currency,
                    'bank_id'        => $bankRow->provider_bank_id, // ← OhentPay's internal id
                    'account_number' => $request->account_number,
                ]);

                Log::info('[AccountInquiry] OhentPay validateRecipient response', [
                    'response' => $response,
                ]);

                if (!$response['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => data_get($response, 'data.message', 'OhentPay account validation failed'),
                        'code' => 'OHENTPAY_VALIDATION_FAILED',
                        'data' => null
                    ], 422);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Account inquiry successful',
                    'code' => 'ACCOUNT_INQUIRY_SUCCESS',
                    'data' => [
                        'account_name' => data_get($response, 'data.account_name')
                            ?? data_get($response, 'data.bank_account.account_name'),
                    ]
                ], 200);

            case 'pivot':
                $auth = $this->pivot->authenticate();

                if (isset($auth['error']) || !isset($auth['tokenResponse']['accessToken'])) {
                    Log::error('[AccountInquiry] Pivot authentication failed or returned unexpected shape', [
                        'auth_response' => $auth,
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => $auth['error'] ?? $auth['message'] ?? 'Pivot authentication failed',
                        'code' => 'PIVOT_AUTH_FAILED',
                        'data' => $auth
                    ], 500);
                }

                $token = $auth['tokenResponse']['accessToken'];
                $mobileCodes = ['MTN', 'AIRTEL'];

                if (in_array($bankCode, $mobileCodes, true)) {
                    $payload = [
                        'serviceCode' => config('services.pivot.ugx_mobile_service_validation'),
                        'accountNumber' => $request->account_number,
                        'msisdn' => $request->account_number,
                        'extraData' => ['amount' => '0'],
                    ];
                } else {
                    $payload = [
                        'serviceCode' => config('services.pivot.ugx_bank_service'),
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
                    'data' => ['account_name' => $response['customerNames'] ?? null]
                ], 200);

            case 'app_mobile':
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
                    'data' => ['account_name' => data_get($response, 'data.response_content.account_name')]
                ], 200);

            case 'payaza':
                $response = $this->payaza->accountEnquiry(
                    $currency,
                    $request->bank_code,
                    $request->account_number
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Account inquiry successful',
                    'code' => 'ACCOUNT_INQUIRY_SUCCESS',
                    'data' => ['account_name' => data_get($response, 'data.response_content.account_name')]
                ], 200);

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Validation not implemented for this provider.',
                    'code' => 'PROVIDER_NOT_IMPLEMENTED',
                    'data' => ['currency' => $currency]
                ], 422);
        }
    }
}