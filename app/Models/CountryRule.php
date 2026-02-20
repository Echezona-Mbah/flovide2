<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryRule extends Model
{
     protected $fillable = [
        'country_iso',
        'country_name',
        'currency_iso',
        'rules'
    ];

    protected $casts = [
        'rules' => 'array'
    ];
}
