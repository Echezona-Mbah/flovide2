<?php

namespace App\Traits;

use App\Models\ExchangeRate;

trait CurrencyHelper
{
    public function getCountryCodeFromCurrency($currency)
    {
        $map = [
            'NGN' => ['symbol' => '₦', 'country' => 'ng', 'rate' => 1550],
            'USD' => ['symbol' => '$', 'country' => 'us', 'rate' => 1],
            'KES' => ['symbol' => 'KSh', 'country' => 'ke', 'rate' => 130],
            'GHS' => ['symbol' => '₵', 'country' => 'gh', 'rate' => 15],
            'ZAR' => ['symbol' => 'R', 'country' => 'za', 'rate' => 18],
            'GBP' => ['symbol' => '£', 'country' => 'gb', 'rate' => 0.77],
            'EUR' => ['symbol' => '€', 'country' => 'eu', 'rate' => 0.9],
            'CAD' => ['symbol' => 'C$', 'country' => 'ca', 'rate' => 1.35],
            'CZK' => ['symbol' => 'Kč', 'country' => 'cz', 'rate' => 22],
            'DKK' => ['symbol' => 'kr', 'country' => 'dk', 'rate' => 6.9],
            'AUD' => ['symbol' => 'A$', 'country' => 'au', 'rate' => 1.5],
            'SEK' => ['symbol' => 'kr', 'country' => 'se', 'rate' => 10.5],
            'RON' => ['symbol' => 'lei', 'country' => 'ro', 'rate' => 4.6],
            'PLN' => ['symbol' => 'zł', 'country' => 'pl', 'rate' => 4.0],
            'CHF' => ['symbol' => 'CHF', 'country' => 'ch', 'rate' => 0.91],
            'HUF' => ['symbol' => 'Ft', 'country' => 'hu', 'rate' => 355],
            'NOK' => ['symbol' => 'kr', 'country' => 'no', 'rate' => 10.4],
            'INR' => ['symbol' => '₹', 'country' => 'in', 'rate' => 83],
            'JPY' => ['symbol' => '¥', 'country' => 'jp', 'rate' => 157],
            'CNY' => ['symbol' => '¥', 'country' => 'cn', 'rate' => 7.2],
            'BRL' => ['symbol' => 'R$', 'country' => 'br', 'rate' => 5.2],
            'MXN' => ['symbol' => '$', 'country' => 'mx', 'rate' => 18],
            'ARS' => ['symbol' => '$', 'country' => 'ar', 'rate' => 900],
            'SGD' => ['symbol' => 'S$', 'country' => 'sg', 'rate' => 1.35],
            'HKD' => ['symbol' => 'HK$', 'country' => 'hk', 'rate' => 7.8],
            'NZD' => ['symbol' => 'NZ$', 'country' => 'nz', 'rate' => 1.6],
            'AED' => ['symbol' => 'د.إ', 'country' => 'ae', 'rate' => 3.67],
            'SAR' => ['symbol' => '﷼', 'country' => 'sa', 'rate' => 3.75],
            'MYR' => ['symbol' => 'RM', 'country' => 'my', 'rate' => 4.7],
            'THB' => ['symbol' => '฿', 'country' => 'th', 'rate' => 36],
            'IDR' => ['symbol' => 'Rp', 'country' => 'id', 'rate' => 16250],
            'PKR' => ['symbol' => '₨', 'country' => 'pk', 'rate' => 277],
            'EGP' => ['symbol' => '£', 'country' => 'eg', 'rate' => 48],
        ];

        // Generate dynamic rate map
        $rateMap = [];

        foreach ($map as $fromCode => $fromData) {
            foreach ($map as $toCode => $toData) {
                if ($fromCode === $toCode) continue;
                $rateMap["{$fromCode}-{$toCode}"] = $toData['rate'] / $fromData['rate'];
            }
        }


    
        return $map[strtoupper($currency)] ?? ['symbol' => '', 'country' => 'us'];
    }

