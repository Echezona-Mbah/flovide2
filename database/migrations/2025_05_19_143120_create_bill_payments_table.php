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
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // optional for guest payments
            $table->string('request_id')->unique();
            $table->string('service_id'); // dstv, airtel-data, ikeja-electric
            $table->string('variation_code')->nullable();
            $table->string('billers_code')->nullable(); // e.g. smartcard number or meter number
            $table->decimal('amount', 10, 2);
            $table->string('phone');
            $table->string('currency');
            $table->json('response')->nullable(); // full VTpass response
            $table->string('status')->default('pending'); // pending, success, failed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_payments');
    }
};
