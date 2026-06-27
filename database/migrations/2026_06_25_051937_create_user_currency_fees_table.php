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
          Schema::create('user_currency_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('currency', 10);
            
            // Collection
            $table->boolean('collection_enabled')->default(false);
            $table->decimal('collection_balance', 20, 4)->default(0);
            $table->decimal('collection_percent', 8, 4)->default(0);
            $table->decimal('collection_fixed', 10, 2)->default(0);
            $table->decimal('collection_min', 20, 4)->default(0);
            $table->decimal('collection_max', 20, 4)->default(0);

            // Payout
            $table->boolean('payout_enabled')->default(false);
            $table->decimal('payout_balance', 20, 4)->default(0);
            $table->decimal('payout_percent', 8, 4)->default(0);
            $table->decimal('payout_fixed', 10, 2)->default(0);
            $table->decimal('payout_min', 20, 4)->default(0);
            $table->decimal('payout_max', 20, 4)->default(0);

            $table->timestamps();

            $table->unique(['user_id', 'currency']); // one row per user per currency
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_currency_fees');
    }
};
