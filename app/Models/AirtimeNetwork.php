<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirtimeNetwork extends Model
{
    protected $fillable = ['name', 'service_id', 'logo', 'status'];
}
