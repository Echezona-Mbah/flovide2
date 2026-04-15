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

    

  

//    public function sendTransaction(Request $request)
//     {
//         $request->validate([
//             'amount' => 'required|numeric|min:1',
//             'recipient_id' => 'required|uuid',
//             'balance_id' => 'required',
//             'reference' => 'nullable|string',
//             'transfer_fee' => 'nullable',
//             'total_amount' => 'required|numeric',
//             'exchange_rate' => 'required|string',
//             'recipient_amount' => 'required|numeric',
//             'account_number' => 'required|string',
//             'account_name' => 'required|string',
//             'bank' => 'nullable|string',
//             'bank_code' => 'nullable|string',
//         ]);

//         //dd($request->all());

//         $sendingCurrency = strtoupper(explode(' ', $request->exchange_rate)[1] ?? 'NGN');
//         // dd($currency);
//         $currency = strtoupper(explode(' ', $request->exchange_rate)[4] ?? 'NGN');
//         // $rateParts = preg_split('/\s+/', trim((string) $request->exchange_rate));
//         // $sendingCurrency = strtoupper($rateParts[1]);
//         // $currency = strtoupper($rateParts[4]);
//         // dd($sendingCurrency);


//         $balance = Balance::find($request->balance_id);
//         if (!$balance) {
//             return $request->expectsJson()
//                 ? response()->json([
//                     'success' => false,
//                     'message' => 'Invalid balance selected',
//                     'code' => 'INVALID_BALANCE',
//                     'data' => null
//                 ], 422)
//                 : back()->withErrors(['balance' => 'Invalid balance selected']);
//         }

//         if ($balance->amount < $request->total_amount) {
//             return $request->expectsJson()
//                 ? response()->json([
//                     'success' => false,
//                     'message' => 'Insufficient funds',
//                     'code' => 'INSUFFICIENT_FUNDS',
//                     'data' => null
//                 ], 422)
//                 : back()->withErrors(['amount' => 'Insufficient funds']);
//         }

//         $pivotCurrencies = ['UGX'];
//         $payazaCurrencies = ['NGN', 'TZS', 'XOF', 'XAF', 'ZAR', 'KES'];
//         $appMobileCurrencies = ['GHS'];

//         if (
//             in_array($currency, $pivotCurrencies) &&
//             filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN)
//         ) {
//             return $this->sendViaPivot($request, $currency,$sendingCurrency, $balance);
//         }

//         if (
//             in_array($currency, $appMobileCurrencies) &&
//             filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN)
//         ) {
//             return $this->sendViaAppMobile($request, $currency,$sendingCurrency, $balance);
//         }

//         if (
//             in_array($currency,$payazaCurrencies) &&
//             filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN)
//         ) {
//             return $this->sendViaPayaza($request, $currency,$sendingCurrency, $balance);
//         }

//         return $request->expectsJson()
//             ? response()->json([
//                 'success' => false,
//                 'message' => 'No payment provider available for this currency',
//                 'code' => 'PROVIDER_NOT_AVAILABLE',
//                 'data' => null
//             ], 422)
//             : back()->with('error', 'No payment provider available for this currency');
//     }

