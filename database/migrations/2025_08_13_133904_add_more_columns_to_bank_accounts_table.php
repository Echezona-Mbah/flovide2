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
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->string('currency', 3)->after('bank_name');
            $table->string('bic', 11)->nullable()->after('currency'); // SWIFT/BIC codes are up to 11 chars
            $table->string('iban', 34)->nullable()->after('bic');
            $table->string('city')->nullable()->after('iban');
            $table->string('state')->nullable()->after('city');
            $table->string('zipcode', 20)->nullable()->after('state');
            $table->string('recipient_address')->nullable()->after('zipcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropColumn([
                'currency',
                'bic',
                'iban',
                'city',
                'state',
                'zipcode',
                'recipient_address'
            ]);
        });
    }
};
