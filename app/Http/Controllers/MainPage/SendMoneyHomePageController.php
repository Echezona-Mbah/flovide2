<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SendMoneyHomePageController extends Controller
{
    public function index($slug = null)
    {
        // ✅ Base currency for "rate" (adjust if your base is not USD)
        $baseCode = 'USD';

        $currencies = Currency::all()->mapWithKeys(function ($c) use ($baseCode) {
            $countrySlug = Str::slug($c->name);

            // try to find a base -> this currency rate
            $rate = ExchangeRate::whereHas('fromCurrency', fn ($q) => $q->where('code', $baseCode))
                ->whereHas('toCurrency', fn ($q) => $q->where('code', $c->code))
                ->value('rate');

            return [
                $c->code => [
                    'symbol' => $c->symbol ?? '',
                    'countrycode' => strtolower($c->country_code ?? substr($c->code, 0, 2)),
                    'country' => $countrySlug,
                    'rate' => $rate ?? 1, // fallback to 1 to avoid breaking JS
                    'country_name' => $c->name,
                ],
            ];
        });

        // Default = first currency
        $countryCurrency = $currencies->first();

        if ($slug) {
            if (!str_starts_with($slug, 'send-money-to-')) {
                abort(404);
            }

            $countrySlug = str_replace('send-money-to-', '', $slug);

            foreach ($currencies as $code => $data) {
                if ($data['country'] === $countrySlug) {
                    $countryCurrency = $data;
                    $countryCurrency['currency_code'] = $code;
                    break;
                }
            }
        }

        return view('mainpage.send-money', compact('currencies', 'countryCurrency'));
    }
}
