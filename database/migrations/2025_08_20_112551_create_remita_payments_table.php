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
        Schema::create('remita_payments', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('remita_id');

            // Payment details
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('transaction_reference')->unique();
            $table->decimal('amount_paid', 15, 2);
            $table->string('currency', 10)->default('NGN');
            $table->string('channel')->nullable(); // card, bank transfer, ussd, wallet etc.

            // Status & gateway response
            $table->enum('status', ['pending', 'success', 'failed', 'reversed', 'refunded'])->default('pending');
            $table->string('response_code')->nullable();
            $table->string('response_message')->nullable();

            // Payment timestamp
            $table->timestamp('paid_at')->nullable();

            // Soft deletes & timestamps
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('remita_id')->references('id')->on('remita')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remita_payments');
    }
};
