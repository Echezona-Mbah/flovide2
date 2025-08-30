<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RemitaPayment extends Model
{
    //
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'remita_payments';
    protected $fillable = [
        'remita_id',
        'name',
        'email',
        'transaction_reference',
        'amount_paid',
        'currency',
        'channel',
        'status',
        'response_code',
        'response_message',
        'paid_at',
    ];

    /**
     * Relationship: A payment belongs to a Remita record.
     */
    public function remita()
    {
        return $this->belongsTo(Remita::class);
    }
    
}
