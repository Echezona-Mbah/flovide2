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
        Schema::table('currencies', function (Blueprint $table) {
            $table->decimal('min_amount', 18, 2)->nullable()->after('country_code');
            $table->decimal('max_amount', 18, 2)->nullable()->after('min_amount');
            $table->boolean('is_active')->default(true)->after('max_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('currencies', function (Blueprint $table) {
            //
        });
    }
};
