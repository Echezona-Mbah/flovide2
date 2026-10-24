<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('transactions_history', 'fee_balance_id')) {
            Schema::table('transactions_history', function (Blueprint $table) {
                $table->uuid('fee_balance_id')->nullable()->after('balance_id');
            });
        }
    }

    public function down(): void
    {
        // Keep fee-balance references if this migration is rolled back.
    }
};
