<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    //custom table name
    protected $table = 'bank_accounts';

    protected $fillable = [
        'user_id',
        'account_name',
        'account_number',
        'bank_country',
        'bank_name',
    ];

    protected $casts = [
        'account_number' => 'encrypted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
