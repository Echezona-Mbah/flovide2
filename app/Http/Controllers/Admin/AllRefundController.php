<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;

class AllRefundController extends Controller
{
             public function index(Request $request)
    {

        $refunds = Refund::orderBy('created_at', 'desc')->paginate(4);

        return view('admin.refunds', compact('refunds'));

    }
}
