<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class InteracService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $apiSecret;
    protected string $serviceAccount;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('interac.base_url'), '/');
        $this->apiKey = (string) config('interac.api_key');
        $this->apiSecret = (string) config('interac.api_secret');
        $this->serviceAccount = (string) config('interac.service_account');
        $this->timeout = (int) config('interac.timeout', 30);
    }

    public function createCustomer(array $payload): array
    {
        return $this->request('post', '/customer', $payload, 201, 'CUSTOMER_CREATED', 'Customer created successfully');
    }

    public function getCustomer(string $customerId): array
    {
        return $this->request('get', "/customer/{$customerId}", null, 200, 'CUSTOMER_RETRIEVED', 'Customer retrieved successfully');
    }

    public function updateCustomer(string $customerId, array $payload): array
    {
        return $this->request('put', "/customer/{$customerId}", $payload, 204, 'CUSTOMER_UPDATED', 'Customer updated successfully');
    }

    public function enableCustomer(string $customerId): array
    {
        return $this->request('patch', "/customer/{$customerId}/enable", null, 204, 'CUSTOMER_ENABLED', 'Customer enabled successfully');
    }

    public function disableCustomer(string $customerId): array
    {
        return $this->request('patch', "/customer/{$customerId}/disable", null, 204, 'CUSTOMER_DISABLED', 'Customer disabled successfully');
    }

    public function createAlias(string $customerId, array $payload): array
    {
        return $this->request('post', "/customer/{$customerId}/alias", $payload, 201, 'ALIAS_CREATED', 'Alias created successfully');
    }

    public function deleteAlias(string $customerId, string $aliasId): array
    {
        return $this->request('delete', "/customer/{$customerId}/alias/{$aliasId}", null, 204, 'ALIAS_DELETED', 'Alias deleted successfully');
    }

    public function getAlias(string $customerId, string $aliasId): array
    {
        return $this->request('get', "/customer/{$customerId}/alias/{$aliasId}", null, 200, 'ALIAS_RETRIEVED', 'Alias retrieved successfully');
    }

    public function listAliases(string $customerId, int $offset = 0, int $maxItems = 0): array
    {
        $query = http_build_query(['offset' => $offset, 'max_items' => $maxItems]);
        return $this->request('get', "/customer/{$customerId}/alias?{$query}", null, 200, 'ALIASES_RETRIEVED', 'Aliases retrieved successfully');
    }

    public function retrievePaymentOptions(array $payload): array
{
    return $this->request('post', '/payment/options', $payload, 200, 'PAYMENT_OPTIONS_RETRIEVED', 'Payment options retrieved');
}

public function initiatePayment(array $payload): array
{
    return $this->request('post', '/payment', $payload, 201, 'PAYMENT_INITIATED', 'Payment initiated successfully');
}

public function submitPayment(string $paymentRefId, array $payload): array
{
    return $this->request('put', "/payment/{$paymentRefId}", $payload, 201, 'PAYMENT_SUBMITTED', 'Payment submitted successfully');
}

public function reverseInitiatedPayment(string $paymentRefId, array $payload): array
{
    return $this->request('post', "/payment/{$paymentRefId}/reverse", $payload, 204, 'PAYMENT_REVERSED', 'Initiated payment reversed');
}

public function cancelPayment(string $paymentRefId, array $payload): array
{
    return $this->request('post', "/payment/{$paymentRefId}/cancel", $payload, 204, 'PAYMENT_CANCELLED', 'Payment cancelled');
}

public function getPayment(string $paymentRefId): array
{
    return $this->request('get', "/payment/{$paymentRefId}", null, 200, 'PAYMENT_RETRIEVED', 'Payment retrieved');
}

public function listPayments(array $query): array
{
    $queryString = http_build_query($query);
    return $this->request('get', "/payment?{$queryString}", null, 200, 'PAYMENTS_RETRIEVED', 'Payments retrieved');
}

public function retrieveIncomingPayment(array $payload): array
{
    return $this->request('post', '/payment/receive', $payload, 200, 'INCOMING_PAYMENT_RETRIEVED', 'Incoming payment retrieved');
}

public function authenticateIncomingPayment(string $paymentRefId, array $payload): array
{
    return $this->request('post', "/payment/receive/{$paymentRefId}/authenticate", $payload, 204, 'INCOMING_PAYMENT_AUTHENTICATED', 'Incoming payment authenticated');
}

public function initiateReceivePayment(string $paymentRefId, array $payload): array
{
    return $this->request('post', "/payment/receive/{$paymentRefId}", $payload, 201, 'RECEIVE_PAYMENT_INITIATED', 'Receive payment initiated');
}

public function submitReceivePayment(string $paymentRefId, array $payload): array
{
    return $this->request('put', "/payment/receive/{$paymentRefId}", $payload, 201, 'RECEIVE_PAYMENT_SUBMITTED', 'Receive payment submitted');
}

