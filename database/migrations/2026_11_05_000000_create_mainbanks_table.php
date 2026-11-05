<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mainbanks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country_iso', 10)->default('');
            $table->string('currency', 3);
            $table->string('type', 30)->default('bank');
            $table->string('normalized_name', 190);
            $table->string('flovide_code', 40);
            $table->timestamps();

            $table->unique(
                ['currency', 'country_iso', 'type', 'normalized_name'],
                'mainbanks_identity_unique'
            );
            $table->unique(['currency', 'flovide_code'], 'mainbanks_currency_code_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mainbanks');
    }
};
