<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use App\Models\InvoiceItem;

class Invoices extends Model
{
   use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 
        'invoice_number',
        'tracking_code',
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
