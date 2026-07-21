<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add uuid column
        if (!Schema::hasColumn('transactions_history', 'uuid')) {
            Schema::table('transactions_history', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });
        }

        // 2) Backfill uuid
        DB::table('transactions_history')->whereNull('uuid')->update([
            'uuid' => DB::raw('UUID()')
        ]);

        // 3) Replace primary key safely (AUTO_INCREMENT)
        DB::statement('ALTER TABLE transactions_history DROP PRIMARY KEY, DROP COLUMN id');
        Schema::table('transactions_history', function (Blueprint $table) {
            $table->renameColumn('uuid', 'id');
        });
        DB::statement('ALTER TABLE transactions_history ADD PRIMARY KEY (id)');
    }

    public function down(): void
    {
        // Not implemented
    }
};
