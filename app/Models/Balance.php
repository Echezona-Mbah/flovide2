<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{

protected $fillable = ['user_id', 'personal_id', 'name', 'currency', 'amount'];

public function user()
{
    return $this->belongsTo(User::class);
}

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
