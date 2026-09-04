<?php

namespace App\Jobs;

use App\Models\Personal;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use App\Services\PayazaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\TransactionSentMail;
use App\Models\Balance;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\ReferralBonusService;
use App\Notifications\GeneralNotification;

class CheckPayazaTransactions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected PayazaService $payaza;

    public function __construct()
    {
        $this->payaza = app(PayazaService::class);
    }

    public function handle()
    {
        $firebase = app(FirebaseNotificationService::class);

        $pending = TransactionHistory::where('payment_provider', 'payaza')
            ->whereIn('status', ['pending'])
            ->get();

        foreach ($pending as $tx) {
            $res = $this->payaza->getTransactionStatus($tx->order_id);

            if (!($res['success'] ?? false)) {
                continue;
            }

            $status = $res['data']['data']['transactionStatus'] ?? null;

            $mapped = match ($status) {
                'NIP_SUCCESS' => 'success',
                'NIP_FAILURE' => 'failed',
                'NIP_PENDING', 'TRANSACTION_INITIATED', 'ESCROW_SUCCESS' => 'pending',
                default => 'pending',
            };

            if ($mapped !== $tx->status) {
                $tx->status = $mapped;
                $tx->save();

                $this->sendTransactionStatusNotification($tx, $firebase);

                    if (in_array($mapped, ['success', 'failed'])) {
                        $this->sendTransactionEmailFromCron($tx);
                    }
            }
        }
    }

// protected function sendTransactionStatusNotification(TransactionHistory $tx, FirebaseNotificationService $firebase): void
// {
//     $user = $tx->user_id ? User::find($tx->user_id) : null;
//     $user = $user ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

//     if (!$user || empty($user->device_token)) {
//         return;
//     }

//     $statusText = ucfirst((string) $tx->status);
//     $currency = strtoupper((string) $tx->currency);
//     $amount = number_format((float) $tx->amount, 2);

//     $title = match ($tx->status) {
//         'success' => 'Transaction Successful',
//         'failed' => 'Transaction Failed',
//         default => 'Transaction Updated',
//     };

//     $body = match ($tx->status) {
//         'success' => "Your transaction of {$currency} {$amount} was successful.",
//         'failed' => "Your transaction of {$currency} {$amount} failed.",
//         default => "Your transaction status is now {$statusText}.",
//     };

//     $firebase->sendToToken($user->device_token, $title, $body, [
//         'type' => 'transaction',
//         'transaction_id' => (string) $tx->id,
//         'status' => (string) $tx->status,
//     ]);
// }


protected function sendTransactionStatusNotification(TransactionHistory $tx, FirebaseNotificationService $firebase): void
{
    $user = $tx->user_id ? User::find($tx->user_id) : null;
    $user = $user ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

    if (!$user) {
        return;
    }

    $statusText = ucfirst((string) $tx->status);
    $currency = strtoupper((string) $tx->currency);
    $amount = number_format((float) $tx->amount, 2);

    $title = match ($tx->status) {
        'success' => 'Transaction Successful',
        'failed' => 'Transaction Failed',
        default => 'Transaction Updated',
    };

    $body = match ($tx->status) {
        'success' => "Your transaction of {$currency} {$amount} was successful.",
        'failed' => "Your transaction of {$currency} {$amount} failed.",
        default => "Your transaction status is now {$statusText}.",
    };

    // ── Push notification (device token required) ──────────────────────────
    if (!empty($user->device_token)) {
        $firebase->sendToToken($user->device_token, $title, $body, [
            'type' => 'transaction',
            'transaction_id' => (string) $tx->id,
            'status' => (string) $tx->status,
        ]);
    }

    // ── In-app / DB notification (always sent, no device token needed) ─────
    try {
        $user->notify(new GeneralNotification($title, $body));
    } catch (\Throwable $e) {
        Log::warning('[CheckOrchardTransactions] GeneralNotification failed', [
            'transaction_id' => $tx->id,
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
    }
}


protected function sendTransactionEmailFromCron(TransactionHistory $tx): void
{
    try {
        $user = $tx->user_id ? User::find($tx->user_id) : null;
        $user = $user ?: ($tx->personal_id ? Personal::find($tx->personal_id) : null);

        if (!$user || empty($user->email)) {
            return;
        }

        $balance = Balance::find($tx->balance_id);
        $statusText = ucfirst((string) $tx->status);

        $data = [
            'name' => $user->business_name ?? $user->name ?? $user->firstname ?? 'Customer',
            'amount_sent' => number_format((float) $tx->amount, 2),
            'recipient_amount' => number_format((float) $tx->recipient_amount, 2),
            'fee' => number_format((float) ($tx->fees ?? 0), 2),
            'total_amount' => number_format((float) ($tx->total_amount ?? $tx->amount), 2),
            'current_balance' => number_format((float) ($balance->amount ?? 0), 2),
            'sending_currency' => strtoupper((string) $tx->currency),
            'recipient_currency' => strtoupper((string) ($tx->to_currency ?? $tx->recipient_bank_currency)),
            'reference' => $tx->reference ?? $tx->order_id ?? 'N/A',
            'status' => $tx->status,
            'status_text' => $statusText,
            'failure_reason' => $tx->failure_reason,
            'status_message' => $tx->status === 'success'
                ? 'Your transfer was processed successfully. Here is a clear summary of your transaction.'
                : 'Your transfer could not be completed. Here is the transaction summary.',
        ];

        Mail::to($user->email)->send(new TransactionSentMail($data));
    } catch (\Throwable $e) {
        Log::warning('Cron transaction email failed', [
            'transaction_id' => $tx->id,
            'status' => $tx->status,
            'error' => $e->getMessage(),
        ]);
    }
}


}










// namespace App\Jobs;

// use App\Models\TransactionHistory;
// use App\Services\PayazaService;
// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Foundation\Bus\Dispatchable;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Queue\SerializesModels;

// class CheckPayazaTransactions implements ShouldQueue
// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     protected PayazaService $payaza;

//     public function __construct()
//     {
//         $this->payaza = app(PayazaService::class);
//     }

//     public function handle()
//     {
//         $pending = TransactionHistory::where('payment_provider', 'payaza')
//             ->whereIn('status', ['pending'])
//             ->get();


//         foreach ($pending as $tx) {
//             $res = $this->payaza->getTransactionStatus($tx->order_id);

//             if (!($res['success'] ?? false)) {
//                 continue;
//             }

//             $status = $res['data']['data']['transactionStatus'] ?? null;

//             $mapped = match ($status) {
//                 'NIP_SUCCESS' => 'success',
//                 'NIP_FAILURE' => 'failed',
//                 'NIP_PENDING', 'TRANSACTION_INITIATED', 'ESCROW_SUCCESS' => 'pending',
//                 default => 'pending',
//             };

//             if ($mapped !== $tx->status) {
//                 $tx->status = $mapped;
//                 $tx->save();
//             }

//         }
//     }
// }
