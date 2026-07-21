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
        Schema::table('customers', function (Blueprint $table) {
        $table->string('recipient_id')->nullable()->after('id');
        $table->string('country')->nullable();
        $table->string('alias')->nullable();
        $table->string('type')->nullable(); // or enum if you know the values
        $table->string('currency', 10)->nullable();
        $table->string('default_reference')->nullable();
        $table->string('sort_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
            'recipient_id',
            'country',
            'alias',
            'type',
            'currency',
            'default_reference',
            'sort_code',
        ]);
        });
    }
};
