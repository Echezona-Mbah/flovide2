<?php

namespace App\Jobs;

use App\Models\Balance;
use App\Models\Personal;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Services\BlaaizService;
use App\Services\FirebaseNotificationService;
use App\Mail\TransactionSentMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckBlaaizInteracTransactions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected BlaaizService $blaaiz;

    public function __construct()
    {
        $this->blaaiz = app(BlaaizService::class);
    }

    public function handle()
    {
        $firebase = app(FirebaseNotificationService::class);

        $pending = TransactionHistory::where('payment_provider', 'interac')
            ->whereIn('status', ['pending'])
            ->get();

        Log::info('[CheckBlaaizInterac] Checking pending transactions', [
            'count' => $pending->count(),
        ]);

        foreach ($pending as $tx) {
            Log::info('[CheckBlaaizInterac] Checking transaction', [
                'transaction_id' => $tx->id,
                'reference'      => $tx->reference,
                'order_id'       => $tx->order_id,
            ]);

            $transactionId = $tx->order_id ?? $tx->payment_reference;

            if (!$transactionId) {
                Log::warning('[CheckBlaaizInterac] No transaction ID to check', [
                    'transaction_id' => $tx->id,
                ]);
                continue;
            }

            $res = $this->blaaiz->getTransaction($transactionId);

            Log::info('[CheckBlaaizInterac] Blaaiz response', [
                'transaction_id' => $tx->id,
                'success'        => $res['success'] ?? false,
                'status'         => $res['status'] ?? null,
                'data'           => $res['data'] ?? null,
            ]);

            if (!($res['success'] ?? false)) {
                Log::warning('[CheckBlaaizInterac] Failed to fetch transaction status', [
                    'transaction_id' => $tx->id,
                ]);
                continue;
            }

            $blaaizStatus = strtolower($res['data']['status'] ?? $res['data']['data']['status'] ?? '');

            $mapped = match ($blaaizStatus) {
                'success', 'completed', 'successful' => 'success',
                'failed', 'declined', 'rejected'     => 'failed',
                default                               => 'pending',
            };

            Log::info('[CheckBlaaizInterac] Mapped status', [
                'transaction_id' => $tx->id,
                'blaaiz_status'  => $blaaizStatus,
                'mapped_status'  => $mapped,
            ]);

            if ($mapped === $tx->status) {
                continue;
            }

            $tx->status = $mapped;
            $tx->save();

            Log::info('[CheckBlaaizInterac] Transaction status updated', [
                'transaction_id' => $tx->id,
                'new_status'     => $mapped,
            ]);

            if ($mapped === 'success' && $tx->balance_id) {
                $balance = Balance::find($tx->balance_id);
                if ($balance) {
                    $balance->amount += $tx->amount;
                    $balance->save();

                    Log::info('[CheckBlaaizInterac] Balance credited', [
                        'balance_id'  => $balance->id,
                        'amount'      => $tx->amount,
                        'new_balance' => $balance->amount,
                    ]);
                }
            }

            $this->sendTransactionStatusNotification($tx, $firebase);

            if (in_array($mapped, ['success', 'failed'])) {
                $this->sendTransactionEmailFromCron($tx);
            }
        }

        Log::info('[CheckBlaaizInterac] Job completed');
    }

    protected function sendTransactionStatusNotification(TransactionHistory $tx, FirebaseNotificationService $firebase): void
    {
        $user = $tx->user_id ? User::find($tx->user_id) : null;
        $user = $user ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

        if (!$user || empty($user->device_token)) {
            return;
        }

        $statusText = ucfirst((string) $tx->status);
        $currency   = strtoupper((string) $tx->currency);
        $amount     = number_format((float) $tx->amount, 2);

        $title = match ($tx->status) {
            'success' => 'Interac Deposit Successful',
            'failed'  => 'Interac Deposit Failed',
            default   => 'Interac Deposit Updated',
        };

        $body = match ($tx->status) {
            'success' => "Your Interac deposit of {$currency} {$amount} was successful.",
            'failed'  => "Your Interac deposit of {$currency} {$amount} failed.",
            default   => "Your Interac deposit status is now {$statusText}.",
        };

        $firebase->sendToToken($user->device_token, $title, $body, [
            'type'           => 'transaction',
            'transaction_id' => (string) $tx->id,
            'status'         => (string) $tx->status,
        ]);
    }

    protected function sendTransactionEmailFromCron(TransactionHistory $tx): void
    {
        try {
            $user = $tx->user_id ? User::find($tx->user_id) : null;
            $user = $user ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

            if (!$user || empty($user->email)) {
                return;
            }

            $balance    = Balance::find($tx->balance_id);
            $statusText = ucfirst((string) $tx->status);

            $data = [
                'name'               => $user->business_name ?? $user->name ?? $user->firstname ?? 'Customer',
                'amount_sent'        => number_format((float) $tx->amount, 2),
                'recipient_amount'   => number_format((float) $tx->recipient_amount, 2),
                'fee'                => number_format((float) ($tx->fees ?? 0), 2),
                'total_amount'       => number_format((float) ($tx->total_amount ?? $tx->amount), 2),
                'current_balance'    => number_format((float) ($balance->amount ?? 0), 2),
                'sending_currency'   => strtoupper((string) $tx->currency),
                'recipient_currency' => strtoupper((string) ($tx->to_currency ?? $tx->recipient_bank_currency ?? $tx->currency)),
                'reference'          => $tx->reference ?? $tx->order_id ?? 'N/A',
                'status'             => $tx->status,
                'status_text'        => $statusText,
                'failure_reason'     => $tx->failure_reason,
                'status_message'     => $tx->status === 'success'
                    ? 'Your Interac deposit was processed successfully. Here is a summary of your transaction.'
                    : 'Your Interac deposit could not be completed. Here is the transaction summary.',
            ];

            Mail::to($user->email)->send(new TransactionSentMail($data));

        } catch (\Throwable $e) {
            Log::warning('[CheckBlaaizInterac] Email notification failed', [
                'transaction_id' => $tx->id,
                'status'         => $tx->status,
                'error'          => $e->getMessage(),
            ]);
        }
    }
}