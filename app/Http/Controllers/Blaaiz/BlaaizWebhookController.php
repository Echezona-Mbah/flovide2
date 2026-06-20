<?php

namespace App\Http\Controllers\Blaaiz;

use App\Http\Controllers\Controller;
use App\Mail\TransactionSentMail;
use App\Models\Balance;
use App\Models\Personal;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BlaaizWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('[BlaaizWebhook] Raw content', [
            'headers'      => $request->headers->all(),
            'content_type' => $request->header('Content-Type'),
            'raw_body'     => $request->getContent(),
            'all'          => $request->all(),
            'json'         => $request->json()->all(),
        ]);
    
        $payload = $request->all();

        Log::info('[BlaaizWebhook] Incoming webhook', $payload);

        // ── Signature verification ────────────────────────────────────────────
        $secret    = config('services.blaaiz.webhook_secret');
        $signature = $request->header('X-Blaaiz-Signature') ?? $request->header('X-Webhook-Signature');

        if ($secret && $signature) {
            $expected = hash_hmac('sha256', $request->getContent(), $secret);
            if (!hash_equals($expected, $signature)) {
                Log::warning('[BlaaizWebhook] Invalid signature', [
                    'expected'  => $expected,
                    'received'  => $signature,
                ]);
                return response()->json(['message' => 'Invalid signature'], 401);
            }
        }

        // ── Route by event type ───────────────────────────────────────────────
        $event = $payload['event'] ?? $payload['type'] ?? null;

        Log::info('[BlaaizWebhook] Event type', ['event' => $event]);

        return match (true) {
            str_contains((string) $event, 'interac')    => $this->handleInterac($payload),
            str_contains((string) $event, 'collection') => $this->handleCollection($payload),
            str_contains((string) $event, 'payout')     => $this->handlePayout($payload),
            str_contains((string) $event, 'kyc')        => $this->handleKyc($payload),
            default => $this->handleUnknown($event, $payload),
        };
    }

    // ── Interac ───────────────────────────────────────────────────────────────

    protected function handleInterac(array $payload): \Illuminate\Http\JsonResponse
    {
        Log::info('[BlaaizWebhook] Handling Interac event', $payload);

        $data          = $payload['data'] ?? $payload;
        $transactionId = $data['transaction_id'] ?? $data['id'] ?? null;
        $reference     = $data['reference'] ?? null;
        $rawStatus     = strtolower($data['status'] ?? '');

        $mapped = match ($rawStatus) {
            'success', 'completed', 'successful' => 'success',
            'failed', 'declined', 'rejected'     => 'failed',
            default                               => 'pending',
        };

        Log::info('[BlaaizWebhook] Interac status mapped', [
            'transaction_id' => $transactionId,
            'reference'      => $reference,
            'raw_status'     => $rawStatus,
            'mapped_status'  => $mapped,
        ]);

        // Find the transaction by order_id or reference
        $tx = TransactionHistory::where('payment_provider', 'interac')
            ->where(function ($q) use ($transactionId, $reference) {
                $q->where('order_id', $transactionId)
                  ->orWhere('reference', $reference)
                  ->orWhere('payment_reference', $reference);
            })
            ->first();

        if (!$tx) {
            Log::warning('[BlaaizWebhook] No matching transaction found', [
                'transaction_id' => $transactionId,
                'reference'      => $reference,
            ]);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($tx->status === $mapped) {
            Log::info('[BlaaizWebhook] Status unchanged, skipping', ['tx_id' => $tx->id]);
            return response()->json(['message' => 'No change'], 200);
        }

        // Update status
        $tx->status = $mapped;
        $tx->save();

        Log::info('[BlaaizWebhook] Transaction status updated', [
            'tx_id'      => $tx->id,
            'new_status' => $mapped,
        ]);

        // Credit balance on success
        if ($mapped === 'success' && $tx->balance_id) {
            $balance = Balance::find($tx->balance_id);
            if ($balance) {
                $balance->amount += $tx->amount;
                $balance->save();

                Log::info('[BlaaizWebhook] Balance credited', [
                    'balance_id'  => $balance->id,
                    'amount'      => $tx->amount,
                    'new_balance' => $balance->amount,
                ]);
            }
        }

        // Notifications
        $this->sendPushNotification($tx);

        if (in_array($mapped, ['success', 'failed'])) {
            $this->sendEmail($tx);
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }

    // ── Collection ────────────────────────────────────────────────────────────

    protected function handleCollection(array $payload): \Illuminate\Http\JsonResponse
    {
        Log::info('[BlaaizWebhook] Handling Collection event', $payload);

        // Reuse same logic as interac — collections also update transaction status
        return $this->handleInterac($payload);
    }

    // ── Payout ────────────────────────────────────────────────────────────────

    protected function handlePayout(array $payload): \Illuminate\Http\JsonResponse
    {
        Log::info('[BlaaizWebhook] Handling Payout event', $payload);

        $data          = $payload['data'] ?? $payload;
        $transactionId = $data['transaction_id'] ?? $data['id'] ?? null;
        $reference     = $data['reference'] ?? null;
        $rawStatus     = strtolower($data['status'] ?? '');

        $mapped = match ($rawStatus) {
            'success', 'completed', 'successful' => 'success',
            'failed', 'declined', 'rejected'     => 'failed',
            default                               => 'pending',
        };

        $tx = TransactionHistory::where(function ($q) use ($transactionId, $reference) {
                $q->where('order_id', $transactionId)
                  ->orWhere('reference', $reference)
                  ->orWhere('payment_reference', $reference);
            })
            ->first();

        if (!$tx) {
            Log::warning('[BlaaizWebhook] Payout transaction not found', [
                'transaction_id' => $transactionId,
                'reference'      => $reference,
            ]);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($tx->status === $mapped) {
            return response()->json(['message' => 'No change'], 200);
        }

        $tx->status = $mapped;
        $tx->save();

        Log::info('[BlaaizWebhook] Payout status updated', [
            'tx_id'      => $tx->id,
            'new_status' => $mapped,
        ]);

        $this->sendPushNotification($tx);

        if (in_array($mapped, ['success', 'failed'])) {
            $this->sendEmail($tx);
        }

        return response()->json(['message' => 'Payout webhook processed'], 200);
    }

    // ── KYC ──────────────────────────────────────────────────────────────────

    protected function handleKyc(array $payload): \Illuminate\Http\JsonResponse
    {
        Log::info('[BlaaizWebhook] Handling KYC event', $payload);

        $data       = $payload['data'] ?? $payload;
        $customerId = $data['customer_id'] ?? $data['id'] ?? null;
        $status     = strtoupper($data['verification_status'] ?? $data['status'] ?? '');

        if (!$customerId) {
            Log::warning('[BlaaizWebhook] KYC event missing customer_id');
            return response()->json(['message' => 'Missing customer_id'], 422);
        }

        // Update user KYC status
        $user = User::where('blaaiz_id', $customerId)->first();

        if ($user) {
            $user->selfie_verification_status = $status;
            $user->save();

            Log::info('[BlaaizWebhook] KYC status updated on User', [
                'user_id'    => $user->id,
                'blaaiz_id'  => $customerId,
                'kyc_status' => $status,
            ]);
        } else {
            Log::warning('[BlaaizWebhook] No user found for KYC event', [
                'blaaiz_customer_id' => $customerId,
            ]);
        }

        return response()->json(['message' => 'KYC webhook processed'], 200);
    }

    // ── Unknown event ─────────────────────────────────────────────────────────

    protected function handleUnknown(?string $event, array $payload): \Illuminate\Http\JsonResponse
    {
        Log::warning('[BlaaizWebhook] Unknown event type', [
            'event'   => $event,
            'payload' => $payload,
        ]);

        return response()->json(['message' => 'Event not handled'], 200);
    }

    // ── Push notification ─────────────────────────────────────────────────────

    protected function sendPushNotification(TransactionHistory $tx): void
    {
        try {
            $firebase = app(FirebaseNotificationService::class);

            $user = $tx->user_id ? User::find($tx->user_id) : null;
            $user = $user ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

            if (!$user || empty($user->device_token)) {
                return;
            }

            $currency = strtoupper((string) $tx->currency);
            $amount   = number_format((float) $tx->amount, 2);

            $isInterac = $tx->payment_provider === 'interac';

            $title = match ($tx->status) {
                'success' => $isInterac ? 'Interac Deposit Successful' : 'Transaction Successful',
                'failed'  => $isInterac ? 'Interac Deposit Failed'     : 'Transaction Failed',
                default   => $isInterac ? 'Interac Deposit Updated'    : 'Transaction Updated',
            };

            $body = match ($tx->status) {
                'success' => "Your " . ($isInterac ? 'Interac deposit' : 'transaction') . " of {$currency} {$amount} was successful.",
                'failed'  => "Your " . ($isInterac ? 'Interac deposit' : 'transaction') . " of {$currency} {$amount} failed.",
                default   => "Your transaction status is now " . ucfirst($tx->status) . ".",
            };

            $firebase->sendToToken($user->device_token, $title, $body, [
                'type'           => 'transaction',
                'transaction_id' => (string) $tx->id,
                'status'         => (string) $tx->status,
            ]);

            Log::info('[BlaaizWebhook] Push notification sent', ['tx_id' => $tx->id]);

        } catch (\Throwable $e) {
            Log::warning('[BlaaizWebhook] Push notification failed', [
                'tx_id' => $tx->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    // ── Email ─────────────────────────────────────────────────────────────────

    protected function sendEmail(TransactionHistory $tx): void
    {
        try {
            $user = $tx->user_id ? User::find($tx->user_id) : null;
            $user = $user ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

            if (!$user || empty($user->email)) {
                return;
            }

            $balance    = Balance::find($tx->balance_id);
            $statusText = ucfirst((string) $tx->status);
            $isInterac  = $tx->payment_provider === 'interac';

            $data = [
                'name'               => $user->business_name ?? $user->name ?? $user->firstname ?? 'Customer',
                'amount_sent'        => number_format((float) $tx->amount, 2),
                'recipient_amount'   => number_format((float) $tx->recipient_amount, 2),
                'fee'                => number_format((float) ($tx->fees ?? 0), 2),
                'total_amount'       => number_format((float) ($tx->total_amount ?? $tx->amount), 2),
                'current_balance'    => number_format((float) ($balance->amount ?? 0), 2),
                'sending_currency'   => strtoupper((string) $tx->currency),
                'recipient_currency' => strtoupper((string) ($tx->to_currency ?? $tx->recipient_bank_currency ?? $tx->currency)),
                'reference'          => $tx->reference ?? $tx->order_id ?? 'N/A',
                'status'             => $tx->status,
                'status_text'        => $statusText,
                'failure_reason'     => $tx->failure_reason,
                'status_message'     => $tx->status === 'success'
                    ? ($isInterac
                        ? 'Your Interac deposit was processed successfully. Here is a summary of your transaction.'
                        : 'Your transfer was processed successfully. Here is a summary of your transaction.')
                    : ($isInterac
                        ? 'Your Interac deposit could not be completed. Here is the transaction summary.'
                        : 'Your transfer could not be completed. Here is the transaction summary.'),
            ];

            Mail::to($user->email)->send(new TransactionSentMail($data));

            Log::info('[BlaaizWebhook] Email sent', ['tx_id' => $tx->id]);

        } catch (\Throwable $e) {
            Log::warning('[BlaaizWebhook] Email failed', [
                'tx_id' => $tx->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}