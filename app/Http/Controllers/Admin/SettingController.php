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
use Illuminate\Support\Facades\Log;


class SettingController extends Controller
{





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
      $unreadReferralAlerts = \App\Models\AdminNotification::whereNull('read_at')
    ->where('type', 'referral_bonus')
    ->latest()
    ->get();


    return view('admin.exchangerate', [
        'currencies' => $currencies,
        'exchangerates' => $exchangeRates,
        'selectedCode' => $selectedCode,
        'unreadReferralAlerts' => $unreadReferralAlerts,
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
        'collection_fee' => 'nullable|numeric',
    ]);

    ExchangeRate::create([
        'from_currency_id' => $request->from_currency_id,
        'to_currency_id' => $request->to_currency_id,
        'rate' => $request->rate,
        'transfer_fee' => $request->transfer_fee ?? 0,
        'collection_fee' => $request->collection_fee ?? 0,
    ]);

    return redirect()->back()->with('success', 'Exchange rate added successfully.');
}




    public function edit($id)
{
    $rate = ExchangeRate::findOrFail($id);
    $currencies = Currency::orderBy('name')->get();
         $unreadReferralAlerts = \App\Models\AdminNotification::whereNull('read_at')
    ->where('type', 'referral_bonus')
    ->latest()
    ->get();

    return view('admin.exchangerate_edit', compact('rate', 'currencies','unreadReferralAlerts'));
}



// public function update(Request $request, $id, FirebaseNotificationService $firebase)
// {
//     $request->validate([
//         'country_name' => 'nullable|string|max:255',
//         'currency_code' => 'nullable|string|max:10',
//         'rate' => 'nullable|numeric|min:0',
//         'transfer_fee' => 'nullable|numeric|min:0',
//     ]);

//     try {
//         $rate = ExchangeRate::findOrFail($id);

//         $rate->update([
//             'country_name' => $request->country_name,
//             'currency_code' => $request->filled('currency_code')
//                 ? strtoupper($request->currency_code)
//                 : $rate->currency_code,
//             'rate' => $request->rate,
//             'transfer_fee' => $request->transfer_fee,
//         ]);

//         $rate->load(['fromCurrency', 'toCurrency']);

//         $fromCode = $rate->fromCurrency->code ?? 'N/A';
//         $toCode   = $rate->toCurrency->code ?? 'N/A';

//         $title = 'Exchange Rate Updated';
//         $message = "The exchange rate {$fromCode} -> {$toCode} was updated. New rate: {$rate->rate}.";

//         // $dataPayload = [
//         //     'type' => 'exchange_rate_update',
//         //     'from_currency' => $fromCode,
//         //     'to_currency' => $toCode,
//         //     'rate' => (string) $rate->rate,
//         // ];

//         $dataPayload = [
//             'type' => 'refresh_rate',
//         ];

//         $stats = [
//             'business_notified' => 0,
//             'personal_notified' => 0,
//             'push_sent' => 0,
//             'push_failed' => 0,
//         ];

//         // $notifyUsers = function ($users, string $group) use ($title, $message, $firebase, $dataPayload, &$stats) {
//         //     foreach ($users as $user) {
//         //         try {
//         //             // In-app/database notification -> ALL users
//         //             $user->notify(new GeneralNotification($title, $message));

//         //             if ($group === 'business') {
//         //                 $stats['business_notified']++;
//         //             } else {
//         //                 $stats['personal_notified']++;
//         //             }

//         //             // Optional push (only if token exists)
//         //             if (!empty($user->device_token)) {
//         //                 $sent = $firebase->sendToToken($user->device_token, $title, $message, $dataPayload);
//         //                 $sent ? $stats['push_sent']++ : $stats['push_failed']++;
//         //             }
//         //         } catch (\Throwable $e) {
//         //             $stats['push_failed']++;
//         //             Log::warning('Notification failed', [
//         //                 'group' => $group,
//         //                 'user_id' => $user->id ?? null,
//         //                 'error' => $e->getMessage(),
//         //             ]);
//         //         }
//         //     }
//         // };

//         $notifyUsers = function ($users, string $group) use ($firebase, $dataPayload, &$stats) {
//             foreach ($users as $user) {
//                 try {
//                     if ($group === 'business') {
//                         $stats['business_notified']++;
//                     } else {
//                         $stats['personal_notified']++;
//                     }

//                     // Silent Firebase push only: no title, no body
//                     if (!empty($user->device_token)) {
//                         $sent = $firebase->sendSilentToToken($user->device_token, $dataPayload);
//                         $sent ? $stats['push_sent']++ : $stats['push_failed']++;
//                     }
//                 } catch (\Throwable $e) {
//                     $stats['push_failed']++;

//                     Log::warning('Silent rate refresh notification failed', [
//                         'group' => $group,
//                         'user_id' => $user->id ?? null,
//                         'error' => $e->getMessage(),
//                     ]);
//                 }
//             }
//         };

