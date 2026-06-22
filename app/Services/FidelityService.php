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
        $this->baseUrl = rtrim((string) config('services.fidelity.base_url'), '/');
        $this->clientId = (string) config('services.fidelity.client_id');
        $this->clientSecret = (string) config('services.fidelity.client_secret');
    }

    protected function request()
    {
        return Http::withHeaders([
            'client-secret' => $this->clientSecret,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
            ->timeout(60)
            ->baseUrl($this->baseUrl);
    }

    public function generateStaticVirtualAccount(array $kyc): array
    {
        $path = config('services.fidelity.static_va_path');

        $payload = [
            'clientId' => $this->clientId,
            'kycInformation' => [
                'firstName' => $kyc['first_name'],
                'lastName' => $kyc['last_name'],
                'email' => $kyc['email'],
                'bvn' => $kyc['bvn'] ?? null,
                'nin' => $kyc['nin'] ?? null,
                'phoneNumber' => $kyc['phone_number'] ?? null,
                'dateOfBirth' => $kyc['date_of_birth'] ?? null,
            ],
        ];

        $response = $this->request()->post($path, $payload);

        Log::info('[Fidelity] Generate Static Virtual Account', [
            'url' => $this->baseUrl . $path,
            'payload' => $payload,
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        return [
            'success' => $response->successful() && (bool) ($response->json('status') ?? false),
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }

    public function generateDynamicVirtualAccount(float $amount, int $durationMinutes = 30): array
    {
        $path = config('services.fidelity.dynamic_va_path');

        $payload = [
            'clientId' => $this->clientId,
            'transactionAmount' => (string) $amount,
            'theDuration' => $durationMinutes,
        ];

        $response = $this->request()->post($path, $payload);

        Log::info('[Fidelity] Generate Dynamic Virtual Account', [
            'url' => $this->baseUrl . $path,
            'payload' => $payload,
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        return [
            'success' => $response->successful() && (bool) ($response->json('status') ?? false),
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }
}