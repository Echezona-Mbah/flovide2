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
    // Fetch all exchange rates from DB
    $exchangeRates = ExchangeRate::all();

    // Build currencies array from database
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

    // 🔍 Find selected country from slug
    $countryCurrency = null;

    if ($slug) {

        if (!str_starts_with($slug, 'send-money-to-')) {
            abort(404);
        }

        $countrySlug = str_replace('send-money-to-', '', $slug);

        foreach ($currencies as $data) {
            if ($data['country'] === $countrySlug) {
                $countryCurrency = $data;
                break;
            }
        }

        if (!$countryCurrency) {
            abort(404);
        }
    }

    return view('mainpage.send-money', [
        'currencies' => $currencies,
        'countryCurrency' => $countryCurrency
    ]);
}



}
