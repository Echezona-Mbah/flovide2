<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personals', function (Blueprint $table) {
            $table->timestamp('referral_bonus_notified_at')->nullable()->after('referred_by');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('referral_bonus_notified_at')->nullable()->after('referred_by');
        });

        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('referral_bonus');
            $table->string('title');
            $table->text('body');
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('personals', fn (Blueprint $table) => $table->dropColumn('referral_bonus_notified_at'));
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('referral_bonus_notified_at'));
        Schema::dropIfExists('admin_notifications');
    }
};