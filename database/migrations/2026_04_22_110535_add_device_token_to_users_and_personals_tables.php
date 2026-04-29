<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'device_token')) {
                $table->text('device_token')->nullable()->after('remember_token');
            }
        });

        // Change 'personals' if your table name is different
        Schema::table('personals', function (Blueprint $table) {
            if (!Schema::hasColumn('personals', 'device_token')) {
                $table->text('device_token')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'device_token')) {
                $table->dropColumn('device_token');
            }
        });

        Schema::table('personals', function (Blueprint $table) {
            if (Schema::hasColumn('personals', 'device_token')) {
                $table->dropColumn('device_token');
            }
        });
    }
};
