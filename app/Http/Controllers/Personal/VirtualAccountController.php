<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Countries;
use App\Models\VirtualCards;
use App\Traits\CurrencyHelper;

class VirtualAccountController extends Controller
{
            use CurrencyHelper;


 public function index(Request $request)
{
    $countries = Countries::all();

    // Capture $this so it can be used inside the closure
    $helper = $this;

    $countries = $countries->map(function ($country) use ($helper) {
        $map = $helper->getCountryCodeFromCurrency($country->currency);
        $country->symbol = $map['symbol'] ?? null;
        $country->flag   = strtolower($map['country'] ?? 'us');
        return $country;
    });

    return view('business.add_virtualcard', compact('countries'));
}

public function createVirtualAccount(Request $request)
{
    $request->validate([
        'currency' => 'required',
    ]);

    $personal = auth('personal-api')->user();

    $card_number   = '4' . mt_rand(1000, 9999) . mt_rand(1000, 9999) . mt_rand(1000, 9999);
    $cvv           = rand(100, 999);
    $card_id       = rand(1000, 9999);
    $expiry_month  = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
    $expiry_year   = now()->addYears(3)->format('y'); 

    $card = VirtualCards::create([
        'personal_id'  => $personal->id,
        'card_number'  => $card_number,
        'cvv'          => $cvv,
        'card_id'      => $card_id,
        'expiry_month' => $expiry_month,
        'expiry_year'  => $expiry_year,
        'currency'     => $request->currency,
        'balance'      => 0.00,
        'status'       => 'active',
        'provider'     => 'Flovide',
    ]);

    if ($request->expectsJson()) {
        return response()->json([
            'data' => [
                'status'      => 'success',
                'personal_id' => $personal->id,
                'message'     => 'Virtual card created successfully.',
                'card'        => $card,
            ]
        ], 201);
    }

    return redirect()->route('personal.allvirtualcard')->with('success', 'Virtual card created successfully.');
}

public function allvirtualcard(Request $request)
{
    $personalId = auth('personal-api')->id();

    $cards = VirtualCards::where('personal_id', $personalId)->get();

    if ($request->expectsJson()) {
        return response()->json([
            'data' => [
                'status'      => 'success',
                'personal_id' => $personalId,
                'message'     => 'Virtual cards fetched successfully',
                'cards'       => $cards,
            ]
        ], 200);
    }

    return view('personal.allvirtualcard', compact('cards'));
}

public function showVirtualCard(Request $request, $id)
{
    $personalId = auth('personal-api')->id();

    $card = VirtualCards::where('personal_id', $personalId)->find($id);

    if (!$card) {
        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'status'      => 'error',
                    'personal_id' => $personalId,
                    'message'     => 'Virtual card not found',
                ]
            ], 404);
        }

        abort(404, 'Virtual card not found');
    }

    if ($request->expectsJson()) {
        return response()->json([
            'data' => [
                'status'      => 'success',
                'personal_id' => $personalId,
                'message'     => 'Virtual card fetched successfully',
                'card'        => $card,
            ]
        ], 200);
    }

    return view('personal.virtualcard_show', compact('card'));
}


public function destroy($id)
{
    $personalId = auth('personal-api')->id();

    $card = VirtualCards::where('personal_id', $personalId)->find($id);

    if (!$card) {
        if (request()->expectsJson()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Virtual card not found.'
            ], 404);
        }

        return back()->with('error', 'Virtual card not found.');
    }

    $card->delete();

    if (request()->expectsJson()) {
        return response()->json([
            'status'  => 'success',
            'message' => 'Virtual card deleted successfully.'
        ], 200);
    }

    return back()->with('success', 'Virtual card deleted successfully.');
}


}
