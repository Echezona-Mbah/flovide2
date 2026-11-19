<?php

namespace App\Http\Controllers\Blaaiz;

use App\Http\Controllers\Controller;
use App\Mail\TransactionSentMail;
use App\Models\Balance;
use App\Models\InteracAutoDeposit;
use App\Models\Personal;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\UserCurrencyFee;
use App\Services\FirebaseNotificationService;
use Blaaiz\LaravelSdk\Exceptions\BlaaizException;
use Blaaiz\LaravelSdk\Facades\Blaaiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BlaaizWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('[BlaaizWebhook] Raw content', [
            'headers'      => $request->headers->all(),
            'content_type' => $request->header('Content-Type'),
            'raw_body'     => $request->getContent(),
        ]);

        // ── Signature verification (via SDK — handles timestamp + body signing) ──
        try {
            $verified = Blaaiz::webhooks()->constructEvent(
                $request->getContent(),
                $request->header('X-Blaaiz-Signature', ''),
                $request->header('X-Blaaiz-Timestamp', ''),
                config('services.blaaiz.webhook_secret')
            );
        } catch (BlaaizException $e) {
            Log::warning('[BlaaizWebhook] Signature verification failed', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        // constructEvent may return an object, stdClass, or array depending on
        // SDK version — normalize to array. Fall back to the raw decoded body
        // if the SDK returns something unexpected, so we don't hard-fail on
        // a shape mismatch after signature verification already succeeded.
        $payload = match (true) {
            is_array($verified) => $verified,
            is_object($verified) => json_decode(json_encode($verified), true),
            default => json_decode($request->getContent(), true) ?? [],
        };

        Log::info('[BlaaizWebhook] Incoming webhook', $payload);

        // ── Route by event type ───────────────────────────────────────────────
        // Observed payloads use "type" (e.g. "collection"); keep "event" as a
        // fallback in case other event categories use a different key.
        $event = $payload['type'] ?? $payload['event'] ?? null;

        Log::info('[BlaaizWebhook] Event type', ['event' => $event]);

        return match (true) {
            str_contains((string) $event, 'interac')    => $this->handleInterac($payload),
            str_contains((string) $event, 'collection') => $this->handleCollection($payload),
            str_contains((string) $event, 'payout')     => $this->handlePayout($payload),
            str_contains((string) $event, 'kyc')        => $this->handleKyc($payload),
            default => $this->handleUnknown($event, $payload),
        };
    }

    /**
     * Pull the transaction id / reference / raw status out of a Blaaiz
     * webhook payload. Observed real payloads are flat (no "data" wrapper)
     * and use "transaction_id", "transaction_reference", and
     * "transaction_status" — but we keep the old key names as fallbacks in
     * case other event types (or future payload versions) nest under
     * "data" or use the shorter field names.
     */
    protected function extractTransactionFields(array $payload): array
    {
        $data = $payload['data'] ?? $payload;

        $transactionId = $data['transaction_id'] ?? $data['id'] ?? null;
        $reference     = $data['transaction_reference']
            ?? $data['interac_reference']
            ?? $data['merchant_reference']
            ?? $data['reference']
            ?? null;
        $rawStatus     = strtolower($data['transaction_status'] ?? $data['status'] ?? '');

        return [$data, $transactionId, $reference, $rawStatus];
    }

    // ── Email mismatch guard ────────────────────────────────────────────────

    /**
     * Compares the email on file for this transaction (set when the Interac
     * request was originally created) against the collection_email Blaaiz
     * reports in the webhook. Returns true if they match (or if there's
     * nothing meaningful to compare), false if they genuinely mismatch.
     */
    protected function interacEmailMatches(TransactionHistory $tx, array $data): bool
    {
        $receivedEmail = data_get($data, 'source_information.collection_email');
        $expectedEmail = $tx->interac_email ?? $tx->sender;

        // Nothing to compare against — don't block on missing data either side.
        if (empty($receivedEmail) || empty($expectedEmail)) {
            return true;
        }

        return strtolower(trim($receivedEmail)) === strtolower(trim($expectedEmail));
    }

    protected function notifyAdminOfEmailMismatch(TransactionHistory $tx, array $data): void
    {
        $expectedEmail = $tx->interac_email ?? $tx->sender;
        $receivedEmail = data_get($data, 'source_information.collection_email');

        $admins = collect(explode(',', (string) env('ADMIN_EMAIL', '')))
            ->map(fn ($e) => trim($e))
            ->filter()
            ->values()
            ->all();

        if (empty($admins)) {
            Log::warning('[BlaaizWebhook] Email mismatch detected but no ADMIN_EMAIL configured', [
                'tx_id' => $tx->id,
            ]);
            return;
        }

        try {
            Mail::send([], [], function ($message) use ($admins, $tx, $expectedEmail, $receivedEmail) {
                $message->to($admins)
                    ->subject('⚠️ Interac Collection Email Mismatch — ' . ($tx->reference ?? $tx->id))
                    ->html('
                        <p><strong>Transaction:</strong> ' . e($tx->reference ?? 'N/A') . ' (ID: ' . e($tx->id) . ')</p>
                        <p><strong>Expected email (on file):</strong> ' . e($expectedEmail ?? 'N/A') . '</p>
                        <p><strong>Received email (from Blaaiz):</strong> ' . e($receivedEmail ?? 'N/A') . '</p>
                        <p>This webhook was <strong>not</strong> applied — the transaction status was left unchanged and the wallet was not credited. Please review manually.</p>
                    ');
            });

            Log::info('[BlaaizWebhook] Admin notified of email mismatch', [
                'tx_id'  => $tx->id,
                'admins' => $admins,
            ]);
        } catch (\Throwable $e) {
            Log::error('[BlaaizWebhook] Failed to send email-mismatch admin notification', [
                'tx_id' => $tx->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    // ── Interac ───────────────────────────────────────────────────────────────

    protected function handleInterac(array $payload): \Illuminate\Http\JsonResponse
    {
        Log::info('[BlaaizWebhook] Handling Interac event', $payload);

        [$data, $transactionId, $reference, $rawStatus] = $this->extractTransactionFields($payload);

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

        // ── Email mismatch guard ────────────────────────────────────────────────
        if (!$this->interacEmailMatches($tx, $data)) {
            Log::warning('[BlaaizWebhook] Interac collection_email mismatch — update skipped', [
                'tx_id'           => $tx->id,
                'expected_email'  => $tx->interac_email ?? $tx->sender,
                'received_email'  => data_get($data, 'source_information.collection_email'),
            ]);

            $this->notifyAdminOfEmailMismatch($tx, $data);

            return response()->json(['message' => 'Email mismatch — transaction not updated, admin notified'], 200);
        }

        if ($tx->status === $mapped) {
            Log::info('[BlaaizWebhook] Status unchanged, skipping', ['tx_id' => $tx->id]);
            return response()->json(['message' => 'No change'], 200);
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
                $balance->amount += $tx->recipient_amount;
                $balance->save();

                Log::info('[BlaaizWebhook] Balance credited', [
                    'balance_id'  => $balance->id,
                    'amount'      => $tx->recipient_amount,
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

        [$data, $transactionId, $reference, $rawStatus] = $this->extractTransactionFields($payload);

        $mapped = match ($rawStatus) {
            'success', 'completed', 'successful' => 'success',
            'failed', 'declined', 'rejected'     => 'failed',
            default                               => 'pending',
        };

        Log::info('[BlaaizWebhook] Collection status mapped', [
            'transaction_id' => $transactionId,
            'reference'      => $reference,
            'raw_status'     => $rawStatus,
            'mapped_status'  => $mapped,
        ]);

        if ($transactionId === null && $reference === null) {
            Log::warning('[BlaaizWebhook] Collection event has no transaction identifier or reference');

            return response()->json(['message' => 'Transaction identifier or reference missing'], 422);
        }

        // Blaaiz includes the destination address (the business's registered
        // collection email) separately from the payer's email. Resolve that
        // address to a business CAD wallet first, then only match a business
        // transaction tied to that wallet. Personal transactions must never
        // be eligible for this callback.
        $payeeCollectionEmail = strtolower(trim((string) data_get($data, 'payee_collection_email')));
        if ($payeeCollectionEmail === '') {
            Log::warning('[BlaaizWebhook] Collection event has no payee collection email', [
                'transaction_id' => $transactionId,
                'reference' => $reference,
            ]);

            return response()->json(['message' => 'Business collection email missing'], 422);
        }

        $destinations = InteracAutoDeposit::query()
            ->with('balance')
            ->whereNotNull('user_id')
            ->whereNotNull('balance_id')
            ->whereRaw('LOWER(email) = ?', [$payeeCollectionEmail])
            ->get()
            ->filter(function (InteracAutoDeposit $destination) {
                $balance = $destination->balance;

                return $balance
                    && (string) $balance->user_id === (string) $destination->user_id
                    && $balance->personal_id === null
                    && strtoupper((string) $balance->currency) === 'CAD';
            })
            ->values();

        if ($destinations->isEmpty()) {
            Log::warning('[BlaaizWebhook] Collection email is not linked to a business wallet', [
                'payee_collection_email' => $payeeCollectionEmail,
                'transaction_id' => $transactionId,
                'reference' => $reference,
            ]);

            return response()->json(['message' => 'Business collection email not found'], 404);
        }

        $destinations = $destinations
            ->unique(fn (InteracAutoDeposit $item) => (string) $item->user_id . ':' . (string) $item->balance_id)
            ->values();

        if ($destinations->count() !== 1) {
            Log::error('[BlaaizWebhook] Collection email maps to multiple business wallets', [
                'payee_collection_email' => $payeeCollectionEmail,
                'wallets' => $destinations->map(fn (InteracAutoDeposit $item) => [
                    'user_id' => $item->user_id,
                    'balance_id' => $item->balance_id,
                ])->all(),
            ]);

            return response()->json(['message' => 'Business collection email maps to multiple wallets'], 409);
        }

        $destination = $destinations->first();

        $txQuery = TransactionHistory::query()
            ->whereNotNull('user_id')
            ->whereNull('personal_id')
            ->where('user_id', $destination->user_id)
            ->where(function ($q) use ($transactionId, $reference) {
                if ($transactionId !== null) {
                    $q->where('id', $transactionId)
                        ->orWhere('order_id', $transactionId);
                }

                if ($reference !== null) {
                    if ($transactionId !== null) {
                        $q->orWhere('reference', $reference)
                            ->orWhere('payment_reference', $reference);
                    } else {
                        $q->where('reference', $reference)
                            ->orWhere('payment_reference', $reference);
                    }
                }
            });

        $tx = $txQuery->first();

        if (!$tx) {
            Log::info('[BlaaizWebhook] No pre-existing business collection found; recording the signed collection event', [
                'transaction_id' => $transactionId,
                'reference'      => $reference,
                'payee_collection_email' => $payeeCollectionEmail,
                'business_id' => $destination->user_id,
                'balance_id' => $destination->balance_id,
            ]);
        }

        // ── Email mismatch guard ────────────────────────────────────────────────
        if ($tx && ! $this->interacEmailMatches($tx, $data)) {
            Log::warning('[BlaaizWebhook] Collection email mismatch — update skipped', [
                'tx_id'           => $tx->id,
                'expected_email'  => $tx->interac_email ?? $tx->sender,
                'received_email'  => data_get($data, 'source_information.collection_email'),
            ]);

            $this->notifyAdminOfEmailMismatch($tx, $data);

            return response()->json(['message' => 'Email mismatch — transaction not updated, admin notified'], 200);
        }

        $amount = data_get($data, 'transaction_amount');
        $fee = data_get($data, 'transaction_fee', 0);
        $netAmount = data_get($data, 'transaction_amount_without_fee');
        $currency = strtoupper((string) data_get($data, 'transaction_currency', ''));

        if (! is_numeric($amount) || ! is_numeric($fee) || ($netAmount !== null && ! is_numeric($netAmount)) || $currency !== 'CAD') {
            Log::warning('[BlaaizWebhook] Invalid business CAD collection amount fields', [
                'transaction_id' => $transactionId,
                'reference' => $reference,
                'currency' => $currency,
            ]);

            return response()->json(['message' => 'Invalid CAD collection amount fields'], 422);
        }

        $amount = round((float) $amount, 2);
        $providerFee = round((float) $fee, 2);
        $providerNetAmount = $netAmount === null
            ? round($amount - $fee, 2)
            : round((float) $netAmount, 2);
        $payerEmail = data_get($data, 'source_information.collection_email');
        $storedReference = (string) ($reference ?: $transactionId);

        if ($amount <= 0 || $providerFee < 0 || $providerFee > $amount || $providerNetAmount < 0 || $providerNetAmount > $amount) {
            return response()->json(['message' => 'Invalid CAD collection amount fields'], 422);
        }

        // Blaaiz's fee is a provider cost. The merchant's collection fee is
        // calculated independently from the business pricing configured in
        // UserCurrencyFee and deducted from the amount Blaaiz settles.
        $businessFee = UserCurrencyFee::query()
            ->where('user_id', $destination->user_id)
            ->where('currency', $currency)
            ->first();
        $platformFee = $businessFee && $businessFee->collection_enabled
            ? $businessFee->calcCollectionFee($amount)
            : 0.0;
        // The customer pays the gross amount. Blaaiz deducts its fee from
        // Flovide's settlement, so it must not reduce the merchant's credit.
        $recipientAmount = round($amount - $platformFee, 2);

        if ($platformFee < 0 || $platformFee > $amount) {
            Log::warning('[BlaaizWebhook] Business collection fee exceeds settled amount', [
                'business_id' => $destination->user_id,
                'amount' => $amount,
                'provider_fee' => $providerFee,
                'provider_net_amount' => $providerNetAmount,
                'business_fee' => $platformFee,
            ]);

            return response()->json(['message' => 'Business collection fee exceeds settled amount'], 422);
        }

        $result = DB::transaction(function () use (
            $tx,
            $mapped,
            $destination,
            $transactionId,
            $storedReference,
            $payerEmail,
            $amount,
            $platformFee,
            $recipientAmount,
            $currency
        ) {
            $balance = Balance::query()
                ->whereKey($destination->balance_id)
                ->where('user_id', $destination->user_id)
                ->whereNull('personal_id')
                ->where('currency', $currency)
                ->lockForUpdate()
                ->first();

            if (! $balance) {
                return ['changed' => false, 'missing' => true];
            }

            $lockedTx = null;
            if ($tx) {
                $lockedTx = TransactionHistory::query()
                    ->whereKey($tx->getKey())
                    ->where('user_id', $destination->user_id)
                    ->whereNotNull('user_id')
                    ->whereNull('personal_id')
                    ->lockForUpdate()
                    ->first();
            } else {
                $lockedTx = TransactionHistory::query()
                    ->where('user_id', $destination->user_id)
                    ->whereNotNull('user_id')
                    ->whereNull('personal_id')
                    ->where(function ($query) use ($transactionId, $storedReference) {
                        $query->where('reference', $storedReference)
                            ->orWhere('payment_reference', $storedReference);

                        if ($transactionId !== null) {
                            $query->orWhere('id', $transactionId)
                                ->orWhere('order_id', $transactionId);
                        }
                    })
                    ->lockForUpdate()
                    ->first();
            }

            if ($lockedTx) {
                $currentStatus = strtolower((string) $lockedTx->status);
                if ($currentStatus === $mapped || in_array($currentStatus, ['success', 'successful', 'failed', 'declined'], true)) {
                    return ['changed' => false, 'missing' => false, 'transaction' => $lockedTx];
                }

                $lockedTx->balance_id = $balance->id;
                $lockedTx->amount = $amount;
                $lockedTx->fees = $platformFee;
                $lockedTx->recipient_amount = $recipientAmount;
                $lockedTx->currency = $currency;
                $referenceOwnedByAnotherTransaction = TransactionHistory::query()
                    ->where('reference', $storedReference)
                    ->where('id', '!=', $lockedTx->id)
                    ->exists();
                if (! $referenceOwnedByAnotherTransaction) {
                    $lockedTx->reference = $storedReference;
                } elseif (blank($lockedTx->reference)) {
                    $lockedTx->reference = 'FLV-' . Str::uuid();
                }
                $lockedTx->payment_reference = $storedReference;
                $lockedTx->order_id = $transactionId ?: $lockedTx->order_id;
                $lockedTx->interac_email = $payerEmail ?: $lockedTx->interac_email;
                $lockedTx->sender = $payerEmail ?: $lockedTx->sender;
                $lockedTx->payment_provider = $lockedTx->payment_provider ?: 'interac';
                $lockedTx->status = $mapped;
                $lockedTx->save();
            } else {
                $historyReference = $storedReference;
                if (TransactionHistory::query()->where('reference', $historyReference)->exists()) {
                    // The provider reference is kept in payment_reference. A
                    // globally unique local reference prevents a collision
                    // with a personal transaction without touching that row.
                    $historyReference = 'FLV-' . Str::uuid();
                }

                $lockedTx = TransactionHistory::create([
                    'user_id' => $destination->user_id,
                    'personal_id' => null,
                    'balance_id' => $balance->id,
                    'type' => 'credit',
                    'method' => 'credit',
                    'transaction_type' => 'payment',
                    'payment_method' => 'wallet_autodeposit',
                    'payment_provider' => 'interac',
                    'sender' => $payerEmail,
                    'interac_email' => $payerEmail,
                    'amount' => $amount,
                    'fees' => $platformFee,
                    'recipient_amount' => $recipientAmount,
                    'currency' => $currency,
                    'status' => $mapped,
                    'reference' => $historyReference,
                    'payment_reference' => $storedReference,
                    'order_id' => $transactionId,
                    'mode' => $balance->mode ?: 'live',
                    'api_source' => true,
                ]);
            }

            if ($mapped === 'success') {
                $balance->amount = (float) $balance->amount + $recipientAmount;
                $balance->save();
            }

            return [
                'changed' => true,
                'missing' => false,
                'transaction' => $lockedTx->fresh(),
                'balance' => $mapped === 'success' ? $balance->fresh() : null,
            ];
        }, 3);

        if ($result['missing']) {
            Log::error('[BlaaizWebhook] Business wallet or transaction disappeared during update', [
                'tx_id' => $tx?->id,
                'user_id' => $destination->user_id,
                'balance_id' => $destination->balance_id,
            ]);

            return response()->json(['message' => 'Business wallet not found'], 404);
        }

        if (! $result['changed']) {
            $unchangedTransaction = $result['transaction'] ?? $tx;
            Log::info('[BlaaizWebhook] Business transaction already terminal, skipping duplicate event', [
                'tx_id' => $unchangedTransaction?->id,
                'status' => $unchangedTransaction?->status,
            ]);

            return response()->json(['message' => 'No change'], 200);
        }

        $tx = $result['transaction'];

        Log::info('[BlaaizWebhook] Collection transaction status updated', [
            'tx_id'      => $tx->id,
            'new_status' => $mapped,
        ]);

        if ($mapped === 'success' && $result['balance']) {
            Log::info('[BlaaizWebhook] Business balance credited', [
                'balance_id' => $result['balance']->id,
                'business_id' => $tx->user_id,
                'amount' => $tx->recipient_amount,
                'provider_fee' => $providerFee,
                'business_fee' => $platformFee,
                'new_balance' => $result['balance']->amount,
            ]);
        }

        $this->sendPushNotification($tx);

        if (in_array($mapped, ['success', 'failed'])) {
            $this->sendEmail($tx);
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }

    // ── Payout ────────────────────────────────────────────────────────────────

    protected function handlePayout(array $payload): \Illuminate\Http\JsonResponse
    {
        Log::info('[BlaaizWebhook] Handling Payout event', $payload);

        [$data, $transactionId, $reference, $rawStatus] = $this->extractTransactionFields($payload);

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

// <?php

// namespace App\Http\Controllers\Blaaiz;

// use App\Http\Controllers\Controller;
// use App\Mail\TransactionSentMail;
// use App\Models\Balance;
// use App\Models\Personal;
// use App\Models\TransactionHistory;
// use App\Models\User;
// use App\Services\FirebaseNotificationService;
// use Blaaiz\LaravelSdk\Exceptions\BlaaizException;
// use Blaaiz\LaravelSdk\Facades\Blaaiz;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail;

// class BlaaizWebhookController extends Controller
// {
//     public function handle(Request $request)
//     {
//         Log::info('[BlaaizWebhook] Raw content', [
//             'headers'      => $request->headers->all(),
//             'content_type' => $request->header('Content-Type'),
//             'raw_body'     => $request->getContent(),
//         ]);

//         // ── Signature verification (via SDK — handles timestamp + body signing) ──
//         try {
//             $verified = Blaaiz::webhooks()->constructEvent(
//                 $request->getContent(),
//                 $request->header('X-Blaaiz-Signature', ''),
//                 $request->header('X-Blaaiz-Timestamp', ''),
//                 config('services.blaaiz.webhook_secret')
//             );
//         } catch (BlaaizException $e) {
//             Log::warning('[BlaaizWebhook] Signature verification failed', [
//                 'error' => $e->getMessage(),
//             ]);
//             return response()->json(['message' => 'Invalid signature'], 401);
//         }

//         // constructEvent may return an object, stdClass, or array depending on
//         // SDK version — normalize to array. Fall back to the raw decoded body
//         // if the SDK returns something unexpected, so we don't hard-fail on
//         // a shape mismatch after signature verification already succeeded.
//         $payload = match (true) {
//             is_array($verified) => $verified,
//             is_object($verified) => json_decode(json_encode($verified), true),
//             default => json_decode($request->getContent(), true) ?? [],
//         };

//         Log::info('[BlaaizWebhook] Incoming webhook', $payload);

//         // ── Route by event type ───────────────────────────────────────────────
//         // Observed payloads use "type" (e.g. "collection"); keep "event" as a
//         // fallback in case other event categories use a different key.
//         $event = $payload['type'] ?? $payload['event'] ?? null;

//         Log::info('[BlaaizWebhook] Event type', ['event' => $event]);

//         return match (true) {
//             str_contains((string) $event, 'interac')    => $this->handleInterac($payload),
//             str_contains((string) $event, 'collection') => $this->handleCollection($payload),
//             str_contains((string) $event, 'payout')     => $this->handlePayout($payload),
//             str_contains((string) $event, 'kyc')        => $this->handleKyc($payload),
//             default => $this->handleUnknown($event, $payload),
//         };
//     }

//     /**
//      * Pull the transaction id / reference / raw status out of a Blaaiz
//      * webhook payload. Observed real payloads are flat (no "data" wrapper)
//      * and use "transaction_id", "transaction_reference", and
//      * "transaction_status" — but we keep the old key names as fallbacks in
//      * case other event types (or future payload versions) nest under
//      * "data" or use the shorter field names.
//      */
//     protected function extractTransactionFields(array $payload): array
//     {
//         $data = $payload['data'] ?? $payload;

//         $transactionId = $data['transaction_id'] ?? $data['id'] ?? null;
//         $reference     = $data['transaction_reference'] ?? $data['reference'] ?? null;
//         $rawStatus     = strtolower($data['transaction_status'] ?? $data['status'] ?? '');

//         return [$data, $transactionId, $reference, $rawStatus];
//     }

//     // ── Interac ───────────────────────────────────────────────────────────────

//     protected function handleInterac(array $payload): \Illuminate\Http\JsonResponse
//     {
//         Log::info('[BlaaizWebhook] Handling Interac event', $payload);

//         [$data, $transactionId, $reference, $rawStatus] = $this->extractTransactionFields($payload);

//         $mapped = match ($rawStatus) {
//             'success', 'completed', 'successful' => 'success',
//             'failed', 'declined', 'rejected'     => 'failed',
//             default                               => 'pending',
//         };

//         Log::info('[BlaaizWebhook] Interac status mapped', [
//             'transaction_id' => $transactionId,
//             'reference'      => $reference,
//             'raw_status'     => $rawStatus,
//             'mapped_status'  => $mapped,
//         ]);

//         // Find the transaction by order_id or reference
//         $tx = TransactionHistory::where('payment_provider', 'interac')
//             ->where(function ($q) use ($transactionId, $reference) {
//                 $q->where('order_id', $transactionId)
//                   ->orWhere('reference', $reference)
//                   ->orWhere('payment_reference', $reference);
//             })
//             ->first();

//         if (!$tx) {
//             Log::warning('[BlaaizWebhook] No matching transaction found', [
//                 'transaction_id' => $transactionId,
//                 'reference'      => $reference,
//             ]);
//             return response()->json(['message' => 'Transaction not found'], 404);
//         }

//         if ($tx->status === $mapped) {
//             Log::info('[BlaaizWebhook] Status unchanged, skipping', ['tx_id' => $tx->id]);
//             return response()->json(['message' => 'No change'], 200);
//         }

//         // Update status
//         $tx->status = $mapped;
//         $tx->save();

//         Log::info('[BlaaizWebhook] Transaction status updated', [
//             'tx_id'      => $tx->id,
//             'new_status' => $mapped,
//         ]);

//         // Credit balance on success
//         if ($mapped === 'success' && $tx->balance_id) {
//             $balance = Balance::find($tx->balance_id);
//             if ($balance) {
//                 $balance->amount += $tx->recipient_amount;
//                 $balance->save();

//                 Log::info('[BlaaizWebhook] Balance credited', [
//                     'balance_id'  => $balance->id,
//                     'amount'      => $tx->recipient_amount,
//                     'new_balance' => $balance->amount,
//                 ]);
//             }
//         }

//         // Notifications
//         $this->sendPushNotification($tx);

//         if (in_array($mapped, ['success', 'failed'])) {
//             $this->sendEmail($tx);
//         }

//         return response()->json(['message' => 'Webhook processed'], 200);
//     }

//     // ── Collection ────────────────────────────────────────────────────────────

//     protected function handleCollection(array $payload): \Illuminate\Http\JsonResponse
//     {
//         Log::info('[BlaaizWebhook] Handling Collection event', $payload);

//         [$data, $transactionId, $reference, $rawStatus] = $this->extractTransactionFields($payload);

//         $mapped = match ($rawStatus) {
//             'success', 'completed', 'successful' => 'success',
//             'failed', 'declined', 'rejected'     => 'failed',
//             default                               => 'pending',
//         };

//         Log::info('[BlaaizWebhook] Collection status mapped', [
//             'transaction_id' => $transactionId,
//             'reference'      => $reference,
//             'raw_status'     => $rawStatus,
//             'mapped_status'  => $mapped,
//         ]);

//         // Collections aren't restricted to payment_provider = interac,
//         // unlike handleInterac() — match on id/reference only.
//         $tx = TransactionHistory::where(function ($q) use ($transactionId, $reference) {
//                 $q->where('order_id', $transactionId)
//                   ->orWhere('reference', $reference)
//                   ->orWhere('payment_reference', $reference);
//             })
//             ->first();

//         if (!$tx) {
//             Log::warning('[BlaaizWebhook] No matching collection transaction found', [
//                 'transaction_id' => $transactionId,
//                 'reference'      => $reference,
//             ]);
//             return response()->json(['message' => 'Transaction not found'], 404);
//         }

//         if ($tx->status === $mapped) {
//             Log::info('[BlaaizWebhook] Status unchanged, skipping', ['tx_id' => $tx->id]);
//             return response()->json(['message' => 'No change'], 200);
//         }

//         $tx->status = $mapped;
//         $tx->save();

//         Log::info('[BlaaizWebhook] Collection transaction status updated', [
//             'tx_id'      => $tx->id,
//             'new_status' => $mapped,
//         ]);

//         if ($mapped === 'success' && $tx->balance_id) {
//             $balance = Balance::find($tx->balance_id);
//             if ($balance) {
//                 $balance->amount += $tx->recipient_amount;
//                 $balance->save();

//                 Log::info('[BlaaizWebhook] Balance credited', [
//                     'balance_id'  => $balance->id,
//                     'amount'      => $tx->recipient_amount,
//                     'new_balance' => $balance->amount,
//                 ]);
//             }
//         }

//         $this->sendPushNotification($tx);

//         if (in_array($mapped, ['success', 'failed'])) {
//             $this->sendEmail($tx);
//         }

//         return response()->json(['message' => 'Webhook processed'], 200);
//     }

//     // ── Payout ────────────────────────────────────────────────────────────────

//     protected function handlePayout(array $payload): \Illuminate\Http\JsonResponse
//     {
//         Log::info('[BlaaizWebhook] Handling Payout event', $payload);

//         [$data, $transactionId, $reference, $rawStatus] = $this->extractTransactionFields($payload);

//         $mapped = match ($rawStatus) {
//             'success', 'completed', 'successful' => 'success',
//             'failed', 'declined', 'rejected'     => 'failed',
//             default                               => 'pending',
//         };

//         $tx = TransactionHistory::where(function ($q) use ($transactionId, $reference) {
//                 $q->where('order_id', $transactionId)
//                   ->orWhere('reference', $reference)
//                   ->orWhere('payment_reference', $reference);
//             })
//             ->first();

//         if (!$tx) {
//             Log::warning('[BlaaizWebhook] Payout transaction not found', [
//                 'transaction_id' => $transactionId,
//                 'reference'      => $reference,
//             ]);
//             return response()->json(['message' => 'Transaction not found'], 404);
//         }

//         if ($tx->status === $mapped) {
//             return response()->json(['message' => 'No change'], 200);
//         }

//         $tx->status = $mapped;
//         $tx->save();

//         Log::info('[BlaaizWebhook] Payout status updated', [
//             'tx_id'      => $tx->id,
//             'new_status' => $mapped,
//         ]);

//         $this->sendPushNotification($tx);

//         if (in_array($mapped, ['success', 'failed'])) {
//             $this->sendEmail($tx);
//         }

//         return response()->json(['message' => 'Payout webhook processed'], 200);
//     }

//     // ── KYC ──────────────────────────────────────────────────────────────────

//     protected function handleKyc(array $payload): \Illuminate\Http\JsonResponse
//     {
//         Log::info('[BlaaizWebhook] Handling KYC event', $payload);

//         $data       = $payload['data'] ?? $payload;
//         $customerId = $data['customer_id'] ?? $data['id'] ?? null;
//         $status     = strtoupper($data['verification_status'] ?? $data['status'] ?? '');

//         if (!$customerId) {
//             Log::warning('[BlaaizWebhook] KYC event missing customer_id');
//             return response()->json(['message' => 'Missing customer_id'], 422);
//         }

//         // Update user KYC status
//         $user = User::where('blaaiz_id', $customerId)->first();

//         if ($user) {
//             $user->selfie_verification_status = $status;
//             $user->save();

//             Log::info('[BlaaizWebhook] KYC status updated on User', [
//                 'user_id'    => $user->id,
//                 'blaaiz_id'  => $customerId,
//                 'kyc_status' => $status,
//             ]);
//         } else {
//             Log::warning('[BlaaizWebhook] No user found for KYC event', [
//                 'blaaiz_customer_id' => $customerId,
//             ]);
//         }

//         return response()->json(['message' => 'KYC webhook processed'], 200);
//     }

//     // ── Unknown event ─────────────────────────────────────────────────────────

//     protected function handleUnknown(?string $event, array $payload): \Illuminate\Http\JsonResponse
//     {
//         Log::warning('[BlaaizWebhook] Unknown event type', [
//             'event'   => $event,
//             'payload' => $payload,
//         ]);

//         return response()->json(['message' => 'Event not handled'], 200);
//     }

//     // ── Push notification ─────────────────────────────────────────────────────

//     protected function sendPushNotification(TransactionHistory $tx): void
//     {
//         try {
//             $firebase = app(FirebaseNotificationService::class);

//             $user = $tx->user_id ? User::find($tx->user_id) : null;
//             $user = $user ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

//             if (!$user || empty($user->device_token)) {
//                 return;
//             }

//             $currency = strtoupper((string) $tx->currency);
//             $amount   = number_format((float) $tx->amount, 2);

//             $isInterac = $tx->payment_provider === 'interac';

//             $title = match ($tx->status) {
//                 'success' => $isInterac ? 'Interac Deposit Successful' : 'Transaction Successful',
//                 'failed'  => $isInterac ? 'Interac Deposit Failed'     : 'Transaction Failed',
//                 default   => $isInterac ? 'Interac Deposit Updated'    : 'Transaction Updated',
//             };

//             $body = match ($tx->status) {
//                 'success' => "Your " . ($isInterac ? 'Interac deposit' : 'transaction') . " of {$currency} {$amount} was successful.",
//                 'failed'  => "Your " . ($isInterac ? 'Interac deposit' : 'transaction') . " of {$currency} {$amount} failed.",
//                 default   => "Your transaction status is now " . ucfirst($tx->status) . ".",
//             };

//             $firebase->sendToToken($user->device_token, $title, $body, [
//                 'type'           => 'transaction',
//                 'transaction_id' => (string) $tx->id,
//                 'status'         => (string) $tx->status,
//             ]);

//             Log::info('[BlaaizWebhook] Push notification sent', ['tx_id' => $tx->id]);

//         } catch (\Throwable $e) {
//             Log::warning('[BlaaizWebhook] Push notification failed', [
//                 'tx_id' => $tx->id,
//                 'error' => $e->getMessage(),
//             ]);
//         }
//     }

//     // ── Email ─────────────────────────────────────────────────────────────────

//     protected function sendEmail(TransactionHistory $tx): void
//     {
//         try {
//             $user = $tx->user_id ? User::find($tx->user_id) : null;
//             $user = $user ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

//             if (!$user || empty($user->email)) {
//                 return;
//             }

//             $balance    = Balance::find($tx->balance_id);
//             $statusText = ucfirst((string) $tx->status);
//             $isInterac  = $tx->payment_provider === 'interac';

//             $data = [
//                 'name'               => $user->business_name ?? $user->name ?? $user->firstname ?? 'Customer',
//                 'amount_sent'        => number_format((float) $tx->amount, 2),
//                 'recipient_amount'   => number_format((float) $tx->recipient_amount, 2),
//                 'fee'                => number_format((float) ($tx->fees ?? 0), 2),
//                 'total_amount'       => number_format((float) ($tx->total_amount ?? $tx->amount), 2),
//                 'current_balance'    => number_format((float) ($balance->amount ?? 0), 2),
//                 'sending_currency'   => strtoupper((string) $tx->currency),
//                 'recipient_currency' => strtoupper((string) ($tx->to_currency ?? $tx->recipient_bank_currency ?? $tx->currency)),
//                 'reference'          => $tx->reference ?? $tx->order_id ?? 'N/A',
//                 'status'             => $tx->status,
//                 'status_text'        => $statusText,
//                 'failure_reason'     => $tx->failure_reason,
//                 'status_message'     => $tx->status === 'success'
//                     ? ($isInterac
//                         ? 'Your Interac deposit was processed successfully. Here is a summary of your transaction.'
//                         : 'Your transfer was processed successfully. Here is a summary of your transaction.')
//                     : ($isInterac
//                         ? 'Your Interac deposit could not be completed. Here is the transaction summary.'
//                         : 'Your transfer could not be completed. Here is the transaction summary.'),
//             ];

//             Mail::to($user->email)->send(new TransactionSentMail($data));

//             Log::info('[BlaaizWebhook] Email sent', ['tx_id' => $tx->id]);

//         } catch (\Throwable $e) {
//             Log::warning('[BlaaizWebhook] Email failed', [
//                 'tx_id' => $tx->id,
//                 'error' => $e->getMessage(),
//             ]);
//         }
//     }
// }










