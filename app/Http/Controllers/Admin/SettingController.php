<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class SettingController extends Controller
{
  public function index(Request $request)
{
    $query = ExchangeRate::query();

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where('country_name', 'like', "%{$search}%")
              ->orWhere('currency_code', 'like', "%{$search}%");
    }

    $exchangerates = $query->paginate(10);

    return view('admin.exchangerate', compact('exchangerates'));
}


      // Show edit form
    public function edit($id)
    {
        $rate = ExchangeRate::findOrFail($id);
        return view('admin.exchangerate_edit', compact('rate'));
    }

    // Handle update
    public function update(Request $request, $id)
    {
        $request->validate([
            'country_name' => 'required|string|max:255',
            'currency_code' => 'required|string|max:10',
            'rate' => 'required|numeric|min:0',
            'transfer_fee' => 'required|numeric|min:0',
        ]);

        $rate = ExchangeRate::findOrFail($id);
        $rate->update([
            'country_name' => $request->country_name,
            'currency_code' => strtoupper($request->currency_code),
            'rate' => $request->rate,
            'transfer_fee' => $request->transfer_fee,
        ]);

        return redirect()->route('admin.exchangerate.edit', $id)->with('success', 'Exchange rate updated successfully.');
    }

    public function destroy($id)
{
    $exchangeRate = ExchangeRate::findOrFail($id);
    $exchangeRate->delete();

    return redirect()->route('admin.exchangerate')->with('success', 'Exchange rate deleted successfully.');
}

public function create()
{
    return view('admin.exchangerate_create');
}

public function store(Request $request)
    {
        $request->validate([
            'country_name'   => 'required|string|max:255',
            'currency_code'  => 'required|string|max:10',
            'rate'           => 'required|numeric',
            'transfer_fee'   => 'required|numeric',
        ]);

        ExchangeRate::create([
            'country_name'   => $request->country_name,
            'currency_code'  => strtoupper($request->currency_code),
            'rate'           => $request->rate,
            'transfer_fee'   => $request->transfer_fee,
        ]);

        return redirect()->route('admin.exchangerate')
                         ->with('success', 'Exchange rate added successfully!');
    }

}
