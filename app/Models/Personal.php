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
        'referral_code',
        'referral_link',
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
        'login_otp',
        'login_otp_expires_at',
        'identity_verification_status',
        'selfie_verification_status',
        'profile_picture',
        'device_token',
        'nin',
        'nin_status',
        'date_of_birth',
    ];



     protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'login_otp_expires_at' => 'datetime',
    ];

    public function balances()
    {
        return $this->hasMany(Balance::class, 'personal_id');
    }

    public function isFullyVerified(): bool
{
    return
        $this->identity_verification_status === 'confirmed' &&
        $this->selfie_verification_status === 'confirmed' &&
        $this->nin_status === 'confirmed';
        // $this->valid_id_status === 'confirmed' &&
        // $this->utility_bill_status === 'confirmed' &&
        // (
        //     $this->countries_id !== 'Nigeria' ||
        //     $this->bvn_status === 'yes'
        // );
}

public function complianceStatus($tokenResponse = null)
{
    $user = auth('personal-api')->user();

    return [
        'identity_verification' => $this->identity_verification_status,
        'selfie_verification' => $this->selfie_verification_status,
        'nin_status' => $this->nin_status,
        'token' => $tokenResponse['token'] ?? null,
        'userId' => $tokenResponse['userId'] ?? null,
    ];
}

}
