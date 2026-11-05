<?php

use App\Services\SwapprCadInteracService;

uses(Tests\TestCase::class);

beforeEach(function () {
    \Illuminate\Support\Facades\Schema::create('balances', function ($table) {
        $table->uuid('id')->primary();
        $table->string('user_id')->nullable();
        $table->string('personal_id')->nullable();
        $table->string('currency');
        $table->decimal('amount', 20, 2)->default(0);
        $table->string('mode')->nullable();
        $table->timestamps();
    });

    \Illuminate\Support\Facades\Schema::create('transactions_history', function ($table) {
        $table->uuid('id')->primary();
        $table->string('user_id')->nullable();
        $table->string('personal_id')->nullable();
        $table->string('balance_id')->nullable();
        $table->string('payment_provider')->nullable();
        $table->string('payment_method')->nullable();
        $table->string('currency')->nullable();
        $table->string('status')->nullable();
        $table->string('sender')->nullable();
        $table->string('reference')->nullable();
        $table->string('payment_reference')->nullable();
        $table->string('order_id')->nullable();
        $table->string('transaction_type')->nullable();
        $table->string('method')->nullable();
        $table->string('type')->nullable();
        $table->decimal('amount', 20, 2)->nullable();
        $table->decimal('fees', 20, 2)->nullable();
        $table->decimal('recipient_amount', 20, 2)->nullable();
        $table->string('mode')->nullable();
        $table->timestamps();
    });
});

test('Swappr webhook signature verifies the exact body and recent timestamp', function () {
    $secret = 'test-webhook-secret';
    $body = '{"event":"wallet_funded","rail":"interac","currency":"CAD"}';
    $now = 1_800_000_000;
    $timestamp = (string) $now;
    $signature = hash_hmac('sha256', $timestamp . '.' . $body, $secret);

    expect((new SwapprCadInteracService())->hasValidWebhookSignature(
        $body,
        "t={$timestamp},v1={$signature}",
        $secret,
        $now
    ))->toBeTrue();
});

test('Swappr webhook rejects a changed body and an expired signature', function () {
    $secret = 'test-webhook-secret';
    $body = '{"event":"wallet_funded","rail":"interac","currency":"CAD"}';
    $now = 1_800_000_000;
    $timestamp = (string) ($now - 301);
    $signature = hash_hmac('sha256', $timestamp . '.' . $body, $secret);
    $service = new SwapprCadInteracService();

    expect($service->hasValidWebhookSignature(
        $body . ' ',
        "t={$timestamp},v1={$signature}",
        $secret,
        $now
    ))->toBeFalse()
        ->and($service->hasValidWebhookSignature(
            $body,
            "t={$timestamp},v1={$signature}",
            $secret,
            $now
        ))->toBeFalse();
});

test('Swappr reports signature failure reasons without exposing signing material', function () {
    $service = new SwapprCadInteracService();
    $body = '{"event":"payout_paid","_test":true}';
    $timestamp = '1800000000';
    $signature = hash_hmac('sha256', $timestamp . '.' . $body, 'test-webhook-secret');

    expect($service->webhookSignatureFailureReason($body, '', 'test-webhook-secret', 1_800_000_000))
        ->toBe('signature_header_missing')
        ->and($service->webhookSignatureFailureReason(
            $body,
            "t={$timestamp},v1={$signature}",
            'different-secret',
            1_800_000_000
        ))->toBe('signature_mismatch');
});

test('Swappr CAD Interac transfers credit the referenced pending wallet once', function () {
    $balance = \App\Models\Balance::create([
        'user_id' => 'user-123',
        'currency' => 'CAD',
        'amount' => 0,
        'mode' => 'live',
    ]);

    $transaction = \App\Models\TransactionHistory::create([
        'user_id' => 'user-123',
        'balance_id' => $balance->id,
        'payment_provider' => 'swappr',
        'payment_method' => 'manual_interac',
        'currency' => 'CAD',
        'status' => 'pending',
        'sender' => 'payer@example.ca',
        'reference' => 'SWP-REFERENCE-123',
        'amount' => 100,
        'fees' => 3,
        'recipient_amount' => 97,
        'mode' => 'live',
    ]);

    $event = [
        'ledgerEntryId' => 'ledger-entry-123',
        'amountMinor' => '10000',
        'netMinor' => '10000',
        'currency' => 'CAD',
        'rail' => 'interac',
        'sender' => ['interac_email' => 'payer@example.ca'],
        'narration' => 'Flovide top-up SWP-REFERENCE-123',
    ];
    $service = new SwapprCadInteracService();

    expect($service->processIncomingTransfer($event)['result'])->toBe('credited')
        ->and($transaction->fresh()->status)->toBe('success')
        ->and((float) $transaction->fresh()->recipient_amount)->toBe(100.0)
        ->and((float) $balance->fresh()->amount)->toBe(100.0)
        ->and($service->processIncomingTransfer($event)['result'])->toBe('duplicate')
        ->and((float) $balance->fresh()->amount)->toBe(100.0);
});

