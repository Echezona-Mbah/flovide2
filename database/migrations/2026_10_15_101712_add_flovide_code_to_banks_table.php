<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->string('flovide_code', 40)->nullable()->after('bank_code');
            $table->index(['currency', 'flovide_code']);
        });

        Schema::table('beneficias', function (Blueprint $table) {
            $table->string('flovide_bank_code', 40)->nullable()->after('bank_code');
        });
    }

    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropIndex(['currency', 'flovide_code']);
            $table->dropColumn('flovide_code');
        });

        Schema::table('beneficias', function (Blueprint $table) {
            $table->dropColumn('flovide_bank_code');
        });
    }
};