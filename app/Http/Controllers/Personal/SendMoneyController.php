<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Mail\TransactionSentMail;
use App\Models\Balance;
use App\Models\Beneficia;
use App\Models\ExchangeRate;
use App\Models\TransactionHistory;
use App\Traits\CurrencyHelper;
use App\Traits\SelectsBalanceId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Services\PayazaService;
use App\Services\PivotService;
use App\Services\OrchardService;
use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\DB;


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
    
    public function index() 
    {
        $personalId = auth('personal-api')->id(); 
        $beneficiaries = Beneficia::where('personal_id', $personalId)->get();

        $balances = Balance::where('personal_id', $personalId)->get(); 
        foreach ($balances as $balance) {
            $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
        }
        $balanceList = $balances;

        return view('business.send', compact('beneficiaries', 'balanceList'));
    }

    

    
  public function getUserTotalBalance(Request $request)
{
    $personalId = auth('personal-api')->id();
    $total = Balance::where('personal_id', $personalId)->sum('amount');

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'User total balance fetched successfully',
            'code' => 'TOTAL_BALANCE_FETCHED',
            'data' => [
                'personal_id' => $personalId,
                'total_balance' => $total
            ]
        ], 200);
    }

    return null;
}




public function getExchangeRate(Request $request)
{
    $from = $request->input('from_currency');
    $to = $request->input('to_currency');
    $amount = $request->input('amount', 1);

    $result = $this->getExchangeRateFromMap($from, $to);

    if (!$result) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid currency',
            'code' => 'INVALID_CURRENCY',
            'data' => null
        ], 400);
    }

    $rate = $result['rate'];
    $transfer_fee = $result['transfer_fee'];

    $formatted = sprintf(
        "%s %.2f = %s %s",
        strtoupper($from),
        (float) $amount,
        strtoupper($to),
        $rate
    );

    return response()->json([
        'success' => true,
        'message' => 'Exchange rate fetched',
        'code' => 'EXCHANGE_RATE_FETCHED',
        'data' => [
            'exchange_rate' => $formatted,
            'transfer_fee' => $transfer_fee
        ]
    ], 200);
}




    // public function sendTransaction(Request $request)
    // {
    //     $personal = auth('personal-api')->user();
    //     if (!$personal) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unauthorized',
    //             'code' => 'UNAUTHORIZED',
    //             'data' => null
    //         ], 401);
    //     }

    //     $request->validate([
    //         'amount' => 'required|numeric|min:1',
    //         'recipient_id' => 'required|uuid',
    //         'balance_id' => 'required',
    //         'reference' => 'nullable|string',
    //         'transfer_fee' => 'nullable',
    //         'total_amount' => 'required|numeric',
    //         'exchange_rate' => 'required|string',
    //         'recipient_amount' => 'required|numeric',
    //         'account_number' => 'required|string',
    //         'account_name' => 'required|string',
    //         'bank' => 'nullable|string',
    //         'bank_code' => 'nullable|string',
    //     ]);

    //     $sendingCurrency = strtoupper(explode(' ', $request->exchange_rate)[1] ?? 'NGN');
    //     $currency = strtoupper(explode(' ', $request->exchange_rate)[4] ?? 'NGN');

    //     $balance = Balance::find($request->balance_id);
    //     if (!$balance) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid balance selected',
    //             'code' => 'INVALID_BALANCE',
    //             'data' => null
    //         ], 422);
    //     }

    //     if ($balance->amount < $request->total_amount) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Insufficient funds',
    //             'code' => 'INSUFFICIENT_FUNDS',
    //             'data' => null
    //         ], 422);
    //     }

    //     $pivotCurrencies = ['UGX'];
    //     $payazaCurrencies = ['NGN', 'TZS', 'XOF', 'XAF', 'ZAR', 'KES'];
    //     $appMobileCurrencies = ['GHS'];

    //       if (
    //         in_array($currency, $pivotCurrencies) &&
    //         filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN)
    //     ) {
    //         return $this->sendViaPivot($request, $currency,$sendingCurrency, $balance,$personal);
    //     }

    //     if (
    //         in_array($currency, $appMobileCurrencies) &&
    //         filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN)
    //     ) {
    //         return $this->sendViaAppMobile($request, $currency,$sendingCurrency, $balance ,$personal);
    //     }

    //     if (
    //         in_array($currency,$payazaCurrencies) &&
    //         filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN)
    //     ) {
    //         return $this->sendViaPayaza($request, $currency,$sendingCurrency, $balance ,$personal);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'No payment provider available for this currency',
    //         'code' => 'PROVIDER_NOT_AVAILABLE',
    //         'data' => null
    //     ], 422);
    // }



