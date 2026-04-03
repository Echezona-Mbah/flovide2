<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Traits\CurrencyHelper;

class businessController extends Controller

{
    use CurrencyHelper;

   // public function business(){
   //    $currencies = $this->getAllCurrencies();
   //  return view('mainpage.business',['currencies' => $currencies]);
   // }

     public function business()
    {
        $currencies = Currency::select('code', 'name', 'symbol', 'country_code')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($c) {
                return [
                    $c->code => [
                        'country' => strtolower($c->name),
                        'countrycode' => strtolower($c->country_code),
                        'symbol' => $c->symbol,
                    ]
                ];
            });

        return view('mainpage.business', ['currencies' => $currencies]);
    }




}
