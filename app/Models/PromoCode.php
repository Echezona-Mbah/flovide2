<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'code',
        'owner_type',
        'owner_id',
        'reward_type',
        'reward_value',
        'status',
        'created_by_admin_id',
    ];

    public function redemptions()
    {
        return $this->hasMany(PromoCodeRedemption::class);
    }

    public function ownerModel(): ?object
    {
        return $this->owner_type === 'business'
            ? User::find($this->owner_id)
            : Personal::find($this->owner_id);
    }

    public function calculateReward(float $transactionAmount): float
    {
        if ($this->reward_type === 'percent') {
            return round($transactionAmount * ((float) $this->reward_value / 100), 2);
        }

        return round((float) $this->reward_value, 2);
    }
}