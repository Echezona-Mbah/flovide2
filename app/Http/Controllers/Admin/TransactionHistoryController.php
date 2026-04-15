<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use App\Services\PivotService;
use App\Services\PayazaService;
use App\Services\OrchardService;

class TransactionHistoryController extends Controller
{
        public function index(Request $request)
    {

        $lastTransactions = TransactionHistory::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.transactionhistory', compact('lastTransactions'));

    }

    public function destroy($id)
    {
        $transaction = TransactionHistory::findOrFail($id);
        // dd($transaction);
        $transaction->delete();

        return redirect()->back()->with('success', 'Transaction deleted successfully.');
    }



public function process($id, PivotService $pivot, PayazaService $payaza, OrchardService $orchard)
{
    $tx = TransactionHistory::findOrFail($id);
    // dd($tx);

    if ($tx->status !== 'pending') {
        return back()->withErrors(['error' => 'Only pending transactions can be processed.']);
    }

    $currency = strtoupper($tx->recipient_bank_currency);

    //dd($currency);

    try {
        // ✅ Pivot
        if (in_array($currency, ['UGX','KES'])) {
            $auth = $pivot->authenticate();
            if (isset($auth['error'])) {
                return back()->withErrors(['error' => $auth['error']]);
            }

            $token = $auth['tokenResponse']['accessToken'];
            $merchantTransactionId = 'TXN_' . substr(uniqid(), 0, 10);

            $payload = [
                "serviceCode" => env('PIVOT_UGX_BANK_SERVICE'),
                "msisdn" => $tx->recipient_account_number ?? '256755289333',
                "accountNumber" => $tx->recipient_account_number,
                "merchantTransactionId" => $merchantTransactionId,
                "amount" => $tx->recipient_amount,
                "chargeAmount" => 0,
                "narration" => $tx->reference ?? "Payment",
                "currencyCode" => $currency,
                "countryCode" => $currency === 'UGX' ? 'UG' : 'KE',
                "customerName" => $tx->recipient_account_name ?? 'N/A',
                "extraData" => [
                    "bankSortCode" => $tx->bank_code ?? '000000'
                ]
            ];

            $payment = $pivot->postTransaction($token, $payload);

            if (($payment['statusCode'] ?? null) === '237') {
                $tx->status = 'success';
                $tx->payment_provider = 'pivot';
                $tx->order_id = $merchantTransactionId;
                $tx->save();
                return back()->with('success', 'Pivot transaction successful.');
            }

            $tx->status = 'pending';
            $tx->payment_provider = 'pivot';
            $tx->save();
            return back()->withErrors(['error' => $payment['statusDescription'] ?? 'Pivot failed']);
        }

        // ✅ AppMobile
        if (in_array($currency, ['GHS'])) {
            $exttrid = uniqid('APPM_');

            $payload = [
                "customer_number" => $tx->recipient_account_number,
                "amount" => number_format($tx->recipient_amount, 2, '.', ''),
                "exttrid" => $exttrid,
                "reference" => $tx->reference ?? "Wallet Payment",
                "nw" => "BNK",
                "bank_code" => $tx->bank_code ?? "BNK",
                "trans_type" => "MTC",
                "callback_url" => route('transactionHistory'),
                "service_id" => env('ORCHARD_SERVICE_ID'),
                "ts" => now()->utc()->format('Y-m-d H:i:s')
            ];

            $response = $orchard->sendPayment($payload);

            if (($response['status'] ?? null) === 'SUCCESS' || ($response['success'] ?? false)) {
                $tx->status = 'success';
                $tx->payment_provider = 'appmobile';
                $tx->order_id = $exttrid;
                $tx->save();
                return back()->with('success', 'AppMobile transaction successful.');
            }

            $tx->status = 'pending';
            $tx->payment_provider = 'appmobile';
            $tx->save();
            return back()->withErrors(['error' => $response['message'] ?? 'AppMobile failed']);
        }

        // ✅ Payaza
        if (in_array($currency, ['NGN','TZS','XOF','XAF','ZAR','KES'])) {
            $transactionReference = "TXN_" . time();
            $accountReference = $payaza->getAccountReference($currency);

            if (!$accountReference) {
                return back()->withErrors(['error' => 'Unable to retrieve Payaza account reference']);
            }

            $payload = [
                "transaction_type" => "nuban",
                "service_payload" => [
                    "payout_amount" => $tx->recipient_amount,
                    "transaction_pin" => env('PAYAZA_MERCHANT_PIN'),
                    "account_reference" => $accountReference,
                    "currency" => $currency,
                    "country" => strtoupper(substr($currency, 0, 2)),
                    "payout_beneficiaries" => [[
                        "credit_amount" => $tx->recipient_amount,
                        "account_number" => $tx->recipient_account_number,
                        "account_name" => $tx->recipient_account_name ?? 'N/A',
                        "bank_code" => $tx->bank_code ?? null,
                        "narration" => $tx->reference ?? "Payment",
                        "transaction_reference" => $transactionReference,
                        "sender" => [
                            "sender_name" => $tx->sender ?? 'Admin',
                            "sender_id" => $tx->sender_id ?? 'admin'
                        ]
                    ]]
                ]
            ];

            $response = $payaza->initiatePayout($payload);

            if (($response['statusCode'] ?? null) === '200' || ($response['success'] ?? false)) {
                $tx->status = 'success';
                $tx->payment_provider = 'payaza';
                $tx->order_id = $transactionReference;
                $tx->save();
                return back()->with('success', 'Payaza transaction successful.');
            }

            $tx->status = 'pending';
            $tx->payment_provider = 'payaza';
            $tx->save();
            return back()->withErrors(['error' => $response['statusDescription'] ?? 'Payaza failed']);
        }

        return back()->withErrors(['error' => 'No provider for this currency.']);
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}


public function refund($id)
{
    $tx = TransactionHistory::findOrFail($id);

    if ($tx->status !== 'pending') {
        return back()->withErrors(['error' => 'Only pending transactions can be refunded.']);
    }

    $balance = Balance::find($tx->balance_id);
    if (!$balance) {
        return back()->withErrors(['error' => 'Balance not found.']);
    }

    $balance->amount += $tx->total_amount;
    $balance->save();

    $tx->status = 'refunded';
    $tx->payment_provider = 'refunded';
    $tx->save();

    return back()->with('success', 'Refund successful.');
}


}
