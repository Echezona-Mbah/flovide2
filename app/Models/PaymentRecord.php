<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentRecord extends Model
{
    //
    use HasFactory;

    protected $table = 'payment_records';

    protected $fillable = [
        'payment_id',
        'personal_id',
        'name',
        'email',
        'phone',
        'amount',
        'currency',
        'status',
        'reference',
    ];

     /**
     * Relationship: A payment record belongs to a payment
     */
    public function payment()
    {
        return $this->belongsTo(payments::class, 'payment_id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }
}
