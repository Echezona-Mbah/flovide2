<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Remita extends Model
{
     use HasFactory, SoftDeletes;

    protected $table = 'remita';
    protected $fillable = [
        'user_id',
        'cover_image',
        'title',
        'amount',
        'rrr',
        'service_type',
        'subaccount_id',
        'subaccount',
        'subaccount_name',
        'subaccount_number',
        'percentage',
        'currency',
        'visibility',
    ];


    public function subaccount()
    {
        return $this->belongsTo(Subaccount::class);
    }
}
