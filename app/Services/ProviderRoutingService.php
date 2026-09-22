<?php

namespace App\Services;

use App\Models\PaymentProvider;
use Illuminate\Support\Facades\Cache;

class ProviderRoutingService
{
    /**
     * Resolve which provider should handle a given currency (and optionally
     * transfer method). Priority: lowest `priority` value wins when multiple
     * enabled providers support the same currency.
     */
    // public function resolveForCurrency(string $currency, ?string $transferMethod = null): ?PaymentProvider
    // {
    //     $currency = strtoupper($currency);
    //     $transferMethod = $transferMethod ? strtolower($transferMethod) : null;

    //     $providers = $this->enabledProviders();

    //     foreach ($providers as $provider) {
    //         $match = $provider->currencies->first(function ($c) use ($currency, $transferMethod) {
                
    //             if (!$c->is_enabled || strtoupper($c->currency) !== $currency) {
    //                 return false;
    //             }

    //             // null transfer_method on the row means "any method" is fine
    //             return $c->transfer_method === null || $c->transfer_method === $transferMethod;
    //         });

    //         if ($match) {
    //             return $provider;
    //         }
    //     }

    //     return null;
    // }

    public function resolveForCurrency(string $currency, ?string $transferMethod = null): ?PaymentProvider
{
    $currency = strtoupper($currency);
    $transferMethod = $transferMethod ? strtolower($transferMethod) : null;

    $providers = $this->enabledProviders();

    foreach ($providers as $provider) {
        $match = $provider->currencies->first(function ($c) use ($currency, $transferMethod) {
            if (!$c->is_enabled || strtoupper($c->currency) !== $currency) {
                return false;
            }

            // No method requested → any row for this currency is fine.
            // Row has no method set → matches regardless of requested method.
            // Otherwise → require an exact match.
            if ($transferMethod === null || $c->transfer_method === null) {
                return true;
            }

            return $c->transfer_method === $transferMethod;
        });

        if ($match) {
            return $provider;
        }
    }

    return null;
}

    public function isEnabled(string $key): bool
    {
        $provider = $this->enabledProviders()->firstWhere('key', $key);
        return (bool) $provider;
    }

    public function enabledCurrenciesFor(string $key): array
    {
        $provider = $this->enabledProviders()->firstWhere('key', $key);
        if (!$provider) {
            return [];
        }
        return $provider->currencies->where('is_enabled', true)->pluck('currency')->unique()->values()->all();
    }

    /**
     * Cached for 5 minutes so we don't hit the DB on every request.
     * Cleared automatically whenever admin saves changes (see controller).
     */
    protected function enabledProviders()
    {
        return Cache::remember('payment_providers_enabled', 300, function () {
            return PaymentProvider::with('currencies')
                ->where('is_enabled', true)
                ->orderBy('priority')
                ->get();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('payment_providers_enabled');
    }
}