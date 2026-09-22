<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_provider_currencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_provider_id')->constrained()->cascadeOnDelete();
            $table->string('currency', 6);                 // e.g. KES, NGN, UGX, CAD
            $table->string('transfer_method')->nullable();  // 'bank', 'mobile', or null = both
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->unique(['payment_provider_id', 'currency', 'transfer_method'], 'provider_currency_method_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_provider_currencies');
    }
};