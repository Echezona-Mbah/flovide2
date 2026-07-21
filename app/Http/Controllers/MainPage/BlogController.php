<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        // Featured post: most recent published post marked as featured, else just the latest published
        $featuredPost = BlogPost::with(['category'])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->where(function ($q) {
                $q->where('is_featured', true)
                  ->orWhereNotNull('published_at');
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->first();

        // Category filter
        $categorySlug = $request->query('category');
        $activeCategory = null;

        $postsQuery = BlogPost::with(['category'])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->orderByDesc('published_at');

        if ($categorySlug && $categorySlug !== 'all') {
            $activeCategory = Category::where('slug', $categorySlug)->first();
            if ($activeCategory) {
                $postsQuery->where('category_id', $activeCategory->id);
            }
        }

        // Exclude featured post from the grid to avoid duplication
        if ($featuredPost) {
            $postsQuery->where('id', '!=', $featuredPost->id);
        }

        $posts = $postsQuery->paginate(9)->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('mainpage.blog', compact(
            'featuredPost',
            'posts',
            'categories',
            'activeCategory'
        ));
    }

    public function show(string $slug)
    {
        $post = BlogPost::with(['category', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->firstOrFail();

        // Increment view count
        $post->increment('views_count');

        // Related posts: same category, excluding current, limit 3
        $relatedPosts = BlogPost::with(['category'])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn($q) => $q->where('category_id', $post->category_id))
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('mainpage.blog-post', compact('post', 'relatedPosts'));
    }
}
