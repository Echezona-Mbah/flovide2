<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrchardService
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;

    public function __construct()
    {
        $this->baseUrl      =  env('ORCHARD_BASE_URL');
        $this->clientId     =  env('ORCHARD_CLIENT_ID');
        $this->clientSecret =  env('ORCHARD_CLIENT_SECRET');
    }

    /**
     * Debit / Credit / Inquiry
     */
    public function sendPayment(array $payload): array
    {
        // Force UTC timestamp
        $payload['ts'] = now()->utc()->format('Y-m-d H:i:s');

        $endpoint = 'sendRequest';

        $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES);

        $signature = hash_hmac('sha256', $jsonPayload, $this->clientSecret);

        $authorization = $this->clientId . ':' . $signature;

        try {

            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => $authorization,
            ])
            ->withBody($jsonPayload, 'application/json')
            ->post($this->baseUrl . $endpoint);

            return [
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
                'raw'     => $response->body(),
            ];

        } catch (\Exception $e) {

            Log::error('Orchard API Error', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }


    public function accountInquiry(array $payload): array
{
    // Force UTC timestamp
    $payload['ts'] = now()->utc()->format('Y-m-d H:i:s');

    $endpoint = 'sendRequest';

    $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES);

    $signature = hash_hmac('sha256', $jsonPayload, $this->clientSecret);

    $authorization = $this->clientId . ':' . $signature;

    try {

        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => $authorization,
        ])
        ->withBody($jsonPayload, 'application/json')
        ->post($this->baseUrl . $endpoint);

        return [
            'success' => $response->successful(),
            'status'  => $response->status(),
            'data'    => $response->json(),
        ];

    } catch (\Exception $e) {

        return [
            'success' => false,
            'error'   => $e->getMessage(),
        ];
    }
}




public function checkTransaction(string $exttrid, string $transType = 'TSC'): array
{
    $payload = [
        "exttrid" => $exttrid,
        "trans_type" => $transType,
        "service_id" => env('ORCHARD_SERVICE_ID'),
    ];

    $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES);

    $signature = hash_hmac('sha256', $jsonPayload, $this->clientSecret);
    $authorization = $this->clientId . ':' . $signature;

    try {
        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => $authorization,
        ])
        ->withBody($jsonPayload, 'application/json')
        ->post($this->baseUrl . 'checkTransaction');

        return [
            'success' => $response->successful(),
            'status'  => $response->status(),
            'data'    => $response->json(),
            'raw'     => $response->body(),
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error'   => $e->getMessage(),
        ];
    }
}



}