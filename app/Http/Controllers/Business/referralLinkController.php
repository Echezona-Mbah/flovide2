<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class referralLinkController extends Controller
{
    //
    public function index()
    {
        $referralLink = Auth::user()->referral_link;
        return view('business.referral', compact('referralLink'));
    }
}
