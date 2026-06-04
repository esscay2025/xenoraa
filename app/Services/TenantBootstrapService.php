<?php

namespace App\Services;

use App\Models\User;
use App\Models\SiteSetting;
use App\Models\CustomPage;
use App\Models\SiteMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * TenantBootstrapService
 *
 * Provisions a complete, professional default site for a new (or existing) tenant.
 * Called on registration and when a tenant activates a theme.
 *
 * For each theme it creates:
 *  - site_settings  (name, tagline, template, colours, chatbot, AI name)
 *  - custom_pages   (Home, About, Services/Solutions, Blog, Contact + extras)
 *  - site_menus     (primary nav items)
 *  - chatbot_training (profession-specific Q&A pairs)
 */
class TenantBootstrapService
{
    // ─────────────────────────────────────────────────────────────────────────
    // Public entry points
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Bootstrap a brand-new tenant (called from RegisteredUserController).
     */
    public function bootstrapNewTenant(User $user): void
    {
        $template = $this->guessTemplate($user);
        $this->provision($user, $template, force: false);
    }

    /**
     * Re-provision an existing tenant when they activate a new theme.
     * Pages/menus that already exist are NOT deleted — only missing ones are added.
     */
    public function activateTheme(User $user, string $template): void
    {
        $this->provision($user, $template, force: false);
    }

