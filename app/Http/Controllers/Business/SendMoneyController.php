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
use App\Services\PayazaService;
use App\Services\PivotService;
use App\Services\OrchardService;




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

    

    //  public function sendTransaction(Request $request)
    // {
    //     // -------------------- Validate --------------------
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
    //     ]);

    //     // -------------------- Extract currencies --------------------
    //     $currency = explode(' ', $request->exchange_rate)[0] ?? 'NGN';
    //     $sendingCurrency = explode(' ', $request->exchange_rate)[3] ?? 'NGN';

    //     $balance = Balance::find($request->balance_id);
    //     if (!$balance) {
    //         return $request->expectsJson()
    //             ? response()->json(['error' => 'Invalid balance selected'], 422)
    //             : back()->withErrors(['balance' => 'Invalid balance selected']);
    //     }

    //     if ($balance->amount < $request->total_amount) {
    //         return $request->expectsJson()
    //             ? response()->json(['error' => 'Insufficient funds'], 422)
    //             : back()->withErrors(['amount' => 'Insufficient funds']);
    //     }

    //     // -------------------- Choose provider --------------------
    //     if (in_array($sendingCurrency, ['UGX', 'KES'])) {
    //         // Use Pivot for UGX or KES
    //         return $this->sendViaPivot($request, $sendingCurrency, $balance);
    //     } else {
    //         // Use IBANQ for other currencies
    //         return $this->sendViaIbanq($request, $sendingCurrency, $balance);
    //     }
    // }

    public function sendTransaction(Request $request)
    {
        // -------------------- Validate --------------------
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
            'bank' => 'nullable|string', // 'bank' or 'mobile'
            'bank_code' => 'nullable|string',
        ]);

        // dd($request->all());
        // -------------------- Extract currencies --------------------
        $currency = strtoupper(explode(' ', $request->exchange_rate)[0] ?? 'NGN');
        $sendingCurrency = strtoupper(explode(' ', $request->exchange_rate)[3] ?? 'NGN');

        // -------------------- Determine service code --------------------

        $bankType = strtolower($request->bank ?? 'bank'); // 'mobile' or 'bank'

        // Read service codes from .env
        $pivotUGXBankService   = env('PIVOT_UGX_BANK_SERVICE');
        $pivotUGXMobileService = env('PIVOT_UGX_MOBILE_SERVICE');
        $pivotKESBankService   = env('PIVOT_KES_BANK_SERVICE', 'KES_BANK_CODE');
        $pivotKESMobileService = env('PIVOT_KES_MOBILE_SERVICE', 'KES_MOBILE_CODE');

        // Choose service code dynamically
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

        // Build payload
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

        // Add extraData only for bank transfer
        $payload['extraData'] = [
            "bankSortCode" => $sortCode
        ];

        // Only include amount in extraData for bank transfers
        if ($bankType !== 'mobile') {
            $payload['extraData']['amount'] = $request->recipient_amount;
        }
        // dd($payload);

        logger('Pivot REQUEST', $payload);
        $payment = $this->pivot->postTransaction($token, $payload);
        logger('Pivot RESPONSE', $payment);

        if (isset($payment['statusCode']) && $payment['statusCode'] === '237') {
            // Success
            $balance->amount -= $request->total_amount;
            $balance->save();

            TransactionHistory::create([
                'amount' => $request->total_amount,
                'currency' => $currency,
                'balance_id' => $balance->id,
                'order_id' => $payment['merchantTransactionId'] ?? 'N/A',
                'sender_id' => auth()->id(),
                'sender' => $user->business_name,
                'recipient_account_number' => $request->account_number,
                'recipient_account_name' => $request->account_name,
                'recipient_country' => $currency === 'UGX' ? 'UG' : 'KE',
                'status' => 'success',
                'method' => $isApi ? 'api' : 'web',
                'reference' => 'ref-' . Str::uuid(),
                'user_id' => auth()->id(),
            ]);

        // -------------------- Choose provider --------------------
        $pivotCurrencies = ['UGX']; // Pivot supported currencies
        $payazaCurrencies = ['NGN', 'TZS', 'XOF', 'XAF', 'ZAR', 'KES']; // Payaza supported currencies
        $appMobileCurrencies = ['GHS'];

       if (
            in_array($sendingCurrency, $pivotCurrencies) &&
            filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN)
        ) {
            return $this->sendViaPivot($request, $sendingCurrency, $balance);
        }

        if (
            in_array($sendingCurrency, $appMobileCurrencies) &&
            filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN)
        ) {
            return $this->sendViaAppMobile($request, $sendingCurrency, $balance); 
        }

        if (
            in_array($sendingCurrency, $payazaCurrencies) &&
            filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN)
        ) {
            return $this->sendViaPayaza($request, $sendingCurrency, $balance);
        }
        return $request->expectsJson()
            ? response()->json(['error' => 'No payment provider available for this currency'], 422)
            : back()->with('error', 'No payment provider available for this currency');
    }

    // -------------------- Pivot Payment --------------------
   protected function sendViaPivot(Request $request, $currency, $balance)
    {
        $isApi = $request->expectsJson();
        $user = auth()->user();

        // Authenticate Pivot
        $auth = $this->pivot->authenticate();
        if (isset($auth['error'])) {
            return $isApi
                ? response()->json(['error' => $auth['error']], 500)
                : back()->withErrors(['error' => $auth['error']]);
        }

        $token = $auth['tokenResponse']['accessToken'];
        $merchantTransactionId = 'TXN_' . substr(uniqid(), 0, 10);

        // -------------------- Determine service code --------------------

        $bankType = strtolower($request->transfer_method ?? 'bank'); // 'mobile' or 'bank'

        // Read service codes from .env
        $pivotUGXBankService   = env('PIVOT_UGX_BANK_SERVICE');
        $pivotUGXMobileService = env('PIVOT_UGX_MOBILE_SERVICE');
        $pivotKESBankService   = env('PIVOT_KES_BANK_SERVICE', 'KES_BANK_CODE');
        $pivotKESMobileService = env('PIVOT_KES_MOBILE_SERVICE', 'KES_MOBILE_CODE');

        // Choose service code dynamically
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

        // Build payload
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

        // Add extraData only for bank transfer
        $payload['extraData'] = [
            "bankSortCode" => $sortCode
        ];

        // Only include amount in extraData for bank transfers
        if ($bankType !== 'mobile') {
            $payload['extraData']['amount'] = $request->recipient_amount;
        }
        // dd($payload);

        logger('Pivot REQUEST', $payload);
        $payment = $this->pivot->postTransaction($token, $payload);
        logger('Pivot RESPONSE', $payment);

        if (isset($payment['statusCode']) && $payment['statusCode'] === '237') {
            // Success
            $balance->amount -= $request->total_amount;
            $balance->save();

            TransactionHistory::create([
                'amount' => $request->total_amount,
                'currency' => $currency,
                'balance_id' => $balance->id,
                'order_id' => $payment['merchantTransactionId'] ?? 'N/A',
                'sender_id' => auth()->id(),
                'sender' => $user->business_name,
                'recipient_account_number' => $request->account_number,
                'recipient_account_name' => $request->account_name,
                'recipient_country' => $currency === 'UGX' ? 'UG' : 'KE',
                'status' => 'success',
                'method' => $isApi ? 'api' : 'web',
                'reference' => 'ref-' . Str::uuid(),
                'user_id' => auth()->id(),
            ]);

            return $isApi
                ? response()->json(['message' => 'Pivot transaction successful', 'data' => $payment])
                : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully via Pivot!');
        } else {
            $errorMessage = $payment['statusDescription'] ?? 'Pivot payment failed';
            return $isApi
                ? response()->json(['error' => $errorMessage, 'details' => $payment], 422)
                : back()->with('error', $errorMessage);
        }
    }


    protected function sendViaPayaza(Request $request, $currency, $balance)
    {

        $isApi = $request->expectsJson();
        $transactionReference = "TXN_" . time();

        // Payaza account reference
        $accountReference = $this->payaza->getAccountReference($currency);
        // dd($accountReference);

        if (!$accountReference) {
            return $isApi
                ? response()->json(['error' => 'Unable to retrieve account reference from Payaza'], 500)
                : back()->with('error', 'Unable to retrieve account reference from Payaza');
        }

        // Determine type: bank or mobile
        $bankType = strtolower($request->transfer_method ?? 'bank'); // 'bank' or 'mobile'



        // ------------------- Transaction type mapping -------------------
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

        // ------------------- Build payload -------------------
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
            //   dd($payload);

        logger('Payaza REQUEST', $payload);

        $response = $this->payaza->initiatePayout($payload);
        logger('Payaza RESPONSE', $response);

        if (($response['statusCode'] ?? null) === '200' || ($response['success'] ?? false)) {
            // Deduct balance
            $balance->amount -= $request->total_amount;
            $balance->save();

            TransactionHistory::create([
                'amount' => $request->total_amount,
                'currency' => $currency,
                'balance_id' => $balance->id,
                'order_id' => $transactionReference,
                'sender_id' => auth()->id(),
                'sender' => auth()->user()->business_name ?? auth()->user()->name,
                'recipient_account_number' => $request->account_number,
                'recipient_account_name' => $request->account_name,
                'recipient_country' => strtoupper(substr($currency,0,2)),
                'status' => 'success',
                'method' => $isApi ? 'api' : 'web',
                'reference' => 'ref-' . Str::uuid(),
                'user_id' => auth()->id(),
            ]);

            return $isApi
                ? response()->json(['message' => 'Payaza transaction successful', 'data' => $response])
                : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully via Payaza!');
        }

        $errorMessage = $response['statusDescription'] ?? 'Payaza transaction failed';
        return $isApi
            ? response()->json(['error' => $errorMessage, 'details' => $response], 422)
            : back()->with('error', $errorMessage);
    }


    protected function sendViaAppMobile(Request $request, $currency, $balance)
    {

        $isApi = $request->expectsJson();
        $user  = auth()->user();

        $exttrid = uniqid('APPM_');

        $bankCode = $request->bank_code;

        // Default to bank
        $network = "BNK";

        // If it's mobile money, the bank_code will usually be MTN, VOD, AIR etc
        if (in_array($bankCode, ["MTN", "VOD", "AIR", "VIS", "MAS"])) {
            $network = $bankCode;
        }

        $payload = [
            "customer_number" => $request->account_number,
            "amount"          => number_format($request->recipient_amount, 2, '.', ''),
            "exttrid"         => $exttrid,
            "reference"       => $request->reference ?? "Wallet Payment",
            "nw"              => $network, // ✅ Dynamic now
            "bank_code"       => $bankCode,
            "trans_type"      => "MTC",
            "callback_url"    => route('transactionHistory'),
            "service_id"      => env('ORCHARD_SERVICE_ID'),
            "ts"              => now()->utc()->format('Y-m-d H:i:s'),
        ];

        logger('AppMobile REQUEST', $payload);

        // dd($payload);


        $response = $this->orchard->sendPayment($payload);

        logger('AppMobile RESPONSE', $response);

        if (($response['status'] ?? null) === 'SUCCESS' || ($response['success'] ?? false)) {

            // Deduct balance
            $balance->amount -= $request->total_amount;
            $balance->save();

            TransactionHistory::create([
                'amount' => $request->total_amount,
                'currency' => $currency,
                'balance_id' => $balance->id,
                'order_id' => $exttrid,
                'sender_id' => auth()->id(),
                'sender' => $user->business_name ?? $user->name,
                'recipient_account_number' => $request->account_number,
                'recipient_account_name' => $request->account_name,
                'recipient_country' => 'GH',
                'status' => 'success',
                'method' => $isApi ? 'api' : 'web',
                'reference' => 'ref-' . Str::uuid(),
                'user_id' => auth()->id(),
            ]);

            return $isApi
                ? response()->json(['message' => 'AppMobile transaction successful', 'data' => $response])
                : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully via AppMobile!');
        }

        $errorMessage = $response['message'] ?? 'AppMobile transaction failed';

        return $isApi
            ? response()->json(['error' => $errorMessage, 'details' => $response], 422)
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

// public function sendTransaction(Request $request)
// {
//     // Validate the request
//     $request->validate([
//         'amount' => 'required|numeric|min:1',
//         'recipient_id' => 'required|uuid',
//         'balance_id' => 'required',
//         'reference' => 'nullable|string',
//         'transfer_fee' => 'nullable',
//         'total_amount' => 'required|numeric',
//         'exchange_rate' => 'required|string',
//         'recipient_amount' => 'required|numeric',
//     ]);

//     // Extract currencies
//     $currency = explode(' ', $request->exchange_rate)[0] ?? 'NGN';
//     $sendingCurrency = explode(' ', $request->exchange_rate)[3] ?? 'NGN';
//     $balanceId = $this->getBalanceIdByCurrency($currency);

//     $isApi = $request->expectsJson();
//     $user = auth()->user();

//     // Check balance exists
//     $balance = Balance::where('id', $request->balance_id)->first();
//     if (!$balance) {
//         return $isApi
//             ? response()->json(['error' => 'Invalid balance selected'], 422)
//             : back()->withErrors(['balance' => 'Invalid balance selected']);
//     }

//     // Check sufficient funds
//     if ($balance->amount < $request->total_amount) {
//         return $isApi
//             ? response()->json(['error' => 'Insufficient funds'], 422)
//             : back()->withErrors(['amount' => 'Insufficient funds']);
//     }

//     $orderId = (string) Str::uuid();
//     $reference = 'ref-' . Str::uuid();
//     dd($request->all());

//     // Prepare IBANQ payload
//     $ibanqPayload = [
//         'beneficiaryAccountId' => $request->account_id,
//         'amount' => $request->recipient_amount,
//         'currency' => $sendingCurrency,
//         'reference' => $reference,
//     ];



//     // Handle IBANQ errors with detailed messages
//     if (!isset($ibanqResponse['success']) || !$ibanqResponse['success']) {
//         $errorMessage = $ibanqResponse['error'] ?? 'Unknown error from IBANQ';
//         return $isApi
//             ? response()->json(['error' => 'IBANQ Payment failed', 'details' => $errorMessage], 422)
//             : back()->with('error', 'IBANQ Payment failed: ' . $errorMessage);
//     }


//     $data = $ibanqResponse['data'] ?? [];

//     // Deduct funds AFTER successful IBANQ payment
//     $balance->amount -= $request->total_amount;
//     $balance->save();

//     // Log transaction
//     TransactionHistory::create([
//         'amount' => $request->total_amount,
//         'fees' => $request->transfer_fee ?? 0,
//         'currency' => $data['currency'] ?? $currency,
//         'to_currency' => $data['to_currency'] ?? null,
//         'balance_id' => $balanceId,
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

//     // Return success
//     return $isApi
//         ? response()->json(['message' => 'Transaction successful', 'data' => $data])
//         : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully!');
// }





    
    public function index() 
    {
        $user = auth()->user();
        $beneficiaries = Beneficia::where('user_id', $user->id)->get();

        $balances = Balance::where('user_id', $user->id)->get(); 
        foreach ($balances as $balance) {
            $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
        }
        $balanceList = $balances;

        return view('business.send', compact('beneficiaries', 'balanceList'));
    }

    

    
      public function getUserTotalBalance(Request $request)
    {
        $user = auth()->user();

        $total = Balance::where('user_id', $user->id)->sum('amount');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User total balance fetched successfully',
                'success' => true,
                'user_id' => $user->id,
                'total_balance' => $total,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 200);
        }

        return view('business.user_balance_total', [
            'total' => $total
        ]);
    }



    public function getExchangeRate(Request $request)
    {
        $from = $request->get('from_currency');
        $to = $request->get('to_currency');
        $amount = $request->get('amount');

        $result = $this->getExchangeRateFromMap($from, $to);

        if (!$result) {
            return response()->json(['error' => 'Invalid currency'], 400);
        }

        $rate = $result['rate'];
        $fee = $result['transfer_fee'];
        $converted = round($amount * $rate, 2);

        return response()->json([
            'rate' => $rate,
            'converted_amount' => $converted,
            'transfer_fee' => $fee,
        ]);
    }

    
    public function getExchangeRates(Request $request)
    {
        $from = $request->input('from_currency');
        $to = $request->input('to_currency');
        $amount = $request->input('amount', 1);

        $result = $this->getExchangeRateFromMap($from, $to);

        if (!$result) {
            return response()->json([
                'data' => [
                    'errors' => 'Invalid currency'
                ]
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
            'exchange_rate' => $formatted,
            'transfer_fee' => $transfer_fee
        ]);
    }






    //   public function sendTransaction(Request $request)
    // {
    //     //dd($request->all());
    //     $request->validate([
    //         'amount' => 'required|numeric|min:1',
    //         'account_id' => 'required|uuid',
    //         'balance_id' => 'required',
    //         'reference' => 'nullable|string',
    //         'transfer_fee' => 'nullable',
    //         'total_amount' => 'required|numeric',
    //         'exchange_rate' => 'required|string',
    //         'recipient_amount' => 'required|numeric',
    //     ]);


    //     $currency = explode(' ', $request->exchange_rate)[0] ?? 'NGN';
    //     $sendingcurrency = explode(' ', $request->exchange_rate)[3] ?? 'NGN';
    //     $balanceId = $this->getBalanceIdByCurrency($currency);

    //     $isApi = $request->expectsJson();
    //     $user = auth()->user();

    //     if (!$balanceId) {
    //         return $isApi
    //             ? response()->json(['error' => 'Unsupported currency'], 422)
    //             : back()->withErrors(['currency' => 'Unsupported currency']);
    //     }

    //     $balance = Balance::where('id', $request->balance_id)->first();
    //     if (!$balance) {
    //         return $isApi
    //             ? response()->json(['error' => 'Invalid balance selected'], 422)
    //             : back()->withErrors(['balance' => 'Invalid balance selected']);
    //     }

    //     if ($balance->amount < $request->total_amount) {
    //         return $isApi
    //             ? response()->json(['error' => 'Insufficient funds'], 422)
    //             : back()->withErrors(['amount' => 'Insufficient funds']);
    //     }

    //     $orderId = (string) Str::uuid();
    //     $reference = 'ref-' . Str::uuid();

    //     try {
    //         $response = Http::withHeaders([
    //             'Authorization' => 'Bearer ' . env('OHENTPAY_API_KEY'),
    //             'Accept' => 'application/json',
    //         ])->post(env('OHENTPAY_BASE_URL') . '/transactions', [
    //             'transaction_type' => 'payment',
    //             'amount' => $request->recipient_amount,
    //             'balance_id' => $balanceId,
    //             'recipient_id' => $request->recipient_id,
    //             'order_id' => $orderId,
    //             'reference' => $reference,
    //         ]);
    //     } catch (\Exception $e) {
    //         return $isApi
    //             ? response()->json(['message' => 'API error', 'error' => $e->getMessage()], 500)
    //             : back()->with('error', 'API connection failed: ' . $e->getMessage());
    //     }

    //     if ($response->successful()) {
    //         $data = $response->json();

    //         // Deduct funds AFTER success
    //         $balance->amount -= $request->total_amount;
    //         $balance->save();

    //         // Log transaction
    //         TransactionHistory::create([
    //             'amount' => $request->total_amount,
    //             'fees' => $request->transfer_fee ?? 0,
    //             'currency' => $data['currency'] ?? $currency,
    //             'to_currency' => $data['to_currency'] ?? null,
    //             'balance_id' => $balanceId,
    //             'virtual_account_id' => $data['virtual_account_id'] ?? null,
    //             'order_id' => $data['order_id'] ?? $orderId,
    //             'payment_reference' => $data['payment_reference'] ?? null,
    //             'status' => $data['status'] ?? 'unknown',
    //             'failure_reason' => $data['failure_reason'] ?? null,
    //             'transaction_type' => $data['transaction_type'] ?? 'payment',
    //             'payment_method' => $data['payment_method'] ?? null,
    //             'sender_id' => $user->id,
    //             'sender' => $user->business_name ?? null,
    //             'recipient_id' => $data['recipient']['id'] ?? null,
    //             'recipient_country' => $data['recipient']['country'] ?? null,
    //             'recipient_account_name' => $data['recipient']['bank_account']['account_name'] ?? null,
    //             'recipient_bank_name' => $data['recipient']['bank_account']['bank_name'] ?? null,
    //             'recipient_account_number' => $data['recipient']['bank_account']['account_number'] ?? null,
    //             'exchange_rate' => $data['exchange_rate']['rate'] ?? null,
    //             'single_rate' => $data['exchange_rate']['single_rate'] ?? null,
    //             'reference' => $data['reference'] ?? $reference,
    //             'user_id' => $user->id,
    //             'method' => $isApi ? 'api' : 'web',
    //         ]);

    //         return $isApi
    //             ? response()->json(['message' => 'Transaction successful', 'data' => $data])
    //             : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully!');
    //     }

    //     // Handle failed transaction
    //     $error = $response->json()['message'] ?? 'Unknown error';
    //     return $isApi
    //         ? response()->json(['error' => 'Transaction failed', 'details' => $error], 422)
    //         : back()->with('error', 'Transaction failed: ' . $error);
    // }


    
    
    
    

}
