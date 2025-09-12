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
        //
        Schema::create("donations", function (Blueprint $table) {

            $table->bigIncrements("id");

            // Foreign key
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade');

            // Payment details
            $table->string('cover_image')->nullable();
            $table->string('title')->nullable();
            $table->string('donation_reference')->unique();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('NGN');
            $table->enum('visibility', ['public', 'private'])->default('private');

            // Soft deletes & timestamps
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('donations');
    }
};
