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
            $table->string('currency')->nullable()->after('email'); // or after any column you prefer
            $table->decimal('balance', 15, 2)->default(0)->after('currency');
            $table->string('default_currency')->nullable()->after('balance');
            $table->decimal('default_currency_balance', 15, 2)->default(0)->after('default_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['currency', 'balance', 'default_currency', 'default_currency_balance']);
        });
    }
};
