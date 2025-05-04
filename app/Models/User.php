<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'country',
        'business_name',
        'registration_number',
        'incorporation_date',
        'business_type',
        'company_url',
        'industry',
        'annual_turnover',
        'street_address',
        'city',
        'trading_address',
        'nature_of_business',
        'trading_street_address',
        'trading_city',
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
        'forgot_password_otp_expires_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
