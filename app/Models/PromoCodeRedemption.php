<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodeRedemption extends Model
{
    protected $fillable = [
        'promo_code_id',
        'transaction_history_id',
        'redeemer_type',
        'redeemer_id',
        'transaction_amount',
        'transaction_currency',
        'fee_waived',
        'reward_amount',
        'reward_currency',
    ];

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }
}