<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use App\Models\WebhookSetting;
use Illuminate\Http\Request;

class RateController extends Controller
{
    // public function getExchangeRates(Request $request)
    // {
    //     $request->validate([
    //         'from_currency' => 'required|string|size:3',
    //         'to_currency' => 'required|string|size:3',
    //         'amount' => 'nullable|numeric|min:0',
    //         'to_amount' => 'nullable|numeric|min:0',
    //     ]);

    //     $from = strtoupper($request->input('from_currency'));
    //     $to = strtoupper($request->input('to_currency'));
    //     $amount = (float) $request->input('amount', 1);
    //     $toAmountInput = $request->input('to_amount');

    //     $rateRow = ExchangeRate::whereHas('fromCurrency', fn ($q) => $q->where('code', $from))
    //         ->whereHas('toCurrency', fn ($q) => $q->where('code', $to))
    //         ->first();

    //     if (! $rateRow) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Rate not found.',
    //             'code' => 'RATE_NOT_FOUND',
    //             'data' => null,
    //         ], 404);
    //     }

    //     $rateValue = (float) $rateRow->rate;
    //     $recipientAmount = $toAmountInput !== null
    //         ? (float) $toAmountInput
    //         : round($amount * $rateValue, 3);

    //     return response()->json([
    //         'rate' => [
    //             'from_currency' => [
    //                 'currency_code' => $from,
    //                 'amount' => 1,
    //             ],
    //             'to_currency' => [
    //                 'currency_code' => $to,
    //                 'amount' => number_format($rateValue, 3, '.', ''),
    //             ],
    //             'last_updated' => optional($rateRow->updated_at)->toIso8601String(),
    //             'outside_market_hours' => false,
    //         ],
    //         'sender' => [
    //             'currency_code' => $from,
    //             'amount' => $amount,
    //         ],
    //         'recipient' => [
    //             'currency_code' => $to,
    //             'amount' => $recipientAmount,
    //         ],
    //         'reversed' => false,
    //     ], 200);
    // }

    public function getExchangeRates(Request $request)
{
    $webhookSetting = $this->resolveKeyOwner($request);

    if (! $webhookSetting) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid public key or secret key',
        ], 401);
    }

    $path = $request->path();
    $isTestUrl = str_starts_with($path, 'api/test/');
    $keyMode = $this->resolveKeyMode($request, $webhookSetting);

    if ($isTestUrl && $keyMode !== 'test') {
        return response()->json([
            'success' => false,
            'message' => 'Test API requires test keys',
            'path' => $path,
            'key_mode' => $keyMode,
        ], 403);
    }

    if (! $isTestUrl && $keyMode !== 'live') {
        return response()->json([
            'success' => false,
            'message' => 'Live API requires live keys',
            'path' => $path,
            'key_mode' => $keyMode,
        ], 403);
    }

    $mode = $isTestUrl ? 'test' : 'live';

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
            'mode' => $mode,
            'data' => null,
        ], 404);
    }

    $rateValue = (float) $rateRow->rate;

    $recipientAmount = $toAmountInput !== null
        ? (float) $toAmountInput
        : round($amount * $rateValue, 3);

    return response()->json([
        'success' => true,
        'message' => 'Exchange rate fetched successfully',
        'code' => 'RATE_FETCHED',
        'mode' => $mode,
        'data' => [
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
        ],
    ], 200);
}



     private function resolveKeyOwner(Request $request): ?WebhookSetting
    {
        $publicKey = $request->header('X-Public-Key');
        $secretKey = $request->header('X-Secret-Key');

        if (! $publicKey || ! $secretKey) {
            return null;
        }

        return WebhookSetting::query()
            ->where(function ($query) use ($publicKey, $secretKey) {
                $query->where('live_public_key', $publicKey)
                    ->where('live_secret_key', $secretKey);
            })
            ->orWhere(function ($query) use ($publicKey, $secretKey) {
                $query->where('test_public_key', $publicKey)
                    ->where('test_secret_key', $secretKey);
            })
            ->first();
    }




    private function resolveKeyMode(Request $request, WebhookSetting $setting): string
    {
        $publicKey = $request->header('X-Public-Key');
        $secretKey = $request->header('X-Secret-Key');

        if (
            $setting->test_public_key === $publicKey &&
            $setting->test_secret_key === $secretKey
        ) {
            return 'test';
        }

        return 'live';
    }



}
