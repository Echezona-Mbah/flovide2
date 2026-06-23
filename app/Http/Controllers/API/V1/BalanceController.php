<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\WebhookSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Currency;


class BalanceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/balances",
     *     tags={"Balances"},
     *     summary="Fetch all balances",
     *     description="Returns balances for the user matched by the provided public and secret keys.",
     *     @OA\Parameter(
     *         name="X-Public-Key",
     *         in="header",
     *         required=true,
     *         description="User public key",
     *         @OA\Schema(type="string", example="pk_live_xxxxxxxxx")
     *     ),
     *     @OA\Parameter(
     *         name="X-Secret-Key",
     *         in="header",
     *         required=true,
     *         description="User secret key",
     *         @OA\Schema(type="string", example="REDACTED_STRIPE_KEY")
     *     ),
     *     @OA\Parameter(
     *         name="currency",
     *         in="query",
     *         required=false,
     *         description="Filter by currency code",
     *         @OA\Schema(type="string", example="GBP")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of balances"
     *     ),
     *     @OA\Response(response=401, description="Invalid API keys")
     * )
     */
    // public function index(Request $request)
    // {
    //     $webhookSetting = $this->resolveKeyOwner($request);

    //     if (! $webhookSetting) {
    //         return response()->json(['error' => 'Invalid public key or secret key'], 401);
    //     }

    //     $mode = $this->resolveKeyMode($request, $webhookSetting);
    //     $isTestMode = $mode === 'test';

    //     $balances = Balance::query()
    //         ->where('user_id', $webhookSetting->user_id)
    //         ->when($request->filled('currency'), function ($query) use ($request) {
    //             $query->where('currency', strtoupper($request->currency));
    //         })
    //         ->latest()
    //         ->get()
    //         ->map(function ($balance) {
    //             return [
    //                 'id' => (string) $balance->id,
    //                 'name' => $balance->name,
    //                 'currency' => $balance->currency,
    //                 'balance' => (int) $balance->amount,
    //                 'created' => optional($balance->created_at)->toIso8601String(),
    //             ];
    //         });

    //     return response()->json($balances);
    // }

    public function index(Request $request)
{
    $webhookSetting = $this->resolveKeyOwner($request);

    if (! $webhookSetting) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid public key or secret key',
        ], 401);
    }

    $mode = $this->resolveKeyMode($request, $webhookSetting);

    $balances = Balance::query()
        ->where('user_id', $webhookSetting->user_id)
        ->where('mode', $mode)
        ->when($request->filled('currency'), function ($query) use ($request) {
            $query->where('currency', strtoupper($request->currency));
        })
        ->latest()
        ->get()
        ->map(function ($balance) {
            return [
                'id' => (string) $balance->id,
                'name' => $balance->name,
                'currency' => $balance->currency,
                'balance' => (int) $balance->amount,
                'created' => optional($balance->created_at)->toIso8601String(),
            ];
        });

    return response()->json([
        'success' => true,
        'mode' => $mode,
        'data' => $balances,
    ], 200);
}

    /**
     * @OA\Post(
     *     path="/api/v1/balances",
     *     tags={"Balances"},
     *     summary="Create a new balance",
     *     description="Creates a balance for the user matched by the provided public and secret keys.",
     *     @OA\Parameter(
     *         name="X-Public-Key",
     *         in="header",
     *         required=true,
     *         description="User public key",
     *         @OA\Schema(type="string", example="pk_live_xxxxxxxxx")
     *     ),
     *     @OA\Parameter(
     *         name="X-Secret-Key",
     *         in="header",
     *         required=true,
     *         description="User secret key",
     *         @OA\Schema(type="string", example="REDACTED_STRIPE_KEY")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","currency"},
     *             @OA\Property(property="name", type="string", example="Main"),
     *             @OA\Property(property="currency", type="string", example="GBP"),
     *             @OA\Property(property="amount", type="integer", example=0)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Balance created successfully"),
     *     @OA\Response(response=401, description="Invalid API keys"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    // public function store(Request $request)
    // {
    //     $webhookSetting = $this->resolveKeyOwner($request);

    //     if (! $webhookSetting) {
    //         return response()->json(['error' => 'Invalid public key or secret key'], 401);
    //     }

    //     $validated = $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'currency' => [
    //             'required',
    //             'string',
    //             'size:3',
    //             Rule::exists('currencies', 'code'),
    //         ],
    //         // 'amount' => ['nullable', 'numeric', 'min:0'],
    //     ]);

    //     $currencyCode = strtoupper($validated['currency']);

    //     $exists = Balance::where('user_id', $webhookSetting->user_id)
    //         ->where('currency', $currencyCode)
    //         ->exists();

    //     if ($exists) {
    //         $errorMessage = "You already have a $currencyCode balance. Duplicates are not allowed.";

    //         return $request->expectsJson()
    //             ? response()->json([
    //                 'success' => false,
    //                 'message' => $errorMessage,
    //                 'code' => 'DUPLICATE_BALANCE',
    //                 'data' => null
    //             ], 400)
    //             : redirect()->back()->withErrors(['message' => $errorMessage]);
    //     }

    //     $balance = Balance::create([
    //         'user_id' => $webhookSetting->user_id,
    //         'name' => $validated['name'],
    //         'currency' => $currencyCode,
    //         'amount' => $validated['amount'] ?? 0,
    //     ]);

    //     return response()->json([
    //         'id' => (string) $balance->id,
    //         'name' => $balance->name,
    //         'currency' => $balance->currency,
    //         'balance' => (float) $balance->amount,
    //         'created' => $balance->created_at->toIso8601String(),
    //     ], 201);
    // }

    public function store(Request $request)
    {
        $webhookSetting = $this->resolveKeyOwner($request);

        if (! $webhookSetting) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid public key or secret key',
            ], 401);
        }

        $isTestUrl = str_starts_with($request->path(), 'api/test/');
        $keyMode = $this->resolveKeyMode($request, $webhookSetting);

        /*
        * Force test URL to test mode.
        */
        $mode = $isTestUrl ? 'test' : $keyMode;

        if ($isTestUrl && $keyMode !== 'test') {
            return response()->json([
                'success' => false,
                'message' => 'Test API requires test keys',
            ], 403);
        }

        if (! $isTestUrl && $keyMode !== 'live') {
            return response()->json([
                'success' => false,
                'message' => 'Live API requires live keys',
            ], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'currency' => [
                'required',
                'string',
                'size:3',
                Rule::exists('currencies', 'code'),
            ],
        ]);

        $currencyCode = strtoupper($validated['currency']);

        $exists = Balance::where('user_id', $webhookSetting->user_id)
            ->where('mode', $mode)
            ->where('currency', $currencyCode)
            ->exists();

        if ($exists) {
            $errorMessage = "You already have a {$currencyCode} balance in {$mode} mode. Duplicates are not allowed.";

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'DUPLICATE_BALANCE',
                'mode' => $mode,
                'data' => null,
            ], 400);
        }

        $balance = Balance::create([
            'user_id' => $webhookSetting->user_id,
            'mode' => $mode,
            'name' => $validated['name'],
            'currency' => $currencyCode,
            'amount' => $mode === 'test'
                ? ($validated['balance'] ?? 0)
                : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Balance created successfully',
            'mode' => $mode,
            'data' => [
                'id' => (string) $balance->id,
                'name' => $balance->name,
                'currency' => $balance->currency,
                'balance' => (float) $balance->amount,
                'created' => $balance->created_at->toIso8601String(),
            ],
        ], 201);
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









    /**
 * @OA\Get(
 *     path="/api/v1/balances/{id}",
 *     tags={"Balances"},
 *     summary="Fetch single balance",
 *     description="Returns a single balance for the account matched by the provided public and secret keys.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Balance ID",
 *         @OA\Schema(type="integer", example=70)
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
 *     @OA\Response(
 *         response=200,
 *         description="Single balance returned successfully"
 *     ),
 *     @OA\Response(response=401, description="Invalid API keys"),
 *     @OA\Response(response=404, description="Balance not found")
 * )
 */
