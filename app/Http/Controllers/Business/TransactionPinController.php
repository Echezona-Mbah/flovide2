<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TransactionPinController extends Controller
{

public function index(){
    return view('business.transaction-pin');
}



    // ── Check whether the logged-in user has a PIN set ─────────────────────
    public function status(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'message' => 'Pin status fetched',
            'code'    => 'PIN_STATUS_FETCHED',
            'data'    => [
                'has_pin' => !empty($user->transaction_pin),
            ],
        ], 200);
    }

    // ── Set PIN for the first time (no current pin required) ───────────────
    public function setPin(Request $request)
    {
        $user = Auth::user();

        if (!empty($user->transaction_pin)) {
            return response()->json([
                'success' => false,
                'message' => 'A transaction PIN is already set. Use update instead.',
                'code'    => 'PIN_ALREADY_SET',
                'data'    => null,
            ], 422);
        }

        $request->validate([
            'pin'              => 'required|digits:4|confirmed', // expects pin_confirmation
            'account_password' => 'required|string',
        ]);

        if (!Hash::check($request->account_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect account password.',
                'code'    => 'PASSWORD_INCORRECT',
                'data'    => null,
            ], 422);
        }

        $user->transaction_pin = Hash::make($request->pin);
        $user->transaction_pin_attempts = 0;
        $user->transaction_pin_locked_until = null;
        $user->save();

        Log::info('[Transaction PIN] Business PIN set', ['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction PIN set successfully.',
            'code'    => 'PIN_SET',
            'data'    => null,
        ], 200);
    }

    // ── Update an existing PIN (requires the current PIN) ───────────────────
    public function updatePin(Request $request)
    {
        $user = Auth::user();

        if (empty($user->transaction_pin)) {
            return response()->json([
                'success' => false,
                'message' => 'No transaction PIN set yet. Use set instead.',
                'code'    => 'PIN_NOT_SET',
                'data'    => null,
            ], 422);
        }

        $request->validate([
            'current_pin' => 'required|digits:4',
            'new_pin'     => 'required|digits:4|confirmed', // expects new_pin_confirmation
        ]);

        if (!Hash::check($request->current_pin, $user->transaction_pin)) {
            Log::warning('[Transaction PIN] Business incorrect current pin on update', ['user_id' => $user->id]);

            return response()->json([
                'success' => false,
                'message' => 'Current PIN is incorrect.',
                'code'    => 'PIN_INCORRECT',
                'data'    => null,
            ], 422);
        }

        $user->transaction_pin = Hash::make($request->new_pin);
        $user->transaction_pin_attempts = 0;
        $user->transaction_pin_locked_until = null;
        $user->save();

        Log::info('[Transaction PIN] Business PIN updated', ['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction PIN updated successfully.',
            'code'    => 'PIN_UPDATED',
            'data'    => null,
        ], 200);
    }

    // ── Forgot pin: reset via account password (no old pin needed) ─────────
    public function resetPin(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'account_password' => 'required|string',
            'new_pin'          => 'required|digits:4|confirmed',
        ]);

        if (!Hash::check($request->account_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect account password.',
                'code'    => 'PASSWORD_INCORRECT',
                'data'    => null,
            ], 422);
        }

        $user->transaction_pin = Hash::make($request->new_pin);
        $user->transaction_pin_attempts = 0;
        $user->transaction_pin_locked_until = null;
        $user->save();

        Log::info('[Transaction PIN] Business PIN reset via password', ['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction PIN reset successfully.',
            'code'    => 'PIN_RESET',
            'data'    => null,
        ], 200);
    }
}