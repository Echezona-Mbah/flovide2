<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'name',
        'email',
        'phone',
        'amount',
        'currency',
        'status',
        'reference',
        'start_date',
        'end_date',
        'is_expired',


    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
