<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\CurrencyHelper;


class personalController extends Controller
{
            use CurrencyHelper;

    public function personal(){
            $currencies = $this->getAllCurrencies();

        return view('mainpage.personal',['currencies' => $currencies]);
    }
}
