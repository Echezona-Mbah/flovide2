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
        'is_locked',
        'country',
        'countries_id',
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
        'date_of_birth',
        'email',
        'password',
        'email_verification_otp',
        'email_verification_otp_expires_at',
        'bvn',
        'typeofuser',
        'referral_code',
        'referral_link',
        'referred_by',
        'referral_bonus_notified_at',
        'email_verified_status',
        'email_verification_attempts',
        'forget_verification_otp',
        'forgot_password_otp_expires_at',
        'currency',
        'balance',
        'default_currency',
        'default_currency_balance',
        'bvn_status',
        'cac_certificate',
        'cac_status',
        'valid_id',
        'valid_id_status',
        'tin',
        'tin_status',
        'utility_bill',
        'utility_bill_status',
        'firstname',
        'lastname',
        'profile_picture',
        'deletestatus',
        'subscription_status',
        'reset_token',
        'reset_token_expires_at',
        'business_phone',
        'person_phone',
        'login_otp',
        'login_otp_expires_at',
        'sumsub_applicant_id',
        'proof_of_identity',
        'proof_of_identity_status',
        'ownership_document',
        'ownership_status',
        'organisational_chart',
        'organisational_chart_status',
        'register_of_directors',
        'register_of_directors_status',
        'formation_document',
        'formation_document_status',
        'identity_verification_status',
        'selfie_verification_status',
        'secret_key',
        'public_key',
        'ip_whitelist',
        'callback_url',
        'webhook_url',
        'nin',
        'nin_status',
        'device_token',
        'blaaiz_id'

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
        'addresses' => 'array',
        'login_otp_expires_at' => 'datetime',
        'live_ip_whitelist' => 'array',
        'test_ip_whitelist' => 'array',
    ];
}


    public function balances()
{
    return $this->hasMany(Balance::class);
}

public function webhookSetting()
{
    return $this->hasOne(WebhookSetting::class);
}

public function isFullyVerified(): bool
{
    $baseChecks =
        $this->cac_status === 'confirmed' &&
        $this->valid_id_status === 'confirmed' &&
        $this->tin_status === 'confirmed' &&
        $this->utility_bill_status === 'confirmed' &&
        $this->proof_of_identity_status === 'confirmed' &&
        $this->ownership_status === 'confirmed' &&
        $this->organisational_chart_status === 'confirmed' &&
        $this->register_of_directors_status === 'confirmed' &&
        $this->formation_document_status === 'confirmed' &&
        $this->identity_verification_status === 'confirmed' &&
        $this->selfie_verification_status === 'confirmed' &&
        $this->nin_status === 'confirmed';  // NIN added

        $nigeriaCheck = (
            $this->countries_id !== 'Nigeria' ||
            $this->bvn_status === 'confirmed'
        );

    return $baseChecks && $nigeriaCheck;
}


public function complianceStatus($tokenResponse = null): array
{
    return [
        'cac' => $this->cac_status,
        'valid_id' => $this->valid_id_status,
        'tin' => $this->tin_status,
        'utility_bill' => $this->utility_bill_status,
        'proof_of_identity' => $this->proof_of_identity_status,
        'ownership' => $this->ownership_status,
        'organisational_chart' => $this->organisational_chart_status,
        'register_of_directors' => $this->register_of_directors_status,
        'formation_document' => $this->formation_document_status,
        'identity_verification' => $this->identity_verification_status,
        'selfie_verification' => $this->selfie_verification_status,
        'nin' => $this->nin_status,
        'bvn_required' => $this->countries_id === 'Nigeria' ? 'yes' : 'no',
        'bvn_verified' => $this->bvn_status,
        'fully_verified' => $this->isFullyVerified() ? 'yes' : 'no',
        'token' => $tokenResponse['token'] ?? null,
        'userId' => $this->id,
    ];
}

// All currency fee rows
public function currencyFees()
{
    return $this->hasMany(UserCurrencyFee::class);
}

// Single currency fee row
public function currencyFee(string $currency): ?UserCurrencyFee
{
    return $this->currencyFees()
                ->where('currency', strtoupper($currency))
                ->first();
}

// Get or create a currency row
public function currencyFeeOrCreate(string $currency): UserCurrencyFee
{
    return UserCurrencyFee::firstOrCreate(
        ['user_id' => $this->id, 'currency' => strtoupper($currency)],
        [
            'collection_enabled' => false,
            'collection_balance' => 0,
            'collection_percent' => 0,
            'collection_fixed'   => 0,
            'payout_enabled'     => false,
            'payout_balance'     => 0,
            'payout_percent'     => 0,
            'payout_fixed'       => 0,
        ]
    );
}







}
