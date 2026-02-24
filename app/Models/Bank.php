<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
 protected $fillable = [
    'name',
    'country_iso',
    'bank_code',
    'sort_code'
];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function country()
    // {
    //     return $this->belongsTo(Countries::class,'country_id');
    // }

    public function country()
    {
        return $this->belongsTo(CountryRule::class,'country_iso','country_iso');
    }                
}
