<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions_history', function (Blueprint $table) {
            $table->decimal('fees', 20, 2)->nullable();
            $table->string('to_currency', 10)->nullable();
            $table->uuid('balance_id')->nullable();
            $table->uuid('virtual_account_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('failure_reason')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('beneficias_id')->nullable();
            $table->string('recipient_country', 5)->nullable();
            $table->string('recipient_default_reference')->nullable();
            $table->string('recipient_alias')->nullable();
            $table->string('recipient_type')->nullable();
            $table->timestamp('recipient_created_at')->nullable();
            $table->string('recipient_account_name')->nullable();
            $table->string('recipient_sort_code')->nullable();
            $table->string('recipient_account_number')->nullable();
            $table->string('recipient_bank_name')->nullable();
            $table->string('recipient_bank_currency')->nullable();
            $table->bigInteger('exchange_rate')->nullable();
            $table->decimal('single_rate', 20, 8)->nullable();
            $table->timestamp('created_at_external')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions_history', function (Blueprint $table) {
            $table->dropColumn([
                'fees',
                'to_currency',
                'balance_id',
                'virtual_account_id',
                'order_id',
                'payment_reference',
                'failure_reason',
                'transaction_type',
                'payment_method',
                'beneficias_id',
                'recipient_country',
                'recipient_default_reference',
                'recipient_alias',
                'recipient_type',
                'recipient_created_at',
                'recipient_account_name',
                'recipient_sort_code',
                'recipient_account_number',
                'recipient_bank_name',
                'recipient_bank_currency',
                'exchange_rate',
                'single_rate',
                'created_at_external',
            ]);
        });
    }
};
