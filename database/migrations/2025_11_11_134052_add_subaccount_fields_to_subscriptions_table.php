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
        Schema::table('subscriptions', function (Blueprint $table) {
                  $table->string('subaccount_id')->nullable()->after('payment_reference');
            $table->string('subaccount')->nullable();
            $table->string('subaccount_name')->nullable();
            $table->string('subaccount_number')->nullable();
            $table->decimal('percentage', 5, 2)->nullable()->default(0); // e.g. 20.00 means 20%
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'subaccount_id',
                'subaccount',
                'subaccount_name',
                'subaccount_number',
                'percentage',
            ]);
        });
    }
};