    // public function getExchangeRateFromMap($from, $to)
    // {
    //     $rates = [
    //         'NGN' => 1550,
    //         'USD' => 1,
    //         'KES' => 130,
    //         'GHS' => 15,
    //         'ZAR' => 18,
    //         'GBP' => 0.77,
    //         'EUR' => 0.9,
    //         'CAD' => 1.3,
    //         'AUD' => 1.5,
    //         'JPY' => 140,
    //         'CNY' => 7.2,
    //         'INR' => 83,
    //         'BRL' => 5.2,
    //         'MXN' => 17,
    //         'AED' => 3.67,
    //         'SAR' => 3.75,
    //         'TRY' => 32,
    //         'RUB' => 91,
    //         'CHF' => 0.88,
    //         'SEK' => 10.8,
    //         'NOK' => 10.6,
    //         'DKK' => 6.7,
    //         'PLN' => 4.1,
    //         'THB' => 35,
    //         'MYR' => 4.7,
    //         'IDR' => 15500,
    //         'PHP' => 58,
    //         'PKR' => 280,
    //         'BDT' => 117,
    //         'EGP' => 47,
    //         'TWD' => 32,
    //         'HKD' => 7.8,
    //         'SGD' => 1.35,
    //         'NZD' => 1.65,
    //     ];

    //     // Transfer fee in each currency
    //     $fees = [
    //         'NGN' => 100,
    //         'USD' => 1,
    //         'KES' => 20,
    //         'GHS' => 5,
    //         'ZAR' => 10,
    //         'GBP' => 0.5,
    //         'EUR' => 0.6,
    //         'CAD' => 1.2,
    //         'AUD' => 1.3,
    //         'JPY' => 120,
    //         'CNY' => 6,
    //         'INR' => 70,
    //         'BRL' => 4,
    //         'MXN' => 15,
    //         'AED' => 3,
    //         'SAR' => 3,
    //         'TRY' => 25,
    //         'RUB' => 80,
    //         'CHF' => 0.7,
    //         'SEK' => 9,
    //         'NOK' => 9,
    //         'DKK' => 5,
    //         'PLN' => 3,
    //         'THB' => 30,
    //         'MYR' => 4,
    //         'IDR' => 14000,
    //         'PHP' => 50,
    //         'PKR' => 250,
    //         'BDT' => 100,
    //         'EGP' => 40,
    //         'TWD' => 30,
    //         'HKD' => 6,
    //         'SGD' => 1.1,
    //         'NZD' => 1.4,
    //     ];

    //     $from = strtoupper($from);
    //     $to = strtoupper($to);

    //     if (!isset($rates[$from]) || !isset($rates[$to])) {
    //         return null;
    //     }

    //     $rate = round($rates[$to] / $rates[$from], 6);
    //     $transferFee = $fees[$to] ?? 0;

    //     return [
    //         'rate' => $rate,
    //         'transfer_fee' => $transferFee,
    //     ];
    // }

    public function getExchangeRateFromMap($from, $to)
{
    $from = strtoupper($from);
    $to = strtoupper($to);

    $fromRate = ExchangeRate::where('currency_code', $from)->first();
    $toRate   = ExchangeRate::where('currency_code', $to)->first();

    if (!$fromRate || !$toRate) {
        return null; // currency not found
    }

    // Calculate exchange rate
    $rate = round($toRate->rate / $fromRate->rate, 6);

    return [
        'rate' => $rate,
        'transfer_fee' => $toRate->transfer_fee,
    ];
}

