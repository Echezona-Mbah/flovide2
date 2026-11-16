<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessWebhookLog extends Model
{
    use HasUuids;

    protected $table = 'business_webhook_logs';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'business_id',
        'business_transaction_id',
        'business_crypto_wallet_transaction_id',
        'type',
        'mode',
        'webhook_url',
        'payload',
        'response',
        'status',
        'delivery_status',
    ];

    protected $casts = [
        'payload' => 'array',
        'response' => 'array',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    public function businessTransaction(): BelongsTo
    {
        return $this->belongsTo(TransactionHistory::class, 'business_transaction_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(BusinessWebhookLogHistory::class, 'webhook_log_id')->orderBy('created_at');
    }
}
