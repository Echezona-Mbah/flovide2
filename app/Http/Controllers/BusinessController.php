<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BusinessController extends Controller
{
    //
    public function payouts()
    {
        return view('business.payouts');
    }
    public function subaccount()
    {
        return view('business.subaccount');
    }
}
