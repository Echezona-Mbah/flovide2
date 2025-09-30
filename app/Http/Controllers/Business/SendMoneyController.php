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

class SendMoneyController extends Controller
{
    use CurrencyHelper;
    use SelectsBalanceId;
    
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
        ]);


        $currency = explode(' ', $request->exchange_rate)[0] ?? 'NGN';
        $balanceId = $this->getBalanceIdByCurrency($currency);
        // dd($currency);
        $isApi = $request->expectsJson();
        $user = auth()->user();

        if (!$balanceId) {
            return $isApi
                ? response()->json(['error' => 'Unsupported currency'], 422)
                : back()->withErrors(['currency' => 'Unsupported currency']);
        }

        $balance = Balance::where('id', $request->balance_id)->first();
        if (!$balance) {
            return $isApi
                ? response()->json(['error' => 'Invalid balance selected'], 422)
                : back()->withErrors(['balance' => 'Invalid balance selected']);
        }

        if ($balance->amount < $request->total_amount) {
            return $isApi
                ? response()->json(['error' => 'Insufficient funds'], 422)
                : back()->withErrors(['amount' => 'Insufficient funds']);
        }

        $orderId = (string) Str::uuid();
        $reference = 'ref-' . Str::uuid();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OHENTPAY_API_KEY'),
                'Accept' => 'application/json',
            ])->post(env('OHENTPAY_BASE_URL') . '/transactions', [
                'transaction_type' => 'payment',
                'amount' => $request->recipient_amount,
                'balance_id' => $balanceId,
                'recipient_id' => $request->recipient_id,
                'order_id' => $orderId,
                'reference' => $reference,
            ]);
        } catch (\Exception $e) {
            return $isApi
                ? response()->json(['message' => 'API error', 'error' => $e->getMessage()], 500)
                : back()->with('error', 'API connection failed: ' . $e->getMessage());
        }

        if ($response->successful()) {
            $data = $response->json();

            // Deduct funds AFTER success
            $balance->amount -= $request->total_amount;
            $balance->save();

            // Log transaction
            TransactionHistory::create([
                'amount' => $request->total_amount,
                'fees' => $request->transfer_fee ?? 0,
                'currency' => $data['currency'] ?? $currency,
                'to_currency' => $data['to_currency'] ?? null,
                'balance_id' => $balanceId,
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
                ? response()->json(['message' => 'Transaction successful', 'data' => $data])
                : redirect()->route('transactionHistory')->with('success', 'Transaction sent successfully!');
        }

        // Handle failed transaction
        $error = $response->json()['message'] ?? 'Unknown error';
        return $isApi
            ? response()->json(['error' => 'Transaction failed', 'details' => $error], 422)
            : back()->with('error', 'Transaction failed: ' . $error);
    }


    
    
    
    

}
