<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interac_autodeposits', function (Blueprint $table) {
            // Drop the FK first — MySQL won't let us drop the unique index
            // while it's the one backing the foreign key constraint.
            $table->dropForeign(['balance_id']);
        });

        Schema::table('interac_autodeposits', function (Blueprint $table) {
            $table->dropUnique(['balance_id']);
            $table->index('balance_id'); // plain index, allows duplicates
        });

        Schema::table('interac_autodeposits', function (Blueprint $table) {
            $table->foreign('balance_id')->references('id')->on('balances')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('interac_autodeposits', function (Blueprint $table) {
            $table->dropForeign(['balance_id']);
        });

        Schema::table('interac_autodeposits', function (Blueprint $table) {
            $table->dropIndex(['balance_id']);
            $table->unique('balance_id');
        });

        Schema::table('interac_autodeposits', function (Blueprint $table) {
            $table->foreign('balance_id')->references('id')->on('balances')->onDelete('cascade');
        });
    }
};