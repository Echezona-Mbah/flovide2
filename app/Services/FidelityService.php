<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FidelityService
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;

    public function __construct()
    {
        $this->baseUrl      = rtrim(config('services.fidelity.base_url'), '/');
        $this->clientId     = config('services.fidelity.client_id');
        $this->clientSecret = config('services.fidelity.client_secret');
    }

    protected function request()
    {
        return Http::withHeaders([
            'client-secret' => $this->clientSecret,
            'Content-Type'  => 'application/json',
        ])->baseUrl($this->baseUrl);
    }

    /**
     * Generate a permanent virtual account for a customer.
     */
    public function generateStaticVirtualAccount(array $kyc): array
    {
        $payload = [
            'clientId'       => $this->clientId,
            'kycInformation' => [
                'firstName'   => $kyc['first_name'],
                'lastName'    => $kyc['last_name'],
                'email'       => $kyc['email'],
                'bvn'         => $kyc['bvn'] ?? null,
                'nin'         => $kyc['nin'] ?? null,
                'phoneNumber' => $kyc['phone_number'] ?? null,
                'dateOfBirth' => $kyc['date_of_birth'] ?? null,
            ],
        ];

        $response = $this->request()->post('/virtual-account/generate-static-virtual-account', $payload);

        Log::info('[Fidelity] Generate Static Virtual Account', [
            'payload' => $payload,
            'status'  => $response->status(),
            'body'    => $response->json(),
        ]);

        return [
            'success' => $response->successful() && ($response->json('status') ?? false),
            'status'  => $response->status(),
            'data'    => $response->json(),
        ];
    }

    /**
     * Generate a single-use virtual account tied to a specific transaction amount.
     */
    public function generateDynamicVirtualAccount(float $amount, int $durationMinutes = 30): array
    {
        $payload = [
            'clientId'          => $this->clientId,
            'transactionAmount' => (string) $amount,
            'theDuration'       => $durationMinutes,
        ];

        $response = $this->request()->post('/virtual-account/generate-dynamic-virtual-account', $payload);

        Log::info('[Fidelity] Generate Dynamic Virtual Account', [
            'payload' => $payload,
            'status'  => $response->status(),
            'body'    => $response->json(),
        ]);

        return [
            'success' => $response->successful() && ($response->json('status') ?? false),
            'status'  => $response->status(),
            'data'    => $response->json(),
        ];
    }
}