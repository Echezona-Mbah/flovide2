<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\UserTransactionHistoryController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminBusinessWebhookActivityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
        });

        Schema::create('business_webhook_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('business_id');
            $table->string('type');
            $table->string('mode');
            $table->text('webhook_url');
            $table->json('payload');
            $table->json('response')->nullable();
            $table->string('status')->nullable();
            $table->string('delivery_status');
            $table->timestamps();
        });

        Schema::create('business_webhook_log_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('webhook_log_id');
            $table->json('metadata')->nullable();
            $table->json('headers')->nullable();
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->json('response')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        DB::table('users')->insert(['id' => 7, 'name' => 'Example Business']);
    }

    public function test_it_returns_business_webhook_counts_events_and_delivery_attempts(): void
    {
        DB::table('business_webhook_logs')->insert([
            'id' => '11111111-1111-4111-8111-111111111111',
            'business_id' => 7,
            'type' => 'PAYOUT',
            'mode' => 'live',
            'webhook_url' => 'https://business.example/webhook',
            'payload' => json_encode([
                'event' => 'transaction.pending',
                'transaction_reference' => 'TX-123',
                'transaction_status' => 'PENDING',
            ]),
            'response' => json_encode(['message' => 'received']),
            'status' => 'PENDING',
            'delivery_status' => 'SUCCESSFUL',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('business_webhook_logs')->insert([
            'id' => '22222222-2222-4222-8222-222222222222',
            'business_id' => 7,
            'type' => 'PAYOUT',
            'mode' => 'live',
            'webhook_url' => 'https://business.example/webhook',
            'payload' => json_encode([
                'event' => 'transaction.failed',
                'transaction_reference' => 'TX-456',
                'transaction_status' => 'FAILED',
            ]),
            'response' => json_encode(['message' => 'unavailable']),
            'status' => 'FAILED',
            'delivery_status' => 'FAILED',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('business_webhook_log_histories')->insert([
            [
                'id' => '33333333-3333-4333-8333-333333333333',
                'webhook_log_id' => '11111111-1111-4111-8111-111111111111',
                'metadata' => json_encode(['attempt' => 1]),
                'http_status' => 200,
                'response' => json_encode(['message' => 'received']),
                'created_at' => now(),
            ],
            [
                'id' => '44444444-4444-4444-8444-444444444444',
                'webhook_log_id' => '22222222-2222-4222-8222-222222222222',
                'metadata' => json_encode(['attempt' => 1]),
                'http_status' => 503,
                'response' => json_encode(['message' => 'unavailable']),
                'created_at' => now(),
            ],
            [
                'id' => '55555555-5555-4555-8555-555555555555',
                'webhook_log_id' => '22222222-2222-4222-8222-222222222222',
                'metadata' => json_encode(['attempt' => 2]),
                'http_status' => 200,
                'response' => json_encode(['message' => 'received']),
                'created_at' => now(),
            ],
        ]);

        $response = app(UserTransactionHistoryController::class)->webhookActivity(7);
        $data = $response->getData(true);

        $this->assertTrue($data['available']);
        $this->assertSame(2, $data['counts']['events']);
        $this->assertSame(1, $data['counts']['successful']);
        $this->assertSame(1, $data['counts']['failed']);
        $this->assertSame(0, $data['counts']['pending']);
        $this->assertSame(3, $data['counts']['attempts']);
        $pendingEvent = collect($data['events'])->firstWhere('reference', 'TX-123');
        $failedEvent = collect($data['events'])->firstWhere('reference', 'TX-456');
        $this->assertSame('transaction.pending', $pendingEvent['event']);
        $this->assertCount(2, $failedEvent['attempts']);
    }
}
