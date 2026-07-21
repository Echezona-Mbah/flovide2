<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillPayment extends Model
{
    protected $fillable = [
        'user_id',
        'personal_id',
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

        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
