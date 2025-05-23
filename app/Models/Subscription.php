<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'title', 'cover_image', 'subscription_interval',
        'amount', 'currency', 'visibility',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $table ='subscriptions';

    protected $primaryKey ='id';
}
