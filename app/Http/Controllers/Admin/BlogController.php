<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    /**
     * Display the blog update form with the post data.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $authors = Admin::all();
        $totalPosts = BlogPost::count();
        $publishedPosts = BlogPost::published()->count();
        $draftPosts = BlogPost::draft()->count();
        $scheduledPosts = BlogPost::scheduled()->count();

        return view('admin.blog', compact(
            'categories',
            'tags',
            'authors',
            'totalPosts',
            'publishedPosts',
            'draftPosts',
            'scheduledPosts'
        ));
    }

    public function view(Request $request)
    {
        $posts = BlogPost::with(['category'])
            ->latest()
            ->paginate(20);

        return view('admin.viewblog', compact(
            'posts',
        ));
    }

    /**
     * Store a newly created blog post 
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'author_id' => 'nullable|exists:admins,id',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $validator->errors()->first()], 422)
                : back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {

            $post = new BlogPost();

            $post->title = $request->title;
            $post->slug = $request->slug;
            $post->excerpt = $request->excerpt;
            $post->content = $request->content;
            $post->status = $request->status;
            $post->visibility = $request->visibility;
            $post->category_id = $request->category_id;
            $post->author_id = $request->author_id;
            $post->meta_title = $request->meta_title;
            $post->meta_description = $request->meta_description;
            
            $post->read_time = BlogPost::calculateReadTime(
                strip_tags($request->content)
            );
            
            if ($request->status === 'published') {
                $post->published_at = $request->published_at ?: now();
            } elseif ($request->status === 'scheduled') {
                $post->published_at = $request->published_at;
            } else {
                $post->published_at = null;
            }

            if ($request->hasFile('featured_image')) {
                $post->featured_image = $request->file('featured_image')->store('blog_posts', 'public');
            }

            $post->save();

            if ($request->filled('tags')) {
                $tagNames = explode(',', $request->tags);
                $tagIds = [];
                foreach ($tagNames as $name) {
                    $name = trim($name);
                    if ($name === '') {
                        continue;
                    }
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($name)],
                        ['name' => $name]
                    );
                    $tagIds[] = $tag->id;
                }
                $post->tags()->sync($tagIds);
            }

            DB::commit();


            return $request->ajax()
                ? response()->json(['success' => true, 'message' => 'Blog post created successfully!', 'post' => $post])
                : redirect()->route('admin.blog')->with('success', 'Blog post created successfully!');


        } catch (\Exception $e) {

            DB::rollBack();

            return $request->ajax()
                ? response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500)
                : back()
                    ->withInput()
                    ->with('error', 'Failed to create blog post.');
        }
    }

    /**
     * Update the specified blog post in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     $post = BlogPost::findOrFail($id);

    //     $validator = Validator::make($request->all(), [
    //         'title' => 'required|string|max:255',
    //         'slug' => 'required|string|max:255|unique:blog_posts,slug,' . $id,
    //         'excerpt' => 'nullable|string',
    //         'content' => 'required|string',
    //         'status' => 'required|in:draft,published,scheduled',
    //         'visibility' => 'required|in:public,private',
    //         'published_at' => 'nullable|date',
    //         'author_id' => 'nullable|exists:admins,id',
    //         'category_id' => 'nullable|exists:categories,id',
    //         'meta_title' => 'nullable|string|max:255',
    //         'meta_description' => 'nullable|string',
    //         'tags' => 'nullable|string',
    //         'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
    //         'remove_featured_image' => 'nullable|in:0,1',
    //     ]);

    //     if ($validator->fails()) {
    //         return $request->ajax()
    //             ? response()->json(['success' => false, 'message' => $validator->errors()->first()], 422)
    //             : back()->withErrors($validator)->withInput();
    //     }

    //     // Fill non-file fields
    //     $post->fill($request->except(['tags', 'featured_image', 'remove_featured_image']));
        
    //     // Handle read time calculation
    //     $post->read_time = BlogPost::calculateReadTime($request->content);

    //     // Handle published_at datetime values based on status
    //     if ($request->status === 'scheduled') {
    //         $post->published_at = $request->published_at ? \Carbon\Carbon::parse($request->published_at) : now();
    //     } elseif ($request->status === 'published') {
    //         $post->published_at = $post->published_at ?? now();
    //     } else {
    //         // draft status
    //         $post->published_at = null;
    //     }

    //     // Handle featured image removal / upload
    //     if ($request->remove_featured_image === '1') {
    //         if ($post->featured_image) {
    //             Storage::disk('public')->delete($post->featured_image);
    //             $post->featured_image = null;
    //         }
    //     }

    //     if ($request->hasFile('featured_image')) {
    //         // Delete old file if present
    //         if ($post->featured_image) {
    //             Storage::disk('public')->delete($post->featured_image);
    //         }
    //         $post->featured_image = $request->file('featured_image')->store('blog_posts', 'public');
    //     }

    //     $post->save();

    //     // Sync tags
    //     if ($request->has('tags')) {
    //         $tagNames = explode(',', $request->tags);
    //         $tagIds = [];
    //         foreach ($tagNames as $name) {
    //             $name = trim($name);
    //             if ($name !== '') {
    //                 $tag = Tag::firstOrCreate(
    //                     ['slug' => Str::slug($name)],
    //                     ['name' => $name]
    //                 );
    //                 $tagIds[] = $tag->id;
    //             }
    //         }
    //         $post->tags()->sync($tagIds);
    //     } else {
    //         $post->tags()->detach();
    //     }

    //     $imageUrl = $post->featured_image ? asset('storage/' . $post->featured_image) : null;

    //     return $request->ajax()
    //         ? response()->json([
    //             'success' => true,
    //             'message' => 'Blog post updated successfully!',
    //             'featured_image_url' => $imageUrl,
    //             'post' => $post
    //         ])
    //         : redirect()->route('admin.blog')->with('success', 'Blog post updated successfully!');
    // }

    /**
     * Remove the specified blog post from storage.
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        
        // Delete image asset if exists
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return request()->ajax()
            ? response()->json(['success' => true, 'message' => 'Blog post deleted successfully!'])
            : redirect()->route('admin.blog')->with('success', 'Blog post deleted successfully!');
    }
}
