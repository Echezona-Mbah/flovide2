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
        Schema::table('admin_broadcast_emails', function (Blueprint $table) {
            $table->foreignId('personal_id')
                ->nullable()
                ->after('user_id')
                ->constrained('personals')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_broadcast_emails', function (Blueprint $table) {
            $table->dropColumn('personal_id');
        });
    }
};
