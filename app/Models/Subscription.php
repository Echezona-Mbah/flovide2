<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'title', 'cover_image', 'subscription_interval',
        'amount', 'currency', 'visibility', 'payment_reference', 'subaccount_id',
    'subaccount',
    'subaccount_name',
    'subaccount_number',
    'percentage',

    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

       public function subscription()
    {
        return $this->hasMany(SubscriptionRecord::class, 'subscription_id');
    }

    protected $table ='subscriptions';

    protected $primaryKey ='id';
}
