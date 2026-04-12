<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Beneficia;
use App\Models\TransactionHistory;
use App\Traits\CurrencyHelper;
use App\Traits\SelectsBalanceId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
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







    //   public function sendTransaction(Request $request)
    // {
    //     $request->validate([
    //         'amount' => 'required|numeric|min:1',
    //         'recipient_id' => 'required|uuid',
    //         'balance_id' => 'required',
    //         'transfer_fee' => 'nullable',
    //         'total_amount' => 'required|numeric',
    //         'exchange_rate' => 'required|string',
    //         'recipient_amount' => 'required|numeric',
    //     ]);


    //     $currency = explode(' ', $request->exchange_rate)[0] ?? 'NGN';
    //     $balanceId = $this->getBalanceIdByCurrency($currency);
    //     $isApi = $request->expectsJson();
    //     $personalId = auth('personal-api')->id(); 

    //     if (!$balanceId) {
    //         return $isApi
    //             ? response()->json([
    //                 'data' =>[
    //                 'errors' => 'Unsupported currency'
    //             ]], 422)
    //             : back()->withErrors(['currency' => 'Unsupported currency']);
    //     }

    //     $balance = Balance::where('id', $request->balance_id)->first();
    //     if (!$balance) {
    //         return $isApi
    //             ? response()->json([
    //                 'data' =>[
    //                 'errors' => 'Invalid balance selected'
    //             ]], 422)
    //             : back()->withErrors(['balance' => 'Invalid balance selected']);
    //     }

    //     if ($balance->amount < $request->total_amount) {
    //         return $isApi
    //             ? response()->json([
    //                   'data' =>[
    //                 'errors' => 'Insufficient funds'
    //             ]], 422)
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
    //             ? response()->json([
    //                   'data' =>[
    //                 // 'message' => 'API error', 
    //                 'errors' => $e->getMessage()
    //             ]], 500)
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
    //             'sender_id' => $personalId,
    //             'sender' => $user->business_name ?? null,
    //             'recipient_id' => $data['recipient']['id'] ?? null,
    //             'recipient_country' => $data['recipient']['country'] ?? null,
    //             'recipient_account_name' => $data['recipient']['bank_account']['account_name'] ?? null,
    //             'recipient_bank_name' => $data['recipient']['bank_account']['bank_name'] ?? null,
    //             'recipient_account_number' => $data['recipient']['bank_account']['account_number'] ?? null,
    //             'exchange_rate' => $data['exchange_rate']['rate'] ?? null,
    //             'single_rate' => $data['exchange_rate']['single_rate'] ?? null,
    //             'reference' => $data['reference'] ?? $reference,
    //             'personal_id' => $personalId,
    //             'method' => $isApi ? 'api' : 'web',
    //         ]);

    //         return $isApi
    //             ? response()->json([
    //                   'data' =>[
    //                 'message' => 'Transaction successful',
    //                  'data' => $data
    //                  ]])
    //             : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully!');
    //     }

    //     // Handle failed transaction
    //     $error = $response->json()['message'] ?? 'Unknown error';
    //     return $isApi
    //         ? response()->json([
    //               'data' =>[
    //             'errors' => 'Transaction failed', 
    //             'details' => $error
    //         ]], 422)
    //         : back()->with('error', 'Transaction failed: ' . $error);
    // }


    public function sendTransaction(Request $request)
    {
        $personal = auth('personal-api')->user();
        if (!$personal) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'code' => 'UNAUTHORIZED',
                'data' => null
            ], 401);
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

        $balance = Balance::find($request->balance_id);
        if (!$balance) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid balance selected',
                'code' => 'INVALID_BALANCE',
                'data' => null
            ], 422);
        }

        if ($balance->amount < $request->total_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient funds',
                'code' => 'INSUFFICIENT_FUNDS',
                'data' => null
            ], 422);
        }

        $pivotCurrencies = ['UGX'];
        $payazaCurrencies = ['NGN', 'TZS', 'XOF', 'XAF', 'ZAR', 'KES'];
        $appMobileCurrencies = ['GHS'];

        if (in_array($sendingCurrency, $pivotCurrencies) && filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
            return $this->sendViaPivot($request, $sendingCurrency, $balance, $personal);
        }

        if (in_array($sendingCurrency, $appMobileCurrencies) && filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN)) {
            return $this->sendViaAppMobile($request, $sendingCurrency, $balance, $personal);
        }

        if (in_array($sendingCurrency, $payazaCurrencies) && filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN)) {
            return $this->sendViaPayaza($request, $sendingCurrency, $balance, $personal);
        }

        return response()->json([
            'success' => false,
            'message' => 'No payment provider available for this currency',
            'code' => 'PROVIDER_NOT_AVAILABLE',
            'data' => null
        ], 422);
    }

