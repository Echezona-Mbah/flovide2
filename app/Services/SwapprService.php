<?php

namespace App\Services;

use App\Models\Balance;
use App\Models\Bank;
use App\Models\Beneficia;
use App\Models\TransactionHistory;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use InvalidArgumentException;
use RuntimeException;

class SwapprService
{
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.swappr.base_url'), '/');
        $this->timeout = (int) config('services.swappr.timeout', 30);
    }

    public function createPayout(array $payload, string $idempotencyKey, string $environment = 'live'): array
    {
        if (!preg_match('/^[A-Za-z0-9._~-]{1,255}$/', $idempotencyKey)) {
            throw new InvalidArgumentException('Swappr idempotency key contains unsupported characters.');
        }

        $apiKey = $this->apiKeyFor($environment);

        $lastResponse = null;

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            try {
                $lastResponse = Http::timeout($this->timeout)
                    ->withToken($apiKey)
                    ->withHeaders(['Idempotency-Key' => $idempotencyKey])
                    ->acceptJson()
                    ->asJson()
                    ->post($this->baseUrl . '/payouts', $payload);
            } catch (ConnectionException $exception) {
                if ($attempt === 3) {
                    return [
                        'success' => false,
                        'uncertain' => true,
                        'status' => 503,
                        'code' => 'SWAPPR_CONNECTION_ERROR',
                        'message' => 'Swappr did not confirm whether the payout was received.',
                        'data' => ['reason' => $exception->getMessage()],
                    ];
                }

                sleep($attempt);
                continue;
            }

            if (!$lastResponse->serverError() || $attempt === 3) {
                return $this->formatResponse($lastResponse);
            }

            sleep($attempt);
        }

        return $this->formatResponse($lastResponse);
    }

    public function getBanks(string $currency, string $environment = 'live'): array
    {
        $apiKey = $this->apiKeyFor($environment);

        $response = Http::timeout($this->timeout)
            ->withToken($apiKey)
            ->acceptJson()
            ->get($this->baseUrl . '/banks', ['currency' => strtoupper($currency)]);

        return $this->formatResponse($response);
    }

    public static function senderInformationValidationRules(): array
    {
        return [
            'sender' => 'nullable|array',
            'sender.first_name' => 'required_with:sender|string|max:100',
            'sender.last_name' => 'required_with:sender|string|max:100',
            'sender.id_type' => [
                'required_with:sender',
                'string',
                Rule::in(['Passport', "Driver's License", 'National-ID', 'Residence Card']),
            ],
            'sender.id_number' => 'required_with:sender|string|max:100',
            'sender.id_expiry' => 'required_with:sender|date_format:dmY',
            'sender.date_of_birth' => 'required_with:sender|date_format:dmY',
            'sender.country' => ['required_with:sender', 'regex:/^[A-Z]{2}$/'],
        ];
    }

    public static function senderInformationFromConfig(): ?array
    {
        $sender = [
            'first_name' => config('services.swappr.sender.first_name'),
            'last_name' => config('services.swappr.sender.last_name'),
            'id_type' => config('services.swappr.sender.id_type'),
            'id_number' => config('services.swappr.sender.id_number'),
            'id_expiry' => config('services.swappr.sender.id_expiry'),
            'date_of_birth' => config('services.swappr.sender.date_of_birth'),
            'country' => config('services.swappr.sender.country'),
        ];

        if (in_array(null, $sender, true) || in_array('', $sender, true)) {
            return null;
        }

        return Validator::make(
            ['sender' => $sender],
            self::senderInformationValidationRules()
        )->passes() ? $sender : null;
    }

    public function nameEnquiry(
        string $accountNumber,
        string $bankCode,
        string $bankName,
        string $idempotencyKey,
        string $environment = 'live'
    ): array {
        if (!preg_match('/^[A-Za-z0-9._~-]{1,255}$/', $idempotencyKey)) {
            throw new InvalidArgumentException('Swappr idempotency key contains unsupported characters.');
        }

        $apiKey = $this->apiKeyFor($environment);
        $payload = [
            'account_number' => $accountNumber,
            'bank_code' => $bankCode,
            'currency' => 'NGN',
        ];

        if ($bankName !== '') {
            $payload['bank_name'] = $bankName;
        }

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            try {
                $response = Http::timeout($this->timeout)
                    ->withToken($apiKey)
                    ->withHeaders(['Idempotency-Key' => $idempotencyKey])
                    ->acceptJson()
                    ->asJson()
                    ->post($this->baseUrl . '/name-enquiry', $payload);
            } catch (ConnectionException $exception) {
                if ($attempt < 3) {
                    sleep($attempt);
                    continue;
                }

                return [
                    'success' => false,
                    'uncertain' => true,
                    'status' => 503,
                    'code' => 'SWAPPR_CONNECTION_ERROR',
                    'message' => 'Swappr did not confirm whether the name enquiry was received.',
                    'data' => ['reason' => $exception->getMessage()],
                ];
            }

            if (!$response->serverError() || $attempt === 3) {
                return $this->formatResponse($response);
            }

            sleep($attempt);
        }

        return $this->beneficiaryFailure('Swappr name enquiry failed.', 'SWAPPR_NAME_ENQUIRY_FAILED', 502);
    }

    public function syncBeneficiary(Beneficia $beneficiary): array
    {
        $currency = strtoupper((string) $beneficiary->currency);
        $label = $beneficiary->alias
            ?: $beneficiary->beneficiary_name
            ?: $beneficiary->account_name
            ?: trim((string) $beneficiary->first_names . ' ' . (string) $beneficiary->last_name);
        if ($label === '') {
            return $this->beneficiaryFailure('Beneficiary name is required for Swappr.', 'SWAPPR_BENEFICIARY_NAME_REQUIRED');
        }

        if ($currency === 'CAD') {
            $email = $beneficiary->email;
            $firstName = $beneficiary->interac_first_name ?: $beneficiary->first_names;
            $lastName = $beneficiary->interac_last_name ?: $beneficiary->last_name;

            if (!$email || !$firstName || !$lastName) {
                return $this->beneficiaryFailure(
                    'CAD Swappr Interac beneficiaries require an email, first name, and last name.',
                    'SWAPPR_BENEFICIARY_FIELDS_REQUIRED'
                );
            }

            $payload = [
                'currency' => 'CAD',
                'name' => $label,
                'interac_email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'external_reference' => (string) $beneficiary->id,
            ];
        } elseif ($currency === 'NGN') {
            $bankCode = (string) ($beneficiary->flovide_bank_code ?: $beneficiary->bank_code);
            $bank = app(BankCodeResolver::class)->forProvider(
                'swappr',
                'NGN',
                $bankCode,
                $beneficiary->provider
            );
            $bank ??= Bank::query()
                ->where('provider', 'swappr')
                ->where('currency', 'NGN')
                ->where(function ($query) use ($bankCode) {
                    $query->where('bank_code', $bankCode)
                        ->orWhere('bank_nibss_code', $bankCode);
                })
                ->first();

            if (!$bank) {
                return $this->beneficiaryFailure(
                    'The selected bank is not available in the Swappr bank list. Sync Swappr banks and select the bank again.',
                    'SWAPPR_BENEFICIARY_BANK_NOT_FOUND'
                );
            }

            if (blank($beneficiary->account_number)) {
                return $this->beneficiaryFailure('NGN Swappr beneficiaries require an account number.', 'SWAPPR_BENEFICIARY_FIELDS_REQUIRED');
            }

            $payload = [
                'currency' => 'NGN',
                'name' => $label,
                'account_number' => (string) $beneficiary->account_number,
                'bank_code' => (string) ($bank->bank_code ?: $bank->bank_nibss_code),
                'bank_name' => $bank->name,
                'external_reference' => (string) $beneficiary->id,
            ];

            if (filled($beneficiary->phone)) {
                $payload['phone'] = $beneficiary->phone;
            }
        } else {
            return $this->beneficiaryFailure(
                "Swappr beneficiary synchronization is not configured for {$currency}.",
                'SWAPPR_CURRENCY_UNSUPPORTED'
            );
        }

        $response = $this->upsertBeneficiary(
            $payload,
            'ben-' . Str::uuid(),
            (string) ($beneficiary->mode ?: 'live')
        );
//dd($response);

        if (!$response['success']) {
            return $response;
        }

        $beneficiaryId = $this->extractBeneficiaryId($response);

        if ($beneficiaryId === null && $currency === 'CAD') {
            $lookup = $this->lookupCadBeneficiaryId(
                (string) $beneficiary->email,
                (string) $beneficiary->id,
                (string) ($beneficiary->mode ?: 'live')
            );

            if (!$lookup['success']) {
                return $lookup;
            }

            $beneficiaryId = $lookup['beneficiary_id'];
        }


        if ($beneficiaryId === null) {
            $responseData = $response['data'] ?? null;
            Log::warning('[Swappr] Beneficiary response did not contain a recognized ID field.', [
                'status' => $response['status'] ?? null,
                'response_keys' => is_array($responseData) ? array_keys($responseData) : [],
                'nested_data_keys' => is_array(data_get($responseData, 'data'))
                    ? array_keys(data_get($responseData, 'data'))
                    : [],
            ]);

            return $this->beneficiaryFailure(
                'Swappr accepted the beneficiary but did not return its ID.',
                'SWAPPR_BENEFICIARY_ID_MISSING'
            );
        }

        $beneficiary->swappr_beneficiary_id = $beneficiaryId;
        $beneficiary->save();


        return [
            'success' => true,
            'message' => 'Beneficiary saved with Swappr.',
            'beneficiary_id' => $beneficiaryId,
            'data' => $response['data'],
        ];
    }

    private function extractBeneficiaryId(array $response): ?string
    {
        foreach ([
            'data.id',
            'data.beneficiary_id',
            'data.beneficiaryId',
            'data.data.id',
            'data.data.beneficiary_id',
            'data.data.beneficiaryId',
            'data.beneficiary.id',
            'data.data.beneficiary.id',
        ] as $path) {
            $value = data_get($response, $path);
            if ((is_string($value) || is_int($value)) && (string) $value !== '') {
                return (string) $value;
            }
        }

        return null;
    }

    private function lookupCadBeneficiaryId(string $email, string $externalReference, string $environment): array
    {
        $apiKey = $this->apiKeyFor($environment);
        $parameters = [
            'currency' => 'CAD',
            'limit' => 100,
        ];
        if ($externalReference !== '') {
            $parameters['external_reference'] = $externalReference;
        }

        do {
            try {
                $response = Http::timeout($this->timeout)
                    ->withToken($apiKey)
                    ->acceptJson()
                    ->get($this->baseUrl . '/beneficiaries', $parameters);
            } catch (ConnectionException $exception) {
                Log::warning('[Swappr] CAD beneficiary ID lookup failed to connect.', [
                    'environment' => $environment,
                    'exception' => $exception->getMessage(),
                ]);

                return $this->beneficiaryFailure(
                    'Swappr saved the CAD beneficiary but its ID lookup failed. The payout was not submitted.',
                    'SWAPPR_BENEFICIARY_LOOKUP_FAILED',
                    503
                );
            }

            if (!$response->successful()) {
                $formattedResponse = $this->formatResponse($response);
                Log::warning('[Swappr] CAD beneficiary ID lookup returned an error.', [
                    'environment' => $environment,
                    'status' => $formattedResponse['status'],
                    'code' => $formattedResponse['code'],
                ]);

                return $this->beneficiaryFailure(
                    'Swappr saved the CAD beneficiary but its ID lookup failed. The payout was not submitted.',
                    'SWAPPR_BENEFICIARY_LOOKUP_FAILED',
                    (int) $formattedResponse['status']
                );
            }

            $body = $response->json();
            $beneficiaries = data_get($body, 'data', []);

            if (!is_array($beneficiaries)) {
                $beneficiaries = [];
            } elseif (isset($beneficiaries['data']) && is_array($beneficiaries['data'])) {
                $body = $beneficiaries;
                $beneficiaries = $beneficiaries['data'];
            }

            foreach ($beneficiaries as $candidate) {
                if (!is_array($candidate)) {
                    continue;
                }

                $candidateEmail = data_get($candidate, 'interac_email');
                $candidateId = data_get($candidate, 'id');

                if (
                    is_string($candidateEmail)
                    && strtolower(trim($candidateEmail)) === strtolower(trim($email))
                    && (is_string($candidateId) || is_int($candidateId))
                    && (string) $candidateId !== ''
                ) {
                    return [
                        'success' => true,
                        'beneficiary_id' => (string) $candidateId,
                    ];
                }
            }

            $hasMore = data_get($body, 'has_more') === true;
            $lastBeneficiary = $beneficiaries === [] ? null : end($beneficiaries);
            $cursor = is_array($lastBeneficiary) ? data_get($lastBeneficiary, 'id') : null;

            if (!$hasMore || !is_string($cursor) || $cursor === '') {
                break;
            }

            $parameters['starting_after'] = $cursor;
        } while (true);

        return [
            'success' => true,
            'beneficiary_id' => null,
        ];
    }

