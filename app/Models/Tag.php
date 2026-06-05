<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    // A tag belongs to many blog posts
    public function blogPosts()
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_tag');
    }
}