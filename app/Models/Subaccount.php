<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subaccount extends Model
{
    protected $fillable = [
        'user_id',
        'account_number',
        'account_name',
        'bank_name',
        'bank_country',
        'default'
    ];

    protected $casts = [
        'account_number' => 'encrypted',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
