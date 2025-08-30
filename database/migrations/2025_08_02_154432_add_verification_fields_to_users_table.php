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
        Schema::table('users', function (Blueprint $table) {
            $table->string('bvn_status')->default('no'); 

            $table->string('cac_certificate')->nullable();
            $table->string('cac_status')->default('no');

            $table->string('valid_id')->nullable();
            $table->string('valid_id_status')->default('no');

            $table->string('tin')->nullable();
            $table->string('tin_status')->default('no');

            $table->string('utility_bill')->nullable();
            $table->string('utility_bill_status')->default('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
            'bvn_status',
            'cac_certificate', 'cac_status',
            'valid_id', 'valid_id_status',
            'tin', 'tin_status',
            'utility_bill', 'utility_bill_status'
        ]);
        });
    }
};
