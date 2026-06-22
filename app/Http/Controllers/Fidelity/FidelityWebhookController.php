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

class FidelityWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('[FidelityWebhook] Incoming webhook', $payload);

        $transactionId          = $payload['transactionId'] ?? null;
        $merchantAccountNumber  = $payload['merchantAccountNumber'] ?? null;
        $merchantAccountName    = $payload['merchantAccountName'] ?? null;
        $settledAmount          = $payload['settledAmount'] ?? null;
        $transactionAmount      = $payload['transactionAmount'] ?? null;
        $feeAmount              = $payload['feeAmount'] ?? 0;
        $currency               = $payload['currency'] ?? 'NGN';
        $narration              = $payload['narration'] ?? null;
        $senderAccountNumber    = $payload['senderAccountNumber'] ?? null;
        $senderAccountName      = $payload['senderAccountName'] ?? null;
        $senderBankName         = $payload['senderBankName'] ?? null;
        $tranDateTime           = $payload['tranDateTime'] ?? null;

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

        // ── Find the owner of this virtual account number ─────────────────────
        $user = User::where('virtual_account_number', $merchantAccountNumber)->first();
        $personal = null;

        if (!$user) {
            $personal = Personal::where('virtual_account_number', $merchantAccountNumber)->first();
        }

        if (!$user && !$personal) {
            Log::warning('[FidelityWebhook] No account owner found for merchant account number', [
                'merchant_account_number' => $merchantAccountNumber,
            ]);
            return response()->json(['message' => 'Account owner not found'], 404);
        }

        $ownerId   = $user->id ?? null;
        $personalId = $personal->id ?? null;

        // ── Find the user's NGN balance to credit ──────────────────────────────
        $balance = Balance::where('user_id', $ownerId ?? $personalId)
            ->where('currency', $currency)
            ->first();

        if (!$balance) {
            Log::warning('[FidelityWebhook] No matching balance wallet found', [
                'owner_id' => $ownerId ?? $personalId,
                'currency' => $currency,
            ]);
            return response()->json(['message' => 'Wallet not found for this currency'], 404);
        }

        $creditAmount = (float) ($settledAmount ?? $transactionAmount ?? 0);

        // ── Create the transaction record ───────────────────────────────────────
        $tx = TransactionHistory::create([
            'user_id'           => $ownerId,
            'personal_id'       => $personalId,
            'balance_id'        => $balance->id,
            'payment_provider'  => 'fidelity',
            'transaction_type'  => 'payment',
            'method'            => 'credit',
            'amount'            => $creditAmount,
            'fees'              => $feeAmount,
            'total_amount'      => $transactionAmount,
            'currency'          => $currency,
            'status'            => 'success',
            'reference'         => $payload['settlementId'] ?? $transactionId,
            'order_id'          => $transactionId,
            'sender'            => $senderAccountName,
            'recipient_account_number' => $merchantAccountNumber,
            'recipient_account_name'   => $merchantAccountName,
            'recipient_bank_name'      => $senderBankName,
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

    protected function sendPushNotification(TransactionHistory $tx): void
    {
        try {
            $firebase = app(FirebaseNotificationService::class);

            $user = $tx->user_id ? User::find($tx->user_id) : null;
            $user = $user ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

            if (!$user || empty($user->device_token)) {
                return;
            }

            $currency = strtoupper((string) $tx->currency);
            $amount   = number_format((float) $tx->amount, 2);

            $firebase->sendToToken(
                $user->device_token,
                'Deposit Successful',
                "Your deposit of {$currency} {$amount} was successful.",
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

    protected function sendEmail(TransactionHistory $tx): void
    {
        try {
            $user = $tx->user_id ? User::find($tx->user_id) : null;
            $user = $user ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

            if (!$user || empty($user->email)) {
                return;
            }

            $balance = Balance::find($tx->balance_id);

            $data = [
                'name'               => $user->business_name ?? $user->name ?? $user->firstname ?? 'Customer',
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

            Mail::to($user->email)->send(new TransactionSentMail($data));

            Log::info('[FidelityWebhook] Email sent', ['tx_id' => $tx->id]);

        } catch (\Throwable $e) {
            Log::warning('[FidelityWebhook] Email failed', [
                'tx_id' => $tx->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}