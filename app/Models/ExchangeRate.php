<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ExchangeRate extends Model
{

        use Notifiable;



protected $fillable = [
<<<<<<< HEAD
    'country_name',
        'currency_code',
=======
        'country_name',
        'currency_code',
        'currency_symbol', 
>>>>>>> recovery
        'rate',
        'transfer_fee'
    ];
}
