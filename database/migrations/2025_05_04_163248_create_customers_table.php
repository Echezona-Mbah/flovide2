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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('bank')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('cascade');
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
