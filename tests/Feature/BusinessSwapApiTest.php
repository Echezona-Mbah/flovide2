<?php

namespace Tests\Feature;

use App\Http\Controllers\API\V1\SwapController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BusinessSwapApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('business_name')->nullable();
        });

        Schema::create('webhook_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('live_public_key')->nullable();
            $table->string('live_secret_key')->nullable();
            $table->string('test_public_key')->nullable();
            $table->string('test_secret_key')->nullable();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code');
        });

        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_currency_id');
            $table->unsignedBigInteger('to_currency_id');
            $table->decimal('rate', 20, 6);
            $table->timestamps();
        });

        Schema::create('balances', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('currency');
            $table->string('mode');
            $table->decimal('amount', 20, 2);
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        });

        Schema::create('transactions_history', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('mode');
            $table->string('type');
            $table->string('method');
            $table->decimal('amount', 15, 2);
            $table->string('currency');
            $table->string('balance_id');
            $table->boolean('api_source')->default(false);
            $table->string('status');
            $table->string('reference')->unique();
            $table->string('recipient_country')->nullable();
            $table->string('sender_id')->nullable();
            $table->string('sender')->nullable();
            $table->string('recipient_account_number')->nullable();
            $table->string('recipient_account_name')->nullable();
            $table->string('to_currency')->nullable();
            $table->decimal('recipient_amount', 20, 2)->nullable();
            $table->decimal('exchange_rate', 20, 6)->nullable();
            $table->decimal('single_rate', 20, 8)->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'id' => 1,
            'name' => 'API Owner',
            'business_name' => 'Test Business',
        ]);
        DB::table('webhook_settings')->insert([
            'id' => 1,
            'user_id' => 1,
            'test_public_key' => 'pk_test_example',
            'test_secret_key' => 'sk_test_example',
            'live_public_key' => 'pk_live_example',
            'live_secret_key' => 'sk_live_example',
        ]);
        DB::table('currencies')->insert([
            ['id' => 1, 'code' => 'CAD'],
            ['id' => 2, 'code' => 'NGN'],
        ]);
        DB::table('exchange_rates')->insert([
            'from_currency_id' => 1,
            'to_currency_id' => 2,
            'rate' => 750,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('balances')->insert([
            ['id' => 'test-cad', 'user_id' => 1, 'currency' => 'CAD', 'mode' => 'test', 'amount' => 20, 'is_locked' => false],
            ['id' => 'test-ngn', 'user_id' => 1, 'currency' => 'NGN', 'mode' => 'test', 'amount' => 100, 'is_locked' => false],
            ['id' => 'live-cad', 'user_id' => 1, 'currency' => 'CAD', 'mode' => 'live', 'amount' => 500, 'is_locked' => false],
            ['id' => 'live-ngn', 'user_id' => 1, 'currency' => 'NGN', 'mode' => 'live', 'amount' => 1000, 'is_locked' => false],
        ]);
    }

    public function test_it_swaps_business_funds_using_the_test_environment_balances(): void
    {
        $response = $this->sendSwap();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('EXCHANGE_SUCCESS', $response->getData(true)['code']);
        $this->assertSame('test', $response->getData(true)['mode']);
        $this->assertEquals(7500, $response->getData(true)['data']['converted']);
        $this->assertSame(10.0, (float) DB::table('balances')->where('id', 'test-cad')->value('amount'));
        $this->assertSame(7600.0, (float) DB::table('balances')->where('id', 'test-ngn')->value('amount'));
        $this->assertSame(500.0, (float) DB::table('balances')->where('id', 'live-cad')->value('amount'));
        $this->assertSame('test', DB::table('transactions_history')->value('mode'));
        $this->assertTrue((bool) DB::table('transactions_history')->value('api_source'));
    }

    public function test_it_rejects_insufficient_funds_without_changing_balances(): void
    {
        $response = $this->sendSwap(['amount' => 25]);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame('INSUFFICIENT_BALANCE', $response->getData(true)['code']);
        $this->assertSame(20.0, (float) DB::table('balances')->where('id', 'test-cad')->value('amount'));
        $this->assertSame(0, DB::table('transactions_history')->count());
    }

    public function test_it_rejects_live_keys_on_the_test_endpoint(): void
    {
        $response = $this->sendSwap([], 'pk_live_example', 'sk_live_example');

        $this->assertSame(403, $response->getStatusCode());
        $this->assertSame('INVALID_KEY_MODE', $response->getData(true)['code']);
        $this->assertSame(0, DB::table('transactions_history')->count());
    }

    private function sendSwap(array $overrides = [], string $publicKey = 'pk_test_example', string $secretKey = 'sk_test_example')
    {
        $request = Request::create('/api/test/v1/swap', 'POST', array_merge([
            'from_currency' => 'CAD',
            'to_currency' => 'NGN',
            'amount' => 10,
        ], $overrides), [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_X_PUBLIC_KEY' => $publicKey,
            'HTTP_X_SECRET_KEY' => $secretKey,
        ]);

        return app(SwapController::class)->store($request);
    }
}
