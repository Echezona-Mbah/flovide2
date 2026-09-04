<?php

namespace App\Http\Controllers\Fidelity;

use App\Http\Controllers\Controller;
use App\Mail\TransactionSentMail;
use App\Models\Balance;
use App\Models\Personal;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Notifications\GeneralNotification;

class FidelityWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('[FidelityWebhook] Incoming webhook', $payload);

        $transactionId          = $payload['transactionId'] ?? null;
        $merchantAccountNumber  = $payload['receiverAccountNumber'] ?? $payload['merchantAccountNumber'] ?? null;
        $merchantAccountName    = $payload['receiverAccountName']   ?? $payload['merchantAccountName']  ?? null;
        $transactionAmount      = $payload['transactionAmount'] ?? null;
        $feeAmount              = $payload['feeAmount'] ?? 0;
        $currency               = $payload['currency'] ?? 'NGN';
        $senderAccountName      = $payload['senderAccountName'] ?? null;
        $senderBankName         = $payload['senderBankName'] ?? null;

        if (!$transactionId || !$merchantAccountNumber) {
            Log::warning('[FidelityWebhook] Missing transactionId or merchantAccountNumber');
            return response()->json(['message' => 'Missing identifiers'], 422);
        }

        // ── Deduplication: transactionId must be unique ───────────────────────
        $alreadyProcessed = TransactionHistory::where('payment_provider', 'fidelity')
            ->where('order_id', $transactionId)
            ->exists();

        if ($alreadyProcessed) {
            Log::info('[FidelityWebhook] Duplicate webhook ignored', [
                'transaction_id' => $transactionId,
            ]);
            return response()->json(['message' => 'Already processed'], 200);
        }

        // ── Find the wallet (Balance) that owns this virtual account ──────────
        $balance = Balance::where('virtual_account_number', $merchantAccountNumber)->first();

        if (!$balance) {
            Log::warning('[FidelityWebhook] No wallet found for virtual account number', [
                'merchant_account_number' => $merchantAccountNumber,
            ]);
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        if ((string) $balance->currency !== (string) $currency) {
            Log::warning('[FidelityWebhook] Currency mismatch on wallet', [
                'balance_id'       => $balance->id,
                'wallet_currency'  => $balance->currency,
                'payload_currency' => $currency,
            ]);
            return response()->json(['message' => 'Currency mismatch for this wallet'], 422);
        }

        $ownerId    = $balance->user_id;
        $personalId = $balance->personal_id;

        $creditAmount = (float) $transactionAmount;

        // ── Create the transaction record ───────────────────────────────────────
        $tx = TransactionHistory::create([
            'user_id'                  => $ownerId,
            'personal_id'               => $personalId,
            'balance_id'                => $balance->id,
            'payment_provider'          => 'fidelity',
            'transaction_type'          => 'payment',
            'method'                    => 'credit',
            'amount'                    => $creditAmount,
            'fees'                      => $feeAmount,
            'total_amount'              => $transactionAmount,
            'currency'                  => $currency,
            'status'                    => 'success',
            'reference'                 => $payload['settlementId'] ?? $transactionId,
            'order_id'                  => $transactionId,
            'sender'                    => $senderAccountName,
            'recipient_account_number'  => $merchantAccountNumber,
            'recipient_account_name'    => $merchantAccountName,
            'recipient_bank_name'       => $senderBankName,
        ]);

        Log::info('[FidelityWebhook] Transaction created', [
            'tx_id'                   => $tx->id,
            'merchant_account_number' => $merchantAccountNumber,
            'amount'                  => $creditAmount,
        ]);

        // ── Credit wallet balance ───────────────────────────────────────────────
        $balance->amount += $creditAmount;
        $balance->save();

        Log::info('[FidelityWebhook] Balance credited', [
            'balance_id'  => $balance->id,
            'amount'      => $creditAmount,
            'new_balance' => $balance->amount,
        ]);

        $this->sendPushNotification($tx);
        $this->sendEmail($tx);

        return response()->json(['message' => 'Webhook processed'], 200);
    }

    // protected function sendPushNotification(TransactionHistory $tx): void
    // {
    //     try {
    //         $firebase = app(FirebaseNotificationService::class);

    //         $owner = $tx->user_id ? User::find($tx->user_id) : null;
    //         $owner = $owner ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

    //         if (!$owner || empty($owner->device_token)) {
    //             return;
    //         }

    //         $currency = strtoupper((string) $tx->currency);
    //         $amount   = number_format((float) $tx->amount, 2);

    //         $firebase->sendToToken(
    //             $owner->device_token,
    //             'Deposit Successful',
    //             "Your deposit of {$currency} {$amount} was successful.",
    //             [
    //                 'type'           => 'transaction',
    //                 'transaction_id' => (string) $tx->id,
    //                 'status'         => 'success',
    //             ]
    //         );

    //         Log::info('[FidelityWebhook] Push notification sent', ['tx_id' => $tx->id]);

    //     } catch (\Throwable $e) {
    //         Log::warning('[FidelityWebhook] Push notification failed', [
    //             'tx_id' => $tx->id,
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }

    protected function sendPushNotification(TransactionHistory $tx): void
    {
        $owner = $tx->user_id ? User::find($tx->user_id) : null;
        $owner = $owner ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

        if (!$owner) {
            return;
        }

        $currency = strtoupper((string) $tx->currency);
        $amount   = number_format((float) $tx->amount, 2);
        $title    = 'Deposit Successful';
        $body     = "Your deposit of {$currency} {$amount} was successful.";

        // ── Push notification (device token required) ──────────────────────────
        if (!empty($owner->device_token)) {
            try {
                $firebase = app(FirebaseNotificationService::class);

                $firebase->sendToToken(
                    $owner->device_token,
                    $title,
                    $body,
                    [
                        'type'           => 'transaction',
                        'transaction_id' => (string) $tx->id,
                        'status'         => 'success',
                    ]
                );

                Log::info('[FidelityWebhook] Push notification sent', ['tx_id' => $tx->id]);

            } catch (\Throwable $e) {
                Log::warning('[FidelityWebhook] Push notification failed', [
                    'tx_id' => $tx->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // ── In-app / DB notification (always sent, no device token needed) ─────
        try {
            $owner->notify(new GeneralNotification($title, $body));

            Log::info('[FidelityWebhook] GeneralNotification sent', ['tx_id' => $tx->id]);

        } catch (\Throwable $e) {
            Log::warning('[FidelityWebhook] GeneralNotification failed', [
                'tx_id' => $tx->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function sendEmail(TransactionHistory $tx): void
    {
        try {
            $owner = $tx->user_id ? User::find($tx->user_id) : null;
            $owner = $owner ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

            if (!$owner || empty($owner->email)) {
                return;
            }

            $balance = Balance::find($tx->balance_id);

            $data = [
                'name'               => $owner->business_name ?? $owner->name ?? $owner->firstname ?? 'Customer',
                'amount_sent'        => number_format((float) $tx->amount, 2),
                'recipient_amount'   => number_format((float) $tx->amount, 2),
                'fee'                => number_format((float) ($tx->fees ?? 0), 2),
                'total_amount'       => number_format((float) ($tx->total_amount ?? $tx->amount), 2),
                'current_balance'    => number_format((float) ($balance->amount ?? 0), 2),
                'sending_currency'   => strtoupper((string) $tx->currency),
                'recipient_currency' => strtoupper((string) $tx->currency),
                'reference'          => $tx->reference ?? $tx->order_id ?? 'N/A',
                'status'             => 'success',
                'status_text'        => 'Success',
                'failure_reason'     => null,
                'status_message'     => 'Your deposit was processed successfully. Here is a summary of your transaction.',
            ];

            Mail::to($owner->email)->send(new TransactionSentMail($data));

            Log::info('[FidelityWebhook] Email sent', ['tx_id' => $tx->id]);

        } catch (\Throwable $e) {
            Log::warning('[FidelityWebhook] Email failed', [
                'tx_id' => $tx->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}