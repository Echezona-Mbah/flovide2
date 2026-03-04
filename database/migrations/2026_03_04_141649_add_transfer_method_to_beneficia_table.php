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
        Schema::table('beneficias', function (Blueprint $table) {
            $table->string('transfer_method')->nullable()->after('bank_code'); // store 'bank' or 'mobile'

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficia', function (Blueprint $table) {
            $table->dropColumn('transfer_method');
        });
    }
};
