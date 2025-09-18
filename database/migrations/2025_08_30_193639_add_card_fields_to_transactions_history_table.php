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
        $table->string('card_number')->nullable(); 
        $table->string('expiry_month')->nullable();
        $table->string('expiry_year')->nullable();
        $table->string('cvv')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions_history', function (Blueprint $table) {
             $table->dropColumn(['card_number', 'expiry_month', 'expiry_year', 'cvv']);
        });
    }
};
