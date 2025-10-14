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
        //
        Schema::table('donations', function (Blueprint $table) {
            //make personal_id nullable
            $table->unsignedBigInteger('personal_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('donations', function (Blueprint $table) {
            //make personal_id not nullable
            $table->unsignedBigInteger('personal_id')->nullable(false)->change();
        });
    }
};
