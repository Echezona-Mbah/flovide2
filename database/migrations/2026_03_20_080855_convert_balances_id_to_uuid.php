<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add UUID column to balances if not exists
        if (!Schema::hasColumn('balances', 'uuid')) {
            Schema::table('balances', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });
        }

        // 2) Backfill UUIDs
        DB::table('balances')->whereNull('uuid')->update([
            'uuid' => DB::raw('UUID()')
        ]);

        // 3) Add UUID column to transactions_history if not exists
        if (!Schema::hasColumn('transactions_history', 'balance_uuid')) {
            Schema::table('transactions_history', function (Blueprint $table) {
                $table->uuid('balance_uuid')->nullable()->after('balance_id');
            });
        }

        // 4a) Map numeric balance_id -> balances.id
        DB::statement('
            UPDATE transactions_history th
            JOIN balances b ON b.id = th.balance_id
            SET th.balance_uuid = b.uuid
            WHERE th.balance_id REGEXP "^[0-9]+$"
        ');

        // 4b) If balance_id already UUID, just copy it
        DB::statement('
            UPDATE transactions_history
            SET balance_uuid = balance_id
            WHERE balance_id REGEXP "^[0-9a-fA-F-]{36}$"
        ');

        // 5) Replace balance_id in transactions_history
        if (Schema::hasColumn('transactions_history', 'balance_id')) {
            Schema::table('transactions_history', function (Blueprint $table) {
                // $table->dropForeign(['balance_id']); // uncomment if FK exists
                $table->dropColumn('balance_id');
            });
        }

        Schema::table('transactions_history', function (Blueprint $table) {
            $table->renameColumn('balance_uuid', 'balance_id');
        });

        // 6) Replace balances.id with UUID safely
        DB::statement('ALTER TABLE balances DROP PRIMARY KEY, DROP COLUMN id');
        Schema::table('balances', function (Blueprint $table) {
            $table->renameColumn('uuid', 'id');
        });
        DB::statement('ALTER TABLE balances ADD PRIMARY KEY (id)');
    }

    public function down(): void
    {
        // Not implemented; restore from backup if needed.
    }
};
