<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
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
}