public function sendTransaction(Request $request)
{
    $personal = auth('personal-api')->user();
    if (!$personal) {
        return response()->json(['success'=>false,'message'=>'Unauthorized','code'=>'UNAUTHORIZED','data'=>null],401);
    }

    $request->validate([
        'amount' => 'required|numeric|min:1',
        'recipient_id' => 'required|uuid',
        'balance_id' => 'required',
        'reference' => 'nullable|string',
        'transfer_fee' => 'nullable',
        'total_amount' => 'required|numeric',
        'exchange_rate' => 'required|string',
        'recipient_amount' => 'required|numeric',
        'account_number' => 'required|string',
        'account_name' => 'required|string',
        'bank' => 'nullable|string',
        'bank_code' => 'nullable|string',
    ]);

    $sendingCurrency = strtoupper(explode(' ', $request->exchange_rate)[1] ?? 'NGN');
    $currency = strtoupper(explode(' ', $request->exchange_rate)[4] ?? 'NGN');


     // Currency limits (configured from admin on currencies table)
    $limit = \App\Models\Currency::where('code', $sendingCurrency)
        ->where('is_active', true)
        ->first();

    if ($limit) {
        if (!is_null($limit->min_amount) && $request->amount < $limit->min_amount) {
            return response()->json([
                'success' => false,
                'message' => "Minimum transfer for {$sendingCurrency} is {$limit->min_amount}",
                'code' => 'AMOUNT_BELOW_MINIMUM',
                'data' => null
            ], 422);
        }

        if (!is_null($limit->max_amount) && $request->amount > $limit->max_amount) {
            return response()->json([
                'success' => false,
                'message' => "Maximum transfer for {$sendingCurrency} is {$limit->max_amount}",
                'code' => 'AMOUNT_ABOVE_MAXIMUM',
                'data' => null
            ], 422);
        }
    }


    $balance = Balance::find($request->balance_id);
    if (!$balance) {
        return response()->json(['success'=>false,'message'=>'Invalid balance','code'=>'INVALID_BALANCE','data'=>null],422);
    }

    if ($balance->amount < $request->total_amount) {
        return response()->json(['success'=>false,'message'=>'Insufficient funds','code'=>'INSUFFICIENT_FUNDS','data'=>null],422);
    }

    DB::beginTransaction();
    try {
        // debit first
        $balance->amount -= $request->total_amount;
        $balance->save();

        // create ONE transaction record
        $tx = TransactionHistory::create([
            'amount' => $request->amount,
            'total_amount' => $request->total_amount,
            'currency' => $sendingCurrency,
            'balance_id' => $balance->id,
            'status' => 'pending',
            'method' => 'withdrawal',
            'payment_provider' => 'wallect', // will update later
            'reference' => 'ref-' . Str::uuid(),
            'personal_id' => $personal->id,
            'sender_id' => $personal->id,
            'sender' => $personal->name,
            'recipient_account_number' => $request->account_number,
            'recipient_account_name' => $request->account_name,
            'recipient_country' => strtoupper(substr($currency,0,2)),
            'recipient_bank_currency' => $currency,
            'to_currency' => $currency,
            'fees' => $request->transfer_fee,
            'exchange_rate' => strtoupper(explode(' ', $request->exchange_rate)[3] ?? null),
            'recipient_amount' => $request->recipient_amount,

        ]);

        if (in_array($currency, ['UGX']) && filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
            $response = $this->sendViaPivot($request, $currency, $sendingCurrency, $balance, $personal, $tx->id);
        } elseif (in_array($currency, ['GHS']) && filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN)) {
            $response = $this->sendViaAppMobile($request, $currency, $sendingCurrency, $balance, $personal, $tx->id);
        } elseif (in_array($currency, ['NGN','TZS','XOF','XAF','ZAR','KES']) && filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
            $response = $this->sendViaPayaza($request, $currency, $sendingCurrency, $balance, $personal, $tx->id);
        } else {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>'No provider','code'=>'PROVIDER_NOT_AVAILABLE','data'=>null],422);
        }

        DB::commit();
        return $response;

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success'=>false,'message'=>'Transaction failed','code'=>'TXN_FAILED','data'=>$e->getMessage()],500);
    }
}



