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
        Schema::table('donations', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('subaccount_id')->nullable()->after('visibility');
            $table->string('subaccount')->nullable()->after('subaccount_id');
            $table->string('subaccount_name')->nullable()->after('subaccount');
            $table->string('subaccount_number')->nullable()->after('subaccount_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            //
            $table->dropColumn(['subaccount_id', 'subaccount', 'subaccount_name', 'subaccount_number']);
        });
    }
};
