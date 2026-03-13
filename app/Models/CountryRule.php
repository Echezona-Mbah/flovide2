<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryRule extends Model
{
     protected $fillable = [
        'country_iso',
        'country_name',
        'currency_iso',
        'rules',
         'is_active', 
    ];

    protected $casts = [
        'rules' => 'array',
         'is_active' => 'boolean',
    ];
}
