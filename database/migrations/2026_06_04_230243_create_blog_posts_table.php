<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();

            // Core Content
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');

            // Media
            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable();

            // Publishing
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->timestamp('published_at')->nullable();

            // Classification
            // $table->foreignId('category_id')
            //     ->nullable()
            //     ->constrained('categories')
            //     ->nullOnDelete();

            // $table->foreignId('author_id')
            //     ->nullable()
            //     ->constrained('admins')
            //     ->nullOnDelete();

            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->boolean('is_featured')->default(false);

            // Analytics
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedSmallInteger('read_time')->nullable()->comment('Estimated read time in minutes');

            // Soft Deletes & Timestamps
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};