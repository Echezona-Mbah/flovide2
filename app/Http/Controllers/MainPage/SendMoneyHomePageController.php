<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use App\Traits\CurrencyHelper;
use Illuminate\Http\Request;

class SendMoneyHomePageController extends Controller
{
        use CurrencyHelper;

public function index($slug = null)
{
    $currencies = $this->getAllCurrencies();

    // If slug is given, find currency for that country
    $countryCurrency = null;
    if ($slug) {
        if (!str_starts_with($slug, 'send-money-to-')) {
            abort(404);
        }

        $countrySlug = str_replace('send-money-to-', '', $slug);

        foreach ($currencies as $code => $data) {
            if ($data['country'] === $countrySlug) {
                $countryCurrency = $data;
                break;
            }
        }

        if (!$countryCurrency) {
            abort(404);
        }
    }
//  dd($countryCurrency);
    return view('mainpage.send-money', [
        'currencies' => $currencies,
        'countryCurrency' => $countryCurrency ?? null
    ]);
}


}