// public function show(Request $request, $id)
// {
//     $webhookSetting = $this->resolveKeyOwner($request);

//     if (! $webhookSetting) {
//         return response()->json(['error' => 'Invalid public key or secret key'], 401);
//     }

//     $balance = Balance::query()
//         ->where('user_id', $webhookSetting->user_id)
//         ->where('id', $id)
//         ->first();

//     if (! $balance) {
//         return response()->json(['error' => 'Balance not found'], 404);
//     }

//     return response()->json([
//         'id' => (string) $balance->id,
//         'name' => $balance->name,
//         'currency' => $balance->currency,
//         'balance' => (int) $balance->amount,
//         'created' => optional($balance->created_at)->toIso8601String(),
//     ]);
// }



public function show(Request $request, $id)
{
    $webhookSetting = $this->resolveKeyOwner($request);

    if (! $webhookSetting) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid public key or secret key',
        ], 401);
    }

    $path = $request->path();
    $isTestUrl = str_starts_with($path, 'api/test/');
    $keyMode = $this->resolveKeyMode($request, $webhookSetting);

    if ($isTestUrl && $keyMode !== 'test') {
        return response()->json([
            'success' => false,
            'message' => 'Test API requires test keys',
            'path' => $path,
            'key_mode' => $keyMode,
        ], 403);
    }

    if (! $isTestUrl && $keyMode !== 'live') {
        return response()->json([
            'success' => false,
            'message' => 'Live API requires live keys',
            'path' => $path,
            'key_mode' => $keyMode,
        ], 403);
    }

    $mode = $isTestUrl ? 'test' : 'live';

    $balance = Balance::query()
        ->where('user_id', $webhookSetting->user_id)
        ->where('mode', $mode)
        ->where('id', $id)
        ->first();

    if (! $balance) {
        return response()->json([
            'success' => false,
            'message' => 'Balance not found',
            'mode' => $mode,
            'data' => null,
        ], 404);
    }

    return response()->json([
        'success' => true,
        'mode' => $mode,
        'data' => [
            'id' => (string) $balance->id,
            'name' => $balance->name,
            'currency' => $balance->currency,
            'balance' => (float) $balance->amount,
            'created' => optional($balance->created_at)->toIso8601String(),
        ],
    ], 200);
}







}
