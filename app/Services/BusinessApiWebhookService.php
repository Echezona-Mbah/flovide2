<?php

namespace App\Services;

use App\Models\BusinessWebhookLog;
use App\Models\TransactionHistory;
use App\Models\WebhookSetting;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BusinessApiWebhookService
{
    public function dispatch(
        TransactionHistory $transaction,
        string $type,
        string $mode,
        ?string $eventTypeOverride = null
    ): ?BusinessWebhookLog
    {
        $type = strtoupper($type);
        $mode = strtolower($mode);

        if (! in_array($type, ['COLLECTION', 'PAYOUT'], true) || ! in_array($mode, ['live', 'test'], true)) {
            throw new \InvalidArgumentException('Unsupported webhook type or mode.');
        }

        $setting = WebhookSetting::where('user_id', $transaction->user_id)->first();
        if (! $setting) {
            return null;
        }

        $urlColumn = "{$mode}_" . strtolower($type) . "_url";
        $url = $setting->{$urlColumn};
        if (blank($url)) {
            return null;
        }

        $eventId = (string) Str::uuid();
        $status = match (strtolower((string) $transaction->status)) {
            'success', 'successful' => 'SUCCESSFUL',
            'failed', 'declined' => 'FAILED',
            default => strtoupper((string) $transaction->status),
        };
        $eventType = $eventTypeOverride ?? match ($status) {
            'SUCCESSFUL' => 'transaction.success',
            'FAILED' => 'transaction.failed',
            default => strtolower($type) . '.created',
        };
        $payload = [
            'message' => $eventTypeOverride !== null ? match ($status) {
                'SUCCESS', 'SUCCESSFUL' => 'Transaction Completed',
                'FAILED', 'DECLINED' => 'Transaction Failed',
                'PENDING' => 'Transaction Pending',
                default => 'Transaction status updated',
            } : match ($status) {
                'SUCCESS', 'SUCCESSFUL' => 'Transaction Completed',
                'FAILED', 'DECLINED' => 'Transaction Failed',
                default => 'Transaction initiated',
            },
            'event_id' => $eventId,
            'event' => $eventType,
            'type' => strtolower($type),
            'transaction_id' => (string) $transaction->id,
            'transaction_reference' => $transaction->reference,
            'transaction_status' => $status,
            'transaction_fee' => (float) ($transaction->fees ?? 0),
            'transaction_amount_without_fee' => max(
                0,
                (float) $transaction->amount - (float) ($transaction->fees ?? 0)
            ),
            'transaction_amount' => (float) $transaction->amount,
            'transaction_currency' => strtoupper((string) $transaction->currency),
            'customer_id' => null,
        ];

        if ($type === 'COLLECTION') {
            $payerEmail = $transaction->interac_email;
            if (blank($payerEmail) && filter_var($transaction->sender, FILTER_VALIDATE_EMAIL)) {
                $payerEmail = $transaction->sender;
            }

            $payload['payer_email'] = $payerEmail;
        } else {
            $payload['recipient'] = [
                'account_number' => $transaction->recipient_account_number,
                'account_name' => $transaction->recipient_account_name,
                'bank_name' => $transaction->recipient_bank_name,
                'amount' => (float) ($transaction->recipient_amount ?? $transaction->amount),
            ];
        }

        $log = BusinessWebhookLog::create([
            'business_id' => $transaction->user_id,
            'business_transaction_id' => $transaction->id,
            'type' => $type,
            'mode' => $mode,
            'webhook_url' => $url,
            'payload' => $payload,
            'status' => $status,
            'delivery_status' => 'PENDING',
        ]);

        $secretColumn = "{$mode}_secret_key";

        return $this->deliver($log, (string) $setting->{$secretColumn});
    }

    public function replay(BusinessWebhookLog $log): BusinessWebhookLog
    {
        $setting = WebhookSetting::where('user_id', $log->business_id)->first();
        $secretColumn = strtolower($log->mode) . '_secret_key';

        if (! $setting || blank($setting->{$secretColumn} ?? null)) {
            throw new \RuntimeException('Webhook signing secret is not configured for this environment.');
        }

        return $this->deliver($log, (string) $setting->{$secretColumn});
    }

    private function deliver(BusinessWebhookLog $log, string $secret): BusinessWebhookLog
    {
        $payload = $log->payload;
        $eventId = (string) ($payload['event_id'] ?? $log->id);
        $eventType = (string) ($payload['event'] ?? strtolower($log->type) . '.created');
        $body = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $signature = hash_hmac('sha256', $body, $secret);
        $headers = [
            'Content-Type' => 'application/json',
            'User-Agent' => 'Flovide',
            'X-Flovide-Event-Id' => $eventId,
            'X-Flovide-Event' => $eventType,
            'X-Flovide-Signature' => $signature,
        ];
        $attempt = $log->histories()->count() + 1;

        try {
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->withBody($body, 'application/json')
                ->send('POST', $log->webhook_url);

            $responseBody = $response->json();
            if (! is_array($responseBody)) {
                $responseBody = ['body' => $response->body()];
            }

            $log->forceFill([
                'response' => $responseBody,
                'delivery_status' => $response->successful() ? 'SUCCESSFUL' : 'FAILED',
            ])->save();

            $log->histories()->create([
                'metadata' => ['url' => $log->webhook_url, 'attempt' => $attempt],
                'headers' => $headers,
                'http_status' => $response->status(),
                'response' => $responseBody,
            ]);
        } catch (ConnectionException $exception) {
            $responseBody = ['message' => $exception->getMessage()];

            $log->forceFill([
                'response' => $responseBody,
                'delivery_status' => 'FAILED',
            ])->save();

            $log->histories()->create([
                'metadata' => ['url' => $log->webhook_url, 'attempt' => $attempt],
                'headers' => $headers,
                'response' => $responseBody,
            ]);

            Log::warning('[Business API Webhook] Delivery connection failed', [
                'webhook_log_id' => $log->id,
                'business_id' => $log->business_id,
                'type' => $log->type,
                'attempt' => $attempt,
            ]);
        }

        return $log->fresh(['histories']);
    }
}
