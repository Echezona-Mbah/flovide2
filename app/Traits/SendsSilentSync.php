<?php

namespace App\Traits;

use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait SendsSilentSync
{

 protected function sendSilentDashboardSync(
        Request $request,
        $account,
        string $accountType,
        string $type = 'refresh',
        array $extraData = []
    ): void {
        if (empty($account->device_token)) {
            return;
        }

        // Loop guard: the app sets this header when it's refetching because of a silent push
        if ($request->header('X-Silent-Sync') === '1') {
            return;
        }

        // Throttle: at most one silent push per minute per account, per type —
        // so a "refresh" push and a "notification" push don't suppress each other.
        $key = "silent_sync:{$accountType}:{$account->id}:{$type}";
        if (!Cache::add($key, true, now()->addSeconds(60))) {
            return;
        }

        $token     = $account->device_token;
        $accountId = $account->id;

        // Runs after the HTTP response is sent, so it never slows the request down
        dispatch(function () use ($token, $accountId, $accountType, $type, $extraData) {
            try {
                app(FirebaseNotificationService::class)->sendSilentToToken($token, array_merge([
                    'type'         => $type,
                    'account_type' => $accountType,
                    'ts'           => (string) now()->timestamp,
                ], $extraData));
            } catch (\Throwable $e) {
                Log::warning('[SilentSync] Silent push failed', [
                    'account_type' => $accountType,
                    'account_id'   => $accountId,
                    'type'         => $type,
                    'error'        => $e->getMessage(),
                ]);
            }
        })->afterResponse();
    }

    // protected function sendSilentDashboardSync(Request $request, $account, string $accountType): void
    // {
    //     if (empty($account->device_token)) {
    //         return;
    //     }

    //     // Loop guard: the app sets this header when it's refetching because of a silent push
    //     if ($request->header('X-Silent-Sync') === '1') {
    //         return;
    //     }

    //     // Throttle: at most one silent push per minute per account
    //     $key = "silent_sync:{$accountType}:{$account->id}";
    //     if (!Cache::add($key, true, now()->addSeconds(60))) {
    //         return;
    //     }

    //     $token     = $account->device_token;
    //     $accountId = $account->id;

    //     // Runs after the HTTP response is sent, so it never slows the dashboard down
    //     dispatch(function () use ($token, $accountId, $accountType) {
    //         try {
    //             app(FirebaseNotificationService::class)->sendSilentToToken($token, [
    //                 'type'         => 'refresh',
    //                 'account_type' => $accountType,
    //                 'ts'           => (string) now()->timestamp,
    //             ]);
    //         } catch (\Throwable $e) {
    //             Log::warning('[SilentSync] Silent push failed', [
    //                 'account_type' => $accountType,
    //                 'account_id'   => $accountId,
    //                 'error'        => $e->getMessage(),
    //             ]);
    //         }
    //     })->afterResponse();
    // }
}