// -------------------- Pivot Payment --------------------
protected function sendViaPivot(Request $request, $currency ,$sendingCurrency, $balance, $personal,$txId)
{
    $auth = $this->pivot->authenticate();
    if (isset($auth['error'])) {
        return response()->json([
            'success' => false,
            'message' => $auth['error'],
            'code' => 'FLOVIDE_AUTH_FAILED',
            'data' => $auth
        ], 500);
    }

    $token = $auth['tokenResponse']['accessToken'];
    $merchantTransactionId = 'TXN_' . substr(uniqid(), 0, 10);
    $bankType = strtolower($request->transfer_method ?? 'bank');

    $serviceCode = $bankType === 'mobile'
        ? env('PIVOT_UGX_MOBILE_SERVICE')
        : env('PIVOT_UGX_BANK_SERVICE');

    $sortCode = $request->bank_code;

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
        "extraData" => [
            "bankSortCode" => $sortCode,
            "amount" => $request->recipient_amount
        ]
    ];

    $payment = $this->pivot->postTransaction($token, $payload);

    if (($payment['statusCode'] ?? null) === '237') {
        // $balance->amount -= $request->total_amount;
        // $balance->save();

        // TransactionHistory::create([
        //     'amount' => $request->total_amount,
        //     'currency' => $sendingCurrency,
        //     'balance_id' => $balance->id,
        //     'order_id' => $payment['merchantTransactionId'] ?? 'N/A',
        //     'sender_id' => $personal->id,
        //     'sender' => $personal->name,
        //     'recipient_account_number' => $request->account_number,
        //     'recipient_account_name' => $request->account_name,
        //     'recipient_country' => $currency === 'UGX' ? 'UG' : 'KE',
        //     'status' => 'pending', 
        //     'method' => 'withdrawal',
        //     'payment_provider' => 'pivot',
        //     'reference' => 'ref-' . Str::uuid(),
        //     'personal_id' => $personal->id,
        // ]);

        TransactionHistory::where('id', $txId)->update([
            'status' => 'pending',
            'payment_provider' => 'pivot',
            'order_id' => $payment['merchantTransactionId'] ?? 'N/A'
            ]);

        return response()->json([
            'success' => true,
            'message' => 'flovide transaction successful',
            'code' => 'FLOVIDE_SUCCESS',
            'data' => $this->txData($txId)
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => $payment['statusDescription'] ?? 'flovide payment failed',
        'code' => 'FLOVIDE_FAILED',
        'data' =>  $this->txData($txId)
    ], 422);
}


