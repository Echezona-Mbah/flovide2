<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Beneficia;
use App\Models\TeamMembers;
use App\Models\User;
use App\Models\WebhookSetting;
use App\Services\OrchardService;
use App\Services\PayazaService;
use App\Services\PivotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BeneficiaryController extends Controller
{
    protected $pivot;
    protected $payaza;
    protected $orchard;

    public function __construct(
        PivotService $pivot,
        PayazaService $payaza,
        OrchardService $orchard
    ) {
        $this->pivot = $pivot;
        $this->payaza = $payaza;
        $this->orchard = $orchard;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/beneficiaries",
     *     tags={"Beneficiaries"},
     *     summary="List beneficiaries",
     *     description="Returns all beneficiaries belonging to the account matched by the provided public key and secret key.",
     *     @OA\Parameter(
     *         name="X-Public-Key",
     *         in="header",
     *         required=true,
     *         description="User public key",
     *         @OA\Schema(type="string", example="env('STRIPE_PUBLIC')")
     *     ),
     *     @OA\Parameter(
     *         name="X-Secret-Key",
     *         in="header",
     *         required=true,
     *         description="User secret key",
     *         @OA\Schema(type="string", example="env('STRIPE_SECRET')")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Beneficiaries retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid public key or secret key"
     *     )
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

        $beneficias = Beneficia::where('user_id', $user->id)->latest()->get();

        $data = $beneficias->map(function ($b) {
            return [
                'id' => $b->id,
                'country' => $b->country,
                'default_reference' => $b->default_reference ?? 'Invoice',
                'alias' => $b->alias,
                'type' => $b->type,
                'created' => optional($b->created_at)->toIso8601String(),
                'bank_account' => [
                    'account_name' => $b->account_name,
                    'sort_code' => $b->sort_code ?? null,
                    'bank_code' => $b->bank_code,
                    'account_number' => $b->account_number,
                    'bank_name' => $b->bank ?? null,
                    'currency' => $b->currency,
                ],
            ];
        });

        return response()->json([
            'message' => 'Beneficiaries retrieved successfully',
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/beneficiaries/account-inquiry",
     *     tags={"Beneficiaries"},
     *     summary="Account inquiry",
     *     description="Validates account details before creating a beneficiary.",
     *     @OA\Parameter(
     *         name="X-Public-Key",
     *         in="header",
     *         required=true,
     *         description="User public key",
     *         @OA\Schema(type="string", example="env('STRIPE_PUBLIC')")
     *     ),
     *     @OA\Parameter(
     *         name="X-Secret-Key",
     *         in="header",
     *         required=true,
     *         description="User secret key",
     *         @OA\Schema(type="string", example="env('STRIPE_SECRET')")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"currency","bank_code","account_number"},
     *             @OA\Property(property="currency", type="string", example="NGN"),
     *             @OA\Property(property="bank_code", type="string", example="058"),
     *             @OA\Property(property="account_number", type="string", example="1234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Account inquiry successful"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid public key or secret key"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation or provider error"
     *     )
     * )
     */
    public function accountInquiry(Request $request)
    {
        $user = $this->resolveKeyUser($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid public key or secret key',
            ], 401);
        }

        $request->validate([
            'currency' => 'required|string|size:3',
            'bank_code' => 'required|string',
            'account_number' => 'required|string',
        ]);

        $currency = strtoupper($request->currency);
        $bankCode = strtoupper($request->bank_code);

        if ($currency === 'UGX' && env('PIVOT_ENABLED', false)) {
            $auth = $this->pivot->authenticate();

            if (isset($auth['error'])) {
                return response()->json($auth, 500);
            }

            $token = $auth['tokenResponse']['accessToken'];
            $mobileCodes = ['MTN', 'AIRTEL'];

            if (in_array($bankCode, $mobileCodes, true)) {
                $payload = [
                    'serviceCode' => env('PIVOT_UGX_MOBILE_SERVICE'),
                    'accountNumber' => $request->account_number,
                    'msisdn' => $request->account_number,
                    'extraData' => [
                        'amount' => '0',
                    ],
                ];
            } else {
                $payload = [
                    'serviceCode' => env('PIVOT_UGX_BANK_SERVICE'),
                    'accountNumber' => $request->account_number,
                    'msisdn' => $request->account_number,
                    'extraData' => [
                        'bankSortCode' => $request->bank_code,
                        'amount' => '0',
                    ],
                ];
            }

            $response = $this->pivot->accountValidation($token, $payload);

            return response()->json([
                'success' => true,
                // 'provider' => 'pivot',
                'accountName' => $response['customerNames'] ?? null,
                'data' => $response,
            ]);
        }

        if ($currency === 'GHS' && env('APP_MOBILE', false)) {
            $payload = [
                'customer_number' => $request->account_number,
                'exttrid' => uniqid('APPM_'),
                'service_id' => env('ORCHARD_SERVICE_ID'),
                'nw' => 'BNK',
                'bank_code' => $request->bank_code,
                'trans_type' => 'AII',
                'ts' => now()->utc()->format('Y-m-d H:i:s'),
            ];

            $response = $this->orchard->accountInquiry($payload);

            return response()->json([
                'success' => true,
                // 'provider' => 'appmobile',
                'data' => $response,
            ]);
        }

        $payazaCurrencies = ['UGX', 'NGN', 'TZS', 'KES', 'XOF', 'XAF', 'ZAR', 'GHS'];

        if (in_array($currency, $payazaCurrencies, true) && env('PAYAZA_ENABLED', false)) {
            $response = $this->payaza->accountEnquiry(
                $currency,
                $request->bank_code,
                $request->account_number
            );

            return response()->json([
                'success' => true,
                // 'provider' => 'payaza',
                'data' => $response,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No provider available for this currency.',
            'currency' => $currency,
        ], 422);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/beneficiaries",
     *     tags={"Beneficiaries"},
     *     summary="Create beneficiary",
     *     description="Creates a beneficiary for bank or mobile transfer using the provided public key and secret key.",
     *     @OA\Parameter(
     *         name="X-Public-Key",
     *         in="header",
     *         required=true,
     *         description="User public key",
     *         @OA\Schema(type="string", example="env('STRIPE_PUBLIC')")
     *     ),
     *     @OA\Parameter(
     *         name="X-Secret-Key",
     *         in="header",
     *         required=true,
     *         description="User secret key",
     *         @OA\Schema(type="string", example="env('STRIPE_SECRET')")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type","transfer_method","bank"},
     *             @OA\Property(property="type", type="string", example="individual"),
     *             @OA\Property(property="firstNames", type="string", example="John"),
     *             @OA\Property(property="lastName", type="string", example="Doe"),
     *             @OA\Property(property="name", type="string", example="Acme Ltd"),
     *             @OA\Property(property="transfer_method", type="string", example="bank"),
     *             @OA\Property(
     *                 property="bank",
     *                 type="object",
     *                 required={"country","currency","accountHolder"},
     *                 @OA\Property(property="country", type="string", example="NG"),
     *                 @OA\Property(property="currency", type="string", example="NGN"),
     *                 @OA\Property(property="accountHolder", type="string", example="John Doe"),
     *                 @OA\Property(property="accountNumber", type="string", example="1234567890"),
     *                 @OA\Property(property="bankCode", type="string", example="058"),
     *                 @OA\Property(property="mobileNumber", type="string", example="233240000000")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Beneficiary created successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid public key or secret key"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
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

        $team = TeamMembers::where('user_id', $user->id)->first();
        $role = $team ? $team->role : 'Owner';

        if (! in_array($role, ['Owner', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only the business owner or an admin can add beneficiaries.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:individual,corporate',
            'firstNames' => 'nullable|required_if:type,individual|string|max:100',
            'lastName' => 'nullable|required_if:type,individual|string|max:100',
            'name' => 'nullable|required_if:type,corporate|string|max:200',
            'transfer_method' => 'required|in:bank,mobile',
            'bank.country' => 'required|string|min:2|max:3',
            'bank.currency' => 'required|string|size:3',
            'bank.accountHolder' => 'required|string|max:100',
            'bank.accountNumber'  => 'nullable|string|max:34',
            'bank.bankCode'       => 'nullable|string|max:20',
            'bank.mobileNumber'   => 'nullable|string|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $bank = $request->input('bank', []);
        $countryIso = strtoupper($bank['country']);
        $currency = strtoupper($bank['currency']);
        $method = $request->transfer_method;

        $payazaCurrencies = ['NGN', 'TZS', 'KES', 'XOF', 'XAF', 'ZAR', 'GHS'];
        $pivotEnabled = filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN);
        $payazaEnabled = filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN);
        $appmobileEnabled = filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN);

        $provider = null;

        if ($currency === 'GHS') {
            if ($appmobileEnabled) {
                $provider = 'app_mobile';
            } elseif ($payazaEnabled) {
                $provider = 'payaza';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No provider enabled for GHS',
                ], 403);
            }
        } elseif ($currency === 'UGX') {
            if ($pivotEnabled) {
                $provider = 'pivot';
            } elseif ($payazaEnabled) {
                $provider = 'payaza';
                $method = 'mobile';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No provider enabled for UGX',
                ], 403);
            }
        } elseif (in_array($currency, $payazaCurrencies)) {
            if ($payazaEnabled) {
                $provider = 'payaza';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Flovide Payaza disabled',
                ], 403);
            }
        } else {
            if ($pivotEnabled) {
                $provider = 'pivot';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pivot disabled',
                ], 403);
            }
        }

        $bankName = null;
        $mobileNumber = null;

        if ($method === 'bank') {
            $bankRow = Bank::where(function ($query) use ($bank) {
                $query->where('bank_code', $bank['bankCode'] ?? null)
                    ->orWhere('sort_code', $bank['bankCode'] ?? null);
            })->first();

            $bankName = $bankRow?->name;
        }

        if ($method === 'mobile') {
            $mobileNumber = $bank['mobileNumber'] ?? null;
            $bankRow = Bank::where('bank_code', $bank['bankCode'] ?? null)->first();
            $bankName = $bankRow?->name ?? 'mobile';
        }

        try {
            $beneficia = Beneficia::create([
                'country' => $countryIso,
                'currency' => $currency,
                'type' => $request->type,
                'first_names' => $request->firstNames ?? null,
                'last_name' => $request->lastName ?? null,
                'beneficiary_name' => $request->name ?? null,
                'account_number' => $bank['accountNumber'] ?? null,
                'account_name' => $bank['accountHolder'] ?? null,
                'phone' => $mobileNumber,
                'bank' => $bankName,
                'transfer_method' => $method,
                'bank_code' => $bank['bankCode'] ?? null,
                'provider' => $provider,
                'unique_reference' => strtoupper(Str::random(7)),
                'customer_reference' => strtoupper(Str::random(7)),
                'recipient_id' => Str::uuid(),
                'account_id' => Str::uuid(),
                'user_id' => $user->id,
            ]);

             return response()->json([
            'success' => true,
            'message' => 'Beneficiary created successfully',
            'data' => [
                'id' => (string) ($beneficia->id),
                'country' => $beneficia->country,
                'default_reference' => $beneficia->default_reference,
                'alias' => $beneficia->alias,
                'type' => $beneficia->type,
                'created' => optional($beneficia->created_at)->toIso8601String(),
                'bank_account' => [
                    'account_name' => $beneficia->account_name,
                    'sort_code' => $beneficia->sort_code,
                    'bank_code' => $beneficia->bank_code,
                    'account_number' => $beneficia->account_number,
                    'bank_name' => $beneficia->bank,
                    'currency' => $beneficia->currency,
                ],
            ],
        ], 201);
        } catch (\Exception $e) {
            logger('Beneficiary Store Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create beneficiary',
            ], 500);
        }
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



        /**
     * @OA\Get(
     *     path="/api/v1/beneficiaries/{id}",
     *     tags={"Beneficiaries"},
     *     summary="Fetch single beneficiary",
     *     description="Returns one beneficiary record for the matched account.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Beneficiary ID",
     *         @OA\Schema(type="integer", example=114)
     *     ),
     *     @OA\Parameter(
     *         name="X-Public-Key",
     *         in="header",
     *         required=true,
     *         description="User public key",
     *         @OA\Schema(type="string", example="env('STRIPE_PUBLIC')")
     *     ),
     *     @OA\Parameter(
     *         name="X-Secret-Key",
     *         in="header",
     *         required=true,
     *         description="User secret key",
     *         @OA\Schema(type="string", example="env('STRIPE_SECRET')")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Beneficiary retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid public key or secret key"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Beneficiary not found"
     *     )
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

    $beneficia = Beneficia::where('user_id', $user->id)
        ->where('id', $id)
        ->first();

    if (! $beneficia) {
        return response()->json([
            'success' => false,
            'message' => 'Beneficiary not found',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Beneficiary retrieved successfully',
        'data' => [
            'id' => (string) ($beneficia->recipient_id ?? $beneficia->id),
            'country' => $beneficia->country,
            'default_reference' => $beneficia->default_reference,
            'alias' => $beneficia->alias,
            'type' => $beneficia->type,
            'created' => optional($beneficia->created_at)->toIso8601String(),
            'bank_account' => [
                'account_name' => $beneficia->account_name,
                'sort_code' => $beneficia->sort_code,
                'bank_code' => $beneficia->bank_code,
                'account_number' => $beneficia->account_number,
                'bank_name' => $beneficia->bank,
                'currency' => $beneficia->currency,
            ],
        ],
    ], 200);
}



        /**
     * @OA\Delete(
     *     path="/api/v1/beneficiaries/{id}",
     *     tags={"Beneficiaries"},
     *     summary="Delete beneficiary",
     *     description="Deletes a beneficiary belonging to the account matched by the provided public key and secret key.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Beneficiary ID",
     *         @OA\Schema(type="integer", example=114)
     *     ),
     *     @OA\Parameter(
     *         name="X-Public-Key",
     *         in="header",
     *         required=true,
     *         description="User public key",
     *         @OA\Schema(type="string", example="env('STRIPE_PUBLIC')")
     *     ),
     *     @OA\Parameter(
     *         name="X-Secret-Key",
     *         in="header",
     *         required=true,
     *         description="User secret key",
     *         @OA\Schema(type="string", example="env('STRIPE_SECRET')")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Beneficiary deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid public key or secret key"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Beneficiary not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function destroy(Request $request, $id)
    {
        $user = $this->resolveKeyUser($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid public key or secret key',
            ], 401);
        }

        $team = TeamMembers::where('user_id', $user->id)->first();
        $role = $team ? $team->role : 'Owner';

        if (! in_array($role, ['Owner', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only the business owner or an admin can delete beneficiaries.',
            ], 403);
        }

        $beneficia = Beneficia::where('user_id', $user->id)->where('id', $id)->first();

        if (! $beneficia) {
            return response()->json([
                'success' => false,
                'message' => 'Beneficiary not found',
            ], 404);
        }

        try {
            $beneficia->delete();

            return response()->json([
                'success' => true,
                'message' => 'Beneficiary deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            logger('Beneficiary Delete Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete beneficiary',
            ], 500);
        }
    }

}
