<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_providers', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();      // 'pivot', 'payaza', 'app_mobile', 'ohentpay', 'blaaiz_interac'
            $table->string('name');                // Display name shown in admin
            $table->boolean('is_enabled')->default(false);
            $table->unsignedInteger('priority')->default(100); // lower = tried first when multiple providers support a currency
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_providers');
    }
};