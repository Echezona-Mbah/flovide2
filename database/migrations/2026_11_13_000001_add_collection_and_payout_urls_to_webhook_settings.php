<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webhook_settings', function (Blueprint $table) {
            $table->text('live_collection_url')->nullable();
            $table->text('live_payout_url')->nullable();
            $table->text('test_collection_url')->nullable();
            $table->text('test_payout_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('webhook_settings', function (Blueprint $table) {
            $table->dropColumn([
                'live_collection_url',
                'live_payout_url',
                'test_collection_url',
                'test_payout_url',
            ]);
        });
    }
};
