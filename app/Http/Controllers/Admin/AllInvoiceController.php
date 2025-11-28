<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoices;
use Illuminate\Http\Request;

class AllInvoiceController extends Controller
{
       public function index()
    {
        $invoices = Invoices::latest()->paginate(10);
        return view('admin.invoice', compact('invoices'));
    }

public function show($id)
{
    // Get the donation with its records
    $invoice = Invoices::with('items')->findOrFail($id);
    // If you want pagination for the records
    $records = $invoice->items()->paginate(10);

    return view('admin.invoiceitems', compact('invoice', 'records'));
}

}
