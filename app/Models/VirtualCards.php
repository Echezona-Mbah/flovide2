<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualCards extends Model
{
        protected $fillable = [
        'user_id',
<<<<<<< HEAD
=======
        'personal_id',
>>>>>>> 4214aa702807e3d23954c2ccb8c80301d29082d1
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
<<<<<<< HEAD

=======
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
>>>>>>> 4214aa702807e3d23954c2ccb8c80301d29082d1
    public function country()
    {
        return $this->belongsTo(Countries::class,'country_id');
    }

    
    protected $table = 'virtual_cards';
    protected $primaryKey ='id';
}
