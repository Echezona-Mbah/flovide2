<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Customer;
use App\Models\InvoiceItem;

class Invoices extends Model
{
   use HasFactory;

    protected $fillable = [
        'user_id', 
        'invoice_number', 
        'billed_to', 
        'address', 
        'due_date', 
        'currency', 
        'note',
        'amount',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
