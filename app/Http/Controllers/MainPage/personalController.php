<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;

class personalController extends Controller
{
    public function personal()
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

        return view('mainpage.personal', ['currencies' => $currencies]);
    }
}
