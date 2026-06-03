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
            }
        }
    }

    protected function sendTransactionStatusNotification(TransactionHistory $tx, FirebaseNotificationService $firebase): void
    {
        $user = User::find($tx->user_id) ?? Personal::find($tx->user_id);

        if (!$user || empty($user->device_token)) {
            return;
        }

        $firebase->sendSilentToToken($user->device_token, [
            'transaction_id' => $tx->id,
            'status' => $tx->status,
        ]);
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