test('Swappr credits the full gross amount when its processing fee reduces net proceeds', function () {
    $balance = \App\Models\Balance::create([
        'user_id' => 'user-123',
        'currency' => 'CAD',
        'amount' => 0,
        'mode' => 'live',
    ]);

    $transaction = \App\Models\TransactionHistory::create([
        'user_id' => 'user-123',
        'balance_id' => $balance->id,
        'payment_provider' => 'swappr',
        'payment_method' => 'manual_interac',
        'currency' => 'CAD',
        'status' => 'pending',
        'sender' => 'payer@example.ca',
        'reference' => 'SWP-REFERENCE-848',
        'amount' => 8.48,
        'fees' => 1,
        'recipient_amount' => 7.48,
        'mode' => 'live',
    ]);

    $result = (new SwapprCadInteracService())->processIncomingTransfer([
        'ledgerEntryId' => 'ledger-entry-net-fee',
        'amountMinor' => '848',
        'feeMinor' => '151',
        'netMinor' => '697',
        'currency' => 'CAD',
        'rail' => 'interac',
        'sender' => ['interac_email' => 'payer@example.ca'],
        'narration' => 'SWP-REFERENCE-848',
    ]);

    expect($result['result'])->toBe('credited')
        ->and((float) $transaction->fresh()->recipient_amount)->toBe(8.48)
        ->and((float) $balance->fresh()->amount)->toBe(8.48);
});

test('Swappr leaves ambiguous sender and amount matches pending', function () {
    $balance = \App\Models\Balance::create([
        'user_id' => 'user-123',
        'currency' => 'CAD',
        'amount' => 0,
        'mode' => 'live',
    ]);

    foreach (['SWP-REFERENCE-1', 'SWP-REFERENCE-2'] as $reference) {
        \App\Models\TransactionHistory::create([
            'user_id' => 'user-123',
            'balance_id' => $balance->id,
            'payment_provider' => 'swappr',
            'payment_method' => 'manual_interac',
            'currency' => 'CAD',
            'status' => 'pending',
            'sender' => 'payer@example.ca',
            'reference' => $reference,
            'amount' => 100,
            'fees' => 3,
            'recipient_amount' => 97,
            'mode' => 'live',
        ]);
    }

    $result = (new SwapprCadInteracService())->processIncomingTransfer([
        'ledgerEntryId' => 'ledger-entry-ambiguous',
        'amountMinor' => '10000',
        'netMinor' => '10000',
        'currency' => 'CAD',
        'rail' => 'interac',
        'sender' => ['interac_email' => 'payer@example.ca'],
        'narration' => '',
    ]);

    expect($result['result'])->toBe('unmatched')
        ->and($result['reason'])->toBe('multiple_sender_and_amount_matches')
        ->and(\App\Models\TransactionHistory::where('status', 'pending')->count())->toBe(2)
        ->and((float) $balance->fresh()->amount)->toBe(0.0);
});

test('Swappr reports when an event sender has a pending top-up for a different amount', function () {
    $balance = \App\Models\Balance::create([
        'user_id' => 'user-123',
        'currency' => 'CAD',
        'amount' => 0,
        'mode' => 'live',
    ]);

    \App\Models\TransactionHistory::create([
        'user_id' => 'user-123',
        'balance_id' => $balance->id,
        'payment_provider' => 'swappr',
        'payment_method' => 'wallet_autodeposit',
        'currency' => 'CAD',
        'status' => 'pending',
        'sender' => 'paymentsauto@flovide.com',
        'reference' => 'SWP-REFERENCE-100',
        'amount' => 100,
        'fees' => 3,
        'recipient_amount' => 97,
        'mode' => 'live',
    ]);

    $result = (new SwapprCadInteracService())->processIncomingTransfer([
        'ledgerEntryId' => 'ledger-entry-848',
        'amountMinor' => '848',
        'feeMinor' => '151',
        'netMinor' => '697',
        'currency' => 'CAD',
        'rail' => 'interac',
        'sender' => ['interac_email' => 'paymentsauto@flovide.com'],
        'narration' => 'Transaction Completed',
    ]);

    expect($result['result'])->toBe('unmatched')
        ->and($result['reason'])->toBe('no_pending_topup_with_exact_gross_amount')
        ->and($result['pending_amounts'])->toBe(['100.00'])
        ->and(\App\Models\TransactionHistory::where('status', 'pending')->count())->toBe(1)
        ->and((float) $balance->fresh()->amount)->toBe(0.0);
});

