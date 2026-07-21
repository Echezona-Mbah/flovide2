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
        'linkedin_profile',
        'failed_attempts',
        'locked_until',
    ];

    protected $hidden = [
        'password'
    ];
    protected $casts = [
        'locked_until' => 'datetime',
    ];

    // Admins can create many blog posts
    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    // Role helpers
    public function isSuperAdmin(): bool
    {
        return $this->role === 'Super Admin';
    }

    public function isEditor(): bool
    {
        return $this->role === 'Editor';
    }

    public function isContentManager(): bool
    {
        return $this->role === 'Content Manager';
    }

    // Permissions
    public function hasPermission(string $permission): bool
{
    $permissions = config('admin_permissions.' . $this->role, []);

    return in_array('*', $permissions, true) || in_array($permission, $permissions, true);
}

}
