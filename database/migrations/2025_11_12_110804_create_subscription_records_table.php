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
        Schema::create('subscription_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('subscription_id');
            $table->string('name'); 
            $table->string('email');
            $table->string('phone')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('NGN');
            $table->string('status')->default('pending'); 
            $table->string('reference')->unique(); 
            $table->timestamps();

            // Foreign keys (optional but recommended)
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_records');
    }
};
