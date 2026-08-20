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
        Schema::create('admin_broadcast_emails', function (Blueprint $table) {
            $table->id();

            // Recipient
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('recipient_email');

            // Email content
            $table->string('subject');
            $table->longText('message');
            
            // Delivery status
            $table->enum('status', ['pending', 'sent', 'failed'])
                ->default('pending');

            // Error information if sending fails
            $table->text('error_message')->nullable();

            // When the email was actually sent
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            // Useful for searching/filtering history
            $table->index('recipient_email');
            $table->index('status');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_broadcast_emails');
    }
};
