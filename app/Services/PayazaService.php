<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayazaService
{
    protected $publicKey;
    protected $secretKey;
    protected $tenantId;
    protected $baseUrl;
    protected $accountDetailsUrl;

    public function __construct()
    {
        $this->publicKey = env('PAYAZA_PUBLIC_KEY');
        $this->secretKey = env('PAYAZA_SECRET_KEY');
        $this->tenantId = env('PAYAZA_TENANT_ID');
        $this->baseUrl = env('PAYAZA_BASE_URL');
        $this->accountDetailsUrl = env('PAYAZA_ACCOUNT_DETAILS_URL');
    }
    private function authHeader()
    {
        // Only base64 encode the PUBLIC key
        return 'Payaza ' . base64_encode($this->publicKey);
    }

    /**
     * Get numeric account reference for a given currency
     */
public function getAccountReference($currency)
{
    $response = Http::withHeaders([
        'Authorization' => $this->authHeader(),
        'X-TenantID' => $this->tenantId,
        'Accept' => 'application/json',
    ])->get($this->accountDetailsUrl);

    if (!$response->ok()) {
        logger('Payaza account error', [$response->body()]);
        return null;
    }

    $accounts = $response->json('data');

    logger('Payaza accounts list', $accounts); // 👈 VERY IMPORTANT

    foreach ($accounts as $acc) {
        if (strtoupper($acc['currency']) === strtoupper($currency)) {
            return $acc['payazaAccountReference'];
        }
    }

    return null;
}
    /**
     * Initiate a payout
     */
    public function initiatePayout(array $payload)
    {
        try {
            // Base64 encode only the public key
            $encodedKey = base64_encode($this->publicKey);

            // Make the POST request
            $response = Http::withHeaders([
                'Authorization' => 'Payaza ' . $encodedKey,
                'X-TenantID' => $this->tenantId,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl, $payload);

            // Check if request was successful
            if ($response->successful()) {
                return [
                    'success' => true,
                    'http_status' => $response->status(),
                    'data' => $response->json(),
                    'raw' => $response->body(),
                ];
            }

            // Handle client/server errors
            return [
                'success' => false,
                'http_status' => $response->status(),
                'data' => $response->json(),
                'raw' => $response->body(),
                'message' => $response->json('response_message') ?? 'Unknown error from Payaza'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getTransactionStatus($transactionReference)
    {
        try {

            $encodedKey = base64_encode($this->publicKey);

            $url = "https://api.payaza.africa/live/payaza-account/api/v1/mainaccounts/merchant/transaction/{$transactionReference}";

            $response = Http::withHeaders([
                'Authorization' => 'Payaza ' . $encodedKey,
                'X-TenantID' => $this->tenantId,
                'Accept' => 'application/json',
            ])->get($url);

            return [
                'success' => $response->successful(),
                'http_status' => $response->status(),
                'data' => $response->json(),
                'raw' => $response->body(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // public function accountEnquiry($currency, $bankCode, $accountNumber)
    // {
    //     try {

    //         $encodedKey = base64_encode($this->publicKey);

    //         $url = "https://api.payaza.africa/live/payaza-account/api/v1/mainaccounts/merchant/provider/enquiry";

    //         $payload = [
    //             "service_payload" => [
    //                 "currency" => $currency,
    //                 "bank_code" => $bankCode,
    //                 "account_number" => $accountNumber
    //             ]
    //         ];

    //         $response = Http::withHeaders([
    //             'Authorization' => 'Payaza ' . $encodedKey,
    //             'X-TenantID' => $this->tenantId,
    //             'Content-Type' => 'application/json',
    //             'Accept' => 'application/json',
    //         ])->post($url, $payload);

    //         return [
    //             'success' => $response->successful(),
    //             'data' => $response->json(),
    //         ];

    //     } catch (\Exception $e) {
    //         return [
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ];
    //     }
    // }

    public function accountEnquiry($currency, $bankOrProvider, $accountNumber = null, $type = 'bank')
{
    try {
        $encodedKey = base64_encode($this->publicKey);

        $url = "https://api.payaza.africa/live/payaza-account/api/v1/mainaccounts/merchant/provider/enquiry";

        $payload = [
            "service_payload" => []
        ];

        if ($type === 'bank') {
            $payload['service_payload'] = [
                "currency" => $currency,
                "bank_code" => $bankOrProvider,
                "account_number" => $accountNumber
            ];
        } else if ($type === 'mobile') {
            $payload['service_payload'] = [
                "currency" => $currency,
                "provider" => $bankOrProvider,
                "mobile_number" => $accountNumber
            ];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Payaza ' . $encodedKey,
            'X-TenantID' => $this->tenantId,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($url, $payload);

        return [
            'success' => $response->successful(),
            'data' => $response->json(),
        ];

    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

public function getBanks($currency = 'NGN')
{
    try {
        $encodedKey = base64_encode($this->publicKey);

        $url = "https://api.payaza.africa/live/payaza-account/api/v1/mainaccounts/merchant/banks/{$currency}";

        $response = Http::withHeaders([
            'Authorization' => 'Payaza ' . $encodedKey,
            'X-TenantID' => $this->tenantId, // must match URL mode
        ])->get($url);

        // If request failed, log raw response for debugging
        if (!$response->successful()) {
            logger("Payaza getBanks failed: " . $response->body());
        }

        return [
            'success' => $response->successful(),
            'http_status' => $response->status(),
            'data' => $response->json(),
            'raw' => $response->body(),
        ];

    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage(),
        ];
    }
}
}