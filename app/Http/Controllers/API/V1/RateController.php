<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function getExchangeRates(Request $request)
    {
        $request->validate([
            'from_currency' => 'required|string|size:3',
            'to_currency' => 'required|string|size:3',
            'amount' => 'nullable|numeric|min:0',
            'to_amount' => 'nullable|numeric|min:0',
        ]);

        $from = strtoupper($request->input('from_currency'));
        $to = strtoupper($request->input('to_currency'));
        $amount = (float) $request->input('amount', 1);
        $toAmountInput = $request->input('to_amount');

        $rateRow = ExchangeRate::whereHas('fromCurrency', fn ($q) => $q->where('code', $from))
            ->whereHas('toCurrency', fn ($q) => $q->where('code', $to))
            ->first();

        if (! $rateRow) {
            return response()->json([
                'success' => false,
                'message' => 'Rate not found.',
                'code' => 'RATE_NOT_FOUND',
                'data' => null,
            ], 404);
        }

        $rateValue = (float) $rateRow->rate;
        $recipientAmount = $toAmountInput !== null
            ? (float) $toAmountInput
            : round($amount * $rateValue, 3);

        return response()->json([
            'rate' => [
                'from_currency' => [
                    'currency_code' => $from,
                    'amount' => 1,
                ],
                'to_currency' => [
                    'currency_code' => $to,
                    'amount' => number_format($rateValue, 3, '.', ''),
                ],
                'last_updated' => optional($rateRow->updated_at)->toIso8601String(),
                'outside_market_hours' => false,
            ],
            'sender' => [
                'currency_code' => $from,
                'amount' => $amount,
            ],
            'recipient' => [
                'currency_code' => $to,
                'amount' => $recipientAmount,
            ],
            'reversed' => false,
        ], 200);
    }
}
