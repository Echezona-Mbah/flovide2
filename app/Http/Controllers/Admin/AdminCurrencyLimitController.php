<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class AdminCurrencyLimitController extends Controller
{
    public function index()
    {
        $currencies = Currency::orderBy('code')->get();
        return view('admin.currency-limits', compact('currencies'));
    }

    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'is_active' => 'nullable|boolean',
        ]);

        $currency->update([
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', "{$currency->code} limit updated successfully.");
    }
}
