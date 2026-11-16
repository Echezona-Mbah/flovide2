<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessWebhookLogHistory extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'webhook_log_id',
        'metadata',
        'headers',
        'http_status',
        'response',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'headers' => 'array',
        'response' => 'array',
        'created_at' => 'datetime',
    ];

    public function webhookLog(): BelongsTo
    {
        return $this->belongsTo(BusinessWebhookLog::class, 'webhook_log_id');
    }
}
