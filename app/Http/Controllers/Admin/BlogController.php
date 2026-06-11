<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\ForumTopic;
use App\Models\ForumReply;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Return the current tenant ID (always the logged-in admin's own ID).
     */
    private function tenantId(): int
    {
        return auth()->user()->getTenantId();
    }

    /**
     * Display a listing of blog posts — scoped to current tenant.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'posts');

        // Blog posts
        $postQuery = BlogPost::with(['author', 'category'])
            ->where('user_id', $this->tenantId());
        if ($request->filled('status')) {
            $postQuery->where('status', $request->status);
        }
        $posts = $postQuery->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Comments
        $tenantPostIds = BlogPost::where('user_id', $this->tenantId())->pluck('id');
        $comments = BlogComment::with(['post', 'user'])
            ->whereIn('blog_post_id', $tenantPostIds)
            ->orderBy('created_at', 'desc')
            ->paginate(20)->withQueryString();

        // Forum topics
        $topicQuery = ForumTopic::with(['user'])->withCount('replies');
        if ($request->filled('category')) {
            $topicQuery->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $topicQuery->where('title', 'like', '%' . $request->search . '%');
        }
        $topics = $topicQuery->orderByDesc('is_pinned')->orderByDesc('created_at')->paginate(20)->withQueryString();
        $forumStats = [
            'total_topics'  => ForumTopic::count(),
            'total_replies' => ForumReply::where('is_deleted', false)->count(),
            'pinned'        => ForumTopic::where('is_pinned', true)->count(),
            'locked'        => ForumTopic::where('is_locked', true)->count(),
        ];

        return view('admin.blog.index', compact('posts', 'comments', 'topics', 'forumStats', 'tab'));
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
            'title'          => ['required', 'string', 'max:255'],
            'content'        => ['required'],
            'category_id'    => ['nullable', 'exists:blog_categories,id'],
            'status'         => ['required', 'in:draft,published,archived'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog', 'public');
        }

        BlogPost::create([
            'user_id'      => auth()->id(),
            'category_id'  => $request->category_id,
            'title'        => $request->title,
            'slug'         => Str::slug($request->title) . '-' . Str::random(4),
            'summary'      => $request->summary,
            'content'      => $request->content,
            'featured_image' => $imagePath,
            'status'       => $request->status,
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully.');
    }

    /**
     * Show the form for editing the specified post — only if owned by tenant.
     */
    public function edit(BlogPost $blog)
    {
        abort_if($blog->user_id !== $this->tenantId(), 403);
        $categories = BlogCategory::all();
        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified blog post.
     */
    public function update(Request $request, BlogPost $blog)
    {
        abort_if($blog->user_id !== $this->tenantId(), 403);

        $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'content'        => ['required'],
            'category_id'    => ['nullable', 'exists:blog_categories,id'],
            'status'         => ['required', 'in:draft,published,archived'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = $blog->featured_image;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog', 'public');
        }

        $blog->update([
            'category_id'  => $request->category_id,
            'title'        => $request->title,
            'slug'         => Str::slug($request->title) . '-' . Str::random(4),
            'summary'      => $request->summary,
            'content'      => $request->content,
            'featured_image' => $imagePath,
            'status'       => $request->status,
            'published_at' => $request->status === 'published' ? ($blog->published_at ?? now()) : null,
        ]);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified blog post.
     */
    public function destroy(BlogPost $blog)
    {
        abort_if($blog->user_id !== $this->tenantId(), 403);
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully.');
    }

    /**
     * Manage comments — only for this tenant's posts.
     */
    public function comments(Request $request)
    {
        $tenantPostIds = BlogPost::where('user_id', $this->tenantId())->pluck('id');
        $comments = BlogComment::with(['post', 'user'])
            ->whereIn('blog_post_id', $tenantPostIds)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
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
