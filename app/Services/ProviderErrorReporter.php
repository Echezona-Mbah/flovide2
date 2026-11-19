<?php

namespace App\Services;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class ProviderErrorReporter
{
    public function report(string $provider, string $operation, string $error, array $context = []): void
    {
        $details = [
            'provider' => $provider,
            'operation' => $operation,
            'error' => $error,
        ] + $context;

        Log::critical('[Payment Provider] Operation failed', $details);

        if (Schema::hasTable('admin_notifications')) {
            try {
                AdminNotification::create([
                    'type' => 'provider_error',
                    'title' => "{$provider}: {$operation} failed",
                    'body' => $error,
                    'data' => $details,
                ]);
            } catch (\Throwable $exception) {
                Log::error('[Payment Provider] Failed to save provider error for the admin dashboard.', [
                    'provider' => $provider,
                    'operation' => $operation,
                    'error' => $exception->getMessage(),
                ]);
            }
        } else {
            Log::warning('[Payment Provider] Admin notification storage is unavailable; provider error was logged only.', [
                'provider' => $provider,
                'operation' => $operation,
                'error' => $error,
            ]);
        }

        $recipients = collect(explode(',', (string) config('mail.admin_email')))
            ->map(fn ($email) => trim($email))
            ->filter()
            ->values()
            ->all();

        if ($recipients === []) {
            Log::warning('[Payment Provider] ADMIN_EMAIL is not configured; provider error was logged only.', [
                'provider' => $provider,
                'operation' => $operation,
                'error' => $error,
            ]);

            return;
        }

        try {
            $body = "A payment provider operation failed.\n\n"
                . json_encode($details, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

            Mail::raw($body, function ($message) use ($recipients, $provider, $operation) {
                $message->to($recipients)
                    ->subject("Payment provider error: {$provider} {$operation}");
            });
        } catch (\Throwable $exception) {
            Log::error('[Payment Provider] Failed to email provider error alert.', [
                'provider' => $provider,
                'operation' => $operation,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
