<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMembers extends Model
{
      protected $fillable = [
        'user_id',
        'owner_id',
        'email',
        'role',
        'permissions',
        'status',
        'invite_token',
        'invite_token_expires_at',
        'invite_token_used_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

public function userOwner()
{
    return $this->belongsTo(User::class, 'owner_id');
}


    protected $casts = [
    'invite_token_expires_at' => 'datetime',
    'invite_token_used_at'    => 'datetime',
];
    protected $table = 'team_members';
    protected $primaryKey ='id';
}
