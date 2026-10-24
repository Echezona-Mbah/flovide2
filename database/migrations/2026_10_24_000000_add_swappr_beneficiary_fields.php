<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficias', function (Blueprint $table) {
            if (!Schema::hasColumn('beneficias', 'provider')) {
                $table->string('provider')->nullable();
            }

            if (!Schema::hasColumn('beneficias', 'swappr_beneficiary_id')) {
                $table->string('swappr_beneficiary_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Retain provider IDs and attribution data if this migration is rolled back.
    }
};
