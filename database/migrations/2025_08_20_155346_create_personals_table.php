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
Schema::create('personals', function (Blueprint $table) {
    $table->id();
    $table->string('firstname')->nullable();
    $table->string('lastname')->nullable();
    $table->string('email')->unique();
    $table->string('password');
    $table->string('person_phone')->unique()->nullable();
    $table->string('country')->nullable();
    $table->string('street_address')->nullable();
    $table->string('city')->nullable();
    $table->string('state')->nullable();
    $table->string('currency')->nullable();
    $table->string('email_verification_otp')->nullable();
    $table->timestamp('email_verification_otp_expires_at')->nullable();
    $table->enum('email_verified_status', ['yes', 'no'])->default('no'); 
    $table->integer('email_verification_attempts')->default(0);
    $table->string('forget_verification_otp')->nullable();
    $table->timestamp('forgot_password_otp_expires_at')->nullable();
    $table->decimal('balance', 15, 2)->default(0);
    $table->string('default_currency')->nullable();
    $table->decimal('default_currency_balance', 15, 2)->default(0);
    $table->string('bvn')->nullable();
    $table->enum('bvn_status', ['yes', 'no'])->default('no');
    $table->string('subscription_status')->default(0);
    $table->string('reset_token')->nullable();
    $table->timestamp('reset_token_expires_at')->nullable();
    $table->string('typeofuser')->default('personal');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personals');
    }
};
