<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Model
{
    //
    
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'item_name', 'qty', 'rate', 'total', 'rate_enabled'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoices::class);
    }
}
