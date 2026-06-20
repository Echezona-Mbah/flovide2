<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Beneficia;
use App\Models\TransactionHistory;
use App\Traits\CurrencyHelper;
use App\Traits\SelectsBalanceId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Http\Controllers\Ibanq\IbanqPaymentController;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Services\PayazaService;
use App\Services\PivotService;
use App\Services\OrchardService;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionSentMail;
use App\Models\TeamMembers;
use Illuminate\Support\Facades\DB;
use App\Models\WebhookSetting;
use App\Models\User;
use Illuminate\Support\Facades\Log;






class SendMoneyController extends Controller
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

    public function sendTransaction(Request $request)
    {
        $isApi = $request->expectsJson();
        
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'recipient_id' => 'required|uuid',
            'balance_id' => 'required',
            'reference' => 'nullable|string',
            'transfer_fee' => 'nullable',
            'total_amount' => 'required|numeric',
            'exchange_rate' => 'required|string',
            'recipient_amount' => 'required|numeric',
            'account_number' => 'nullable|string',
            'account_name' => 'nullable|string',
            'bank' => 'nullable|string',
            'bank_code' => 'nullable|string',
            'transfer_method' => 'nullable|string',
            'interac_email'    => 'nullable|email',    // ← ADD
            'interac_first_name' => 'nullable|string', // ← ADD
            'interac_last_name'  => 'nullable|string', 
            // 'transaction_type' => 'nullable|in:payment',
            // 'order_id' => 'nullable|string|max:100',
        ]);

       // dd($request->all());


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
    
        // dd($request->all());
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
                'reference' => 'ref-' . Str::uuid(),
                'user_id' => $ownerId,
                'created_by_member_id' => $memberId,
                'sender_id' => $ownerId,
                'sender' => $actor->business_name ?? $actor->name,
                'recipient_account_number' => $request->account_number,
                'recipient_account_name' => $request->account_name 
                    ?? trim(($request->interac_first_name ?? '') . ' ' . ($request->interac_last_name ?? '')) 
                    ?: null,
                'bank_code' => $request->bank_code,
                'recipient_bank_name' => $request->bank,
                'recipient_id' => $request->recipient_id,
                'recipient_country' => strtoupper(substr($currency, 0, 2)),
                'recipient_bank_currency' => $currency,
                'to_currency' => $currency,
                'fees' => $request->transfer_fee,
                'exchange_rate' => strtoupper(explode(' ', $request->exchange_rate)[3] ?? null),
                'recipient_amount' => $request->recipient_amount,
                'interac_email'      => $request->interac_email      ?? null,
                'interac_first_name' => $request->interac_first_name ?? null,
                'interac_last_name'  => $request->interac_last_name  ?? null,

            ]);
            if (in_array($currency, ['UGX']) && filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
                $response = $this->sendViaPivot($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner);
            } elseif (in_array($currency, ['GHS']) && filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN)) {
                $response = $this->sendViaAppMobile($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner);
            } elseif (in_array($currency, ['NGN','TZS','XOF','XAF','ZAR','KES']) && filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
                $response = $this->sendViaPayaza($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner);
            }  elseif ($currency === 'CAD') {                                              // ← ADD THIS
                $response = $this->sendViaBlaaizInterac($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner);
            }else {
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

        // logger('Pivot REQUEST', $payload);
        $payment = $this->pivot->postTransaction($token, $payload);
        // logger('Pivot RESPONSE', $payment);

        if (isset($payment['statusCode']) && $payment['statusCode'] === '237') {
            // $balance->amount -= $request->total_amount;
            // $balance->save();
             $this->sendTransactionEmail($request, $balance, $owner);
   

            TransactionHistory::where('id', $txId)->update([
            'status' => 'success',
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
            // $this->sendTransactionEmail($request, $balance, $owner, $transactionReference);



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

        // logger('AppMobile REQUEST', $payload);
        $response = $this->orchard->sendPayment($payload);
        // logger('AppMobile RESPONSE', $response);

        if (($response['status'] ?? null) === 'SUCCESS' || ($response['success'] ?? false)) {
            // $balance->amount -= $request->total_amount;
            // $balance->save();
            // $this->sendTransactionEmail($request, $balance, $owner, $exttrid);

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



    protected function sendViaBlaaizInterac(Request $request, $currency, $sendingCurrency, $balance, $txId, $actor, User $owner)
{
    $isApi = $request->expectsJson();

    Log::info('[Blaaiz Interac] Initiating payout', [
        'amount'       => $request->recipient_amount,
        'email'        => $request->interac_email,
        'first_name'   => $request->interac_first_name,
        'last_name'    => $request->interac_last_name,
        'currency'     => $currency,
        'tx_id'        => $txId,
        'actor_id'     => $actor->id,
    ]);

    $blaaiz     = app(\App\Services\BlaaizService::class);  // ← ADD THIS
    $customerId = "019ec763-8348-73d5-9bdb-c85b22c6333b";
    $cadWallet = "61c1135d-b5ce-49c8-add9-0717af84e392";

    $payload = [
        'wallet_id'          => $cadWallet,
        'customer_id'        => $customerId,
        'method'             => 'interac',
        'from_currency_id'   => 'CAD',
        'to_currency_id'     => 'CAD',
        'from_amount'        => $request->recipient_amount,
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
            'status'            => 'pending',
            'payment_provider'  => 'interac',
            'order_id'          => $transaction['id']        ?? null,
            'payment_reference' => $transaction['reference'] ?? null,
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





    // -------------------- IBANQ Payment --------------------
    // protected function sendViaIbanq(Request $request, $currency, $balance)
    // {
    //     $isApi = $request->expectsJson();
    //     $user = auth()->user();
    //     $orderId = (string) Str::uuid();
    //     $reference = 'ref-' . Str::uuid();

    //     // Prepare IBANQ payload
    //     $ibanqPayload = [
    //         'beneficiaryAccountId' => $request->account_id ?? null,
    //         'amount' => $request->recipient_amount,
    //         'currency' => $currency,
    //         'reference' => $reference,
    //     ];

    //     // Call IBANQ Payment Controller (assuming you have a service/controller)
    //     $ibanqResponse = app()->make('App\Http\Controllers\Ibanq\IbanqPaymentController')
    //         ->createPayment($ibanqPayload);

    //     if (!isset($ibanqResponse['success']) || !$ibanqResponse['success']) {
    //         $errorMessage = $ibanqResponse['error'] ?? 'Unknown error from IBANQ';
    //         return $isApi
    //             ? response()->json(['error' => 'IBANQ Payment failed', 'details' => $errorMessage], 422)
    //             : back()->with('error', 'IBANQ Payment failed: ' . $errorMessage);
    //     }

    //     $data = $ibanqResponse['data'] ?? [];

    //     // Deduct balance
    //     $balance->amount -= $request->total_amount;
    //     $balance->save();

    //     // Log transaction
    //     TransactionHistory::create([
    //         'amount' => $request->total_amount,
    //         'fees' => $request->transfer_fee ?? 0,
    //         'currency' => $data['currency'] ?? $currency,
    //         'balance_id' => $balance->id,
    //         'virtual_account_id' => $data['virtual_account_id'] ?? null,
    //         'order_id' => $data['order_id'] ?? $orderId,
    //         'payment_reference' => $data['payment_reference'] ?? null,
    //         'status' => $data['status'] ?? 'unknown',
    //         'failure_reason' => $data['failure_reason'] ?? null,
    //         'transaction_type' => $data['transaction_type'] ?? 'payment',
    //         'payment_method' => $data['payment_method'] ?? null,
    //         'sender_id' => $user->id,
    //         'sender' => $user->business_name ?? null,
    //         'recipient_id' => $data['recipient']['id'] ?? null,
    //         'recipient_country' => $data['recipient']['country'] ?? null,
    //         'recipient_account_name' => $data['recipient']['bank_account']['account_name'] ?? null,
    //         'recipient_bank_name' => $data['recipient']['bank_account']['bank_name'] ?? null,
    //         'recipient_account_number' => $data['recipient']['bank_account']['account_number'] ?? null,
    //         'exchange_rate' => $data['exchange_rate']['rate'] ?? null,
    //         'single_rate' => $data['exchange_rate']['single_rate'] ?? null,
    //         'reference' => $data['reference'] ?? $reference,
    //         'user_id' => $user->id,
    //         'method' => $isApi ? 'api' : 'web',
    //     ]);

    //     return $isApi
    //         ? response()->json(['message' => 'IBANQ transaction successful', 'data' => $data])
    //         : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully via IBANQ!');
    // }

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
            'method' => $tx->method,
            'amount' => (float) $tx->amount,
            'total_amount' => (float) $tx->total_amount,
            'fees' => (float) ($tx->fees ?? 0),
            'currency' => $tx->currency,
            'to_currency' => $tx->to_currency,
            'recipient_amount' => (float) ($tx->recipient_amount ?? 0),
            'payment_provider' => 'Flovide',
            'recipient_account_name' => $tx->recipient_account_name,
            'recipient_account_number' => $tx->recipient_account_number,
            // 'exchange_rate' => strtoupper(explode(' ', $request->exchange_rate)[3] ?? null),
            'interac_email'      => $tx->interac_email      ?? null,
            'interac_first_name' => $tx->interac_first_name ?? null,
            'interac_last_name'  => $tx->interac_last_name  ?? null,
            'created_at' => optional($tx->created_at)->toIso8601String(),
        ];
    }




    public function runPayazaCheck(Request $request)
{
    if ($request->query('key') !== env('CRON_SECRET')) {
        return response()->json(['ok' => false], 403);
    }

    (new \App\Jobs\CheckPayazaTransactions)->handle();
    (new \App\Jobs\CheckOrchardTransactions)->handle();
    // (new \App\Jobs\CheckBlaaizInteracTransactions)->handle();



    return response()->json(['ok' => true]);
}




    
//  public function index() 
// {


//     $user = auth()->user();

//     $beneficiaries = Beneficia::where('user_id', $user->id)->get();

//     $balances = Balance::where('user_id', $user->id)->get();
//     foreach ($balances as $balance) {
//         $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
//     }

//     $balanceList = $balances;

//     // ✅ add currencies list
//     $currencies = Currency::all();

//     return view('business.send', compact('beneficiaries', 'balanceList', 'currencies'));
// }

    public function index(Request $request)
    {
        $actor = auth()->user();
        [$ownerId, $memberId, $role] = $this->resolveOwnerAndMember($request, $actor);

        if (! $ownerId) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $beneficiaries = Beneficia::where('user_id', $ownerId)->get();

        $balances = Balance::where('user_id', $ownerId)->get();
        foreach ($balances as $balance) {
            $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
        }

        $balanceList = $balances;
        $currencies = Currency::all();

        return view('business.send', compact('beneficiaries', 'balanceList', 'currencies'));
    }
    

    
    public function getUserTotalBalance(Request $request)
    {
        $user = auth()->user();

        $total = Balance::where('user_id', $user->id)->sum('amount');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User total balance fetched successfully',
                'code' => 'TOTAL_BALANCE_FETCHED',
                'data' => [
                    'user_id' => $user->id,
                    'total_balance' => $total
                ]
            ], 200);
        }

        return view('business.user_balance_total', [
            'total' => $total
        ]);
    }




    public function getExchangeRates(Request $request)
    {
        $from = strtoupper($request->input('from_currency'));
        $to   = strtoupper($request->input('to_currency'));
        $amount = (float) $request->input('amount', 1);

        try {
            $rate = ExchangeRate::whereHas('fromCurrency', function ($q) use ($from) {
                    $q->where('code', $from);
                })
                ->whereHas('toCurrency', function ($q) use ($to) {
                    $q->where('code', $to);
                })
                ->first();

            if (!$rate) {
                throw new \Exception("Rate not found");
            }

            $converted = $amount * $rate->rate;

            $rateText = sprintf(
                "%s 1.00 = %s %s",
                $from,
                $to,
                number_format($rate->rate, 6, '.', '')
            );

            return response()->json([
                'success' => true,
                'message' => 'Exchange rate fetched',
                'code' => 'EXCHANGE_RATE_FETCHED',
                'data' => [
                    'converted' => $converted,
                    'transfer_fee' => $rate->transfer_fee,
                    'exchange_rate' => $rateText,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 'RATE_NOT_FOUND',
                'data' => null
            ], 400);
        }
    }

    public function refreshExchangeRates(Request $request)
    {
        $rates = \App\Models\ExchangeRate::with(['fromCurrency:id,code', 'toCurrency:id,code'])
            ->get()
            ->map(function ($r) {
                return [
                    'from_currency' => $r->fromCurrency->code ?? null,
                    'to_currency' => $r->toCurrency->code ?? null,
                    'rate' => (float) $r->rate,
                    'transfer_fee' => (float) $r->transfer_fee,
                    'updated_at' => optional($r->updated_at)->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'All exchange rates refreshed successfully',
            'code' => 'EXCHANGE_RATES_REFRESHED',
            'data' => [
                'rates' => $rates,
                'last_updated' => optional(\App\Models\ExchangeRate::max('updated_at'))->toIso8601String(),
                'count' => $rates->count(),
            ],
        ], 200);
    }

    


    


    


//         public function indexexc() 
//     {
//         $user = auth()->user();
//         $beneficiaries = Beneficia::where('user_id', $user->id)->get();

//         $balances = Balance::where('user_id', $user->id)->get(); 
//         foreach ($balances as $balance) {
//             $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
//         }
//         $balanceList = $balances;
//         return view('business.exchange_rate', compact('beneficiaries', 'balances'));
//     }





public function indexexc(Request $request)
{
    $actor = auth()->user();
    [$ownerId, $memberId, $role] = $this->resolveOwnerAndMember($request, $actor);

    if (! $ownerId) {
        return back()->with('error', 'Unauthorized');
    }

    $beneficiaries = Beneficia::where('user_id', $ownerId)->get();

    $balances = Balance::where('user_id', $ownerId)->get();
    foreach ($balances as $balance) {
        $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
    }

    $balanceList = $balances;

    return view('business.exchange_rate', compact('beneficiaries', 'balances', 'balanceList'));
}

public function exchangeSubmit(Request $request)
{
    $request->validate([
        'from_currency' => 'required|string',
        'to_currency' => 'required|string',
        'amount' => 'required|numeric|min:1',
    ]);

    //  dd($request->all());

    $actor = auth()->user();
    [$ownerId, $memberId, $role, $owner] = $this->resolveOwnerAndMember($request, $actor);

    if (! $ownerId || ! $owner) {
        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => 'Owner account not found',
                'code' => 'OWNER_NOT_FOUND',
                'data' => null
            ], 422)
            : back()->withErrors(['amount' => 'Owner account not found']);
    }

    $from = strtoupper($request->from_currency);
    $to = strtoupper($request->to_currency);
    $amount = (float) $request->amount;

    if ($from === $to) {
        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => 'From and To currency cannot be the same.',
                'code' => 'SAME_CURRENCY',
                'data' => null
            ], 422)
            : back()->withErrors(['amount' => 'From and To currency cannot be the same.']);
    }

    $fromBalance = Balance::where('user_id', $ownerId)->where('currency', $from)->first();
    $toBalance   = Balance::where('user_id', $ownerId)->where('currency', $to)->first();

    if (! $fromBalance || ! $toBalance) {
        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => 'Invalid wallet selection.',
                'code' => 'INVALID_WALLET',
                'data' => null
            ], 422)
            : back()->withErrors(['amount' => 'Invalid wallet selection.']);
    }

    if ($fromBalance->amount < $amount) {
        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => 'Insufficient balance.',
                'code' => 'INSUFFICIENT_BALANCE',
                'data' => null
            ], 422)
            : back()->withErrors(['amount' => 'Insufficient balance.']);
    }

    $rate = ExchangeRate::whereHas('fromCurrency', fn($q) => $q->where('code', $from))
        ->whereHas('toCurrency', fn($q) => $q->where('code', $to))
        ->first();

    if (! $rate) {
        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => 'Rate not found.',
                'code' => 'RATE_NOT_FOUND',
                'data' => null
            ], 422)
            : back()->withErrors(['amount' => 'Rate not found.']);
    }

    $converted = $amount * $rate->rate;

    DB::beginTransaction();
    try {
        $fromBalance->amount -= $amount;
        $fromBalance->save();

        $toBalance->amount += $converted;
        $toBalance->save();

        $tx = TransactionHistory::create([
            'amount' => $amount,
            'currency' => $from,
            'balance_id' => $fromBalance->id,
            'status' => 'success',
            
            // 'method' => 'exchange',
            'method' => 'Swap ' . $from . ' to ' . $to,

            'reference' => 'ref-' . Str::uuid(),
            'user_id' => $ownerId,
            'created_by_member_id' => $memberId,
            'recipient_country' => strtoupper(substr($to, 0, 2)),
            'sender_id' => $ownerId,
            'sender' => $actor->business_name ?? $actor->name,
            'recipient_account_number' => $from,
            'recipient_account_name' => $actor->business_name ?? $actor->name,
            'to_currency' => $to,
            'recipient_amount' => $converted,
            'exchange_rate' => $rate->rate,
        ]);

        DB::commit();

    } catch (\Throwable $e) {
        DB::rollBack();

        return $request->expectsJson()
            ? response()->json([
                'success' => false,
                'message' => 'Exchange failed.',
                'code' => 'EXCHANGE_FAILED',
                'data' => $e->getMessage(),
            ], 500)
            : back()->withErrors(['amount' => 'Exchange failed.']);
    }

    $this->sendExchangeEmail($request, $from, $to, $amount, $converted, $fromBalance->amount, $owner);

    $balances = Balance::where('user_id', $ownerId)->get()->map(function ($balance) {
        return [
            'id' => $balance->id,
            'currency' => $balance->currency,
            'amount' => $balance->amount,
        ];
    })->values();

    $transaction = [
        'id' => $tx->id,
        'reference' => $tx->reference,
        'status' => $tx->status,
        'method' => $tx->method,
        'amount' => $tx->amount,
        'currency' => $tx->currency,
        'to_currency' => $tx->to_currency,
        'recipient_amount' => $tx->recipient_amount,
        'exchange_rate' => $tx->exchange_rate,
        'sender' => $tx->sender,
        'recipient_account_number' => $tx->recipient_account_number,
        'recipient_account_name' => $tx->recipient_account_name,
        'created_at' => $tx->created_at->format('Y-m-d H:i:s'),
    ];

    return $request->expectsJson()
        ? response()->json([
            'success' => true,
            'message' => 'Exchange completed successfully!',
            'code' => 'EXCHANGE_SUCCESS',
            'data' => [
                'from_currency' => $from,
                'to_currency' => $to,
                'amount' => $amount,
                'converted' => $converted,
                'reference' => $tx->reference,
                'method' => $tx->method,
                'balances' => $balances,
                'transaction' => $transaction,
            ]
        ], 200)
        : back()->with('success', 'Exchange completed successfully!');
}


    protected function sendExchangeEmail(Request $request, $from, $to, $amount, $converted, $currentBalance, User $owner)
    {
        $data = [
            'name' => $owner->business_name ?? $owner->name,
            'amount_sent' => number_format((float) $amount, 2),
            'recipient_amount' => number_format((float) $converted, 2),
            'fee' => number_format(0, 2),
            'total_amount' => number_format((float) $amount, 2),
            'current_balance' => number_format((float) $currentBalance, 2),
            'sending_currency' => $from,
            'recipient_currency' => $to,
            'reference' => 'Exchange',
        ];

        Mail::to($owner->email)->send(new TransactionSentMail($data));
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
