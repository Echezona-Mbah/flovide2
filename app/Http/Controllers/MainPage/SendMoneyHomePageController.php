<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SendMoneyHomePageController extends Controller
{
    public function index($slug)
    {
        if (!str_starts_with($slug, 'send-money-to-')) {
            abort(404);
        }

        $country = str_replace('send-money-to-', '', $slug);
        $country = ucwords(str_replace('-', ' ', $country));

        return view('mainpage.send-money', compact('country'));
    }
}
