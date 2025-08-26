<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{

<<<<<<< HEAD
protected $fillable = ['user_id', 'name', 'currency', 'amount'];


  public function user()
=======
protected $fillable = ['user_id', 'personal_id', 'name', 'currency', 'amount'];

public function user()
>>>>>>> 4214aa702807e3d23954c2ccb8c80301d29082d1
{
    return $this->belongsTo(User::class);
}

<<<<<<< HEAD
=======
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
>>>>>>> 4214aa702807e3d23954c2ccb8c80301d29082d1
}
