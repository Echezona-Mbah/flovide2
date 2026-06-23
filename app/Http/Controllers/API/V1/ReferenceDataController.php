<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\User;
use App\Models\WebhookSetting;
use Illuminate\Http\Request;

class ReferenceDataController extends Controller
{


   public function currencies(Request $request)
{
    $user = $this->resolveKeyUser($request);

    if (! $user) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid public key or secret key',
        ], 401);
    }

    $webhookSetting = $this->resolveKeyOwner($request);

    if (! $webhookSetting) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid public key or secret key',
        ], 401);
    }

    $path      = $request->path();
    $isTestUrl = str_starts_with($path, 'api/test/');
    $keyMode   = $this->resolveKeyMode($request, $webhookSetting);

    if ($isTestUrl && $keyMode !== 'test') {
        return response()->json([
            'success'  => false,
            'message'  => 'Test API requires test keys',
            'path'     => $path,
            'key_mode' => $keyMode,
        ], 403);
    }

    if (! $isTestUrl && $keyMode !== 'live') {
        return response()->json([
            'success'  => false,
            'message'  => 'Live API requires live keys',
            'path'     => $path,
            'key_mode' => $keyMode,
        ], 403);
    }

    $mode = $isTestUrl ? 'test' : 'live';

    $currencies = Currency::query()
        ->orderBy('code')
        ->get(['code', 'country_code', 'name']);

    return response()->json([
        'success' => true,
        'message' => 'Currencies fetched successfully',
        'code'    => 'CURRENCIES_FETCHED',
        'mode'    => $mode,
        'data'    => $currencies,
    ], 200);
}

 
// public function banks(Request $request)
// {
//     $countryIso = $request->filled('country_iso') ? strtoupper($request->country_iso) : null;
//     $currency   = $request->filled('currency') ? strtoupper($request->currency) : null;

//     $pivotEnabled     = filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN);
//     $payazaEnabled    = filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN);
//     $appMobileEnabled = filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN);

//     $providerPriorityByCurrency = [
//         'UGX' => ['pivot', 'payaza'],
//         'GHS' => ['app_mobile', 'payaza'],
//         'NGN' => ['payaza'],
//         'KES' => ['payaza'],
//         'XOF' => ['payaza'],
//         'XAF' => ['payaza'],
//     ];

//     $enabledMap = [
//         'pivot'      => $pivotEnabled,
//         'payaza'     => $payazaEnabled,
//         'app_mobile' => $appMobileEnabled,
//     ];

//     $query = Bank::query()
//         ->when($countryIso, fn ($q) => $q->where('country_iso', $countryIso))
//         ->when($currency, fn ($q) => $q->where('currency', $currency));

//     if ($request->filled('provider')) {
//         $provider = strtolower($request->provider);

//         $banks = $query->where('provider', $provider)
//             ->orderBy('name')
//             ->get(['id', 'name', 'country_iso', 'currency', 'bank_code', 'sort_code', 'provider', 'type'])
//             ->map(fn ($b) => [
//                 'name' => $b->name,
//                 'country_iso' => $b->country_iso,
//                 'currency' => $b->currency,
//                 'bank_code' => $b->bank_code,
//                 'sort_code' => $b->sort_code,
//                 'type' => $b->type,
//             ])
//             ->values();

//         return response()->json([
//             'success' => true,
//             'message' => 'Banks fetched successfully',
//             'code' => 'BANKS_FETCHED',
//             'data' => $banks,
//         ], 200);
//     }

//     $banks = $query
//         ->get(['id', 'name', 'country_iso', 'currency', 'bank_code', 'sort_code', 'provider', 'type'])
//         ->groupBy(fn ($b) => strtoupper($b->currency))
//         ->flatMap(function ($group, $groupCurrency) use ($providerPriorityByCurrency, $enabledMap) {
//             $priority = $providerPriorityByCurrency[$groupCurrency] ?? ['payaza', 'pivot', 'app_mobile'];

