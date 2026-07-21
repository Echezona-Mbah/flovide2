<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhook_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('live_secret_key')->nullable();
            $table->string('live_public_key')->nullable();
            $table->json('live_ip_whitelist')->nullable();
            $table->text('live_callback_url')->nullable();
            $table->text('live_webhook_url')->nullable();

            $table->string('test_secret_key')->nullable();
            $table->string('test_public_key')->nullable();
            $table->json('test_ip_whitelist')->nullable();
            $table->text('test_callback_url')->nullable();
            $table->text('test_webhook_url')->nullable();

            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_settings');
    }
};
