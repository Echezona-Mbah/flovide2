<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Traits\CurrencyHelper;
use App\Traits\SelectsBalanceId;
use Illuminate\Http\Request;

class RateController extends Controller
{
        use CurrencyHelper;
    use SelectsBalanceId;
    /**
     * Get exchange rates
     */
    public function getExchangeRates(Request $request)
    {
        $from = $request->input('from_currency');
        $to = $request->input('to_currency');
        $amount = $request->input('amount', 1);

        $result = $this->getExchangeRateFromMap($from, $to);

        if (! $result) {
            return response()->json([
                'data' => [
                    'errors' => 'Invalid currency'
                ]
            ], 400);
        }

        $rate = $result['rate'];
        $transfer_fee = $result['transfer_fee'];

        $formatted = sprintf(
            "%s %.2f = %s %s",
            strtoupper($from),
            (float) $amount,
            strtoupper($to),
            $rate
        );

        return response()->json([
            'exchange_rate' => $formatted,
            // 'transfer_fee' => $transfer_fee
        ]);
    }

    /**
     * Example rate map (replace with your real logic)
     */
    // protected function getExchangeRateFromMap($from, $to)
    // {
    //     $rates = [
    //         'USD_NGN' => ['rate' => '1500.00', 'transfer_fee' => 10],
    //         'NGN_USD' => ['rate' => '0.00067', 'transfer_fee' => 10],
    //         'GBP_NGN' => ['rate' => '1800.00', 'transfer_fee' => 15],
    //     ];

    //     $key = strtoupper($from).'_'.strtoupper($to);

    //     return $rates[$key] ?? null;
    // }
}
