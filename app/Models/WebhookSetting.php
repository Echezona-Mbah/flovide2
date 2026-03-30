<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookSetting extends Model
{
    protected $fillable = [
        'user_id',
        'live_secret_key',
        'live_public_key',
        'live_ip_whitelist',
        'live_callback_url',
        'live_webhook_url',
        'test_secret_key',
        'test_public_key',
        'test_ip_whitelist',
        'test_callback_url',
        'test_webhook_url',
    ];

    protected $casts = [
        'live_ip_whitelist' => 'array',
        'test_ip_whitelist' => 'array',
    ];
}
