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
        Schema::table('personals', function (Blueprint $table) {
                       $table->string('identity_verification_status')
                ->default('pending')
                ->after('email');

            $table->string('selfie_verification_status')
                ->default('pending')
                ->after('identity_verification_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personals', function (Blueprint $table) {
            $table->dropColumn([
                'identity_verification_status',
                'selfie_verification_status'
            ]);
        });
    }
};
