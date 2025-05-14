<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Invoices extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'amount',
        'status',
    ];

    public function Customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
