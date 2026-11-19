<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\TestModeController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminTestBalanceFundingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('business_name')->nullable();
        });

        Schema::create('balances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('currency');
            $table->decimal('amount', 20, 2)->default(0);
            $table->string('mode')->default('live');
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        });

        Schema::create('transactions_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('personal_id')->nullable();
            $table->string('type');
            $table->string('method')->nullable();
            $table->decimal('amount', 20, 2);
            $table->string('currency');
            $table->string('balance_id')->nullable();
            $table->string('status');
            $table->string('reference')->unique();
            $table->string('payment_provider')->nullable();
            $table->string('mode')->default('live');
            $table->string('sender')->nullable();
            $table->string('recipient_account_name')->nullable();
            $table->decimal('total_amount', 20, 2)->nullable();
            $table->decimal('fees', 20, 2)->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert(['id' => 1, 'name' => 'Test Merchant', 'business_name' => 'Merchant']);
        DB::table('balances')->insert([
            [
                'id' => '11111111-1111-4111-8111-111111111111',
                'user_id' => 1,
                'name' => 'Test CAD',
                'currency' => 'CAD',
                'amount' => 5,
                'mode' => 'test',
                'is_locked' => false,
            ],
            [
                'id' => '22222222-2222-4222-8222-222222222222',
                'user_id' => 1,
                'name' => 'Live CAD',
                'currency' => 'CAD',
                'amount' => 20,
                'mode' => 'live',
                'is_locked' => false,
            ],
        ]);
    }

    public function test_it_adds_funds_only_to_the_business_test_balance_and_records_a_credit(): void
    {
        $request = Request::create('/admin/testmode/1', 'POST', [
            'amount' => '25.50',
            'note' => 'Test funds',
        ]);

        app(TestModeController::class)->addTestMoney(
            $request,
            1,
            '11111111-1111-4111-8111-111111111111'
        );

        $this->assertSame(30.5, (float) DB::table('balances')
            ->where('id', '11111111-1111-4111-8111-111111111111')
            ->value('amount'));
        $this->assertSame(20.0, (float) DB::table('balances')
            ->where('id', '22222222-2222-4222-8222-222222222222')
            ->value('amount'));
        $this->assertDatabaseHas('transactions_history', [
            'user_id' => 1,
            'balance_id' => '11111111-1111-4111-8111-111111111111',
            'type' => 'credit',
            'mode' => 'test',
            'amount' => 25.5,
            'currency' => 'CAD',
            'payment_reference' => 'Test funds',
        ]);
    }
}
