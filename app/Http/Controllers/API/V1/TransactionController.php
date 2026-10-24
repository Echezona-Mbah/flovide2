<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SendMoneyController;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\WebhookSetting;
use Illuminate\Http\Request;
use App\Models\Balance;
use App\Models\Beneficia;
use App\Models\TeamMembers;
use App\Traits\CurrencyHelper;
use App\Traits\SelectsBalanceId;
use App\Models\Currency;
use App\Services\PayazaService;
use App\Services\PivotService;
use App\Services\OrchardService;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionSentMail;
use App\Models\ExchangeRate;
use App\Services\BankCodeResolver;
use App\Services\ProviderRoutingService;
use App\Services\SwapprService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;



class TransactionController extends Controller
{


    use CurrencyHelper;
    use SelectsBalanceId;

    protected $pivot;
    protected $payaza;
    protected $orchard;



    public function __construct(PivotService $pivot, PayazaService $payaza,OrchardService $orchard)
    {
        $this->pivot = $pivot;
        $this->payaza = $payaza;
        $this->orchard = $orchard;
    }





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

    $transactions = TransactionHistory::where('user_id', $user->id)
        ->where('mode', $mode)
        ->latest()
        ->get();

    $data = $transactions->map(function ($tx) {
        return [
            'id' => $tx->id,
            'amount' => $tx->amount !== null ? (float) $tx->amount : 0,
            'fees' => $tx->fees !== null ? (float) $tx->fees : 0,
            'currency' => $tx->currency,
            'to_currency' => $tx->to_currency,
            'balance_id' => $tx->balance_id,
            'status' => $tx->status,
            'transaction_type' => $tx->transaction_type ?? $tx->method,
            'recipient' => [
                'id' => $tx->recipient_id ?? $tx->beneficias_id,
                'country' => $tx->recipient_country,
                'bank_account' => [
                    'account_name' => $tx->recipient_account_name,
                    'account_number' => $tx->recipient_account_number,
                    'bank_name' => $tx->recipient_bank_name,
                    'currency' => $tx->recipient_bank_currency ?? $tx->to_currency,
                ],
            ],
        ];
    })->values();

