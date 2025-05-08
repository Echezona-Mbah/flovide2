<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;

    // custom table name
    protected $table = 'transactions_history';

    protected $fillable = [
        'user_id',
        'type',
        'sender',
        'recipient',
        'amount',
        'status',
        'reference'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
