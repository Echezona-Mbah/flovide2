<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions_history', function (Blueprint $table) {
            $table->boolean('api_source')->default(false)->index();
        });
    }

    public function down(): void
    {
        Schema::table('transactions_history', function (Blueprint $table) {
            $table->dropColumn('api_source');
        });
    }
};
