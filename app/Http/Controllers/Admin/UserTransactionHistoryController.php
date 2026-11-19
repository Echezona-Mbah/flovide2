<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use App\Models\BusinessWebhookLog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Personal;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserTransactionHistoryController extends Controller
{
    public function history(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $search = $request->input('search');

        $query = TransactionHistory::where('user_id', $user->id)->liveMode();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'LIKE', "%{$search}%")
                  ->orWhere('amount', 'LIKE', "%{$search}%")
                  ->orWhere('type', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%")
                  ->orWhere('method', 'LIKE', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(15)->appends(['search' => $search]);
        $businessTransactions = TransactionHistory::where('user_id', $user->id)->liveMode();
        $statusCounts = (clone $businessTransactions)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $normalizedStatusCounts = $statusCounts->reduce(function (array $counts, $total, $status) {
            $normalizedStatus = strtolower((string) $status);
            $counts[$normalizedStatus] = ($counts[$normalizedStatus] ?? 0) + (int) $total;
            return $counts;
        }, []);
        $apiSourceAvailable = Schema::hasColumn((new TransactionHistory())->getTable(), 'api_source');
        $transactionSummary = [
            'total' => (clone $businessTransactions)->count(),
            'api_source_available' => $apiSourceAvailable,
            'api' => $apiSourceAvailable ? (clone $businessTransactions)->where('api_source', true)->count() : 0,
            'successful' => array_sum(array_intersect_key($normalizedStatusCounts, array_flip(['success', 'successful', 'completed']))),
            'pending' => array_sum(array_intersect_key($normalizedStatusCounts, array_flip(['pending', 'processing']))),
            'failed' => array_sum(array_intersect_key($normalizedStatusCounts, array_flip(['failed', 'declined']))),
        ];

        $webhookRoutes = [
            'live' => ['collection' => null, 'payout' => null],
            'test' => ['collection' => null, 'payout' => null],
            'available' => false,
        ];
        if (Schema::hasTable('webhook_settings') && Schema::hasColumn('webhook_settings', 'user_id')) {
            $routeColumns = array_filter([
                'live_collection_url' => Schema::hasColumn('webhook_settings', 'live_collection_url'),
                'live_payout_url' => Schema::hasColumn('webhook_settings', 'live_payout_url'),
                'test_collection_url' => Schema::hasColumn('webhook_settings', 'test_collection_url'),
                'test_payout_url' => Schema::hasColumn('webhook_settings', 'test_payout_url'),
            ]);

            if ($routeColumns !== []) {
                $settings = DB::table('webhook_settings')
                    ->where('user_id', $user->id)
                    ->first(array_keys($routeColumns));

                if ($settings) {
                    $webhookRoutes = [
                        'live' => [
                            'collection' => $settings->live_collection_url ?? null,
                            'payout' => $settings->live_payout_url ?? null,
                        ],
                        'test' => [
                            'collection' => $settings->test_collection_url ?? null,
                            'payout' => $settings->test_payout_url ?? null,
                        ],
                        'available' => true,
                    ];
                }
            }
        }

        return view('admin.history', compact('transactions', 'user', 'transactionSummary', 'webhookRoutes'));
    }

    public function webhookActivity($id): JsonResponse
    {
        $user = User::findOrFail($id);

        if (
            ! Schema::hasTable('business_webhook_logs')
            || ! Schema::hasTable('business_webhook_log_histories')
        ) {
            return response()->json([
                'available' => false,
                'message' => 'Webhook activity storage is not available. Apply the existing webhook-log migrations to enable it.',
            ]);
        }

        $logs = BusinessWebhookLog::query()
            ->where('business_id', $user->id)
            ->with(['histories' => fn ($query) => $query->reorder()->orderBy('created_at')->orderBy('id')])
            ->latest()
            ->limit(50)
            ->get();

        $deliveryCounts = BusinessWebhookLog::query()
            ->where('business_id', $user->id)
            ->select('delivery_status', DB::raw('COUNT(*) as total'))
            ->groupBy('delivery_status')
            ->pluck('total', 'delivery_status');

        $transactionStatusCounts = BusinessWebhookLog::query()
            ->where('business_id', $user->id)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $attempts = DB::table('business_webhook_log_histories')
            ->join('business_webhook_logs', 'business_webhook_log_histories.webhook_log_id', '=', 'business_webhook_logs.id')
            ->where('business_webhook_logs.business_id', $user->id)
            ->count();

        return response()->json([
            'available' => true,
            'counts' => [
                'events' => (int) $deliveryCounts->sum(),
                'successful' => (int) ($deliveryCounts['SUCCESSFUL'] ?? 0),
                'failed' => (int) ($deliveryCounts['FAILED'] ?? 0),
                'pending' => (int) ($deliveryCounts['PENDING'] ?? 0),
                'attempts' => $attempts,
                'transaction_statuses' => $transactionStatusCounts,
            ],
            'events' => $logs->map(fn (BusinessWebhookLog $log) => [
                'id' => $log->id,
                'event_id' => data_get($log->payload, 'event_id'),
                'type' => $log->type,
                'mode' => $log->mode,
                'url' => $log->webhook_url,
                'transaction_status' => $log->status,
                'delivery_status' => $log->delivery_status,
                'reference' => data_get($log->payload, 'transaction_reference'),
                'transaction_id' => data_get($log->payload, 'transaction_id') ?: $log->business_transaction_id,
                'event' => data_get($log->payload, 'event'),
                'amount' => data_get($log->payload, 'transaction_amount'),
                'amount_without_fee' => data_get($log->payload, 'transaction_amount_without_fee'),
                'fee' => data_get($log->payload, 'transaction_fee'),
                'currency' => data_get($log->payload, 'transaction_currency'),
                'recipient' => data_get($log->payload, 'recipient'),
                'payload' => $log->payload,
                'response' => $log->response,
                'created_at' => $log->created_at?->toIso8601String(),
                'attempts' => $log->histories->map(fn ($history) => [
                    'attempt' => data_get($history->metadata, 'attempt'),
                    'url' => data_get($history->metadata, 'url'),
                    'http_status' => $history->http_status,
                    'response' => $history->response,
                    'created_at' => $history->created_at?->toIso8601String(),
                ])->values(),
            ])->values(),
        ]);
    }

    public function sendBusinessTransactionWebhook(
        string $user,
        string $transaction,
        \App\Services\BusinessApiWebhookService $webhooks
    ) {
        $business = User::findOrFail($user);
        $transaction = TransactionHistory::query()
            ->where('user_id', $business->id)
            ->whereNull('personal_id')
            ->findOrFail($transaction);

        if (! in_array(strtolower((string) $transaction->status), ['success', 'successful', 'completed'], true)) {
            return back()->with('error', 'Only successful business transactions can be sent to the client webhook.');
        }

        $displayType = strtolower((string) $transaction->display_type);
        if (! $transaction->api_source || ! in_array($displayType, ['collection', 'payout'], true)) {
            return back()->with('error', 'This transaction is not an API collection or payout event.');
        }

        $mode = strtolower((string) ($transaction->mode ?: 'live'));
        $type = $displayType === 'collection' ? 'COLLECTION' : 'PAYOUT';

        try {
            $log = $webhooks->dispatch($transaction, $type, $mode, 'transaction.success');
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Could not send the transaction webhook. Check the business webhook settings and try again.');
        }

        if (! $log) {
            return back()->with('error', 'The business has no webhook URL configured for this transaction environment.');
        }

        if ($log->delivery_status === 'SUCCESSFUL') {
            return back()->with('success', 'Transaction webhook delivered to the business successfully.');
        }

        return back()->with('error', 'Webhook delivery failed. Review the business webhook activity for the response and retry.');
    }


    public function personalHistory(Request $request, $id)
    {
        $personal = Personal::findOrFail($id);
        $search = $request->input('search');

        $query = TransactionHistory::where('personal_id', $personal->id)->liveMode();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'LIKE', "%{$search}%")
                  ->orWhere('amount', 'LIKE', "%{$search}%")
                  ->orWhere('type', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%")
                  ->orWhere('method', 'LIKE', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(15)->appends(['search' => $search]);

        return view('admin.personal_history', compact('transactions', 'personal'));
    }
}
