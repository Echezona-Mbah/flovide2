<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Remita extends Model
{
     use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'amount',
        'rrr',
        'service_type',
        'subaccount',
        'currency',
        'visibility',
    ];
}
