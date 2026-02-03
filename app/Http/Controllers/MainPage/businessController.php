<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\CurrencyHelper;

class businessController extends Controller

{
    use CurrencyHelper;

   public function business(){
      $currencies = $this->getAllCurrencies();
    return view('mainpage.business',['currencies' => $currencies]);
   }
}
