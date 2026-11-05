<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainBank extends Model
{
    protected $table = 'mainbanks';

    protected $fillable = [
        'name',
        'country_iso',
        'currency',
        'type',
        'normalized_name',
        'flovide_code',
    ];
}
