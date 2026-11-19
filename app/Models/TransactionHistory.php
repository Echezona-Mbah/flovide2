<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'fee_balance_id',
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
        'security_answer',
        'promo_code',
        'promo_code_id',
        'promo_fee_waived',
        'api_source',

    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'api_source' => 'boolean',
    ];

    public function getSourceLabelAttribute(): string
    {
        return $this->api_source ? 'API' : 'Normal';
    }

    public function getDisplayTypeAttribute(): string
    {
        $type = strtolower((string) $this->type);
        $method = strtolower((string) $this->method);
        $transactionType = strtolower((string) $this->transaction_type);

        if ($type === 'swap' || $method === 'swap' || $transactionType === 'swap') {
            return 'Swap';
        }

        if (in_array($type, ['payout', 'withdrawal'], true)
            || in_array($method, ['payout', 'withdrawal'], true)
            || $transactionType === 'payout') {
            return 'Payout';
        }

        if ($this->api_source
            && in_array($type, ['credit', 'collection'], true)
            && in_array($method, ['credit', 'collection'], true)
            && $transactionType === 'payment') {
            return 'Collection';
        }

        if ($type !== '') {
            return ucfirst($type);
        }

        if ($method !== '') {
            return ucfirst($method);
        }

        return $transactionType !== '' ? ucfirst($transactionType) : 'Unknown';
    }

    protected static function booted(): void
    {
        static::updated(function (TransactionHistory $transaction) {
            if (! $transaction->wasChanged('status') || ! $transaction->api_source) {
                return;
            }

            $status = strtolower((string) $transaction->status);
            $type = in_array(strtolower((string) $transaction->type), ['credit', 'collection'], true)
                ? 'COLLECTION'
                : 'PAYOUT';
            $mode = $transaction->mode ?: 'live';
            $transactionId = $transaction->id;
            $event = match ($status) {
                'success', 'successful' => 'transaction.success',
                'failed', 'declined' => 'transaction.failed',
                'pending' => 'transaction.pending',
                default => 'transaction.status_updated',
            };

            DB::afterCommit(function () use ($transactionId, $type, $mode, $event) {
                $freshTransaction = TransactionHistory::find($transactionId);
                if ($freshTransaction) {
                    app(\App\Services\BusinessApiWebhookService::class)
                        ->dispatch($freshTransaction, $type, $mode, $event);
                }
            });
        });
    }

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
