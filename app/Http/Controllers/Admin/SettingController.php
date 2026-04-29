<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Personal;
use App\Models\User;
use App\Notifications\GeneralNotification;
use App\Services\FirebaseNotificationService; 

class SettingController extends Controller
{


// public function index(Request $request)
// {
//     $search = $request->search;

//     $currencies = Currency::query()
//         ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
//             ->orWhere('code', 'like', "%{$search}%"))
//         ->orderBy('name')
//         ->get();

//     $selectedCode = $request->get('currency', $currencies->first()?->code);

//     $exchangeRates = ExchangeRate::with(['fromCurrency:id,code', 'toCurrency:id,code'])
//         ->when($selectedCode, function ($q) use ($selectedCode) {
//             $q->whereHas('fromCurrency', fn($sub) => $sub->where('code', $selectedCode))
//               ->orWhereHas('toCurrency', fn($sub) => $sub->where('code', $selectedCode));
//         })
//         ->get();

//     return view('admin.exchangerate', [
//     'currencies' => $currencies,
//     'exchangerates' => $exchangeRates, // <- match view
//     'selectedCode' => $selectedCode,
// ]);
// }



public function index(Request $request)
{
    $search = $request->search;

    $currencies = Currency::query()
        ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%"))
        ->orderBy('name')
        ->get();

    $selectedCode = $request->get('currency', $currencies->first()?->code);

    $exchangeRates = ExchangeRate::with([
        'fromCurrency:id,code,name',
        'toCurrency:id,code,name'
    ])
    ->when($selectedCode, function ($q) use ($selectedCode) {
        $q->whereHas('fromCurrency', fn($sub) => $sub->where('code', $selectedCode));
    })
    ->get();


    return view('admin.exchangerate', [
        'currencies' => $currencies,
        'exchangerates' => $exchangeRates,
        'selectedCode' => $selectedCode,
    ]);
}


public function storeCurrency(Request $request)
{
    $request->validate([
        'code' => 'required|string|size:3|unique:currencies,code',
        'name' => 'required|string',
        'symbol' => 'nullable|string|max:10',
        'country_code' => 'nullable|string|size:2',
    ]);

    Currency::create([
        'code' => strtoupper($request->code),
        'name' => $request->name,
        'symbol' => $request->symbol,
        'country_code' => strtolower($request->country_code),
    ]);

    return redirect()->back()->with('success', 'Currency added successfully.');
}

public function storeExchangeRate(Request $request)
{
    $request->validate([
        'from_currency_id' => 'required|exists:currencies,id',
        'to_currency_id' => 'required|exists:currencies,id',
        'rate' => 'required|numeric',
        'transfer_fee' => 'nullable|numeric',
    ]);

    ExchangeRate::create([
        'from_currency_id' => $request->from_currency_id,
        'to_currency_id' => $request->to_currency_id,
        'rate' => $request->rate,
        'transfer_fee' => $request->transfer_fee ?? 0,
    ]);

    return redirect()->back()->with('success', 'Exchange rate added successfully.');
}




