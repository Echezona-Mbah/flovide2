<?php

namespace App\Services;

use App\Models\Bank;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OhentPayService
{
    protected string $baseUrl;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.ohentpay.base_url'), '/');
        $this->apiKey  = config('services.ohentpay.api_key');
    }

    public function isEnabled(): bool
    {
        return (bool) config('services.ohentpay.enabled') && !empty($this->apiKey);
    }

    /**
     * Whether a given currency should be routed through OhentPay.
     * Controlled entirely via OHENTPAY_CURRENCIES in .env — e.g. set it
     * to "KES" to only use OhentPay for KES while every other currency
     * keeps using your existing providers (Pivot/Payaza/AppMobile).
     */
    public function handlesCurrency(string $currency): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $currency = strtoupper($currency);
        $allowed  = array_map('strtoupper', config('services.ohentpay.currencies', []));

        return in_array($currency, $allowed, true);
    }

    protected function client()
    {
        return Http::withToken($this->apiKey)
            ->baseUrl($this->baseUrl)
            ->acceptJson()
            ->asJson();
    }

    protected function logAndReturn(string $context, $response): array
    {
        $ok = $response->successful();

        Log::info("[OhentPay] {$context}", [
            'status'  => $response->status(),
            'success' => $ok,
            'body'    => $response->json(),
        ]);

        return [
            'success' => $ok,
            'status'  => $response->status(),
            'data'    => $response->json(),
        ];
    }

    /* ================= BALANCES ================= */

    public function getBalances(?string $currency = null): array
    {
        $response = $this->client()->get('/balances', array_filter([
            'currency' => $currency,
        ]));

        return $this->logAndReturn('Get balances', $response);
    }

    public function createBalance(?string $name = null, ?string $currency = null): array
    {
        $response = $this->client()->post('/balances', array_filter([
            'name'     => $name,
            'currency' => $currency,
        ]));

        return $this->logAndReturn('Create balance', $response);
    }

    public function getBalance(string $id): array
    {
        $response = $this->client()->get("/balances/{$id}");

        return $this->logAndReturn('Get single balance', $response);
    }

    public function updateBalanceName(string $id, string $name): array
    {
        $response = $this->client()->patch("/balances/{$id}", [
            'name' => $name,
        ]);

        return $this->logAndReturn('Update balance name', $response);
    }

    /* ================= RECIPIENTS ================= */

    public function getRecipients(): array
    {
        $response = $this->client()->get('/recipients');

        return $this->logAndReturn('Get recipients', $response);
    }

    public function createRecipient(array $payload): array
    {
        // Expected payload keys: country, currency, alias, type,
        // account_name, sort_code, account_number (whatever the
        // /bankfields endpoint requires for that country/currency).
        $response = $this->client()->post('/recipients', $payload);

        return $this->logAndReturn('Create recipient', $response);
    }

    public function getRecipient(string $id): array
    {
        $response = $this->client()->get("/recipients/{$id}");

        return $this->logAndReturn('Get single recipient', $response);
    }

    public function updateRecipientAlias(string $id, string $alias): array
    {
        $response = $this->client()->patch("/recipients/{$id}", [
            'alias' => $alias,
        ]);

        return $this->logAndReturn('Update recipient alias', $response);
    }

    public function deleteRecipient(string $id): array
    {
        $response = $this->client()->delete("/recipients/{$id}");

        return $this->logAndReturn('Delete recipient', $response);
    }

    public function validateRecipient(array $payload): array
    {
        // payload: country, currency, bank_id, account_number
        $response = $this->client()->post('/recipients/validate', $payload);

        return $this->logAndReturn('Validate recipient', $response);
    }

    /* ================= TRANSACTIONS ================= */

    public function getTransactions(array $filters = []): array
    {
        $response = $this->client()->get('/transactions', array_filter($filters));

        return $this->logAndReturn('Get transactions', $response);
    }

    public function createTransaction(array $payload): array
    {
        // Required: transaction_type, amount, balance_id, recipient_id.
        // Optional: to_amount, order_id, reference.
        $response = $this->client()->post('/transactions', $payload);

        return $this->logAndReturn('Create transaction', $response);
    }

    public function getTransaction(string $id): array
    {
        $response = $this->client()->get("/transactions/{$id}");

        return $this->logAndReturn('Get single transaction', $response);
    }

    public function getBankFields(string $country, string $currency): array
    {
        $response = $this->client()->get('/bankfields', [
            'country'  => $country,
            'currency' => $currency,
        ]);

        return $this->logAndReturn('Get bank fields', $response);
    }

    public function getBanks(string $country, ?string $currency = null): array
    {
        $response = $this->client()->get('/banks', array_filter([
            'country'  => $country,
            'currency' => $currency,
        ]));

        return $this->logAndReturn('Get banks', $response);
    }


    /**
 * Translate a standard bank_code (e.g. "000014") into OhentPay's internal
 * bank_id (e.g. "1"). Our public API and forms only ever expose bank_code
 * — this keeps that stable regardless of which provider is live.
 */
public function resolveBankId(string $bankCode, string $country, ?string $currency = null): ?string
{
    $bank = Bank::where('provider', 'ohentpay')
        ->where('country_iso', strtoupper($country))
        ->where('bank_code', $bankCode)
        ->when($currency, fn ($q) => $q->where('currency', strtoupper($currency)))
        ->first();

    return $bank?->provider_bank_id;
}


/**
 * Resolve everything OhentPay needs from just currency + bank_code.
 * Country is derived from the synced bank row itself — no need for the
 * frontend to send it separately.
 */
public function resolveBank(string $bankCode, string $currency): ?Bank
{
    return Bank::where('provider', 'ohentpay')
        ->where('currency', strtoupper($currency))
        ->where('bank_code', $bankCode)
        ->first();
}


}