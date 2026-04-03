<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
       protected $fillable = ['code', 'name','symbol','country_code'];

    public function ratesFrom()
    {
        return $this->hasMany(ExchangeRate::class, 'from_currency_id');
    }

    public function ratesTo()
    {
        return $this->hasMany(ExchangeRate::class, 'to_currency_id');
    }
}
