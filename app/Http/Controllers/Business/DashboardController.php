<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Countries;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\TeamMembers;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Traits\CurrencyHelper;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
  use CurrencyHelper;
 public function create()
    {
        $countries = Countries::all();

        $user = auth()->user();
        $team = TeamMembers::where('user_id', $user->id)->first();
        $ownerId = $team ? $team->owner_id : $user->id;

        $transactions = TransactionHistory::where('user_id', $ownerId)
            ->latest('created_at')
            ->take(5)
            ->get();

        $balances = Balance::where('user_id', $ownerId)->get();

        foreach ($balances as $balance) {
            $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
        }

        // ✅ currencies from currencies table
        $allCurrencies = Currency::all()->map(function ($c) {
            $countryCode = strtolower($c->country_code ?? substr($c->code, 0, 2));

            return [
                'country_name' => $c->name,
                'code' => $c->code,
                'symbol' => $c->symbol ?? '',
                'flag' => "https://flagcdn.com/w20/{$countryCode}.png",
            ];
        })->values()->all();

        return view('dashboard', compact(
            'countries',
            'transactions',
            'balances',
            'allCurrencies'
        ));
    }

    public function getExchangeRates(Request $request)
    {
        $from = strtoupper($request->input('from_currency'));
        $to   = strtoupper($request->input('to_currency'));
        $amount = (float) $request->input('amount', 1);

        try {
            $rate = ExchangeRate::whereHas('fromCurrency', function ($q) use ($from) {
                    $q->where('code', $from);
                })
                ->whereHas('toCurrency', function ($q) use ($to) {
                    $q->where('code', $to);
                })
                ->first();

            if (!$rate) {
                throw new \Exception("Rate not found");
            }

            $converted = $amount * $rate->rate;

            return response()->json([
                'success' => true,
                'message' => 'Exchange rate fetched',
                'code' => 'EXCHANGE_RATE_FETCHED',
                'data' => [
                    'converted' => $converted,
                    'transfer_fee' => $rate->transfer_fee,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 'RATE_NOT_FOUND',
                'data' => null
            ], 400);
        }
    }








    public function fetchBalances()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OHENTPAY_API_KEY'),
            'Accept' => 'application/json',
        ])->get(env('OHENTPAY_BASE_URL') . '/balances');
    
        if ($response->successful()) {
            return $response->json();
        }
    
        return [
            'error' => true,
            'message' => $response->body(),
            'status' => $response->status(),
        ];
    }


    public function getBalances()
    {
        $data = $this->fetchBalances();
    
        if (isset($data['error']) && $data['error']) {
            return view('balances', ['balances' => []]);
        }
    
        return view('balances', ['balances' => $data['balances'] ?? []]);
    }
    
    // private function getCountryCodeFromCurrency($currency)
    // {
    //     $map = [
    //         'NGN' => ['symbol' => '₦', 'country' => 'ng'],
    //         'USD' => ['symbol' => '$', 'country' => 'us'],
    //         'KES' => ['symbol' => 'KSh', 'country' => 'ke'],
    //         'GHS' => ['symbol' => '₵', 'country' => 'gh'],
    //         'ZAR' => ['symbol' => 'R', 'country' => 'za'],
    //         'GBP' => ['symbol' => '£', 'country' => 'gb'],
    //         'EUR' => ['symbol' => '€', 'country' => 'eu'],
    //         'CAD' => ['symbol' => 'C$', 'country' => 'ca'],
    //         'CZK' => ['symbol' => 'Kč', 'country' => 'cz'],
    //         'DKK' => ['symbol' => 'kr', 'country' => 'dk'],
    //         'AUD' => ['symbol' => 'A$', 'country' => 'au'],
    //         'SEK' => ['symbol' => 'kr', 'country' => 'se'],
    //         'RON' => ['symbol' => 'lei', 'country' => 'ro'],
    //         'PLN' => ['symbol' => 'zł', 'country' => 'pl'],
    //         'CHF' => ['symbol' => 'CHF', 'country' => 'ch'],
    //         'HUF' => ['symbol' => 'Ft', 'country' => 'hu'],
    //         'NOK' => ['symbol' => 'kr', 'country' => 'no'],
    //     ];
    
    //     return $map[strtoupper($currency)] ?? ['symbol' => '', 'country' => 'us'];
    // }
    
}
