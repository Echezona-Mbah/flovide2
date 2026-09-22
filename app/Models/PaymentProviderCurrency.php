<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProviderCurrency extends Model
{
    protected $fillable = ['payment_provider_id', 'currency', 'transfer_method', 'is_enabled'];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function provider()
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }
}