<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCurrencyFee extends Model
{
    const CURRENCIES = [
        'USD','EUR','GBP','NGN','GHS',
        'KES','ZAR','UGX','XOF','CAD','BEN','CIV',
    ];

    protected $fillable = [
        'user_id',
        'currency',

        'collection_enabled',
        'collection_balance',
        'collection_percent',
        'collection_fixed',
        'collection_min',
        'collection_max',

        'payout_enabled',
        'payout_balance',
        'payout_percent',
        'payout_fixed',
        'payout_min',
        'payout_max',
    ];

    protected $casts = [
        'collection_enabled'  => 'boolean',
        'collection_balance'  => 'float',
        'collection_percent'  => 'float',
        'collection_fixed'    => 'float',
        'collection_min'      => 'float',
        'collection_max'      => 'float',

        'payout_enabled'      => 'boolean',
        'payout_balance'      => 'float',
        'payout_percent'      => 'float',
        'payout_fixed'        => 'float',
        'payout_min'          => 'float',
        'payout_max'          => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Fee Calculators ────────────────────────────────────────────────────────

    public function calcCollectionFee(float $amount): float
    {
        return round(
            ($amount * $this->collection_percent / 100) + $this->collection_fixed,
            2
        );
    }

    public function calcPayoutFee(float $amount): float
    {
        return round(
            ($amount * $this->payout_percent / 100) + $this->payout_fixed,
            2
        );
    }

    public function collectionAmountAfterFee(float $amount): float
    {
        return round($amount - $this->calcCollectionFee($amount), 2);
    }

    public function payoutAmountAfterFee(float $amount): float
    {
        return round($amount - $this->calcPayoutFee($amount), 2);
    }

    // ── Balance Helpers ────────────────────────────────────────────────────────

    public function creditCollection(float $amount): void
    {
        $this->increment('collection_balance', $amount);
    }

    public function debitCollection(float $amount): void
    {
        $this->decrement('collection_balance', $amount);
    }

    public function creditPayout(float $amount): void
    {
        $this->increment('payout_balance', $amount);
    }

    public function debitPayout(float $amount): void
    {
        $this->decrement('payout_balance', $amount);
    }
}