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
        Schema::table('remita', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('subaccount_id')->nullable()->after('service_type');

            // If you want to enforce relationship
            $table->foreign('subaccount_id')
                ->references('id')
                ->on('subaccounts')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remita', function (Blueprint $table) {
            //
            $table->dropForeign(['subaccount_id']);
            $table->dropColumn('subaccount_id');
        });
    }
};