// -------------------- Pivot Payment --------------------
protected function sendViaPivot(Request $request, $currency, $balance, $personal)
{
    $auth = $this->pivot->authenticate();
    if (isset($auth['error'])) {
        return response()->json([
            'success' => false,
            'message' => $auth['error'],
            'code' => 'PIVOT_AUTH_FAILED',
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
        $balance->amount -= $request->total_amount;
        $balance->save();

        TransactionHistory::create([
            'amount' => $request->total_amount,
            'currency' => $currency,
            'balance_id' => $balance->id,
            'order_id' => $payment['merchantTransactionId'] ?? 'N/A',
            'sender_id' => $personal->id,
            'sender' => $personal->name,
            'recipient_account_number' => $request->account_number,
            'recipient_account_name' => $request->account_name,
            'recipient_country' => $currency === 'UGX' ? 'UG' : 'KE',
            'status' => 'success',
            'method' => 'api',
            'reference' => 'ref-' . Str::uuid(),
            'personal_id' => $personal->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pivot transaction successful',
            'code' => 'PIVOT_SUCCESS',
            'data' => $payment
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => $payment['statusDescription'] ?? 'Pivot payment failed',
        'code' => 'PIVOT_FAILED',
        'data' => $payment
    ], 422);
}


// -------------------- Payaza Payment --------------------
protected function sendViaPayaza(Request $request, $currency, $balance, $personal)
{
    $transactionReference = "TXN_" . time();
    $accountReference = $this->payaza->getAccountReference($currency);

    if (!$accountReference) {
        return response()->json([
            'success' => false,
            'message' => 'Unable to retrieve account reference from Payaza',
            'code' => 'PAYAZA_ACCOUNT_REF_FAILED',
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
        $balance->amount -= $request->total_amount;
        $balance->save();

        TransactionHistory::create([
            'amount' => $request->total_amount,
            'currency' => $currency,
            'balance_id' => $balance->id,
            'order_id' => $transactionReference,
            'sender_id' => $personal->id,
            'sender' => $personal->name,
            'recipient_account_number' => $request->account_number,
            'recipient_account_name' => $request->account_name,
            'recipient_country' => strtoupper(substr($currency,0,2)),
            'status' => 'success',
            'method' => 'api',
            'reference' => 'ref-' . Str::uuid(),
            'personal_id' => $personal->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payaza transaction successful',
            'code' => 'PAYAZA_SUCCESS',
            'data' => $response
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => $response['statusDescription'] ?? 'Payaza transaction failed',
        'code' => 'PAYAZA_FAILED',
        'data' => $response
    ], 422);
}


// -------------------- AppMobile Payment --------------------
protected function sendViaAppMobile(Request $request, $currency, $balance, $personal)
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
        $balance->amount -= $request->total_amount;
        $balance->save();

        TransactionHistory::create([
            'amount' => $request->total_amount,
            'currency' => $currency,
            'balance_id' => $balance->id,
            'order_id' => $exttrid,
            'sender_id' => $personal->id,
            'sender' => $personal->name,
            'recipient_account_number' => $request->account_number,
            'recipient_account_name' => $request->account_name,
            'recipient_country' => 'GH',
            'status' => 'success',
            'method' => 'api',
            'reference' => 'ref-' . Str::uuid(),
            'personal_id' => $personal->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'AppMobile transaction successful',
            'code' => 'APPMOBILE_SUCCESS',
            'data' => $response
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => $response['message'] ?? 'AppMobile transaction failed',
        'code' => 'APPMOBILE_FAILED',
        'data' => $response
    ], 422);
}


}
