<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_name',
        'email',
        'phone',
        'bank',
        'country_id',
        'account_number',
        'account_name',
        'user_id',
        'recipient_id',
        'country',
        'alias',
        'type',
        'currency',
        'default_reference',
        'sort_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function country()
    {
        return $this->belongsTo(Countries::class,'country_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class,'name');
    }
}
