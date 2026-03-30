<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SendMoneyController;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\WebhookSetting;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/transactions",
     *     tags={"Transactions"},
     *     summary="List transactions",
     *     description="Returns all transactions belonging to the account matched by the provided public key and secret key.",
     *     @OA\Parameter(
     *         name="X-Public-Key",
     *         in="header",
     *         required=true,
     *         description="User public key",
     *         @OA\Schema(type="string", example="pk_live_xxxxxxxxxxxxxxxxx")
     *     ),
     *     @OA\Parameter(
     *         name="X-Secret-Key",
     *         in="header",
     *         required=true,
     *         description="User secret key",
     *         @OA\Schema(type="string", example="REDACTED_STRIPE_KEY")
     *     ),
     *     @OA\Response(response=200, description="Transactions retrieved successfully"),
     *     @OA\Response(response=401, description="Invalid public key or secret key")
     * )
     */
    public function index(Request $request)
    {
        $user = $this->resolveKeyUser($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid public key or secret key',
            ], 401);
        }

        $transactions = TransactionHistory::where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Transactions retrieved successfully',
            'data' => $transactions,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/transactions/{id}",
     *     tags={"Transactions"},
     *     summary="Fetch single transaction",
     *     description="Returns one transaction record for the matched account.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transaction ID",
     *         @OA\Schema(type="integer", example=1001)
     *     ),
     *     @OA\Parameter(
     *         name="X-Public-Key",
     *         in="header",
     *         required=true,
     *         description="User public key",
     *         @OA\Schema(type="string", example="pk_live_xxxxxxxxxxxxxxxxx")
     *     ),
     *     @OA\Parameter(
     *         name="X-Secret-Key",
     *         in="header",
     *         required=true,
     *         description="User secret key",
     *         @OA\Schema(type="string", example="REDACTED_STRIPE_KEY")
     *     ),
     *     @OA\Response(response=200, description="Transaction retrieved successfully"),
     *     @OA\Response(response=401, description="Invalid public key or secret key"),
     *     @OA\Response(response=404, description="Transaction not found")
     * )
     */
    public function show(Request $request, $id)
    {
        $user = $this->resolveKeyUser($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid public key or secret key',
            ], 401);
        }

        $transaction = TransactionHistory::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (! $transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction retrieved successfully',
            'data' => $transaction,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/transactions",
     *     tags={"Transactions"},
     *     summary="Initiate transaction",
     *     description="Initiates a new transaction for the matched account.",
     *     @OA\Parameter(
     *         name="X-Public-Key",
     *         in="header",
     *         required=true,
     *         description="User public key",
     *         @OA\Schema(type="string", example="pk_live_xxxxxxxxxxxxxxxxx")
     *     ),
     *     @OA\Parameter(
     *         name="X-Secret-Key",
     *         in="header",
     *         required=true,
     *         description="User secret key",
     *         @OA\Schema(type="string", example="REDACTED_STRIPE_KEY")
     *     ),
     *     @OA\Response(response=200, description="Transaction initiated successfully"),
     *     @OA\Response(response=401, description="Invalid public key or secret key"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $user = $this->resolveKeyUser($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid public key or secret key',
            ], 401);
        }

        // Delegate to your existing SendMoneyController
        $sendMoney = app()->make(\App\Http\Controllers\Business\SendMoneyController::class);

        return $sendMoney->sendTransaction($request);
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
}
