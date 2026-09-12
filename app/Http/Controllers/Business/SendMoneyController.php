<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Beneficia;
use App\Models\TransactionHistory;
use App\Traits\CurrencyHelper;
use App\Traits\SelectsBalanceId;
use App\Models\PromoCode;
use App\Models\PromoCodeRedemption;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Notifications\GeneralNotification;
use App\Services\FirebaseNotificationService;






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
        $mode  = session('mode', 'live');

        if (!$isApi && $mode === 'test') {
            return redirect()->back()->withErrors(['message' => 'Sending Money in Test Mode is only available via the API.']);
        }

        $request->validate([
            'amount'             => 'required|numeric|min:1',
            'recipient_id'       => 'required|uuid',
            'balance_id'         => 'required',
            'payment_reference'          => 'nullable|string',
            'transfer_fee'       => 'nullable|numeric',
            'total_amount'       => 'required|numeric',
            'exchange_rate'      => 'required|string',
            'recipient_amount'   => 'required|numeric',
            'account_number'     => 'nullable|string',
            'account_name'       => 'nullable|string',
            'bank'               => 'nullable|string',
            'bank_code'          => 'nullable|string',
            'transfer_method'    => 'nullable|string',
            'interac_email'      => 'nullable|email',
            'interac_first_name' => 'nullable|string',
            'interac_last_name'  => 'nullable|string',
            'promo_code'         => 'nullable|string|max:50',
            'transaction_pin' => 'required|digits:4',

        ]);

        //dd($request->all());

        $actor = $this->resolveKeyUser($request) ?? auth()->user();
        if (!$actor) {
            $msg = 'Unauthorized';
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'UNAUTHORIZED', 'data' => null], 401)
                : back()->withInput()->with('error', $msg);
        }

        [$ownerId, $memberId, $role, $owner] = $this->resolveOwnerAndMember($request, $actor);
        if (!$ownerId || !$owner) {
            $msg = 'Owner account not found';
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'OWNER_NOT_FOUND', 'data' => null], 422)
                : back()->withInput()->with('error', $msg);
        }


        // Check if business account is locked
        if ((bool) $owner->is_locked === true) {

            Log::warning('[Send Money] Blocked - business account is locked', [
                'authenticated_user_id' => $actor->id,
                'owner_id' => $owner->id,
                'member_id' => $memberId,
                'role' => $role,
                'ip_address' => $request->ip(),
            ]);

            $msg = 'Your business account is locked. You cannot send money at this time.';

            return $isApi
                ? response()->json([
                    'success' => false,
                    'message' => $msg,
                    'code' => 'ACCOUNT_LOCKED',
                    'data' => null,
                ], 403)
                : back()->withInput()->with('error', $msg);
        }

        // ── Transaction PIN verification ────────────────────────────────────────
        if (empty($owner->transaction_pin)) {
            $msg = 'You have not set a transaction PIN yet. Please set one to continue.';
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'PIN_NOT_SET', 'data' => null], 422)
                : back()->withInput()->with('error', $msg);
        }

        if ($owner->transaction_pin_locked_until && now()->lt($owner->transaction_pin_locked_until)) {
            $msg = 'Too many incorrect PIN attempts. Try again after ' . $owner->transaction_pin_locked_until->diffForHumans();
            Log::warning('[Transaction PIN] Business locked out', ['owner_id' => $owner->id]);
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'PIN_LOCKED', 'data' => null], 423)
                : back()->withInput()->with('error', $msg);
        }

        if (!Hash::check($request->transaction_pin, $owner->transaction_pin)) {
            $owner->transaction_pin_attempts += 1;

            if ($owner->transaction_pin_attempts >= 5) {
                $owner->transaction_pin_locked_until = now()->addMinutes(15);
                $owner->transaction_pin_attempts = 0;
            }

            $owner->save();

            Log::warning('[Transaction PIN] Business incorrect pin', [
                'owner_id' => $owner->id,
                'attempts' => $owner->transaction_pin_attempts,
            ]);

            $msg = 'Incorrect transaction PIN.';
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'PIN_INCORRECT', 'data' => null], 422)
                : back()->withInput()->with('error', $msg);
        }

        // reset attempts on success
        if ($owner->transaction_pin_attempts > 0 || $owner->transaction_pin_locked_until) {
            $owner->transaction_pin_attempts = 0;
            $owner->transaction_pin_locked_until = null;
            $owner->save();
        }


        // ── Promo code resolution ────────────────────────────────────────────
        $promoCode = null;

        if ($request->filled('promo_code')) {
            $inputCode = strtoupper(trim($request->promo_code));

            $promoCode = PromoCode::where('code', $inputCode)
                ->where('owner_type', 'business')
                ->where('status', 'active')
                ->first();

            if (!$promoCode) {
                $msg = 'Invalid or inactive promo code.';
                return $isApi
                    ? response()->json(['success' => false, 'message' => $msg, 'code' => 'PROMO_CODE_INVALID', 'data' => null], 422)
                    : back()->withInput()->with('error', $msg);
            }

            if ((string) $promoCode->owner_id === (string) $ownerId) {
                $msg = 'You cannot use your own promo code.';
                return $isApi
                    ? response()->json(['success' => false, 'message' => $msg, 'code' => 'PROMO_CODE_SELF_USE', 'data' => null], 422)
                    : back()->withInput()->with('error', $msg);
            }
        }

        $sendingCurrency = strtoupper(explode(' ', $request->exchange_rate)[1] ?? 'NGN');
        $currency        = strtoupper(explode(' ', $request->exchange_rate)[4] ?? 'NGN');

        // $transferFee          = (float) $request->transfer_fee;
        // $amount          = (float) $request->amount;
        // $totalAmount          = (float) $request->total_amount;
        // $recipientAmount = (float) $request->recipient_amount;

        $originalTransferFee = (float) $request->transfer_fee;
        $amount              = (float) $request->amount;
        $totalAmount         = (float) $request->total_amount;
        $recipientAmount     = (float) $request->recipient_amount;
        $transferFee         = $originalTransferFee;

        // ── Promo code: server-side fee waiver, never trust client-sent totals ──
        if ($promoCode) {
            $transferFee = 0;
            $totalAmount = $amount; // sender pays only the amount, no fee
        }

        // ── Currency limits (on sending amount) ───────────────────────────────
        $limit = Currency::where('code', $sendingCurrency)->where('is_active', true)->first();
        if ($limit) {
            if (!is_null($limit->min_amount) && $amount < $limit->min_amount) {
                $msg = "Minimum transfer for {$sendingCurrency} is {$limit->min_amount}";
                return $isApi
                    ? response()->json(['success' => false, 'message' => $msg, 'code' => 'AMOUNT_BELOW_MINIMUM', 'data' => null], 422)
                    : back()->withInput()->with('error', $msg);
            }
            if (!is_null($limit->max_amount) && $amount > $limit->max_amount) {
                $msg = "Maximum transfer for {$sendingCurrency} is {$limit->max_amount}";
                return $isApi
                    ? response()->json(['success' => false, 'message' => $msg, 'code' => 'AMOUNT_ABOVE_MAXIMUM', 'data' => null], 422)
                    : back()->withInput()->with('error', $msg);
            }
        }


        $netRecipientAmount = (int) round($recipientAmount, 0);
        // dd($netRecipientAmount);

        // ── Balance check — deduct sender's full amount ───────────────────────
        $balance = Balance::where('id', $request->balance_id)
            ->where('user_id', $ownerId)
            ->first();
        

        if (!$balance) {
            $msg = 'Invalid balance';
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'INVALID_BALANCE', 'data' => null], 422)
                : back()->withInput()->with('error', $msg);
        }

        if ($balance->is_locked) {
        $msg = 'This balance is locked and cannot be used for payouts. Please contact support.';
        return $isApi
            ? response()->json(['success' => false, 'message' => $msg, 'code' => 'BALANCE_LOCKED', 'data' => null], 422)
            : back()->withInput()->with('error', $msg);
        }

        if ($balance->amount < $totalAmount) {
            $msg = 'Insufficient funds';
            return $isApi
                ? response()->json(['success' => false, 'message' => $msg, 'code' => 'INSUFFICIENT_FUNDS', 'data' => null], 422)
                : back()->withInput()->with('error', $msg);
        }

        
        DB::beginTransaction();
        try {
            // Deduct full sending amount from sender's balance
            $balance->amount -= $totalAmount;
            $balance->save();

            // ── Deduct platform fee from the payout-currency balance ───────────────
            $feeDeductedFrom = null;

        
            $tx = TransactionHistory::create([
                'user_id'                  => $ownerId,
                'sender_id'                => $ownerId,
                'sender'                   => $actor->business_name ?? $actor->name,
                'balance_id'               => $balance->id,
                'fee_balance_id'           => $feeDeductedFrom,
                'currency'                 => $sendingCurrency,
                'amount'                   => $amount,
                'fees'                     => $transferFee,
                'total_amount'             => $totalAmount,
                'recipient_amount'         => $netRecipientAmount,
                'to_currency'              => $currency,
                'recipient_bank_currency'  => $currency,
                'recipient_country'        => strtoupper(substr($currency, 0, 2)),
                'exchange_rate'            => strtoupper(explode(' ', $request->exchange_rate)[3] ?? null),
                'status'                   => 'success',
                'method'                   => 'withdrawal',
                'type'                     => 'withdrawal',
                'payment_provider'         => 'wallect',
                'transfer_method'         => $request->transfer_method,
                'order_id'                 => $request->order_id  ?? null,
                'reference'                => 'ref-' . Str::uuid(),
                'payment_reference'                => $request->payment_reference,
                'recipient_id'             => $request->recipient_id,
                'recipient_account_number' => $request->account_number,
                'recipient_account_name'   => $request->account_name
                    ?? trim(($request->interac_first_name ?? '') . ' ' . ($request->interac_last_name ?? ''))
                    ?: null,
                'bank_code'                => $request->bank_code,
                'recipient_bank_name'      => $request->bank,
                'interac_email'            => $request->interac_email      ?? null,
                'interac_first_name'       => $request->interac_first_name ?? null,
                'interac_last_name'        => $request->interac_last_name  ?? null,
                'promo_code'    => $promoCode->code ?? null,
                'promo_code_id' => $promoCode->id ?? null,
                'promo_fee_waived' => $promoCode ? $originalTransferFee : null,
            ]);

            // ── Promo code reward: credit code owner's default balance ─────────────
            if ($promoCode) {
                $rewardAmount = $promoCode->calculateReward($amount);
                $ownerModel   = $promoCode->ownerModel();

                if ($ownerModel) {
                    $ownerColumn  = $promoCode->owner_type === 'business' ? 'user_id' : 'personal_id';

                    $ownerBalance = Balance::where($ownerColumn, $ownerModel->id)
                        ->orderBy('created_at', 'asc')
                        ->first();

                    if ($ownerBalance) {
                        $ownerBalance->amount += $rewardAmount;
                        $ownerBalance->save();

                        TransactionHistory::create([
                            $ownerColumn             => $ownerModel->id,
                            'balance_id'             => $ownerBalance->id,
                            'amount'                 => $rewardAmount,
                            'currency'               => $ownerBalance->currency,
                            'status'                 => 'success',
                            'type'                   => 'credit',
                            'method'                 => 'credit',
                            'payment_provider'       => 'promo_reward',
                            'transaction_type'       => 'promo_reward',
                            'reference'              => 'promo-' . Str::uuid(),
                            'sender'                 => 'Promo Code Reward',
                            'recipient_account_name' => $ownerModel->business_name ?? ($ownerModel->firstname ?? null),
                            'mode'                   => $mode,
                        ]);

                        PromoCodeRedemption::create([
                            'promo_code_id'          => $promoCode->id,
                            'transaction_history_id' => $tx->id,
                            'redeemer_type'          => 'business',
                            'redeemer_id'            => $ownerId,
                            'transaction_amount'     => $amount,
                            'transaction_currency'   => $sendingCurrency,
                            'fee_waived'             => $originalTransferFee,
                            'reward_amount'          => $rewardAmount,
                            'reward_currency'        => $ownerBalance->currency,
                        ]);

                        Log::info('[Promo Code] Reward credited', [
                            'promo_code_id' => $promoCode->id,
                            'owner_id'      => $ownerModel->id,
                            'reward_amount' => $rewardAmount,
                            'currency'      => $ownerBalance->currency,
                        ]);
                    } else {
                        Log::warning('[Promo Code] Owner has no balance to receive reward', [
                            'promo_code_id' => $promoCode->id,
                            'owner_id'      => $ownerModel->id,
                        ]);
                    }
                }
            }
            app(\App\Services\ReferralBonusService::class)->checkAndNotify($owner->fresh(), $tx->fresh());
            // ── Route to provider ─────────────────────────────────────────────
            if (in_array($currency, ['UGX']) && filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
                $response = $this->sendViaPivot($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner, $netRecipientAmount, $transferFee);
            } elseif (in_array($currency, ['GHS']) && filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN)) {
                $response = $this->sendViaAppMobile($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner, $netRecipientAmount, $transferFee);
            } elseif (in_array($currency, ['NGN', 'TZS', 'XOF', 'XAF', 'ZAR', 'KES']) && filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
                $response = $this->sendViaPayaza($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner, $netRecipientAmount, $transferFee);
            } elseif ($currency === 'CAD') {
                $response = $this->sendViaBlaaizInterac($request, $currency, $sendingCurrency, $balance, $tx->id, $actor, $owner, $netRecipientAmount, $transferFee);
            } else {
                DB::rollBack();
                $msg = 'No payment provider available for this currency';
                return $isApi
                    ? response()->json(['success' => false, 'message' => $msg, 'code' => 'PROVIDER_NOT_AVAILABLE', 'data' => null], 422)
                    : back()->withInput()->with('error', $msg);
            }

            DB::commit();
            return $response;

        } catch (\Exception $e) {
            DB::rollBack();
            return $isApi
                ? response()->json(['success' => false, 'message' => 'Transaction failed', 'code' => 'TXN_FAILED', 'data' => $e->getMessage()], 500)
                : back()->withInput()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }




    // -------------------- Pivot Payment --------------------
    protected function sendViaPivot(Request $request, $currency, $sendingCurrency, $balance, $txId, $actor, User $owner,$netRecipientAmount,$transferFee)
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
            "amount" => $netRecipientAmount,
            "chargeAmount" => 0,
            "narration" => $request->reference ?? "Payment",
            "currencyCode" => $currency,
            "countryCode" => $currency === 'UGX' ? 'UG' : 'KE',
            "customerName" => $request->account_name,
        ];

        $payload['extraData'] = [
            "bankSortCode" => $sortCode
        ];

        if ($bankType !== 'mobile') {
            $payload['extraData']['amount'] = $netRecipientAmount;
        }

        // logger('Pivot REQUEST', $payload);
        $payment = $this->pivot->postTransaction($token, $payload);
        // logger('Pivot RESPONSE', $payment);

        if (isset($payment['statusCode']) && $payment['statusCode'] === '237') {
            // $balance->amount -= $request->total_amount;
            // $balance->save();
            //  $this->sendTransactionEmail($request, $balance, $owner,$netRecipientAmount);
   

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



    protected function sendViaPayaza(Request $request, $currency, $sendingCurrency, $balance, $txId, $actor, User $owner,$netRecipientAmount,$transferFee)
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
                "payout_amount" => $netRecipientAmount,
                "transaction_pin" => env('PAYAZA_MERCHANT_PIN'),
                "account_reference" => $accountReference,
                "currency" => $currency,
                "country" => strtoupper(substr($currency, 0, 2)),
                "payout_beneficiaries" => [
                    [
                        "credit_amount" => $netRecipientAmount,
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



    protected function sendViaAppMobile(Request $request, $currency, $sendingCurrency, $balance, $txId, $actor, User $owner,$netRecipientAmount,$transferFee)
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
            "amount"          => number_format($netRecipientAmount, 2, '.', ''),
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



    protected function sendViaBlaaizInterac(Request $request, $currency, $sendingCurrency, $balance, $txId, $actor, User $owner,$netRecipientAmount,$c)
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
                'reference' => $transaction['reference'] ?? null,
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


    protected function sendTransactionEmail(Request $request, $balance, $owner, $netRecipientAmount, $transferFee, $reference = null)
    {
        $rateParts = preg_split('/\s+/', trim((string) $request->exchange_rate));
        $sendingCurrency = strtoupper($rateParts[1] ?? 'NGN');
        $recipientCurrency = strtoupper($rateParts[4] ?? 'NGN');

        $data = [
            'name' => $owner->business_name ?? $owner->name,
            'amount_sent' => number_format((float) $request->amount, 2),
            'recipient_amount' => number_format((float) $netRecipientAmount, 2),
            'fee' => number_format((float) ($transferFee ?? 0), 2),
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






    public function index(Request $request)
{
    $actor = auth()->user();
    [$ownerId, $memberId, $role] = $this->resolveOwnerAndMember($request, $actor);

    if (! $ownerId) {
        return redirect()->back()->with('error', 'Unauthorized');
    }

    $mode = session('mode', 'live');

    $beneficiaries = Beneficia::where('user_id', $ownerId)
        ->where('mode', $mode)
        ->get();

    $balances = Balance::where('user_id', $ownerId)
        ->where('mode', $mode)
        ->get();

    foreach ($balances as $balance) {
        $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
    }

    $balanceList = $balances;
    $currencies = Currency::all();

    return view('business.send', compact('beneficiaries', 'balanceList', 'currencies', 'mode'));
}
    

public function getUserTotalBalance(Request $request)
{
    $user = auth()->user();
    $mode = session('mode', 'live');

    $team    = TeamMembers::where('user_id', $user->id)->first();
    $ownerId = $team ? $team->owner_id : $user->id;

    $total = Balance::where('user_id', $ownerId)
        ->where('mode', $mode)
        ->sum('amount');

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'User total balance fetched successfully',
            'code' => 'TOTAL_BALANCE_FETCHED',
            'data' => [
                'user_id' => $ownerId,
                'mode' => $mode,
                'total_balance' => $total
            ]
        ], 200);
    }

    return view('business.user_balance_total', [
        'total' => $total
    ]);
}



//    public function getExchangeRates(Request $request)
// {
//     $from = strtoupper($request->input('from_currency'));
//     $to   = strtoupper($request->input('to_currency'));
//     $amount = (float) $request->input('amount', 1);

//     try {
//         $rate = ExchangeRate::whereHas('fromCurrency', function ($q) use ($from) {
//                 $q->where('code', $from);
//             })
//             ->whereHas('toCurrency', function ($q) use ($to) {
//                 $q->where('code', $to);
//             })
//             ->orderByDesc('updated_at')   // NEW: always pick the most recently updated rate row
//             ->orderByDesc('id')           // NEW: tiebreaker if updated_at is identical
//             ->first();

//         if (!$rate) {
//             throw new \Exception("Rate not found");
//         }

//         $converted = $amount * $rate->rate;

//         $rateText = sprintf(
//             "%s 1.00 = %s %s",
//             $from,
//             $to,
//             number_format($rate->rate, 6, '.', '')
//         );

//         return response()->json([
//             'success' => true,
//             'message' => 'Exchange rate fetched',
//             'code' => 'EXCHANGE_RATE_FETCHED',
//             'data' => [
//                 'converted' => $converted,
//                 'transfer_fee' => $rate->transfer_fee,
//                 'exchange_rate' => $rateText,
//             ]
//         ], 200);

//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => $e->getMessage(),
//             'code' => 'RATE_NOT_FOUND',
//             'data' => null
//         ], 400);
//     }
// }



// public function getExchangeRates(Request $request)
// {
//     $from   = strtoupper($request->input('from_currency'));
//     $to     = strtoupper($request->input('to_currency'));
//     $amount = (float) $request->input('amount', 1);

//     try {
//         $actor = $this->resolveKeyUser($request) ?? auth()->user();
//         if (! $actor) {
//             throw new \Exception("Unauthorized");
//         }

//         [$ownerId, $memberId, $role, $owner] = $this->resolveOwnerAndMember($request, $actor);
//         if (! $ownerId) {
//             throw new \Exception("Owner account not found");
//         }

//         $rate = ExchangeRate::whereHas('fromCurrency', function ($q) use ($from) {
//                 $q->where('code', $from);
//             })
//             ->whereHas('toCurrency', function ($q) use ($to) {
//                 $q->where('code', $to);
//             })
//             ->first();

//         if (!$rate) {
//             throw new \Exception("Rate not found");
//         }

//         $converted = $amount * $rate->rate;

//         // ── Platform fee on recipient (TO) currency, per-user override ──
//         $userFee = \App\Models\UserCurrencyFee::where('user_id', $ownerId)
//             ->where('currency', $to)
//             ->first();

//         $transferFee = (float) $rate->transfer_fee;

//         if ($userFee && $userFee->payout_enabled) {
//             $transferFee = round(
//                 ($converted * $userFee->payout_percent / 100) + $userFee->payout_fixed,
//                 2
//             );
//         }

//         $rateText = sprintf(
//             "%s 1.00 = %s %s",
//             $from,
//             $to,
//             number_format($rate->rate, 6, '.', '')
//         );

//         return response()->json([
//             'success' => true,
//             'message' => 'Exchange rate fetched',
//             'code' => 'EXCHANGE_RATE_FETCHED',
//             'data' => [
//                 'converted' => $converted,
//                 'transfer_fee' => $transferFee,
//                 'exchange_rate' => $rateText,
//             ]
//         ], 200);

//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => $e->getMessage(),
//             'code' => 'RATE_NOT_FOUND',
//             'data' => null
//         ], 400);
//     }
// }



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

    





public function indexexc(Request $request)
{
    $actor = auth()->user();
    [$ownerId, $memberId, $role] = $this->resolveOwnerAndMember($request, $actor);

    if (! $ownerId) {
        return back()->with('error', 'Unauthorized');
    }

    $mode = session('mode', 'live');

    $beneficiaries = Beneficia::where('user_id', $ownerId)
        ->where('mode', $mode)
        ->get();

    $balances = Balance::where('user_id', $ownerId)
        ->where('mode', $mode)
        ->get();

    foreach ($balances as $balance) {
        $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
    }

    $balanceList = $balances;

    return view('business.exchange_rate', compact('beneficiaries', 'balances', 'balanceList', 'mode'));
}
// public function exchangeSubmit(Request $request)
// {

//     $isApi = $request->expectsJson();
//            $mode  = session('mode', 'live');

//         // ── Block web-based deletion while in Test mode ──────────────────────
//         if (!$isApi && $mode === 'test') {
//             $message = 'Exchange Money in Test Mode is only available via the API.';
//             return redirect()->back()->withErrors(['message' => $message]);
//         }
//     $request->validate([
//         'from_currency' => 'required|string',
//         'to_currency' => 'required|string',
//         'amount' => 'required|numeric|min:1',
//     ]);

//     //  dd($request->all());

//     $actor = auth()->user();
//     [$ownerId, $memberId, $role, $owner] = $this->resolveOwnerAndMember($request, $actor);

//     if (! $ownerId || ! $owner) {
//         return $request->expectsJson()
//             ? response()->json([
//                 'success' => false,
//                 'message' => 'Owner account not found',
//                 'code' => 'OWNER_NOT_FOUND',
//                 'data' => null
//             ], 422)
//             : back()->withErrors(['amount' => 'Owner account not found']);
//     }

//     $from = strtoupper($request->from_currency);
//     $to = strtoupper($request->to_currency);
//     $amount = (float) $request->amount;

//     if ($from === $to) {
//         return $request->expectsJson()
//             ? response()->json([
//                 'success' => false,
//                 'message' => 'From and To currency cannot be the same.',
//                 'code' => 'SAME_CURRENCY',
//                 'data' => null
//             ], 422)
//             : back()->withErrors(['amount' => 'From and To currency cannot be the same.']);
//     }

//     $fromBalance = Balance::where('user_id', $ownerId)->where('currency', $from)->first();
//     $toBalance   = Balance::where('user_id', $ownerId)->where('currency', $to)->first();

//     if (! $fromBalance || ! $toBalance) {
//         return $request->expectsJson()
//             ? response()->json([
//                 'success' => false,
//                 'message' => 'Invalid wallet selection.',
//                 'code' => 'INVALID_WALLET',
//                 'data' => null
//             ], 422)
//             : back()->withErrors(['amount' => 'Invalid wallet selection.']);
//     }

//     if ($fromBalance->amount < $amount) {
//         return $request->expectsJson()
//             ? response()->json([
//                 'success' => false,
//                 'message' => 'Insufficient balance.',
//                 'code' => 'INSUFFICIENT_BALANCE',
//                 'data' => null
//             ], 422)
//             : back()->withErrors(['amount' => 'Insufficient balance.']);
//     }

//     $rate = ExchangeRate::whereHas('fromCurrency', fn($q) => $q->where('code', $from))
//         ->whereHas('toCurrency', fn($q) => $q->where('code', $to))
//         ->first();

//     if (! $rate) {
//         return $request->expectsJson()
//             ? response()->json([
//                 'success' => false,
//                 'message' => 'Rate not found.',
//                 'code' => 'RATE_NOT_FOUND',
//                 'data' => null
//             ], 422)
//             : back()->withErrors(['amount' => 'Rate not found.']);
//     }

//     $converted = $amount * $rate->rate;

//     DB::beginTransaction();
//     try {
//         $fromBalance->amount -= $amount;
//         $fromBalance->save();

//         $toBalance->amount += $converted;
//         $toBalance->save();

//         $tx = TransactionHistory::create([
//             'amount' => $amount,
//             'currency' => $from,
//             'balance_id' => $fromBalance->id,
//             'status' => 'success',
            
//             // 'method' => 'exchange',
//             'type' => 'Swap',
//             'method' => 'Swap ' . $from . ' to ' . $to,

//             'reference' => 'ref-' . Str::uuid(),
//             'user_id' => $ownerId,
//             'created_by_member_id' => $memberId,
//             'recipient_country' => strtoupper(substr($to, 0, 2)),
//             'sender_id' => $ownerId,
//             'sender' => $actor->business_name ?? $actor->name,
//             'recipient_account_number' => $from,
//             'recipient_account_name' => $actor->business_name ?? $actor->name,
//             'to_currency' => $to,
//             'recipient_amount' => $converted,
//             'exchange_rate' => $rate->rate,
//         ]);

//         DB::commit();

//     } catch (\Throwable $e) {
//         DB::rollBack();

//         return $request->expectsJson()
//             ? response()->json([
//                 'success' => false,
//                 'message' => 'Exchange failed.',
//                 'code' => 'EXCHANGE_FAILED',
//                 'data' => $e->getMessage(),
//             ], 500)
//             : back()->withErrors(['amount' => 'Exchange failed.']);
//     }

//     $this->sendExchangeEmail($request, $from, $to, $amount, $converted, $fromBalance->amount, $owner);

//     $balances = Balance::where('user_id', $ownerId)->get()->map(function ($balance) {
//         return [
//             'id' => $balance->id,
//             'currency' => $balance->currency,
//             'amount' => $balance->amount,
//         ];
//     })->values();

//     $transaction = [
//         'id' => $tx->id,
//         'reference' => $tx->reference,
//         'status' => $tx->status,
//         'method' => $tx->method,
//         'type' => $tx->type,
//         'amount' => $tx->amount,
//         'currency' => $tx->currency,
//         'to_currency' => $tx->to_currency,
//         'recipient_amount' => $tx->recipient_amount,
//         'exchange_rate' => $tx->exchange_rate,
//         'sender' => $tx->sender,
//         'recipient_account_number' => $tx->recipient_account_number,
//         'recipient_account_name' => $tx->recipient_account_name,
//         'created_at' => $tx->created_at->format('Y-m-d H:i:s'),
//     ];

//     return $request->expectsJson()
//         ? response()->json([
//             'success' => true,
//             'message' => 'Exchange completed successfully!',
//             'code' => 'EXCHANGE_SUCCESS',
//             'data' => [
//                 'from_currency' => $from,
//                 'to_currency' => $to,
//                 'amount' => $amount,
//                 'converted' => $converted,
//                 'reference' => $tx->reference,
//                 'method' => $tx->method,
//                 'type' => $tx->type,
//                 'balances' => $balances,
//                 'transaction' => $transaction,
//             ]
//         ], 200)
//         : back()->with('success', 'Exchange completed successfully!');
// }


//     protected function sendExchangeEmail(Request $request, $from, $to, $amount, $converted, $currentBalance, User $owner)
//     {
//         $data = [
//             'name' => $owner->business_name ?? $owner->name,
//             'amount_sent' => number_format((float) $amount, 2),
//             'recipient_amount' => number_format((float) $converted, 2),
//             'fee' => number_format(0, 2),
//             'total_amount' => number_format((float) $amount, 2),
//             'current_balance' => number_format((float) $currentBalance, 2),
//             'sending_currency' => $from,
//             'recipient_currency' => $to,
//             'reference' => 'Exchange',
//         ];

//         Mail::to($owner->email)->send(new TransactionSentMail($data));
//     }
    

public function exchangeSubmit(Request $request)
{

    $isApi = $request->expectsJson();
           $mode  = session('mode', 'live');

        // ── Block web-based deletion while in Test mode ──────────────────────
        if (!$isApi && $mode === 'test') {
            $message = 'Exchange Money in Test Mode is only available via the API.';
            return redirect()->back()->withErrors(['message' => $message]);
        }
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
            'type' => 'Swap',
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
    $this->sendExchangeNotification($owner, $from, $to, $amount, $converted);

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
        'type' => $tx->type,
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
                'type' => $tx->type,
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

    protected function sendExchangeNotification(User $owner, string $from, string $to, float $amount, float $converted): void
    {
        $title = 'Exchange Successful';
        $body  = "You swapped {$from} " . number_format($amount, 2) . " to {$to} " . number_format($converted, 2) . ".";

        // ── Push notification (device token required) ──────────────────────────
        if (!empty($owner->device_token)) {
            try {
                $firebase = app(FirebaseNotificationService::class);

                $firebase->sendToToken($owner->device_token, $title, $body, [
                    'type' => 'exchange',
                    'from_currency' => $from,
                    'to_currency' => $to,
                    'amount' => (string) $amount,
                    'converted' => (string) $converted,
                ]);

                Log::info('[Exchange] Push notification sent', ['owner_id' => $owner->id]);

            } catch (\Throwable $e) {
                Log::warning('[Exchange] Push notification failed', [
                    'owner_id' => $owner->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // ── In-app / DB notification (always sent, no device token needed) ─────
        try {
            $owner->notify(new GeneralNotification($title, $body));

            Log::info('[Exchange] GeneralNotification sent', ['owner_id' => $owner->id]);

        } catch (\Throwable $e) {
            Log::warning('[Exchange] GeneralNotification failed', [
                'owner_id' => $owner->id,
                'error' => $e->getMessage(),
            ]);
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






    // ── Currency Fee Lookup ─────────────────────────────────────────────────

public function getCurrencyFee(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated',
            'code'    => 'UNAUTHENTICATED',
            'data'    => null,
        ], 401);
    }

    $request->validate([
        'currency' => 'required|string|in:' . implode(',', \App\Models\UserCurrencyFee::CURRENCIES),
        'type'     => 'nullable|string|in:collection,payout',
        'amount'   => 'nullable|numeric|min:0',
    ]);

    $currency = strtoupper($request->currency);
    $type     = $request->type;
    $amount   = $request->filled('amount') ? (float) $request->amount : null;

    $userFee = \App\Models\UserCurrencyFee::where('user_id', $user->id)
        ->where('currency', $currency)
        ->first();

    Log::info('[CurrencyFee Lookup]', [
        'user_id'  => $user->id,
        'currency' => $currency,
        'type'     => $type,
        'amount'   => $amount,
        'found'    => (bool) $userFee,
    ]);

    if (!$userFee) {
        $msg = "No fee settings found for {$currency}. Contact your marketer.";
        return response()->json([
            'success' => false,
            'message' => $msg,
            'code'    => 'FEE_SETTINGS_NOT_FOUND',
            'data'    => null,
        ], 404);
    }

    $build = function (string $side) use ($userFee, $currency, $amount) {
        $enabled = (bool) $userFee->{"{$side}_enabled"};
        $min     = $userFee->{"{$side}_min"};
        $max     = $userFee->{"{$side}_max"};

        $out = [
            'enabled'      => $enabled,
            'min'          => $min,
            'max'          => $max,
            'fee_label'    => $this->describeFee($userFee, $side, $currency), // e.g. "2.5 CAD" or "1.5%"
        ];

        if ($amount !== null) {
            $calc = $side === 'collection'
                ? $userFee->calcCollectionFee($amount)
                : $userFee->calcPayoutFee($amount);

            $out['amount']     = $amount;
            $out['fee']        = $calc;
            $out['net_amount'] = round($amount - $calc, 2);
        }

        return $out;
    };

    $data = ['currency' => $currency];

    if ($type === 'collection') {
        $data['collection'] = $build('collection');
    } elseif ($type === 'payout') {
        $data['payout'] = $build('payout');
    } else {
        $data['collection'] = $build('collection');
        $data['payout']     = $build('payout');
    }

    return response()->json([
        'success' => true,
        'message' => 'Fee settings retrieved successfully.',
        'code'    => 'FEE_SETTINGS_FOUND',
        'data'    => $data,
    ], 200);
}

// ── Helper: human-readable fee, no percent/fixed leaked ─────────────────

private function describeFee(\App\Models\UserCurrencyFee $userFee, string $side, string $currency): string
{
    $percent = $userFee->{"{$side}_percent"};
    $fixed   = $userFee->{"{$side}_fixed"};

    if ($percent > 0 && $fixed > 0) {
        return "{$percent}% + " . number_format($fixed, 2) . " {$currency}";
    }
    if ($percent > 0) {
        return "{$percent}%";
    }
    if ($fixed > 0) {
        return number_format($fixed, 2) . " {$currency} flat";
    }
    return "No fee";
}


// ── Currency Fee Lookup (Payout only) ────────────────────────────────────

public function getPayoutFees(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated',
            'code'    => 'UNAUTHENTICATED',
            'data'    => null,
        ], 401);
    }

    $userFees = \App\Models\UserCurrencyFee::where('user_id', $user->id)->get();

    Log::info('[PayoutFee Lookup]', [
        'user_id' => $user->id,
        'count'   => $userFees->count(),
    ]);

    if ($userFees->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No fee settings found. Contact your marketer.',
            'code'    => 'FEE_SETTINGS_NOT_FOUND',
            'data'    => null,
        ], 404);
    }

    $data = $userFees->map(function ($userFee) {
        return [
            'currency'  => $userFee->currency,
            'enabled'   => (bool) $userFee->payout_enabled,
            'min'       => $userFee->payout_min,
            'max'       => $userFee->payout_max,
            'fee_label' => $this->describeFee($userFee, 'payout', $userFee->currency),
        ];
    })->values();

    return response()->json([
        'success' => true,
        'message' => 'Payout fee settings retrieved successfully.',
        'code'    => 'FEE_SETTINGS_FOUND',
        'data'    => $data,
    ], 200);
}


}
