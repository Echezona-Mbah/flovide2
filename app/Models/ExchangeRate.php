<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ExchangeRate extends Model
{

        use Notifiable;



    protected $fillable = [

        'rate',
        'transfer_fee',
        'collection_fee',
        'from_currency_id',
        'to_currency_id',
    ];


    public function fromCurrency()
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    public function toCurrency()
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }
}
