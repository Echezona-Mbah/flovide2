<?php

namespace App\Services;

use App\Models\Bank;

class BankCodeResolver
{
    /**
     * Turn the code we hold (a Flovide code, or an old provider-specific code saved
     * before Flovide codes existed) into the bank row of the provider that is live now.
     */
    public function forProvider(string $provider, string $currency, string $code, ?string $savedProvider = null): ?Bank
    {
        $currency = strtoupper($currency);

        // 1) Normal path: the code is already a Flovide code
        $row = Bank::where('provider', $provider)
            ->where('currency', $currency)
            ->where('flovide_code', $code)
            ->first();

        if ($row) {
            return $row;
        }

        // 2) Legacy path: an old provider-specific code on a saved beneficiary
        $flovide = $this->toFlovideCode($currency, $code, $savedProvider);

        return $flovide
            ? Bank::where('provider', $provider)->where('currency', $currency)->where('flovide_code', $flovide)->first()
            : null;
    }

    public function toFlovideCode(string $currency, string $code, ?string $savedProvider = null): ?string
    {
        $query = Bank::where('currency', strtoupper($currency))
            ->whereNotNull('flovide_code')
            ->where(fn ($q) => $q->where('flovide_code', $code)
                ->orWhere('bank_code', $code)
                ->orWhere('sort_code', $code));

        // Prefer the provider the beneficiary was saved under (avoids short-code collisions)
        if ($savedProvider && (clone $query)->where('provider', $savedProvider)->exists()) {
            $query->where('provider', $savedProvider);
        }

        return $query->value('flovide_code');
    }

    /** True once a currency has been through banks:link-flovide-codes. */
    public function isLinked(string $currency): bool
    {
        return Bank::where('currency', strtoupper($currency))->whereNotNull('flovide_code')->exists();
    }

    public function findRow(string $currency, string $code, ?string $preferProvider = null): ?Bank
    {
        return Bank::where('currency', strtoupper($currency))
            ->where(fn ($q) => $q->where('flovide_code', $code)
                ->orWhere('bank_code', $code)
                ->orWhere('sort_code', $code))
            // if several providers have this bank, prefer the live provider's row
            ->when($preferProvider, fn ($q) => $q->orderByRaw('provider = ? desc', [$preferProvider]))
            ->first();
    }

    public function publicBanks(string $currency, ?string $country = null, ?string $transferMethod = null): ?array
    {
        $currency = strtoupper($currency);
        $provider = app(\App\Services\ProviderRoutingService::class)->resolveForCurrency($currency, $transferMethod);

        if (!$provider) {
            return null; // nothing is switched on for this currency
        }

        return Bank::where('provider', $provider->key)
            ->where('currency', $currency)
            ->when($transferMethod === 'mobile', fn ($q) => $q->where('type', 'mobile_money'))
            ->when($transferMethod === 'bank', fn ($q) => $q->where('type', '!=', 'mobile_money'))
            ->when($country, fn ($q) => $q->where(function ($w) use ($country) {
                $w->where('country_iso', $country)
                ->orWhereRaw('LEFT(country_iso,2) = ?', [substr($country, 0, 2)]);
            }))
            ->orderBy('name')
            ->get()
            ->map(fn ($b) => [
                'name'        => $b->name,
                'country_iso' => $b->country_iso,
                'currency'    => $b->currency,
                'bank_code'   => $b->flovide_code ?? $b->bank_code,   // the Flovide code
                'sort_code'   => $b->sort_code,                       // provider-specific, tell customers to ignore it
                'type'        => $b->type,
            ])
            ->unique('bank_code')
            ->values()
            ->all();
    }


}