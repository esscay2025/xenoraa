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
    protected function resolveTenant(Request $request, ?string $username = null): ?User
    {
        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');
        if ($host !== $mainDomain && $host !== 'www.' . $mainDomain) {
            $tenant = User::where('custom_domain', $host)
                ->orWhere('custom_domain', 'www.' . $host)
                ->orWhere('custom_domain', str_replace('www.', '', $host))
                ->first();
            if ($tenant) return $tenant;
        }
        if ($username) {
            return User::where('username', $username)->first();
        }
        if (Auth::check() && Auth::user()->isAdmin()) {
            return Auth::user();
        }
        return null;
    }

    private function decodeJson(string $json): array
    {
        if (empty($json)) return [];
        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function loadProfile(int $tenantId, User $tenant, array $allSettings): array
    {
        $siteName = $allSettings['site_name'] ?? $tenant->name;
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();

        $profile = [
            'name'                 => $siteName,
            'title'                => $allSettings['profile_title']             ?? ($tenant->profession ?? 'Professional'),
            'tagline'              => $allSettings['hero_subtitle']             ?? ($allSettings['site_tagline'] ?? ''),
            'email'                => $allSettings['contact_email']             ?? $tenant->email,
            'phone'                => $allSettings['contact_phone']             ?? '',
            'address'              => $allSettings['contact_address']           ?? '',
            'about'                => $allSettings['profile_about']             ?? ($tenant->bio ?? ''),
            'booking_link'         => $allSettings['profile_booking_link']      ?? '',
            'cta_text'             => $allSettings['profile_cta_text']          ?? 'Book a Call',
            'years'                => $allSettings['profile_years']             ?? '',
            'clients'              => $allSettings['profile_clients']           ?? '',
            'projects'             => $allSettings['profile_projects']          ?? '',
            'revenue'              => $allSettings['profile_revenue']           ?? '',
            'expertise'            => $this->decodeJson($allSettings['profile_expertise']       ?? ''),
            'services'             => $this->decodeJson($allSettings['profile_services']        ?? ''),
            'stats'                => $this->decodeJson($allSettings['profile_stats']           ?? ''),
            'platforms'            => $this->decodeJson($allSettings['profile_platforms']       ?? ''),
            'specializations'      => $this->decodeJson($allSettings['profile_specializations'] ?? ''),
            // Advocate
            'practice_areas'       => $this->decodeJson($allSettings['profile_practice_areas']  ?? ''),
            'bar_number'           => $allSettings['profile_bar_number']        ?? '',
            'court'                => $allSettings['profile_court']             ?? '',
            'enrollment_no'        => $allSettings['profile_enrollment_no']     ?? ($allSettings['profile_bar_number'] ?? ''),
            'chamber'              => $allSettings['contact_address']           ?? '',
            'consultation_link'    => $allSettings['profile_booking_link']      ?? '',
            'years_experience'     => $allSettings['profile_years']             ?? '',
            'cases_won'            => $allSettings['profile_cases_won']         ?? ($allSettings['profile_projects'] ?? ''),
            'clients_served'       => $allSettings['profile_clients']           ?? '',
            // Doctor
            'registration_no'      => $allSettings['profile_registration_no']  ?? '',
            'appointment_link'     => $allSettings['profile_appointment_link']  ?? ($allSettings['profile_booking_link'] ?? ''),
            'clinic'               => $allSettings['profile_clinic']            ?? ($allSettings['contact_address'] ?? ''),
            'timings'              => $allSettings['profile_timings']           ?? '',
            // Entrepreneur
            'ventures'             => $this->decodeJson($allSettings['profile_ventures']        ?? ''),
            'ventures_built'       => $allSettings['profile_ventures_built']    ?? ($allSettings['profile_projects'] ?? ''),
            'funding_raised'       => $allSettings['profile_funding_raised']    ?? '',
            'team_size'            => $allSettings['profile_team_size']         ?? '',
            'industries'           => $this->decodeJson($allSettings['profile_industries']      ?? ''),
            'pitch_link'           => $allSettings['profile_pitch_link']        ?? ($allSettings['profile_booking_link'] ?? ''),
            'linkedin'             => $allSettings['profile_linkedin']          ?? '',
            // Influencer
            'handle'               => $allSettings['profile_handle']            ?? '',
            'niche'                => $allSettings['profile_niche']             ?? ($allSettings['site_tagline'] ?? ''),
            'followers_total'      => $allSettings['profile_followers_total']   ?? '',
            'instagram_followers'  => $allSettings['profile_instagram_followers'] ?? '',
            'youtube_subscribers'  => $allSettings['profile_youtube_subscribers'] ?? '',
            'twitter_followers'    => $allSettings['profile_twitter_followers']   ?? '',
            'tiktok_followers'     => $allSettings['profile_tiktok_followers']    ?? '',
            'instagram'            => $allSettings['profile_instagram']         ?? '',
            'youtube'              => $allSettings['profile_youtube']           ?? '',
            'twitter'              => $allSettings['profile_twitter']           ?? '',
            'tiktok'               => $allSettings['profile_tiktok']            ?? '',
            'collab_email'         => $allSettings['profile_collab_email']      ?? ($allSettings['contact_email'] ?? $tenant->email),
            'collab_types'         => $this->decodeJson($allSettings['profile_collab_types']    ?? ''),
            'media_kit'            => $allSettings['profile_media_kit']         ?? '',
            // Politician
            'constituency'         => $allSettings['profile_constituency']      ?? '',
            'party'                => $allSettings['profile_party']             ?? '',
            'agenda'               => $this->decodeJson($allSettings['profile_agenda']          ?? ''),
            'achievements'         => $this->decodeJson($allSettings['profile_achievements']    ?? ''),
            'petition_link'        => $allSettings['profile_petition_link']     ?? '',
            'contact_link'         => $allSettings['profile_contact_link']      ?? '',
            'facebook'             => $allSettings['profile_facebook']          ?? '',
            'whatsapp'             => $allSettings['profile_whatsapp']          ?? '',
        ];

        // Populate social links from SocialLink table if profile fields are empty
        foreach ($socialLinks as $sl) {
            $p = strtolower($sl->platform ?? '');
            if ($p === 'instagram' && empty($profile['instagram'])) $profile['instagram'] = $sl->url;
            if ($p === 'youtube'   && empty($profile['youtube']))   $profile['youtube']   = $sl->url;
            if ($p === 'twitter'   && empty($profile['twitter']))   $profile['twitter']   = $sl->url;
            if ($p === 'tiktok'    && empty($profile['tiktok']))    $profile['tiktok']    = $sl->url;
            if ($p === 'linkedin'  && empty($profile['linkedin']))  $profile['linkedin']  = $sl->url;
            if ($p === 'facebook'  && empty($profile['facebook']))  $profile['facebook']  = $sl->url;
            if ($p === 'whatsapp'  && empty($profile['whatsapp']))  $profile['whatsapp']  = $sl->url;
        }

        return $profile;
    }

    public function home(Request $request, ?string $username = null)
    {
        $tenant   = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;
        if (!$tenant) abort(404);

        $blogCategories = BlogCategory::where('user_id', $tenantId)->get();
        $categoryPosts  = [];
        foreach ($blogCategories as $cat) {
            $categoryPosts[$cat->slug] = [
                'category' => $cat,
                'posts'    => BlogPost::where('status', 'published')
                    ->where('category_id', $cat->id)
                    ->where('user_id', $tenantId)
                    ->orderBy('published_at', 'desc')
                    ->take(3)->get(),
            ];
        }
        $featuredPost = BlogPost::where('status', 'published')->where('user_id', $tenantId)
            ->orderBy('views_count', 'desc')->first();
        $activeJobs   = Job::where('status', 'active')->where('user_id', $tenantId)
            ->orderBy('created_at', 'desc')->take(5)->get();
        $experiences  = PortfolioExperience::where('user_id', $tenantId)
            ->orderBy('start_date', 'desc')->get();
        $socialLinks  = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();

        $allSettings = SiteSetting::where('user_id', $tenantId)->pluck('value', 'key')->toArray();
        $template    = $allSettings['profile_template'] ?? $tenant->getProfileTemplate() ?? 'consultant';
        $siteName    = $allSettings['site_name']    ?? $tenant->name;
        $siteTagline = $allSettings['site_tagline'] ?? ($tenant->profession ?? '');
        $logoPath    = $allSettings['logo_path']    ?? null;
        $faviconPath = $allSettings['favicon_path'] ?? null;
        $accentColor = $allSettings['color_accent'] ?? '#6366f1';
        $chatbotEnabled = $allSettings['chatbot_enabled'] ?? '1';

        $profile = $this->loadProfile($tenantId, $tenant, $allSettings);

        $templateViews = [
            'doctor'       => 'tenant.templates.doctor',
            'advocate'     => 'tenant.templates.advocate',
            'politician'   => 'tenant.templates.politician',
            'consultant'   => 'tenant.templates.consultant',
            'entrepreneur' => 'tenant.templates.entrepreneur',
            'influencer'   => 'tenant.templates.influencer',
        ];
        $view = $templateViews[$template] ?? 'tenant.templates.consultant';

        $homePage         = CustomPage::where('user_id', $tenantId)->where('page_type', 'home')->first();
        $homePageSections = $homePage ? ($homePage->merged_sections ?? []) : [];

        if ($homePage) {
            $heroData = $homePage->getSectionData('hero');
            if (!empty($heroData['heading']))    $profile['name']         = $heroData['heading'];
            if (!empty($heroData['subheading'])) $profile['tagline']      = $heroData['subheading'];
            if (!empty($heroData['cta_text']))   $profile['cta_text']     = $heroData['cta_text'];
            if (!empty($heroData['cta_url']))    $profile['booking_link'] = $heroData['cta_url'];

            $aboutData = $homePage->getSectionData('about');
            if (!empty($aboutData['text']))      $profile['about']        = $aboutData['text'];

            $statsData = $homePage->getSectionData('stats');
            if (!empty($statsData['items']))     $profile['stats']        = $statsData['items'];

            $servicesData = $homePage->getSectionData('services');
            if (!empty($servicesData['items']))  $profile['services']     = $servicesData['items'];

            $followersData = $homePage->getSectionData('followers');
            if (!empty($followersData['instagram'])) $profile['instagram_followers'] = $followersData['instagram'];
            if (!empty($followersData['youtube']))    $profile['youtube_subscribers'] = $followersData['youtube'];
            if (!empty($followersData['twitter']))    $profile['twitter_followers']   = $followersData['twitter'];
            if (!empty($followersData['tiktok']))     $profile['tiktok_followers']    = $followersData['tiktok'];
            if (!empty($followersData['total']))      $profile['followers_total']     = $followersData['total'];

            $venturesData = $homePage->getSectionData('ventures');
            if (!empty($venturesData['items']))  $profile['ventures']     = $venturesData['items'];

            $agendaData = $homePage->getSectionData('agenda');
            if (!empty($agendaData['items']))    $profile['agenda']       = $agendaData['items'];

            $achievementsData = $homePage->getSectionData('achievements');
            if (!empty($achievementsData['items'])) $profile['achievements'] = $achievementsData['items'];

            $contactData = $homePage->getSectionData('contact');
            if (!empty($contactData['heading'])) $profile['contact_heading'] = $contactData['heading'];
            if (!empty($contactData['text']))     $profile['contact_text']    = $contactData['text'];
        }

        return view($view, compact(
            'tenant', 'experiences', 'socialLinks', 'activeJobs',
            'blogCategories', 'categoryPosts', 'featuredPost',
            'siteName', 'siteTagline', 'logoPath', 'faviconPath', 'accentColor', 'chatbotEnabled',
            'profile', 'template', 'homePage', 'homePageSections', 'allSettings'
        ));
    }

    public function about(Request $request, ?string $username = null)
    {
        $tenant   = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;
        if (!$tenant) abort(404);

        $socialLinks    = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();
        $experiences    = PortfolioExperience::where('user_id', $tenantId)->orderBy('start_date', 'desc')->get();
        $settings       = SiteSetting::where('user_id', $tenantId)->pluck('value', 'key')->toArray();
        $siteName       = $settings['site_name']    ?? $tenant->name;
        $logoPath       = $settings['logo_path']    ?? null;
        $faviconPath    = $settings['favicon_path'] ?? null;
        $accentColor    = $settings['color_accent'] ?? '#6366f1';
        $template       = $settings['profile_template'] ?? $tenant->getProfileTemplate() ?? 'consultant';
        $skills         = \App\Models\ProfileSkill::where('user_id', $tenantId)->orderBy('proficiency', 'desc')->get();
        $education      = \App\Models\ProfileEducation::where('user_id', $tenantId)->orderBy('start_date', 'desc')->get();
        $certifications = \App\Models\ProfileCertification::where('user_id', $tenantId)->orderBy('issue_date', 'desc')->get();
        $languages      = \App\Models\ProfileLanguage::where('user_id', $tenantId)->get();
        $aboutPage      = CustomPage::where('user_id', $tenantId)->where('slug', 'about')->first();
        $profile        = $this->loadProfile($tenantId, $tenant, $settings);

        return view('portfolio.about', compact(
            'tenant', 'socialLinks', 'experiences', 'settings',
            'siteName', 'logoPath', 'faviconPath', 'accentColor', 'template',
            'skills', 'education', 'certifications', 'languages', 'aboutPage', 'profile'
        ));
    }

    public function contact(Request $request, ?string $username = null)
    {
        $tenant   = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;
        if (!$tenant) abort(404);

        $settings    = SiteSetting::where('user_id', $tenantId)->pluck('value', 'key')->toArray();
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();
        $siteName    = $settings['site_name']    ?? $tenant->name;
        $logoPath    = $settings['logo_path']    ?? null;
        $faviconPath = $settings['favicon_path'] ?? null;
        $accentColor = $settings['color_accent'] ?? '#6366f1';
        $template    = $settings['profile_template'] ?? $tenant->getProfileTemplate() ?? 'consultant';
        $contactPage = CustomPage::where('user_id', $tenantId)->where('slug', 'contact')->first();
        $profile     = $this->loadProfile($tenantId, $tenant, $settings);

        return view('portfolio.contact', compact(
            'tenant', 'settings', 'socialLinks', 'siteName', 'logoPath', 'faviconPath',
            'accentColor', 'template', 'contactPage', 'profile'
        ));
    }

    public function services(Request $request, ?string $username = null)
    {
        $tenant   = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;
        if (!$tenant) abort(404);

        $settings    = SiteSetting::where('user_id', $tenantId)->pluck('value', 'key')->toArray();
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();
        $siteName    = $settings['site_name']    ?? $tenant->name;
        $logoPath    = $settings['logo_path']    ?? null;
        $faviconPath = $settings['favicon_path'] ?? null;
        $accentColor = $settings['color_accent'] ?? '#6366f1';
        $template    = $settings['profile_template'] ?? $tenant->getProfileTemplate() ?? 'consultant';
        $servicesPage = CustomPage::where('user_id', $tenantId)
            ->whereIn('slug', ['services', 'solutions', 'collaborations', 'practice-areas'])
            ->where('status', 'published')->first();
        $profile     = $this->loadProfile($tenantId, $tenant, $settings);

        return view('portfolio.services', compact(
            'tenant', 'settings', 'socialLinks', 'siteName', 'logoPath', 'faviconPath',
            'accentColor', 'template', 'servicesPage', 'profile'
        ));
    }

    public function portfolioPage(Request $request, ?string $username = null)
    {
        $tenant   = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;
        if (!$tenant) abort(404);

        $settings    = SiteSetting::where('user_id', $tenantId)->pluck('value', 'key')->toArray();
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();
        $siteName    = $settings['site_name']    ?? $tenant->name;
        $logoPath    = $settings['logo_path']    ?? null;
        $faviconPath = $settings['favicon_path'] ?? null;
        $accentColor = $settings['color_accent'] ?? '#6366f1';
        $template    = $settings['profile_template'] ?? $tenant->getProfileTemplate() ?? 'consultant';
        $portfolioPage = CustomPage::where('user_id', $tenantId)
            ->whereIn('slug', ['portfolio', 'case-studies', 'ventures', 'vision', 'initiatives'])
            ->where('status', 'published')->first();
        $profile     = $this->loadProfile($tenantId, $tenant, $settings);

        return view('portfolio.portfolio-page', compact(
            'tenant', 'settings', 'socialLinks', 'siteName', 'logoPath', 'faviconPath',
            'accentColor', 'template', 'portfolioPage', 'profile'
        ));
    }

    public function blog(Request $request, ?string $username = null)
    {
        $tenant   = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;
        if (!$tenant) abort(404);

        $query = BlogPost::where('status', 'published')->where('user_id', $tenantId);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('category')) {
            $cat = BlogCategory::where('slug', $request->category)->where('user_id', $tenantId)->first();
            if ($cat) $query->where('category_id', $cat->id);
        }
        $posts       = $query->orderBy('published_at', 'desc')->paginate(9);
        $categories  = BlogCategory::where('user_id', $tenantId)->get();
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();
        $settings    = SiteSetting::where('user_id', $tenantId)->pluck('value', 'key')->toArray();
        $siteName    = $settings['site_name']    ?? $tenant->name;
        $logoPath    = $settings['logo_path']    ?? null;
        $faviconPath = $settings['favicon_path'] ?? null;
        $accentColor = $settings['color_accent'] ?? '#6366f1';
        $template    = $settings['profile_template'] ?? $tenant->getProfileTemplate() ?? 'consultant';

        return view('portfolio.blog', compact(
            'posts', 'categories', 'socialLinks', 'tenant',
            'siteName', 'logoPath', 'faviconPath', 'accentColor', 'template', 'settings'
        ));
    }

    public function blogCategory(Request $request, string $slug, ?string $username = null)
    {
        $tenant   = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;
        if (!$tenant) abort(404);

        $category    = BlogCategory::where('slug', $slug)->where('user_id', $tenantId)->firstOrFail();
        $posts       = BlogPost::where('status', 'published')
            ->where('category_id', $category->id)->where('user_id', $tenantId)
            ->orderBy('published_at', 'desc')->paginate(9);
        $categories  = BlogCategory::where('user_id', $tenantId)->get();
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();
        $settings    = SiteSetting::where('user_id', $tenantId)->pluck('value', 'key')->toArray();
        $siteName    = $settings['site_name']    ?? $tenant->name;
        $accentColor = $settings['color_accent'] ?? '#6366f1';
        $template    = $settings['profile_template'] ?? $tenant->getProfileTemplate() ?? 'consultant';

        return view('portfolio.blog', compact(
            'posts', 'categories', 'category', 'socialLinks', 'tenant',
            'siteName', 'accentColor', 'template', 'settings'
        ));
    }

    public function blogShow(Request $request, string $slug, ?string $username = null)
    {
        $tenant   = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;
        if (!$tenant) abort(404);

        $post = BlogPost::where('slug', $slug)->where('status', 'published')
            ->where('user_id', $tenantId)->firstOrFail();
        $post->increment('views_count');

        $relatedPosts = BlogPost::where('status', 'published')->where('user_id', $tenantId)
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn($q) => $q->where('category_id', $post->category_id))
            ->orderBy('published_at', 'desc')->take(3)->get();

        $comments    = BlogComment::where('blog_post_id', $post->id)->where('is_approved', true)->orderBy('created_at')->get();
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();
        $settings    = SiteSetting::where('user_id', $tenantId)->pluck('value', 'key')->toArray();
        $siteName    = $settings['site_name']    ?? $tenant->name;
        $logoPath    = $settings['logo_path']    ?? null;
        $faviconPath = $settings['favicon_path'] ?? null;
        $accentColor = $settings['color_accent'] ?? '#6366f1';
        $template    = $settings['profile_template'] ?? $tenant->getProfileTemplate() ?? 'consultant';

        return view('portfolio.blog-show', compact(
            'post', 'relatedPosts', 'comments', 'socialLinks', 'tenant',
            'siteName', 'logoPath', 'faviconPath', 'accentColor', 'template', 'settings'
        ));
    }

    public function submitComment(Request $request, string $slug)
    {
        $request->validate([
            'comment'       => ['required', 'string', 'max:1000'],
            'visitor_name'  => ['nullable', 'string', 'max:100'],
            'visitor_email' => ['nullable', 'email', 'max:255'],
        ]);
        $post = BlogPost::where('slug', $slug)->where('status', 'published')->firstOrFail();
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

    public function jobs(Request $request, ?string $username = null)
    {
        $tenant   = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;
        if (!$tenant) abort(404);

        $query = Job::where('status', 'active')->where('user_id', $tenantId);
        if ($request->filled('type'))     $query->where('type', $request->type);
        if ($request->filled('location')) $query->where('location', 'like', '%' . $request->location . '%');
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        $jobs        = $query->orderBy('created_at', 'desc')->paginate(10);
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();
        $settings    = SiteSetting::where('user_id', $tenantId)->pluck('value', 'key')->toArray();
        $siteName    = $settings['site_name']    ?? $tenant->name;
        $accentColor = $settings['color_accent'] ?? '#6366f1';
        $template    = $settings['profile_template'] ?? $tenant->getProfileTemplate() ?? 'consultant';

        return view('portfolio.jobs', compact('jobs', 'socialLinks', 'tenant', 'settings', 'siteName', 'accentColor', 'template'));
    }

    public function jobShow(Request $request, string $slug, ?string $username = null)
    {
        $tenant   = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;
        $query    = Job::where('slug', $slug)->where('status', 'active');
        if ($tenantId) $query->where('user_id', $tenantId);
        $job         = $query->firstOrFail();
        $socialLinks = SocialLink::where('user_id', $tenantId)->where('is_active', true)->get();
        $settings    = SiteSetting::where('user_id', $tenantId)->pluck('value', 'key')->toArray();
        $siteName    = $settings['site_name']    ?? $tenant->name;
        $accentColor = $settings['color_accent'] ?? '#6366f1';

        return view('portfolio.job-show', compact('job', 'socialLinks', 'tenant', 'settings', 'siteName', 'accentColor'));
    }

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

    public function customPage(Request $request, string $slugOrUsername, ?string $slug = null)
    {
        if ($slug === null) {
            $slug   = $slugOrUsername;
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
        $profile     = $this->loadProfile($tenant->id, $tenant, $settings);

        return view('tenant.custom-page', compact(
            'tenant', 'page', 'template', 'siteName', 'logo', 'favicon', 'accent', 'socialLinks', 'settings', 'profile'
        ));
    }
}
