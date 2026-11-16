<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_webhook_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('business_id')->constrained('users')->cascadeOnDelete();
            $table->uuid('business_transaction_id')->nullable()->index();
            $table->string('business_crypto_wallet_transaction_id')->nullable();
            $table->string('type', 20)->index();
            $table->string('mode', 10)->index();
            $table->text('webhook_url');
            $table->json('payload');
            $table->json('response')->nullable();
            $table->string('status', 30)->nullable();
            $table->string('delivery_status', 20)->default('PENDING');
            $table->timestamps();

            $table->index(['business_id', 'created_at']);
        });

        Schema::create('business_webhook_log_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('webhook_log_id')
                ->constrained('business_webhook_logs')
                ->cascadeOnDelete();
            $table->json('metadata')->nullable();
            $table->json('headers')->nullable();
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->json('response')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['webhook_log_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_webhook_log_histories');
        Schema::dropIfExists('business_webhook_logs');
    }
};
