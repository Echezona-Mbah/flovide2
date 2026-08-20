<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interac_autodeposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->uuid('balance_id'); // matches balances.id type — adjust if balances.id isn't uuid
            $table->string('email');
            $table->enum('status', ['pending', 'active', 'inactive'])->default('active');
            $table->timestamp('added_at')->nullable();
            $table->timestamps();

            $table->unique('balance_id'); // one auto-deposit email per wallet
            $table->index('user_id');

            $table->foreign('balance_id')->references('id')->on('balances')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interac_autodeposits');
    }
};