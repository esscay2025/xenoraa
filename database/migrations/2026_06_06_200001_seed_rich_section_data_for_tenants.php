<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\CustomPage;
use App\Models\SiteMenu;

return new class extends Migration
{
    public function up(): void
    {
        $tenants = [
            'priya'     => $this->priyaData(),
            'arjun'     => $this->arjunData(),
            'soushanth' => $this->soushanth_data(),
        ];

        foreach ($tenants as $username => $data) {
            $user = User::where('username', $username)->first();
            if (!$user) continue;

            // Update/create site settings
            foreach ($data['settings'] as $key => $value) {
                SiteSetting::setValueForTenant($user->id, $key, is_array($value) ? json_encode($value) : $value);
            }

            // Update home page sections with rich data
            $homePage = CustomPage::where('user_id', $user->id)
                ->where('page_type', 'home')
                ->first();

            if ($homePage) {
                $defaultSections = CustomPage::defaultSections('home');
                $existing = $homePage->sections ?? [];
                $merged = array_replace_recursive($defaultSections, $existing, $data['home_sections']);
                $homePage->update(['sections' => $merged]);
            }

            // Update menu with proper links
            if (!empty($data['menu'])) {
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
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIYA SHARMA — Influencer
    // ─────────────────────────────────────────────────────────────────────────
    private function priyaData(): array
    {
        return [
            'settings' => [
                'profile_template'            => 'influencer',
                'site_name'                   => 'Priya Sharma',
                'site_tagline'                => 'Lifestyle · Fashion · Travel · Wellness Creator',
                'profile_title'               => 'Lifestyle & Fashion Content Creator',
                'profile_handle'              => '@priyasharma_lifestyle',
                'profile_niche'               => 'Lifestyle · Fashion · Travel · Wellness',
                'profile_followers_total'     => '2M+',
                'profile_instagram_followers' => '1.2M',
                'profile_youtube_subscribers' => '450K',
                'profile_twitter_followers'   => '280K',
                'profile_tiktok_followers'    => '320K',
                'profile_instagram'           => 'https://instagram.com/priyasharma_lifestyle',
                'profile_youtube'             => 'https://youtube.com/@priyasharmalifestyle',
                'profile_twitter'             => 'https://twitter.com/priyasharma_life',
                'profile_tiktok'              => 'https://tiktok.com/@priyasharmalife',
                'profile_collab_email'        => 'collabs@priyasharma.in',
                'profile_about'               => 'Priya Sharma is India\'s leading lifestyle and fashion content creator with 2M+ followers across platforms. Known for her authentic storytelling, stunning fashion editorials, and genuine brand partnerships, Priya inspires millions to live beautifully and confidently. Based in Mumbai, she collaborates with luxury and lifestyle brands globally.',
                'profile_years'               => '7+',
                'profile_clients'             => '150+',
                'profile_projects'            => '800+',
                'contact_email'               => 'priya@xenoraa.com',
                'contact_phone'               => '+91 99887 76655',
                'accent_color'                => '#f43f5e',
                'profile_collab_types'        => json_encode(['Sponsored Posts', 'Brand Ambassadorship', 'Product Reviews', 'Reels & Stories', 'Event Coverage', 'YouTube Integrations']),
            ],
            'home_sections' => [
                'hero' => [
                    'enabled' => true,
                    'heading' => 'Priya Sharma',
                    'subheading' => 'Lifestyle · Fashion · Travel · Wellness Creator',
                    'cta_text' => 'Collaborate With Me',
                    'cta_url' => '/collaborations',
                    'image' => '',
                ],
                'followers' => [
                    'enabled' => true,
                    'heading' => 'My Reach',
                    'instagram' => '1.2M',
                    'youtube' => '450K',
                    'twitter' => '280K',
                    'tiktok' => '320K',
                    'total' => '2M+',
                ],
                'stats' => [
                    'enabled' => true,
                    'items' => [
                        ['icon' => '📸', 'value' => '2M+',  'label' => 'Total Followers'],
                        ['icon' => '🤝', 'value' => '150+', 'label' => 'Brand Collabs'],
                        ['icon' => '🎬', 'value' => '800+', 'label' => 'Posts Created'],
                        ['icon' => '⭐', 'value' => '7+',   'label' => 'Years Creating'],
                    ],
                ],
                'about' => [
                    'enabled' => true,
                    'heading' => 'Hey, I\'m Priya!',
                    'text' => 'I\'m a lifestyle and fashion content creator based in Mumbai, India. For 7+ years, I\'ve been sharing my love for fashion, travel, beauty, and mindful living with 2M+ followers across Instagram, YouTube, TikTok, and Twitter. I believe in authentic storytelling and creating content that genuinely inspires and adds value to people\'s lives.',
                ],
                'services' => [
                    'enabled' => true,
                    'heading' => 'Collaboration Packages',
                    'subheading' => 'Let\'s create authentic content that resonates with my audience',
                    'items' => [
                        ['icon' => '📸', 'title' => 'Instagram Posts & Reels', 'text' => 'High-quality styled photos and engaging Reels with authentic storytelling and strong engagement rates', 'price' => 'Starting ₹50,000'],
                        ['icon' => '🎬', 'title' => 'YouTube Integrations',    'text' => 'Dedicated videos or brand integrations in my lifestyle, travel, and fashion vlogs with 450K subscribers', 'price' => 'Starting ₹80,000'],
                        ['icon' => '🌟', 'title' => 'Brand Ambassadorship',    'text' => 'Long-term brand partnerships with consistent content creation, events, and exclusive audience access', 'price' => 'Custom Pricing'],
                        ['icon' => '📱', 'title' => 'Stories & Highlights',    'text' => '24-hour Instagram Stories with swipe-up links, polls, and interactive content for maximum engagement', 'price' => 'Starting ₹20,000'],
                        ['icon' => '✈️', 'title' => 'Travel & Destination',    'text' => 'Destination campaigns, hotel stays, travel brand collaborations with stunning visual content', 'price' => 'Custom Pricing'],
                        ['icon' => '💄', 'title' => 'Beauty & Fashion Edits',  'text' => 'Product reviews, try-on hauls, GRWM videos, and beauty tutorials for fashion and beauty brands', 'price' => 'Starting ₹35,000'],
                    ],
                ],
                'testimonials' => [
                    'enabled' => true,
                    'heading' => 'Brand Partners Say',
                    'items' => [
                        ['name' => 'Nisha Kapoor',   'role' => 'Marketing Head, Luxe Beauty India',  'text' => 'Priya\'s authentic approach to content creation delivered a 340% ROI on our campaign. Her audience engagement is phenomenal.', 'avatar' => ''],
                        ['name' => 'Rahul Joshi',    'role' => 'Brand Manager, TravelLux',           'text' => 'The travel content Priya created for our Maldives resort was breathtaking. Bookings increased by 60% during the campaign.', 'avatar' => ''],
                        ['name' => 'Ananya Singh',   'role' => 'CEO, StyleCo Fashion',               'text' => 'Working with Priya was a game-changer for our brand launch. Her styling and storytelling are unmatched in the industry.', 'avatar' => ''],
                    ],
                ],
                'blog' => [
                    'enabled' => true,
                    'heading' => 'Latest Content',
                    'count' => 3,
                ],
                'shop' => [
                    'enabled' => true,
                    'heading' => 'Shop My Picks',
                ],
                'contact' => [
                    'enabled' => true,
                    'heading' => 'Let\'s Create Together',
                    'text' => 'Interested in a collaboration? I\'d love to hear about your brand and create authentic content that resonates with my audience.',
                    'button_text' => 'Get Collaboration Kit',
                    'button_url' => '/collaborations',
                ],
            ],
            'menu' => [
                ['label' => 'Home',           'url' => '/',               'target' => '_self'],
                ['label' => 'About',          'url' => '/about',          'target' => '_self'],
                ['label' => 'Collaborations', 'url' => '/collaborations', 'target' => '_self'],
                ['label' => 'Shop',           'url' => '/shop',           'target' => '_self'],
                ['label' => 'Blog',           'url' => '/blog',           'target' => '_self'],
                ['label' => 'Contact',        'url' => '/contact',        'target' => '_self'],
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ARJUN MEHTA — Advocate
    // ─────────────────────────────────────────────────────────────────────────
    private function arjunData(): array
    {
        return [
            'settings' => [
                'profile_template'       => 'advocate',
                'site_name'              => 'Arjun Mehta, Advocate',
                'site_tagline'           => 'Corporate Law · Civil Litigation · Intellectual Property',
                'profile_title'          => 'Senior Advocate & Legal Consultant',
                'profile_enrollment_no'  => 'TN/2005/08762',
                'profile_bar_number'     => 'TN/2005/08762',
                'profile_court'          => 'Madras High Court, NCLT & Supreme Court of India',
                'contact_address'        => 'Chambers No. 12, High Court Road, Chennai, Tamil Nadu 600 001',
                'profile_booking_link'   => '/contact',
                'profile_about'          => 'Arjun Mehta is a distinguished Senior Advocate with 18+ years of experience in corporate law, civil litigation, and intellectual property rights. Enrolled with the Bar Council of Tamil Nadu, Arjun has successfully represented clients before the Madras High Court, National Company Law Tribunal (NCLT), and the Supreme Court of India. Known for his meticulous preparation and strategic approach, he has won 1,200+ cases across diverse legal domains.',
                'profile_years'          => '18+',
                'profile_clients'        => '800+',
                'profile_cases_won'      => '1,200+',
                'contact_email'          => 'arjun@xenoraa.com',
                'contact_phone'          => '+91 98765 43210',
                'accent_color'           => '#b45309',
                'profile_practice_areas' => json_encode(['Corporate Law', 'Civil Litigation', 'Intellectual Property', 'Criminal Defense', 'Family Law', 'Property Law']),
            ],
            'home_sections' => [
                'hero' => [
                    'enabled' => true,
                    'heading' => 'Arjun Mehta',
                    'subheading' => 'Senior Advocate & Legal Consultant',
                    'cta_text' => 'Book Free Consultation',
                    'cta_url' => '/contact',
                ],
                'stats' => [
                    'enabled' => true,
                    'items' => [
                        ['icon' => '⚖️', 'value' => '18+',    'label' => 'Years Experience'],
                        ['icon' => '🏛️', 'value' => '1,200+', 'label' => 'Cases Won'],
                        ['icon' => '👥', 'value' => '800+',   'label' => 'Clients Served'],
                        ['icon' => '🏆', 'value' => '15+',    'label' => 'Courts Practiced'],
                    ],
                ],
                'about' => [
                    'enabled' => true,
                    'heading' => 'About Arjun Mehta',
                    'text' => 'Arjun Mehta is a distinguished Senior Advocate with 18+ years of experience in corporate law, civil litigation, and intellectual property rights. Enrolled with the Bar Council of Tamil Nadu, Arjun has successfully represented clients before the Madras High Court, NCLT, and the Supreme Court of India. Known for his meticulous preparation and strategic approach, he has won 1,200+ cases across diverse legal domains.',
                ],
                'services' => [
                    'enabled' => true,
                    'heading' => 'Practice Areas',
                    'subheading' => 'Comprehensive legal services across all major areas of law',
                    'items' => [
                        ['icon' => '⚖️', 'title' => 'Civil Litigation',       'text' => 'Property disputes, contract enforcement, injunctions, and civil appeals across all courts in India'],
                        ['icon' => '🏛️', 'title' => 'Criminal Defense',       'text' => 'Bail applications, trial defense, appeals, and white-collar crime representation at all levels'],
                        ['icon' => '🏢', 'title' => 'Corporate Law',           'text' => 'Mergers & acquisitions, compliance, corporate governance, and commercial dispute resolution'],
                        ['icon' => '©️', 'title' => 'Intellectual Property',  'text' => 'Trademark registration, patent filing, copyright protection, and IP enforcement & litigation'],
                        ['icon' => '👨‍👩‍👧', 'title' => 'Family Law',            'text' => 'Divorce, child custody, maintenance, succession planning, and matrimonial property disputes'],
                        ['icon' => '🏗️', 'title' => 'Property & Real Estate', 'text' => 'Title verification, RERA disputes, registration, and landlord-tenant matters'],
                    ],
                ],
                'testimonials' => [
                    'enabled' => true,
                    'heading' => 'Client Testimonials',
                    'items' => [
                        ['name' => 'Ramesh Kumar',   'role' => 'Managing Director, Kumar Industries',   'text' => 'Arjun\'s exceptional legal acumen won a complex corporate dispute that had been pending for 3 years. His strategic approach and courtroom presence are unmatched.', 'avatar' => ''],
                        ['name' => 'Sunita Sharma',  'role' => 'Client, Family Law Matter',             'text' => 'Compassionate, thorough, and highly effective. Arjun handled my divorce case with utmost professionalism and sensitivity. I cannot recommend him highly enough.', 'avatar' => ''],
                        ['name' => 'Anil Patel',     'role' => 'CEO, Patel Constructions',              'text' => 'Outstanding property law expertise. Arjun resolved a major title dispute that saved our ₹50 crore project. His knowledge of RERA is exceptional.', 'avatar' => ''],
                    ],
                ],
                'blog' => [
                    'enabled' => true,
                    'heading' => 'Legal Insights',
                    'count' => 3,
                ],
                'contact' => [
                    'enabled' => true,
                    'heading' => 'Need Legal Advice?',
                    'text' => 'Schedule a confidential consultation to discuss your legal matter. Initial consultation is complimentary.',
                    'button_text' => 'Book Free Consultation',
                    'button_url' => '/contact',
                ],
            ],
            'menu' => [
                ['label' => 'Home',           'url' => '/',               'target' => '_self'],
                ['label' => 'About',          'url' => '/about',          'target' => '_self'],
                ['label' => 'Practice Areas', 'url' => '/practice-areas', 'target' => '_self'],
                ['label' => 'Case Studies',   'url' => '/case-studies',   'target' => '_self'],
                ['label' => 'Legal Insights', 'url' => '/blog',           'target' => '_self'],
                ['label' => 'Contact',        'url' => '/contact',        'target' => '_self'],
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SOUSHANTH — Entrepreneur
    // ─────────────────────────────────────────────────────────────────────────
    private function soushanth_data(): array
    {
        return [
            'settings' => [
                'profile_template'        => 'entrepreneur',
                'site_name'               => 'Soushanth',
                'site_tagline'            => 'Serial Entrepreneur · Startup Founder · Angel Investor',
                'profile_title'           => 'Serial Entrepreneur & Startup Founder',
                'profile_ventures_built'  => '5+',
                'profile_funding_raised'  => '₹75Cr+',
                'profile_team_size'       => '300+',
                'profile_pitch_link'      => '/contact',
                'profile_linkedin'        => 'https://linkedin.com/in/soushanth-entrepreneur',
                'profile_about'           => 'Soushanth is a serial entrepreneur who has founded and scaled 5+ ventures across technology, education, consumer goods, and healthcare. With ₹75Cr+ in funding raised and 300+ team members across ventures, Soushanth brings deep operational expertise and a track record of building businesses from zero to scale. He is also an active angel investor and startup mentor.',
                'profile_years'           => '12+',
                'profile_clients'         => '50+',
                'profile_projects'        => '5+',
                'contact_email'           => 'soushanth@xenoraa.com',
                'contact_phone'           => '+91 97654 32109',
                'accent_color'            => '#10b981',
                'profile_industries'      => json_encode(['Technology', 'EdTech', 'Consumer Goods', 'Healthcare', 'FinTech']),
            ],
            'home_sections' => [
                'hero' => [
                    'enabled' => true,
                    'heading' => 'Soushanth',
                    'subheading' => 'Serial Entrepreneur · Startup Founder · Angel Investor',
                    'cta_text' => 'View My Ventures',
                    'cta_url' => '/ventures',
                ],
                'stats' => [
                    'enabled' => true,
                    'items' => [
                        ['icon' => '🚀', 'value' => '5+',     'label' => 'Ventures Built'],
                        ['icon' => '💰', 'value' => '₹75Cr+', 'label' => 'Funding Raised'],
                        ['icon' => '👥', 'value' => '300+',   'label' => 'Team Members'],
                        ['icon' => '🌍', 'value' => '5',      'label' => 'Industries'],
                    ],
                ],
                'about' => [
                    'enabled' => true,
                    'heading' => 'My Entrepreneurial Journey',
                    'text' => 'I started my first venture at 24 with ₹50,000 and a laptop. Today, I have built and scaled 5+ companies across technology, education, consumer goods, and healthcare. My journey has been one of relentless learning, bold bets, and building teams that believe in the mission. I am passionate about the Indian startup ecosystem and actively mentor early-stage founders.',
                ],
                'ventures' => [
                    'enabled' => true,
                    'heading' => 'My Ventures',
                    'items' => [
                        ['icon' => '🚀', 'title' => 'TechStart Solutions',  'text' => 'B2B SaaS platform for SME automation — 10,000+ users, ₹20Cr ARR, Series A funded', 'url' => ''],
                        ['icon' => '🎓', 'title' => 'LearnFast Academy',    'text' => 'EdTech platform with 25,000+ learners — upskilling programs in tech, business, and design', 'url' => ''],
                        ['icon' => '🛒', 'title' => 'QuickMart India',      'text' => 'Quick commerce platform — ₹50Cr GMV in Year 1, operating in 8 cities across South India', 'url' => ''],
                        ['icon' => '🏥', 'title' => 'HealthFirst Clinics',  'text' => 'Chain of affordable primary healthcare clinics — 15 clinics, 50,000+ patients served', 'url' => ''],
                        ['icon' => '💳', 'title' => 'FinEasy Payments',     'text' => 'Fintech platform for SME payments and working capital — ₹100Cr+ transactions processed', 'url' => ''],
                    ],
                ],
                'services' => [
                    'enabled' => true,
                    'heading' => 'How I Can Help',
                    'subheading' => 'Advisory, mentorship, and investment for early-stage founders',
                    'items' => [
                        ['icon' => '🎯', 'title' => 'Startup Mentoring',    'text' => 'One-on-one guidance on product-market fit, team building, fundraising, and scaling your startup'],
                        ['icon' => '💡', 'title' => 'Idea Validation',      'text' => 'Market research, MVP strategy, competitive analysis, and go-to-market planning'],
                        ['icon' => '💰', 'title' => 'Fundraising Advisory', 'text' => 'Pitch deck review, investor introductions, term sheet negotiation, and due diligence prep'],
                        ['icon' => '🤝', 'title' => 'Angel Investing',      'text' => 'Strategic investment in early-stage startups with hands-on mentorship and network access'],
                        ['icon' => '📊', 'title' => 'Growth Strategy',      'text' => 'Customer acquisition, retention, unit economics, and revenue optimization frameworks'],
                        ['icon' => '🌐', 'title' => 'Speaking & Keynotes',  'text' => 'Entrepreneurship, startup ecosystem, innovation, and building businesses in India talks'],
                    ],
                ],
                'testimonials' => [
                    'enabled' => true,
                    'heading' => 'Founder Testimonials',
                    'items' => [
                        ['name' => 'Kiran Rao',     'role' => 'Founder, DataSync AI',    'text' => 'Soushanth\'s mentorship transformed our startup. We closed our Series A in 6 months with the right guidance on pitch and investor relations. Truly invaluable.', 'avatar' => ''],
                        ['name' => 'Pooja Mehta',   'role' => 'CEO, GreenTech Ventures', 'text' => 'The strategic advice on market expansion helped us grow 5x in one year. Soushanth\'s network and operational insights are exceptional.', 'avatar' => ''],
                        ['name' => 'Aditya Sharma', 'role' => 'Co-founder, HealthPlus',  'text' => 'The angel investment and mentorship package was invaluable. We went from idea to product launch in 90 days with Soushanth\'s guidance.', 'avatar' => ''],
                    ],
                ],
                'blog' => [
                    'enabled' => true,
                    'heading' => 'Startup Insights',
                    'count' => 3,
                ],
                'jobs' => [
                    'enabled' => true,
                    'heading' => 'Join My Team',
                ],
                'contact' => [
                    'enabled' => true,
                    'heading' => 'Let\'s Build Something Great',
                    'text' => 'Whether you\'re a founder seeking mentorship, an investor exploring opportunities, or a brand looking to collaborate — let\'s connect and build something impactful.',
                    'button_text' => 'Connect With Me',
                    'button_url' => '/contact',
                ],
            ],
            'menu' => [
                ['label' => 'Home',      'url' => '/',          'target' => '_self'],
                ['label' => 'About',     'url' => '/about',     'target' => '_self'],
                ['label' => 'Ventures',  'url' => '/ventures',  'target' => '_self'],
                ['label' => 'Solutions', 'url' => '/solutions', 'target' => '_self'],
                ['label' => 'Blog',      'url' => '/blog',      'target' => '_self'],
                ['label' => 'Jobs',      'url' => '/jobs',      'target' => '_self'],
                ['label' => 'Contact',   'url' => '/contact',   'target' => '_self'],
            ],
        ];
    }

    public function down(): void
    {
        // Not reversible
    }
};
