<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficia extends Model
{
    protected $fillable = [
        'bank',
        'country_id',
        'account_number',
        'account_name',
        'recipient_id',
        'country',
        'alias',
        'type',
        'currency',
        'default_reference',
        'sort_code',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function country()
    {
        return $this->belongsTo(Countries::class,'country_id');
    }

    
    protected $table ='beneficias';

    protected $primaryKey ='id';
}
