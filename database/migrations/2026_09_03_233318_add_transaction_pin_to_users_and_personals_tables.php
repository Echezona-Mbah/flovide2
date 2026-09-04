<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('transaction_pin', 50)->nullable()->after('password');
            $table->unsignedTinyInteger('transaction_pin_attempts')->default(0)->after('transaction_pin');
            $table->timestamp('transaction_pin_locked_until')->nullable()->after('transaction_pin_attempts');
        });

        Schema::table('personals', function (Blueprint $table) {
            $table->string('transaction_pin', 50)->nullable()->after('password');
            $table->unsignedTinyInteger('transaction_pin_attempts')->default(0)->after('transaction_pin');
            $table->timestamp('transaction_pin_locked_until')->nullable()->after('transaction_pin_attempts');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['transaction_pin', 'transaction_pin_attempts', 'transaction_pin_locked_until']);
        });

        Schema::table('personals', function (Blueprint $table) {
            $table->dropColumn(['transaction_pin', 'transaction_pin_attempts', 'transaction_pin_locked_until']);
        });
    }
};