<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DstvPlans extends Model
{
        use HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'code',
        'amount',
        'description',
    ];
}
