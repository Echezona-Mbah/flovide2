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
        Schema::table('subscription_records', function (Blueprint $table) {
        $table->date('start_date')->nullable()->after('currency');
        $table->date('end_date')->nullable()->after('start_date');
        $table->boolean('is_expired')->default(false)->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_records', function (Blueprint $table) {
            //
        });
    }
};
