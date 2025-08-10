<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    //
    protected $fillable = [
        'user_id',
        'name',
        'amount',
        'status',
        'transaction_ref_number',
        'reason',
        'type',
        'time_date',
        'recipient',
        'currency'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
