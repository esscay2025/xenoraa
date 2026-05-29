<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['author', 'category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $categories = BlogCategory::all();
        return view('admin.blog.create', compact('categories'));
    }

    /**
     * Store a newly created blog post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required'],
            'category_id' => ['nullable', 'exists:blog_categories,id'],
            'status' => ['required', 'in:draft,published,archived'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog', 'public');
        }

        BlogPost::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'summary' => $request->summary,
            'content' => $request->content,
            'featured_image' => $imagePath,
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully.');
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(BlogPost $blog)
    {
        $categories = BlogCategory::all();
        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified blog post.
     */
    public function update(Request $request, BlogPost $blog)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required'],
            'category_id' => ['nullable', 'exists:blog_categories,id'],
            'status' => ['required', 'in:draft,published,archived'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = $blog->featured_image;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog', 'public');
        }

        $blog->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'summary' => $request->summary,
            'content' => $request->content,
            'featured_image' => $imagePath,
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? ($blog->published_at ?? now()) : null,
        ]);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified blog post.
     */
    public function destroy(BlogPost $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully.');
    }

    /**
     * Manage comments.
     */
    public function comments(Request $request)
    {
        $comments = BlogComment::with(['post', 'user'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.blog.comments', compact('comments'));
    }

    /**
     * Toggle comment approval.
     */
    public function toggleComment(BlogComment $comment)
    {
        $comment->update(['is_approved' => !$comment->is_approved]);
        return back()->with('success', 'Comment status updated.');
    }

    /**
     * Delete a comment.
     */
    public function destroyComment(BlogComment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}
