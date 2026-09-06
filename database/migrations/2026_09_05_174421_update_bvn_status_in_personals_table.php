<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Temporarily allow old and new values
        DB::statement("
            ALTER TABLE personals
            MODIFY bvn_status
            ENUM('yes', 'no', 'pending', 'under review', 'confirmed')
            DEFAULT 'pending'
        ");

        DB::statement("
            ALTER TABLE personals
            MODIFY nin_status
            ENUM('yes', 'no', 'pending', 'under review', 'confirmed')
            DEFAULT 'pending'
        ");

        // Convert existing old values
        DB::table('personals')
            ->where('bvn_status', 'yes')
            ->update(['bvn_status' => 'confirmed']);

        DB::table('personals')
            ->where('bvn_status', 'no')
            ->update(['bvn_status' => 'pending']);

        DB::table('personals')
            ->where('nin_status', 'yes')
            ->update(['nin_status' => 'confirmed']);

        DB::table('personals')
            ->where('nin_status', 'no')
            ->update(['nin_status' => 'pending']);

        // Remove old yes/no values
        DB::statement("
            ALTER TABLE personals
            MODIFY bvn_status
            ENUM('pending', 'under review', 'confirmed')
            DEFAULT 'pending'
        ");

        DB::statement("
            ALTER TABLE personals
            MODIFY nin_status
            ENUM('pending', 'under review', 'confirmed')
            DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        // Temporarily allow old and new values
        DB::statement("
            ALTER TABLE personals
            MODIFY bvn_status
            ENUM('yes', 'no', 'pending', 'under review', 'confirmed')
            DEFAULT 'no'
        ");

        DB::statement("
            ALTER TABLE personals
            MODIFY nin_status
            ENUM('yes', 'no', 'pending', 'under review', 'confirmed')
            DEFAULT 'no'
        ");

        // Convert new values back to old values
        DB::table('personals')
            ->where('bvn_status', 'confirmed')
            ->update(['bvn_status' => 'yes']);

        DB::table('personals')
            ->whereIn('bvn_status', ['pending', 'under review'])
            ->update(['bvn_status' => 'no']);

        DB::table('personals')
            ->where('nin_status', 'confirmed')
            ->update(['nin_status' => 'yes']);

        DB::table('personals')
            ->whereIn('nin_status', ['pending', 'under review'])
            ->update(['nin_status' => 'no']);

        // Restore original ENUM
        DB::statement("
            ALTER TABLE personals
            MODIFY bvn_status ENUM('yes', 'no')
            DEFAULT 'no'
        ");

        DB::statement("
            ALTER TABLE personals
            MODIFY nin_status ENUM('yes', 'no')
            DEFAULT 'no'
        ");
    }
};