<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nin', 20)->nullable();
            $table->string('date_of_birth', 10)->nullable();
            $table->string('virtual_account_number', 20)->nullable();
            $table->string('virtual_account_name')->nullable();
            $table->string('virtual_account_bank', 20)->nullable();
            $table->string('fidelty_process_id', 35)->nullable();
        });

        Schema::table('personals', function (Blueprint $table) {
            $table->string('nin', 20)->nullable();
            $table->string('date_of_birth', 10)->nullable();
            $table->string('virtual_account_number', 20)->nullable();
            $table->string('virtual_account_name')->nullable();
            $table->string('virtual_account_bank', 20)->nullable();
            $table->string('fidelty_process_id', 35)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nin',
                'date_of_birth',
                'virtual_account_number',
                'virtual_account_name',
                'virtual_account_bank',
                'fidelty_process_id',
            ]);
        });

        Schema::table('personals', function (Blueprint $table) {
            $table->dropColumn([
                'nin',
                'date_of_birth',
                'virtual_account_number',
                'virtual_account_name',
                'virtual_account_bank',
                'fidelty_process_id',
            ]);
        });
    }
};