//             $chosenProvider = collect($priority)->first(function ($provider) use ($group, $enabledMap) {
//                 return ($enabledMap[$provider] ?? false) && $group->contains('provider', $provider);
//             });

//             if (!$chosenProvider) {
//                 return collect();
//             }

//             return $group
//                 ->where('provider', $chosenProvider)
//                 ->unique(fn ($b) => $b->bank_code . '|' . $b->type)
//                 ->sortBy('name')
//                 ->values();
//         })
//         ->values()
//         ->map(fn ($b) => [
//             'name' => $b->name,
//             'country_iso' => $b->country_iso,
//             'currency' => $b->currency,
//             'bank_code' => $b->bank_code,
//             'sort_code' => $b->sort_code,
//             'type' => $b->type,
//         ]);

//     return response()->json([
//         'success' => true,
//         'message' => 'Banks fetched successfully',
//         'code' => 'BANKS_FETCHED',
//         'data' => $banks->values(),
//     ], 200);
// }




public function banks(Request $request)
{
    $user = $this->resolveKeyUser($request);

    if (! $user) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid public key or secret key',
        ], 401);
    }

    $webhookSetting = $this->resolveKeyOwner($request);

    if (! $webhookSetting) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid public key or secret key',
        ], 401);
    }

    $path      = $request->path();
    $isTestUrl = str_starts_with($path, 'api/test/');
    $keyMode   = $this->resolveKeyMode($request, $webhookSetting);

    if ($isTestUrl && $keyMode !== 'test') {
        return response()->json([
            'success'  => false,
            'message'  => 'Test API requires test keys',
            'path'     => $path,
            'key_mode' => $keyMode,
        ], 403);
    }

    if (! $isTestUrl && $keyMode !== 'live') {
        return response()->json([
            'success'  => false,
            'message'  => 'Live API requires live keys',
            'path'     => $path,
            'key_mode' => $keyMode,
        ], 403);
    }

    $mode = $isTestUrl ? 'test' : 'live';

    // ── Existing logic unchanged below ──

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
        ->when($currency,   fn ($q) => $q->where('currency', $currency));

    if ($request->filled('provider')) {
        $provider = strtolower($request->provider);

        $banks = $query->where('provider', $provider)
            ->orderBy('name')
            ->get(['id', 'name', 'country_iso', 'currency', 'bank_code', 'sort_code', 'provider', 'type'])
            ->map(fn ($b) => [
                'name'        => $b->name,
                'country_iso' => $b->country_iso,
                'currency'    => $b->currency,
                'bank_code'   => $b->bank_code,
                'sort_code'   => $b->sort_code,
                'type'        => $b->type,
            ])
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Banks fetched successfully',
            'code'    => 'BANKS_FETCHED',
            'mode'    => $mode,
            'data'    => $banks,
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

            if (! $chosenProvider) {
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
            'name'        => $b->name,
            'country_iso' => $b->country_iso,
            'currency'    => $b->currency,
            'bank_code'   => $b->bank_code,
            'sort_code'   => $b->sort_code,
            'type'        => $b->type,
        ]);

    return response()->json([
        'success' => true,
        'message' => 'Banks fetched successfully',
        'code'    => 'BANKS_FETCHED',
        'mode'    => $mode,
        'data'    => $banks->values(),
    ], 200);
}

    private function resolveKeyUser(Request $request): ?User
    {
        $publicKey = $request->header('X-Public-Key');
        $secretKey = $request->header('X-Secret-Key');

        if (! $publicKey || ! $secretKey) {
            return null;
        }

        $webhookSetting = WebhookSetting::query()
            ->where(function ($query) use ($publicKey, $secretKey) {
                $query->where('live_public_key', $publicKey)
                    ->where('live_secret_key', $secretKey);
            })
            ->orWhere(function ($query) use ($publicKey, $secretKey) {
                $query->where('test_public_key', $publicKey)
                    ->where('test_secret_key', $secretKey);
            })
            ->first();

        return $webhookSetting ? User::find($webhookSetting->user_id) : null;
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
