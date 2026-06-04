<?php


namespace App\Jobs;

use App\Models\Personal;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use App\Services\OrchardService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckOrchardTransactions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected OrchardService $orchard;

    public function __construct()
    {
        $this->orchard = app(OrchardService::class);
    }

    public function handle()
    {
        $firebase = app(FirebaseNotificationService::class);

        $pending = TransactionHistory::where('payment_provider', 'appmobile')
            ->whereIn('status', ['pending'])
            ->get();

        foreach ($pending as $tx) {
            $res = $this->orchard->checkTransaction($tx->order_id, 'TSC');

            if (!($res['success'] ?? false)) {
                continue;
            }

            $status = $res['data']['trans_status'] ?? null;
            $message = strtoupper($res['data']['message'] ?? '');

            $mapped = match (true) {
                $status === '000/01' || $message === 'SUCCESSFUL' => 'success',
                $status === '000/02' || $message === 'FAILED' => 'failed',
                default => 'pending',
            };

            if ($mapped !== $tx->status) {
                $tx->status = $mapped;
                $tx->save();

                $this->sendTransactionStatusNotification($tx, $firebase);
            }
        }
    }

    protected function sendTransactionStatusNotification(TransactionHistory $tx, FirebaseNotificationService $firebase): void
{
    $user = User::find($tx->user_id) ?? Personal::find($tx->user_id);

    if (!$user || empty($user->device_token)) {
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

    $firebase->sendToToken($user->device_token, $title, $body, [
        'type' => 'transaction',
        'transaction_id' => (string) $tx->id,
        'status' => (string) $tx->status,
    ]);
}

    // protected function sendTransactionStatusNotification(TransactionHistory $tx, FirebaseNotificationService $firebase): void
    // {
    //     $user = User::find($tx->user_id) ?? Personal::find($tx->user_id);

    //     if (!$user || empty($user->device_token)) {
    //         return;
    //     }

    //     $firebase->sendSilentToToken($user->device_token, [
    //         'transaction_id' => $tx->id,
    //         'status' => $tx->status,
    //     ]);
    // }
}



// namespace App\Jobs;

// use App\Models\TransactionHistory;
// use App\Services\OrchardService;
// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Foundation\Bus\Dispatchable;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Queue\SerializesModels;

// class CheckOrchardTransactions implements ShouldQueue
// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     protected OrchardService $orchard;

//     public function __construct()
//     {
//         $this->orchard = app(OrchardService::class);
//     }

//     public function handle()
//     {
//         $pending = TransactionHistory::where('payment_provider', 'appmobile')
//             ->whereIn('status', ['pending'])
//             ->get();

//         foreach ($pending as $tx) {
//             $res = $this->orchard->checkTransaction($tx->order_id, 'TSC');

//             if (!($res['success'] ?? false)) {
//                 continue;
//             }

//             $status = $res['data']['trans_status'] ?? null; // e.g. "000/01"
//             $message = strtoupper($res['data']['message'] ?? '');

//             // Map Orchard response to your status
//             $mapped = match (true) {
//                 $status === '000/01' || $message === 'SUCCESSFUL' => 'success',
//                 $status === '000/02' || $message === 'FAILED' => 'failed',
//                 default => 'pending',
//             };

//             if ($mapped !== $tx->status) {
//                 $tx->status = $mapped;
//                 $tx->save();
//             }
//         }
//     }
// }
