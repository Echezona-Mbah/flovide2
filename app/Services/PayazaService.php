<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayazaService
{
    protected $apiKey;
    protected $tenantId;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('PAYAZA_API_KEY');
        $this->tenantId = env('PAYAZA_TENANT_ID');
        $this->baseUrl = env('PAYAZA_BASE_URL');
    }

    /**
     * Initiate a payout
     *
     * @param array $data
     * @return array
     */
  public function initiatePayout(array $payload)
    {
        // POST request with correct headers
        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
            'X-TenantID' => $this->tenantId,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl, $payload);

        // check if request failed
        if ($response->failed()) {
            return [
                'status' => 'error',
                'http_code' => $response->status(),
                'body' => $response->body()
            ];
        }

        return $response->json();
    }
}