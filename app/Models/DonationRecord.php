<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DonationRecord extends Model
{
    //
    use HasFactory;

    protected $table = 'donation_records';

    protected $fillable = [
        'donation_id',
        'personal_id',
        'name',
        'email',
        'phone',
        'amount',
        'currency',
        'status',
        'reference',
    ];

     /**
     * Relationship: A donation record belongs to a donation
     */
    public function donation()
    {
        return $this->belongsTo(donations::class, 'donation_id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }
}
