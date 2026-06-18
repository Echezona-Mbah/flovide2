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
            'CIV' => ['symbol' => 'CFA', 'country' => 'CI', 'rate' => 277],
            'BEN' => ['symbol' => 'CFA', 'country' => 'BJ', 'rate' => 48],
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



// public function getExchangeRateFromMap($from, $to, $amount = 1)
// {
//     $from = strtoupper($from);
//     $to = strtoupper($to);

//     $fromRow = ExchangeRate::where('currency_code', $from)
//         ->select('rate', 'transfer_fee')
//         ->first();

//     $toRow = ExchangeRate::where('currency_code', $to)
//         ->select('rate', 'transfer_fee')
//         ->first();


//     if (!$fromRow || !$toRow) {
//         return null;
//     }

//     // USD-base rate table (USD=1, NGN=1550, etc.)
//     $rate = $toRow->rate / $fromRow->rate;

//     // Amount converted from "from" currency to "to" currency
//     $converted = $amount * $fromRow->rate;

//     return [
//         'rate' => $rate,
//         'converted' => $converted,
//         'transfer_fee' => $toRow->transfer_fee,
//     ];
// }

// public function getExchangeRateFromMap(string $from, string $to, float $amount): ?array
// {
//     $from = strtoupper($from);
//     $to = strtoupper($to);

//     $fromRate = (float) ExchangeRate::where('currency_code', $from)->value('rate');
//     $toRate   = (float) ExchangeRate::where('currency_code', $to)->value('rate');

//     if ($fromRate <= 0 || $toRate <= 0) {
//         return null;
//     }

//     $converted = ($amount / $fromRate) * $toRate;

//     return [
//         'rate_text' => sprintf(
//             "%s %s = %s %s",
//             number_format($amount, 2, '.', ','),
//             $from,
//             number_format($converted, 2, '.', ','),
//             $to
//         ),
//         'converted' => round($converted, 2),
//     ];
// }

function getExchangeRateFromMap($amount, $from, $to)
{
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

    return [
        'converted' => $converted,
        'transfer_fee' => $rate->transfer_fee,
    ];
}




//     public function getExchangeRateFromMap($from, $to)
// {
//     $from = strtoupper($from);
//     $to = strtoupper($to);

//     $fromRate = ExchangeRate::where('currency_code', $from)->first();
//     $toRate   = ExchangeRate::where('currency_code', $to)->first();

//     if (!$fromRate || !$toRate) {
//         return null; // currency not found
//     }

//     // Calculate exchange rate
//     $rate = round($toRate->rate / $fromRate->rate, 6);

//     return [
//         'rate' => $rate,
//         'transfer_fee' => $toRate->transfer_fee,
//     ];
// }

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
  