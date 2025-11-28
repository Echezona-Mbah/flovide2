<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    //
    protected $fillable = [
        'user_id',
        'personal_id',
        'ip_address',
        'device',
        'browser',
        'os',
        'location',
        'login_time'
    ];
}

