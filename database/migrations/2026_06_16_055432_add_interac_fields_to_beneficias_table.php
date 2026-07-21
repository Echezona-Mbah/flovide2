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
            $table->string('interac_first_name')->nullable()->after('email');
            $table->string('interac_last_name')->nullable()->after('interac_first_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficias', function (Blueprint $table) {
            //
        });
    }
};
