<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\BusinessWebhookLog;
use App\Models\WebhookSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebhookController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/v1/webhooks",
     *     tags={"Webhooks"},
     *     summary="Fetch webhook settings",
     *     description="Returns the authenticated user's webhook configuration.",
     *     @OA\Response(
     *         response=200,
     *         description="Webhook settings",
     *         @OA\JsonContent(
     *             @OA\Property(property="secret_key", type="string", example="sk_x8f2n9c4p7m1q3z6k5t8v2b1w4y7r9d"),
     *             @OA\Property(property="public_key", type="string", example="pk_x8f2n9c4p7m1q3z6k5t8v2b1w4y7r9d"),
     *             @OA\Property(
     *                 property="ip_whitelist",
     *                 type="array",
     *                 @OA\Items(type="string", example="192.168.1.1")
     *             ),
     *             @OA\Property(property="callback_url", type="string", format="uri", example="https://example.com/callback"),
     *             @OA\Property(property="webhook_url", type="string", format="uri", example="https://example.com/webhook")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json($this->buildPayload($user));
    }

    public function logs(Request $request)
    {
        $setting = $this->resolveSetting($request);
        if (! $setting) {
            return response()->json([
                'message' => 'Invalid public key or secret key',
                'data' => null,
            ], 401);
        }

        $mode = $this->resolveMode($request, $setting);
        $isTestUrl = str_starts_with($request->path(), 'api/test/');
        if (($isTestUrl && $mode !== 'test') || (! $isTestUrl && $mode !== 'live')) {
            return response()->json([
                'message' => ucfirst($isTestUrl ? 'test' : 'live') . ' API requires matching keys',
                'data' => null,
            ], 403);
        }

        $validated = $request->validate([
            'type' => ['nullable', 'in:COLLECTION,PAYOUT'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $logs = BusinessWebhookLog::where('business_id', $setting->user_id)
            ->where('mode', $mode)
            ->when($validated['type'] ?? null, fn ($query, $type) => $query->where('type', $type))
            ->with(['businessTransaction', 'histories'])
            ->latest()
            ->paginate($validated['per_page'] ?? 20);

        $logs->getCollection()->transform(fn (BusinessWebhookLog $log) => $this->formatLog($log));

        return response()->json([
            'message' => 'Webhook logs retrieved successfully',
            'data' => $logs,
        ]);
    }



    /**
     * @OA\Post(
     *     path="/api/v1/webhooks",
     *     tags={"Webhooks"},
     *     summary="Create or update webhook settings",
     *     description="Stores webhook configuration for the authenticated user.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="ip_whitelist",
     *                 type="array",
     *                 @OA\Items(type="string", example="192.168.1.1")
     *             ),
     *             @OA\Property(property="callback_url", type="string", format="uri", example="https://example.com/callback"),
     *             @OA\Property(property="webhook_url", type="string", format="uri", example="https://example.com/webhook")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Webhook settings saved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="secret_key", type="string", example="sk_x8f2n9c4p7m1q3z6k5t8v2b1w4y7r9d"),
     *             @OA\Property(property="public_key", type="string", example="pk_x8f2n9c4p7m1q3z6k5t8v2b1w4y7r9d"),
     *             @OA\Property(
     *                 property="ip_whitelist",
     *                 type="array",
     *                 @OA\Items(type="string", example="192.168.1.1")
     *             ),
     *             @OA\Property(property="callback_url", type="string", format="uri", example="https://example.com/callback"),
     *             @OA\Property(property="webhook_url", type="string", format="uri", example="https://example.com/webhook")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'ip_whitelist' => ['nullable', 'array'],
            'ip_whitelist.*' => ['string'],
            'callback_url' => ['nullable', 'url'],
            'webhook_url' => ['nullable', 'url'],
        ]);

        $user->secret_key = $user->secret_key ?: 'sk_' . Str::lower(Str::random(32));
        $user->public_key = $user->public_key ?: 'pk_' . Str::lower(Str::random(32));
        $user->ip_whitelist = $validated['ip_whitelist'] ?? [];
        $user->callback_url = $validated['callback_url'] ?? null;
        $user->webhook_url = $validated['webhook_url'] ?? null;
        $user->save();

        return response()->json($this->buildPayload($user));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/webhooks/regenerate-secret",
     *     tags={"Webhooks"},
     *     summary="Generate a new secret key",
     *     description="Regenerates the authenticated user's webhook secret key.",
     *     @OA\Response(
     *         response=200,
     *         description="Secret key regenerated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="secret_key", type="string", example="sk_x8f2n9c4p7m1q3z6k5t8v2b1w4y7r9d")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function regenerateSecret()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user->secret_key = 'sk_' . Str::lower(Str::random(32));
        $user->save();

        return response()->json([
            'secret_key' => $user->secret_key,
        ]);
    }

    private function buildPayload($user): array
    {
        return [
            'secret_key' => $user->secret_key ?: 'sk_' . Str::lower(Str::random(32)),
            'public_key' => $user->public_key ?: 'pk_' . Str::lower(Str::random(32)),
            'ip_whitelist' => $user->ip_whitelist ?? [],
            'callback_url' => $user->callback_url,
            'webhook_url' => $user->webhook_url,
        ];
    }

    private function resolveSetting(Request $request): ?WebhookSetting
    {
        $publicKey = $request->header('X-Public-Key');
        $secretKey = $request->header('X-Secret-Key');

        if (! $publicKey || ! $secretKey) {
            return null;
        }

        return WebhookSetting::query()
            ->where(fn ($query) => $query->where('live_public_key', $publicKey)->where('live_secret_key', $secretKey))
            ->orWhere(fn ($query) => $query->where('test_public_key', $publicKey)->where('test_secret_key', $secretKey))
            ->first();
    }

    private function resolveMode(Request $request, WebhookSetting $setting): string
    {
        return $setting->test_public_key === $request->header('X-Public-Key')
            && $setting->test_secret_key === $request->header('X-Secret-Key')
                ? 'test'
                : 'live';
    }

    private function formatLog(BusinessWebhookLog $log): array
    {
        $transaction = $log->businessTransaction;

        return [
            'id' => (string) $log->id,
            'business_id' => (string) $log->business_id,
            'business_transaction_id' => $log->business_transaction_id,
            'business_crypto_wallet_transaction_id' => null,
            'type' => $log->type,
            'webhook_url' => $log->webhook_url,
            'payload' => $log->payload,
            'response' => $log->response,
            'status' => $log->status,
            'delivery_status' => $log->delivery_status,
            'created_at' => optional($log->created_at)->toIso8601String(),
            'updated_at' => optional($log->updated_at)->toIso8601String(),
            'business_transaction' => $transaction ? [
                'id' => (string) $transaction->id,
                'reference' => $transaction->reference,
                'status' => $transaction->status,
                'type' => $transaction->transaction_type,
                'sender_local_amount' => (float) $transaction->amount,
                'sender_local_currency' => $transaction->currency,
                'amount' => (float) ($transaction->recipient_amount ?? $transaction->amount),
                'payment_method' => $transaction->payment_method,
            ] : null,
            'business_crypto_wallet_transaction' => null,
            'webhook_log_histories' => $log->histories->map(fn ($history) => [
                'id' => (string) $history->id,
                'metadata' => $history->metadata,
                'headers' => $history->headers,
                'http_status' => $history->http_status,
                'response' => $history->response,
                'created_at' => optional($history->created_at)->toIso8601String(),
            ])->values()->all(),
        ];
    }
}
