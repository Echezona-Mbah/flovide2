<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InteracAutoDeposit extends Model
{
    protected $table = 'interac_autodeposits'; // ← match the migration exactly

    protected $fillable = [
        'user_id',
        'balance_id',
        'email',
        'status',
        'added_at',
    ];

    protected $casts = [
        'added_at' => 'datetime',
    ];

    public function balance()
    {
        return $this->belongsTo(Balance::class, 'balance_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}