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
        Schema::create('virtual_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('card_provider')->nullable(); // flutterwave, mono, etc.
            $table->string('card_id')->unique(); // external API ID
            $table->string('card_type')->default('Visa'); // e.g. Visa, Mastercard
            $table->string('currency')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('masked_pan')->nullable(); // last 4 digits
            $table->string('cvv')->nullable();
            $table->string('card_number')->nullable();
            $table->string('expiry_month')->nullable();
            $table->string('expiry_year')->nullable();
            $table->string('balance')->nullable();
            $table->enum('status', ['active', 'frozen', 'terminated'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_cards');
    }
};
