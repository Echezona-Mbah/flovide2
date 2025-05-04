<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    protected $fillable = [
        'name',
        'code',
        'currency',
        'currency_code',
        // 'instructions',

  

    ];


    protected $table ='countries';

    protected $primaryKey ='id';
}
