<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\payments;
use Illuminate\Http\Request;

class AllPaymentController extends Controller
{
        public function index()
    {
        $payments = payments::latest()->paginate(10);
        return view('admin.payments', compact('payments'));
    }

public function show($id)
{
    $payment = payments::with('records')->findOrFail($id);
    $records = $payment->records()->paginate(10);

    return view('admin.paymentrecord', compact('payment', 'records'));
}
}