public function sendTransaction(Request $request)
{
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

    //dd($request->all());

    $sendingCurrency = strtoupper(explode(' ', $request->exchange_rate)[1] ?? 'NGN');
    $currency = strtoupper(explode(' ', $request->exchange_rate)[4] ?? 'NGN');

    $balance = Balance::find($request->balance_id);
    if (!$balance) {
        return response()->json(['success'=>false,'message'=>'Invalid balance','code'=>'INVALID_BALANCE','data'=>null],422);
    }

    if ($balance->amount < $request->total_amount) {
        return response()->json(['success'=>false,'message'=>'Insufficient funds','code'=>'INSUFFICIENT_FUNDS','data'=>null],422);
    }

    DB::beginTransaction();
    try {
        // Debit
        $balance->amount -= $request->total_amount;
        $balance->save();

        // Create ONE transaction record
        $tx = TransactionHistory::create([
            'amount' => $request->amount,
            'total_amount' => $request->total_amount,
            'currency' => $sendingCurrency,
            'balance_id' => $balance->id,
            'status' => 'pending',
            'method' => 'withdrawal',
            'payment_provider' => 'wallect', // will update later
            'reference' => 'ref-' . Str::uuid(),
            'user_id' => auth()->id(),
            'sender_id' => auth()->id(),
            'sender' => auth()->user()->business_name ?? auth()->user()->name,
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
            $response = $this->sendViaPivot($request, $currency, $sendingCurrency, $balance, $tx->id);
        } elseif (in_array($currency, ['GHS']) && filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN)) {
            $response = $this->sendViaAppMobile($request, $currency, $sendingCurrency, $balance, $tx->id);
        } elseif (in_array($currency, ['NGN','TZS','XOF','XAF','ZAR','KES']) && filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
            $response = $this->sendViaPayaza($request, $currency, $sendingCurrency, $balance, $tx->id);
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
    protected function sendViaPivot(Request $request, $currency,$sendingCurrency, $balance,$txId)
    {
        $isApi = $request->expectsJson();
        $user = auth()->user();

        $auth = $this->pivot->authenticate();
        if (isset($auth['error'])) {
            return $isApi
                ? response()->json([
                    'success' => false,
                    'message' => $auth['error'],
                    'code' => 'PIVOT_AUTH_FAILED',
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

            // TransactionHistory::create([
            //     'amount' => $request->total_amount,
            //     'currency' => $sendingCurrency,
            //     'balance_id' => $balance->id,
            //     'order_id' => $payment['merchantTransactionId'] ?? 'N/A',
            //     'sender_id' => auth()->id(),
            //     'sender' => $user->business_name,
            //     'recipient_account_number' => $request->account_number,
            //     'recipient_account_name' => $request->account_name,
            //     'recipient_country' => $currency === 'UGX' ? 'UG' : 'KE',
            //     'status' => 'pending', 
            //     'method' => 'withdrawal',
            //     'payment_provider' => 'pivot',
            //     'reference' => 'ref-' . Str::uuid(),
            //     'user_id' => auth()->id(),
            // ]);

            TransactionHistory::where('id', $txId)->update([
            'status' => 'pending',
            'payment_provider' => 'pivot',
            'order_id' => $payment['merchantTransactionId'] ?? 'N/A'
            ]);

            return $isApi
                ? response()->json([
                    'success' => true,
                    'message' => 'Pivot transaction successful',
                    'code' => 'PIVOT_SUCCESS',
                    'data' => $payment
                ], 200)
                : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully via Pivot!');
        }

        $errorMessage = $payment['statusDescription'] ?? 'Pivot payment failed';

        return $isApi
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'PIVOT_FAILED',
                'data' => $payment
            ], 422)
            : back()->with('error', $errorMessage);
    }



    protected function sendViaPayaza(Request $request, $currency,$sendingCurrency, $balance,$txId)
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
                    'code' => 'PAYAZA_ACCOUNT_REF_FAILED',
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
                            "sender_name" => auth()->user()->business_name ?? auth()->user()->name,
                            "sender_id" => auth()->id(),
                            "sender_phone_number" => auth()->user()->business_phone ?? null,
                            "sender_address" => auth()->user()->street_address ?? null
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
            $this->sendTransactionEmail($request, $balance, $transactionReference ?? null);


            // TransactionHistory::create([
            //     'amount' => $request->total_amount,
            //     'currency' => $sendingCurrency,
            //     'balance_id' => $balance->id,
            //     'order_id' => $transactionReference,
            //     'sender_id' => auth()->id(),
            //     'sender' => auth()->user()->business_name ?? auth()->user()->name,
            //     'recipient_account_number' => $request->account_number,
            //     'recipient_account_name' => $request->account_name,
            //     'recipient_country' => strtoupper(substr($currency,0,2)),
            //     'status' => 'pending', 
            //     'method' => 'withdrawal',
            //     'payment_provider' => 'payaza',
            //     'reference' => 'ref-' . Str::uuid(),
            //     'user_id' => auth()->id(),
            // ]);

            TransactionHistory::where('id', $txId)->update([
            'status' => 'pending',
            'payment_provider' => 'payaza',
            'order_id' => $transactionReference
            ]);


            return $isApi
                ? response()->json([
                    'success' => true,
                    'message' => 'Payaza transaction successful',
                    'code' => 'PAYAZA_SUCCESS',
                    'data' => $response
                ], 200)
                : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully via Payaza!');
        }

        $errorMessage = $response['statusDescription'] ?? 'Payaza transaction failed';

        return $isApi
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'PAYAZA_FAILED',
                'data' => $response
            ], 422)
            : back()->with('error', $errorMessage);
    }



   protected function sendViaAppMobile(Request $request, $currency,$sendingCurrency, $balance,$txId)
    {
        $isApi = $request->expectsJson();
        $user  = auth()->user();

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
            $this->sendTransactionEmail($request, $balance, $exttrid ?? null);

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
                    'message' => 'AppMobile transaction successful',
                    'code' => 'APPMOBILE_SUCCESS',
                    'data' => $response
                ], 200)
                : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully via AppMobile!');
        }

        $errorMessage = $response['message'] ?? 'AppMobile transaction failed';

        return $isApi
            ? response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'APPMOBILE_FAILED',
                'data' => $response
            ], 422)
            : back()->with('error', $errorMessage);
    }


    // -------------------- IBANQ Payment --------------------
    protected function sendViaIbanq(Request $request, $currency, $balance)
    {
        $isApi = $request->expectsJson();
        $user = auth()->user();
        $orderId = (string) Str::uuid();
        $reference = 'ref-' . Str::uuid();

        // Prepare IBANQ payload
        $ibanqPayload = [
            'beneficiaryAccountId' => $request->account_id ?? null,
            'amount' => $request->recipient_amount,
            'currency' => $currency,
            'reference' => $reference,
        ];

        // Call IBANQ Payment Controller (assuming you have a service/controller)
        $ibanqResponse = app()->make('App\Http\Controllers\Ibanq\IbanqPaymentController')
            ->createPayment($ibanqPayload);

        if (!isset($ibanqResponse['success']) || !$ibanqResponse['success']) {
            $errorMessage = $ibanqResponse['error'] ?? 'Unknown error from IBANQ';
            return $isApi
                ? response()->json(['error' => 'IBANQ Payment failed', 'details' => $errorMessage], 422)
                : back()->with('error', 'IBANQ Payment failed: ' . $errorMessage);
        }

        $data = $ibanqResponse['data'] ?? [];

        // Deduct balance
        $balance->amount -= $request->total_amount;
        $balance->save();

        // Log transaction
        TransactionHistory::create([
            'amount' => $request->total_amount,
            'fees' => $request->transfer_fee ?? 0,
            'currency' => $data['currency'] ?? $currency,
            'balance_id' => $balance->id,
            'virtual_account_id' => $data['virtual_account_id'] ?? null,
            'order_id' => $data['order_id'] ?? $orderId,
            'payment_reference' => $data['payment_reference'] ?? null,
            'status' => $data['status'] ?? 'unknown',
            'failure_reason' => $data['failure_reason'] ?? null,
            'transaction_type' => $data['transaction_type'] ?? 'payment',
            'payment_method' => $data['payment_method'] ?? null,
            'sender_id' => $user->id,
            'sender' => $user->business_name ?? null,
            'recipient_id' => $data['recipient']['id'] ?? null,
            'recipient_country' => $data['recipient']['country'] ?? null,
            'recipient_account_name' => $data['recipient']['bank_account']['account_name'] ?? null,
            'recipient_bank_name' => $data['recipient']['bank_account']['bank_name'] ?? null,
            'recipient_account_number' => $data['recipient']['bank_account']['account_number'] ?? null,
            'exchange_rate' => $data['exchange_rate']['rate'] ?? null,
            'single_rate' => $data['exchange_rate']['single_rate'] ?? null,
            'reference' => $data['reference'] ?? $reference,
            'user_id' => $user->id,
            'method' => $isApi ? 'api' : 'web',
        ]);

        return $isApi
            ? response()->json(['message' => 'IBANQ transaction successful', 'data' => $data])
            : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully via IBANQ!');
    }

    protected function sendTransactionEmail(Request $request, $balance, $reference = null)
    {
        $user = auth()->user();

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



    public function runPayazaCheck(Request $request)
{
    if ($request->query('key') !== env('CRON_SECRET')) {
        return response()->json(['ok' => false], 403);
    }

    (new \App\Jobs\CheckPayazaTransactions)->handle();
    (new \App\Jobs\CheckOrchardTransactions)->handle();


    return response()->json(['ok' => true]);
}




    
 public function index() 
{
    $user = auth()->user();

    $beneficiaries = Beneficia::where('user_id', $user->id)->get();

    $balances = Balance::where('user_id', $user->id)->get();
    foreach ($balances as $balance) {
        $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
    }

    $balanceList = $balances;

    // ✅ add currencies list
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


    


    


    


        public function indexexc() 
    {
        $user = auth()->user();
        $beneficiaries = Beneficia::where('user_id', $user->id)->get();

        $balances = Balance::where('user_id', $user->id)->get(); 
        foreach ($balances as $balance) {
            $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
        }
        $balanceList = $balances;
        return view('business.exchange_rate', compact('beneficiaries', 'balances'));
    }



public function exchangeSubmit(Request $request)
{
    //  dd($request->all());

    $request->validate([
        'from_currency' => 'required|string',
        'to_currency' => 'required|string',
        'amount' => 'required|numeric|min:1',
    ]);

    // dd($request->all());

    $user = auth()->user();

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

    $fromBalance = Balance::where('user_id', $user->id)->where('currency', $from)->first();
    $toBalance = Balance::where('user_id', $user->id)->where('currency', $to)->first();

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

    // Update balances
    $fromBalance->amount -= $amount;
    $fromBalance->save();

    $toBalance->amount += $converted;
    $toBalance->save();

    // Log transaction
    $tx = TransactionHistory::create([
        'amount' => $amount,
        'currency' => $from,
        'balance_id' => $fromBalance->id,
        'status' => 'success',
        'method' => 'exchange',
        'reference' => 'ref-' . Str::uuid(),
        'user_id' => $user->id,
        'recipient_country' => strtoupper(substr($to,0,2)),
        'sender_id' => auth()->id(),
        'sender' => auth()->user()->business_name ?? auth()->user()->name,
        'recipient_account_number' => $from,
        'recipient_account_name' => auth()->user()->business_name ?? auth()->user()->name,
    ]);

    $this->sendExchangeEmail($request, $from, $to, $amount, $converted, $fromBalance->amount);


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
                'reference' => $tx->reference
            ]
        ], 200)
        : back()->with('success', 'Exchange completed successfully!');
}


protected function sendExchangeEmail(Request $request, $from, $to, $amount, $converted, $currentBalance)
{
    $user = auth()->user();

    $data = [
        'name' => $user->business_name ?? $user->name,
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
