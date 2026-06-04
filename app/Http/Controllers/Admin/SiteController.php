<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\CustomPage;
use App\Models\SiteMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SiteController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // Helper: resolve tenant user id
    // ─────────────────────────────────────────────────────────────
    protected function tenantId(): int
    {
        return auth()->user()->getTenantId();
    }

    protected function setting(string $key, $default = null)
    {
        return SiteSetting::forTenant($this->tenantId())->where('key', $key)->value('value') ?? $default;
    }

    protected function setSetting(string $key, $value): void
    {
        SiteSetting::setValueForTenant($this->tenantId(), $key, $value);
    }

    // ─────────────────────────────────────────────────────────────
    // Site Builder Hub
    // ─────────────────────────────────────────────────────────────
    public function index()
    {
        $tid = $this->tenantId();
        $activeTheme   = $this->setting('profile_template', 'consultant');
        $pageCount     = CustomPage::where('user_id', $tid)->count();
        $menuItemCount = SiteMenu::where('user_id', $tid)->count();
        $logo          = $this->setting('logo_path');
        $favicon       = $this->setting('favicon_path');
        $siteName      = $this->setting('site_name', auth()->user()->name);

        return view('admin.site.index', compact(
            'activeTheme', 'pageCount', 'menuItemCount', 'logo', 'favicon', 'siteName'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // Theme Store
    // ─────────────────────────────────────────────────────────────
    public function themes()
    {
        $activeTheme = $this->setting('profile_template', 'consultant');
        $themes      = $this->getThemeDefinitions();

        return view('admin.site.themes', compact('activeTheme', 'themes'));
    }

    public function activateTheme(Request $request)
    {
        $request->validate(['theme' => 'required|string|in:consultant,influencer,advocate,entrepreneur,doctor,politician']);
        $this->setSetting('profile_template', $request->theme);

        return response()->json(['success' => true, 'theme' => $request->theme]);
    }

    protected function getThemeDefinitions(): array
    {
        return [
            'consultant' => [
                'id'          => 'consultant',
                'name'        => 'Nexus Pro',
                'category'    => 'IT & Consulting',
                'description' => 'Dark, minimal, ultra-professional. Built for IT consultants, solution architects, and tech executives who want to command authority online.',
                'tags'        => ['Dark', 'Minimal', 'Tech', 'Corporate'],
                'accent'      => '#6366f1',
                'bg'          => '#0a0a0a',
                'preview_css' => 'bg:#0a0a0a;text:#fff;accent:#6366f1;card:#1a1a1a',
                'hero_title'  => 'Enterprise Technology Consultant',
                'hero_sub'    => 'Cloud · AI · Digital Transformation',
                'sections'    => ['Hero', 'About', 'Services', 'Experience', 'Blog', 'Contact'],
                'best_for'    => 'IT Professionals, Cloud Architects, CTO/CIO',
                'premium'     => true,
            ],
            'influencer' => [
                'id'          => 'influencer',
                'name'        => 'Aura',
                'category'    => 'Lifestyle & Influencer',
                'description' => 'Vibrant, warm, and visually stunning. Designed for lifestyle influencers, content creators, and personal brands who live in colour.',
                'tags'        => ['Colorful', 'Bold', 'Social', 'Creative'],
                'accent'      => '#f43f5e',
                'bg'          => '#fff7f7',
                'preview_css' => 'bg:#fff7f7;text:#1a1a1a;accent:#f43f5e;card:#fff',
                'hero_title'  => 'Lifestyle Influencer & Content Creator',
                'hero_sub'    => 'Fashion · Travel · Beauty',
                'sections'    => ['Hero', 'About', 'Portfolio', 'Collaborations', 'Blog', 'Contact'],
                'best_for'    => 'Influencers, Bloggers, YouTubers, Creators',
                'premium'     => true,
            ],
            'advocate' => [
                'id'          => 'advocate',
                'name'        => 'Lex',
                'category'    => 'Legal & Advocacy',
                'description' => 'Authoritative, trust-building, and clean. The perfect digital presence for lawyers, advocates, and legal consultants.',
                'tags'        => ['Professional', 'Trust', 'Clean', 'Legal'],
                'accent'      => '#0ea5e9',
                'bg'          => '#f8fafc',
                'preview_css' => 'bg:#f8fafc;text:#0f172a;accent:#0ea5e9;card:#fff',
                'hero_title'  => 'Senior Advocate & Legal Consultant',
                'hero_sub'    => 'Corporate Law · Civil Litigation · IP',
                'sections'    => ['Hero', 'Practice Areas', 'About', 'Vacancies', 'Blog', 'Contact'],
                'best_for'    => 'Lawyers, Advocates, Legal Consultants',
                'premium'     => true,
            ],
            'entrepreneur' => [
                'id'          => 'entrepreneur',
                'name'        => 'Momentum',
                'category'    => 'Business & Startup',
                'description' => 'Bold, energetic, and conversion-focused. Built for founders, startup CEOs, and business coaches who mean business.',
                'tags'        => ['Bold', 'Startup', 'Growth', 'Business'],
                'accent'      => '#f59e0b',
                'bg'          => '#0f0f0f',
                'preview_css' => 'bg:#0f0f0f;text:#fff;accent:#f59e0b;card:#1c1c1c',
                'hero_title'  => 'Founder, Investor & Business Coach',
                'hero_sub'    => 'Startups · Growth · Leadership',
                'sections'    => ['Hero', 'About', 'Portfolio', 'Services', 'Blog', 'Contact'],
                'best_for'    => 'Founders, CEOs, Business Coaches, Investors',
                'premium'     => true,
            ],
            'doctor' => [
                'id'          => 'doctor',
                'name'        => 'Vitae',
                'category'    => 'Healthcare & Medical',
                'description' => 'Calm, reassuring, and clinically clean. Designed for doctors, specialists, and healthcare professionals who put patients first.',
                'tags'        => ['Clean', 'Medical', 'Calm', 'Trust'],
                'accent'      => '#10b981',
                'bg'          => '#f0fdf4',
                'preview_css' => 'bg:#f0fdf4;text:#064e3b;accent:#10b981;card:#fff',
                'hero_title'  => 'Consultant Physician & Specialist',
                'hero_sub'    => 'Internal Medicine · Cardiology · Wellness',
                'sections'    => ['Hero', 'About', 'Specialisations', 'Appointments', 'Blog', 'Contact'],
                'best_for'    => 'Doctors, Surgeons, Specialists, Clinics',
                'premium'     => true,
            ],
            'politician' => [
                'id'          => 'politician',
                'name'        => 'Civitas',
                'category'    => 'Politics & Public Service',
                'description' => 'Patriotic, powerful, and people-first. Built for politicians, public servants, and civic leaders who want to connect with constituents.',
                'tags'        => ['Patriotic', 'Bold', 'Community', 'Leadership'],
                'accent'      => '#dc2626',
                'bg'          => '#fafafa',
                'preview_css' => 'bg:#fafafa;text:#111;accent:#dc2626;card:#fff',
                'hero_title'  => 'Public Servant & Community Leader',
                'hero_sub'    => 'Governance · Development · People',
                'sections'    => ['Hero', 'Vision', 'Achievements', 'Events', 'Blog', 'Contact'],
                'best_for'    => 'Politicians, Public Servants, NGO Leaders',
                'premium'     => true,
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // Page Manager
    // ─────────────────────────────────────────────────────────────
    public function pages()
    {
        $pages = CustomPage::where('user_id', $this->tenantId())
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();

        return view('admin.site.pages', compact('pages'));
    }

    public function createPage()
    {
        return view('admin.site.page-form', ['page' => null]);
    }

    public function storePage(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'slug'    => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'status'  => 'required|in:published,draft',
            'show_in_menu' => 'boolean',
        ]);

        $slug = $request->slug
            ? Str::slug($request->slug)
            : Str::slug($request->title);

        // Ensure slug is unique for this tenant
        $baseSlug = $slug;
        $i = 1;
        while (CustomPage::where('user_id', $this->tenantId())->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        CustomPage::create([
            'user_id'      => $this->tenantId(),
            'title'        => $request->title,
            'slug'         => $slug,
            'content'      => $request->content,
            'meta_title'   => $request->meta_title,
            'meta_desc'    => $request->meta_desc,
            'status'       => $request->status,
            'show_in_menu' => $request->boolean('show_in_menu'),
            'sort_order'   => CustomPage::where('user_id', $this->tenantId())->max('sort_order') + 1,
        ]);

        return redirect()->route('admin.site.pages')->with('success', 'Page "' . $request->title . '" created successfully.');
    }

    public function editPage(CustomPage $page)
    {
        $this->authorisePage($page);
        return view('admin.site.page-form', compact('page'));
    }

    public function updatePage(Request $request, CustomPage $page)
    {
        $this->authorisePage($page);

        $request->validate([
            'title'   => 'required|string|max:255',
            'slug'    => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'status'  => 'required|in:published,draft',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);

        // Ensure slug unique (excluding self)
        $baseSlug = $slug;
        $i = 1;
        while (CustomPage::where('user_id', $this->tenantId())->where('slug', $slug)->where('id', '!=', $page->id)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        $page->update([
            'title'        => $request->title,
            'slug'         => $slug,
            'content'      => $request->content,
            'meta_title'   => $request->meta_title,
            'meta_desc'    => $request->meta_desc,
            'status'       => $request->status,
            'show_in_menu' => $request->boolean('show_in_menu'),
        ]);

        return redirect()->route('admin.site.pages')->with('success', 'Page updated successfully.');
    }

    public function destroyPage(CustomPage $page)
    {
        $this->authorisePage($page);
        $page->delete();
        return redirect()->route('admin.site.pages')->with('success', 'Page deleted.');
    }

    protected function authorisePage(CustomPage $page): void
    {
        if ($page->user_id !== $this->tenantId()) {
            abort(403);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Menu Builder
    // ─────────────────────────────────────────────────────────────
    public function menu()
    {
        $items = SiteMenu::where('user_id', $this->tenantId())
            ->orderBy('sort_order')
            ->get();

        $customPages = CustomPage::where('user_id', $this->tenantId())
            ->where('status', 'published')
            ->get();

        $systemPages = $this->getSystemPages();

        return view('admin.site.menu', compact('items', 'customPages', 'systemPages'));
    }

    public function saveMenu(Request $request)
    {
        $request->validate([
            'items'               => 'required|array',
            'items.*.label'       => 'required|string|max:100',
            'items.*.url'         => 'required|string|max:500',
            'items.*.target'      => 'nullable|in:_self,_blank',
            'items.*.icon'        => 'nullable|string|max:100',
            'items.*.parent_id'   => 'nullable|integer',
        ]);

        // Delete existing and recreate
        SiteMenu::where('user_id', $this->tenantId())->delete();

        foreach ($request->items as $i => $item) {
            SiteMenu::create([
                'user_id'    => $this->tenantId(),
                'label'      => $item['label'],
                'url'        => $item['url'],
                'target'     => $item['target'] ?? '_self',
                'icon'       => $item['icon'] ?? null,
                'parent_id'  => $item['parent_id'] ?? null,
                'sort_order' => $i,
            ]);
        }

        return response()->json(['success' => true, 'count' => count($request->items)]);
    }

    protected function getSystemPages(): array
    {
        $username = auth()->user()->username;
        return [
            ['label' => 'Home',    'url' => '/' . $username],
            ['label' => 'About',   'url' => '/' . $username . '/about'],
            ['label' => 'Blog',    'url' => '/' . $username . '/blog'],
            ['label' => 'Jobs',    'url' => '/' . $username . '/jobs'],
            ['label' => 'Shop',    'url' => '/' . $username . '/shop'],
            ['label' => 'Forum',   'url' => '/' . $username . '/forum'],
            ['label' => 'Contact', 'url' => '/' . $username . '/contact'],
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // Branding (Logo + Favicon)
    // ─────────────────────────────────────────────────────────────
    public function branding()
    {
        $tid      = $this->tenantId();
        $logo     = $this->setting('logo_path');
        $favicon  = $this->setting('favicon_path');
        $siteName = $this->setting('site_name', auth()->user()->name);
        $tagline  = $this->setting('site_tagline', '');
        $colorAccent = $this->setting('color_accent', '#6366f1');
        $colorBg     = $this->setting('color_bg', '#0a0a0a');

        return view('admin.site.branding', compact('logo', 'favicon', 'siteName', 'tagline', 'colorAccent', 'colorBg'));
    }

    public function saveBranding(Request $request)
    {
        $request->validate([
            'site_name'    => 'required|string|max:100',
            'site_tagline' => 'nullable|string|max:200',
            'color_accent' => 'nullable|string|max:20',
            'color_bg'     => 'nullable|string|max:20',
            'logo'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'favicon'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:1024',
        ]);

        $tid = $this->tenantId();
        $username = auth()->user()->username ?? 'tenant_' . $tid;

        $this->setSetting('site_name', $request->site_name);
        $this->setSetting('site_tagline', $request->site_tagline ?? '');
        $this->setSetting('color_accent', $request->color_accent ?? '#6366f1');
        $this->setSetting('color_bg', $request->color_bg ?? '#0a0a0a');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = 'tenants/' . $username . '/logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('tenants/' . $username), 'logo.' . $file->getClientOriginalExtension());
            $this->setSetting('logo_path', '/tenants/' . $username . '/logo.' . $file->getClientOriginalExtension());
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $ext  = $file->getClientOriginalExtension();
            @mkdir(public_path('tenants/' . $username), 0755, true);
            $file->move(public_path('tenants/' . $username), 'favicon.' . $ext);
            $this->setSetting('favicon_path', '/tenants/' . $username . '/favicon.' . $ext);
        }

        return redirect()->route('admin.site.branding')->with('success', 'Branding updated successfully.');
    }

    // ─────────────────────────────────────────────────────────────
    // Dashboard Mode (light/dark) — AJAX
    // ─────────────────────────────────────────────────────────────
    public function saveDashboardMode(Request $request)
    {
        $request->validate(['mode' => 'required|in:dark,light']);
        $this->setSetting('dashboard_mode', $request->mode);
        return response()->json(['success' => true, 'mode' => $request->mode]);
    }
}
