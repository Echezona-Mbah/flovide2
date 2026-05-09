<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Currency;
use Illuminate\Http\Request;

class ReferenceDataController extends Controller
{
    // public function index(Request $request)
    // {
    //     $currencies = Currency::query()
    //         ->orderBy('code')
    //         ->get(['id', 'code', 'name', 'symbol', 'country_code']);

    //     $banks = Bank::query()
    //         ->when($request->filled('country_iso'), fn ($q) => $q->where('country_iso', strtoupper($request->country_iso)))
    //         ->when($request->filled('currency'), fn ($q) => $q->where('currency', strtoupper($request->currency)))
    //         ->orderBy('name')
    //         ->get(['id', 'name', 'country_iso', 'currency', 'bank_code', 'sort_code', 'provider', 'type']);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Reference data fetched successfully',
    //         'code' => 'REFERENCE_DATA_FETCHED',
    //         'data' => [
    //             'currencies' => $currencies,
    //             'banks' => $banks,
    //         ],
    //     ], 200);
    // }

    public function currencies()
    {
        $currencies = Currency::query()
            ->orderBy('code')
            ->get(['code','country_code','name']);

        return response()->json([
            'success' => true,
            'message' => 'Currencies fetched successfully',
            'code' => 'CURRENCIES_FETCHED',
            'data' => $currencies,
        ], 200);
    }

    // public function banks(Request $request)
    // {
    //     $banks = Bank::query()
    //         ->when($request->filled('country_iso'), fn ($q) => $q->where('country_iso', strtoupper($request->country_iso)))
    //         ->when($request->filled('currency'), fn ($q) => $q->where('currency', strtoupper($request->currency)))
    //         ->orderBy('name')
    //         ->get(['id', 'name', 'country_iso', 'currency', 'bank_code', 'sort_code', 'provider', 'type']);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Banks fetched successfully',
    //         'code' => 'BANKS_FETCHED',
    //         'data' => $banks,
    //     ], 200);
    // }

public function banks(Request $request)
{
    $countryIso = $request->filled('country_iso') ? strtoupper($request->country_iso) : null;
    $currency   = $request->filled('currency') ? strtoupper($request->currency) : null;

    $pivotEnabled     = filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN);
    $payazaEnabled    = filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN);
    $appMobileEnabled = filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN);

    $providerPriorityByCurrency = [
        'UGX' => ['pivot', 'payaza'],
        'GHS' => ['app_mobile', 'payaza'],
        'NGN' => ['payaza'],
        'KES' => ['payaza'],
        'XOF' => ['payaza'],
        'XAF' => ['payaza'],
    ];

    $enabledMap = [
        'pivot'      => $pivotEnabled,
        'payaza'     => $payazaEnabled,
        'app_mobile' => $appMobileEnabled,
    ];

    $query = Bank::query()
        ->when($countryIso, fn ($q) => $q->where('country_iso', $countryIso))
        ->when($currency, fn ($q) => $q->where('currency', $currency));

    if ($request->filled('provider')) {
        $provider = strtolower($request->provider);

        $banks = $query->where('provider', $provider)
            ->orderBy('name')
            ->get(['id', 'name', 'country_iso', 'currency', 'bank_code', 'sort_code', 'provider', 'type'])
            ->map(fn ($b) => [
                'name' => $b->name,
                'country_iso' => $b->country_iso,
                'currency' => $b->currency,
                'bank_code' => $b->bank_code,
                'sort_code' => $b->sort_code,
                'type' => $b->type,
            ])
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Banks fetched successfully',
            'code' => 'BANKS_FETCHED',
            'data' => $banks,
        ], 200);
    }

    $banks = $query
        ->get(['id', 'name', 'country_iso', 'currency', 'bank_code', 'sort_code', 'provider', 'type'])
        ->groupBy(fn ($b) => strtoupper($b->currency))
        ->flatMap(function ($group, $groupCurrency) use ($providerPriorityByCurrency, $enabledMap) {
            $priority = $providerPriorityByCurrency[$groupCurrency] ?? ['payaza', 'pivot', 'app_mobile'];

            $chosenProvider = collect($priority)->first(function ($provider) use ($group, $enabledMap) {
                return ($enabledMap[$provider] ?? false) && $group->contains('provider', $provider);
            });

            if (!$chosenProvider) {
                return collect();
            }

            return $group
                ->where('provider', $chosenProvider)
                ->unique(fn ($b) => $b->bank_code . '|' . $b->type)
                ->sortBy('name')
                ->values();
        })
        ->values()
        ->map(fn ($b) => [
            'name' => $b->name,
            'country_iso' => $b->country_iso,
            'currency' => $b->currency,
            'bank_code' => $b->bank_code,
            'sort_code' => $b->sort_code,
            'type' => $b->type,
        ]);

    return response()->json([
        'success' => true,
        'message' => 'Banks fetched successfully',
        'code' => 'BANKS_FETCHED',
        'data' => $banks->values(),
    ], 200);
}


}
