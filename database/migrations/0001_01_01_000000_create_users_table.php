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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('country')->nullable();
            $table->string('business_name')->nullable();
            $table->string('registration_number')->nullable();
            $table->date('incorporation_date')->nullable();
            $table->string('business_type')->nullable();
            $table->string('company_url')->nullable();
            $table->string('industry')->nullable();
            $table->string('annual_turnover')->nullable();
            $table->string('street_address')->nullable();
            $table->string('city')->nullable();
            $table->string('trading_address')->nullable();
            $table->string('nature_of_business')->nullable();
            $table->string('trading_street_address')->nullable();
            $table->string('trading_city')->nullable();
            $table->string('state')->nullable();
            $table->string('typeofuser'); 
            $table->string('bvn')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('email_verification_otp')->nullable();
            $table->timestamp('email_verification_otp_expires_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