    public function getAllCurrencies()
    {
        return [
            'NGN' => ['symbol' => '₦', 'countrycode' => 'ng', 'country' => 'nigeria', 'rate' => 1550],
            'USD' => ['symbol' => '$', 'countrycode' => 'us', 'country' => 'united-states', 'rate' => 1],
            'KES' => ['symbol' => 'KSh', 'countrycode' => 'ke', 'country' => 'kenya', 'rate' => 130],
            'GHS' => ['symbol' => '₵', 'countrycode' => 'gh', 'country' => 'ghana', 'rate' => 15],
            'ZAR' => ['symbol' => 'R', 'countrycode' => 'za', 'country' => 'south-africa', 'rate' => 18],
            'GBP' => ['symbol' => '£', 'countrycode' => 'gb', 'country' => 'united-kingdom', 'rate' => 0.77],
            'EUR' => ['symbol' => '€', 'countrycode' => 'eu', 'country' => 'europe', 'rate' => 0.9],
            'CAD' => ['symbol' => 'C$', 'countrycode' => 'ca', 'country' => 'canada', 'rate' => 1.35],
            'CZK' => ['symbol' => 'Kč', 'countrycode' => 'cz', 'country' => 'czech-republic', 'rate' => 22],
            'DKK' => ['symbol' => 'kr', 'countrycode' => 'dk', 'country' => 'denmark', 'rate' => 6.9],
            'AUD' => ['symbol' => 'A$', 'countrycode' => 'au', 'country' => 'australia', 'rate' => 1.5],
            'SEK' => ['symbol' => 'kr', 'countrycode' => 'se', 'country' => 'sweden', 'rate' => 10.5],
            'RON' => ['symbol' => 'lei', 'countrycode' => 'ro', 'country' => 'romania', 'rate' => 4.6],
            'PLN' => ['symbol' => 'zł', 'countrycode' => 'pl', 'country' => 'poland', 'rate' => 4.0],
            'CHF' => ['symbol' => 'CHF', 'countrycode' => 'ch', 'country' => 'switzerland', 'rate' => 0.91],
            'HUF' => ['symbol' => 'Ft', 'countrycode' => 'hu', 'country' => 'hungary', 'rate' => 355],
            'NOK' => ['symbol' => 'kr', 'countrycode' => 'no', 'country' => 'norway', 'rate' => 10.4],
            'INR' => ['symbol' => '₹', 'countrycode' => 'in', 'country' => 'india', 'rate' => 83],
            'JPY' => ['symbol' => '¥', 'countrycode' => 'jp', 'country' => 'japan', 'rate' => 157],
            'CNY' => ['symbol' => '¥', 'countrycode' => 'cn', 'country' => 'china', 'rate' => 7.2],
            'BRL' => ['symbol' => 'R$', 'countrycode' => 'br', 'country' => 'brazil', 'rate' => 5.2],
            'MXN' => ['symbol' => '$', 'countrycode' => 'mx', 'country' => 'mexico', 'rate' => 18],
            'ARS' => ['symbol' => '$', 'countrycode' => 'ar', 'country' => 'argentina', 'rate' => 900],
            'SGD' => ['symbol' => 'S$', 'countrycode' => 'sg', 'country' => 'singapore', 'rate' => 1.35],
            'HKD' => ['symbol' => 'HK$', 'countrycode' => 'hk', 'country' => 'hong-kong', 'rate' => 7.8],
            'NZD' => ['symbol' => 'NZ$', 'countrycode' => 'nz', 'country' => 'new-zealand', 'rate' => 1.6],
            'AED' => ['symbol' => 'د.إ', 'countrycode' => 'ae', 'country' => 'united-arab-emirates', 'rate' => 3.67],
            'SAR' => ['symbol' => '﷼', 'countrycode' => 'sa', 'country' => 'saudi-arabia', 'rate' => 3.75],
            'MYR' => ['symbol' => 'RM', 'countrycode' => 'my', 'country' => 'malaysia', 'rate' => 4.7],
            'THB' => ['symbol' => '฿', 'countrycode' => 'th', 'country' => 'thailand', 'rate' => 36],
            'IDR' => ['symbol' => 'Rp', 'countrycode' => 'id', 'country' => 'indonesia', 'rate' => 16250],
            'PKR' => ['symbol' => '₨', 'countrycode' => 'pk', 'country' => 'pakistan', 'rate' => 277],
            'EGP' => ['symbol' => '£', 'countrycode' => 'eg', 'country' => 'egypt', 'rate' => 48],
        ];
    }


}
  