public function reverseReceivePayment(string $paymentRefId, array $payload): array
{
    return $this->request('post', "/payment/receive/{$paymentRefId}/reverse", $payload, 204, 'RECEIVE_PAYMENT_REVERSED', 'Receive payment reversed');
}

public function declineReceivePayment(string $paymentRefId, array $payload): array
{
    return $this->request('post', "/payment/receive/{$paymentRefId}/decline", $payload, 204, 'RECEIVE_PAYMENT_DECLINED', 'Receive payment declined');
}



public function createRequestPayment(array $payload): array
{
    return $this->request('post', '/request', $payload, 201, 'REQUEST_PAYMENT_CREATED', 'Request payment created');
}

public function getRequestPayment(string $requestId): array
{
    return $this->request('get', "/request/{$requestId}", null, 200, 'REQUEST_PAYMENT_RETRIEVED', 'Request payment retrieved');
}

public function cancelRequestPayment(string $requestId, array $payload): array
{
    return $this->request('post', "/request/{$requestId}/cancel", $payload, 204, 'REQUEST_PAYMENT_CANCELLED', 'Request payment cancelled');
}

public function retrieveIncomingRequestPayment(array $payload): array
{
    return $this->request('post', '/request/receive', $payload, 200, 'INCOMING_REQUEST_PAYMENT_RETRIEVED', 'Incoming request payment retrieved');
}

public function declineIncomingRequestPayment(string $networkRequestRefId, array $payload): array
{
    return $this->request('post',"/request/receive/{$networkRequestRefId}/decline",$payload,204,'INCOMING_REQUEST_PAYMENT_DECLINED','Incoming request payment declined' );
}

public function updateFraudStatus(array $payload): array
{
    return $this->request('patch','/fraud/status',$payload,204,'FRAUD_STATUS_UPDATED','Fraud status updated');
}



    protected function request(
        string $method,
        string $path,
        ?array $payload,
        int $expectedStatus,
        string $okCode,
        string $okMessage
    ): array {
        try {
            $response = Http::timeout($this->timeout)
                ->withBasicAuth($this->apiKey, $this->apiSecret)
                ->withHeaders($this->headers())
                ->send(strtoupper($method), "{$this->baseUrl}{$path}", [
                    'json' => $payload,
                ]);

            if ($response->status() === $expectedStatus || ($expectedStatus === 204 && $response->successful())) {
                return [
                    'success' => true,
                    'status' => $response->status(),
                    'code' => $okCode,
                    'message' => $okMessage,
                    'headers' => $this->extractHeaders($response->headers()),
                    'data' => $response->status() === 204 ? null : $response->json(),
                ];
            }

            return $this->mapErrorResponse($response->status(), $response->json(), $response->body());
        } catch (ConnectionException $e) {
            return [
                'success' => false,
                'status' => 503,
                'code' => 'SERVICE_UNAVAILABLE',
                'message' => 'Interac service unavailable',
                'errors' => null,
                'data' => ['reason' => $e->getMessage()],
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'status' => 500,
                'code' => 'SERVER_ERROR',
                'message' => 'Unexpected server error',
                'errors' => null,
                'data' => ['reason' => $e->getMessage()],
            ];
        }
    }

    protected function headers(): array
    {
        return [
            'x-pg-service-account' => $this->serviceAccount,
            'x-pg-interaction-id' => (string) Str::uuid(),
            'x-pg-interaction-timestamp' => now()->utc()->format('Y-m-d\TH:i:s.v\Z'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    protected function extractHeaders(array $headers): array
    {
        return [
            'x-pg-interaction-id' => $headers['x-pg-interaction-id'][0] ?? null,
            'x-pg-correspondent-id' => $headers['x-pg-correspondent-id'][0] ?? null,
        ];
    }

    protected function mapErrorResponse(int $status, ?array $json, ?string $rawBody = null): array
    {
        $providerCode = $json['error'][0]['code'] ?? null;
        $additional = $json['error'][0]['additional_information'] ?? null;

        $codeByStatus = [
            400 => 'BAD_REQUEST',
            401 => 'UNAUTHORIZED',
            403 => 'FORBIDDEN',
            404 => 'NOT_FOUND',
            422 => 'VALIDATION_ERROR',
            429 => 'TOO_MANY_REQUESTS',
            500 => 'SERVER_ERROR',
            501 => 'TXN_FAILED',
            503 => 'SERVICE_UNAVAILABLE',
            504 => 'SERVICE_UNAVAILABLE',
        ];

        return [
            'success' => false,
            'status' => $status,
            'code' => $codeByStatus[$status] ?? 'SERVER_ERROR',
            'message' => $providerCode ?? 'Interac request failed',
            'errors' => $json['error'] ?? null,
            'data' => [
                'provider_code' => $providerCode,
                'additional_information' => $additional,
                'raw' => $json ?? $rawBody,
            ],
        ];
    }
}
