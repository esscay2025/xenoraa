<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\CustomPage;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\PortfolioExperience;
use App\Models\SocialLink;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * Resolve the current tenant from domain or username.
     */
    protected function resolveTenant(Request $request, ?string $username = null): ?User
    {
        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');

        // Custom domain (e.g. gopi.blog)
        if ($host !== $mainDomain && $host !== 'www.' . $mainDomain) {
            $tenant = User::where('custom_domain', $host)
                ->orWhere('custom_domain', 'www.' . $host)
                ->first();
            if ($tenant) return $tenant;
        }

        // Username-based route (xenoraa.com/priya)
        if ($username) {
            return User::where('username', $username)->first();
        }

        // Logged-in admin viewing their own portfolio
        if (Auth::check() && Auth::user()->isAdmin()) {
            return Auth::user();
        }

        return null;
    }

    /**
     * Display the public portfolio homepage.
     */
    public function home(Request $request, ?string $username = null)
    {
        $tenant = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;

        if (!$tenant) {
            abort(404);
        }

        $experiences = PortfolioExperience::where('user_id', $tenantId)
            ->orderBy('start_date', 'desc')
            ->get();

        $socialLinks = SocialLink::where('user_id', $tenantId)
            ->where('is_active', true)
            ->get();

        $activeJobs = Job::where('user_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $blogCategories = BlogCategory::withCount([
            'posts' => fn($q) => $q->where('status', 'published')->where('user_id', $tenantId)
        ])->having('posts_count', '>', 0)->get();

        $categoryPosts = [];
        foreach ($blogCategories as $cat) {
            $categoryPosts[$cat->slug] = [
                'category' => $cat,
                'posts'    => BlogPost::with('category')
                    ->where('status', 'published')
                    ->where('category_id', $cat->id)
                    ->where('user_id', $tenantId)
                    ->orderBy('published_at', 'desc')
                    ->take(3)
                    ->get(),
            ];
        }

        $featuredPost = BlogPost::where('status', 'published')
            ->where('user_id', $tenantId)
            ->orderBy('views_count', 'desc')
            ->first();

        // Apply profession-based template — read from site_settings first, fallback to users.profile_template
        $template = SiteSetting::getValueForTenant($tenantId, 'profile_template')
            ?? $tenant->getProfileTemplate()
            ?? 'consultant';

        // Load tenant branding from site_settings
        $siteName   = SiteSetting::getValueForTenant($tenantId, 'site_name', $tenant->name);
        $siteTagline = SiteSetting::getValueForTenant($tenantId, 'site_tagline', $tenant->profession ?? '');
        $logoPath   = SiteSetting::getValueForTenant($tenantId, 'logo_path');
        $faviconPath = SiteSetting::getValueForTenant($tenantId, 'favicon_path');
        $accentColor = SiteSetting::getValueForTenant($tenantId, 'color_accent', '#6366f1');
        $chatbotEnabled = SiteSetting::getValueForTenant($tenantId, 'chatbot_enabled', '1');

        // Build $profile array from site_settings and DB data for all templates
        $profileSettings = SiteSetting::where('user_id', $tenantId)
            ->whereIn('key', ['profile_title','profile_expertise','profile_about','profile_booking_link',
                'profile_years','profile_clients','profile_projects','profile_revenue',
                'profile_services','profile_stats','profile_platforms','profile_specializations',
                'profile_practice_areas','profile_bar_number','profile_court'])
            ->pluck('value', 'key')
            ->toArray();

        $profile = [
            'name'             => $siteName,
            'title'            => $profileSettings['profile_title'] ?? ($tenant->profession ?? 'Professional'),
            'email'            => $tenant->email,
            'about'            => $profileSettings['profile_about'] ?? ($tenant->bio ?? ''),
            'booking_link'     => $profileSettings['profile_booking_link'] ?? '',
            'years'            => $profileSettings['profile_years'] ?? '',
            'clients'          => $profileSettings['profile_clients'] ?? '',
            'projects'         => $profileSettings['profile_projects'] ?? '',
            'revenue'          => $profileSettings['profile_revenue'] ?? '',
            'expertise'        => !empty($profileSettings['profile_expertise'])
                ? json_decode($profileSettings['profile_expertise'], true)
                : [],
            'services'         => !empty($profileSettings['profile_services'])
                ? json_decode($profileSettings['profile_services'], true)
                : [],
            'stats'            => !empty($profileSettings['profile_stats'])
                ? json_decode($profileSettings['profile_stats'], true)
                : [],
            'platforms'        => !empty($profileSettings['profile_platforms'])
                ? json_decode($profileSettings['profile_platforms'], true)
                : [],
            'specializations'  => !empty($profileSettings['profile_specializations'])
                ? json_decode($profileSettings['profile_specializations'], true)
                : [],
            'practice_areas'   => !empty($profileSettings['profile_practice_areas'])
                ? json_decode($profileSettings['profile_practice_areas'], true)
                : [],
            'bar_number'       => $profileSettings['profile_bar_number'] ?? '',
            'court'            => $profileSettings['profile_court'] ?? '',
        ];

        $templateViews = [
            'doctor'       => 'tenant.templates.doctor',
            'advocate'     => 'tenant.templates.advocate',
            'politician'   => 'tenant.templates.politician',
            'consultant'   => 'tenant.templates.consultant',
            'entrepreneur' => 'tenant.templates.entrepreneur',
            'influencer'   => 'tenant.templates.influencer',
        ];
        $view = $templateViews[$template] ?? 'tenant.templates.consultant';

        // Load home page sections from Page Manager
        $homePage = CustomPage::where('user_id', $tenantId)
            ->where('page_type', 'home')
            ->first();
        $homePageSections = $homePage ? $homePage->getMergedSections() : [];

        // Override profile data with section data if available
        if ($homePage) {
            $heroData = $homePage->getSectionData('hero');
            if (!empty($heroData['title']))    $profile['title']        = $heroData['title'];
            if (!empty($heroData['subtitle'])) $profile['tagline']      = $heroData['subtitle'];
            if (!empty($heroData['cta_text'])) $profile['cta_text']     = $heroData['cta_text'];
            if (!empty($heroData['cta_url']))  $profile['booking_link'] = $heroData['cta_url'];

            $aboutData = $homePage->getSectionData('about');
            if (!empty($aboutData['text'])) $profile['about'] = $aboutData['text'];

            $statsData = $homePage->getSectionData('stats');
            if (!empty($statsData['items'])) $profile['stats'] = $statsData['items'];

            $servicesData = $homePage->getSectionData('services');
            if (!empty($servicesData['items'])) $profile['services'] = $servicesData['items'];
        }

        return view($view, compact(
            'tenant', 'experiences', 'socialLinks', 'activeJobs',
            'blogCategories', 'categoryPosts', 'featuredPost',
            'siteName', 'siteTagline', 'logoPath', 'faviconPath', 'accentColor', 'chatbotEnabled',
            'profile', 'template', 'homePage', 'homePageSections'
        ));
    }

    /**
     * Display the About page.
     */
    public function about(Request $request, ?string $username = null)
    {
        $tenant = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;

        if (!$tenant) {
            abort(404);
        }

        $socialLinks = SocialLink::where('user_id', $tenantId)
            ->where('is_active', true)
            ->get();

        $experiences = PortfolioExperience::where('user_id', $tenantId)
            ->orderBy('start_date', 'desc')
            ->get();

        $settings = SiteSetting::where('user_id', $tenantId)->pluck('value', 'key')->toArray();
        $siteName = $settings['site_name'] ?? $tenant->name;
        $logoPath = $settings['logo_path'] ?? null;
        $faviconPath = $settings['favicon_path'] ?? null;
        $accentColor = $settings['color_accent'] ?? '#6366f1';
        $template = $settings['profile_template'] ?? $tenant->getProfileTemplate() ?? 'consultant';

        // Skills, Education, Certifications, Languages for about page
        $skills = \App\Models\ProfileSkill::where('user_id', $tenantId)->orderBy('proficiency', 'desc')->get();
        $education = \App\Models\ProfileEducation::where('user_id', $tenantId)->orderBy('start_date', 'desc')->get();
        $certifications = \App\Models\ProfileCertification::where('user_id', $tenantId)->orderBy('issue_date', 'desc')->get();
        $languages = \App\Models\ProfileLanguage::where('user_id', $tenantId)->get();

        return view('portfolio.about', compact(
            'tenant', 'socialLinks', 'experiences', 'settings',
            'siteName', 'logoPath', 'faviconPath', 'accentColor', 'template',
            'skills', 'education', 'certifications', 'languages'
        ));
    }

    /**
     * Display the blog listing page — scoped to tenant.
     */
    public function blog(Request $request, ?string $username = null)
    {
        $tenant = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;

        if (!$tenant) {
            abort(404);
        }

        $query = BlogPost::with(['author', 'category'])
            ->where('status', 'published')
            ->where('user_id', $tenantId);

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
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();

        return view('portfolio.blog', compact('posts', 'socialLinks', 'tenant'));
    }

    /**
     * Display blog posts filtered by category — scoped to tenant.
     */
    public function blogCategory(Request $request, string $slug, ?string $username = null)
    {
        $tenant = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;

        if (!$tenant) {
            abort(404);
        }

        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::with(['author', 'category'])
            ->where('status', 'published')
            ->where('category_id', $category->id)
            ->where('user_id', $tenantId)
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        $allCategories = BlogCategory::withCount([
            'posts' => fn($q) => $q->where('status', 'published')->where('user_id', $tenantId)
        ])->having('posts_count', '>', 0)->get();

        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();

        return view('portfolio.blog', compact('posts', 'socialLinks', 'category', 'allCategories', 'tenant'));
    }

    /**
     * Display a single blog post — verify it belongs to the tenant.
     */
    public function blogShow(Request $request, string $slug, ?string $username = null)
    {
        $tenant = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;

        $query = BlogPost::with(['author', 'category', 'comments.user'])
            ->where('slug', $slug)
            ->where('status', 'published');

        if ($tenantId) {
            $query->where('user_id', $tenantId);
        }

        $post = $query->firstOrFail();
        $post->increment('views_count');

        $comments = $post->comments()->where('is_approved', true)->orderBy('created_at', 'desc')->get();
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();

        $relatedPosts = BlogPost::with(['category'])
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where('user_id', $tenantId)
            ->when($post->category_id, fn($q) => $q->where('category_id', $post->category_id))
            ->orderByDesc('views_count')
            ->limit(4)
            ->get();

        return view('portfolio.blog-show', compact('post', 'comments', 'socialLinks', 'relatedPosts', 'tenant'));
    }

    /**
     * Submit a comment on a blog post.
     */
    public function submitComment(Request $request, string $slug)
    {
        $post = BlogPost::where('slug', $slug)->where('status', 'published')->firstOrFail();

        $request->validate([
            'comment'       => ['required', 'string', 'max:1000'],
            'visitor_name'  => ['nullable', 'string', 'max:100'],
            'visitor_email' => ['nullable', 'email', 'max:255'],
        ]);

        BlogComment::create([
            'blog_post_id'  => $post->id,
            'user_id'       => auth()->id(),
            'visitor_name'  => auth()->check() ? null : $request->visitor_name,
            'visitor_email' => auth()->check() ? null : $request->visitor_email,
            'comment'       => $request->comment,
            'is_approved'   => true,
        ]);

        return back()->with('success', 'Your comment has been submitted!');
    }

    /**
     * Display the jobs listing page — scoped to tenant.
     */
    public function jobs(Request $request, ?string $username = null)
    {
        $tenant = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;

        if (!$tenant) {
            abort(404);
        }

        $query = Job::where('status', 'active')->where('user_id', $tenantId);

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
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();

        return view('portfolio.jobs', compact('jobs', 'socialLinks', 'tenant'));
    }

    /**
     * Display a single job listing — scoped to tenant.
     */
    public function jobShow(Request $request, string $slug, ?string $username = null)
    {
        $tenant = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;

        $query = Job::where('slug', $slug)->where('status', 'active');
        if ($tenantId) {
            $query->where('user_id', $tenantId);
        }

        $job = $query->firstOrFail();
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();

        return view('portfolio.job-show', compact('job', 'socialLinks', 'tenant'));
    }

    /**
     * Submit a job application.
     */
    public function applyJob(Request $request, string $slug)
    {
        $job = Job::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $request->validate([
            'applicant_name'  => ['required', 'string', 'max:255'],
            'applicant_email' => ['required', 'email', 'max:255'],
            'applicant_phone' => ['nullable', 'string', 'max:20'],
            'resume'          => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'cover_letter'    => ['nullable', 'string', 'max:2000'],
        ]);

        $resumePath = $request->file('resume')->store('resumes', 'public');

        JobApplication::create([
            'job_id'          => $job->id,
            'user_id'         => auth()->id(),
            'applicant_name'  => $request->applicant_name,
            'applicant_email' => $request->applicant_email,
            'applicant_phone' => $request->applicant_phone,
            'resume_path'     => $resumePath,
            'cover_letter'    => $request->cover_letter,
            'status'          => 'applied',
        ]);

        return back()->with('success', 'Your application has been submitted successfully! We will get back to you soon.');
    }

    /**
     * Display a custom page for a tenant.
     */
    public function customPage(Request $request, string $slugOrUsername, ?string $slug = null)
    {
        // Handle both /page/{slug} (custom domain) and /{username}/page/{slug} (xenoraa.com)
        if ($slug === null) {
            $slug = $slugOrUsername;
            $tenant = $this->resolveTenant($request, null);
        } else {
            $tenant = $this->resolveTenant($request, $slugOrUsername);
        }
        if (!$tenant) abort(404);

        $page = CustomPage::where('user_id', $tenant->id)
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $template    = SiteSetting::getValueForTenant($tenant->id, 'profile_template', 'consultant');
        $siteName    = SiteSetting::getValueForTenant($tenant->id, 'site_name', $tenant->name);
        $logo        = SiteSetting::getValueForTenant($tenant->id, 'logo_path');
        $favicon     = SiteSetting::getValueForTenant($tenant->id, 'favicon_path');
        $accent      = SiteSetting::getValueForTenant($tenant->id, 'color_accent', '#6366f1');
        $socialLinks = SocialLink::where('user_id', $tenant->id)->where('is_active', true)->get();
        $settings    = SiteSetting::where('user_id', $tenant->id)->pluck('value', 'key')->toArray();

        return view('tenant.custom-page', compact('tenant', 'page', 'template', 'siteName', 'logo', 'favicon', 'accent', 'socialLinks', 'settings'));
    }
}
