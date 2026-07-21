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
           $table->string('interac_email')->nullable()->after('payment_reference');
            $table->string('interac_first_name')->nullable()->after('interac_email');
            $table->string('interac_last_name')->nullable()->after('interac_first_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_histories', function (Blueprint $table) {
                    $table->dropColumn(['interac_email', 'interac_first_name', 'interac_last_name']);

        });
    }
};
