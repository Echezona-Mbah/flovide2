<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLoginLog extends Model
{
    protected $fillable = [
        'admin_id',
        'email',
        'ip_address',
        'device',
        'success',
        'attempted_at',
        'country',
        'city',
        'latitude',
        'longitude'
    ];
}