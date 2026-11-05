<?php

namespace App\Http\Controllers\Swappr;

use App\Http\Controllers\Controller;
use App\Services\SwapprCadInteracService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class CadInteracWebhookController extends Controller
{
    public function handle(Request $request, SwapprCadInteracService $swappr)
    {
        $secret = (string) config('services.swappr.webhook_secret');
        if ($secret === '') {
            Log::error('[Swappr Webhook] Signing secret is not configured.');

            return response()->json(['message' => 'Webhook is not configured.'], 503);
        }

        $rawBody = $request->getContent();
        $signatureFailureReason = $swappr->webhookSignatureFailureReason(
            $rawBody,
            (string) $request->header('X-Swappr-Signature'),
            $secret,
            now()->timestamp
        );
        if ($signatureFailureReason !== null) {
            Log::warning('[Swappr Webhook] Signature verification failed.', [
                'reason' => $signatureFailureReason,
            ]);

            return response()->json(['message' => 'Invalid or expired signature.'], 401);
        }

        $event = json_decode($rawBody, true);
        if (!is_array($event)) {
            return response()->json(['message' => 'Invalid JSON payload.'], 400);
        }

        $headerEvent = $request->header('X-Swappr-Event');
        if ($headerEvent && $headerEvent !== ($event['event'] ?? null)) {
            return response()->json(['message' => 'Event header does not match the payload.'], 400);
        }

        if (($event['_test'] ?? false) === true) {
            return response()->json(['message' => 'Test webhook acknowledged.'], 200);
        }

        if (
            ($event['event'] ?? null) !== 'wallet_funded'
            || ($event['rail'] ?? null) !== 'interac'
            || strtoupper((string) ($event['currency'] ?? '')) !== 'CAD'
        ) {
            return response()->json(['message' => 'Event ignored.'], 200);
        }

        try {
            $result = $swappr->processIncomingTransfer($event);
        } catch (InvalidArgumentException $exception) {
            Log::warning('[Swappr Webhook] Invalid CAD Interac transfer event.', [
                'ledger_entry_id' => $event['ledgerEntryId'] ?? null,
                'reason' => $exception->getMessage(),
            ]);

            return response()->json(['message' => $exception->getMessage()], 422);
        }

        if ($result['result'] === 'unmatched') {
            Log::warning('[Swappr Webhook] Transfer needs manual reconciliation.', [
                'ledger_entry_id' => $event['ledgerEntryId'],
                'amount_minor' => $event['amountMinor'],
                'net_minor' => $event['netMinor'],
                'reason' => $result['reason'],
                'pending_amounts_for_sender' => $result['pending_amounts'],
                'exact_amount_other_sender_count' => $result['exact_amount_other_sender_count'],
            ]);
        } else {
            Log::info('[Swappr Webhook] CAD Interac transfer handled.', [
                'ledger_entry_id' => $event['ledgerEntryId'],
                'result' => $result['result'],
                'transaction_id' => $result['transaction_id'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Webhook processed.'], 200);
    }
}
