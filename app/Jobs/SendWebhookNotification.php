<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWebhookNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    /** Seconds to wait before each retry attempt. */
    public array $backoff = [10, 30, 120, 600, 1800];

    public function __construct(
        public string $url,
        public string $secretKey,
        public string $event,
        public array $payload,
    ) {}

    public function handle(): void
    {
        $body = [
            'event'      => $this->event,
            'data'       => $this->payload,
            'created_at' => now()->toIso8601String(),
        ];

        $json = json_encode($body, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', $json, $this->secretKey);

        $response = Http::withBody($json, 'application/json')
            ->withHeaders([
                'X-Flovide-Signature' => $signature,
                'X-Flovide-Event'     => $this->event,
            ])
            ->timeout(10)
            ->post($this->url);

        if (!$response->successful()) {
            Log::warning('[Webhook] Non-2xx response from developer endpoint', [
                'url'    => $this->url,
                'status' => $response->status(),
                'body'   => \Illuminate\Support\Str::limit($response->body(), 500),
                'event'  => $this->event,
                'attempt'=> $this->attempts(),
            ]);

            // Throwing triggers the queue's built-in retry via $tries/$backoff
            throw new \RuntimeException("Webhook delivery failed with status {$response->status()}");
        }

        Log::info('[Webhook] Delivered successfully', [
            'url'   => $this->url,
            'event' => $this->event,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[Webhook] Permanently failed after all retries', [
            'url'   => $this->url,
            'event' => $this->event,
            'error' => $exception->getMessage(),
        ]);
    }
}