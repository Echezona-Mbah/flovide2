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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name'); 
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending');
            $table->string('transaction_ref_number')->unique();
            $table->string('reason')->nullable();
            $table->string('type');
            $table->timestamp('time_date')->useCurrent();
            $table->string('recipient'); 
            $table->string('currency', 3)->default('USD'); 
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
