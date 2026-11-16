<?php

use App\Models\TransactionHistory;
use App\Models\WebhookSetting;
use App\Http\Controllers\API\V1\WebhookController;
use App\Services\BusinessApiWebhookService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class);

beforeEach(function () {
    Schema::dropIfExists('business_webhook_log_histories');
    Schema::dropIfExists('business_webhook_logs');
    Schema::dropIfExists('webhook_settings');
    Schema::dropIfExists('transactions_history');

    Schema::create('webhook_settings', function ($table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('live_public_key')->nullable();
        $table->string('live_secret_key')->nullable();
        $table->string('test_public_key')->nullable();
        $table->string('test_secret_key')->nullable();
        $table->text('live_collection_url')->nullable();
        $table->text('live_payout_url')->nullable();
        $table->text('test_collection_url')->nullable();
        $table->text('test_payout_url')->nullable();
        $table->timestamps();
    });

    Schema::create('transactions_history', function ($table) {
        $table->uuid('id')->primary();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->string('type')->nullable();
        $table->string('transaction_type')->nullable();
        $table->string('status')->nullable();
        $table->string('reference')->nullable();
        $table->decimal('amount', 12, 2)->nullable();
        $table->decimal('fees', 12, 2)->nullable();
        $table->decimal('recipient_amount', 12, 2)->nullable();
        $table->string('currency')->nullable();
        $table->string('mode')->nullable();
        $table->string('recipient_account_number')->nullable();
        $table->string('recipient_account_name')->nullable();
        $table->string('recipient_bank_name')->nullable();
        $table->string('payment_method')->nullable();
        $table->boolean('api_source')->default(false);
        $table->timestamps();
    });

    Schema::create('business_webhook_logs', function ($table) {
        $table->uuid('id')->primary();
        $table->unsignedBigInteger('business_id');
        $table->uuid('business_transaction_id')->nullable();
        $table->string('business_crypto_wallet_transaction_id')->nullable();
        $table->string('type');
        $table->string('mode');
        $table->text('webhook_url');
        $table->json('payload');
        $table->json('response')->nullable();
        $table->string('status')->nullable();
        $table->string('delivery_status');
        $table->timestamps();
    });

    Schema::create('business_webhook_log_histories', function ($table) {
        $table->uuid('id')->primary();
        $table->uuid('webhook_log_id');
        $table->json('metadata')->nullable();
        $table->json('headers')->nullable();
        $table->unsignedSmallInteger('http_status')->nullable();
        $table->json('response')->nullable();
        $table->timestamp('created_at')->useCurrent();
    });
});

it('delivers and records a signed collection webhook', function () {
    WebhookSetting::create([
        'user_id' => 42,
        'live_secret_key' => 'live-secret',
        'live_collection_url' => 'https://merchant.example/hooks/collection',
    ]);

    $transaction = TransactionHistory::create([
        'id' => '019e8eb3-29cc-7011-b742-ca8bf459143a',
        'user_id' => 42,
        'transaction_type' => 'payment',
        'type' => 'credit',
        'status' => 'success',
        'reference' => 'COLLECTION-REF',
        'amount' => 200,
        'fees' => 1.3,
        'recipient_amount' => 198.7,
        'currency' => 'CAD',
    ]);

    Http::fake([
        'merchant.example/hooks/collection' => Http::response(['message' => 'Webhook processed'], 200),
    ]);

    $log = app(BusinessApiWebhookService::class)->dispatch($transaction, 'COLLECTION', 'live');

    expect($log)->not->toBeNull()
        ->and($log->delivery_status)->toBe('SUCCESSFUL')
        ->and($log->payload['message'])->toBe('Transaction Completed')
        ->and($log->payload['event'])->toBe('transaction.success')
        ->and($log->payload['transaction_status'])->toBe('SUCCESSFUL')
        ->and($log->payload['transaction_amount_without_fee'])->toBe(198.7)
        ->and($log->response)->toBe(['message' => 'Webhook processed'])
        ->and($log->histories)->toHaveCount(1)
        ->and($log->histories[0]->http_status)->toBe(200);

    Http::assertSent(fn (Request $request) =>
        $request->method() === 'POST'
        && $request->url() === 'https://merchant.example/hooks/collection'
        && $request->hasHeader('X-Flovide-Event', 'transaction.success')
        && $request->hasHeader('X-Flovide-Signature', hash_hmac('sha256', $request->body(), 'live-secret'))
    );
});

it('does not create a webhook log when the business has no collection URL', function () {
    WebhookSetting::create([
        'user_id' => 42,
        'live_secret_key' => 'live-secret',
    ]);

    $transaction = TransactionHistory::create([
        'id' => '019e8eb3-29cc-7011-b742-ca8bf459143a',
        'user_id' => 42,
        'type' => 'credit',
        'status' => 'pending',
        'amount' => 10,
        'currency' => 'CAD',
    ]);

    Http::fake();

    expect(app(BusinessApiWebhookService::class)->dispatch($transaction, 'COLLECTION', 'live'))->toBeNull();
    Http::assertNothingSent();
});

