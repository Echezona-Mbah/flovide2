<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlaaizService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.blaaiz.base_url'), '/');
    }

    public function getAccessToken(): ?string
    {
        return Cache::remember('blaaiz_access_token', now()->addMinutes(14), function () {
            $response = Http::asForm()->post($this->baseUrl . '/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => config('services.blaaiz.client_id'),
                'client_secret' => config('services.blaaiz.client_secret'),
                'scope' => config('services.blaaiz.scopes'),
            ]);

            Log::info('Blaaiz token response', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            if (! $response->successful()) {
                return null;
            }

            return $response->json('access_token');
        });
    }

    protected function request()
    {
        $token = $this->getAccessToken();

        if (! $token) {
            throw new \Exception('Unable to authenticate with Blaaiz');
        }

        return Http::withToken($token)
            ->acceptJson()
            ->baseUrl($this->baseUrl);
    }

    public function wallets(): array
    {
        $response = $this->request()->get('/api/external/wallet');

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }

    public function getTransaction(string $transactionId): array
    {
        $response = $this->request()->get("/api/external/transaction/{$transactionId}");

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }

    public function payout(array $payload): array
    {
        $response = $this->request()->post('/api/external/payout', $payload);

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }


    public function acceptInteracMoneyRequest(array $payload): array
    {
        $response = $this->request()
            ->acceptJson()
            ->post('/api/external/collection/accept-interac-money-request', $payload);

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }


    public function simulateInteracWebhook(string $email, float $amount): array
    {
        $response = $this->request()->post('/api/external/mock/simulate-webhook/interac', [
            'interac_email' => $email,
            'amount'        => $amount,
        ]);

        return [
            'success' => $response->successful(),
            'status'  => $response->status(),
            'data'    => $response->json(),
        ];
    }


    public function initiateInteracMoneyRequest(array $payload): array
    {
        $response = $this->request()
            ->acceptJson()
            ->post('/api/external/collection/interac-money-request', $payload);

        Log::info('[Blaaiz] Initiate Interac Money Request', [
            'payload' => $payload,
            'status'  => $response->status(),
            'body'    => $response->json(),
        ]);

        return [
            'success' => $response->successful(),
            'status'  => $response->status(),
            'data'    => $response->json(),
        ];
    }

    public function createCustomer(array $payload): array
    {
        $response = $this->request()
            ->acceptJson()
            ->post('/api/external/customer', $payload);

        Log::info('[Blaaiz] Create Customer', [
            'payload' => $payload,
            'status'  => $response->status(),
            'body'    => $response->json(),
        ]);

        return [
            'success' => $response->successful(),
            'status'  => $response->status(),
            'data'    => $response->json(),
        ];
    }

    public function listCustomers(array $filters = []): array
    {
        $response = $this->request()
            ->acceptJson()
            ->get('/api/external/customer', $filters);

        Log::info('[Blaaiz] List Customers', [
            'filters' => $filters,
            'status'  => $response->status(),
            'body'    => $response->json(),
        ]);

        return [
            'success' => $response->successful(),
            'status'  => $response->status(),
            'data'    => $response->json(),
        ];
    }

    public function getCustomer(string $customerId): array
    {
        $response = $this->request()
            ->acceptJson()
            ->get("/api/external/customer/{$customerId}");

        Log::info('[Blaaiz] Get Customer', [
            'customer_id' => $customerId,
            'status'      => $response->status(),
            'body'        => $response->json(),
        ]);

        return [
            'success' => $response->successful(),
            'status'  => $response->status(),
            'data'    => $response->json(),
        ];
    }

    public function getWebhookUrls(): array
{
    $response = $this->request()->get('/api/external/webhook');

    return [
        'success' => $response->successful(),
        'status'  => $response->status(),
        'data'    => $response->json(),
    ];
}

public function registerWebhookUrls(string $collectionUrl, string $payoutUrl): array
{
    $response = $this->request()->post('/api/external/webhook', [
        'collection_url' => $collectionUrl,
        'payout_url'     => $payoutUrl,
    ]);

    Log::info('[Blaaiz] Register Webhook URLs', [
        'collection_url' => $collectionUrl,
        'payout_url'     => $payoutUrl,
        'status'         => $response->status(),
        'body'           => $response->json(),
    ]);

    return [
        'success' => $response->successful(),
        'status'  => $response->status(),
        'data'    => $response->json(),
    ];
}


}