//         User::query()->chunk(500, fn($users) => $notifyUsers($users, 'business'));
//         Personal::query()->chunk(500, fn($users) => $notifyUsers($users, 'personal'));

//         Log::info('Exchange rate notification summary', [
//             'exchange_rate_id' => $rate->id,
//             'from_currency' => $fromCode,
//             'to_currency' => $toCode,
//             'rate' => $rate->rate,
//             'stats' => $stats,
//         ]);

//         if ($request->expectsJson()) {
//             return response()->json([
//                 'success' => true,
//                 'message' => 'Exchange rate updated successfully.',
//                 'code' => 'EXCHANGE_RATE_UPDATED',
//                 'data' => [
//                     'id' => $rate->id,
//                     'from_currency' => $fromCode,
//                     'to_currency' => $toCode,
//                     'rate' => $rate->rate,
//                     'transfer_fee' => $rate->transfer_fee,
//                     'notification_stats' => $stats,
//                 ],
//             ], 200);
//         }

//         return redirect()
//             ->route('admin.exchangerate.edit', $id)
//             ->with('success', 'Exchange rate updated successfully.');
//     } catch (\Throwable $e) {
//         Log::error('Exchange rate update failed', [
//             'exchange_rate_id' => $id,
//             'error' => $e->getMessage(),
//         ]);

//         if ($request->expectsJson()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to update exchange rate.',
//                 'code' => 'EXCHANGE_RATE_UPDATE_FAILED',
//                 'data' => $e->getMessage(),
//             ], 500);
//         }

//         return back()->withInput()->with('error', 'Failed to update exchange rate.');
//     }
// }


public function update(Request $request, $id, FirebaseNotificationService $firebase)
{
    $request->validate([
        'country_name' => 'nullable|string|max:255',
        'currency_code' => 'nullable|string|max:10',
        'rate' => 'nullable|numeric|min:0',
        'transfer_fee' => 'nullable|numeric|min:0',
        'collection_fee' => 'nullable|numeric|min:0',
    ]);

    try {
        $rate = ExchangeRate::findOrFail($id);

        $rate->update([
            'country_name' => $request->country_name,
            'currency_code' => $request->filled('currency_code')
                ? strtoupper($request->currency_code)
                : $rate->currency_code,
            'rate' => $request->rate,
            'transfer_fee' => $request->transfer_fee,
            'collection_fee' => $request->collection_fee,
        ]);

        $rate->load(['fromCurrency', 'toCurrency']);

        $fromCode = $rate->fromCurrency->code ?? 'N/A';
        $toCode   = $rate->toCurrency->code ?? 'N/A';

        $title = 'Exchange Rate Updated';
        $message = "The exchange rate {$fromCode} -> {$toCode} was updated. New rate: {$rate->rate}.";

        $dataPayload = [
            'type' => 'refresh_rate',
        ];

        $stats = [
            'business_notified' => 0,
            'personal_notified' => 0,
            'push_sent' => 0,
            'push_failed' => 0,
        ];

        $notifyUsers = function ($users, string $group) use ($firebase, $dataPayload, &$stats) {
            foreach ($users as $user) {
                try {
                    if ($group === 'business') {
                        $stats['business_notified']++;
                    } else {
                        $stats['personal_notified']++;
                    }

                    if (!empty($user->device_token)) {
                        $sent = $firebase->sendSilentToToken($user->device_token, $dataPayload);
                        $sent ? $stats['push_sent']++ : $stats['push_failed']++;
                    }
                } catch (\Throwable $e) {
                    $stats['push_failed']++;

                    Log::warning('Silent rate refresh notification failed', [
                        'group' => $group,
                        'user_id' => $user->id ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        };

        User::query()->chunk(500, fn($users) => $notifyUsers($users, 'business'));
        Personal::query()->chunk(500, fn($users) => $notifyUsers($users, 'personal'));

        Log::info('Exchange rate notification summary', [
            'exchange_rate_id' => $rate->id,
            'from_currency' => $fromCode,
            'to_currency' => $toCode,
            'rate' => $rate->rate,
            'stats' => $stats,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Exchange rate updated successfully.',
                'code' => 'EXCHANGE_RATE_UPDATED',
                'data' => [
                    'id' => $rate->id,
                    'from_currency' => $fromCode,
                    'to_currency' => $toCode,
                    'rate' => $rate->rate,
                    'transfer_fee' => $rate->transfer_fee,
                    'collection_fee' => $rate->collection_fee,
                    'notification_stats' => $stats,
                ],
            ], 200);
        }

        return redirect()
            ->route('admin.exchangerate.edit', $id)
            ->with('success', 'Exchange rate updated successfully.');
    } catch (\Throwable $e) {
        Log::error('Exchange rate update failed', [
            'exchange_rate_id' => $id,
            'error' => $e->getMessage(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update exchange rate.',
                'code' => 'EXCHANGE_RATE_UPDATE_FAILED',
                'data' => $e->getMessage(),
            ], 500);
        }

        return back()->withInput()->with('error', 'Failed to update exchange rate.');
    }
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