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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



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

        $transactions = TransactionHistory::where('user_id', $user->id)
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
                    // 'default_reference' => $tx->recipient_default_reference,
                    // 'alias' => $tx->recipient_alias,
                    // 'type' => $tx->recipient_type,
                    // 'created' => $tx->recipient_created_at,
                    'bank_account' => [
                        'account_name' => $tx->recipient_account_name,
                        // 'sort_code' => $tx->recipient_sort_code,
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

    $transaction = TransactionHistory::where('user_id', $user->id)
        ->where('id', $id)
        ->first();

    if (! $transaction) {
        return response()->json([
            'success' => false,
            'message' => 'Transaction not found',
        ], 404);
    }

    $data = [
        'id' => $transaction->id,
        'amount' => $transaction->amount !== null ? (float) $transaction->amount : 0,
        'fees' => $transaction->fees !== null ? (float) $transaction->fees : 0,
        'currency' => $transaction->currency,
        'to_currency' => $transaction->to_currency,
        'balance_id' => $transaction->balance_id,
        // 'virtual_account_id' => $transaction->virtual_account_id,
        // 'order_id' => $transaction->order_id,
        'payment_reference' => $transaction->payment_reference,
        'status' => $transaction->status,
        // 'failure_reason' => $transaction->failure_reason,
        'transaction_type' => $transaction->transaction_type ?? $transaction->method,
        // 'payment_method' => $transaction->payment_method,
        'recipient' => [
            'id' => $transaction->recipient_id ?? $transaction->beneficias_id,
            'country' => $transaction->recipient_country,
            // 'default_reference' => $transaction->recipient_default_reference,
            // 'alias' => $transaction->recipient_alias,
            // 'type' => $transaction->recipient_type,
            'created' => $transaction->recipient_created_at,
            'bank_account' => [
                'account_name' => $transaction->recipient_account_name,
                // 'sort_code' => $transaction->recipient_sort_code,
                'account_number' => $transaction->recipient_account_number,
                'bank_name' => $transaction->recipient_bank_name,
                'currency' => $transaction->recipient_bank_currency ?? $transaction->to_currency,
            ],
        ],
    ];

    return response()->json([
        'success' => true,
        'message' => 'Transaction retrieved successfully',
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
    // public function store(Request $request)
    // {
    //     $user = $this->resolveKeyUser($request);

    //     if (! $user) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid public key or secret key',
    //         ], 401);
    //     }

    //     // Delegate to your existing SendMoneyController
    //     $sendMoney = app()->make(\App\Http\Controllers\Business\SendMoneyController::class);

    //     return $sendMoney->sendTransaction($request);
    // }

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

    $allowedFields = ['transaction_type', 'amount', 'recipient_id', 'order_id', 'balance_id','reference'];
    $extraFields = array_diff(array_keys($request->all()), $allowedFields);

    if (! empty($extraFields)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid request fields',
            'errors' => [
                'fields' => array_values($extraFields),
            ],
        ], 422);
    }

    $validated = $request->validate([
        'transaction_type' => 'required|in:payment',
        'amount' => 'required|numeric|min:1',
        'recipient_id' => 'required|uuid',
        'balance_id' => 'required|uuid',
        'order_id' => 'required|string|max:100',
        'reference' => 'nullable|string|max:255',
    ]);

    $team = TeamMembers::where('user_id', $user->id)->first();
    $ownerId = $team ? $team->owner_id : $user->id;

    $recipient = Beneficia::where('user_id', $ownerId)
        ->where('recipient_id', $validated['recipient_id'])
        ->first();


    if (! $recipient) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid recipient',
            'code' => 'INVALID_RECIPIENT',
            'data' => null,
        ], 422);
    }

    $balance = Balance::where('user_id', $ownerId)
    ->where('id', $validated['balance_id'])
    ->first();

    if (! $balance) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid balance',
            'code' => 'INVALID_BALANCE',
            'data' => null,
        ], 422);
    }

    $request->merge([
        'balance_id' => $balance->id,
        'transfer_fee' => 0,
        'total_amount' => $validated['amount'],
        'recipient_amount' => $validated['amount'],
        'account_number' => $recipient->account_number ?? $recipient->phone,
        'account_name' => $recipient->account_name,
        'bank' => $recipient->bank,
        'bank_code' => $recipient->bank_code,
        'transfer_method' => $recipient->transfer_method,
    ]);

    
        $actor = $this->resolveKeyUser($request) ?? auth()->user();
        if (! $actor) {
            $msg = 'Unauthorized';
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'UNAUTHORIZED', 'data' => null], 401)
                : back()->withInput()->with('error', $msg);
        }

        [$ownerId, $memberId, $role, $owner] = $this->resolveOwnerAndMember($request, $actor);
        if (! $ownerId || ! $owner) {
            $msg = 'Owner account not found';
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'OWNER_NOT_FOUND', 'data' => null], 422)
                : back()->withInput()->with('error', $msg);
        }

    dd($request->all());

}



 public function sendTransaction(Request $request)
    {
        $isApi = $request->expectsJson();

        //  dd($request->all());die();


        $sendingCurrency = strtoupper(explode(' ', $request->exchange_rate)[1] ?? 'NGN');
        $currency        = strtoupper(explode(' ', $request->exchange_rate)[4] ?? 'NGN');

        $limit = Currency::where('code', $sendingCurrency)->where('is_active', true)->first();
        if ($limit) {
            if (!is_null($limit->min_amount) && $request->amount < $limit->min_amount) {
                $msg = "Minimum transfer for {$sendingCurrency} is {$limit->min_amount}";
                return $isApi ? response()->json(['success'=>false,'message'=>$msg,'code'=>'AMOUNT_BELOW_MINIMUM','data'=>null],422)
                    : back()->withInput()->with('error',$msg);
            }
            if (!is_null($limit->max_amount) && $request->amount > $limit->max_amount) {
                $msg = "Maximum transfer for {$sendingCurrency} is {$limit->max_amount}";
                return $isApi ? response()->json(['success'=>false,'message'=>$msg,'code'=>'AMOUNT_ABOVE_MAXIMUM','data'=>null],422)
                    : back()->withInput()->with('error',$msg);
            }
        }

        // IMPORTANT: owner-scope balance
        $balance = Balance::where('id', $request->balance_id)->where('user_id', $ownerId)->first();
        if (! $balance) {
            $msg = 'Invalid balance';
            return $isApi ? response()->json(['success'=>false,'message'=>$msg,'code'=>'INVALID_BALANCE','data'=>null],422)
                : back()->withInput()->with('error',$msg);
        }

        if ($balance->amount < $request->total_amount) {
            $msg = 'Insufficient funds';
            return $isApi ? response()->json(['success'=>false,'message'=>$msg,'code'=>'INSUFFICIENT_FUNDS','data'=>null],422)
                : back()->withInput()->with('error',$msg);
        }

        DB::beginTransaction();
        try {
            $balance->amount -= $request->total_amount;
            $balance->save();

            $tx = TransactionHistory::create([
                'amount' => $request->amount,
                'total_amount' => $request->total_amount,
                'currency' => $sendingCurrency,
                'balance_id' => $balance->id,
                'status' => 'pending',
                'method' => 'withdrawal',
                'payment_provider' => 'wallect',
                'order_id' => $request->order_id,
                'reference' => $request->reference ?? 'ref-' . Str::uuid(),
                'user_id' => $ownerId,
                'created_by_member_id' => $memberId,
                'sender_id' => $ownerId,
                'sender' => $actor->business_name ?? $actor->name,
                'recipient_account_number' => $request->account_number,
                'recipient_account_name' => $request->account_name,
                'recipient_id' => $request->recipient_id,
                'recipient_country' => strtoupper(substr($currency, 0, 2)),
                'recipient_bank_currency' => $currency,
                'to_currency' => $currency,
                'fees' => $request->transfer_fee,
                'exchange_rate' => strtoupper(explode(' ', $request->exchange_rate)[3] ?? null),
                'recipient_amount' => $request->recipient_amount,

            ]);

            if (in_array($currency, ['UGX']) && filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
                $response = $this->sendViaPivot($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner);
            } elseif (in_array($currency, ['GHS']) && filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN)) {
                $response = $this->sendViaAppMobile($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner);
            } elseif (in_array($currency, ['NGN','TZS','XOF','XAF','ZAR','KES']) && filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
                $response = $this->sendViaPayaza($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner);
            } else {
                DB::rollBack();
                $msg = 'No provider';
                return $isApi ? response()->json(['success'=>false,'message'=>$msg,'code'=>'PROVIDER_NOT_AVAILABLE','data'=>null],422)
                    : back()->withInput()->with('error',$msg);
            }

            DB::commit();
            return $response;

        } catch (\Exception $e) {
            DB::rollBack();
            return $isApi
                ? response()->json(['success'=>false,'message'=>'Transaction failed','code'=>'TXN_FAILED','data'=>$e->getMessage()],500)
                : back()->withInput()->with('error','Transaction failed');
        }
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
}
