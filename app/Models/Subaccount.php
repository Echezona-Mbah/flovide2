<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subaccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'personal_id',
        'account_type',
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
        'recipient_id',
        'default',
    ];

    // protected $casts = [
    //     'account_number' => 'encrypted',
    //     'bic' => 'encrypted',
    //     'iban' => 'encrypted',
    // ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
