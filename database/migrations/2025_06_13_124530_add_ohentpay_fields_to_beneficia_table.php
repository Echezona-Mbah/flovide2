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
        Schema::table('beneficias', function (Blueprint $table) {
            $table->uuid('recipient_id')->nullable();
            $table->string('country')->nullable();
            $table->string('alias')->nullable();
            $table->string('type')->nullable(); // e.g. 'personal' or 'business'
            $table->string('currency')->nullable();
            $table->string('default_reference')->nullable();
            $table->string('sort_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficias', function (Blueprint $table) {
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
