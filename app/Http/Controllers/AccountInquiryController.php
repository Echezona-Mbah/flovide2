<?php

namespace App\Http\Controllers;

use App\Services\BankCodeResolver;
use App\Services\PivotService;
use App\Services\PayazaService;
use App\Services\OrchardService;
use App\Services\OhentPayService;
use App\Services\ProviderRoutingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        $request->validate([
            'currency'        => 'required|string|size:3',
            'bank_code'       => 'required|string',
            'account_number'  => 'required|string',
            'transfer_method' => 'nullable|in:bank,mobile',
            'mode'            => 'nullable|in:test,live',
        ]);

        $currency = strtoupper($request->currency);

        $resolvedProvider = $this->routing->resolveForCurrency($currency, $request->transfer_method);

        if (!$resolvedProvider) {
            return response()->json([
                'success' => false,
                'message' => 'No provider available for this currency.',
                'code'    => 'PROVIDER_NOT_AVAILABLE',
                'data'    => ['currency' => $currency]
            ], 422);
        }

        // ── Flovide bank code → the live provider's own code ────────────────────
        $resolver    = app(BankCodeResolver::class);
        $providerRow = $resolver->forProvider($resolvedProvider->key, $currency, (string) $request->bank_code);


        if (!$providerRow && $resolver->isLinked($currency)) {
            return response()->json([
                'success' => false,
                'message' => "This bank is not supported by the current provider for {$currency}.",
                'code'    => 'BANK_NOT_SUPPORTED',
                'data'    => null
            ], 422);
        }

        // Currency not linked yet → keep the old behaviour (use the code as received)
        $providerCode = $providerRow?->bank_code ?? $request->bank_code;
        $providerSort = $providerRow?->sort_code ?: $providerCode;
        $bankCode     = strtoupper($providerCode);

        switch ($resolvedProvider->key) {

            case 'ohentpay':
                if (!$providerRow?->provider_bank_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unrecognised bank code for this currency.',
                        'code'    => 'BANK_CODE_NOT_FOUND',
                        'data'    => null
                    ], 422);
                }

                $response = $this->ohentpay->validateRecipient([
                    'country'        => $providerRow->country_iso,
                    'currency'       => $currency,
                    'bank_id'        => $providerRow->provider_bank_id,
                    'account_number' => $request->account_number,
                ]);

                Log::info('[AccountInquiry] OhentPay validateRecipient response', ['response' => $response]);

                if (!$response['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => data_get($response, 'data.message', 'OhentPay account validation failed'),
                        'code'    => 'OHENTPAY_VALIDATION_FAILED',
                        'data'    => null
                    ], 422);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Account inquiry successful',
                    'code'    => 'ACCOUNT_INQUIRY_SUCCESS',
                    'data'    => [
                        'account_name' => data_get($response, 'data.account_name')
                            ?? data_get($response, 'data.bank_account.account_name'),
                    ]
                ], 200);

            case 'pivot':
                $auth = $this->pivot->authenticate();

                if (isset($auth['error']) || !isset($auth['tokenResponse']['accessToken'])) {
                    Log::error('[AccountInquiry] Pivot authentication failed', ['auth_response' => $auth]);

                    return response()->json([
                        'success' => false,
                        'message' => $auth['error'] ?? $auth['message'] ?? 'Pivot authentication failed',
                        'code'    => 'PIVOT_AUTH_FAILED',
                        'data'    => $auth
                    ], 500);
                }

                $token = $auth['tokenResponse']['accessToken'];

                if (in_array($bankCode, ['MTN', 'AIRTEL'], true)) {
                    $payload = [
                        'serviceCode'   => config('services.pivot.ugx_mobile_service_validation'),
                        'accountNumber' => $request->account_number,
                        'msisdn'        => $request->account_number,
                        'extraData'     => ['amount' => '0'],
                    ];
                } else {
                    $payload = [
                        'serviceCode'   => config('services.pivot.ugx_bank_service'),
                        'accountNumber' => $request->account_number,
                        'msisdn'        => $request->account_number,
                        'extraData'     => [
                            'bankSortCode' => $providerSort,
                            'amount'       => '0',
                        ],
                    ];
                }

                $response = $this->pivot->accountValidation($token, $payload);

                return response()->json([
                    'success' => true,
                    'message' => 'Account inquiry successful',
                    'code'    => 'ACCOUNT_INQUIRY_SUCCESS',
                    'data'    => ['account_name' => $response['customerNames'] ?? null]
                ], 200);

            case 'app_mobile':
                $payload = [
                    'customer_number' => $request->account_number,
                    'exttrid'         => uniqid('APPM_'),
                    'service_id'      => env('ORCHARD_SERVICE_ID'),
                    'nw'              => 'BNK',
                    'bank_code'       => $providerCode,
                    'trans_type'      => 'AII',
                    'ts'              => now()->utc()->format('Y-m-d H:i:s'),
                ];

                $response    = $this->orchard->accountInquiry($payload);
                $accountName = data_get($response, 'data.name')
                    ?? data_get($response, 'data.response_content.account_name');

                if (!($response['success'] ?? false) || empty($accountName)) {
                    return response()->json([
                        'success' => false,
                        'message' => data_get($response, 'data.resp_desc') ?? ($response['error'] ?? 'Account validation failed'),
                        'code'    => 'APPMOBILE_VALIDATION_FAILED',
                        'data'    => null
                    ], 422);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Account inquiry successful',
                    'code'    => 'ACCOUNT_INQUIRY_SUCCESS',
                    'data'    => ['account_name' => $accountName]
                ], 200);

            case 'payaza':
                $response = $this->payaza->accountEnquiry($currency, $providerCode, $request->account_number);

                return response()->json([
                    'success' => true,
                    'message' => 'Account inquiry successful',
                    'code'    => 'ACCOUNT_INQUIRY_SUCCESS',
                    'data'    => ['account_name' => data_get($response, 'data.response_content.account_name')]
                ], 200);

            case 'swappr':
                if ($currency !== 'NGN') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Swappr name enquiry is only available for NGN.',
                        'code'    => 'unsupported_currency',
                        'data'    => ['currency' => $currency],
                    ], 400);
                }

                $validated = $request->validate([
                    'account_number' => ['required', 'regex:/^\d{10}$/'],
                    'bank_code' => ['required', 'regex:/^\d+$/'],
                ]);

                $swapprBank = $providerRow;
                if (!$swapprBank || $swapprBank->provider !== 'swappr') {
                    $swapprBank = \App\Models\Bank::query()
                        ->where('provider', 'swappr')
                        ->where('currency', 'NGN')
                        ->where(function ($query) use ($request) {
                            $query->where('flovide_code', $request->bank_code)
                                ->orWhere('bank_code', $request->bank_code)
                                ->orWhere('bank_nibss_code', $request->bank_code);
                        })
                        ->first();
                }

                if (!$swapprBank) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This bank is not supported by Swappr.',
                        'code'    => 'unknown_bank_code',
                        'data'    => null,
                    ], 422);
                }

                $swapprBankCode = in_array((string) $request->bank_code, [
                    (string) $swapprBank->bank_code,
                    (string) $swapprBank->bank_nibss_code,
                ], true)
                    ? (string) $request->bank_code
                    : (string) ($swapprBank->bank_code ?: $swapprBank->bank_nibss_code);

                $isTestUrl = str_starts_with($request->path(), 'api/test/');
                $environment = $isTestUrl
                    ? 'test'
                    : ($request->input('mode') ?? session('mode', 'live'));
                $response = app(\App\Services\SwapprService::class)->nameEnquiry(
                    $validated['account_number'],
                    $swapprBankCode,
                    (string) $swapprBank->name,
                    (string) Str::uuid(),
                    $environment
                );

                if (!$response['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => $response['message'] ?? 'Swappr name enquiry failed.',
                        'code' => $response['code'] ?? 'SWAPPR_NAME_ENQUIRY_FAILED',
                        'data' => $response['data'],
                    ], $response['status'] ?: 502);
                }

                $resolvedName = data_get($response, 'data.resolved_name');
                if (!is_string($resolvedName) || trim($resolvedName) === '') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Swappr did not return a resolved account name.',
                        'code' => 'SWAPPR_NAME_ENQUIRY_INVALID_RESPONSE',
                        'data' => null,
                    ], 502);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Account inquiry successful',
                    'code' => 'ACCOUNT_INQUIRY_SUCCESS',
                    'data' => [
                        'account_name' => $resolvedName,
                        'resolved_name' => $resolvedName,
                        'source' => data_get($response, 'data.source'),
                        'resolved_by_provider' => data_get($response, 'data.resolved_by_provider'),
                    ],
                ], 200);

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Validation not implemented for this provider.',
                    'code'    => 'PROVIDER_NOT_IMPLEMENTED',
                    'data'    => ['currency' => $currency]
                ], 422);
        }
    }
}