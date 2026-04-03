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
    Schema::table('exchange_rates', function (Blueprint $table) {
        $table->foreignId('from_currency_id')->nullable()->after('id');
        $table->foreignId('to_currency_id')->nullable()->after('from_currency_id');
        // $table->decimal('rate', 15, 6)->after('to_currency_id');
        // $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            //
        });
    }
};
