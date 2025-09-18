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
        'personal_id',
        'type',
        'sender',
        'sender_id',
        'recipient',
        'recipient_id',
        'method',
        'amount',
        'currency',
        'status',
        'reference',
        'fees',
        'to_currency',
        'balance_id',
        'virtual_account_id',
        'order_id',
        'payment_reference',
        'failure_reason',
        'transaction_type',
        'payment_method',
        'beneficias_id',
        'recipient_country',
        'recipient_default_reference',
        'recipient_alias',
        'recipient_type',
        'recipient_created_at',
        'recipient_account_name',
        'recipient_sort_code',
        'recipient_account_number',
        'recipient_bank_name',
        'recipient_bank_currency',
        'exchange_rate',
        'single_rate',
        'created_at_external',
        'card_number',
        'expiry_month',
        'expiry_year',
        'cvv',

    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
