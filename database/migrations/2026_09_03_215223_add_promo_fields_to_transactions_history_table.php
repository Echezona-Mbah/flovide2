<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions_history', function (Blueprint $table) {
            $table->string('promo_code')->nullable()->after('reference');
            $table->unsignedBigInteger('promo_code_id')->nullable()->after('promo_code');
            $table->decimal('promo_fee_waived', 18, 4)->nullable()->after('promo_code_id');
        });
    }

    public function down(): void
    {
        Schema::table('transactions_history', function (Blueprint $table) {
            $table->dropColumn(['promo_code', 'promo_code_id', 'promo_fee_waived']);
        });
    }
};