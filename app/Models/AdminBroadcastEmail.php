<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminBroadcastEmail extends Model
{
    protected $table = 'admin_broadcast_emails';

    protected $fillable = [
        'user_id',
        'personal_id',
        'recipient_email',
        'subject',
        'message',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }
}
