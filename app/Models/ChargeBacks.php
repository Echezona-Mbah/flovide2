<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargeBacks extends Model
{
       protected $fillable = [
        'user_id',
        'name',
        'amount',
        'currency',
        'deadline',
        'status',
        'transaction_reference',
        'reason',
        'bank_email',
        'payment_method',
        'evidence_path',
        'note',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function country()
    {
        return $this->belongsTo(Countries::class,'country_id');
    }

    
    protected $table = 'charge_backs';
    protected $primaryKey ='id';
}
