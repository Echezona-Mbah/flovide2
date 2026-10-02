<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TransactionPinController extends Controller
{
    public function status(Request $request)
    {
        $personal = auth('personal-api')->user();

        return response()->json([
            'success' => true,
            'message' => 'Pin status fetched',
            'code'    => 'PIN_STATUS_FETCHED',
            'data'    => [
                'has_pin' => !empty($personal->transaction_pin),
            ],
        ], 200);
    }

    public function setPin(Request $request)
    {
        $personal = auth('personal-api')->user();

        if (!empty($personal->transaction_pin)) {
            return response()->json([
                'success' => false,
                'message' => 'A transaction PIN is already set. Use update instead.',
                'code'    => 'PIN_ALREADY_SET',
                'data'    => null,
            ], 422);
        }

        $request->validate([
            'pin'              => 'required|digits:4|confirmed',
            'account_password' => 'required|string',
        ]);

        if (!Hash::check($request->account_password, $personal->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect account password.',
                'code'    => 'PASSWORD_INCORRECT',
                'data'    => null,
            ], 422);
        }

        $personal->transaction_pin = Hash::make($request->pin);
        $personal->transaction_pin_attempts = 0;
        $personal->transaction_pin_locked_until = null;
        $personal->save();

        Log::info('[Transaction PIN] Personal PIN set', ['personal_id' => $personal->id]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction PIN set successfully.',
            'code'    => 'PIN_SET',
            'data'    => null,
        ], 200);
    }

    public function updatePin(Request $request)
    {
        $personal = auth('personal-api')->user();

        if (empty($personal->transaction_pin)) {
            return response()->json([
                'success' => false,
                'message' => 'No transaction PIN set yet. Use set instead.',
                'code'    => 'PIN_NOT_SET',
                'data'    => null,
            ], 422);
        }

        $request->validate([
            'current_pin' => 'required|digits:4',
            'new_pin'     => 'required|digits:4|confirmed',
        ]);

        if (!Hash::check($request->current_pin, $personal->transaction_pin)) {
            Log::warning('[Transaction PIN] Personal incorrect current pin on update', ['personal_id' => $personal->id]);

            return response()->json([
                'success' => false,
                'message' => 'Current PIN is incorrect.',
                'code'    => 'PIN_INCORRECT',
                'data'    => null,
            ], 422);
        }

        $personal->transaction_pin = Hash::make($request->new_pin);
        $personal->transaction_pin_attempts = 0;
        $personal->transaction_pin_locked_until = null;
        $personal->save();

        Log::info('[Transaction PIN] Personal PIN updated', ['personal_id' => $personal->id]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction PIN updated successfully.',
            'code'    => 'PIN_UPDATED',
            'data'    => null,
        ], 200);
    }

    public function resetPin(Request $request)
    {
        $personal = auth('personal-api')->user();

        $request->validate([
            'account_password' => 'required|string',
            'new_pin'          => 'required|digits:4|confirmed',
        ]);

        if (!Hash::check($request->account_password, $personal->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect account password.',
                'code'    => 'PASSWORD_INCORRECT',
                'data'    => null,
            ], 422);
        }

        $personal->transaction_pin = Hash::make($request->new_pin);
        $personal->transaction_pin_attempts = 0;
        $personal->transaction_pin_locked_until = null;
        $personal->save();

        Log::info('[Transaction PIN] Personal PIN reset via password', ['personal_id' => $personal->id]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction PIN reset successfully.',
            'code'    => 'PIN_RESET',
            'data'    => null,
        ], 200);
    }

    // ── Verify PIN is correct (no transaction attached) ─────────────────────
    public function verifyPin(Request $request)
    {
        dd('dd');
        $personal = auth('personal-api')->user();

        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        if (empty($personal->transaction_pin)) {
            return response()->json([
                'success' => false,
                'message' => 'You have not set a transaction PIN yet.',
                'code'    => 'PIN_NOT_SET',
                'data'    => null,
            ], 422);
        }

        if ($personal->transaction_pin_locked_until && now()->lt($personal->transaction_pin_locked_until)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many incorrect PIN attempts. Try again after ' . $personal->transaction_pin_locked_until->diffForHumans(),
                'code'    => 'PIN_LOCKED',
                'data'    => null,
            ], 423);
        }

        $pinIsValid = Hash::check($request->pin, $personal->transaction_pin);

        if (!$pinIsValid) {
            $personal->transaction_pin_attempts += 1;

            if ($personal->transaction_pin_attempts >= 5) {
                $personal->transaction_pin_locked_until = now()->addMinutes(15);
                $personal->transaction_pin_attempts = 0;
            }

            $personal->save();

            Log::warning('[Transaction PIN] Personal verify failed', [
                'personal_id' => $personal->id,
                'attempts'    => $personal->transaction_pin_attempts,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Incorrect transaction PIN.',
                'code'    => 'PIN_INCORRECT',
                'data'    => null,
            ], 422);
        }

        if ($personal->transaction_pin_attempts > 0 || $personal->transaction_pin_locked_until) {
            $personal->transaction_pin_attempts = 0;
            $personal->transaction_pin_locked_until = null;
        }

        if (Hash::needsRehash($personal->transaction_pin)) {
            $personal->transaction_pin = Hash::make($request->pin);
            Log::info('[Transaction PIN] Personal rehashed on verify', ['personal_id' => $personal->id]);
        }

        $personal->save();

        Log::info('[Transaction PIN] Personal verify success', ['personal_id' => $personal->id]);

        return response()->json([
            'success' => true,
            'message' => 'PIN verified successfully.',
            'code'    => 'PIN_VERIFIED',
            'data'    => null,
        ], 200);
    }

}