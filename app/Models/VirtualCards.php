<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualCards extends Model
{
        protected $fillable = [
        'user_id',
        'personal_id',
        'card_provider',
        'card_id',
        'card_type',
        'currency',
        'amount',
        'masked_pan',
        'cvv',
        'card_number',
        'expiry_month',
        'expiry_year',
        'balance',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
    public function country()
    {
        return $this->belongsTo(Countries::class,'country_id');
    }

    
    protected $table = 'virtual_cards';
    protected $primaryKey ='id';
}
