<?php

namespace App\Services;

use App\Models\Balance;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\DB;
use DomainException;
use InvalidArgumentException;

class SwapprCadInteracService
{
    public function hasValidWebhookSignature(
        string $body,
        string $header,
        string $secret,
        int $nowTimestamp
    ): bool
    {
        return $this->webhookSignatureFailureReason($body, $header, $secret, $nowTimestamp) === null;
    }

    public function webhookSignatureFailureReason(
        string $body,
        string $header,
        string $secret,
        int $nowTimestamp
    ): ?string
    {
        $parts = [];
        foreach (explode(',', $header) as $part) {
            $pair = explode('=', trim($part), 2);
            if (count($pair) === 2) {
                $parts[$pair[0]] = $pair[1];
            }
        }

        $timestamp = $parts['t'] ?? '';
        $signature = $parts['v1'] ?? '';
        if ($secret === '') {
            return 'secret_not_configured';
        }

        if ($header === '') {
            return 'signature_header_missing';
        }

        if (!ctype_digit($timestamp)) {
            return 'timestamp_missing_or_invalid';
        }

        if (!preg_match('/^[a-f0-9]{64}$/i', $signature)) {
            return 'signature_missing_or_invalid';
        }

        if (abs($nowTimestamp - (int) $timestamp) > 300) {
            return 'timestamp_outside_tolerance';
        }

        $expected = hash_hmac('sha256', $timestamp . '.' . $body, $secret);

        return hash_equals($expected, strtolower($signature))
            ? null
            : 'signature_mismatch';
    }

    /**
     * Match a settled CAD Interac transfer to an open top-up, then credit it once.
     *
     * @return array{result: string, transaction_id?: string, reason?: string, pending_amounts?: array<int, string>}
     */
    public function processIncomingTransfer(array $event): array
    {
        $ledgerEntryId = $event['ledgerEntryId'] ?? null;
        $senderEmail = strtolower(trim((string) data_get($event, 'sender.interac_email')));
        $narration = trim((string) ($event['narration'] ?? ''));
        $grossMinor = $this->parseMinorUnits($event['amountMinor'] ?? null);
        $netMinor = $this->parseMinorUnits($event['netMinor'] ?? null);

        if (
            !is_string($ledgerEntryId)
            || $ledgerEntryId === ''
            || strlen($ledgerEntryId) > 255
            || !filter_var($senderEmail, FILTER_VALIDATE_EMAIL)
            || $grossMinor === null
            || $grossMinor < 1
            || $netMinor === null
            || $netMinor < 1
            || $netMinor > $grossMinor
        ) {
            throw new InvalidArgumentException('Swappr CAD Interac webhook is missing valid transfer fields.');
        }

        $alreadyProcessed = TransactionHistory::query()
            ->where('payment_provider', 'swappr')
            ->where('order_id', $ledgerEntryId)
            ->exists();

        if ($alreadyProcessed) {
            return ['result' => 'duplicate'];
        }

        $pendingForSender = TransactionHistory::query()
            ->where('payment_provider', 'swappr')
            ->where('currency', 'CAD')
            ->where('status', 'pending')
            ->whereNull('order_id')
            ->whereIn('payment_method', ['manual_interac', 'wallet_autodeposit'])
            ->whereRaw('LOWER(sender) = ?', [$senderEmail])
            ->get();

        $exactAmountOtherSenderCount = 0;
        if ($pendingForSender->isEmpty()) {
            $exactAmountOtherSenderCount = TransactionHistory::query()
                ->where('payment_provider', 'swappr')
                ->where('currency', 'CAD')
                ->where('status', 'pending')
                ->whereNull('order_id')
                ->whereIn('payment_method', ['manual_interac', 'wallet_autodeposit'])
                ->where('amount', number_format($grossMinor / 100, 2, '.', ''))
                ->where(function ($query) use ($senderEmail) {
                    $query->whereNull('sender')
                        ->orWhereRaw('LOWER(sender) <> ?', [$senderEmail]);
                })
                ->count();
        }

        $pending = $pendingForSender
            ->filter(function (TransactionHistory $transaction) use ($grossMinor) {
                return $this->amountToMinorUnits($transaction->amount) === $grossMinor;
            })
            ->values();

        $referenced = $pending->filter(function (TransactionHistory $transaction) use ($narration) {
            $reference = (string) ($transaction->reference ?: $transaction->payment_reference);

            return $reference !== ''
                && $narration !== ''
                && stripos($narration, $reference) !== false;
        })->values();

        if ($referenced->count() === 1) {
            $transaction = $referenced->first();
        } elseif ($referenced->count() > 1) {
            return $this->unmatchedResult('multiple_reference_matches', $pendingForSender);
        } elseif ($pending->count() > 1) {
            return $this->unmatchedResult('multiple_sender_and_amount_matches', $pendingForSender);
        } elseif ($pending->isEmpty()) {
            return $this->unmatchedResult(
                $pendingForSender->isEmpty() && $exactAmountOtherSenderCount > 0
                    ? 'pending_topup_amount_found_but_sender_differs'
                    : ($pendingForSender->isEmpty()
                    ? 'no_pending_topup_for_sender'
                    : 'no_pending_topup_with_exact_gross_amount'),
                $pendingForSender,
                $exactAmountOtherSenderCount
            );
        } else {
            $transaction = $pending->first();
        }

        $credited = $this->creditPendingTransaction(
            (string) $transaction->id,
            $ledgerEntryId,
            round($grossMinor / 100, 2),
            false
        );

        return $credited
            ? ['result' => 'credited', 'transaction_id' => (string) $transaction->id]
            : ['result' => 'already_processed'];
    }

