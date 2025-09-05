<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory;
    use SoftDeletes;

    //custom table name
    protected $table = 'bank_accounts';

    protected $fillable = [
        'user_id',
        'personal_id',
        'account_type',
        'account_name',
        'account_number',
        'bank_country',
        'bank_name',
        'currency',
        'bic',
        'iban',
        'city',
        'state',
        'zipcode',
        'recipient_address',
        'type',
    ];
    protected $attributes = [
        'currency' => 'USD',
    ];


    protected $casts = [
        'account_number' => 'encrypted',
        'iban' => 'encrypted',
        'bic' => 'encrypted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
