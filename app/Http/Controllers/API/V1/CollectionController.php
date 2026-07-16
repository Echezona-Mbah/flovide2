<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\TeamMembers;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\WebhookSetting;
use App\Services\BlaaizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CollectionController extends Controller
{

/**
 * @OA\Post(
 *     path="/api/v1/collections/interac",
 *     tags={"Collections"},
 *     summary="Initiate Interac collection (top-up)",
 *     description="Sends an Interac Auto Deposit money request to the payer's email, crediting the account's CAD wallet once approved.",
 *     @OA\Parameter(
 *         name="X-Public-Key",
 *         in="header",
 *         required=true,
 *         @OA\Schema(type="string", example="pk_live_xxxxxxxxxxxxxxxxx")
 *     ),
 *     @OA\Parameter(
 *         name="X-Secret-Key",
 *         in="header",
 *         required=true,
 *         @OA\Schema(type="string", example="sk_live_xxxxxxxxxxxxxxxxx")
 *     ),
 *     @OA\Response(response=200, description="Interac collection request sent successfully"),
 *     @OA\Response(response=401, description="Invalid public key or secret key"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
public function collectInterac(Request $request, BlaaizService $blaaiz)
{
    $user = $this->resolveKeyUser($request);

    if (! $user) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid public key or secret key',
        ], 401);
    }

    $webhookSetting = $this->resolveKeyOwner($request);

    if (! $webhookSetting) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid public key or secret key',
        ], 401);
    }

    $path      = $request->path();
    $isTestUrl = str_starts_with($path, 'api/test/');
    $keyMode   = $this->resolveKeyMode($request, $webhookSetting);

    if ($isTestUrl && $keyMode !== 'test') {
        return response()->json([
            'success'  => false,
            'message'  => 'Test API requires test keys',
            'path'     => $path,
            'key_mode' => $keyMode,
        ], 403);
    }

    if (! $isTestUrl && $keyMode !== 'live') {
        return response()->json([
            'success'  => false,
            'message'  => 'Live API requires live keys',
            'path'     => $path,
            'key_mode' => $keyMode,
        ], 403);
    }

    $mode = $isTestUrl ? 'test' : 'live';

    $validator = Validator::make($request->all(), [
        'amount' => 'required|numeric|min:0.1',
        'email'  => 'required|email',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'code'    => 'VALIDATION_ERROR',
            'errors'  => $validator->errors(),
        ], 422);
    }

    $validated = $validator->validated();
    [$ownerId, $memberId, $role, $owner] = $this->resolveOwnerAndMember($request, $user);

    if (! $ownerId || ! $owner) {
        return response()->json([
            'success' => false,
            'message' => 'Owner account not found',
            'code'    => 'OWNER_NOT_FOUND',
            'data'    => null,
        ], 422);
    }

    // ── Resolve the CAD wallet automatically ────────────────────────────
    $balance = Balance::where('user_id', $ownerId)
        ->where('currency', 'CAD')
        ->where('mode', $mode)
        ->first();

    if (! $balance) {
        return response()->json([
            'success' => false,
            'message' => 'No CAD wallet found for this account.',
            'code'    => 'BALANCE_NOT_FOUND',
            'data'    => null,
        ], 404);
    }

    $amount = (float) $validated['amount'];

    // ── Collection fee — same rules as the dashboard Interac top-up ────
    $userFee = \App\Models\UserCurrencyFee::where('user_id', $ownerId)
        ->where('currency', 'CAD')
        ->first();

    Log::info('[API Interac Collect] Fee lookup', [
        'owner_id' => $ownerId,
        'found'    => (bool) $userFee,
        'enabled'  => $userFee->collection_enabled ?? null,
    ]);

    if (! $userFee || ! $userFee->collection_enabled) {
        return response()->json([
            'success' => false,
            'message' => 'Contact your marketer to enable collection pricing for CAD.',
            'code'    => 'COLLECTION_DISABLED',
            'data'    => null,
        ], 422);
    }

    if ($userFee->collection_min > 0 && $amount < $userFee->collection_min) {
        return response()->json([
            'success' => false,
            'message' => 'Minimum top-up for CAD is ' . number_format($userFee->collection_min, 2),
            'code'    => 'BELOW_COLLECTION_MIN',
            'data'    => null,
        ], 422);
    }

    if ($userFee->collection_max > 0 && $amount > $userFee->collection_max) {
        return response()->json([
            'success' => false,
            'message' => 'Maximum top-up for CAD is ' . number_format($userFee->collection_max, 2),
            'code'    => 'ABOVE_COLLECTION_MAX',
            'data'    => null,
        ], 422);
    }

    $platformFee = $userFee->calcCollectionFee($amount);
    $netAmount   = $userFee->collectionAmountAfterFee($amount);

    Log::info('[API Interac Collect] Fee calculated', [
        'platform_fee' => $platformFee,
        'net_amount'   => $netAmount,
    ]);

    if ($amount <= $platformFee) {
        return response()->json([
            'success' => false,
            'message' => 'Amount must be greater than the platform fee of ' . number_format($platformFee, 2) . ' CAD.',
            'code'    => 'AMOUNT_BELOW_FEE',
            'data'    => [
                'amount'       => $amount,
                'platform_fee' => $platformFee,
            ],
        ], 422);
    }

    // ── Test mode: skip Blaaiz, simulate success ────────────────────────
    if ($mode === 'test') {
        $transaction = TransactionHistory::create([
            'user_id'           => $ownerId,
            'balance_id'        => $balance->id,
            'mode'              => 'test',
            'payment_provider'  => 'interac',
            'transaction_type'  => 'payment',
            'method'            => 'credit',
            'type'              => 'credit',
            'payment_method'    => 'auto',
            'sender'            => $validated['email'],
            'amount'            => $amount,
            'fees'              => $platformFee,
            'platform_fee'      => $platformFee,
            'recipient_amount'  => $netAmount,
            'currency'          => 'CAD',
            'status'            => 'pending',
            'reference'         => 'CA' . Str::random(10),
            'order_id'          => 'TEST_' . Str::uuid(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Test Interac collection created successfully.',
            'code'    => 'TEST_SUCCESS',
            'mode'    => 'test',
            'data'    => $this->txData($transaction->id),
        ], 200);
    }

    // ── Live: call Blaaiz ────────────────────────────────────────────────
    $response = $blaaiz->initiateInteracMoneyRequest([
        'email'  => $validated['email'],
        'amount' => $amount,
    ]);

    Log::info('[API Interac Collect] Blaaiz response', [
        'success' => $response['success'],
        'status'  => $response['status'],
        'data'    => $response['data'],
    ]);

    if (! $response['success']) {
        $errorMsg = $response['data']['message']
            ?? $response['data']['error_description']
            ?? 'Interac request failed. Please try again.';

        return response()->json([
            'success' => false,
            'message' => $errorMsg,
            'code'    => 'INTERAC_REQUEST_FAILED',
            'data'    => $response['data'] ?? null,
        ], $response['status']);
    }

    $responseData = $response['data'] ?? [];

    $transaction = TransactionHistory::create([
        'user_id'           => $ownerId,
        'balance_id'        => $balance->id,
        'mode'              => 'live',
        'payment_provider'  => 'interac',
        'transaction_type'  => 'payment',
        'method'            => 'credit',
        'type'              => 'credit',
        'payment_method'    => 'auto',
        'sender'            => $validated['email'],
        'amount'            => $amount,
        'fees'              => $platformFee,
        'platform_fee'      => $platformFee,
        'recipient_amount'  => $netAmount,
        'currency'          => 'CAD',
        'status'            => 'pending',
        'reference'         => $responseData['reference']       ?? null,
        'payment_reference' => $responseData['reference']       ?? null,
        'order_id'          => $responseData['transaction_id']  ?? null,
    ]);

    return response()->json([
        'success' => true,
        'message' => $responseData['message'] ?? 'Interac collection request sent successfully.',
        'code'    => 'INTERAC_COLLECTION_INITIATED',
        'mode'    => 'live',
        'data'    => [
            'transaction_id' => $responseData['transaction_id'] ?? null,
            'reference'      => $responseData['reference']      ?? null,
            'expires_at'     => $responseData['expires_at']     ?? null,
            'amount'         => $amount,
            'fee'            => $platformFee,
            'net_amount'     => $netAmount,
            'currency'       => 'CAD',
            'email'          => $validated['email'],
        ],
    ], 200);
}


    // ── Helpers (mirrors TransactionController's key/owner resolution) ──────

    private function txData(string $txId): array
    {
        $tx = TransactionHistory::findOrFail($txId);

        return [
            'id'                        => (string) $tx->id,
            'reference'                 => $tx->reference,
            'order_id'                  => $tx->order_id,
            'created_at'                => optional($tx->created_at)->toIso8601String(),
        ];
    }

    private function resolveOwnerAndMember(Request $request, ?User $actor): array
    {
        if (! $actor) {
            return [null, null, null, null];
        }

        $team = TeamMembers::where('user_id', $actor->id)->first();

        $ownerId  = $team ? $team->owner_id : $actor->id;
        $memberId = $team ? $actor->id : null;
        $role     = $team ? $team->role : 'Owner';
        $owner    = User::find($ownerId);

        return [$ownerId, $memberId, $role, $owner];
    }

    private function resolveKeyUser(Request $request): ?User
    {
        $publicKey = $request->header('X-Public-Key');
        $secretKey = $request->header('X-Secret-Key');

        if (! $publicKey || ! $secretKey) {
            return null;
        }

        $webhookSetting = WebhookSetting::query()
            ->where(function ($query) use ($publicKey, $secretKey) {
                $query->where('live_public_key', $publicKey)
                    ->where('live_secret_key', $secretKey);
            })
            ->orWhere(function ($query) use ($publicKey, $secretKey) {
                $query->where('test_public_key', $publicKey)
                    ->where('test_secret_key', $secretKey);
            })
            ->first();

        return $webhookSetting ? User::find($webhookSetting->user_id) : null;
    }

    private function resolveKeyOwner(Request $request): ?WebhookSetting
    {
        $publicKey = $request->header('X-Public-Key');
        $secretKey = $request->header('X-Secret-Key');

        if (! $publicKey || ! $secretKey) {
            return null;
        }

        return WebhookSetting::query()
            ->where(function ($query) use ($publicKey, $secretKey) {
                $query->where('live_public_key', $publicKey)
                    ->where('live_secret_key', $secretKey);
            })
            ->orWhere(function ($query) use ($publicKey, $secretKey) {
                $query->where('test_public_key', $publicKey)
                    ->where('test_secret_key', $secretKey);
            })
            ->first();
    }

    private function resolveKeyMode(Request $request, WebhookSetting $setting): string
    {
        $publicKey = $request->header('X-Public-Key');
        $secretKey = $request->header('X-Secret-Key');

        if (
            $setting->test_public_key === $publicKey &&
            $setting->test_secret_key === $secretKey
        ) {
            return 'test';
        }

        return 'live';
    }

}