    /**
     * @return array{result: string, reason: string, pending_amounts: array<int, string>}
     */
    private function unmatchedResult(
        string $reason,
        $pendingForSender,
        int $exactAmountOtherSenderCount = 0
    ): array
    {
        return [
            'result' => 'unmatched',
            'reason' => $reason,
            'pending_amounts' => $pendingForSender
                ->map(fn (TransactionHistory $transaction) => number_format((float) $transaction->amount, 2, '.', ''))
                ->values()
                ->all(),
            'exact_amount_other_sender_count' => $exactAmountOtherSenderCount,
        ];
    }

    public function confirmManually(
        string $transactionId,
        string $ledgerEntryId,
        string $senderEmail,
        float $amount,
        float $netAmount
    ): void {
        $grossMinor = $this->amountToMinorUnits($amount);
        $netMinor = $this->amountToMinorUnits($netAmount);

        if ($grossMinor === null || $grossMinor < 1 || $netMinor === null || $netMinor < 1) {
            throw new DomainException('Enter a valid CAD amount from the Swappr transfer.');
        }

        if (trim($ledgerEntryId) === '' || strlen($ledgerEntryId) > 255) {
            throw new DomainException('Enter the Swappr ledger entry ID.');
        }

        $transaction = TransactionHistory::query()->findOrFail($transactionId);

        if ($this->amountToMinorUnits($transaction->amount) !== $grossMinor) {
            throw new DomainException('The confirmed amount does not match the pending top-up.');
        }

        if ($netMinor > $grossMinor) {
            throw new DomainException('The verified Swappr net amount cannot exceed the gross transfer amount.');
        }

        $senderEmail = strtolower(trim($senderEmail));
        if (!filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException('Enter the sender email shown in the Swappr transfer.');
        }

        $credited = $this->creditPendingTransaction(
            $transactionId,
            $ledgerEntryId,
            round($grossMinor / 100, 2),
            true,
            $senderEmail
        );

        if (!$credited) {
            throw new DomainException('This transaction is no longer pending.');
        }
    }

    private function creditPendingTransaction(
        string $transactionId,
        string $ledgerEntryId,
        float $creditAmount,
        bool $manual,
        ?string $verifiedSenderEmail = null
    ): bool {
        return DB::transaction(function () use ($transactionId, $ledgerEntryId, $creditAmount, $manual, $verifiedSenderEmail) {
            $transaction = TransactionHistory::query()
                ->whereKey($transactionId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaction->status !== 'pending') {
                return false;
            }

            if (
                $transaction->payment_provider !== 'swappr'
                || strtoupper((string) $transaction->currency) !== 'CAD'
                || !in_array($transaction->payment_method, ['manual_interac', 'wallet_autodeposit'], true)
                || !$transaction->balance_id
                    || (!$transaction->user_id && !$transaction->personal_id)
                    || ($transaction->user_id && $transaction->personal_id)
            ) {
                throw new DomainException('This transaction is not an eligible Swappr CAD top-up.');
            }

            $ledgerEntryExists = TransactionHistory::query()
                ->where('payment_provider', 'swappr')
                ->where('order_id', $ledgerEntryId)
                ->whereKeyNot($transaction->id)
                ->exists();

            if ($ledgerEntryExists) {
                throw new DomainException('This Swappr ledger entry has already been applied.');
            }

            if ($manual && $transaction->order_id) {
                throw new DomainException('This transaction already has a Swappr ledger entry.');
            }

            $balance = Balance::query()
                ->whereKey($transaction->balance_id)
                ->lockForUpdate()
                ->first();

            if (
                !$balance
                || strtoupper((string) $balance->currency) !== 'CAD'
                || strtolower((string) ($balance->mode ?: $transaction->mode ?: 'live')) !== 'live'
                || (string) $balance->user_id !== (string) $transaction->user_id
                || (string) $balance->personal_id !== (string) $transaction->personal_id
            ) {
                throw new DomainException('The CAD wallet does not belong to this pending top-up.');
            }

            if (!is_finite($creditAmount) || $creditAmount <= 0) {
                throw new DomainException('The verified Swappr gross amount must be greater than zero.');
            }

            $transaction->recipient_amount = round($creditAmount, 2);
            $transaction->order_id = $ledgerEntryId;
            $transaction->status = 'success';
            if ($manual && $verifiedSenderEmail !== null) {
                $transaction->sender = $verifiedSenderEmail;
            }
            $transaction->save();

            $balance->amount = round((float) $balance->amount + $creditAmount, 2);
            $balance->save();

            return true;
        });
    }

    private function parseMinorUnits($amount): ?int
    {
        if (is_int($amount)) {
            return $amount >= 0 ? $amount : null;
        }

        if (is_string($amount) && preg_match('/^(0|[1-9][0-9]*)$/', $amount)) {
            $minorUnits = filter_var($amount, FILTER_VALIDATE_INT);

            return $minorUnits === false ? null : $minorUnits;
        }

        return null;
    }

    private function amountToMinorUnits($amount): ?int
    {
        if (!is_numeric($amount) || !is_finite((float) $amount) || (float) $amount < 0) {
                return null;
        }

        return (int) round((float) $amount * 100);
    }
}
