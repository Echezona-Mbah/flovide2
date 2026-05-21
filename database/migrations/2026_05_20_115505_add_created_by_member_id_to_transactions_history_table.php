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
           $table->unsignedBigInteger('created_by_member_id')->nullable()->after('user_id');
            $table->index('created_by_member_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions_history', function (Blueprint $table) {
            $table->dropIndex(['created_by_member_id']);
            $table->dropColumn('created_by_member_id');
        });
    }
};
