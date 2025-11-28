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
        Schema::table('admins', function (Blueprint $table) {
              $table->string('phone')->nullable();
            $table->string('role')->nullable();
            $table->text('skills')->nullable();
            $table->string('experience')->nullable();
            $table->string('profile_picture')->nullable();
            $table->text('portfolio_links')->nullable();
            $table->text('social_media_accounts')->nullable();
            $table->string('languages_spoken')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('linkedin_profile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
              $table->dropColumn([
                'phone', 'role', 'skills', 'experience', 'profile_picture', 
                'portfolio_links', 'social_media_accounts', 'languages_spoken', 
                'emergency_contact', 'linkedin_profile'
            ]);
        });
    }
};
