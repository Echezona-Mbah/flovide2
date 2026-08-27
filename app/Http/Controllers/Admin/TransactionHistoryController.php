<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use App\Services\PivotService;
use App\Services\PayazaService;
use App\Services\OrchardService;
use Illuminate\Support\Facades\Mail;
use App\Mail\RefundProcessedMail;

class TransactionHistoryController extends Controller
{
     public function index(Request $request)
    {
        $allowedPerPage = [25, 50, 100, 250, 500];
        $perPage = (int) $request->input('per_page', 25);

        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 25;
        }

        $lastTransactions = TransactionHistory::orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->query());

        return view('admin.transactionhistory', compact('lastTransactions', 'perPage'));
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

    if ($tx->status !== 'pending') {
        return back()->withErrors([
            'error' => 'Only pending transactions can be processed.',
        ]);
    }

    $currency = strtoupper((string) $tx->recipient_bank_currency);
    $transferMethod = strtolower((string) ($tx->transfer_method ?? 'bank'));
    $providerAmount = (int) round((float) $tx->recipient_amount, 0);

    try {
        /*
         * Pivot - UGX
         */
        if (in_array($currency, ['UGX'], true)) {
            $auth = $pivot->authenticate();

            if (isset($auth['error'])) {
                return back()->withErrors([
                    'error' => $auth['error'],
                ]);
            }

            $token = $auth['tokenResponse']['accessToken'];
            $merchantTransactionId = $tx->order_id ?: 'TXN_' . substr(uniqid(), 0, 10);

            $serviceCode = $transferMethod === 'mobile'
                ? env('PIVOT_UGX_MOBILE_SERVICE')
                : env('PIVOT_UGX_BANK_SERVICE');

            $sortCode = $transferMethod === 'mobile'
                ? env('PIVOT_UGX_MOBILE_SORT', '000000')
                : ($tx->bank_code ?? '000000');

            $payload = [
                'serviceCode' => $serviceCode,
                'msisdn' => $transferMethod === 'mobile'
                    ? $tx->recipient_account_number
                    : '256755289333',
                'accountNumber' => $tx->recipient_account_number,
                'merchantTransactionId' => $merchantTransactionId,
                'amount' => $providerAmount,
                'chargeAmount' => (int) round((float) ($tx->fees ?? 0), 0),
                'narration' => $tx->reference ?? 'Payment',
                'currencyCode' => $currency,
                'countryCode' => 'UG',
                'customerName' => $tx->recipient_account_name ?? 'N/A',
                'extraData' => [
                    'bankSortCode' => $sortCode,
                ],
            ];

            if ($transferMethod !== 'mobile') {
                $payload['extraData']['amount'] = $providerAmount;
            }

            logger('Admin Pivot REQUEST', $payload);

            $payment = $pivot->postTransaction($token, $payload);

            logger('Admin Pivot RESPONSE', $payment);

            if (($payment['statusCode'] ?? null) === '237') {
                $tx->status = 'pending';
                $tx->payment_provider = 'pivot';
                $tx->order_id = $payment['merchantTransactionId'] ?? $merchantTransactionId;
                $tx->save();

                return back()->with('success', 'Pivot transaction submitted successfully. Awaiting confirmation.');
            }

            $tx->payment_provider = 'pivot';
            $tx->failure_reason = $payment['statusDescription'] ?? 'Pivot failed';
            $tx->save();

            return back()->withErrors([
                'error' => $payment['statusDescription'] ?? 'Pivot failed',
            ]);
        }

        /*
         * AppMobile / Orchard - GHS
         */
        if (in_array($currency, ['GHS'], true)) {
            $exttrid = $tx->order_id ?: uniqid('APPM_');

            $network = 'BNK';

            if (in_array(strtoupper((string) $tx->bank_code), ['MTN', 'VOD', 'AIR', 'VIS', 'MAS'], true)) {
                $network = strtoupper((string) $tx->bank_code);
            }

            $payload = [
                'customer_number' => $tx->recipient_account_number,
                'amount' => (string) $providerAmount,
                'exttrid' => $exttrid,
                'reference' => $tx->reference ?? 'Wallet Payment',
                'nw' => $transferMethod === 'mobile' ? $network : 'BNK',
                'bank_code' => $tx->bank_code ?? 'BNK',
                'trans_type' => 'MTC',
                'callback_url' => route('transactionHistory'),
                'service_id' => env('ORCHARD_SERVICE_ID'),
                'ts' => now()->utc()->format('Y-m-d H:i:s'),
            ];

            logger('Admin AppMobile REQUEST', $payload);

            $response = $orchard->sendPayment($payload);

            logger('Admin AppMobile RESPONSE', $response);

            if (($response['status'] ?? null) === 'SUCCESS' || ($response['success'] ?? false)) {
                $tx->status = 'pending';
                $tx->payment_provider = 'appmobile';
                $tx->order_id = $exttrid;
                $tx->save();

                return back()->with('success', 'AppMobile transaction submitted successfully. Awaiting confirmation.');
            }

            $tx->payment_provider = 'appmobile';
            $tx->failure_reason = $response['message'] ?? 'AppMobile failed';
            $tx->save();

            return back()->withErrors([
                'error' => $response['message'] ?? 'AppMobile failed',
            ]);
        }

        /*
         * Payaza
         */
        if (in_array($currency, ['NGN', 'TZS', 'XOF', 'XAF', 'ZAR', 'KES'], true)) {
            $transactionReference = $tx->order_id ?: 'TXN_' . time();

            $accountReference = $payaza->getAccountReference($currency);

            if (! $accountReference) {
                return back()->withErrors([
                    'error' => 'Unable to retrieve Payaza account reference',
                ]);
            }

            $transactionTypes = [
                'NGN' => 'nuban',
                'TZS' => $transferMethod === 'mobile' ? 'mobile_money' : 'tiss',
                'KES' => $transferMethod === 'mobile' ? 'mobile_money' : 'kepss',
                'XOF' => $transferMethod === 'mobile' ? 'mobile_money' : 'wave',
                'XAF' => 'mobile_money',
                'ZAR' => 'RTC',
            ];

            $countryCodes = [
                'NGN' => 'NG',
                'TZS' => 'TZ',
                'KES' => 'KE',
                'XOF' => 'SN',
                'XAF' => 'CM',
                'ZAR' => 'ZA',
            ];

            $transactionType = $transactionTypes[$currency] ?? 'nuban';
            $countryCode = $countryCodes[$currency] ?? strtoupper(substr($currency, 0, 2));

            $payload = [
                'transaction_type' => $transactionType,
                'service_payload' => [
                    'payout_amount' => $providerAmount,
                    'transaction_pin' => env('PAYAZA_MERCHANT_PIN'),
                    'account_reference' => $accountReference,
                    'currency' => $currency,
                    'country' => $countryCode,
                    'payout_beneficiaries' => [
                        [
                            'credit_amount' => $providerAmount,
                            'account_number' => $tx->recipient_account_number,
                            'account_name' => $tx->recipient_account_name ?? 'N/A',
                            'bank_code' => $tx->bank_code ?? null,
                            'narration' => $tx->reference ?? 'Payment',
                            'transaction_reference' => $transactionReference,
                            'sender' => [
                                'sender_name' => $tx->sender ?? 'Admin',
                                'sender_id' => $tx->sender_id ?? 'admin',
                            ],
                        ],
                    ],
                ],
            ];

            logger('Admin Payaza REQUEST', $payload);

            $response = $payaza->initiatePayout($payload);

            logger('Admin Payaza RESPONSE', $response);

            if (($response['statusCode'] ?? null) === '200' || ($response['success'] ?? false)) {
                $tx->status = 'pending';
                $tx->payment_provider = 'payaza';
                $tx->order_id = $transactionReference;
                $tx->save();

                return back()->with('success', 'Payaza transaction submitted successfully. Awaiting confirmation.');
            }

            $tx->payment_provider = 'payaza';
            $tx->failure_reason = $response['statusDescription']
                ?? $response['message']
                ?? 'Payaza failed';
            $tx->save();

            return back()->withErrors([
                'error' => $response['statusDescription']
                    ?? $response['message']
                    ?? 'Payaza failed',
            ]);
        }

        return back()->withErrors([
            'error' => 'No provider for this currency.',
        ]);
    } catch (\Exception $e) {
        logger('Admin process transaction failed', [
            'transaction_id' => $tx->id,
            'error' => $e->getMessage(),
        ]);

        return back()->withErrors([
            'error' => $e->getMessage(),
        ]);
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

    $refundAmount = (float) ($tx->total_amount ?? $tx->amount ?? 0);

    $balance->amount += $refundAmount;
    $balance->save();

    $tx->status = 'refunded';
    $tx->payment_provider = 'refunded';
    $tx->save();

    // send refund mail
    try {
        $email = null;

        if (!empty($tx->user_id) && class_exists(\App\Models\User::class)) {
            $user = \App\Models\User::find($tx->user_id);
            $email = $user->email ?? null;
        }

        if (!$email && !empty($tx->personal_id) && class_exists(\App\Models\Personal::class)) {
            $personal = \App\Models\Personal::find($tx->personal_id);
            $email = $personal->email ?? null;
        }

        if ($email) {
            Mail::to($email)->send(new RefundProcessedMail($tx, $refundAmount, $balance->amount));
        }
    } catch (\Throwable $e) {
        \Log::warning('Refund mail failed', [
            'transaction_id' => $tx->id,
            'error' => $e->getMessage(),
        ]);
    }

    return back()->with('success', 'Refund successful.');
}



// public function refund($id)
// {
//     $tx = TransactionHistory::findOrFail($id);

//     if ($tx->status !== 'pending') {
//         return back()->withErrors(['error' => 'Only pending transactions can be refunded.']);
//     }

//     $balance = Balance::find($tx->balance_id);
//     if (!$balance) {
//         return back()->withErrors(['error' => 'Balance not found.']);
//     }

//     $balance->amount += $tx->total_amount;
//     $balance->save();

//     $tx->status = 'refunded';
//     $tx->payment_provider = 'refunded';
//     $tx->save();

//     return back()->with('success', 'Refund successful.');
// }


}
