<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // important for login
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Personal extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'personals';
        protected $fillable = [
        'country',
        'street_address',
        'city',
        'state',
        'email',
        'password',
        'email_verification_otp',
        'email_verification_otp_expires_at',
        'bvn', 
        'typeofuser',
        'email_verified_status',
        'email_verification_attempts',
        'forget_verification_otp',
        'forgot_password_otp_expires_at',
        'currency',
        'balance',
        'default_currency',
        'default_currency_balance',
        'bvn_status',
        'firstname',
        'lastname',
        'subscription_status',
        'reset_token',
        'reset_token_expires_at',
        'business_phone',
        'person_phone',
        'deletestatus',
    ];



     protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function balances()
    {
        return $this->hasMany(Balance::class, 'personal_id');
    }

}
