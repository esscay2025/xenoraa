<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\PortfolioExperience;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Display the public portfolio homepage.
     */
    public function home()
    {
        $experiences = PortfolioExperience::orderBy('start_date', 'desc')->get();
        $socialLinks = SocialLink::where('is_active', true)->get();
        $activeJobs = Job::where('status', 'active')->orderBy('created_at', 'desc')->take(3)->get();

        // Category-grouped blog posts for homepage
        $blogCategories = BlogCategory::withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->having('posts_count', '>', 0)
            ->get();

        $categoryPosts = [];
        foreach ($blogCategories as $cat) {
            $categoryPosts[$cat->slug] = [
                'category' => $cat,
                'posts'    => BlogPost::with('category')
                    ->where('status', 'published')
                    ->where('category_id', $cat->id)
                    ->orderBy('published_at', 'desc')
                    ->take(3)
                    ->get(),
            ];
        }

        // Featured post (most viewed)
        $featuredPost = BlogPost::where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->first();

        return view('portfolio.home', compact(
            'experiences', 'socialLinks', 'activeJobs',
            'blogCategories', 'categoryPosts', 'featuredPost'
        ));
    }

    /**
     * Display the About page.
     */
    public function about()
    {
        $socialLinks = SocialLink::where('is_active', true)->get();
        return view('portfolio.about', compact('socialLinks'));
    }

    /**
     * Display the blog listing page.
     */
    public function blog(Request $request)
    {
        $query = BlogPost::with(['author', 'category'])->where('status', 'published');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(9);
        $socialLinks = SocialLink::where('is_active', true)->get();

        return view('portfolio.blog', compact('posts', 'socialLinks'));
    }

    /**
     * Display blog posts filtered by category.
     */
    public function blogCategory(Request $request, string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::with(['author', 'category'])
            ->where('status', 'published')
            ->where('category_id', $category->id)
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        $allCategories = BlogCategory::withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->having('posts_count', '>', 0)
            ->get();

        $socialLinks = SocialLink::where('is_active', true)->get();

        return view('portfolio.blog', compact('posts', 'socialLinks', 'category', 'allCategories'));
    }

    /**
     * Display a single blog post.
     */
    public function blogShow(string $slug)
    {
        $post = BlogPost::with(['author', 'category', 'comments.user'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count
        $post->increment('views_count');

        $comments = $post->comments()->where('is_approved', true)->orderBy('created_at', 'desc')->get();
        $socialLinks = SocialLink::where('is_active', true)->get();

        // Related posts: same category, exclude current
        $relatedPosts = BlogPost::with(['category'])
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn($q) => $q->where('category_id', $post->category_id))
            ->orderByDesc('views_count')
            ->limit(4)
            ->get();

        return view('portfolio.blog-show', compact('post', 'comments', 'socialLinks', 'relatedPosts'));
    }

    /**
     * Submit a comment on a blog post.
     */
    public function submitComment(Request $request, string $slug)
    {
        $post = BlogPost::where('slug', $slug)->where('status', 'published')->firstOrFail();

        $request->validate([
            'comment' => ['required', 'string', 'max:1000'],
            'visitor_name' => ['nullable', 'string', 'max:100'],
            'visitor_email' => ['nullable', 'email', 'max:255'],
        ]);

        BlogComment::create([
            'blog_post_id' => $post->id,
            'user_id' => auth()->id(),
            'visitor_name' => auth()->check() ? null : $request->visitor_name,
            'visitor_email' => auth()->check() ? null : $request->visitor_email,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Your comment has been submitted!');
    }

    /**
     * Display the jobs listing page.
     */
    public function jobs(Request $request)
    {
        $query = Job::where('status', 'active');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(10);
        $socialLinks = SocialLink::where('is_active', true)->get();

        return view('portfolio.jobs', compact('jobs', 'socialLinks'));
    }

    /**
     * Display a single job listing.
     */
    public function jobShow(string $slug)
    {
        $job = Job::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $socialLinks = SocialLink::where('is_active', true)->get();

        return view('portfolio.job-show', compact('job', 'socialLinks'));
    }

    /**
     * Submit a job application.
     */
    public function applyJob(Request $request, string $slug)
    {
        $job = Job::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $request->validate([
            'applicant_name' => ['required', 'string', 'max:255'],
            'applicant_email' => ['required', 'email', 'max:255'],
            'applicant_phone' => ['nullable', 'string', 'max:20'],
            'resume' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'cover_letter' => ['nullable', 'string', 'max:2000'],
        ]);

        $resumePath = $request->file('resume')->store('resumes', 'public');

        JobApplication::create([
            'job_id' => $job->id,
            'user_id' => auth()->id(),
            'applicant_name' => $request->applicant_name,
            'applicant_email' => $request->applicant_email,
            'applicant_phone' => $request->applicant_phone,
            'resume_path' => $resumePath,
            'cover_letter' => $request->cover_letter,
            'status' => 'applied',
        ]);

        return back()->with('success', 'Your application has been submitted successfully! We will get back to you soon.');
    }
}
