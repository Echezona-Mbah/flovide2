<?php

namespace App\Http\Controllers\Fidelity;

use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use App\Services\FidelityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FidelityController extends Controller
{
    public function generateStaticAccount(Request $request, FidelityService $fidelity)
    {
        $isApi = $request->expectsJson();

        $validated = $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email',
            'bvn'           => 'nullable|string|max:11',
            'nin'           => 'nullable|string|max:11',
            'phone_number'  => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|string',
        ]);

        $user = auth()->user();
        if (!$user) {
            $msg = 'Unauthorized';
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'UNAUTHORIZED', 'data' => null], 401)
                : back()->withInput()->with('error', $msg);
        }

        $response = $fidelity->generateStaticVirtualAccount($validated);

        if (!$response['success']) {
            $msg = $response['data']['messageCode'] ?? 'Failed to generate virtual account.';

            Log::warning('[Fidelity] Static account creation failed', [
                'user_id' => $user->id,
                'data'    => $response['data'] ?? null,
            ]);

            return $isApi
                ? response()->json([
                    'success' => false,
                    'message' => $msg,
                    'code'    => 'FIDELITY_STATIC_ACCOUNT_FAILED',
                    'data'    => $response['data'] ?? null,
                ], $response['status'] ?: 422)
                : back()->withInput()->with('error', $msg);
        }

        $accountInfo = $response['data']['data']['accountInformation'] ?? [];

        // Persist the assigned account number to the user's profile
        $user->virtual_account_number = $accountInfo['accountNumber'] ?? null;
        $user->virtual_account_name   = $accountInfo['accountName'] ?? null;
        $user->virtual_account_bank   = $accountInfo['bankName'] ?? null;
        $user->save();

        Log::info('[Fidelity] Static account assigned', [
            'user_id'        => $user->id,
            'account_number' => $accountInfo['accountNumber'] ?? null,
        ]);

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Virtual account generated successfully.',
                'code'    => 'FIDELITY_STATIC_ACCOUNT_CREATED',
                'data'    => $response['data']['data'] ?? null,
            ], 200);
        }

        return back()->with('success', 'Virtual account generated successfully.')
            ->with('account', $accountInfo);
    }

    public function generateDynamicAccount(Request $request, FidelityService $fidelity)
    {
        $isApi = $request->expectsJson();

        $validated = $request->validate([
            'amount'           => 'required|numeric|min:1',
            'duration_minutes' => 'nullable|integer|min:1',
            'balance_id'       => 'required|string',
        ]);

        $user = auth()->user();
        if (!$user) {
            $msg = 'Unauthorized';
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'UNAUTHORIZED', 'data' => null], 401)
                : back()->withInput()->with('error', $msg);
        }

        $balance = \App\Models\Balance::where('id', $validated['balance_id'])
            ->where('user_id', $user->id)
            ->first();

        if (!$balance) {
            $msg = 'Wallet not found or does not belong to your account.';
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'INVALID_WALLET', 'data' => null], 422)
                : back()->withInput()->with('error', $msg);
        }

        $response = $fidelity->generateDynamicVirtualAccount(
            $validated['amount'],
            $validated['duration_minutes'] ?? 30
        );

        if (!$response['success']) {
            $msg = $response['data']['messageCode'] ?? 'Failed to generate virtual account.';

            Log::warning('[Fidelity] Dynamic account creation failed', [
                'user_id' => $user->id,
                'data'    => $response['data'] ?? null,
            ]);

            return $isApi
                ? response()->json([
                    'success' => false,
                    'message' => $msg,
                    'code'    => 'FIDELITY_DYNAMIC_ACCOUNT_FAILED',
                    'data'    => $response['data'] ?? null,
                ], $response['status'] ?: 422)
                : back()->withInput()->with('error', $msg);
        }

        $accountData = $response['data']['data'] ?? [];

        // Create a pending transaction tied to the accountGenerationId (referenceId)
        $tx = TransactionHistory::create([
            'user_id'           => $user->id,
            'balance_id'        => $balance->id,
            'payment_provider'  => 'fidelity',
            'transaction_type'  => 'payment',
            'method'            => 'credit',
            'amount'            => $validated['amount'],
            'currency'          => 'NGN',
            'status'            => 'pending',
            'reference'         => $accountData['referenceId'] ?? null,
            'payment_reference' => $accountData['referenceId'] ?? null,
            'order_id'          => $accountData['referenceId'] ?? null, // used to match webhook's accountGenerationId
        ]);

        Log::info('[Fidelity] Dynamic account transaction created', [
            'tx_id'        => $tx->id,
            'reference_id' => $accountData['referenceId'] ?? null,
            'account_no'   => $accountData['accountNumber'] ?? null,
        ]);

        if ($isApi) {
            return response()->json([
                'success' => true,
                'message' => 'Virtual account generated successfully.',
                'code'    => 'FIDELITY_DYNAMIC_ACCOUNT_CREATED',
                'data'    => array_merge($accountData, ['transaction_id' => $tx->id]),
            ], 200);
        }

        return back()->with('success', 'Virtual account generated successfully.')
            ->with('account', array_merge($accountData, ['transaction_id' => $tx->id]));
    }
}