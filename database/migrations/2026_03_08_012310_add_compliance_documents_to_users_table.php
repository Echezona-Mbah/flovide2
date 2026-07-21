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
            
            $table->string('proof_of_identity')->nullable();
            $table->string('proof_of_identity_status')->default('no');

            $table->string('ownership_document')->nullable();
            $table->string('ownership_status')->default('no');

            $table->string('organisational_chart')->nullable();
            $table->string('organisational_chart_status')->default('no');

            $table->string('register_of_directors')->nullable();
            $table->string('register_of_directors_status')->default('no');

            $table->string('formation_document')->nullable();
            $table->string('formation_document_status')->default('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn([
                'proof_of_identity',
                'proof_of_identity_status',
                'ownership_document',
                'ownership_status',
                'organisational_chart',
                'organisational_chart_status',
                'register_of_directors',
                'register_of_directors_status',
                'formation_document',
                'formation_document_status',
            ]);
        });
    }
};
