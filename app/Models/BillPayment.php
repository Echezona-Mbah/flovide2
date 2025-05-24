<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillPayment extends Model
{
    protected $fillable = [
        'user_id',
        'request_id',
        'service_id',
        'variation_code',
        'billers_code',
        'amount',
        'phone',
        'currency',
        'response',
        'status'
    ];

    protected $casts = [
        'response' => 'array',
    ];
}