public function upsertBeneficiary(array $payload, string $idempotencyKey, string $environment = 'live'): array
{
    $apiKey = $this->apiKeyFor($environment);
    $url    = $this->baseUrl . '/beneficiaries';

    Log::info('[Swappr Beneficiary] Sending upsert', [
        'url'             => $url,
        'environment'     => $environment,
        'key_prefix'      => substr($apiKey, 0, 8),
        'idempotency_key' => $idempotencyKey,
        'payload'         => $payload,
    ]);

    for ($attempt = 1; $attempt <= 3; $attempt++) {
        try {
            $response = Http::timeout($this->timeout)
                ->withoutRedirecting()
                ->withToken($apiKey)
                ->withHeaders(['Idempotency-Key' => $idempotencyKey])
                ->acceptJson()
                ->asJson()
                ->post($url, $payload);

            Log::info('[Swappr Beneficiary] Raw response', [
                'attempt'      => $attempt,
                'status'       => $response->status(),
                'content_type' => $response->header('Content-Type'),
                'location'     => $response->header('Location'),
                'body'         => substr($response->body(), 0, 1000),
            ]);
        } catch (ConnectionException $exception) {
            Log::error('[Swappr Beneficiary] Connection exception', [
                'attempt' => $attempt,
                'error'   => $exception->getMessage(),
            ]);

            if ($attempt < 3) {
                sleep($attempt);
                continue;
            }

            return $this->beneficiaryFailure(
                'Swappr did not confirm whether the beneficiary was saved.',
                'SWAPPR_BENEFICIARY_CONFIRMATION_UNCERTAIN',
                503
            );
        }

        if ($response->serverError() && $attempt < 3) {
            sleep($attempt);
            continue;
        }

        $formatted = $this->formatResponse($response);

        // A 2xx that isn't a JSON beneficiary object means we did not reach the Swappr API
        if ($formatted['success'] && !is_array($formatted['data'])) {
            return $this->beneficiaryFailure(
                'Swappr returned a non-JSON response. Check SWAPPR_BASE_URL (expected https://api.swappr.me/api/v1).',
                'SWAPPR_UNEXPECTED_RESPONSE',
                502
            );
        }

        return $formatted;
    }

    return $this->beneficiaryFailure('Swappr beneficiary request failed.', 'SWAPPR_BENEFICIARY_FAILED', 502);
}

    public function createCadBeneficiaryForPayout(Beneficia $beneficiary, string $idempotencyKey): array
    {
        if (strtoupper((string) $beneficiary->currency) !== 'CAD') {
            return $this->beneficiaryFailure(
                'Only CAD beneficiaries can be prepared for a CAD payout.',
                'SWAPPR_BENEFICIARY_CURRENCY_INVALID'
            );
        }

        $name = $beneficiary->alias
            ?: $beneficiary->beneficiary_name
            ?: $beneficiary->account_name
            ?: trim((string) $beneficiary->first_names . ' ' . (string) $beneficiary->last_name);
        $firstName = $beneficiary->interac_first_name ?: $beneficiary->first_names;
        $lastName = $beneficiary->interac_last_name ?: $beneficiary->last_name;

        if (blank($name) || blank($beneficiary->email) || blank($firstName) || blank($lastName)) {
            return $this->beneficiaryFailure(
                'CAD Swappr Interac beneficiaries require a display name, email, first name, and last name.',
                'SWAPPR_BENEFICIARY_FIELDS_REQUIRED'
            );
        }

        $response = $this->upsertBeneficiary([
            'currency' => 'CAD',
            'name' => $name,
            'interac_email' => $beneficiary->email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'external_reference' => (string) $beneficiary->id,
        ], $idempotencyKey, (string) ($beneficiary->mode ?: 'live'));

        if (!$response['success']) {
            return $response;
        }

        $beneficiaryId = $this->extractBeneficiaryId($response);

        if ($beneficiaryId === null) {
            $lookup = $this->lookupCadBeneficiaryId(
                (string) $beneficiary->email,
                (string) $beneficiary->id,
                (string) ($beneficiary->mode ?: 'live')
            );

            if (!$lookup['success']) {
                return $lookup;
            }

            $beneficiaryId = $lookup['beneficiary_id'];
        }

        if ($beneficiaryId === null) {
            $responseData = $response['data'] ?? null;
            Log::warning('[Swappr] Payout beneficiary response did not contain a recognized ID field.', [
                'status' => $response['status'] ?? null,
                'response_keys' => is_array($responseData) ? array_keys($responseData) : [],
                'nested_data_keys' => is_array(data_get($responseData, 'data'))
                    ? array_keys(data_get($responseData, 'data'))
                    : [],
            ]);

            return $this->beneficiaryFailure(
                'Swappr accepted the beneficiary but did not return its ID; the payout was not submitted.',
                'SWAPPR_BENEFICIARY_ID_MISSING'
            );
        }

        return [
            'success' => true,
            'message' => 'CAD beneficiary is ready for payout.',
            'beneficiary_id' => $beneficiaryId,
            'data' => $response['data'],
        ];
    }

    public function submitPayout(
        TransactionHistory $transaction,
        array $payload,
        ?Beneficia $beneficiary = null
    ): array
    {
        if (
            strtoupper((string) ($payload['currency'] ?? '')) === 'CAD'
            && blank($payload['beneficiary_id'] ?? null)
            && $beneficiary !== null
        ) {
            $preparedBeneficiary = $this->createCadBeneficiaryForPayout(
                $beneficiary,
                'payout-beneficiary-' . $transaction->id
            );

            if (!$preparedBeneficiary['success']) {
                $message = ($preparedBeneficiary['message'] ?? 'Swappr could not prepare the CAD beneficiary.')
                    . ' The payout was not submitted.';
                $this->refundFailedPayout($transaction);
                $transaction->update([
                    'status' => 'failed',
                    'payment_provider' => 'swappr',
                    'failure_reason' => $message,
                ]);

                return [
                    'success' => false,
                    'uncertain' => false,
                    'status' => 'failed',
                    'code' => $preparedBeneficiary['code'] ?? 'SWAPPR_BENEFICIARY_FAILED',
                    'message' => $message,
                    'data' => $preparedBeneficiary['data'] ?? null,
                ];
            }

            $payload['beneficiary_id'] = $preparedBeneficiary['beneficiary_id'];
            unset($payload['recipient']);
        }

        if (
            strtoupper((string) ($payload['currency'] ?? '')) === 'NGN'
            && (
                !is_array($payload['sender'] ?? null)
                || !Validator::make(
                    ['sender' => $payload['sender']],
                    self::senderInformationValidationRules()
                )->passes()
            )
        ) {
            $message = 'Configure complete, valid Swappr sender details before submitting this NGN payout.';
            $this->refundFailedPayout($transaction);
            $transaction->update([
                'status' => 'failed',
                'payment_provider' => 'swappr',
                'failure_reason' => $message,
            ]);

            return [
                'success' => false,
                'uncertain' => false,
                'status' => 'failed',
                'code' => 'SWAPPR_SENDER_INFO_REQUIRED',
                'message' => $message,
                'data' => null,
            ];
        }

        $transaction->update([
            'status' => 'pending',
            'payment_provider' => 'swappr',
        ]);

        $response = $this->createPayout($payload, $transaction->id, (string) ($transaction->mode ?: 'live'));

        if ($response['success']) {
            $payout = is_array($response['data']) ? $response['data'] : [];
            $payoutStatus = strtolower((string) data_get($payout, 'status', ''));
            $localStatus = in_array($payoutStatus, ['paid', 'paid_manual'], true)
                ? 'success'
                : (in_array($payoutStatus, ['failed', 'failed_manual', 'reversed', 'cancelled'], true) ? 'failed' : 'pending');
            $message = data_get($payout, 'failure_message') ?? 'Swappr payout failed and the balance was refunded.';

            $transaction->update([
                'status' => $localStatus,
                'order_id' => data_get($payout, 'id') ?? data_get($payout, 'reference'),
                'recipient_account_name' => data_get($payout, 'recipient_name') ?? $transaction->recipient_account_name,
                'failure_reason' => $localStatus === 'failed' ? $message : null,
            ]);

            if ($localStatus === 'failed') {
                $this->refundFailedPayout($transaction);
            }

            return [
                'success' => $localStatus !== 'failed',
                'uncertain' => false,
                'status' => $localStatus,
                'message' => $localStatus === 'failed' ? $message : 'Swappr payout submitted successfully.',
                'data' => $payout,
            ];
        }

        if ($response['uncertain'] ?? false) {
            return [
                'success' => false,
                'uncertain' => true,
                'status' => 'pending',
                'message' => 'Swappr is confirming the payout submission. Do not submit this transfer again.',
                'data' => null,
            ];
        }

        $message = $response['message'] ?? 'Swappr payout failed.';
        $this->refundFailedPayout($transaction);
        $transaction->update([
            'status' => 'failed',
            'failure_reason' => $message,
        ]);

        return [
            'success' => false,
            'uncertain' => false,
            'status' => 'failed',
            'message' => $message,
            'data' => $response['data'] ?? null,
        ];
    }

    public function refundFailedPayout(TransactionHistory $transaction): void
    {
        if ($transaction->mode === 'test') {
            return;
        }

        $balance = Balance::query()->lockForUpdate()->find($transaction->balance_id);

        if (!$balance) {
            throw new RuntimeException("Cannot refund Swappr payout {$transaction->id}: source balance not found.");
        }

        $balance->amount += (float) $transaction->total_amount;
        $balance->save();

        if ($transaction->fee_balance_id && (float) $transaction->fees > 0) {
            $feeBalance = Balance::query()->lockForUpdate()->find($transaction->fee_balance_id);

            if (!$feeBalance) {
                throw new RuntimeException("Cannot refund Swappr payout {$transaction->id}: fee balance not found.");
            }

            $feeBalance->amount += (float) $transaction->fees;
            $feeBalance->save();
        }
    }

    protected function apiKeyFor(string $environment): string
    {
        if (!in_array($environment, ['test', 'live'], true)) {
            throw new InvalidArgumentException('Swappr environment must be either "test" or "live".');
        }

        if ($this->baseUrl === '') {
            throw new RuntimeException('Swappr is not configured. Set SWAPPR_BASE_URL.');
        }

        $expectedPrefix = "sk_{$environment}_";
        $apiKey = config("services.swappr.{$environment}_api_key");
        $legacyApiKey = config('services.swappr.api_key');

        if (blank($apiKey) && is_string($legacyApiKey) && str_starts_with($legacyApiKey, $expectedPrefix)) {
            $apiKey = $legacyApiKey;
        }

        if (!is_string($apiKey) || !str_starts_with($apiKey, $expectedPrefix)) {
            throw new RuntimeException("Set SWAPPR_" . strtoupper($environment) . "_API_KEY to a Swappr {$environment} API key.");
        }

        return $apiKey;
    }

    protected function formatResponse($response): array
    {
        $data = $response->json();
        $error = data_get($data, 'error');

        return [
            'success' => $response->successful(),
            'uncertain' => $response->serverError(),
            'status' => $response->status(),
            'code' => data_get($error, 'code') ?? data_get($data, 'code'),
            'message' => data_get($error, 'message')
                ?? data_get($data, 'message')
                ?? data_get($data, 'error_description'),
            'data' => $data,
        ];
    }

    protected function beneficiaryFailure(string $message, string $code, int $status = 422): array
    {
        return [
            'success' => false,
            'uncertain' => false,
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'data' => null,
        ];
    }
}
