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
        Schema::table('balances', function (Blueprint $table) {
               $table->boolean('is_locked')->default(false)->after('amount');
        $table->string('locked_reason')->nullable()->after('is_locked');
        $table->timestamp('locked_at')->nullable()->after('locked_reason');
        $table->unsignedBigInteger('locked_by')->nullable()->after('locked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('balances', function (Blueprint $table) {
                   $table->dropColumn(['is_locked', 'locked_reason', 'locked_at', 'locked_by']);

        });
    }
};
