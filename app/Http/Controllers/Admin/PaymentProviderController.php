<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentProvider;
use App\Services\ProviderRoutingService;
use Illuminate\Http\Request;

class PaymentProviderController extends Controller
{
    protected $currencies = [
        'NGN', 'GHS', 'KES', 'UGX', 'TZS', 'XOF', 'XAF', 'ZAR', 'CAD', 'USD', 'GBP', 'EUR',
    ];

    public function index()
    {
        $providers = PaymentProvider::with('currencies')->orderBy('priority')->get();

        return view('admin.payment-providers', [
            'providers' => $providers,
            'allCurrencies' => $this->currencies,
        ]);
    }

    // Toggle a provider fully on/off
    public function toggleProvider(Request $request, PaymentProvider $provider)
    {
        $provider->is_enabled = !$provider->is_enabled;
        $provider->save();

        ProviderRoutingService::clearCache();

        return back()->with('success', "{$provider->name} is now " . ($provider->is_enabled ? 'enabled' : 'disabled') . ".");
    }

    // Update priority (lower number = tried first)
    public function updatePriority(Request $request, PaymentProvider $provider)
    {
        $request->validate(['priority' => 'required|integer|min:1|max:999']);

        $provider->priority = $request->priority;
        $provider->save();

        ProviderRoutingService::clearCache();

        return back()->with('success', "Priority updated for {$provider->name}.");
    }

    // Toggle a specific currency (+ optional transfer method) for a provider
    public function toggleCurrency(Request $request, PaymentProvider $provider)
    {
        $request->validate([
            'currency' => 'required|string|size:3',
            'transfer_method' => 'nullable|in:bank,mobile',
        ]);

        $currency = strtoupper($request->currency);
        $method   = $request->transfer_method;

        $row = $provider->currencies()->firstOrCreate(
            ['currency' => $currency, 'transfer_method' => $method],
            ['is_enabled' => true]
        );

        // If it already existed, flip it; if just created, it's already enabled=true
        if ($row->wasRecentlyCreated === false) {
            $row->is_enabled = !$row->is_enabled;
            $row->save();
        }

        ProviderRoutingService::clearCache();

        return back()->with('success', "Updated {$currency} for {$provider->name}.");
    }

    // Remove a currency mapping from a provider entirely
    public function removeCurrency(Request $request, PaymentProvider $provider, $currencyId)
    {
        $provider->currencies()->where('id', $currencyId)->delete();

        ProviderRoutingService::clearCache();

        return back()->with('success', 'Currency mapping removed.');
    }
}