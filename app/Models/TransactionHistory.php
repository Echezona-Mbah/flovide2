<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
       use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

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
        'transfer_method',
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
        'bank_code',
        'recipient_bank_currency',
        'exchange_rate',
        'single_rate',
        'created_at_external',
        'card_number',
        'expiry_month',
        'expiry_year',
        'cvv',
        'payment_provider',
        'exchange_rate',
        'recipient_amount',
        'total_amount',
        'interac_email',
        'interac_first_name',
        'interac_last_name',
        'mode',

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

    public function scopeTestMode($query)
    {
        return $query->where('mode', 'test');
    }

    public function scopeLiveMode($query)
    {
        return $query->where('mode', 'live');
    }
}
