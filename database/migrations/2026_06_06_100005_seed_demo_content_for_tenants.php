<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\CustomPage;
use App\Models\SiteMenu;

return new class extends Migration
{
    public function up(): void
    {
        // ── Fix Priya's template (influencer, not consultant) ──────────────
        $priya = User::where('username', 'priya')->first();
        if ($priya) {
            SiteSetting::setValueForTenant($priya->id, 'profile_template', 'influencer');
        }

        // ── Demo content per tenant ────────────────────────────────────────
        $tenants = [
            'arjun'     => $this->arjunData(),
            'priya'     => $this->priyaData(),
            'soushanth' => $this->soushanth_data(),
        ];

        foreach ($tenants as $username => $data) {
            $user = User::where('username', $username)->first();
            if (!$user) continue;

            // Site settings
            foreach ($data['settings'] as $key => $value) {
                SiteSetting::setValueForTenant($user->id, $key, $value);
            }

            // Social links — clear existing and re-seed
            SocialLink::where('user_id', $user->id)->delete();
            foreach ($data['socials'] as $i => $social) {
                SocialLink::create(array_merge($social, [
                    'user_id'    => $user->id,
                    'sort_order' => $i + 1,
                    'is_active'  => true,
                ]));
            }

            // Custom pages — update sections for home page
            $homePage = CustomPage::where('user_id', $user->id)
                ->where('page_type', 'home')
                ->first();
            if ($homePage && !empty($data['home_sections'])) {
                $merged = $homePage->merged_sections ?? CustomPage::defaultSections($homePage->page_type ?? 'home');
                foreach ($data['home_sections'] as $sectionKey => $sectionData) {
                    if (isset($merged[$sectionKey])) {
                        $merged[$sectionKey] = array_merge($merged[$sectionKey], $sectionData);
                    }
                }
                $homePage->update(['sections' => $merged]);
            }

            // Menu — clear and re-seed
            SiteMenu::where('user_id', $user->id)->whereNull('parent_id')->delete();
            foreach ($data['menu'] as $i => $item) {
                $children = $item['children'] ?? [];
                unset($item['children']);
                $parent = SiteMenu::create(array_merge($item, [
                    'user_id'    => $user->id,
                    'sort_order' => $i + 1,
                ]));
                foreach ($children as $j => $child) {
                    SiteMenu::create(array_merge($child, [
                        'user_id'    => $user->id,
                        'parent_id'  => $parent->id,
                        'sort_order' => $j + 1,
                    ]));
                }
            }
        }
    }

    private function arjunData(): array
    {
        return [
            'settings' => [
                'site_name'        => 'Arjun Mehta',
                'site_tagline'     => 'Senior Advocate & Legal Consultant',
                'site_description' => 'Experienced advocate specialising in corporate law, civil litigation, and intellectual property rights. Trusted by 500+ clients across India.',
                'contact_email'    => 'arjun@xenoraa.com',
                'contact_phone'    => '+91 98765 43210',
                'contact_address'  => 'Chambers No. 12, High Court Road, Chennai, Tamil Nadu 600001',
                'profile_template' => 'advocate',
            ],
            'socials' => [
                ['platform' => 'linkedin', 'url' => 'https://www.linkedin.com/in/arjun-mehta-advocate', 'icon_class' => 'fab fa-linkedin'],
                ['platform' => 'twitter',  'url' => 'https://twitter.com/arjunmehtalaw',               'icon_class' => 'fab fa-twitter'],
                ['platform' => 'youtube',  'url' => 'https://youtube.com/@arjunmehtalegal',             'icon_class' => 'fab fa-youtube'],
            ],
            'home_sections' => [
                'hero' => ['enabled' => true, 'data' => ['title' => 'Arjun Mehta', 'subtitle' => 'Senior Advocate & Legal Consultant', 'cta_text' => 'Book a Consultation']],
                'about' => ['enabled' => true],
                'services' => ['enabled' => true],
                'blog' => ['enabled' => true],
                'contact' => ['enabled' => true],
            ],
            'menu' => [
                ['label' => 'Home',     'url' => '/',        'target' => '_self'],
                ['label' => 'About',    'url' => '/about',   'target' => '_self'],
                ['label' => 'Practice Areas', 'url' => '#', 'target' => '_self', 'children' => [
                    ['label' => 'Corporate Law',      'url' => '/about', 'icon' => 'fas fa-building'],
                    ['label' => 'Civil Litigation',   'url' => '/about', 'icon' => 'fas fa-gavel'],
                    ['label' => 'IP Rights',          'url' => '/about', 'icon' => 'fas fa-copyright'],
                ]],
                ['label' => 'Blog',     'url' => '/blog',    'target' => '_self'],
                ['label' => 'Contact',  'url' => '/about',   'target' => '_self'],
            ],
        ];
    }

    private function priyaData(): array
    {
        return [
            'settings' => [
                'site_name'        => 'Priya Sharma',
                'site_tagline'     => 'Lifestyle & Fashion Influencer',
                'site_description' => 'Fashion, beauty, travel and lifestyle content creator with 2M+ followers. Collaborating with premium brands to inspire authentic living.',
                'contact_email'    => 'priya@xenoraa.com',
                'contact_phone'    => '+91 99887 76655',
                'profile_template' => 'influencer',
            ],
            'socials' => [
                ['platform' => 'instagram', 'url' => 'https://www.instagram.com/priyasharma_lifestyle', 'icon_class' => 'fab fa-instagram'],
                ['platform' => 'youtube',   'url' => 'https://youtube.com/@priyasharmalifestyle',       'icon_class' => 'fab fa-youtube'],
                ['platform' => 'twitter',   'url' => 'https://twitter.com/priyasharma_life',            'icon_class' => 'fab fa-twitter'],
                ['platform' => 'tiktok',    'url' => 'https://tiktok.com/@priyasharmalife',             'icon_class' => 'fab fa-tiktok'],
            ],
            'home_sections' => [
                'hero'     => ['enabled' => true],
                'about'    => ['enabled' => true],
                'services' => ['enabled' => true],
                'blog'     => ['enabled' => true],
                'shop'     => ['enabled' => true],
                'contact'  => ['enabled' => true],
            ],
            'menu' => [
                ['label' => 'Home',        'url' => '/',      'target' => '_self'],
                ['label' => 'About',       'url' => '/about', 'target' => '_self'],
                ['label' => 'Content',     'url' => '/blog',  'target' => '_self'],
                ['label' => 'Shop',        'url' => '/shop',  'target' => '_self'],
                ['label' => 'Collaborate', 'url' => '/about', 'target' => '_self'],
            ],
        ];
    }

    private function soushanth_data(): array
    {
        return [
            'settings' => [
                'site_name'        => 'Soushanth',
                'site_tagline'     => 'Entrepreneur & Startup Founder',
                'site_description' => 'Serial entrepreneur, startup mentor, and business coach. Founded 3 companies. Helping founders build scalable businesses from idea to IPO.',
                'contact_email'    => 'soushanth@xenoraa.com',
                'contact_phone'    => '+91 97654 32109',
                'profile_template' => 'entrepreneur',
            ],
            'socials' => [
                ['platform' => 'linkedin', 'url' => 'https://www.linkedin.com/in/soushanth-entrepreneur', 'icon_class' => 'fab fa-linkedin'],
                ['platform' => 'twitter',  'url' => 'https://twitter.com/soushanth_builds',              'icon_class' => 'fab fa-twitter'],
                ['platform' => 'youtube',  'url' => 'https://youtube.com/@soushanth',                    'icon_class' => 'fab fa-youtube'],
            ],
            'home_sections' => [
                'hero'     => ['enabled' => true],
                'stats'    => ['enabled' => true],
                'about'    => ['enabled' => true],
                'services' => ['enabled' => true],
                'blog'     => ['enabled' => true],
                'jobs'     => ['enabled' => true],
                'contact'  => ['enabled' => true],
            ],
            'menu' => [
                ['label' => 'Home',     'url' => '/',      'target' => '_self'],
                ['label' => 'About',    'url' => '/about', 'target' => '_self'],
                ['label' => 'Ventures', 'url' => '/about', 'target' => '_self'],
                ['label' => 'Blog',     'url' => '/blog',  'target' => '_self'],
                ['label' => 'Jobs',     'url' => '/jobs',  'target' => '_self'],
                ['label' => 'Contact',  'url' => '/about', 'target' => '_self'],
            ],
        ];
    }

    public function down(): void
    {
        // Not reversible
    }
};
