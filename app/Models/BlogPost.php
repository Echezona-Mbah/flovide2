<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'featured_image_alt',
        'status',
        'visibility',
        'published_at',
        'category_id',
        'author_id',
        'meta_title',
        'meta_description',
        'is_featured',
        'views_count',
        'read_time',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count'  => 'integer',
        'read_time'    => 'integer',
    ];

    // Relationships

    // Belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Belongs to an author (Admin)
    public function author()
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }

    // Belongs to many tags via pivot table
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_post_tag');
    }

    // Scopes

    // Get only published posts
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('visibility', 'public')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    // Get only draft posts
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Get only scheduled posts
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    // Helpers

    // Auto-generate slug from title
    public static function generateSlug(string $title): string
    {
        return Str::slug($title);
    }

    // Auto-calculate read time from content
    public static function calculateReadTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        return (int) ceil($wordCount / 200); // avg 200 words per minute
    }

    // Increment views count
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // Check if post is published
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}