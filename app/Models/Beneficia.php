<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficia extends Model
{
    protected $fillable = [
        // Bank & account details
        'bank',                 // bank nickname
        'account_number',        // account number
        'account_name',          // account holder
        'currency',              // currency
        'sort_code',             // bank code
        'bank_code',
        'swift_bic',             // SWIFT/BICy
        'alias',                 // same as nickname
        'default_reference',     // default reference

        // Beneficiary references
        'recipient_id',          // IFX beneficiary id
        'account_id',            // IFX account id
        'unique_reference',      // generated unique reference
        'customer_reference',    // generated customer reference

        // Beneficiary type & name
        'type',                  // individual or corporate
        'beneficiary_name',      // individual: first+last, corporate: name
        'first_names',           // individual only
        'last_name',             // individual only
        'name',                  // corporate only

        // Address
        'address_line1',
        'address_line2',
        'building_name',
        'city',
        'state',
        'postcode',
        'country',

        // User & personal
        'user_id',
        'personal_id',

        // Optional: extra info if needed
        'email',
        'phone',
        'transfer_method',
    ];

    protected $table = 'beneficias';
    protected $primaryKey = 'id';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function country()
    {
        return $this->belongsTo(Countries::class,'country_id');
    }
}