test('Swappr reports exact amount top-ups with a different sender without crediting them', function () {
    $balance = \App\Models\Balance::create([
        'user_id' => 'user-123',
        'currency' => 'CAD',
        'amount' => 0,
        'mode' => 'live',
    ]);

    \App\Models\TransactionHistory::create([
        'user_id' => 'user-123',
        'balance_id' => $balance->id,
        'payment_provider' => 'swappr',
        'payment_method' => 'manual_interac',
        'currency' => 'CAD',
        'status' => 'pending',
        'sender' => 'another@example.ca',
        'reference' => 'SWP-OTHER-SENDER',
        'amount' => 8.48,
        'fees' => 0,
        'recipient_amount' => 8.48,
        'mode' => 'live',
    ]);

    $result = (new SwapprCadInteracService())->processIncomingTransfer([
        'ledgerEntryId' => 'ledger-entry-sender-mismatch',
        'amountMinor' => '848',
        'feeMinor' => '151',
        'netMinor' => '697',
        'currency' => 'CAD',
        'rail' => 'interac',
        'sender' => ['interac_email' => 'paymentsauto@flovide.com'],
        'narration' => 'Transaction Completed',
    ]);

    expect($result['result'])->toBe('unmatched')
        ->and($result['reason'])->toBe('pending_topup_amount_found_but_sender_differs')
        ->and($result['exact_amount_other_sender_count'])->toBe(1)
        ->and(\App\Models\TransactionHistory::where('status', 'pending')->count())->toBe(1)
        ->and((float) $balance->fresh()->amount)->toBe(0.0);
});

test('an admin can reconcile a verified transfer from a different sender email', function () {
    $balance = \App\Models\Balance::create([
        'user_id' => 'user-123',
        'currency' => 'CAD',
        'amount' => 0,
        'mode' => 'live',
    ]);

    $transaction = \App\Models\TransactionHistory::create([
        'user_id' => 'user-123',
        'balance_id' => $balance->id,
        'payment_provider' => 'swappr',
        'payment_method' => 'manual_interac',
        'currency' => 'CAD',
        'status' => 'pending',
        'sender' => 'registered@example.ca',
        'reference' => 'SWP-REFERENCE-MANUAL',
        'amount' => 50,
        'fees' => 3,
        'recipient_amount' => 47,
        'mode' => 'live',
    ]);

    (new SwapprCadInteracService())->confirmManually(
        (string) $transaction->id,
        'ledger-entry-manual',
        'actual-sender@example.ca',
        50,
        1
    );

    expect($transaction->fresh()->status)->toBe('success')
        ->and($transaction->fresh()->sender)->toBe('actual-sender@example.ca')
        ->and((float) $transaction->fresh()->recipient_amount)->toBe(50.0)
        ->and((float) $balance->fresh()->amount)->toBe(50.0);
});

test('root Swappr webhook URLs reach the handler without CSRF tokens', function () {
    config()->set('services.swappr.webhook_secret', null);

    $this->postJson('/webhooks/swappr')->assertStatus(503);
    $this->postJson('/webhooks/swapper')->assertStatus(503);
});

test('Swappr acknowledges signed test events without processing business logic', function () {
    $secret = 'test-webhook-secret';
    config()->set('services.swappr.webhook_secret', $secret);
    $body = json_encode([
        'data' => [
            'currency' => 'NGN',
            'bank_code' => '044',
            'reference' => 'test_1793519337096',
            'timestamp' => '2026-11-01T07:48:57.096Z',
            'amount_minor' => '100000',
            'recipient_name' => 'TEST RECIPIENT',
        ],
        '_test' => true,
        'event' => 'payout_paid',
    ], JSON_UNESCAPED_SLASHES);
    $timestamp = (string) now()->timestamp;
    $signature = hash_hmac('sha256', $timestamp . '.' . $body, $secret);

    $this->call('POST', '/webhooks/swappr', [], [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_SWAPPR_SIGNATURE' => "t={$timestamp},v1={$signature}",
        'HTTP_X_SWAPPR_EVENT' => 'payout_paid',
    ], $body)
        ->assertOk()
        ->assertJson(['message' => 'Test webhook acknowledged.']);
});
