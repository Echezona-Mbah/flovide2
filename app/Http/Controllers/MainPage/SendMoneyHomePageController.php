<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use App\Traits\CurrencyHelper;
use Illuminate\Http\Request;
use App\Models\ExchangeRate;
use Illuminate\Support\Str;


class SendMoneyHomePageController extends Controller
{
        use CurrencyHelper;


public function index($slug = null)
{
    $exchangeRates = ExchangeRate::all();

    $currencies = $exchangeRates->mapWithKeys(function ($rate) {

        $countrySlug = Str::slug($rate->country_name);

        return [
            $rate->currency_code => [
                'symbol' => $rate->currency_symbol,
                'countrycode' => strtolower(substr($rate->currency_code, 0, 2)),
                'country' => $countrySlug,
                'rate' => $rate->rate,
                'country_name' => $rate->country_name
            ]
        ];
    });

    // Default = first currency
    $countryCurrency = $currencies->first(); // default
    if ($slug) {
        if (!str_starts_with($slug, 'send-money-to-')) {
            abort(404);
        }

        $countrySlug = str_replace('send-money-to-', '', $slug);

        foreach ($currencies as $code => $data) {
            if ($data['country'] === $countrySlug) {
                $countryCurrency = $data;
                $countryCurrency['currency_code'] = $code; // <-- add this
                break;
            }
        }
    }


    return view('mainpage.send-money', compact('currencies', 'countryCurrency'));
}



}
