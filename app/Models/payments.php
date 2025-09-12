<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class payments extends Model
{
    //

    use HasFactory, SoftDeletes;
    protected $table = 'payments'; 

     /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'personal_id',
        'cover_image',
        'title',
        'payment_reference',
        'amount',
        'currency',
        'visibility',
    ];

    /**
     * Relationship: A payment belongs to a personal.
     */
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
    public function records()
    {
        return $this->hasMany(PaymentRecord::class, 'payment_id');
    }

}
