<?php

namespace App\Services;

use App\Models\AdminNotification;
use App\Models\ExchangeRate;
use App\Models\Personal;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Traits\ResolvesReferrer;
use Illuminate\Support\Facades\Log;

class ReferralBonusService
{
    use ResolvesReferrer;

    /**
     * Call this right after a transaction is recorded for a personal or
     * business account. It checks whether that account was referred,
     * whether it has crossed the bonus threshold (converted into the
     * trigger currency), and — if it hasn't already been flagged —
     * notifies the admin exactly once.
     *
     * @param Personal|User $referredAccount  the account that just transacted
     */
    public function checkAndNotify($referredAccount, TransactionHistory $transaction): void
    {
        // Already notified for this account — never notify twice.
        if ($referredAccount->referral_bonus_notified_at) {
            return;
        }

        // Not a referred account at all.
        if (empty($referredAccount->referred_by)) {
            return;
        }

        $referredType = $referredAccount instanceof Personal ? 'personal' : 'business';

        $threshold = $referredType === 'personal'
            ? config('referral.bonus_trigger_amount_personal')
            : config('referral.bonus_trigger_amount_business');

        $targetCurrency = config('referral.bonus_trigger_currency');
        $mode = config('referral.trigger_mode');

        $amountToCheck = $mode === 'cumulative'
            ? $this->cumulativeAmount($referredAccount, $targetCurrency)
            : $this->convertToTargetCurrency((float) $transaction->amount, $transaction->currency, $targetCurrency);

        if ($amountToCheck < $threshold) {
            return;
        }

        $referrer = $this->resolveReferrerById($referredAccount->referred_by, $referredType);

        if (!$referrer) {
            return; // dangling/invalid referrer id, nothing to notify
        }

        // ── One-time lock: set this BEFORE dispatching the notification ──
        $locked = $referredAccount->newQuery()
            ->where('id', $referredAccount->id)
            ->whereNull('referral_bonus_notified_at')
            ->update(['referral_bonus_notified_at' => now()]);

        if (!$locked) {
            return; // another process already claimed this notification
        }

        $this->notifyAdmin($referredAccount, $referrer, $amountToCheck, $threshold, $targetCurrency);
    }

    /**
     * Sum ALL of this account's successful transactions, converting each
     * one into the target currency (e.g. CAD) using the current exchange
     * rate table. Transactions already in the target currency pass through
     * unchanged. Transactions with no available rate are skipped (not
     * counted) rather than causing an error.
     */
    private function cumulativeAmount($referredAccount, string $targetCurrency): float
    {
        $column = $referredAccount instanceof Personal ? 'personal_id' : 'user_id';

        $transactions = TransactionHistory::where($column, $referredAccount->id)
            ->where('status', 'success')
            ->get(['amount', 'currency']);

        $total = 0.0;

        foreach ($transactions as $t) {
            $total += $this->convertToTargetCurrency((float) $t->amount, $t->currency, $targetCurrency);
        }

        return $total;
    }

    /**
     * Convert an amount from one currency into the target currency using
     * the live ExchangeRate table. Returns the original amount unchanged
     * if the currencies already match. Returns 0.0 if no rate is found,
     * so an unconvertible transaction simply doesn't count toward the
     * cumulative total rather than throwing.
     */
    private function convertToTargetCurrency(float $amount, string $fromCurrency, string $targetCurrency): float
    {
        $fromCurrency = strtoupper($fromCurrency);
        $targetCurrency = strtoupper($targetCurrency);

        if ($fromCurrency === $targetCurrency) {
            return $amount;
        }

        $rate = ExchangeRate::whereHas('fromCurrency', function ($q) use ($fromCurrency) {
                $q->where('code', $fromCurrency);
            })
            ->whereHas('toCurrency', function ($q) use ($targetCurrency) {
                $q->where('code', $targetCurrency);
            })
            ->first();

        if (!$rate) {
            Log::warning('Referral bonus: no exchange rate found for conversion', [
                'from' => $fromCurrency,
                'to'   => $targetCurrency,
            ]);
            return 0.0;
        }

        return $amount * (float) $rate->rate;
    }

    private function notifyAdmin($referredAccount, array $referrer, float $amount, float $threshold, string $currency): void
    {
        $referredName = $referredAccount->business_name
            ?? trim(($referredAccount->firstname ?? '') . ' ' . ($referredAccount->lastname ?? ''));

        $referrerModel = $referrer['model'];
        $referrerName = $referrerModel->business_name
            ?? trim(($referrerModel->firstname ?? '') . ' ' . ($referrerModel->lastname ?? ''));

        $referredType = $referredAccount instanceof Personal ? 'personal' : 'business';

        AdminNotification::create([
            'type'  => 'referral_bonus',
            'title' => 'Referral bonus payout due',
            'body'  => "{$referredName} ({$referredType}) has reached {$currency} " . number_format($amount, 2)
                . " in total transactions, crossing the {$currency} " . number_format($threshold, 2)
                . " threshold. This is a one-time referral bonus for {$referrerName} — add money manually.",
            'data'  => [
                'referred_id'      => $referredAccount->id,
                'referred_type'    => $referredType,
                'referred_name'    => $referredName,
                'referrer_id'      => $referrerModel->id,
                'referrer_type'    => $referrer['type'],
                'referrer_name'    => $referrerName,
                'amount'           => $amount,
                'threshold'        => $threshold,
                'currency'         => $currency,
            ],
        ]);
    }


    // app/Services/ReferralBonusService.php — add these public methods

    /**
     * Get this referred account's bonus progress: how much they've transacted
     * (converted to the trigger currency), what the threshold is, and whether
     * they've already crossed it (bonus triggered).
     */
    public function getProgress($referredAccount): array
    {
        $type = $referredAccount instanceof Personal ? 'personal' : 'business';

        $threshold = $type === 'personal'
            ? config('referral.bonus_trigger_amount_personal')
            : config('referral.bonus_trigger_amount_business');

        $targetCurrency = config('referral.bonus_trigger_currency');

        $total = $this->cumulativeAmount($referredAccount, $targetCurrency);

        return [
            'total'     => $total,
            'threshold' => (float) $threshold,
            'currency'  => $targetCurrency,
            'completed' => (bool) $referredAccount->referral_bonus_notified_at,
            'percent'   => $threshold > 0 ? min(100, round(($total / $threshold) * 100)) : 0,
        ];
    }
    
}