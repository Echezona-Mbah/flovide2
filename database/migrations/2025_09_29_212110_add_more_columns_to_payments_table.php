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
        Schema::table('payments', function (Blueprint $table) {
            //
            $table->string("subaccount_id")->nullable(true)->after("amount");
            $table->string("subaccount")->nullable(true)->after("subaccount_id");
            $table->string("subaccount_name")->nullable(true)->after("subaccount");
            $table->string("subaccount_number")->nullable(true)->after("subaccount_name");
            $table->string("percentage")->nullable(true)->after("subaccount_number");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};
