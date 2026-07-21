<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('balances', function (Blueprint $table) {
            $table->string('virtual_account_number')->nullable()->after('amount');
            $table->string('virtual_account_name')->nullable()->after('virtual_account_number');
            $table->string('virtual_account_bank')->nullable()->after('virtual_account_name');
            $table->string('fidelty_process_id')->nullable()->after('virtual_account_bank');

            $table->index('virtual_account_number');
        });
    }

    public function down(): void
    {
        Schema::table('balances', function (Blueprint $table) {
            $table->dropIndex(['virtual_account_number']);
            $table->dropColumn([
                'virtual_account_number',
                'virtual_account_name',
                'virtual_account_bank',
                'fidelty_process_id',
            ]);
        });
    }
};