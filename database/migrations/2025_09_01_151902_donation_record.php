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
        Schema::create("donation_records", function (Blueprint $table) {
            $table->bigIncrements("id");

            // Foreign keys
            $table->foreignId('donation_id')->constrained('donations')->onDelete('cascade');
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade'); 
            //user details
            $table->string("name")->nullable();
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            // Payment details
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('NGN');
            $table->enum('status', ['pending', 'successful', 'failed'])->default('pending');
            $table->string('reference')->unique();

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('donation_records');
    }
};
