<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
         'phone',
        'role',
        'skills',
        'experience',
        'profile_picture', 
        'portfolio_links',
        'social_media_accounts',
        'languages_spoken', 
        'emergency_contact',
        'linkedin_profile'
    ];

    protected $hidden = [
        'password'
    ];
}
