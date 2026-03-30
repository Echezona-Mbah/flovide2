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
        Schema::table('users', function (Blueprint $table) {
             $table->string('secret_key')->nullable()->after('selfie_verification_status');
            $table->string('public_key')->nullable()->after('secret_key');
            $table->json('ip_whitelist')->nullable()->after('public_key');
            $table->string('callback_url')->nullable()->after('ip_whitelist');
            $table->string('webhook_url')->nullable()->after('callback_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
           $table->dropColumn([
                'secret_key',
                'public_key',
                'ip_whitelist',
                'callback_url',
                'webhook_url',
            ]);
        });
    }
};