// -------------------- Payaza Payment --------------------
protected function sendViaPayaza(Request $request, $currency, $sendingCurrency, $balance, $personal,$txId)
{
    $transactionReference = "TXN_" . time();
    $accountReference = $this->payaza->getAccountReference($currency);

    if (!$accountReference) {
        return response()->json([
            'success' => false,
            'message' => 'Unable to retrieve account reference from flovide',
            'code' => 'FLOVIDE_ACCOUNT_REF_FAILED',
            'data' => null
        ], 500);
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

    $payload = [
        "transaction_type" => $transactionTypes[$currency] ?? ($bankType === 'mobile' ? 'mobile_money' : 'nuban'),
        "service_payload" => [
            "payout_amount" => $request->recipient_amount,
            "transaction_pin" => env('PAYAZA_MERCHANT_PIN'),
            "account_reference" => $accountReference,
            "currency" => $currency,
            "country" => strtoupper(substr($currency,0,2)),
            "payout_beneficiaries" => [[
                "credit_amount" => $request->recipient_amount,
                "account_number" => $request->account_number,
                "account_name" => $request->account_name,
                "bank_code" => $request->bank_code ?? null,
                "narration" => $request->reference ?? "Payment",
                "transaction_reference" => $transactionReference,
                "sender" => [
                    "sender_name" => $personal->name,
                    "sender_id" => $personal->id,
                    "sender_phone_number" => $personal->phone ?? null
                ]
            ]]
        ]
    ];

    $response = $this->payaza->initiatePayout($payload);

    if (($response['statusCode'] ?? null) === '200' || ($response['success'] ?? false)) {
        // $balance->amount -= $request->total_amount;
        // $balance->save();
        $this->sendTransactionEmail($request, $balance, $transactionReference ?? null);

         TransactionHistory::where('id', $txId)->update([
            'status' => 'pending',
            'payment_provider' => 'payaza',
            'order_id' => $transactionReference
            ]);

        return response()->json([
            'success' => true,
            'message' => 'flovide transaction successful',
            'code' => 'FLOVIDE_SUCCESS',
            'data' => $this->txData($txId)
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => $response['statusDescription'] ?? 'flovide transaction failed',
        'code' => 'FLOVIDE_FAILED',
        'data' =>  $this->txData($txId)
    ], 422);
}


// -------------------- AppMobile Payment --------------------
protected function sendViaAppMobile(Request $request, $currency,$sendingCurrency, $balance, $personal,$txId)
{
    $exttrid = uniqid('APPM_');
    $bankCode = $request->bank_code;
    $network = in_array($bankCode, ["MTN","VOD","AIR","VIS","MAS"]) ? $bankCode : "BNK";

    $payload = [
        "customer_number" => $request->account_number,
        "amount" => number_format($request->recipient_amount, 2, '.', ''),
        "exttrid" => $exttrid,
        "reference" => $request->reference ?? "Wallet Payment",
        "nw" => $network,
        "bank_code" => $bankCode,
        "trans_type" => "MTC",
        "callback_url" => route('transactionHistory'),
        "service_id" => env('ORCHARD_SERVICE_ID'),
        "ts" => now()->utc()->format('Y-m-d H:i:s')
    ];

    $response = $this->orchard->sendPayment($payload);

    if (($response['status'] ?? null) === 'SUCCESS' || ($response['success'] ?? false)) {
        // $balance->amount -= $request->total_amount;
        // $balance->save();

        // TransactionHistory::create([
        //     'amount' => $request->total_amount,
        //     'currency' => $sendingCurrency,
        //     'balance_id' => $balance->id,
        //     'order_id' => $exttrid,
        //     'sender_id' => $personal->id,
        //     'sender' => $personal->name,
        //     'recipient_account_number' => $request->account_number,
        //     'recipient_account_name' => $request->account_name,
        //     'recipient_country' => 'GH',
        //     'status' => 'pending', 
        //     'method' => 'withdrawal',
        //     'payment_provider' => 'appmobile',
        //     'reference' => 'ref-' . Str::uuid(),
        //     'personal_id' => $personal->id,
        // ]);
            TransactionHistory::where('id', $txId)->update([
                'status' => 'pending',
                'payment_provider' => 'appmobile'
            ]);

        return response()->json([
            'success' => true,
            'message' => 'AppMobile transaction successful',
            'code' => 'APPMOBILE_SUCCESS',
            'data' =>  $this->txData($txId)
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => $response['message'] ?? 'AppMobile transaction failed',
        'code' => 'APPMOBILE_FAILED',
        'data' =>  $this->txData($txId)
    ], 422);
}


    protected function sendTransactionEmail(Request $request, $balance, $reference = null)
    {
     $user = auth('personal-api')->user();

        $rateParts = preg_split('/\s+/', trim((string) $request->exchange_rate));
        $sendingCurrency = strtoupper($rateParts[1] ?? 'NGN');
        $recipientCurrency = strtoupper($rateParts[4] ?? 'NGN');

        $data = [
            'name' => $user->business_name ?? $user->name,
            'amount_sent' => number_format((float) $request->amount, 2),
            'recipient_amount' => number_format((float) $request->recipient_amount, 2),
            'fee' => number_format((float) ($request->transfer_fee ?? 0), 2),
            'total_amount' => number_format((float) $request->total_amount, 2),
            'current_balance' => number_format((float) $balance->amount, 2),
            'sending_currency' => $sendingCurrency,
            'recipient_currency' => $recipientCurrency,
            'reference' => $reference ?? ($request->reference ?? 'N/A'),
        ];

        Mail::to($user->email)->send(new TransactionSentMail($data));
    }


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


public function exchangeSubmit(Request $request)
{
    $request->validate([
        'from_currency' => 'required|string',
        'to_currency' => 'required|string',
        'amount' => 'required|numeric|min:1',
    ]);

    $user = auth('personal-api')->user();

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

    $fromBalance = Balance::where('personal_id', $user->id)->where('currency', $from)->first();
    $toBalance = Balance::where('personal_id', $user->id)->where('currency', $to)->first();

    if (!$fromBalance || !$toBalance) {
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

    if (!$rate) {
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

    $fromBalance->amount -= $amount;
    $fromBalance->save();

    $toBalance->amount += $converted;
    $toBalance->save();

    $tx = TransactionHistory::create([
        'amount' => $amount,
        'currency' => $from,
        'balance_id' => $fromBalance->id,
        'status' => 'success',
        'method' => 'exchange',
        'reference' => 'ref-' . Str::uuid(),
        'personal_id' => $user->id,
        'recipient_country' => strtoupper(substr($to, 0, 2)),
        'sender' => $user->business_name ?? $user->name,
        'recipient_account_number' => $from,
        'recipient_account_name' => $user->business_name ?? $user->name,
        'to_currency' => $to,
        'recipient_amount' => $converted,
        'exchange_rate' => $rate->rate,
    ]);

    $this->sendExchangeEmail($user, $from, $to, $amount, $converted, $fromBalance->amount);

    $balances = Balance::where('personal_id', $user->id)
        ->get()
        ->map(function ($balance) {
            return [
                'id' => $balance->id,
                'currency' => $balance->currency,
                'amount' => $balance->amount,
            ];
        })
        ->values();

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
protected function sendExchangeEmail($user, $from, $to, $amount, $converted, $currentBalance)
{
    $data = [
        'name' => $user->name ?? 'Customer',
        'amount_sent' => number_format((float) $amount, 2),
        'recipient_amount' => number_format((float) $converted, 2),
        'fee' => number_format((float) 0, 2),
        'total_amount' => number_format((float) $amount, 2),
        'current_balance' => number_format((float) $currentBalance, 2),
        'sending_currency' => $from,
        'recipient_currency' => $to,
        'reference' => 'Exchange',
    ];

    Mail::to($user->email)->send(new TransactionSentMail($data));
}




}
