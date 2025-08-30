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
            $table->string(column: 'subaccount_name')->after('subaccount');
            $table->string(column: 'subaccount_number')->after('subaccount_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remita', function (Blueprint $table) {
            //
            $table->dropColumn('subaccount_name');
            $table->dropColumn('subaccount_number');
        });
    }
};