    return response()->json([
        'success' => true,
        'message' => 'Transactions retrieved successfully',
        'mode' => $mode,
        'data' => $data,
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

    $transaction = TransactionHistory::where('user_id', $user->id)
        ->where('mode', $mode)
        ->where('id', $id)
        ->first();

    if (! $transaction) {
        return response()->json([
            'success' => false,
            'message' => 'Transaction not found',
            'mode' => $mode,
        ], 404);
    }

    $data = [
        'id' => $transaction->id,
        'amount' => $transaction->amount !== null ? (float) $transaction->amount : 0,
        'fees' => $transaction->fees !== null ? (float) $transaction->fees : 0,
        'currency' => $transaction->currency,
        'to_currency' => $transaction->to_currency,
        'balance_id' => $transaction->balance_id,
        'payment_reference' => $transaction->payment_reference,
        'status' => $transaction->status,
        'transaction_type' => $transaction->transaction_type ?? $transaction->method,
        'recipient' => [
            'id' => $transaction->recipient_id ?? $transaction->beneficias_id,
            'country' => $transaction->recipient_country,
            'created' => $transaction->recipient_created_at,
            'bank_account' => [
                'account_name' => $transaction->recipient_account_name,
                'account_number' => $transaction->recipient_account_number,
                'bank_name' => $transaction->recipient_bank_name,
                'currency' => $transaction->recipient_bank_currency ?? $transaction->to_currency,
            ],
        ],
    ];

    return response()->json([
        'success' => true,
        'message' => 'Transaction retrieved successfully',
        'mode' => $mode,
        'data' => $data,
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
    $isApi = $request->expectsJson();

    $user = $this->resolveKeyUser($request);

    if (! $user) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid public key or secret key',
        ], 401);
    }

    // ── Test / Live gate ──
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
        $txMode = $mode ?? (str_starts_with($request->path(), 'api/test/') ? 'test' : 'live');

    // ── End gate ──

    $allowedFields = ['transaction_type', 'amount', 'recipient_id', 'order_id', 'balance_id', 'reference', 'sender'];
    $extraFields   = array_diff(array_keys($request->all()), $allowedFields);

    if (! empty($extraFields)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid request fields',
            'errors'  => ['fields' => array_values($extraFields)],
        ], 422);
    }

    $validator = Validator::make($request->all(), [
        'transaction_type' => 'required|in:payment',
        'amount'           => 'required|numeric|min:1',
        'recipient_id'     => 'required|uuid',
        'balance_id'       => 'required|uuid',
        'order_id'         => 'required|string|max:100',
        'reference'        => 'nullable|uuid|unique:transactions_history,reference',
        ...SwapprService::senderInformationValidationRules(),
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

    $actor = $this->resolveKeyUser($request) ?? auth()->user();

    if (! $actor) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
            'code'    => 'UNAUTHORIZED',
            'data'    => null,
        ], 401);
    }

    [$ownerId, $memberId, $role, $owner] = $this->resolveOwnerAndMember($request, $actor);

    if (! $ownerId || ! $owner) {
        return response()->json([
            'success' => false,
            'message' => 'Owner account not found',
            'code'    => 'OWNER_NOT_FOUND',
            'data'    => null,
        ], 422);
    }

    $recipient = Beneficia::where('recipient_id', $request->recipient_id)
        ->where('user_id', $ownerId)
        ->first();

    if (! $recipient) {
        return response()->json([
            'success' => false,
            'message' => 'Recipient not found',
            'code'    => 'RECIPIENT_NOT_FOUND',
            'mode'    => $txMode,
            'data'    => null,
        ], 404);
    }

    $balance = Balance::where('id', $validated['balance_id'])
        ->where('user_id', $ownerId)
        ->first();

    if (! $balance) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid balance',
            'code'    => 'INVALID_BALANCE',
            'data'    => null,
        ], 422);
    }



    $sendingCurrency = strtoupper($balance->currency);
    $currency        = strtoupper($recipient->currency);
    $amount          = (float) $validated['amount'];


    $rate = ExchangeRate::whereHas('fromCurrency', fn($q) => $q->where('code', $sendingCurrency))
        ->whereHas('toCurrency',   fn($q) => $q->where('code', $currency))
        ->first();

    if (! $rate) {
        return response()->json([
            'success' => false,
            'message' => "Rate not found for {$sendingCurrency} to {$currency}",
            'code'    => 'RATE_NOT_FOUND',
            'data'    => null,
        ], 400);
    }

        $userFee = \App\Models\UserCurrencyFee::where('user_id', $ownerId)->where('currency', $currency)->first();
        $platformFee = 0;
        $transferFee = 0;

        if ($userFee->payout_enabled == 0) {
            $msg = "Contact your marketer to enable payout pricing for {$currency}.";
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'PAYOUT_DISABLED', 'data' => null], 422)
                : back()->withInput()->with('error', $msg);
        }



    if ($userFee && $userFee->payout_enabled) {

        if ($userFee->payout_min > 0 && $amount < $userFee->payout_min) {
            return response()->json([
                'success' => false,
                'message' => "Minimum payout for {$sendingCurrency} is " . number_format($userFee->payout_min, 2),
                'code'    => 'BELOW_PAYOUT_MIN',
                'data'    => null,
            ], 422);
        }

        if ($userFee->payout_max > 0 && $amount > $userFee->payout_max) {
            return response()->json([
                'success' => false,
                'message' => "Maximum payout for {$sendingCurrency} is " . number_format($userFee->payout_max, 2),
                'code'    => 'ABOVE_PAYOUT_MAX',
                'data'    => null,
            ], 422);
        }

        $platformFee = round(($amount * $userFee->payout_percent / 100) + $userFee->payout_fixed,2);

        $transferFee = $platformFee;
    }

    $totalAmount     = $amount; // fee no longer bundled into total — taken from payout balance separately
    $recipientAmount = $amount; // same amount, no conversion

    if ($balance->amount < $totalAmount) {
        return response()->json([
            'success' => false,
            'message' => 'Insufficient funds',
            'code'    => 'INSUFFICIENT_FUNDS',
            'data'    => null,
        ], 422);
    }

    $exchangeRateText = sprintf(
        "%s 1.00 = %s %s",
        $sendingCurrency,
        $currency,
        number_format((float) $rate->rate, 6, '.', '')
    );

    $resolvedProvider = app(ProviderRoutingService::class)
        ->resolveForCurrency($currency, $request->transfer_method ?: $recipient->transfer_method);

    if (! $resolvedProvider) {
        return response()->json([
            'success' => false,
            'message' => "No provider available for {$currency}",
            'code'    => 'PROVIDER_NOT_AVAILABLE',
            'mode'    => $txMode,
            'data'    => null,
        ], 422);
    }

    $resolver    = app(BankCodeResolver::class);
    $codeWeHold  = $recipient->flovide_bank_code ?: (string) $recipient->bank_code;
    $providerRow = $resolver->forProvider($resolvedProvider->key, $currency, $codeWeHold, $recipient->provider);

    if (! $providerRow && $resolver->isLinked($currency)) {
        return response()->json([
            'success' => false,
            'message' => "This recipient's bank is not currently supported for {$currency}.",
            'code'    => 'BANK_NOT_SUPPORTED',
            'mode'    => $txMode,
            'data'    => null,
        ], 422);
    }

    $providerBankCode = $providerRow
        ? ($resolvedProvider->key === 'pivot' ? ($providerRow->sort_code ?: $providerRow->bank_code) : $providerRow->bank_code)
        : $recipient->bank_code;   // currency not linked to Flovide codes yet: behaves as before


    $request->merge([
        'balance_id'          => $balance->id,
        'transfer_fee'        => $transferFee,
        'total_amount'        => $totalAmount,
        'recipient_amount'    => $recipientAmount,
        'exchange_rate'       => $exchangeRateText,
        'exchange_rate_value' => (float) $rate->rate,
        'account_number'      => $recipient->account_number ?? $recipient->phone,
        'account_name'        => $recipient->account_name,
         'bank_code' => $providerBankCode,
        'bank'      => $recipient->bank,
        'transfer_method'     => $recipient->transfer_method,
        'mode'                => $mode,

         // CAD / Interac
        'interac_email'       => $recipient->email ?? null,
        'interac_first_name'  => $recipient->interac_first_name ?? null,
        'interac_last_name'   => $recipient->interac_last_name ?? null,
    ]);

    DB::beginTransaction();

    try {
        $feeDeductedFrom = null;

        // ── Only deduct balance on live ──
        if ($mode === 'live') {
            $balance->amount -= $totalAmount;
            $balance->save();

            // ── Deduct platform fee from the payout-currency balance ───────────
            if ($transferFee > 0) {
                $payoutBalance = Balance::where('user_id', $ownerId)
                    ->where('currency', $currency) // payout/recipient currency
                    ->first();

                if ($payoutBalance) {
                    if ($payoutBalance->amount < $transferFee) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient {$currency} balance to cover transfer fee",
                            'code'    => 'INSUFFICIENT_FEE_BALANCE',
                            'data'    => null,
                        ], 422);
                    }

                    $payoutBalance->amount -= $transferFee;
                    $payoutBalance->save();
                    $feeDeductedFrom = $payoutBalance->id;
                } else {
                    // No payout-currency wallet — fall back to the sending balance
                    if ($balance->amount < $transferFee) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Insufficient funds to cover transfer fee',
                            'code'    => 'INSUFFICIENT_FEE_BALANCE',
                            'data'    => null,
                        ], 422);
                    }

                    $balance->amount -= $transferFee;
                    $balance->save();
                    $feeDeductedFrom = $balance->id;
                }
            }
        }

        $tx = TransactionHistory::create([
            'amount'                   => $validated['amount'],
            'total_amount'             => $totalAmount,
            'currency'                 => $sendingCurrency,
            'balance_id'               => $balance->id,
            'fee_balance_id'           => $feeDeductedFrom,
            'status'                   => 'pending',
            'method'                   => 'withdrawal',
            'mode'                     => $mode,
            'payment_provider'         => 'wallect',
            'order_id'                 => $validated['order_id'],
            'reference'                => $validated['reference'] ?? 'ref-' . Str::uuid(),
            'user_id'                  => $ownerId,
            'created_by_member_id'     => $memberId,
            'sender_id'                => $ownerId,
            'sender'                   => $actor->business_name ?? $actor->name,
            'recipient_account_number' => $request->account_number,
            'recipient_account_name'   => $request->account_name,
            'recipient_id'             => $validated['recipient_id'],
            'bank_code'                => $request->bank_code,
            'recipient_country'        => strtoupper(substr($currency, 0, 2)),
            'recipient_bank_currency'  => $currency,
            'to_currency'              => $currency,
            'fees'                     => $transferFee,
            'platform_fee'             => $transferFee,
            'exchange_rate'            => (float) $rate->rate,
            'recipient_amount'         => $recipientAmount,
            'interac_email'            => $request->interac_email      ?? null,
            'interac_first_name'       => $request->interac_first_name ?? null,
            'interac_last_name'        => $request->interac_last_name  ?? null,
        ]);

       if ($mode === 'live') {
            // ── Real providers only on live ──
           if ($resolvedProvider->key === 'swappr') {
               $response = $this->sendViaSwappr($request, $currency, $tx->id, $recipientAmount);
           } elseif (in_array($currency, ['UGX']) && filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
               $response = $this->sendViaPivot($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner, $recipientAmount, $transferFee);
           } elseif (in_array($currency, ['GHS']) && filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN)) {
               $response = $this->sendViaAppMobile($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner, $recipientAmount, $transferFee);
            } elseif (in_array($currency, ['NGN', 'TZS', 'XOF', 'XAF', 'ZAR', 'KES']) && filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
                $response = $this->sendViaPayaza($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner, $recipientAmount, $transferFee);
            } elseif ($currency === 'CAD') {
                $response = $this->sendViaBlaaizInterac($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner, $recipientAmount, $transferFee);
            } else {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'No provider available for this currency',
                    'code'    => 'PROVIDER_NOT_AVAILABLE',
                    'data'    => null,
                ], 422);
            }
        } else {
            // ── Test mode: skip providers, simulate success ──
            TransactionHistory::where('id', $tx->id)->update(['status' => 'pending']);

            $response = response()->json([
                'success' => true,
                'message' => 'Test transaction created successfully',
                'code'    => 'TEST_SUCCESS',
                'mode'    => 'test',
                'data'    => $this->txData($tx->id),
            ], 200);
        }

        DB::commit();

        return $response;

    } catch (\Exception $e) {
        DB::rollBack();

        // ── Restore balance on exception only if live deducted it ──
        if ($mode === 'live') {
            $balance->refresh();
            $balance->amount += $totalAmount;
            $balance->save();

            if (isset($feeDeductedFrom) && $feeDeductedFrom) {
                $feeBalance = Balance::find($feeDeductedFrom);
                if ($feeBalance) {
                    $feeBalance->amount += $transferFee;
                    $feeBalance->save();
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Transaction failed',
            'code'    => 'TXN_FAILED',
            'data'    => $e->getMessage(),
        ], 500);
    }
}




    protected function sendViaSwappr(Request $request, string $currency, string $txId, $recipientAmount)
    {
        $transaction = TransactionHistory::findOrFail($txId);
        $currency = strtoupper($currency);

        if ($currency === 'CAD' && $request->filled('interac_email')) {
            $recipient = [
                'email' => $request->interac_email,
                'first_name' => $request->interac_first_name,
                'last_name' => $request->interac_last_name,
            ];
            $payload = [
                'amount_minor' => number_format((float) $recipientAmount * 100, 0, '.', ''),
                'currency' => $currency,
                'method' => 'interac',
                'recipient' => $recipient,
                'merchant_reference' => $transaction->reference,
            ];
        } elseif ($currency === 'NGN') {
            $payload = [
                'amount_minor' => number_format((float) $recipientAmount * 100, 0, '.', ''),
                'currency' => $currency,
                'recipient' => [
                    'account_number' => $request->account_number,
                    'bank_code' => $request->bank_code,
                ],
                'sender' => SwapprService::senderInformationFromConfig(),
                'merchant_reference' => $transaction->reference,
            ];
        } else {
            return response()->json([
                'success' => false,
                'message' => "Swappr payouts are not configured for {$currency}.",
                'code' => 'SWAPPR_CURRENCY_UNSUPPORTED',
                'data' => $this->txData($txId),
            ], 422);
        }

        if ($request->filled('sender')) {
            $payload['sender'] = $request->input('sender');
        }

        $beneficiary = Beneficia::where('recipient_id', $transaction->recipient_id)
            ->where('user_id', $transaction->user_id)
            ->where('mode', $transaction->mode ?: 'live')
            ->first();

        if ($beneficiary?->swappr_beneficiary_id) {
            $payload['beneficiary_id'] = $beneficiary->swappr_beneficiary_id;
            unset($payload['recipient']);
        }

        $response = app(\App\Services\SwapprService::class)->submitPayout($transaction, $payload);

        if ($response['success']) {
            return response()->json([
                'success' => true,
                'message' => $response['message'],
                'code' => 'SWAPPR_PAYOUT_SUBMITTED',
                'mode' => $transaction->mode,
                'data' => $this->txData($txId),
            ], 200);
        }

        if ($response['uncertain'] ?? false) {
            return response()->json([
                'success' => true,
                'message' => $response['message'],
                'code' => 'SWAPPR_PAYOUT_PENDING_CONFIRMATION',
                'mode' => $transaction->mode,
                'data' => $this->txData($txId),
            ], 202);
        }

        return response()->json([
            'success' => false,
            'message' => $response['message'],
            'code' => 'SWAPPR_PAYOUT_FAILED',
            'mode' => $transaction->mode,
            'data' => $this->txData($txId),
        ], 422);
    }


    // -------------------- Pivot Payment --------------------
    protected function sendViaPivot(Request $request, $currency, $sendingCurrency, $balance, $txId, $actor, User $owner)
    {
        $isApi = $request->expectsJson();
        $user = auth()->user();

        $auth = $this->pivot->authenticate();
        if (isset($auth['error'])) {
            return $isApi
                ? response()->json([
                    'success' => false,
                    'message' => $auth['error'],
                    'code' => 'FLOVIDE_AUTH_FAILED',
                    'data' => null
                ], 500)
                : back()->withErrors(['error' => $auth['error']]);
        }

        $token = $auth['tokenResponse']['accessToken'];
        $merchantTransactionId = 'TXN_' . substr(uniqid(), 0, 10);

        $bankType = strtolower($request->transfer_method ?? 'bank');

        $pivotUGXBankService   = env('PIVOT_UGX_BANK_SERVICE');
        $pivotUGXMobileService = env('PIVOT_UGX_MOBILE_SERVICE');
        $pivotKESBankService   = env('PIVOT_KES_BANK_SERVICE', 'KES_BANK_CODE');
        $pivotKESMobileService = env('PIVOT_KES_MOBILE_SERVICE', 'KES_MOBILE_CODE');

        if ($currency === 'UGX') {
            $serviceCode = $bankType === 'mobile' ? $pivotUGXMobileService : $pivotUGXBankService;
            $sortCode    = $bankType === 'mobile' ? env('PIVOT_UGX_MOBILE_SORT', '000000') : $request->bank_code;
        } elseif ($currency === 'KES') {
            $serviceCode = $bankType === 'mobile' ? $pivotKESMobileService : $pivotKESBankService;
            $sortCode    = $bankType === 'mobile' ? env('PIVOT_KES_MOBILE_SORT', '000000') : $request->bank_code;
        } else {
            $serviceCode = $bankType === 'mobile' ? 'DEFAULT_MOBILE' : 'DEFAULT_BANK';
            $sortCode    = $bankType === 'mobile' ? '013847' : $request->bank_code;
        }

        $payload = [
            "serviceCode" => $serviceCode,
            "msisdn" => $bankType === 'mobile' ? $request->account_number : '256755289333',
            "accountNumber" => $request->account_number,
            "merchantTransactionId" => $merchantTransactionId,
            "amount" => $request->recipient_amount,
            "chargeAmount" => $request->transfer_fee ?? 0,
            "narration" => $request->reference ?? "Payment",
            "currencyCode" => $currency,
            "countryCode" => $currency === 'UGX' ? 'UG' : 'KE',
            "customerName" => $request->account_name,
        ];

        $payload['extraData'] = [
            "bankSortCode" => $sortCode
        ];

        if ($bankType !== 'mobile') {
            $payload['extraData']['amount'] = $request->recipient_amount;
        }

        logger('Pivot REQUEST', $payload);
        $payment = $this->pivot->postTransaction($token, $payload);
        logger('Pivot RESPONSE', $payment);

        if (isset($payment['statusCode']) && $payment['statusCode'] === '237') {
            // $balance->amount -= $request->total_amount;
            // $balance->save();

   

            TransactionHistory::where('id', $txId)->update([
            'status' => 'pending',
            'payment_provider' => 'pivot',
            'order_id' => $payment['merchantTransactionId'] ?? 'N/A'
            ]);

            return $isApi
                ? response()->json([
                    'success' => true,
                    'message' => 'flovide transaction successful',
                    'code' => 'FLOVIDE_SUCCESS',
                    'data' => $this->txData($txId),
                ], 200)
                : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully via flovide!');
        }

        $errorMessage = $payment['statusDescription'] ?? 'flovide payment failed';

        return $isApi
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'FLOVIDE_FAILED',
                'data' => $this->txData($txId),
            ], 422)
            : back()->with('error', $errorMessage);
    }



    protected function sendViaPayaza(Request $request, $currency, $sendingCurrency, $balance, $txId, $actor, User $owner)
    {   
        $isApi = $request->expectsJson();
        $transactionReference = "TXN_" . time();

        $accountReference = $this->payaza->getAccountReference($currency);
        //  dd($accountReference);

        if (!$accountReference) {
            return $isApi
                ? response()->json([
                    'success' => false,
                    'message' => 'Unable to retrieve account reference from Payaza',
                    'code' => 'FLOVIDE_ACCOUNT_REF_FAILED',
                    'data' => null
                ], 500)
                : back()->with('error', 'Unable to retrieve account reference from Payaza');
        }

        $bankType = strtolower($request->transfer_method ?? 'bank');

        $transactionTypes = [
            'NGN' => 'nuban',
            'GHS' => $bankType === 'mobile' ? 'mobile_money' : 'ghipps',
            'UGX' => 'mobile_money',
            'TZS' => $bankType === 'mobile' ? 'mobile_money' : 'tiss',
            'KES' => $bankType === 'mobile' ? 'mobile_money' : 'kepss',
            'XOF' => $bankType === 'mobile' ? 'mobile_money' : 'wave',
            'XAF' => 'mobile_money',
            'ZAR' => 'RTC',
        ];

        $transactionType = $transactionTypes[$currency] ?? ($bankType === 'mobile' ? 'mobile_money' : 'nuban');

        $payload = [
            "transaction_type" => $transactionType,
            "service_payload" => [
                "payout_amount" => $request->recipient_amount,
                "transaction_pin" => env('PAYAZA_MERCHANT_PIN'),
                "account_reference" => $accountReference,
                "currency" => $currency,
                "country" => strtoupper(substr($currency, 0, 2)),
                "payout_beneficiaries" => [
                    [
                        "credit_amount" => $request->recipient_amount,
                        "account_number" => $request->account_number,
                        "account_name" => $request->account_name,
                        "bank_code" => $request->bank_code ?? null,
                        "narration" => $request->reference ?? "Payment",
                        "transaction_reference" => $transactionReference,
                        "sender" => [
                            "sender_name" => $actor->business_name ?? $actor->name ?? 'Flovide User',
                            "sender_id" => $actor->id,
                            "sender_phone_number" => $actor->business_phone ?? null,
                            "sender_address" => $actor->street_address ?? null
                        ]
                    ]
                ]
            ]
        ];

        logger('Payaza REQUEST', $payload);
        $response = $this->payaza->initiatePayout($payload);
        logger('Payaza RESPONSE', $response);

        if (($response['statusCode'] ?? null) === '200' || ($response['success'] ?? false)) {
            // $balance->amount -= $request->total_amount;
            // $balance->save();
            $this->sendTransactionEmail($request, $balance, $owner, $transactionReference);



            TransactionHistory::where('id', $txId)->update([
            'status' => 'pending',
            'payment_provider' => 'payaza',
            'order_id' => $transactionReference
            ]);


            return $isApi
                ? response()->json([
                    'success' => true,
                    'message' => 'Transaction transaction successful',
                    'code' => 'FLOVIDE_SUCCESS',
                    'data' => $this->txData($txId),
                ], 200)
                : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully via flovide!');
        }

        $errorMessage = $response['statusDescription'] ?? 'flovide transaction failed';

        return $isApi
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'FLOVIDE_FAILED',
                'data' => $this->txData($txId),
            ], 422)
            : back()->with('error', $errorMessage);
    }



    protected function sendViaAppMobile(Request $request, $currency, $sendingCurrency, $balance, $txId, $actor, User $owner)
    {
        $isApi = $request->expectsJson();
        
        $exttrid = uniqid('APPM_');
        $bankCode = $request->bank_code;

        $network = "BNK";
        if (in_array($bankCode, ["MTN", "VOD", "AIR", "VIS", "MAS"])) {
            $network = $bankCode;
        }

        $payload = [
            "customer_number" => $request->account_number,
            "amount"          => number_format($request->recipient_amount, 2, '.', ''),
            "exttrid"         => $exttrid,
            "reference"       => $request->reference ?? "Wallet Payment",
            "nw"              => $network,
            "bank_code"       => $bankCode,
            "trans_type"      => "MTC",
            "callback_url"    => route('transactionHistory'),
            "service_id"      => env('ORCHARD_SERVICE_ID'),
            "ts"              => now()->utc()->format('Y-m-d H:i:s'),
        ];

        logger('AppMobile REQUEST', $payload);
        $response = $this->orchard->sendPayment($payload);
        logger('AppMobile RESPONSE', $response);

        if (($response['status'] ?? null) === 'SUCCESS' || ($response['success'] ?? false)) {
            // $balance->amount -= $request->total_amount;
            // $balance->save();
            $this->sendTransactionEmail($request, $balance, $owner, $exttrid);

            // TransactionHistory::create([
            //     'amount' => $request->total_amount,
            //     'currency' => $sendingCurrency,
            //     'balance_id' => $balance->id,
            //     'order_id' => $exttrid,
            //     'sender_id' => auth()->id(),
            //     'sender' => $user->business_name ?? $user->name,
            //     'recipient_account_number' => $request->account_number,
            //     'recipient_account_name' => $request->account_name,
            //     'recipient_country' => 'GH',
            //     'status' => 'pending', 
            //     'method' => 'withdrawal',
            //     'payment_provider' => 'appmobile',
            //     'reference' => 'ref-' . Str::uuid(),
            //     'user_id' => auth()->id(),
            // ]);

             TransactionHistory::where('id', $txId)->update([
                'status' => 'pending',
                'payment_provider' => 'appmobile'
            ]);

            return $isApi
                ? response()->json([
                    'success' => true,
                    'message' => 'flovide transaction successful',
                    'code' => 'FLOVIDE_SUCCESS',
                    'data' => $this->txData($txId),
                ], 200)
                : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully via flovide!');
        }

        $errorMessage = $response['message'] ?? 'flovide transaction failed';

        return $isApi
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'FLOVIDE_FAILED',
                'data' => $this->txData($txId),
            ], 422)
            : back()->with('error', $errorMessage);
    }


    protected function sendViaBlaaizInterac(Request $request, $currency, $sendingCurrency, $balance, $txId, $actor, User $owner, $recipientAmount, $transferFee)
    {
        $isApi = $request->expectsJson();

        Log::info('[Blaaiz Interac] Initiating payout', [
            'amount'     => $recipientAmount,
            'email'      => $request->interac_email,
            'first_name' => $request->interac_first_name,
            'last_name'  => $request->interac_last_name,
            'currency'   => $currency,
            'tx_id'      => $txId,
            'actor_id'   => $actor->id,
        ]);

        $blaaiz     = app(\App\Services\BlaaizService::class);
        $customerId = "019ec763-8348-73d5-9bdb-c85b22c6333b";
        $cadWallet  = "61c1135d-b5ce-49c8-add9-0717af84e392";

        $payload = [
            'wallet_id'          => $cadWallet,
            'customer_id'        => $customerId,
            'method'             => 'interac',
            'from_currency_id'   => 'CAD',
            'to_currency_id'     => 'CAD',
            'from_amount'        => $recipientAmount,
            'email'              => $request->interac_email,
            'interac_first_name' => $request->interac_first_name,
            'interac_last_name'  => $request->interac_last_name,
        ];

        Log::info('[Blaaiz Interac] Sending payout payload', $payload);

        $response = $blaaiz->payout($payload);

        Log::info('[Blaaiz Interac] Payout response', [
            'success' => $response['success'],
            'status'  => $response['status'],
            'data'    => $response['data'],
        ]);

        if ($response['success']) {
            $transaction = $response['data']['transaction'] ?? [];

            TransactionHistory::where('id', $txId)->update([
                'status'           => 'pending',
                'payment_provider' => 'interac',
                'order_id'         => $transaction['id']        ?? null,
                'reference'        => $transaction['reference'] ?? null,
            ]);

            Log::info('[Blaaiz Interac] Transaction updated', ['tx_id' => $txId]);

            return $isApi
                ? response()->json([
                    'success' => true,
                    'message' => 'Interac payout initiated successfully.',
                    'code'    => 'BLAAIZ_INTERAC_SUCCESS',
                    'data'    => $this->txData($txId),
                ], 200)
                : redirect()->route('transactionHistory')->with('success', 'Interac payout sent successfully!');
        }

        $errorMsg = $response['data']['message']
            ?? $response['data']['error_description']
            ?? 'Blaaiz Interac payout failed.';

        Log::warning('[Blaaiz Interac] Payout failed', [
            'error' => $errorMsg,
            'tx_id' => $txId,
        ]);

        return $isApi
            ? response()->json([
                'success' => false,
                'message' => $errorMsg,
                'code'    => 'BLAAIZ_INTERAC_FAILED',
                'data'    => $this->txData($txId),
            ], 422)
            : back()->with('error', $errorMsg);
    }



       protected function sendTransactionEmail(Request $request, $balance, User $owner, $reference = null)
    {
        $rateParts = preg_split('/\s+/', trim((string) $request->exchange_rate));
        $sendingCurrency = strtoupper($rateParts[1] ?? 'NGN');
        $recipientCurrency = strtoupper($rateParts[4] ?? 'NGN');

        $data = [
            'name' => $owner->business_name ?? $owner->name,
            'amount_sent' => number_format((float) $request->amount, 2),
            'recipient_amount' => number_format((float) $request->recipient_amount, 2),
            'fee' => number_format((float) ($request->transfer_fee ?? 0), 2),
            'total_amount' => number_format((float) $request->total_amount, 2),
            'current_balance' => number_format((float) $balance->amount, 2),
            'sending_currency' => $sendingCurrency,
            'recipient_currency' => $recipientCurrency,
            'reference' => $reference ?? ($request->reference ?? 'N/A'),
        ];

        Mail::to($owner->email)->send(new TransactionSentMail($data));
    }


    // Add this helper in the same controller
    private function txData(string $txId): array
    {
        $tx = TransactionHistory::findOrFail($txId);

        return [
            'id' => (string) $tx->id,
            'reference' => $tx->reference,
            'order_id' => $tx->order_id,
            'status' => $tx->status,
            'amount' => (float) $tx->amount,
            'total_amount' => (float) $tx->total_amount,
            'fees' => (float) ($tx->fees ?? 0),
            'currency' => $tx->currency,
            'to_currency' => $tx->to_currency,
            'recipient_amount' => (float) ($tx->recipient_amount ?? 0),
            'payment_provider' => 'Flovide',
            'recipient_account_name' => $tx->recipient_account_name,
            'recipient_account_number' => $tx->recipient_account_number,
            'created_at' => optional($tx->created_at)->toIso8601String(),
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
