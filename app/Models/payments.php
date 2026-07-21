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
        'user_id',
        'personal_id',
        'cover_image',
        'title',
        'amount',
        'payment_reference',
        'subaccount_id',
        'subaccount',
        'subaccount_name',
        'subaccount_number',
        'percentage',
        'currency',
        'visibility',
        'page_link'
    ];

    /**
     * Relationship: A payment belongs to a personal.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
    public function records()
    {
        return $this->hasMany(PaymentRecord::class, 'payment_id');
    }

}
