<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillPayment;
use Illuminate\Http\Request;

class AllBillPaymentController extends Controller
{
   public function index(Request $request)
{
    $allbillpayment = BillPayment::latest()->paginate(10); // 10 per page

    return view('admin.billpayment', compact('allbillpayment'));
}

}
