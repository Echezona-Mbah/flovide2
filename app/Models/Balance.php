<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Balance extends Model
{

    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'personal_id',
        'name',
        'currency',
        'amount',
        'mode',
        'virtual_account_number',
        'virtual_account_name',
        'virtual_account_bank',
        'fidelty_process_id',
        'is_locked',
        'locked_reason',
        'locked_at',
        'locked_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function scopeTestMode($query)
    {
        return $query->where('mode', 'test');
    }

    public function scopeLiveMode($query)
    {
        return $query->where('mode', 'live');
    }

    protected $casts = [
    'is_locked' => 'boolean',
    'locked_at' => 'datetime',
    ];

    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }
}
