<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProvider extends Model
{
    protected $fillable = ['key', 'name', 'is_enabled', 'priority'];

    protected $casts = [
        'is_enabled' => 'boolean',
        'priority'   => 'integer',
    ];

    public function currencies()
    {
        return $this->hasMany(PaymentProviderCurrency::class);
    }

    public function enabledCurrencies()
    {
        return $this->currencies()->where('is_enabled', true);
    }
}