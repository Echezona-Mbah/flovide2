<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Temporarily allow old and new values
        DB::statement("
            ALTER TABLE personal_bank_account_requests
            MODIFY status
            ENUM(
                'pending',
                'processing',
                'under review',
                'approved',
                'confirmed',
                'rejected'
            )
            DEFAULT 'pending'
        ");

        // Convert existing values
        DB::table('personal_bank_account_requests')
            ->where('status', 'processing')
            ->update(['status' => 'under review']);

        DB::table('personal_bank_account_requests')
            ->where('status', 'approved')
            ->update(['status' => 'confirmed']);

        // Final ENUM
        DB::statement("
            ALTER TABLE personal_bank_account_requests
            MODIFY status
            ENUM(
                'pending',
                'under review',
                'confirmed',
                'rejected'
            )
            DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        // Temporarily allow old and new values
        DB::statement("
            ALTER TABLE personal_bank_account_requests
            MODIFY status
            ENUM(
                'pending',
                'processing',
                'under review',
                'approved',
                'confirmed',
                'rejected'
            )
            DEFAULT 'pending'
        ");

        // Convert new values back to old values
        DB::table('personal_bank_account_requests')
            ->where('status', 'under review')
            ->update(['status' => 'processing']);

        DB::table('personal_bank_account_requests')
            ->where('status', 'confirmed')
            ->update(['status' => 'approved']);

        // Restore original ENUM
        DB::statement("
            ALTER TABLE personal_bank_account_requests
            MODIFY status
            ENUM('pending', 'processing', 'approved', 'rejected')
            DEFAULT 'pending'
        ");
    }
};