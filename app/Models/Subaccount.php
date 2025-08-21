<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subaccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'account_number',
        'account_name',
        'bank_name',
        'bank_country',
        'currency',
        'bic',
        'iban',
        'city',
        'state',
        'zipcode',
        'recipient_address',
        'type',
        'default',
    ];

    protected $casts = [
        'account_number' => 'encrypted',
        'bic' => 'encrypted',
        'iban' => 'encrypted',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