it('returns logs only for the environment authenticated by the API key', function () {
    WebhookSetting::create([
        'user_id' => 42,
        'live_public_key' => 'pk_live_test',
        'live_secret_key' => 'sk_live_test',
        'test_public_key' => 'pk_test_test',
        'test_secret_key' => 'sk_test_test',
        'live_collection_url' => 'https://merchant.example/hooks/collection',
        'test_collection_url' => 'https://merchant.example/test/hooks/collection',
    ]);

    $transaction = TransactionHistory::create([
        'id' => '019e8eb3-29cc-7011-b742-ca8bf459143a',
        'user_id' => 42,
        'type' => 'credit',
        'status' => 'pending',
        'amount' => 10,
        'currency' => 'CAD',
    ]);

    Http::fake(['merchant.example/*' => Http::response(['message' => 'received'], 200)]);
    app(BusinessApiWebhookService::class)->dispatch($transaction, 'COLLECTION', 'live');
    app(BusinessApiWebhookService::class)->dispatch($transaction, 'COLLECTION', 'test');

    $request = Illuminate\Http\Request::create('/api/v1/webhooks/logs', 'GET', ['type' => 'COLLECTION'], [], [], [
        'HTTP_X_PUBLIC_KEY' => 'pk_live_test',
        'HTTP_X_SECRET_KEY' => 'sk_live_test',
    ]);
    $response = app(WebhookController::class)->logs($request);
    $data = $response->getData(true)['data'];

    expect($response->getStatusCode())->toBe(200)
        ->and($data['total'])->toBe(1)
        ->and($data['data'][0]['type'])->toBe('COLLECTION')
        ->and($data['data'][0]['business_id'])->toBe('42')
        ->and($data['data'][0]['webhook_log_histories'])->toHaveCount(1);
});

it('sends payout events to the separate payout URL with recipient details', function () {
    WebhookSetting::create([
        'user_id' => 42,
        'live_secret_key' => 'live-secret',
        'live_payout_url' => 'https://merchant.example/hooks/payout',
    ]);

    $transaction = TransactionHistory::create([
        'id' => '019e8eb3-29cc-7011-b742-ca8bf459143a',
        'user_id' => 42,
        'type' => 'withdrawal',
        'status' => 'pending',
        'reference' => 'PAYOUT-REF',
        'amount' => 100,
        'fees' => 2,
        'recipient_amount' => 98,
        'currency' => 'CAD',
        'recipient_account_number' => '1234567890',
        'recipient_account_name' => 'Example Recipient',
        'recipient_bank_name' => 'Example Bank',
    ]);

    Http::fake(['merchant.example/hooks/payout' => Http::response(['accepted' => true], 202)]);

    $log = app(BusinessApiWebhookService::class)->dispatch($transaction, 'PAYOUT', 'live');

    expect($log->type)->toBe('PAYOUT')
        ->and($log->webhook_url)->toBe('https://merchant.example/hooks/payout')
        ->and($log->payload['event'])->toBe('payout.created')
        ->and($log->payload['recipient']['account_name'])->toBe('Example Recipient')
        ->and($log->delivery_status)->toBe('SUCCESSFUL');

    Http::assertSent(fn (Request $request) =>
        $request->url() === 'https://merchant.example/hooks/payout'
        && $request->hasHeader('X-Flovide-Event', 'payout.created')
    );
});

it('sends a terminal webhook when an API transaction status changes', function () {
    WebhookSetting::create([
        'user_id' => 42,
        'live_secret_key' => 'live-secret',
        'live_collection_url' => 'https://merchant.example/hooks/collection',
    ]);

    $transaction = TransactionHistory::create([
        'id' => '019e8eb3-29cc-7011-b742-ca8bf459143a',
        'user_id' => 42,
        'type' => 'credit',
        'status' => 'pending',
        'amount' => 10,
        'currency' => 'CAD',
        'mode' => 'live',
        'api_source' => true,
    ]);

    Http::fake(['merchant.example/*' => Http::response(['received' => true], 200)]);

    $transaction->status = 'success';
    $transaction->save();

    expect(\App\Models\BusinessWebhookLog::count())->toBe(1)
        ->and(\App\Models\BusinessWebhookLog::first()->payload['event'])->toBe('transaction.success')
        ->and(\App\Models\BusinessWebhookLog::first()->payload['message'])->toBe('Transaction Completed');

    Http::assertSent(fn (Request $request) =>
        $request->hasHeader('X-Flovide-Event', 'transaction.success')
    );
});

it('replays the original event and records a second attempt', function () {
    WebhookSetting::create([
        'user_id' => 42,
        'live_secret_key' => 'live-secret',
        'live_collection_url' => 'https://merchant.example/hooks/collection',
    ]);

    $transaction = TransactionHistory::create([
        'id' => '019e8eb3-29cc-7011-b742-ca8bf459143a',
        'user_id' => 42,
        'type' => 'credit',
        'status' => 'pending',
        'reference' => 'REPLAY-REF',
        'amount' => 10,
        'currency' => 'CAD',
    ]);

    Http::fakeSequence()
        ->push(['message' => 'Temporary failure'], 500)
        ->push(['message' => 'Webhook processed'], 200);

    $service = app(BusinessApiWebhookService::class);
    $log = $service->dispatch($transaction, 'COLLECTION', 'live');
    $eventId = $log->payload['event_id'];
    $replayedLog = $service->replay($log);

    expect($replayedLog->delivery_status)->toBe('SUCCESSFUL')
        ->and($replayedLog->payload['event_id'])->toBe($eventId)
        ->and($replayedLog->histories)->toHaveCount(2)
        ->and($replayedLog->histories[1]->metadata['attempt'])->toBe(2)
        ->and($replayedLog->histories[1]->http_status)->toBe(200);

    Http::assertSentCount(2);
});
