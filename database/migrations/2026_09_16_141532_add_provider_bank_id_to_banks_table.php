<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            // OhentPay's internal numeric bank identifier (their "value" field).
            // Null for providers that don't use a separate internal id.
            $table->string('provider_bank_id')->nullable()->after('bank_code');
            $table->string('bank_nibss_code')->nullable()->after('provider_bank_id');
        });
    }

    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn(['provider_bank_id', 'bank_nibss_code']);
        });
    }
};