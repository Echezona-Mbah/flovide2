<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class donations extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'donations'; 
    
     /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'personal_id',
        'cover_image',
        'title',
        'donation_reference',
        'amount',
        'currency',
        'visibility',
        'page_link',
        'percentage',
        'subaccount_id',
        'subaccount',
        'subaccount_name',
        'subaccount_number',
    ];

    /**
     * Relationship: A donation belongs to a personal and user.
     */
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function records()
    {
        return $this->hasMany(DonationRecord::class, 'donation_id');
    }
}
