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
        Schema::create('personal_bank_account_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('personal_id')->constrained('personals')->cascadeOnDelete();
            $table->text('bvn');
            $table->text('nin');
            $table->enum('status', ['pending', 'processing', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->index(['personal_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_bank_account_requests');
    }
};
