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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Public identifier for the employee verification URL
            $table->uuid('public_id')->unique();

            $table->string('employee_id')->unique();
            $table->string('name');
            $table->string('job_title');
            $table->string('department');
            $table->string('work_email')->unique();
            $table->date('employment_date');
            $table->enum('status', [
                'active',
                'inactive',
                'on_leave',
            ])->default('active');

            $table->string('photo_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
