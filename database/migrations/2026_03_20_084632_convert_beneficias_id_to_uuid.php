<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add UUID column to beneficias
        if (!Schema::hasColumn('beneficias', 'uuid')) {
            Schema::table('beneficias', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });
        }

        // 2) Backfill UUIDs
        DB::table('beneficias')->whereNull('uuid')->update([
            'uuid' => DB::raw('UUID()')
        ]);

        // 3) Add UUID column to transactions_history
        if (!Schema::hasColumn('transactions_history', 'beneficias_uuid')) {
            Schema::table('transactions_history', function (Blueprint $table) {
                $table->uuid('beneficias_uuid')->nullable()->after('beneficias_id');
            });
        }

        // 4a) Map numeric beneficias_id -> beneficias.id
        DB::statement('
            UPDATE transactions_history th
            JOIN beneficias b ON b.id = th.beneficias_id
            SET th.beneficias_uuid = b.uuid
            WHERE th.beneficias_id REGEXP "^[0-9]+$"
        ');

        // 4b) If beneficias_id already UUID, just copy it
        DB::statement('
            UPDATE transactions_history
            SET beneficias_uuid = beneficias_id
            WHERE beneficias_id REGEXP "^[0-9a-fA-F-]{36}$"
        ');

        // 5) Replace beneficias_id in transactions_history
        if (Schema::hasColumn('transactions_history', 'beneficias_id')) {
            Schema::table('transactions_history', function (Blueprint $table) {
                // $table->dropForeign(['beneficias_id']); // uncomment if FK exists
                $table->dropColumn('beneficias_id');
            });
        }

        Schema::table('transactions_history', function (Blueprint $table) {
            $table->renameColumn('beneficias_uuid', 'beneficias_id');
        });

        // 6) Replace beneficias.id with UUID safely
        DB::statement('ALTER TABLE beneficias DROP PRIMARY KEY, DROP COLUMN id');
        Schema::table('beneficias', function (Blueprint $table) {
            $table->renameColumn('uuid', 'id');
        });
        DB::statement('ALTER TABLE beneficias ADD PRIMARY KEY (id)');
    }

    public function down(): void
    {
        // Not implemented; restore from backup if needed.
    }
};
