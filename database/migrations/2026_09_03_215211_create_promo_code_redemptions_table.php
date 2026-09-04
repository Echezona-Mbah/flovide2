<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_code_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained('promo_codes')->cascadeOnDelete();
            $table->string('transaction_history_id')->nullable();
            $table->enum('redeemer_type', ['business', 'personal']);
            $table->string('redeemer_id');
            $table->decimal('transaction_amount', 18, 4);
            $table->string('transaction_currency', 10);
            $table->decimal('fee_waived', 18, 4)->default(0);
            $table->decimal('reward_amount', 18, 4)->default(0);
            $table->string('reward_currency', 10)->nullable();
            $table->timestamps();

            $table->index(['redeemer_type', 'redeemer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_code_redemptions');
    }
};