    public function edit($id)
{
    $rate = ExchangeRate::findOrFail($id);
    $currencies = Currency::orderBy('name')->get();

    return view('admin.exchangerate_edit', compact('rate', 'currencies'));
}


// public function update(Request $request, $id)
// {
//     $request->validate([
//         'country_name' => 'nullable|string|max:255',
//         'currency_code' => 'nullable|string|max:10',
//         'rate' => 'nullable|numeric|min:0',
//         'transfer_fee' => 'nullable|numeric|min:0',
//     ]);

//     $rate = ExchangeRate::findOrFail($id);

//     $rate->update([
//         'country_name' => $request->country_name,
//         'currency_code' => strtoupper($request->currency_code),
//         'rate' => $request->rate,
//         'transfer_fee' => $request->transfer_fee,
//     ]);

//     $rate->load(['fromCurrency', 'toCurrency']);

//     $fromCode = $rate->fromCurrency->code ?? 'N/A';
//     $toCode = $rate->toCurrency->code ?? 'N/A';

//     $title = "Exchange Rate Updated";
//     $message = "The exchange rate {$fromCode} → {$toCode} was updated. New rate: {$rate->rate}.";


//     // ✅ Notify all Users
//     User::chunk(500, function ($users) use ($title, $message) {
//         foreach ($users as $user) {
//             $user->notify(new GeneralNotification($title, $message));
//         }
//     });

//     // ✅ Notify all Personal users
//     Personal::chunk(500, function ($users) use ($title, $message) {
//         foreach ($users as $user) {
//             $user->notify(new GeneralNotification($title, $message));
//         }
//     });

//     return redirect()->route('admin.exchangerate.edit', $id)
//         ->with('success', 'Exchange rate updated successfully.');
// }

Route::get('/test-fcm', function (\App\Services\FirebaseNotificationService $fcm) {
    $user = \App\Models\User::whereNotNull('device_token')->first();
    abort_if(!$user, 404, 'No token found');

    $ok = $fcm->sendToToken(
        $user->device_token,
        'FCM Test',
        'If you see this, push is working.',
        ['type' => 'test']
    );

    return ['success' => $ok];
});


public function update(Request $request, $id, FirebaseNotificationService $firebase)
{
    $request->validate([
        'country_name' => 'nullable|string|max:255',
        'currency_code' => 'nullable|string|max:10',
        'rate' => 'nullable|numeric|min:0',
        'transfer_fee' => 'nullable|numeric|min:0',
    ]);

    $rate = ExchangeRate::findOrFail($id);

    $rate->update([
        'country_name' => $request->country_name,
        'currency_code' => strtoupper($request->currency_code),
        'rate' => $request->rate,
        'transfer_fee' => $request->transfer_fee,
    ]);

    $rate->load(['fromCurrency', 'toCurrency']);

    $fromCode = $rate->fromCurrency->code ?? 'N/A';
    $toCode = $rate->toCurrency->code ?? 'N/A';

    $title = 'Exchange Rate Updated';
    $message = "The exchange rate {$fromCode} -> {$toCode} was updated. New rate: {$rate->rate}.";

    // Notify all business users
    User::chunk(500, function ($users) use ($title, $message, $firebase, $fromCode, $toCode, $rate) {
        foreach ($users as $user) {
            $user->notify(new GeneralNotification($title, $message));

            if (!empty($user->device_token)) {
                $firebase->sendToToken(
                    $user->device_token,
                    $title,
                    $message,
                    [
                        'type' => 'exchange_rate_update',
                        'from_currency' => $fromCode,
                        'to_currency' => $toCode,
                        'rate' => (string) $rate->rate,
                    ]
                );
            }
        }
    });

    // Notify all personal users
    Personal::chunk(500, function ($users) use ($title, $message, $firebase, $fromCode, $toCode, $rate) {
        foreach ($users as $user) {
            $user->notify(new GeneralNotification($title, $message));

            if (!empty($user->device_token)) {
                $firebase->sendToToken(
                    $user->device_token,
                    $title,
                    $message,
                    [
                        'type' => 'exchange_rate_update',
                        'from_currency' => $fromCode,
                        'to_currency' => $toCode,
                        'rate' => (string) $rate->rate,
                    ]
                );
            }
        }
    });

    return redirect()->route('admin.exchangerate.edit', $id)
        ->with('success', 'Exchange rate updated successfully.');
}

    public function destroy($id)
{
    $exchangeRate = ExchangeRate::findOrFail($id);
    $exchangeRate->delete();

    return redirect()->route('admin.exchangerate')->with('success', 'Exchange rate deleted successfully.');
}

// public function create()
// {
//     return view('admin.exchangerate_create');
// }

// public function store(Request $request)
//     {
//         $request->validate([
//             'country_name'   => 'required|string|max:255',
//             'currency_code'  => 'required|string|max:10',
//             'rate'           => 'required|numeric',
//             'transfer_fee'   => 'required|numeric',
//         ]);

//         ExchangeRate::create([
//             'country_name'   => $request->country_name,
//             'currency_code'  => strtoupper($request->currency_code),
//             'rate'           => $request->rate,
//             'transfer_fee'   => $request->transfer_fee,
//         ]);

//         return redirect()->route('admin.exchangerate')
//                          ->with('success', 'Exchange rate added successfully!');
//     }

// }
}