    /**
     * Full re-provision (wipes existing pages/menus for this tenant and rebuilds).
     * Use with caution — only called from admin "Reset to Default" action.
     */
    public function resetToDefault(User $user): void
    {
        $template = SiteSetting::getValueForTenant($user->id, 'profile_template', 'consultant');
        CustomPage::where('user_id', $user->id)->delete();
        SiteMenu::where('user_id', $user->id)->delete();
        $this->provision($user, $template, force: true);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Core provisioning
    // ─────────────────────────────────────────────────────────────────────────

    private function provision(User $user, string $template, bool $force): void
    {
        $config = $this->getThemeConfig($template, $user);

        // 1. Site settings
        $this->seedSettings($user->id, $config['settings']);

        // 2. Pages
        $this->seedPages($user->id, $config['pages'], $force);

        // 3. Menus
        $this->seedMenus($user->id, $config['menus'], $force);

        // 4. Chatbot training
        $this->seedChatbotTraining($user->id, $config['training'], $force);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function guessTemplate(User $user): string
    {
        $existing = SiteSetting::getValueForTenant($user->id, 'profile_template', null);
        if ($existing) return $existing;

        $profession = strtolower($user->profession ?? '');
        if (str_contains($profession, 'influencer') || str_contains($profession, 'creator') || str_contains($profession, 'lifestyle')) return 'influencer';
        if (str_contains($profession, 'advocate') || str_contains($profession, 'lawyer') || str_contains($profession, 'legal')) return 'advocate';
        if (str_contains($profession, 'doctor') || str_contains($profession, 'physician') || str_contains($profession, 'health')) return 'doctor';
        if (str_contains($profession, 'entrepreneur') || str_contains($profession, 'startup') || str_contains($profession, 'business')) return 'entrepreneur';
        if (str_contains($profession, 'politi') || str_contains($profession, 'government') || str_contains($profession, 'public')) return 'politician';
        return 'consultant';
    }

    private function seedSettings(int $userId, array $settings): void
    {
        foreach ($settings as $key => $value) {
            $existing = SiteSetting::where('user_id', $userId)->where('key', $key)->first();
            if (!$existing) {
                SiteSetting::create(['user_id' => $userId, 'key' => $key, 'value' => $value]);
            }
        }
    }

    private function seedPages(int $userId, array $pages, bool $force): void
    {
        foreach ($pages as $page) {
            $exists = CustomPage::where('user_id', $userId)->where('slug', $page['slug'])->exists();
            if ($force || !$exists) {
                if ($force) CustomPage::where('user_id', $userId)->where('slug', $page['slug'])->delete();
                CustomPage::create(array_merge($page, [
                    'user_id'    => $userId,
                    'status'     => 'published',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    private function seedMenus(int $userId, array $menus, bool $force): void
    {
        if ($force) SiteMenu::where('user_id', $userId)->delete();
        if (SiteMenu::where('user_id', $userId)->exists() && !$force) return;

        foreach ($menus as $i => $item) {
            SiteMenu::create([
                'user_id'    => $userId,
                'label'      => $item['label'],
                'url'        => $item['url'],
                'target'     => $item['target'] ?? '_self',
                'sort_order' => $i + 1,
                'parent_id'  => null,
                'is_active'  => true,
            ]);
        }
    }

    private function seedChatbotTraining(int $userId, array $training, bool $force): void
    {
        if ($force) DB::table('chatbot_training')->where('user_id', $userId)->delete();
        if (DB::table('chatbot_training')->where('user_id', $userId)->exists() && !$force) return;

        foreach ($training as $i => $item) {
            DB::table('chatbot_training')->insert([
                'user_id'    => $userId,
                'category'   => $item['category'],
                'question'   => $item['question'],
                'answer'     => $item['answer'],
                'is_active'  => true,
                'sort_order' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Theme configurations
    // ─────────────────────────────────────────────────────────────────────────

    private function getThemeConfig(string $template, User $user): array
    {
        $name = $user->name ?? 'Your Name';
        $email = $user->email ?? 'hello@example.com';
        $username = $user->username ?? 'user';

        return match ($template) {
            'influencer'  => $this->influencerConfig($name, $email, $username),
            'advocate'    => $this->advocateConfig($name, $email, $username),
            'doctor'      => $this->doctorConfig($name, $email, $username),
            'entrepreneur'=> $this->entrepreneurConfig($name, $email, $username),
            'politician'  => $this->politicianConfig($name, $email, $username),
            default       => $this->consultantConfig($name, $email, $username),
        };
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CONSULTANT / IT PROFESSIONAL theme
    // ─────────────────────────────────────────────────────────────────────────
    private function consultantConfig(string $name, string $email, string $username): array
    {
        return [
            'settings' => [
                'profile_template'    => 'consultant',
                'site_name'           => $name,
                'site_tagline'        => 'Senior IT Consultant & Digital Transformation Specialist',
                'accent_color'        => '#6366f1',
                'chatbot_enabled'     => '1',
                'ai_assistant_name'   => $name . ' AI',
                'ai_assistant_tagline'=> 'Ask me about IT consulting, cloud, and digital transformation',
            ],
            'pages' => [
                [
                    'title'      => 'Home',
                    'slug'       => 'home',
                    'page_type'  => 'home',
                    'meta_title' => $name . ' — IT Consultant',
                    'content'    => $this->consultantHomePage($name),
                ],
                [
                    'title'      => 'About',
                    'slug'       => 'about',
                    'page_type'  => 'about',
                    'meta_title' => 'About ' . $name,
                    'content'    => $this->consultantAboutPage($name),
                ],
                [
                    'title'      => 'Services',
                    'slug'       => 'services',
                    'page_type'  => 'services',
                    'meta_title' => 'IT Consulting Services — ' . $name,
                    'content'    => $this->consultantServicesPage($name),
                ],
                [
                    'title'      => 'Portfolio',
                    'slug'       => 'portfolio',
                    'page_type'  => 'portfolio',
                    'meta_title' => 'Portfolio — ' . $name,
                    'content'    => $this->consultantPortfolioPage($name),
                ],
                [
                    'title'      => 'Blog',
                    'slug'       => 'blog',
                    'page_type'  => 'blog',
                    'meta_title' => 'Tech Insights — ' . $name,
                    'content'    => '<p>Read the latest articles on cloud computing, AI, and digital transformation.</p>',
                ],
                [
                    'title'      => 'Contact',
                    'slug'       => 'contact',
                    'page_type'  => 'contact',
                    'meta_title' => 'Contact ' . $name,
                    'content'    => $this->contactPage($name, $email),
                ],
            ],
            'menus' => [
                ['label' => 'Home',      'url' => '/'],
                ['label' => 'About',     'url' => '/about'],
                ['label' => 'Services',  'url' => '/services'],
                ['label' => 'Portfolio', 'url' => '/portfolio'],
                ['label' => 'Blog',      'url' => '/blog'],
                ['label' => 'Contact',   'url' => '/contact'],
            ],
            'training' => $this->consultantTraining($name),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // INFLUENCER theme
    // ─────────────────────────────────────────────────────────────────────────
    private function influencerConfig(string $name, string $email, string $username): array
    {
        return [
            'settings' => [
                'profile_template'    => 'influencer',
                'site_name'           => $name,
                'site_tagline'        => 'Lifestyle Creator · Fashion · Travel · Wellness',
                'accent_color'        => '#f43f5e',
                'chatbot_enabled'     => '1',
                'ai_assistant_name'   => $name . ' AI',
                'ai_assistant_tagline'=> 'Ask me about collaborations, content, and lifestyle tips',
            ],
            'pages' => [
                [
                    'title'      => 'Home',
                    'slug'       => 'home',
                    'page_type'  => 'home',
                    'meta_title' => $name . ' — Lifestyle Creator',
                    'content'    => $this->influencerHomePage($name),
                ],
                [
                    'title'      => 'About Me',
                    'slug'       => 'about',
                    'page_type'  => 'about',
                    'meta_title' => 'About ' . $name,
                    'content'    => $this->influencerAboutPage($name),
                ],
                [
                    'title'      => 'Collaborations',
                    'slug'       => 'collaborations',
                    'page_type'  => 'services',
                    'meta_title' => 'Brand Collaborations — ' . $name,
                    'content'    => $this->influencerCollaborationsPage($name),
                ],
                [
                    'title'      => 'Shop My Picks',
                    'slug'       => 'shop',
                    'page_type'  => 'shop',
                    'meta_title' => 'Shop — ' . $name,
                    'content'    => '<p>Discover my curated collection of favourite products across fashion, beauty, and lifestyle.</p>',
                ],
                [
                    'title'      => 'Blog',
                    'slug'       => 'blog',
                    'page_type'  => 'blog',
                    'meta_title' => 'Blog — ' . $name,
                    'content'    => '<p>Stories, tips, and inspiration from my everyday life.</p>',
                ],
                [
                    'title'      => 'Contact',
                    'slug'       => 'contact',
                    'page_type'  => 'contact',
                    'meta_title' => 'Contact ' . $name,
                    'content'    => $this->contactPage($name, $email),
                ],
            ],
            'menus' => [
                ['label' => 'Home',           'url' => '/'],
                ['label' => 'About',          'url' => '/about'],
                ['label' => 'Collaborations', 'url' => '/collaborations'],
                ['label' => 'Shop',           'url' => '/shop'],
                ['label' => 'Blog',           'url' => '/blog'],
                ['label' => 'Contact',        'url' => '/contact'],
            ],
            'training' => $this->influencerTraining($name),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ADVOCATE / LEGAL theme
    // ─────────────────────────────────────────────────────────────────────────
    private function advocateConfig(string $name, string $email, string $username): array
    {
        return [
            'settings' => [
                'profile_template'    => 'advocate',
                'site_name'           => $name . ', Advocate',
                'site_tagline'        => 'Corporate Law · Intellectual Property · Dispute Resolution',
                'accent_color'        => '#0ea5e9',
                'chatbot_enabled'     => '1',
                'ai_assistant_name'   => $name . ' Legal AI',
                'ai_assistant_tagline'=> 'Ask about legal services, consultations, and case types',
            ],
            'pages' => [
                [
                    'title'      => 'Home',
                    'slug'       => 'home',
                    'page_type'  => 'home',
                    'meta_title' => $name . ' — Advocate & Legal Consultant',
                    'content'    => $this->advocateHomePage($name),
                ],
                [
                    'title'      => 'About',
                    'slug'       => 'about',
                    'page_type'  => 'about',
                    'meta_title' => 'About ' . $name,
                    'content'    => $this->advocateAboutPage($name),
                ],
                [
                    'title'      => 'Practice Areas',
                    'slug'       => 'practice-areas',
                    'page_type'  => 'services',
                    'meta_title' => 'Practice Areas — ' . $name,
                    'content'    => $this->advocatePracticeAreasPage($name),
                ],
                [
                    'title'      => 'Case Studies',
                    'slug'       => 'case-studies',
                    'page_type'  => 'portfolio',
                    'meta_title' => 'Case Studies — ' . $name,
                    'content'    => $this->advocateCaseStudiesPage($name),
                ],
                [
                    'title'      => 'Legal Insights',
                    'slug'       => 'blog',
                    'page_type'  => 'blog',
                    'meta_title' => 'Legal Insights — ' . $name,
                    'content'    => '<p>Articles on corporate law, consumer rights, and legal updates in India.</p>',
                ],
                [
                    'title'      => 'Vacancies',
                    'slug'       => 'vacancies',
                    'page_type'  => 'jobs',
                    'meta_title' => 'Vacancies — ' . $name,
                    'content'    => '<p>Current openings at our legal practice for junior advocates and legal researchers.</p>',
                ],
                [
                    'title'      => 'Contact',
                    'slug'       => 'contact',
                    'page_type'  => 'contact',
                    'meta_title' => 'Contact ' . $name,
                    'content'    => $this->contactPage($name, $email),
                ],
            ],
            'menus' => [
                ['label' => 'Home',           'url' => '/'],
                ['label' => 'About',          'url' => '/about'],
                ['label' => 'Practice Areas', 'url' => '/practice-areas'],
                ['label' => 'Case Studies',   'url' => '/case-studies'],
                ['label' => 'Legal Insights', 'url' => '/blog'],
                ['label' => 'Vacancies',      'url' => '/vacancies'],
                ['label' => 'Contact',        'url' => '/contact'],
            ],
            'training' => $this->advocateTraining($name),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DOCTOR / HEALTHCARE theme
    // ─────────────────────────────────────────────────────────────────────────
    private function doctorConfig(string $name, string $email, string $username): array
    {
        return [
            'settings' => [
                'profile_template'    => 'doctor',
                'site_name'           => 'Dr. ' . $name,
                'site_tagline'        => 'MBBS, MD · General Physician & Wellness Consultant',
                'accent_color'        => '#10b981',
                'chatbot_enabled'     => '1',
                'ai_assistant_name'   => 'Dr. ' . $name . ' AI',
                'ai_assistant_tagline'=> 'Ask about appointments, services, and health tips',
            ],
            'pages' => [
                [
                    'title'      => 'Home',
                    'slug'       => 'home',
                    'page_type'  => 'home',
                    'meta_title' => 'Dr. ' . $name . ' — General Physician',
                    'content'    => $this->doctorHomePage($name),
                ],
                [
                    'title'      => 'About',
                    'slug'       => 'about',
                    'page_type'  => 'about',
                    'meta_title' => 'About Dr. ' . $name,
                    'content'    => $this->doctorAboutPage($name),
                ],
                [
                    'title'      => 'Services',
                    'slug'       => 'services',
                    'page_type'  => 'services',
                    'meta_title' => 'Medical Services — Dr. ' . $name,
                    'content'    => $this->doctorServicesPage($name),
                ],
                [
                    'title'      => 'Appointments',
                    'slug'       => 'appointments',
                    'page_type'  => 'contact',
                    'meta_title' => 'Book an Appointment — Dr. ' . $name,
                    'content'    => $this->doctorAppointmentPage($name, $email),
                ],
                [
                    'title'      => 'Health Blog',
                    'slug'       => 'blog',
                    'page_type'  => 'blog',
                    'meta_title' => 'Health Blog — Dr. ' . $name,
                    'content'    => '<p>Evidence-based health articles and wellness tips from Dr. ' . $name . '.</p>',
                ],
                [
                    'title'      => 'Contact',
                    'slug'       => 'contact',
                    'page_type'  => 'contact',
                    'meta_title' => 'Contact Dr. ' . $name,
                    'content'    => $this->contactPage('Dr. ' . $name, $email),
                ],
            ],
            'menus' => [
                ['label' => 'Home',         'url' => '/'],
                ['label' => 'About',        'url' => '/about'],
                ['label' => 'Services',     'url' => '/services'],
                ['label' => 'Appointments', 'url' => '/appointments'],
                ['label' => 'Health Blog',  'url' => '/blog'],
                ['label' => 'Contact',      'url' => '/contact'],
            ],
            'training' => $this->doctorTraining($name),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ENTREPRENEUR / STARTUP theme
    // ─────────────────────────────────────────────────────────────────────────
    private function entrepreneurConfig(string $name, string $email, string $username): array
    {
        return [
            'settings' => [
                'profile_template'    => 'entrepreneur',
                'site_name'           => $name,
                'site_tagline'        => 'Entrepreneur · Startup Founder · Investor',
                'accent_color'        => '#f59e0b',
                'chatbot_enabled'     => '1',
                'ai_assistant_name'   => $name . ' AI',
                'ai_assistant_tagline'=> 'Ask about my ventures, investments, and startup ecosystem',
            ],
            'pages' => [
                [
                    'title'      => 'Home',
                    'slug'       => 'home',
                    'page_type'  => 'home',
                    'meta_title' => $name . ' — Entrepreneur & Startup Founder',
                    'content'    => $this->entrepreneurHomePage($name),
                ],
                [
                    'title'      => 'About',
                    'slug'       => 'about',
                    'page_type'  => 'about',
                    'meta_title' => 'About ' . $name,
                    'content'    => $this->entrepreneurAboutPage($name),
                ],
                [
                    'title'      => 'Ventures',
                    'slug'       => 'ventures',
                    'page_type'  => 'portfolio',
                    'meta_title' => 'Ventures — ' . $name,
                    'content'    => $this->entrepreneurVenturesPage($name),
                ],
                [
                    'title'      => 'Solutions',
                    'slug'       => 'solutions',
                    'page_type'  => 'services',
                    'meta_title' => 'Business Solutions — ' . $name,
                    'content'    => $this->entrepreneurSolutionsPage($name),
                ],
                [
                    'title'      => 'Blog',
                    'slug'       => 'blog',
                    'page_type'  => 'blog',
                    'meta_title' => 'Startup Insights — ' . $name,
                    'content'    => '<p>Lessons from building startups, raising capital, and scaling businesses.</p>',
                ],
                [
                    'title'      => 'Jobs',
                    'slug'       => 'jobs',
                    'page_type'  => 'jobs',
                    'meta_title' => 'Jobs — ' . $name,
                    'content'    => '<p>Join our growing team. We are always looking for talented people.</p>',
                ],
                [
                    'title'      => 'Contact',
                    'slug'       => 'contact',
                    'page_type'  => 'contact',
                    'meta_title' => 'Contact ' . $name,
                    'content'    => $this->contactPage($name, $email),
                ],
            ],
            'menus' => [
                ['label' => 'Home',      'url' => '/'],
                ['label' => 'About',     'url' => '/about'],
                ['label' => 'Ventures',  'url' => '/ventures'],
                ['label' => 'Solutions', 'url' => '/solutions'],
                ['label' => 'Blog',      'url' => '/blog'],
                ['label' => 'Jobs',      'url' => '/jobs'],
                ['label' => 'Contact',   'url' => '/contact'],
            ],
            'training' => $this->entrepreneurTraining($name),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POLITICIAN / PUBLIC SERVICE theme
    // ─────────────────────────────────────────────────────────────────────────
    private function politicianConfig(string $name, string $email, string $username): array
    {
        return [
            'settings' => [
                'profile_template'    => 'politician',
                'site_name'           => $name,
                'site_tagline'        => 'Public Servant · Community Leader · People\'s Voice',
                'accent_color'        => '#dc2626',
                'chatbot_enabled'     => '1',
                'ai_assistant_name'   => $name . ' AI',
                'ai_assistant_tagline'=> 'Ask about my vision, initiatives, and constituency work',
            ],
            'pages' => [
                [
                    'title'      => 'Home',
                    'slug'       => 'home',
                    'page_type'  => 'home',
                    'meta_title' => $name . ' — Public Servant',
                    'content'    => $this->politicianHomePage($name),
                ],
                [
                    'title'      => 'About',
                    'slug'       => 'about',
                    'page_type'  => 'about',
                    'meta_title' => 'About ' . $name,
                    'content'    => $this->politicianAboutPage($name),
                ],
                [
                    'title'      => 'Vision & Mission',
                    'slug'       => 'vision',
                    'page_type'  => 'services',
                    'meta_title' => 'Vision & Mission — ' . $name,
                    'content'    => $this->politicianVisionPage($name),
                ],
                [
                    'title'      => 'Initiatives',
                    'slug'       => 'initiatives',
                    'page_type'  => 'portfolio',
                    'meta_title' => 'Initiatives — ' . $name,
                    'content'    => $this->politicianInitiativesPage($name),
                ],
                [
                    'title'      => 'News',
                    'slug'       => 'blog',
                    'page_type'  => 'blog',
                    'meta_title' => 'News — ' . $name,
                    'content'    => '<p>Latest news, press releases, and updates from the constituency.</p>',
                ],
                [
                    'title'      => 'Contact',
                    'slug'       => 'contact',
                    'page_type'  => 'contact',
                    'meta_title' => 'Contact ' . $name,
                    'content'    => $this->contactPage($name, $email),
                ],
            ],
            'menus' => [
                ['label' => 'Home',        'url' => '/'],
                ['label' => 'About',       'url' => '/about'],
                ['label' => 'Vision',      'url' => '/vision'],
                ['label' => 'Initiatives', 'url' => '/initiatives'],
                ['label' => 'News',        'url' => '/blog'],
                ['label' => 'Contact',     'url' => '/contact'],
            ],
            'training' => $this->politicianTraining($name),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PAGE CONTENT GENERATORS
    // ─────────────────────────────────────────────────────────────────────────

    private function consultantHomePage(string $name): string
    {
        return <<<HTML
<section class="hero-section">
  <h1>Hi, I'm {$name}</h1>
  <h2>Senior IT Consultant &amp; Digital Transformation Specialist</h2>
  <p>I help enterprises modernise their technology infrastructure, migrate to the cloud, and build AI-powered workflows that drive measurable business outcomes. With over 12 years of experience across BFSI, healthcare, and e-commerce sectors, I deliver solutions that scale.</p>
  <div class="hero-cta">
    <a href="/contact" class="btn-primary">Hire Me</a>
    <a href="/portfolio" class="btn-secondary">View Portfolio</a>
  </div>
</section>

<section class="stats-section">
  <div class="stat"><span>150+</span><p>Projects Delivered</p></div>
  <div class="stat"><span>50+</span><p>Enterprise Clients</p></div>
  <div class="stat"><span>12+</span><p>Years Experience</p></div>
  <div class="stat"><span>98%</span><p>Client Satisfaction</p></div>
</section>

<section class="services-preview">
  <h2>What I Do</h2>
  <div class="services-grid">
    <div class="service-card">
      <i class="fas fa-cloud"></i>
      <h3>Cloud Architecture</h3>
      <p>AWS, Azure &amp; GCP migrations, multi-cloud strategies, and cost optimisation for enterprise workloads.</p>
    </div>
    <div class="service-card">
      <i class="fas fa-robot"></i>
      <h3>AI &amp; Automation</h3>
      <p>Building intelligent automation pipelines, LLM integrations, and data-driven decision systems.</p>
    </div>
    <div class="service-card">
      <i class="fas fa-shield-alt"></i>
      <h3>Cybersecurity</h3>
      <p>Zero-trust architecture, VAPT, compliance audits (ISO 27001, SOC 2), and incident response.</p>
    </div>
    <div class="service-card">
      <i class="fas fa-code"></i>
      <h3>Software Development</h3>
      <p>Full-stack web applications, API design, microservices, and DevOps pipeline setup.</p>
    </div>
  </div>
</section>

<section class="featured-work">
  <h2>Recent Projects</h2>
  <div class="projects-grid">
    <div class="project-card">
      <h3>Banking Core Modernisation</h3>
      <p>Led the migration of a legacy COBOL banking system to a cloud-native microservices architecture, reducing operational costs by 40%.</p>
      <span class="tag">AWS</span><span class="tag">Kubernetes</span><span class="tag">BFSI</span>
    </div>
    <div class="project-card">
      <h3>AI-Powered Customer Support</h3>
      <p>Deployed an LLM-based support automation system handling 80% of tier-1 queries, saving 2,000+ agent hours per month.</p>
      <span class="tag">GPT-4</span><span class="tag">Python</span><span class="tag">E-commerce</span>
    </div>
    <div class="project-card">
      <h3>Healthcare Data Platform</h3>
      <p>Built a HIPAA-compliant data lake and analytics platform processing 10M+ patient records with real-time dashboards.</p>
      <span class="tag">Azure</span><span class="tag">Databricks</span><span class="tag">Healthcare</span>
    </div>
  </div>
</section>

<section class="testimonials">
  <h2>What Clients Say</h2>
  <div class="testimonial-card">
    <p>"Exceptional technical depth combined with clear business communication. Delivered our cloud migration 3 weeks ahead of schedule."</p>
    <cite>— CTO, Leading Private Bank</cite>
  </div>
  <div class="testimonial-card">
    <p>"The AI automation solution transformed our customer service operations. ROI was visible within the first quarter."</p>
    <cite>— VP Engineering, E-commerce Platform</cite>
  </div>
</section>
HTML;
    }

    private function consultantAboutPage(string $name): string
    {
        return <<<HTML
<section class="about-hero">
  <h1>About {$name}</h1>
  <p class="subtitle">12+ years transforming enterprises through technology</p>
</section>

<section class="about-content">
  <div class="about-text">
    <h2>My Story</h2>
    <p>I began my career as a software engineer at a fintech startup, where I quickly discovered my passion for solving complex infrastructure challenges. Over the years, I transitioned into consulting, working with Fortune 500 companies and fast-growing startups across India, the UK, and the US.</p>
    <p>Today, I specialise in helping organisations navigate digital transformation — from legacy system modernisation to AI adoption and cloud-native architecture. My approach combines deep technical expertise with a strong understanding of business strategy.</p>

    <h2>Education &amp; Certifications</h2>
    <ul>
      <li>B.Tech in Computer Science — IIT Madras</li>
      <li>AWS Certified Solutions Architect — Professional</li>
      <li>Google Cloud Professional Data Engineer</li>
      <li>Certified Information Systems Security Professional (CISSP)</li>
      <li>PMP — Project Management Professional</li>
    </ul>

    <h2>Core Expertise</h2>
    <ul>
      <li>Cloud Architecture: AWS, Azure, GCP</li>
      <li>AI/ML: LLMs, RAG pipelines, MLOps</li>
      <li>DevOps: Kubernetes, Terraform, CI/CD</li>
      <li>Security: Zero-trust, VAPT, ISO 27001</li>
      <li>Languages: Python, Go, TypeScript, Java</li>
    </ul>
  </div>
</section>

<section class="experience-timeline">
  <h2>Career Timeline</h2>
  <div class="timeline">
    <div class="timeline-item">
      <span class="year">2022–Present</span>
      <h3>Independent IT Consultant</h3>
      <p>Working with enterprise clients on cloud strategy, AI integration, and digital transformation programmes.</p>
    </div>
    <div class="timeline-item">
      <span class="year">2018–2022</span>
      <h3>Principal Architect — TechCorp India</h3>
      <p>Led a 25-person engineering team delivering cloud-native solutions for BFSI and healthcare clients.</p>
    </div>
    <div class="timeline-item">
      <span class="year">2014–2018</span>
      <h3>Senior Software Engineer — GlobalSoft</h3>
      <p>Full-stack development and infrastructure management for SaaS products serving 500K+ users.</p>
    </div>
    <div class="timeline-item">
      <span class="year">2012–2014</span>
      <h3>Software Engineer — FinTech Startup</h3>
      <p>Built the core payment processing engine and API gateway for a B2B payments platform.</p>
    </div>
  </div>
</section>
HTML;
    }

    private function consultantServicesPage(string $name): string
    {
        return <<<HTML
<section class="services-hero">
  <h1>IT Consulting Services</h1>
  <p>End-to-end technology consulting for enterprises and high-growth startups</p>
</section>

<section class="services-list">
  <div class="service-detail">
    <h2><i class="fas fa-cloud"></i> Cloud Architecture &amp; Migration</h2>
    <p>I design and implement cloud-native architectures on AWS, Azure, and GCP. Whether you're migrating a legacy monolith or building a greenfield microservices platform, I ensure your infrastructure is scalable, secure, and cost-optimised.</p>
    <ul>
      <li>Cloud readiness assessment and roadmap</li>
      <li>Lift-and-shift and re-architecture migrations</li>
      <li>Multi-cloud and hybrid cloud strategies</li>
      <li>FinOps — cloud cost optimisation</li>
    </ul>
    <p class="price-range">Engagement from ₹2,50,000 / project</p>
  </div>

  <div class="service-detail">
    <h2><i class="fas fa-robot"></i> AI &amp; Machine Learning</h2>
    <p>From LLM-powered chatbots to predictive analytics platforms, I help organisations harness the power of AI to automate processes and gain competitive advantage.</p>
    <ul>
      <li>LLM integration and RAG pipeline development</li>
      <li>ML model development and MLOps setup</li>
      <li>AI-powered automation workflows</li>
      <li>Data engineering and analytics platforms</li>
    </ul>
    <p class="price-range">Engagement from ₹3,00,000 / project</p>
  </div>

  <div class="service-detail">
    <h2><i class="fas fa-shield-alt"></i> Cybersecurity &amp; Compliance</h2>
    <p>I conduct comprehensive security assessments and implement zero-trust architectures to protect your digital assets and ensure regulatory compliance.</p>
    <ul>
      <li>Vulnerability Assessment &amp; Penetration Testing (VAPT)</li>
      <li>ISO 27001 and SOC 2 compliance readiness</li>
      <li>Zero-trust network architecture</li>
      <li>Incident response planning</li>
    </ul>
    <p class="price-range">Engagement from ₹1,50,000 / project</p>
  </div>

  <div class="service-detail">
    <h2><i class="fas fa-code"></i> Software Development</h2>
    <p>Custom web applications, APIs, and microservices built with modern technology stacks and best-in-class engineering practices.</p>
    <ul>
      <li>Full-stack web application development</li>
      <li>REST and GraphQL API design</li>
      <li>DevOps pipeline setup (CI/CD, IaC)</li>
      <li>Code review and technical due diligence</li>
    </ul>
    <p class="price-range">Engagement from ₹1,00,000 / project</p>
  </div>
</section>

<section class="cta-section">
  <h2>Ready to Transform Your Technology?</h2>
  <p>Let's discuss your project and how I can help you achieve your goals.</p>
  <a href="/contact" class="btn-primary">Schedule a Free Consultation</a>
</section>
HTML;
    }

    private function consultantPortfolioPage(string $name): string
    {
        return <<<HTML
<section class="portfolio-hero">
  <h1>Portfolio</h1>
  <p>Selected projects across cloud, AI, and enterprise software</p>
</section>

<section class="portfolio-grid">
  <div class="portfolio-item">
    <div class="portfolio-header">
      <h3>Banking Core Modernisation</h3>
      <span class="industry-tag">BFSI</span>
    </div>
    <p><strong>Challenge:</strong> A leading private bank needed to migrate its 20-year-old COBOL core banking system to a modern cloud-native platform without disrupting 24/7 operations.</p>
    <p><strong>Solution:</strong> Designed a strangler-fig migration pattern using event-driven microservices on AWS EKS, with a dual-write phase to ensure zero data loss.</p>
    <p><strong>Outcome:</strong> 40% reduction in operational costs, 99.99% uptime maintained, and 3x faster feature delivery.</p>
    <div class="tech-stack"><span>AWS EKS</span><span>Kafka</span><span>PostgreSQL</span><span>Terraform</span></div>
  </div>

  <div class="portfolio-item">
    <div class="portfolio-header">
      <h3>AI Customer Support Platform</h3>
      <span class="industry-tag">E-commerce</span>
    </div>
    <p><strong>Challenge:</strong> An e-commerce company with 5M+ customers was spending ₹2Cr/month on customer support agents handling repetitive queries.</p>
    <p><strong>Solution:</strong> Built a RAG-based LLM system trained on product catalogues, order data, and support history, integrated with WhatsApp, email, and web chat.</p>
    <p><strong>Outcome:</strong> 80% of tier-1 queries automated, saving ₹1.6Cr/month, with customer satisfaction scores improving by 22%.</p>
    <div class="tech-stack"><span>GPT-4o</span><span>LangChain</span><span>Pinecone</span><span>FastAPI</span></div>
  </div>

  <div class="portfolio-item">
    <div class="portfolio-header">
      <h3>Healthcare Data Lake</h3>
      <span class="industry-tag">Healthcare</span>
    </div>
    <p><strong>Challenge:</strong> A hospital chain needed a unified data platform to analyse patient outcomes across 15 hospitals while maintaining HIPAA compliance.</p>
    <p><strong>Solution:</strong> Architected a HIPAA-compliant data lake on Azure with Databricks for processing, Power BI for dashboards, and row-level security for data governance.</p>
    <p><strong>Outcome:</strong> Real-time dashboards for 10M+ patient records, 60% reduction in reporting time, and full HIPAA audit compliance achieved.</p>
    <div class="tech-stack"><span>Azure Data Lake</span><span>Databricks</span><span>Power BI</span><span>Python</span></div>
  </div>
</section>
HTML;
    }

    private function influencerHomePage(string $name): string
    {
        return <<<HTML
<section class="hero-section influencer-hero">
  <h1>Hey, I'm {$name} ✨</h1>
  <h2>Lifestyle · Fashion · Travel · Wellness</h2>
  <p>Welcome to my corner of the internet! I share authentic stories about fashion finds, travel adventures, wellness routines, and everything in between. Join 250K+ followers on this journey of living beautifully.</p>
  <div class="hero-cta">
    <a href="/collaborations" class="btn-primary">Work With Me</a>
    <a href="/blog" class="btn-secondary">Read My Blog</a>
  </div>
</section>

<section class="social-proof">
  <div class="stat"><span>250K+</span><p>Instagram Followers</p></div>
  <div class="stat"><span>180K+</span><p>YouTube Subscribers</p></div>
  <div class="stat"><span>95K+</span><p>Pinterest Monthly Views</p></div>
  <div class="stat"><span>4.2%</span><p>Engagement Rate</p></div>
</section>

<section class="content-categories">
  <h2>What I Create</h2>
  <div class="categories-grid">
    <div class="category-card fashion">
      <i class="fas fa-tshirt"></i>
      <h3>Fashion</h3>
      <p>Sustainable style, capsule wardrobes, and affordable luxury finds for the modern woman.</p>
    </div>
    <div class="category-card travel">
      <i class="fas fa-plane"></i>
      <h3>Travel</h3>
      <p>Hidden gems, budget travel hacks, and luxury escapes across Asia, Europe, and beyond.</p>
    </div>
    <div class="category-card wellness">
      <i class="fas fa-heart"></i>
      <h3>Wellness</h3>
      <p>Mindfulness, fitness routines, clean beauty, and holistic health for a balanced life.</p>
    </div>
    <div class="category-card lifestyle">
      <i class="fas fa-home"></i>
      <h3>Lifestyle</h3>
      <p>Home decor, productivity tips, morning routines, and the art of intentional living.</p>
    </div>
  </div>
</section>

<section class="featured-posts">
  <h2>Latest from the Blog</h2>
  <div class="posts-grid">
    <div class="post-card">
      <h3>My 10-Piece Capsule Wardrobe for 2025</h3>
      <p>How I built a versatile, sustainable wardrobe that works for every occasion without breaking the bank.</p>
      <a href="/blog">Read More →</a>
    </div>
    <div class="post-card">
      <h3>Solo Travel in Bali: The Honest Guide</h3>
      <p>Everything I wish I knew before my first solo trip to Bali — from hidden temples to the best warungs.</p>
      <a href="/blog">Read More →</a>
    </div>
    <div class="post-card">
      <h3>My Morning Routine That Changed Everything</h3>
      <p>The 5 AM habits that transformed my productivity, mental health, and overall wellbeing.</p>
      <a href="/blog">Read More →</a>
    </div>
  </div>
</section>

<section class="brand-partners">
  <h2>Brands I've Worked With</h2>
  <p>I partner with brands that align with my values of authenticity, sustainability, and quality.</p>
  <div class="brands-list">
    <span class="brand-tag">Nykaa</span>
    <span class="brand-tag">Myntra</span>
    <span class="brand-tag">Airbnb</span>
    <span class="brand-tag">Levi's</span>
    <span class="brand-tag">Forest Essentials</span>
    <span class="brand-tag">Yoga Bar</span>
  </div>
</section>
HTML;
    }

    private function influencerAboutPage(string $name): string
    {
        return <<<HTML
<section class="about-hero">
  <h1>About {$name}</h1>
  <p class="subtitle">Lifestyle creator, storyteller, and your internet best friend</p>
</section>

<section class="about-content">
  <h2>My Story</h2>
  <p>I started creating content in 2019 from my tiny Mumbai apartment, armed with nothing but a smartphone and a passion for sharing authentic stories. What began as a personal fashion diary quickly grew into a community of 250K+ incredible people who share my love for intentional living.</p>
  <p>My content philosophy is simple: be real, be relatable, and always add value. I don't believe in perfection — I believe in showing up authentically, whether that's a gorgeous travel photo or an honest review of a product that didn't work for me.</p>

  <h2>What I Stand For</h2>
  <ul>
    <li><strong>Sustainability:</strong> I only promote brands that have genuine sustainable practices</li>
    <li><strong>Authenticity:</strong> Every review is honest — I turn down partnerships that don't align with my values</li>
    <li><strong>Inclusivity:</strong> Fashion and wellness are for every body, every budget, every background</li>
    <li><strong>Community:</strong> My followers are my community, not just numbers</li>
  </ul>

  <h2>Content Platforms</h2>
  <ul>
    <li>Instagram: @{$name} — 250K followers</li>
    <li>YouTube: {$name} — 180K subscribers</li>
    <li>Pinterest: {$name} — 95K monthly views</li>
    <li>Newsletter: 45K subscribers</li>
  </ul>

  <h2>Media Features</h2>
  <ul>
    <li>Vogue India — "Top 10 Indian Lifestyle Creators to Follow"</li>
    <li>Femina — "The New Wave of Authentic Influencers"</li>
    <li>Forbes India — "30 Under 30: Digital Creators"</li>
  </ul>
</section>
HTML;
    }

    private function influencerCollaborationsPage(string $name): string
    {
        return <<<HTML
<section class="collab-hero">
  <h1>Work With Me</h1>
  <p>Let's create content that resonates, converts, and builds lasting brand love</p>
</section>

<section class="collab-options">
  <div class="collab-card">
    <h2>Instagram Collaboration</h2>
    <p>Sponsored posts, stories, reels, and carousel content crafted to feel native and authentic to my feed aesthetic.</p>
    <ul>
      <li>Single feed post + 3 stories</li>
      <li>Instagram Reel (30–60 seconds)</li>
      <li>Story series (5–7 frames)</li>
      <li>Instagram Live collaboration</li>
    </ul>
    <p class="price-range">Starting from ₹25,000 per post</p>
  </div>

  <div class="collab-card">
    <h2>YouTube Integration</h2>
    <p>Dedicated videos, integrations, and hauls that give your brand extended exposure to a highly engaged audience.</p>
    <ul>
      <li>Dedicated product video (5–10 min)</li>
      <li>Brand integration in lifestyle video</li>
      <li>Unboxing and first impressions</li>
      <li>Sponsored haul video</li>
    </ul>
    <p class="price-range">Starting from ₹75,000 per video</p>
  </div>

  <div class="collab-card">
    <h2>Blog &amp; Newsletter</h2>
    <p>Long-form content that drives SEO value and reaches my highly engaged newsletter audience.</p>
    <ul>
      <li>Sponsored blog post (1,000+ words)</li>
      <li>Newsletter feature (45K subscribers)</li>
      <li>Product review article</li>
      <li>Gift guide inclusion</li>
    </ul>
    <p class="price-range">Starting from ₹15,000 per placement</p>
  </div>

  <div class="collab-card">
    <h2>Brand Ambassador</h2>
    <p>Long-term partnerships for brands looking for sustained, authentic representation across all my platforms.</p>
    <ul>
      <li>3, 6, or 12-month partnerships</li>
      <li>Exclusive category representation</li>
      <li>Multi-platform content calendar</li>
      <li>Event appearances and launches</li>
    </ul>
    <p class="price-range">Custom packages available</p>
  </div>
</section>

<section class="collab-process">
  <h2>How It Works</h2>
  <ol>
    <li><strong>Reach Out:</strong> Fill out the collaboration form below with your brand details and campaign goals.</li>
    <li><strong>Discovery Call:</strong> We'll schedule a 30-minute call to discuss alignment and creative direction.</li>
    <li><strong>Proposal:</strong> I'll send a detailed proposal with content ideas, timelines, and pricing.</li>
    <li><strong>Creation:</strong> I create the content with full creative freedom to ensure authenticity.</li>
    <li><strong>Delivery &amp; Reporting:</strong> Content goes live and I share detailed performance metrics.</li>
  </ol>
</section>

<section class="cta-section">
  <h2>Ready to Collaborate?</h2>
  <a href="/contact" class="btn-primary">Get in Touch</a>
</section>
HTML;
    }

    private function advocateHomePage(string $name): string
    {
        return <<<HTML
<section class="hero-section advocate-hero">
  <h1>{$name}</h1>
  <h2>Advocate &amp; Legal Consultant</h2>
  <p>Providing expert legal counsel in corporate law, intellectual property, and dispute resolution. With 10+ years of practice at the Madras High Court and Supreme Court of India, I am committed to delivering strategic, results-oriented legal solutions.</p>
  <div class="hero-cta">
    <a href="/contact" class="btn-primary">Book Consultation</a>
    <a href="/practice-areas" class="btn-secondary">Practice Areas</a>
  </div>
</section>

<section class="stats-section">
  <div class="stat"><span>500+</span><p>Cases Won</p></div>
  <div class="stat"><span>10+</span><p>Years of Practice</p></div>
  <div class="stat"><span>200+</span><p>Corporate Clients</p></div>
  <div class="stat"><span>95%</span><p>Success Rate</p></div>
</section>

<section class="practice-areas-preview">
  <h2>Practice Areas</h2>
  <div class="practice-grid">
    <div class="practice-card">
      <i class="fas fa-building"></i>
      <h3>Corporate Law</h3>
      <p>Company incorporation, M&amp;A, shareholder agreements, and corporate governance advisory.</p>
    </div>
    <div class="practice-card">
      <i class="fas fa-lightbulb"></i>
      <h3>Intellectual Property</h3>
      <p>Trademark registration, patent filing, copyright protection, and IP litigation.</p>
    </div>
    <div class="practice-card">
      <i class="fas fa-gavel"></i>
      <h3>Dispute Resolution</h3>
      <p>Commercial arbitration, mediation, and litigation before High Courts and Supreme Court.</p>
    </div>
    <div class="practice-card">
      <i class="fas fa-users"></i>
      <h3>Consumer Protection</h3>
      <p>Consumer forum representation, product liability, and consumer rights advisory.</p>
    </div>
  </div>
</section>

<section class="why-choose">
  <h2>Why Choose {$name}?</h2>
  <div class="reasons-grid">
    <div class="reason">
      <i class="fas fa-balance-scale"></i>
      <h3>Strategic Approach</h3>
      <p>Every case is approached with a thorough understanding of both legal merits and business implications.</p>
    </div>
    <div class="reason">
      <i class="fas fa-clock"></i>
      <h3>Timely Delivery</h3>
      <p>Strict adherence to deadlines and proactive communication at every stage of the matter.</p>
    </div>
    <div class="reason">
      <i class="fas fa-handshake"></i>
      <h3>Client-First</h3>
      <p>Transparent billing, clear communication, and unwavering commitment to client interests.</p>
    </div>
  </div>
</section>

<section class="testimonials">
  <h2>Client Testimonials</h2>
  <div class="testimonial-card">
    <p>"Exceptional legal acumen and strategic thinking. Helped us navigate a complex M&amp;A transaction seamlessly."</p>
    <cite>— CEO, Technology Startup</cite>
  </div>
  <div class="testimonial-card">
    <p>"Won our trademark dispute in record time. Highly professional and deeply knowledgeable."</p>
    <cite>— Founder, Consumer Brand</cite>
  </div>
</section>
HTML;
    }

    private function advocateAboutPage(string $name): string
    {
        return <<<HTML
<section class="about-hero">
  <h1>About {$name}</h1>
  <p class="subtitle">Advocate, Madras High Court &amp; Supreme Court of India</p>
</section>

<section class="about-content">
  <h2>Professional Profile</h2>
  <p>{$name} is a seasoned advocate with over 10 years of practice specialising in corporate law, intellectual property rights, and commercial dispute resolution. Enrolled with the Bar Council of Tamil Nadu, {$name} has appeared before the Madras High Court, National Company Law Tribunal (NCLT), and the Supreme Court of India.</p>

  <h2>Education</h2>
  <ul>
    <li>LL.B. (Hons.) — National Law School of India University, Bangalore</li>
    <li>LL.M. in Corporate Law — University of Mumbai</li>
    <li>Diploma in Intellectual Property Law — WIPO Academy</li>
  </ul>

  <h2>Bar Memberships</h2>
  <ul>
    <li>Bar Council of Tamil Nadu</li>
    <li>Madras Bar Association</li>
    <li>Society of Indian Law Firms (SILF)</li>
  </ul>

  <h2>Notable Achievements</h2>
  <ul>
    <li>Successfully argued landmark consumer protection case before Supreme Court (2023)</li>
    <li>Led legal team for ₹500Cr M&amp;A transaction in the technology sector</li>
    <li>Secured trademark protection for 50+ brands across India and internationally</li>
    <li>Recognised as "Rising Star in Corporate Law" by Legal 500 India (2022)</li>
  </ul>

  <h2>Publications &amp; Speaking</h2>
  <ul>
    <li>Author: "Startup Legal Handbook" — published by LexisNexis India</li>
    <li>Regular contributor to Bar &amp; Bench, Live Law, and Indian Corporate Law</li>
    <li>Speaker at NASSCOM, CII, and various law school moot court events</li>
  </ul>
</section>
HTML;
    }

    private function advocatePracticeAreasPage(string $name): string
    {
        return <<<HTML
<section class="practice-hero">
  <h1>Practice Areas</h1>
  <p>Comprehensive legal services for businesses, startups, and individuals</p>
</section>

<section class="practice-list">
  <div class="practice-detail">
    <h2><i class="fas fa-building"></i> Corporate &amp; Commercial Law</h2>
    <p>End-to-end legal support for businesses at every stage — from incorporation to exit.</p>
    <ul>
      <li>Company incorporation (Private Limited, LLP, OPC)</li>
      <li>Shareholders' and founders' agreements</li>
      <li>Mergers, acquisitions, and due diligence</li>
      <li>Corporate governance and board advisory</li>
      <li>Joint venture and partnership agreements</li>
    </ul>
  </div>

  <div class="practice-detail">
    <h2><i class="fas fa-lightbulb"></i> Intellectual Property</h2>
    <p>Protecting your innovations, brands, and creative works in India and internationally.</p>
    <ul>
      <li>Trademark registration and prosecution</li>
      <li>Patent filing and prosecution (Indian Patent Office)</li>
      <li>Copyright registration and enforcement</li>
      <li>IP due diligence for M&amp;A transactions</li>
      <li>Domain name disputes (UDRP/INDRP)</li>
    </ul>
  </div>

  <div class="practice-detail">
    <h2><i class="fas fa-gavel"></i> Dispute Resolution &amp; Litigation</h2>
    <p>Strategic representation in courts, tribunals, and arbitration proceedings.</p>
    <ul>
      <li>Commercial arbitration (domestic and international)</li>
      <li>Mediation and conciliation</li>
      <li>High Court and Supreme Court litigation</li>
      <li>NCLT/NCLAT proceedings</li>
      <li>Consumer forum representation</li>
    </ul>
  </div>

  <div class="practice-detail">
    <h2><i class="fas fa-file-contract"></i> Contracts &amp; Documentation</h2>
    <p>Drafting and reviewing commercial contracts that protect your interests.</p>
    <ul>
      <li>Service agreements and MSAs</li>
      <li>Employment contracts and HR policies</li>
      <li>Technology licensing agreements</li>
      <li>Real estate and lease agreements</li>
      <li>Non-disclosure and non-compete agreements</li>
    </ul>
  </div>
</section>

<section class="consultation-cta">
  <h2>Schedule a Legal Consultation</h2>
  <p>Initial consultation available at ₹2,000 for 30 minutes. Corporate retainers available.</p>
  <a href="/contact" class="btn-primary">Book Now</a>
</section>
HTML;
    }

    private function advocateCaseStudiesPage(string $name): string
    {
        return <<<HTML
<section class="cases-hero">
  <h1>Case Studies</h1>
  <p>Selected matters demonstrating our legal expertise and outcomes</p>
</section>

<section class="cases-list">
  <div class="case-card">
    <div class="case-header">
      <h3>Startup Trademark Dispute — Technology Sector</h3>
      <span class="outcome-tag win">Favourable Outcome</span>
    </div>
    <p><strong>Matter:</strong> A technology startup's trademark was being infringed by a larger competitor using a confusingly similar name and logo.</p>
    <p><strong>Action:</strong> Filed for interim injunction before the Madras High Court, obtained ex-parte stay within 72 hours, and negotiated a settlement including financial compensation and rebranding of the infringing party.</p>
    <p><strong>Outcome:</strong> Client's trademark fully protected, ₹15 lakh compensation received, competitor rebranded within 90 days.</p>
  </div>

  <div class="case-card">
    <div class="case-header">
      <h3>M&amp;A Legal Advisory — EdTech Acquisition</h3>
      <span class="outcome-tag win">Successfully Completed</span>
    </div>
    <p><strong>Matter:</strong> Advised the acquirer in a ₹120Cr acquisition of an EdTech startup, including full legal due diligence and transaction structuring.</p>
    <p><strong>Action:</strong> Conducted IP, regulatory, employment, and commercial due diligence; identified and mitigated 12 material risks; drafted and negotiated SPA, SHA, and ancillary documents.</p>
    <p><strong>Outcome:</strong> Transaction closed successfully with all identified risks addressed through appropriate representations, warranties, and indemnities.</p>
  </div>

  <div class="case-card">
    <div class="case-header">
      <h3>Consumer Protection — Product Liability</h3>
      <span class="outcome-tag win">Favourable Outcome</span>
    </div>
    <p><strong>Matter:</strong> Represented a class of 200+ consumers against a consumer electronics company for defective products causing property damage.</p>
    <p><strong>Action:</strong> Filed complaint before the National Consumer Disputes Redressal Commission (NCDRC), presented technical evidence, and argued for enhanced compensation under the Consumer Protection Act 2019.</p>
    <p><strong>Outcome:</strong> Full refund plus ₹50,000 compensation per consumer awarded; company directed to recall the defective product line.</p>
  </div>
</section>
HTML;
    }

    private function doctorHomePage(string $name): string
    {
        return <<<HTML
<section class="hero-section doctor-hero">
  <h1>Dr. {$name}</h1>
  <h2>MBBS, MD · General Physician &amp; Wellness Consultant</h2>
  <p>Providing compassionate, evidence-based medical care for over 15 years. I believe in treating the whole person — not just the symptoms. My practice combines modern medicine with a holistic approach to health and wellness.</p>
  <div class="hero-cta">
    <a href="/appointments" class="btn-primary">Book Appointment</a>
    <a href="/services" class="btn-secondary">Our Services</a>
  </div>
</section>

<section class="stats-section">
  <div class="stat"><span>15+</span><p>Years Experience</p></div>
  <div class="stat"><span>10,000+</span><p>Patients Treated</p></div>
  <div class="stat"><span>4.9★</span><p>Patient Rating</p></div>
  <div class="stat"><span>Mon–Sat</span><p>Clinic Hours</p></div>
</section>

<section class="services-preview">
  <h2>Medical Services</h2>
  <div class="services-grid">
    <div class="service-card">
      <i class="fas fa-stethoscope"></i>
      <h3>General Medicine</h3>
      <p>Comprehensive diagnosis and treatment for acute and chronic conditions including diabetes, hypertension, and respiratory disorders.</p>
    </div>
    <div class="service-card">
      <i class="fas fa-heartbeat"></i>
      <h3>Preventive Health</h3>
      <p>Annual health check-ups, vaccination programmes, and personalised preventive care plans.</p>
    </div>
    <div class="service-card">
      <i class="fas fa-leaf"></i>
      <h3>Wellness Consulting</h3>
      <p>Nutrition counselling, stress management, sleep optimisation, and lifestyle medicine.</p>
    </div>
    <div class="service-card">
      <i class="fas fa-video"></i>
      <h3>Teleconsultation</h3>
      <p>Convenient online consultations for follow-ups, prescription renewals, and non-emergency concerns.</p>
    </div>
  </div>
</section>

<section class="testimonials">
  <h2>Patient Reviews</h2>
  <div class="testimonial-card">
    <p>"Dr. {$name} takes the time to truly understand your concerns. The most thorough and caring physician I've ever visited."</p>
    <cite>— Patient, Chennai</cite>
  </div>
  <div class="testimonial-card">
    <p>"My diabetes has been under excellent control since I started following Dr. {$name}'s personalised care plan. Highly recommend."</p>
    <cite>— Patient, Bangalore</cite>
  </div>
</section>
HTML;
    }

    private function doctorAboutPage(string $name): string
    {
        return <<<HTML
<section class="about-hero">
  <h1>About Dr. {$name}</h1>
  <p class="subtitle">MBBS, MD (Internal Medicine) · 15+ Years of Clinical Practice</p>
</section>

<section class="about-content">
  <h2>Medical Background</h2>
  <p>Dr. {$name} completed MBBS from Madras Medical College and pursued MD in Internal Medicine from AIIMS New Delhi. With 15+ years of clinical practice, Dr. {$name} has treated thousands of patients across a wide spectrum of medical conditions.</p>
  <p>A strong believer in preventive medicine and patient education, Dr. {$name} takes time with every patient to ensure they understand their condition and treatment plan.</p>

  <h2>Education &amp; Training</h2>
  <ul>
    <li>MBBS — Madras Medical College, Chennai (2005)</li>
    <li>MD Internal Medicine — AIIMS New Delhi (2009)</li>
    <li>Fellowship in Diabetes Management — Apollo Hospitals (2011)</li>
    <li>Certificate in Lifestyle Medicine — American College of Lifestyle Medicine</li>
  </ul>

  <h2>Registrations</h2>
  <ul>
    <li>Tamil Nadu Medical Council — Registration No. TN-XXXXX</li>
    <li>Medical Council of India</li>
    <li>Indian Medical Association (IMA)</li>
  </ul>

  <h2>Special Interests</h2>
  <ul>
    <li>Diabetes and metabolic syndrome management</li>
    <li>Hypertension and cardiovascular risk reduction</li>
    <li>Lifestyle medicine and preventive health</li>
    <li>Geriatric medicine and healthy ageing</li>
  </ul>
</section>
HTML;
    }

    private function doctorServicesPage(string $name): string
    {
        return <<<HTML
<section class="services-hero">
  <h1>Medical Services</h1>
  <p>Comprehensive healthcare for you and your family</p>
</section>

<section class="services-list">
  <div class="service-detail">
    <h2><i class="fas fa-stethoscope"></i> General Medicine Consultation</h2>
    <p>Thorough evaluation and treatment for all general medical conditions.</p>
    <ul>
      <li>Fever, infections, and viral illnesses</li>
      <li>Diabetes management and monitoring</li>
      <li>Hypertension and heart health</li>
      <li>Thyroid disorders</li>
      <li>Respiratory conditions (asthma, COPD)</li>
    </ul>
    <p class="price-range">Consultation: ₹500 | Follow-up: ₹300</p>
  </div>

  <div class="service-detail">
    <h2><i class="fas fa-heartbeat"></i> Preventive Health Check-ups</h2>
    <p>Comprehensive health screening packages to detect conditions early.</p>
    <ul>
      <li>Basic Health Package (CBC, lipid profile, blood sugar, urine) — ₹1,500</li>
      <li>Comprehensive Package (40+ tests) — ₹3,500</li>
      <li>Senior Citizen Package (50+ tests) — ₹4,500</li>
      <li>Corporate Health Camps — Custom pricing</li>
    </ul>
  </div>

  <div class="service-detail">
    <h2><i class="fas fa-leaf"></i> Wellness &amp; Lifestyle Consulting</h2>
    <p>Personalised programmes to optimise your health and prevent chronic disease.</p>
    <ul>
      <li>Nutrition and diet counselling</li>
      <li>Weight management programme</li>
      <li>Stress and sleep management</li>
      <li>Smoking cessation programme</li>
    </ul>
    <p class="price-range">Initial session: ₹800 | Programme packages available</p>
  </div>

  <div class="service-detail">
    <h2><i class="fas fa-video"></i> Teleconsultation</h2>
    <p>Convenient online consultations from the comfort of your home.</p>
    <ul>
      <li>Available via video call (Google Meet / Zoom)</li>
      <li>Prescription delivery via email/WhatsApp</li>
      <li>Follow-up consultations</li>
      <li>Chronic disease monitoring</li>
    </ul>
    <p class="price-range">Teleconsultation: ₹400</p>
  </div>
</section>
HTML;
    }

    private function doctorAppointmentPage(string $name, string $email): string
    {
        return <<<HTML
<section class="appointment-hero">
  <h1>Book an Appointment</h1>
  <p>Schedule your consultation with Dr. {$name}</p>
</section>

<section class="clinic-info">
  <div class="info-card">
    <h2>Clinic Hours</h2>
    <table>
      <tr><td>Monday – Friday</td><td>9:00 AM – 1:00 PM, 5:00 PM – 8:00 PM</td></tr>
      <tr><td>Saturday</td><td>9:00 AM – 2:00 PM</td></tr>
      <tr><td>Sunday</td><td>Emergency only</td></tr>
    </table>
  </div>

  <div class="info-card">
    <h2>Contact</h2>
    <p>📞 +91 98765 43210</p>
    <p>📧 {$email}</p>
    <p>📍 123, Anna Salai, Chennai — 600002</p>
  </div>

  <div class="info-card">
    <h2>Teleconsultation</h2>
    <p>Available Monday to Saturday, 2:00 PM – 4:00 PM via video call.</p>
    <p>Book online and receive the meeting link via email.</p>
  </div>
</section>

<section class="appointment-note">
  <p><strong>Note:</strong> For emergencies, please call directly or visit the nearest emergency room. This appointment system is for non-emergency consultations only.</p>
</section>
HTML;
    }

    private function entrepreneurHomePage(string $name): string
    {
        return <<<HTML
<section class="hero-section entrepreneur-hero">
  <h1>{$name}</h1>
  <h2>Entrepreneur · Startup Founder · Angel Investor</h2>
  <p>I build companies that solve real problems. With 3 successful exits and 12+ portfolio investments, I am passionate about the startup ecosystem and helping founders navigate the journey from idea to scale.</p>
  <div class="hero-cta">
    <a href="/ventures" class="btn-primary">My Ventures</a>
    <a href="/contact" class="btn-secondary">Let's Connect</a>
  </div>
</section>

<section class="stats-section">
  <div class="stat"><span>3</span><p>Successful Exits</p></div>
  <div class="stat"><span>12+</span><p>Portfolio Companies</p></div>
  <div class="stat"><span>₹50Cr+</span><p>Capital Deployed</p></div>
  <div class="stat"><span>500+</span><p>Jobs Created</p></div>
</section>

<section class="ventures-preview">
  <h2>Current Ventures</h2>
  <div class="ventures-grid">
    <div class="venture-card">
      <h3>TechFlow AI</h3>
      <p>AI-powered workflow automation for SMEs. Series A funded. 200+ enterprise customers.</p>
      <span class="tag">SaaS</span><span class="tag">AI</span><span class="tag">B2B</span>
    </div>
    <div class="venture-card">
      <h3>GreenCart</h3>
      <p>Sustainable e-commerce platform connecting eco-conscious consumers with verified green brands.</p>
      <span class="tag">E-commerce</span><span class="tag">Sustainability</span>
    </div>
    <div class="venture-card">
      <h3>EduReach</h3>
      <p>EdTech platform delivering vocational training to Tier 2 and Tier 3 cities. 50,000+ learners.</p>
      <span class="tag">EdTech</span><span class="tag">Social Impact</span>
    </div>
  </div>
</section>

<section class="investment-thesis">
  <h2>Investment Thesis</h2>
  <p>I invest in pre-seed and seed stage startups in India with a focus on:</p>
  <div class="thesis-grid">
    <div class="thesis-item"><i class="fas fa-robot"></i><p>AI &amp; Deep Tech</p></div>
    <div class="thesis-item"><i class="fas fa-leaf"></i><p>Climate Tech</p></div>
    <div class="thesis-item"><i class="fas fa-graduation-cap"></i><p>EdTech</p></div>
    <div class="thesis-item"><i class="fas fa-heartbeat"></i><p>HealthTech</p></div>
    <div class="thesis-item"><i class="fas fa-store"></i><p>D2C Brands</p></div>
    <div class="thesis-item"><i class="fas fa-industry"></i><p>B2B SaaS</p></div>
  </div>
</section>
HTML;
    }

    private function entrepreneurAboutPage(string $name): string
    {
        return <<<HTML
<section class="about-hero">
  <h1>About {$name}</h1>
  <p class="subtitle">Serial entrepreneur, investor, and startup ecosystem builder</p>
</section>

<section class="about-content">
  <h2>My Journey</h2>
  <p>I built my first company at 24 — a B2B software firm that grew to 80 employees before being acquired. That experience taught me more than any MBA could. Since then, I've founded, scaled, and exited 3 companies across SaaS, e-commerce, and fintech.</p>
  <p>Today, I focus on building my current portfolio of ventures while actively investing in and mentoring early-stage founders across India. I believe India is at an inflection point for startup creation, and I want to be part of that story.</p>

  <h2>Education</h2>
  <ul>
    <li>B.Tech in Computer Science — IIT Bombay</li>
    <li>MBA — Indian School of Business (ISB), Hyderabad</li>
  </ul>

  <h2>Exits</h2>
  <ul>
    <li>SoftSolve Technologies — Acquired by TCS (2015)</li>
    <li>PayEasy — Acquired by a leading private bank (2019)</li>
    <li>ShopLocal — Merged with a D2C aggregator (2022)</li>
  </ul>

  <h2>Boards &amp; Advisory</h2>
  <ul>
    <li>Board Member — NASSCOM Startup Council</li>
    <li>Mentor — T-Hub Hyderabad</li>
    <li>Angel Network — Indian Angel Network (IAN)</li>
    <li>Advisor — 5 portfolio companies</li>
  </ul>
</section>
HTML;
    }

    private function entrepreneurVenturesPage(string $name): string
    {
        return <<<HTML
<section class="ventures-hero">
  <h1>Ventures &amp; Portfolio</h1>
  <p>Companies I've built, invested in, and advised</p>
</section>

<section class="ventures-list">
  <h2>Current Ventures</h2>
  <div class="venture-detail">
    <h3>TechFlow AI <span class="status active">Active</span></h3>
    <p>AI-powered workflow automation platform helping SMEs automate repetitive business processes. Currently serving 200+ enterprise customers with an ARR of ₹8Cr.</p>
    <div class="venture-meta"><span>Founded: 2021</span><span>Stage: Series A</span><span>Team: 45</span></div>
  </div>
  <div class="venture-detail">
    <h3>GreenCart <span class="status active">Active</span></h3>
    <p>India's first certified sustainable e-commerce marketplace. 500+ verified green brands, 1.2L+ customers, and growing 40% MoM.</p>
    <div class="venture-meta"><span>Founded: 2022</span><span>Stage: Seed+</span><span>Team: 22</span></div>
  </div>

  <h2>Exits</h2>
  <div class="venture-detail">
    <h3>SoftSolve Technologies <span class="status exited">Acquired by TCS</span></h3>
    <p>B2B enterprise software company. Built from 0 to ₹25Cr ARR and 80 employees before acquisition.</p>
    <div class="venture-meta"><span>Founded: 2010</span><span>Acquired: 2015</span></div>
  </div>

  <h2>Angel Portfolio</h2>
  <p>I've invested in 12+ early-stage startups across AI, climate tech, and consumer internet. Notable investments include companies that have gone on to raise Series A and B rounds from top-tier VCs.</p>
</section>
HTML;
    }

    private function entrepreneurSolutionsPage(string $name): string
    {
        return <<<HTML
<section class="solutions-hero">
  <h1>Business Solutions</h1>
  <p>Advisory, mentorship, and strategic support for founders and enterprises</p>
</section>

<section class="solutions-list">
  <div class="solution-card">
    <h2>Startup Advisory</h2>
    <p>Strategic guidance for early-stage founders on product-market fit, fundraising, team building, and go-to-market strategy.</p>
    <ul>
      <li>Monthly advisory sessions (4 hours/month)</li>
      <li>Fundraising pitch review and investor introductions</li>
      <li>Product strategy and roadmap review</li>
      <li>Network access to co-founders, talent, and partners</li>
    </ul>
    <p class="price-range">₹50,000/month or equity-based</p>
  </div>

  <div class="solution-card">
    <h2>Corporate Innovation</h2>
    <p>Helping established companies build innovation capabilities, launch internal ventures, and partner with startups.</p>
    <ul>
      <li>Innovation strategy and roadmap</li>
      <li>Startup scouting and partnership facilitation</li>
      <li>Internal venture building programmes</li>
      <li>Digital transformation advisory</li>
    </ul>
    <p class="price-range">Custom engagement</p>
  </div>

  <div class="solution-card">
    <h2>Speaking &amp; Workshops</h2>
    <p>Keynote speaking and workshop facilitation for corporate events, startup summits, and university programmes.</p>
    <ul>
      <li>Keynote: "Building in India's Startup Decade"</li>
      <li>Workshop: "Zero to One — Validating Your Startup Idea"</li>
      <li>Masterclass: "Fundraising for Indian Founders"</li>
      <li>Panel discussions on entrepreneurship and innovation</li>
    </ul>
    <p class="price-range">Speaking fee on request</p>
  </div>
</section>
HTML;
    }

    private function politicianHomePage(string $name): string
    {
        return <<<HTML
<section class="hero-section politician-hero">
  <h1>{$name}</h1>
  <h2>Your Voice in Parliament · Serving the People</h2>
  <p>Dedicated to building a prosperous, equitable, and sustainable future for our constituency. With 15 years of public service, I have worked tirelessly to bring infrastructure, education, and economic opportunities to every corner of our region.</p>
  <div class="hero-cta">
    <a href="/initiatives" class="btn-primary">Our Initiatives</a>
    <a href="/contact" class="btn-secondary">Contact Office</a>
  </div>
</section>

<section class="stats-section">
  <div class="stat"><span>15+</span><p>Years of Service</p></div>
  <div class="stat"><span>50+</span><p>Initiatives Launched</p></div>
  <div class="stat"><span>₹500Cr+</span><p>Development Funds Secured</p></div>
  <div class="stat"><span>1L+</span><p>Constituents Served</p></div>
</section>

<section class="key-issues">
  <h2>Key Focus Areas</h2>
  <div class="issues-grid">
    <div class="issue-card">
      <i class="fas fa-road"></i>
      <h3>Infrastructure</h3>
      <p>Building world-class roads, bridges, and public transport to connect every village to opportunity.</p>
    </div>
    <div class="issue-card">
      <i class="fas fa-graduation-cap"></i>
      <h3>Education</h3>
      <p>Ensuring quality education for every child with modern schools, digital classrooms, and scholarships.</p>
    </div>
    <div class="issue-card">
      <i class="fas fa-briefcase"></i>
      <h3>Employment</h3>
      <p>Creating jobs through industrial development, skill training, and support for local entrepreneurs.</p>
    </div>
    <div class="issue-card">
      <i class="fas fa-leaf"></i>
      <h3>Environment</h3>
      <p>Protecting our natural heritage through sustainable development and green energy initiatives.</p>
    </div>
  </div>
</section>

<section class="recent-news">
  <h2>Recent Updates</h2>
  <div class="news-grid">
    <div class="news-card">
      <h3>₹120Cr Highway Project Approved</h3>
      <p>Successfully secured central government funding for the 45km highway connecting our constituency to the state capital.</p>
    </div>
    <div class="news-card">
      <h3>1,000 New Jobs Created</h3>
      <p>New industrial park inaugurated, bringing 1,000 direct employment opportunities for local youth.</p>
    </div>
    <div class="news-card">
      <h3>Digital Literacy Programme Launched</h3>
      <p>Free computer training for 5,000 women and youth across 50 villages in the constituency.</p>
    </div>
  </div>
</section>
HTML;
    }

    private function politicianAboutPage(string $name): string
    {
        return <<<HTML
<section class="about-hero">
  <h1>About {$name}</h1>
  <p class="subtitle">Member of Parliament · Public Servant · Community Leader</p>
</section>

<section class="about-content">
  <h2>Public Service Journey</h2>
  <p>{$name} has dedicated 15 years to public service, beginning as a grassroots community organiser and rising to represent the constituency in Parliament. Known for a hands-on approach and deep connection with constituents, {$name} has consistently delivered on promises of development and good governance.</p>

  <h2>Education</h2>
  <ul>
    <li>B.A. Political Science — Presidency College, Chennai</li>
    <li>M.A. Public Administration — Jawaharlal Nehru University, Delhi</li>
    <li>Executive Programme in Public Policy — IIM Bangalore</li>
  </ul>

  <h2>Political Career</h2>
  <ul>
    <li>2008–2013: District Youth Wing President</li>
    <li>2013–2018: Member of Legislative Assembly (MLA)</li>
    <li>2018–Present: Member of Parliament (MP)</li>
    <li>Parliamentary Committee: Standing Committee on Finance</li>
    <li>Parliamentary Committee: Standing Committee on Education</li>
  </ul>

  <h2>Key Achievements</h2>
  <ul>
    <li>Secured ₹500Cr+ in central government development funds for the constituency</li>
    <li>Built 25 new government schools and upgraded 50 existing ones</li>
    <li>Launched free skill training programme benefiting 20,000+ youth</li>
    <li>Constructed 5,000 affordable housing units under PM Awas Yojana</li>
    <li>Awarded "Best Performing MP" by a leading governance think tank (2022)</li>
  </ul>
</section>
HTML;
    }

    private function politicianVisionPage(string $name): string
    {
        return <<<HTML
<section class="vision-hero">
  <h1>Vision &amp; Mission</h1>
  <p>Building a constituency that every resident is proud to call home</p>
</section>

<section class="vision-content">
  <div class="vision-statement">
    <h2>Our Vision</h2>
    <p>A constituency where every citizen has access to quality education, healthcare, employment, and infrastructure — regardless of their background. A region that leads the state in human development indices while preserving its cultural heritage and natural environment.</p>
  </div>

  <div class="mission-statement">
    <h2>Our Mission</h2>
    <p>To serve as a transparent, accountable, and effective representative who listens to constituents, delivers on promises, and fights for the region's interests at every level of government.</p>
  </div>

  <h2>5-Year Development Agenda</h2>
  <div class="agenda-grid">
    <div class="agenda-item">
      <h3>Infrastructure</h3>
      <ul>
        <li>Complete 45km highway project by 2026</li>
        <li>Build 3 new bridges over river crossings</li>
        <li>Upgrade all village roads to paved surface</li>
        <li>Establish 2 new bus terminals</li>
      </ul>
    </div>
    <div class="agenda-item">
      <h3>Education</h3>
      <ul>
        <li>Open 10 new government schools</li>
        <li>Digital classrooms in all 200 schools</li>
        <li>Scholarship programme for 1,000 students/year</li>
        <li>New government engineering college</li>
      </ul>
    </div>
    <div class="agenda-item">
      <h3>Healthcare</h3>
      <ul>
        <li>New 200-bed district hospital</li>
        <li>Mobile health clinics for remote villages</li>
        <li>Free health insurance for all BPL families</li>
        <li>Mental health awareness programme</li>
      </ul>
    </div>
    <div class="agenda-item">
      <h3>Economy</h3>
      <ul>
        <li>New industrial park creating 5,000 jobs</li>
        <li>Skill development centres in 10 blocks</li>
        <li>Support for 500 new micro-enterprises</li>
        <li>Agricultural modernisation programme</li>
      </ul>
    </div>
  </div>
</section>
HTML;
    }

    private function politicianInitiativesPage(string $name): string
    {
        return <<<HTML
<section class="initiatives-hero">
  <h1>Initiatives &amp; Achievements</h1>
  <p>Delivering on our promises to the constituency</p>
</section>

<section class="initiatives-list">
  <div class="initiative-card completed">
    <span class="status-badge">Completed</span>
    <h3>Smart Village Programme</h3>
    <p>Brought broadband internet, solar street lighting, and digital payment infrastructure to 100 villages. 50,000+ residents now have reliable internet access.</p>
    <div class="initiative-meta"><span>Investment: ₹45Cr</span><span>Beneficiaries: 50,000+</span></div>
  </div>

  <div class="initiative-card completed">
    <span class="status-badge">Completed</span>
    <h3>Women Empowerment Centre</h3>
    <p>Established 20 women empowerment centres providing skill training, microfinance access, and legal aid to women across the constituency.</p>
    <div class="initiative-meta"><span>Investment: ₹12Cr</span><span>Beneficiaries: 15,000+</span></div>
  </div>

  <div class="initiative-card ongoing">
    <span class="status-badge ongoing">Ongoing</span>
    <h3>Highway Development Project</h3>
    <p>45km four-lane highway connecting the constituency to the state capital. 60% complete. Expected completion: December 2025.</p>
    <div class="initiative-meta"><span>Investment: ₹120Cr</span><span>Completion: Dec 2025</span></div>
  </div>

  <div class="initiative-card ongoing">
    <span class="status-badge ongoing">Ongoing</span>
    <h3>Industrial Park Phase 2</h3>
    <p>Expansion of the existing industrial park to accommodate 50 more manufacturing units, creating an additional 3,000 jobs.</p>
    <div class="initiative-meta"><span>Investment: ₹80Cr</span><span>Jobs: 3,000</span></div>
  </div>
</section>
HTML;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SHARED PAGE CONTENT
    // ─────────────────────────────────────────────────────────────────────────

    private function contactPage(string $name, string $email): string
    {
        return <<<HTML
<section class="contact-hero">
  <h1>Get in Touch</h1>
  <p>I'd love to hear from you. Reach out via any of the channels below.</p>
</section>

<section class="contact-content">
  <div class="contact-info">
    <div class="contact-item">
      <i class="fas fa-envelope"></i>
      <div>
        <h3>Email</h3>
        <p><a href="mailto:{$email}">{$email}</a></p>
      </div>
    </div>
    <div class="contact-item">
      <i class="fas fa-phone"></i>
      <div>
        <h3>Phone</h3>
        <p>+91 98765 43210</p>
      </div>
    </div>
    <div class="contact-item">
      <i class="fas fa-map-marker-alt"></i>
      <div>
        <h3>Location</h3>
        <p>Chennai, Tamil Nadu, India</p>
      </div>
    </div>
    <div class="contact-item">
      <i class="fas fa-clock"></i>
      <div>
        <h3>Response Time</h3>
        <p>Within 24 hours on business days</p>
      </div>
    </div>
  </div>

  <div class="contact-note">
    <p>You can also use the AI Assistant (chat button at the bottom right) to get instant answers to common questions, or to schedule a callback.</p>
  </div>
</section>
HTML;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CHATBOT TRAINING DATA
    // ─────────────────────────────────────────────────────────────────────────

    private function consultantTraining(string $name): array
    {
        return [
            ['category' => 'Services', 'question' => 'What services do you offer?', 'answer' => "I offer IT consulting services including cloud architecture, AI/ML integration, cybersecurity, and software development. My speciality is helping enterprises modernise their technology infrastructure and adopt AI-powered workflows."],
            ['category' => 'Services', 'question' => 'Do you do cloud migration?', 'answer' => "Yes, cloud migration is one of my core services. I work with AWS, Azure, and GCP and have successfully migrated 50+ enterprise workloads. I handle everything from assessment and planning to execution and post-migration optimisation."],
            ['category' => 'Services', 'question' => 'Can you help with AI implementation?', 'answer' => "Absolutely. I specialise in LLM integration, RAG pipeline development, and AI-powered automation. I've built AI solutions for customer support, document processing, and predictive analytics across multiple industries."],
            ['category' => 'Pricing', 'question' => 'What are your consulting rates?', 'answer' => "My project-based engagements start from ₹1,00,000 for software development and ₹2,50,000 for cloud architecture projects. I also offer retainer arrangements for ongoing advisory. Let's schedule a call to discuss your specific needs and provide a tailored quote."],
            ['category' => 'Pricing', 'question' => 'Do you offer a free consultation?', 'answer' => "Yes, I offer a free 30-minute discovery call to understand your requirements and determine if we're a good fit. You can book this through the Contact page."],
            ['category' => 'Experience', 'question' => 'How many years of experience do you have?', 'answer' => "I have 12+ years of experience in IT, spanning software engineering, infrastructure, and consulting. I've worked with clients across BFSI, healthcare, and e-commerce sectors in India, the UK, and the US."],
            ['category' => 'Experience', 'question' => 'What industries do you work with?', 'answer' => "I have deep experience in BFSI (banking, financial services, insurance), healthcare, e-commerce, and technology companies. I'm comfortable working with both large enterprises and fast-growing startups."],
            ['category' => 'Process', 'question' => 'How do you work with clients?', 'answer' => "My typical engagement starts with a discovery call, followed by a detailed proposal. Once engaged, I work in structured sprints with weekly check-ins and clear deliverables. I believe in transparent communication and proactive problem-solving."],
            ['category' => 'Contact', 'question' => 'How can I contact you?', 'answer' => "You can reach me via email, the contact form on this website, or by booking a call directly. I typically respond within 24 hours on business days."],
            ['category' => 'Contact', 'question' => 'Are you available for remote work?', 'answer' => "Yes, I work with clients globally on a remote basis. For local clients in Chennai, I'm also available for on-site engagements when needed."],
        ];
    }

    private function influencerTraining(string $name): array
    {
        return [
            ['category' => 'Collaborations', 'question' => 'How can my brand work with you?', 'answer' => "I'd love to explore a collaboration! I work with brands across fashion, beauty, travel, and lifestyle. Please visit the Collaborations page to see my packages, or reach out via the Contact page with your brand details and campaign goals."],
            ['category' => 'Collaborations', 'question' => 'What are your collaboration rates?', 'answer' => "My rates vary by platform and content type. Instagram posts start from ₹25,000, YouTube integrations from ₹75,000, and blog/newsletter placements from ₹15,000. I also offer brand ambassador packages for long-term partnerships. Reach out for a custom quote."],
            ['category' => 'Audience', 'question' => 'What is your audience demographic?', 'answer' => "My audience is primarily women aged 22–35, based in metro cities across India. They are educated, digitally savvy, and interested in fashion, travel, wellness, and lifestyle. Engagement rate is 4.2%, well above the industry average."],
            ['category' => 'Content', 'question' => 'What type of content do you create?', 'answer' => "I create content across fashion, travel, wellness, and lifestyle. This includes Instagram posts and reels, YouTube videos, blog articles, and newsletter content. All content is authentic, well-produced, and aligned with my personal brand aesthetic."],
            ['category' => 'Process', 'question' => 'What is your collaboration process?', 'answer' => "It starts with a discovery call to understand your brand and campaign goals. I then send a proposal with creative concepts, timelines, and pricing. Once agreed, I create the content with full creative freedom to ensure it feels authentic. After publishing, I share detailed performance metrics."],
            ['category' => 'Values', 'question' => 'What brands do you work with?', 'answer' => "I partner with brands that align with my values of authenticity, sustainability, and quality. I've worked with Nykaa, Myntra, Airbnb, Levi's, Forest Essentials, and Yoga Bar, among others. I turn down partnerships that don't feel genuine to my audience."],
            ['category' => 'Contact', 'question' => 'How do I get in touch?', 'answer' => "You can reach me via the Contact page or email me directly. For collaboration enquiries, please include your brand name, product/service, campaign goals, and preferred timeline. I respond to all genuine enquiries within 48 hours."],
        ];
    }

    private function advocateTraining(string $name): array
    {
        return [
            ['category' => 'Services', 'question' => 'What legal services do you offer?', 'answer' => "I specialise in corporate law, intellectual property, dispute resolution, and contract drafting. My practice covers company incorporation, M&A, trademark and patent filing, commercial arbitration, and consumer protection matters."],
            ['category' => 'Services', 'question' => 'Can you help with company incorporation?', 'answer' => "Yes, I handle all aspects of company incorporation including Private Limited, LLP, and OPC structures. This includes drafting MOA/AOA, filing with MCA, obtaining PAN/TAN, and advising on the most suitable structure for your business."],
            ['category' => 'Services', 'question' => 'Do you handle trademark registration?', 'answer' => "Yes, trademark registration is one of my core practice areas. I handle trademark search, application filing, prosecution, and enforcement. I also handle international trademark filings through the Madrid Protocol."],
            ['category' => 'Consultation', 'question' => 'How much does a consultation cost?', 'answer' => "Initial consultations are available at ₹2,000 for 30 minutes. Corporate retainer arrangements are also available for businesses requiring ongoing legal support. Please contact me to discuss your requirements."],
            ['category' => 'Process', 'question' => 'How do I engage your services?', 'answer' => "Start by booking an initial consultation through the Contact page. During the consultation, we'll discuss your legal matter, I'll assess the situation, and provide a clear engagement letter with scope of work, timeline, and fees."],
            ['category' => 'Courts', 'question' => 'Which courts do you practice in?', 'answer' => "I am enrolled with the Bar Council of Tamil Nadu and practice before the Madras High Court, National Company Law Tribunal (NCLT), and the Supreme Court of India. I also handle arbitration proceedings under ICC, SIAC, and DIAC rules."],
            ['category' => 'Contact', 'question' => 'How can I contact you?', 'answer' => "You can book a consultation through the Contact page, call the office, or email directly. For urgent matters, please call. I respond to all enquiries within 24 hours on business days."],
        ];
    }

    private function doctorTraining(string $name): array
    {
        return [
            ['category' => 'Appointments', 'question' => 'How do I book an appointment?', 'answer' => "You can book an appointment through the Appointments page on this website, call the clinic directly at +91 98765 43210, or use the AI assistant to schedule a callback. Teleconsultations are also available."],
            ['category' => 'Services', 'question' => 'What medical conditions do you treat?', 'answer' => "I treat a wide range of general medical conditions including diabetes, hypertension, thyroid disorders, respiratory conditions, infections, and chronic diseases. I also offer preventive health check-ups and wellness consulting."],
            ['category' => 'Teleconsultation', 'question' => 'Is teleconsultation available?', 'answer' => "Yes, teleconsultations are available Monday to Saturday from 2:00 PM to 4:00 PM via video call. The fee is ₹400. Book online and you'll receive the meeting link via email."],
            ['category' => 'Fees', 'question' => 'What are the consultation fees?', 'answer' => "General medicine consultation is ₹500 and follow-up visits are ₹300. Teleconsultation is ₹400. Preventive health packages start from ₹1,500. Please contact the clinic for the latest fee schedule."],
            ['category' => 'Hours', 'question' => 'What are the clinic hours?', 'answer' => "The clinic is open Monday to Friday from 9:00 AM to 1:00 PM and 5:00 PM to 8:00 PM. Saturday hours are 9:00 AM to 2:00 PM. For emergencies outside clinic hours, please call the emergency number."],
            ['category' => 'Emergency', 'question' => 'What should I do in a medical emergency?', 'answer' => "For medical emergencies, please call 108 (ambulance) or go to the nearest emergency room immediately. Do not wait for a regular appointment. The clinic handles non-emergency consultations only."],
            ['category' => 'Contact', 'question' => 'How do I contact the clinic?', 'answer' => "You can call +91 98765 43210, email the clinic, or use the Contact page. For appointment bookings, the Appointments page has all the details. We respond to all messages within the same business day."],
        ];
    }

    private function entrepreneurTraining(string $name): array
    {
        return [
            ['category' => 'Investments', 'question' => 'What stage do you invest in?', 'answer' => "I invest primarily at pre-seed and seed stage, with typical ticket sizes of ₹25L to ₹1Cr. I focus on AI/deep tech, climate tech, EdTech, HealthTech, D2C brands, and B2B SaaS startups in India."],
            ['category' => 'Investments', 'question' => 'How can I pitch to you?', 'answer' => "Send a brief email with your one-pager or pitch deck to my email address. I review all pitches personally and respond to those that align with my investment thesis within 2 weeks."],
            ['category' => 'Advisory', 'question' => 'Do you offer startup advisory?', 'answer' => "Yes, I offer strategic advisory to early-stage founders on product-market fit, fundraising, team building, and go-to-market strategy. Advisory engagements are available on a monthly retainer or equity basis."],
            ['category' => 'Speaking', 'question' => 'Are you available for speaking engagements?', 'answer' => "Yes, I speak at startup events, corporate innovation programmes, and university sessions. My topics include building startups in India, fundraising, and the future of AI in business. Please contact me with your event details."],
            ['category' => 'Ventures', 'question' => 'What companies have you built?', 'answer' => "I've founded and exited 3 companies: SoftSolve Technologies (acquired by TCS), PayEasy (acquired by a private bank), and ShopLocal (merged with a D2C aggregator). I currently run TechFlow AI and GreenCart."],
            ['category' => 'Contact', 'question' => 'How can I connect with you?', 'answer' => "The best way to connect is via email or LinkedIn. For investment pitches, send a brief email with your pitch deck. For advisory enquiries, use the Contact page. I respond to all genuine messages within 48 hours."],
        ];
    }

    private function politicianTraining(string $name): array
    {
        return [
            ['category' => 'Constituency', 'question' => 'What has been done for the constituency?', 'answer' => "Over the past 5 years, we have secured ₹500Cr+ in development funds, built 25 new schools, launched the Smart Village Programme connecting 100 villages to broadband, established 20 women empowerment centres, and created 5,000+ jobs through the industrial park."],
            ['category' => 'Initiatives', 'question' => 'What is the highway project status?', 'answer' => "The 45km four-lane highway project connecting our constituency to the state capital is 60% complete and on track for completion by December 2025. This ₹120Cr project will significantly reduce travel time and boost economic activity."],
            ['category' => 'Contact', 'question' => 'How can I meet the MP?', 'answer' => "Constituency office hours are Monday and Wednesday from 10:00 AM to 1:00 PM for public meetings. You can also contact the office by phone or email to schedule an appointment. For urgent matters, the office staff will assist you."],
            ['category' => 'Grievances', 'question' => 'How do I submit a grievance?', 'answer' => "Grievances can be submitted through the Contact page, by visiting the constituency office during office hours, or by calling the office. All grievances are logged and addressed within 15 working days."],
            ['category' => 'Vision', 'question' => 'What is your vision for the constituency?', 'answer' => "My vision is a constituency where every citizen has access to quality education, healthcare, employment, and infrastructure. The 5-year development agenda focuses on completing the highway, opening 10 new schools, building a new district hospital, and creating 5,000 additional jobs."],
            ['category' => 'Contact', 'question' => 'What is the office address?', 'answer' => "The constituency office is located at 45, Main Road, Town Centre. Office hours are Monday to Friday, 10:00 AM to 5:00 PM. For the Parliament office, please contact through the official Parliament website."],
        ];
    }
}
