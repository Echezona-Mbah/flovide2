<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ExchangeRate extends Model
{

        use Notifiable;



protected $fillable = [
        'country_name',
        'currency_code',
        'currency_symbol', 
        'rate',
        'transfer_fee'
    ];
}
