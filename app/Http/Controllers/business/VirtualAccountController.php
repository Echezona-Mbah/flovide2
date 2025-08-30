<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Countries;
use App\Models\VirtualCards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
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
            $country->flag = strtolower($map['country'] ?? 'us');
            return $country;

        });

        return view('business.add_virtualcard', compact('countries'));
    }


   public function createVirtualAccount(Request $request)
    {
         $request->validate([
            'currency' => 'required',
        ]);
                // dd($request->all());

        $user = auth()->user();

        $card_number = '4' . mt_rand(1000, 9999) . mt_rand(1000, 9999) . mt_rand(1000, 9999);
        $cvv = rand(100, 999);
        $card_id = rand(1000, 9999);
        $expiry_month = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
        $expiry_year = now()->addYears(3)->format('y'); 

        $card = VirtualCards::create([
            'user_id' => $user->id,
            'card_number' => $card_number,
            'cvv' => $cvv,
            'card_id'=>$card_id,
            'expiry_month' => $expiry_month,
            'expiry_year' => $expiry_year,
            'currency' => $request->currency,
            'balance' => 0.00,
            'status' => 'active',
            'provider' => 'Flovide',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Virtual card created successfully.',
                'data' =>  $card,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ],201);
        }

        return redirect()->route('allvirtualcard')->with('success', 'Virtual card created successfully.');
    }


    public function allvirtualcard(Request $request)
    {
        $ownerId = session('owner_id');

        $cards = VirtualCards::where('user_id', $ownerId)->get();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Virtual cards fetched successfully',
                'data' => $cards,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ],201);
        }

        return view('business.allvirtualcard', compact('cards'));
    }

    public function showVirtualCard(Request $request, $id)
    {
        $card = VirtualCards::find($id);

        if (!$card) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Virtual card not found'
                ], 404);
            }

            abort(404, 'Virtual card not found');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Virtual card fetched successfully',
                'data' => $card
            ]);
        }

        return view('business.virtualcard_show', compact('card'));
    }




        public function destroy($id)
        {
            $card = VirtualCards::findOrFail($id);
            $card->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Virtual card deleted successfully.'
                ], 200);
            }

            return back()->with('success', 'Virtual card deleted successfully.');
        }



}
