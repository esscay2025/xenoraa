<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\CustomPage;
use App\Models\SiteSetting;

return new class extends Migration
{
    /**
     * Replace all section data with rich, profession-specific demo content.
     * This REPLACES (not merges) the sections array so all fields are populated.
     */
    public function up(): void
    {
        // ── Priya Sharma — Influencer ──────────────────────────────────────────
        $priya = User::where('username', 'priya')->first();
        if ($priya) {
            $this->seedTenantSections($priya->id, $this->priyaSections());
            $this->seedTenantSettings($priya->id, $this->priyaSettings());
        }

        // ── Arjun Mehta — Advocate ─────────────────────────────────────────────
        $arjun = User::where('username', 'arjun')->first();
        if ($arjun) {
            $this->seedTenantSections($arjun->id, $this->arjunSections());
            $this->seedTenantSettings($arjun->id, $this->arjunSettings());
        }

        // ── Soushanth — Entrepreneur ───────────────────────────────────────────
        $soushanth = User::where('username', 'soushanth')->first();
        if ($soushanth) {
            $this->seedTenantSections($soushanth->id, $this->soushanthSections());
            $this->seedTenantSettings($soushanth->id, $this->soushanthSettings());
        }
    }

    private function seedTenantSections(int $userId, array $pageData): void
    {
        foreach ($pageData as $slug => $sections) {
            $page = CustomPage::where('user_id', $userId)
                ->where(function($q) use ($slug) {
                    $q->where('slug', $slug)->orWhere('page_type', $slug);
                })->first();

            if ($page) {
                $page->sections = $sections;
                $page->save();
            }
        }
    }

    private function seedTenantSettings(int $userId, array $settings): void
    {
        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(
                ['user_id' => $userId, 'key' => $key],
                ['value' => $value]
            );
        }
    }

    // ══════════════════════════════════════════════════════════════════════════
    // PRIYA SHARMA — Lifestyle Influencer & Content Creator
    // ══════════════════════════════════════════════════════════════════════════
    private function priyaSections(): array
    {
        return [
            'home' => [
                ['key' => 'hero', 'label' => 'Hero / Banner', 'icon' => 'fas fa-image', 'enabled' => true, 'data' => [
                    'heading'    => 'Priya Sharma',
                    'subheading' => 'Lifestyle Creator · Beauty & Wellness Influencer · Brand Storyteller',
                    'badge'      => '✨ 2.4M+ Followers Across Platforms',
                    'cta_text'   => 'Collaborate with Me',
                    'cta_url'    => '/collaborations',
                    'image'      => '',
                    'bg_color'   => '',
                ]],
                ['key' => 'stats', 'label' => 'Stats / Numbers', 'icon' => 'fas fa-chart-bar', 'enabled' => true, 'data' => [
                    'heading' => 'My Reach',
                    'items'   => [
                        ['icon' => '📸', 'label' => 'Instagram Followers', 'value' => '1.2M'],
                        ['icon' => '▶️', 'label' => 'YouTube Subscribers', 'value' => '840K'],
                        ['icon' => '🐦', 'label' => 'Twitter Followers',   'value' => '320K'],
                        ['icon' => '🎵', 'label' => 'TikTok Followers',    'value' => '680K'],
                        ['icon' => '🤝', 'label' => 'Brand Collabs',       'value' => '150+'],
                        ['icon' => '⭐', 'label' => 'Avg. Engagement',     'value' => '6.8%'],
                    ],
                ]],
                ['key' => 'about', 'label' => 'About Snippet', 'icon' => 'fas fa-user', 'enabled' => true, 'data' => [
                    'heading'  => 'About Priya',
                    'text'     => 'Hey! I\'m Priya — a Mumbai-based lifestyle creator passionate about beauty, wellness, travel, and authentic storytelling. I believe in creating content that inspires, educates, and entertains. With over 5 years of experience working with global and Indian brands, I bring creativity, professionalism, and a genuine connection with my audience to every collaboration.',
                    'image'    => '',
                    'items'    => [
                        ['icon' => '🏆', 'label' => 'Years Creating',  'value' => '5+'],
                        ['icon' => '😊', 'label' => 'Brand Partners',  'value' => '150+'],
                        ['icon' => '✅', 'label' => 'Campaigns Done',  'value' => '400+'],
                    ],
                ]],
                ['key' => 'followers', 'label' => 'Follower Stats', 'icon' => 'fas fa-users', 'enabled' => true, 'data' => [
                    'heading'   => 'Social Media Presence',
                    'total'     => '2.4M+',
                    'instagram' => '1.2M',
                    'youtube'   => '840K',
                    'twitter'   => '320K',
                    'tiktok'    => '680K',
                ]],
                ['key' => 'services', 'label' => 'Collaboration Packages', 'icon' => 'fas fa-cogs', 'enabled' => true, 'data' => [
                    'heading'    => 'Collaboration Packages',
                    'subheading' => 'Tailored content solutions for brands of all sizes',
                    'text'       => 'From Instagram Reels to YouTube integrations, I create authentic content that resonates with my audience and drives real results for your brand.',
                    'cta_text'   => 'View Media Kit',
                    'cta_url'    => '/collaborations',
                    'items'      => [
                        ['icon' => '📸', 'name' => 'Instagram Post',     'description' => 'High-quality feed posts with professional photography, brand-aligned captions, and story highlights.', 'price' => '₹25,000 – ₹60,000'],
                        ['icon' => '🎬', 'name' => 'Instagram Reel',     'description' => 'Engaging short-form video content optimized for maximum reach and viral potential.', 'price' => '₹40,000 – ₹1,20,000'],
                        ['icon' => '▶️', 'name' => 'YouTube Integration','description' => 'Dedicated or integrated brand mentions in long-form YouTube videos with 840K+ subscribers.', 'price' => '₹80,000 – ₹2,50,000'],
                        ['icon' => '🎵', 'name' => 'TikTok Campaign',    'description' => 'Trending short videos designed for virality with authentic brand storytelling.', 'price' => '₹30,000 – ₹90,000'],
                        ['icon' => '📦', 'name' => 'Full Campaign',      'description' => 'Multi-platform campaign across Instagram, YouTube, and TikTok with strategy, content, and analytics.', 'price' => 'Custom Quote'],
                        ['icon' => '🎙️', 'name' => 'Brand Ambassador',  'description' => 'Long-term partnership with exclusive content rights, event appearances, and ongoing promotion.', 'price' => 'Custom Quote'],
                    ],
                ]],
                ['key' => 'testimonials', 'label' => 'Testimonials', 'icon' => 'fas fa-star', 'enabled' => true, 'data' => [
                    'heading' => 'What Brands Say',
                    'items'   => [
                        ['name' => 'Ananya Kapoor', 'role' => 'Marketing Head, Nykaa',    'text' => 'Priya delivered exceptional results for our summer campaign. Her authentic storytelling drove a 340% increase in our product page visits within 48 hours of her post going live.', 'rating' => 5],
                        ['name' => 'Rahul Sharma',  'role' => 'CEO, FitLife India',       'text' => 'Working with Priya was seamless from brief to delivery. She truly understood our brand voice and created content that felt natural and drove real conversions.', 'rating' => 5],
                        ['name' => 'Meera Patel',   'role' => 'Brand Manager, Mamaearth', 'text' => 'Priya\'s engagement rates are phenomenal. Our collaboration resulted in our best-performing influencer campaign ever — 6.8% engagement vs our 2.1% benchmark.', 'rating' => 5],
                    ],
                ]],
                ['key' => 'blog', 'label' => 'Latest Content', 'icon' => 'fas fa-blog', 'enabled' => true, 'data' => [
                    'heading'    => 'Latest from My Blog',
                    'subheading' => 'Beauty tips, travel diaries, wellness guides, and more',
                    'count'      => 3,
                    'items'      => [],
                ]],
                ['key' => 'shop', 'label' => 'Shop / Products', 'icon' => 'fas fa-shopping-bag', 'enabled' => true, 'data' => [
                    'heading'    => 'My Curated Shop',
                    'subheading' => 'Products I personally use and recommend',
                    'count'      => 6,
                ]],
                ['key' => 'contact', 'label' => 'Contact / CTA', 'icon' => 'fas fa-envelope', 'enabled' => true, 'data' => [
                    'heading'     => 'Let\'s Create Something Amazing',
                    'text'        => 'Interested in collaborating? I\'d love to hear about your brand and explore how we can create authentic content together.',
                    'button_text' => 'Start a Collaboration',
                    'button_url'  => '/collaborations',
                ]],
            ],
            'about' => [
                ['key' => 'hero', 'label' => 'Hero / Banner', 'icon' => 'fas fa-image', 'enabled' => true, 'data' => [
                    'heading'    => 'Priya Sharma',
                    'subheading' => 'Lifestyle Creator · Beauty & Wellness Influencer · Brand Storyteller',
                ]],
                ['key' => 'about', 'label' => 'About Snippet', 'icon' => 'fas fa-user', 'enabled' => true, 'data' => [
                    'heading' => 'My Story',
                    'text'    => 'I started my journey as a content creator in 2019 with a simple Instagram account sharing my skincare routine. What began as a personal passion quickly grew into a community of millions who share my love for beauty, wellness, and authentic living. Today, I\'m proud to be one of India\'s top lifestyle influencers, having collaborated with over 150 brands and created content that genuinely impacts people\'s lives.',
                ]],
            ],
            'collaborations' => [
                ['key' => 'hero', 'label' => 'Hero / Banner', 'icon' => 'fas fa-image', 'enabled' => true, 'data' => [
                    'heading'    => 'Brand Collaborations',
                    'subheading' => 'Let\'s create authentic content that resonates with 2.4M+ engaged followers',
                ]],
                ['key' => 'list', 'label' => 'Collaboration Types', 'icon' => 'fas fa-list', 'enabled' => true, 'data' => [
                    'heading' => 'What I Offer',
                    'items'   => [
                        ['icon' => '📸', 'name' => 'Instagram Post',      'description' => 'Feed posts with professional photography and authentic captions.', 'price' => '₹25,000 – ₹60,000'],
                        ['icon' => '🎬', 'name' => 'Instagram Reel',      'description' => 'Short-form video content optimized for maximum reach.', 'price' => '₹40,000 – ₹1,20,000'],
                        ['icon' => '▶️', 'name' => 'YouTube Integration', 'description' => 'Brand integration in long-form YouTube videos.', 'price' => '₹80,000 – ₹2,50,000'],
                        ['icon' => '🎵', 'name' => 'TikTok Campaign',     'description' => 'Trending short videos with authentic brand storytelling.', 'price' => '₹30,000 – ₹90,000'],
                        ['icon' => '📦', 'name' => 'Full Campaign',       'description' => 'Multi-platform campaign with strategy and analytics.', 'price' => 'Custom Quote'],
                        ['icon' => '🎙️', 'name' => 'Brand Ambassador',   'description' => 'Long-term partnership with exclusive content rights.', 'price' => 'Custom Quote'],
                    ],
                ]],
                ['key' => 'cta', 'label' => 'CTA', 'icon' => 'fas fa-envelope', 'enabled' => true, 'data' => [
                    'heading'     => 'Ready to Collaborate?',
                    'text'        => 'Send me your brand brief and I\'ll get back to you within 24 hours.',
                    'button_text' => 'Get in Touch',
                    'button_url'  => '/contact',
                ]],
            ],
        ];
    }

    private function priyaSettings(): array
    {
        return [
            'profile_title'     => 'Lifestyle Creator & Brand Collaborator',
            'profile_about'     => 'Mumbai-based lifestyle creator passionate about beauty, wellness, travel, and authentic storytelling. 5+ years creating content that inspires millions.',
            'profile_years'     => '5+',
            'profile_clients'   => '150+',
            'profile_projects'  => '400+',
            'profile_handle'    => '@priyasharma',
            'profile_niche'     => 'Lifestyle, Beauty & Wellness',
            'profile_followers_total' => '2.4M+',
            'profile_instagram_followers' => '1.2M',
            'profile_youtube_subscribers' => '840K',
            'profile_twitter_followers'   => '320K',
            'profile_tiktok_followers'    => '680K',
            'profile_collab_email'        => 'collabs@priyasharma.in',
            'profile_instagram_url'       => 'https://instagram.com/priyasharma',
            'profile_youtube_url'         => 'https://youtube.com/@priyasharma',
            'profile_twitter_url'         => 'https://twitter.com/priyasharma',
            'profile_tiktok_url'          => 'https://tiktok.com/@priyasharma',
        ];
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ARJUN MEHTA — Senior Advocate & Legal Consultant
    // ══════════════════════════════════════════════════════════════════════════
    private function arjunSections(): array
    {
        return [
            'home' => [
                ['key' => 'hero', 'label' => 'Hero / Banner', 'icon' => 'fas fa-image', 'enabled' => true, 'data' => [
                    'heading'    => 'Arjun Mehta',
                    'subheading' => 'Senior Advocate · Supreme Court of India · 18 Years of Legal Excellence',
                    'badge'      => '⚖️ Enrolled with Bar Council of India',
                    'cta_text'   => 'Book a Consultation',
                    'cta_url'    => '/contact',
                    'image'      => '',
                    'bg_color'   => '',
                ]],
                ['key' => 'stats', 'label' => 'Stats / Numbers', 'icon' => 'fas fa-chart-bar', 'enabled' => true, 'data' => [
                    'heading' => 'Track Record',
                    'items'   => [
                        ['icon' => '⚖️', 'label' => 'Years of Practice',  'value' => '18+'],
                        ['icon' => '🏆', 'label' => 'Cases Won',          'value' => '1,200+'],
                        ['icon' => '👥', 'label' => 'Clients Served',     'value' => '500+'],
                        ['icon' => '🏛️', 'label' => 'High Court Cases',  'value' => '300+'],
                        ['icon' => '📋', 'label' => 'Corporate Clients',  'value' => '80+'],
                        ['icon' => '⭐', 'label' => 'Success Rate',       'value' => '94%'],
                    ],
                ]],
                ['key' => 'about', 'label' => 'About Snippet', 'icon' => 'fas fa-user', 'enabled' => true, 'data' => [
                    'heading' => 'About Arjun Mehta',
                    'text'    => 'With over 18 years of distinguished legal practice at the Supreme Court and Delhi High Court, I bring deep expertise in corporate law, intellectual property, and civil litigation. My approach combines rigorous legal analysis with strategic thinking to deliver outcomes that protect my clients\' interests and rights.',
                    'image'   => '',
                    'items'   => [
                        ['icon' => '⚖️', 'label' => 'Years of Practice', 'value' => '18+'],
                        ['icon' => '🏆', 'label' => 'Cases Won',         'value' => '1,200+'],
                        ['icon' => '👥', 'label' => 'Clients Served',    'value' => '500+'],
                    ],
                ]],
                ['key' => 'services', 'label' => 'Practice Areas', 'icon' => 'fas fa-cogs', 'enabled' => true, 'data' => [
                    'heading'    => 'Practice Areas',
                    'subheading' => 'Comprehensive legal services across multiple domains',
                    'text'       => 'I provide expert legal counsel across a wide range of practice areas, ensuring your rights are protected and your interests are represented with the highest professional standards.',
                    'cta_text'   => 'View All Practice Areas',
                    'cta_url'    => '/practice-areas',
                    'items'      => [
                        ['icon' => '🏢', 'name' => 'Corporate & Commercial Law', 'description' => 'Company formation, mergers & acquisitions, shareholder disputes, corporate governance, and commercial contracts.', 'price' => ''],
                        ['icon' => '💡', 'name' => 'Intellectual Property',      'description' => 'Trademark registration, patent filing, copyright protection, IP litigation, and licensing agreements.', 'price' => ''],
                        ['icon' => '⚖️', 'name' => 'Civil Litigation',           'description' => 'Property disputes, contract enforcement, injunctions, and appellate advocacy before High Courts and Supreme Court.', 'price' => ''],
                        ['icon' => '👨‍👩‍👧', 'name' => 'Family Law',             'description' => 'Divorce proceedings, child custody, matrimonial property disputes, and domestic violence cases.', 'price' => ''],
                        ['icon' => '🏗️', 'name' => 'Real Estate Law',           'description' => 'Property transactions, title disputes, RERA matters, builder-buyer disputes, and land acquisition.', 'price' => ''],
                        ['icon' => '💼', 'name' => 'Employment Law',             'description' => 'Wrongful termination, employment contracts, workplace disputes, and labour tribunal representation.', 'price' => ''],
                    ],
                ]],
                ['key' => 'testimonials', 'label' => 'Testimonials', 'icon' => 'fas fa-star', 'enabled' => true, 'data' => [
                    'heading' => 'Client Testimonials',
                    'items'   => [
                        ['name' => 'Vikram Nair',    'role' => 'CEO, TechVentures India',    'text' => 'Arjun\'s expertise in corporate law saved our company from a potentially devastating merger dispute. His strategic approach and deep knowledge of commercial law is unmatched.', 'rating' => 5],
                        ['name' => 'Sunita Reddy',   'role' => 'Director, Reddy Properties', 'text' => 'I had a complex property dispute that had been going on for 3 years. Arjun resolved it in 8 months with a favorable outcome. His dedication and expertise are exceptional.', 'rating' => 5],
                        ['name' => 'Pradeep Kumar',  'role' => 'Entrepreneur',               'text' => 'Arjun handled our startup\'s IP portfolio with great professionalism. He secured our trademarks and patents efficiently, giving us the legal foundation to grow confidently.', 'rating' => 5],
                    ],
                ]],
                ['key' => 'blog', 'label' => 'Legal Insights', 'icon' => 'fas fa-blog', 'enabled' => true, 'data' => [
                    'heading'    => 'Legal Insights',
                    'subheading' => 'Expert analysis on recent legal developments and case law',
                    'count'      => 3,
                    'items'      => [],
                ]],
                ['key' => 'contact', 'label' => 'Contact / CTA', 'icon' => 'fas fa-envelope', 'enabled' => true, 'data' => [
                    'heading'     => 'Schedule a Legal Consultation',
                    'text'        => 'Every legal matter deserves expert attention. Contact my chambers to schedule a confidential consultation and discuss your legal needs.',
                    'button_text' => 'Book Consultation',
                    'button_url'  => '/contact',
                ]],
            ],
            'practice-areas' => [
                ['key' => 'hero', 'label' => 'Hero / Banner', 'icon' => 'fas fa-image', 'enabled' => true, 'data' => [
                    'heading'    => 'Practice Areas',
                    'subheading' => 'Comprehensive legal expertise across corporate, civil, IP, family, and real estate law',
                ]],
                ['key' => 'list', 'label' => 'Practice Areas List', 'icon' => 'fas fa-list', 'enabled' => true, 'data' => [
                    'heading' => 'Areas of Legal Practice',
                    'items'   => [
                        ['icon' => '🏢', 'name' => 'Corporate & Commercial Law', 'description' => 'Company formation, M&A, shareholder disputes, and commercial contracts.', 'price' => ''],
                        ['icon' => '💡', 'name' => 'Intellectual Property',      'description' => 'Trademark, patent, copyright protection and IP litigation.', 'price' => ''],
                        ['icon' => '⚖️', 'name' => 'Civil Litigation',           'description' => 'Property disputes, contract enforcement, and appellate advocacy.', 'price' => ''],
                        ['icon' => '👨‍👩‍👧', 'name' => 'Family Law',             'description' => 'Divorce, child custody, matrimonial property disputes.', 'price' => ''],
                        ['icon' => '🏗️', 'name' => 'Real Estate Law',           'description' => 'Property transactions, RERA matters, and land acquisition.', 'price' => ''],
                        ['icon' => '💼', 'name' => 'Employment Law',             'description' => 'Wrongful termination, employment contracts, and labour disputes.', 'price' => ''],
                    ],
                ]],
                ['key' => 'cta', 'label' => 'CTA', 'icon' => 'fas fa-envelope', 'enabled' => true, 'data' => [
                    'heading'     => 'Need Legal Advice?',
                    'text'        => 'Book a confidential consultation to discuss your legal matter.',
                    'button_text' => 'Book Consultation',
                    'button_url'  => '/contact',
                ]],
            ],
            'case-studies' => [
                ['key' => 'hero', 'label' => 'Hero / Banner', 'icon' => 'fas fa-image', 'enabled' => true, 'data' => [
                    'heading'    => 'Notable Cases',
                    'subheading' => 'A selection of landmark cases and successful outcomes',
                ]],
                ['key' => 'projects', 'label' => 'Case Studies', 'icon' => 'fas fa-briefcase', 'enabled' => true, 'data' => [
                    'heading' => 'Selected Case Studies',
                    'items'   => [
                        ['icon' => '🏢', 'title' => 'TechVentures vs. Competitor — IP Dispute', 'category' => 'Intellectual Property', 'description' => 'Successfully defended a tech startup against a trademark infringement claim, securing full dismissal and ₹50L in damages.', 'link' => ''],
                        ['icon' => '🏗️', 'title' => 'Reddy Properties — Land Title Dispute',   'category' => 'Real Estate Law',       'description' => 'Resolved a 15-year-old land title dispute involving ₹12 crore property through strategic litigation and negotiation.', 'link' => ''],
                        ['icon' => '💼', 'title' => 'Employee Rights — Wrongful Termination',   'category' => 'Employment Law',        'description' => 'Secured ₹85L compensation for a senior executive wrongfully terminated without cause or notice period.', 'link' => ''],
                    ],
                ]],
            ],
        ];
    }

    private function arjunSettings(): array
    {
        return [
            'profile_title'       => 'Senior Advocate, Supreme Court of India',
            'profile_about'       => '18+ years of distinguished legal practice at the Supreme Court and Delhi High Court. Expert in corporate law, intellectual property, civil litigation, and family law.',
            'profile_years'       => '18+',
            'profile_clients'     => '500+',
            'profile_projects'    => '1,200+',
            'profile_enrollment_no' => 'D/1234/2006',
            'profile_bar_number'  => 'BAR-SC-2006-4521',
            'profile_court'       => 'Supreme Court of India',
            'profile_chamber'     => 'Chamber No. 412, Supreme Court Annexe, New Delhi',
            'profile_phone'       => '+91 98765 43210',
            'profile_consultation_link' => '/contact',
        ];
    }

    // ══════════════════════════════════════════════════════════════════════════
    // SOUSHANTH — Serial Entrepreneur & Startup Founder
    // ══════════════════════════════════════════════════════════════════════════
    private function soushanthSections(): array
    {
        return [
            'home' => [
                ['key' => 'hero', 'label' => 'Hero / Banner', 'icon' => 'fas fa-image', 'enabled' => true, 'data' => [
                    'heading'    => 'Soushanth',
                    'subheading' => 'Serial Entrepreneur · 4 Startups · $12M+ Raised · Building the Future of Tech',
                    'badge'      => '🚀 Forbes 30 Under 30 — 2023',
                    'cta_text'   => 'Explore My Ventures',
                    'cta_url'    => '/ventures',
                    'image'      => '',
                    'bg_color'   => '',
                ]],
                ['key' => 'stats', 'label' => 'Stats / Numbers', 'icon' => 'fas fa-chart-bar', 'enabled' => true, 'data' => [
                    'heading' => 'By the Numbers',
                    'items'   => [
                        ['icon' => '🚀', 'label' => 'Ventures Founded',  'value' => '4'],
                        ['icon' => '💰', 'label' => 'Total Funding',     'value' => '$12M+'],
                        ['icon' => '👥', 'label' => 'Team Members',      'value' => '120+'],
                        ['icon' => '🌍', 'label' => 'Countries Active',  'value' => '8'],
                        ['icon' => '📈', 'label' => 'Revenue Generated', 'value' => '$4.2M'],
                        ['icon' => '🏆', 'label' => 'Awards Won',        'value' => '12'],
                    ],
                ]],
                ['key' => 'about', 'label' => 'About Snippet', 'icon' => 'fas fa-user', 'enabled' => true, 'data' => [
                    'heading' => 'About Soushanth',
                    'text'    => 'I\'m a Chennai-based serial entrepreneur who has founded 4 technology ventures across fintech, edtech, and SaaS. Named Forbes 30 Under 30 in 2023, I\'m passionate about building scalable solutions that solve real-world problems. My ventures have collectively raised $12M+ in funding and serve customers across 8 countries.',
                    'image'   => '',
                    'items'   => [
                        ['icon' => '🚀', 'label' => 'Ventures Founded',  'value' => '4'],
                        ['icon' => '💰', 'label' => 'Funding Raised',    'value' => '$12M+'],
                        ['icon' => '👥', 'label' => 'Team Members',      'value' => '120+'],
                    ],
                ]],
                ['key' => 'ventures', 'label' => 'Ventures / Portfolio', 'icon' => 'fas fa-rocket', 'enabled' => true, 'data' => [
                    'heading'    => 'My Ventures',
                    'subheading' => 'Building technology companies that scale globally',
                    'items'      => [
                        ['icon' => '💳', 'name' => 'FinEdge',     'description' => 'AI-powered personal finance platform helping 500K+ users manage investments, budgets, and financial goals.', 'status' => 'Active · Series A', 'link' => ''],
                        ['icon' => '📚', 'name' => 'LearnSpark',  'description' => 'Adaptive learning platform for K-12 students with AI-personalized curriculum. 200K+ students across India.', 'status' => 'Active · Seed+',   'link' => ''],
                        ['icon' => '🤖', 'name' => 'AutoDesk AI', 'description' => 'No-code AI automation platform for SMEs. Acquired by TechCorp in 2022 for $3.2M.', 'status' => 'Acquired · 2022',  'link' => ''],
                        ['icon' => '🏥', 'name' => 'HealthBridge','description' => 'Telemedicine and health records platform connecting patients with doctors. 50K+ consultations monthly.', 'status' => 'Active · Pre-Series A', 'link' => ''],
                    ],
                ]],
                ['key' => 'services', 'label' => 'Advisory Services', 'icon' => 'fas fa-cogs', 'enabled' => true, 'data' => [
                    'heading'    => 'Advisory & Consulting',
                    'subheading' => 'Helping startups scale from idea to Series A and beyond',
                    'text'       => 'Drawing from my experience founding and scaling 4 ventures, I offer strategic advisory services to early-stage startups and growth-stage companies.',
                    'cta_text'   => 'Work with Me',
                    'cta_url'    => '/contact',
                    'items'      => [
                        ['icon' => '🎯', 'name' => 'Startup Strategy',     'description' => 'Business model validation, go-to-market strategy, and product-market fit assessment for early-stage startups.', 'price' => '₹50,000 / session'],
                        ['icon' => '💰', 'name' => 'Fundraising Advisory', 'description' => 'Pitch deck review, investor introductions, term sheet negotiation, and due diligence preparation.', 'price' => '₹1,00,000 / month'],
                        ['icon' => '📈', 'name' => 'Growth Consulting',    'description' => 'Customer acquisition strategy, unit economics optimization, and scaling playbooks for Series A+ companies.', 'price' => 'Custom Quote'],
                        ['icon' => '🤝', 'name' => 'Board Advisory',       'description' => 'Ongoing board advisory role with monthly strategy sessions, network introductions, and crisis management.', 'price' => 'Equity + Retainer'],
                    ],
                ]],
                ['key' => 'testimonials', 'label' => 'Testimonials', 'icon' => 'fas fa-star', 'enabled' => true, 'data' => [
                    'heading' => 'What Founders Say',
                    'items'   => [
                        ['name' => 'Karthik Rajan',  'role' => 'Founder, PaySwift',        'text' => 'Soushanth\'s fundraising advice was invaluable. He helped us refine our pitch and made key introductions that led to our $2M seed round closing in 6 weeks.', 'rating' => 5],
                        ['name' => 'Divya Krishnan', 'role' => 'CEO, EduTech Startup',     'text' => 'Working with Soushanth transformed our go-to-market strategy. His hands-on experience building LearnSpark gave us a clear roadmap to our first 10,000 users.', 'rating' => 5],
                        ['name' => 'Arun Selvam',    'role' => 'CTO, HealthTech Ventures', 'text' => 'Soushanth\'s technical and business acumen is rare. He helped us identify the right product pivots that doubled our user retention in 3 months.', 'rating' => 5],
                    ],
                ]],
                ['key' => 'blog', 'label' => 'Latest Insights', 'icon' => 'fas fa-blog', 'enabled' => true, 'data' => [
                    'heading'    => 'Startup Insights',
                    'subheading' => 'Lessons from building 4 companies and raising $12M+',
                    'count'      => 3,
                    'items'      => [],
                ]],
                ['key' => 'jobs', 'label' => 'Open Positions', 'icon' => 'fas fa-briefcase', 'enabled' => true, 'data' => [
                    'heading'    => 'Join My Ventures',
                    'subheading' => 'We\'re hiring across all our portfolio companies',
                    'count'      => 5,
                    'items'      => [],
                ]],
                ['key' => 'contact', 'label' => 'Contact / CTA', 'icon' => 'fas fa-envelope', 'enabled' => true, 'data' => [
                    'heading'     => 'Let\'s Build Something Great',
                    'text'        => 'Whether you\'re a founder looking for advisory, an investor exploring opportunities, or a potential partner — I\'d love to connect.',
                    'button_text' => 'Get in Touch',
                    'button_url'  => '/contact',
                ]],
            ],
            'ventures' => [
                ['key' => 'hero', 'label' => 'Hero / Banner', 'icon' => 'fas fa-image', 'enabled' => true, 'data' => [
                    'heading'    => 'My Ventures',
                    'subheading' => 'Building technology companies that solve real problems and scale globally',
                ]],
                ['key' => 'projects', 'label' => 'Ventures Portfolio', 'icon' => 'fas fa-rocket', 'enabled' => true, 'data' => [
                    'heading' => 'Portfolio Companies',
                    'items'   => [
                        ['icon' => '💳', 'title' => 'FinEdge',     'category' => 'Fintech · Series A',       'description' => 'AI-powered personal finance platform. 500K+ users, $5.5M raised.', 'link' => ''],
                        ['icon' => '📚', 'title' => 'LearnSpark',  'category' => 'EdTech · Seed+',           'description' => 'Adaptive learning for K-12. 200K+ students, $2.1M raised.', 'link' => ''],
                        ['icon' => '🤖', 'title' => 'AutoDesk AI', 'category' => 'SaaS · Acquired 2022',     'description' => 'No-code AI automation for SMEs. Acquired for $3.2M.', 'link' => ''],
                        ['icon' => '🏥', 'title' => 'HealthBridge','category' => 'HealthTech · Pre-Series A', 'description' => 'Telemedicine platform. 50K+ monthly consultations.', 'link' => ''],
                    ],
                ]],
                ['key' => 'cta', 'label' => 'CTA', 'icon' => 'fas fa-envelope', 'enabled' => true, 'data' => [
                    'heading'     => 'Interested in Investing?',
                    'text'        => 'I\'m always open to conversations with investors and strategic partners.',
                    'button_text' => 'Let\'s Talk',
                    'button_url'  => '/contact',
                ]],
            ],
            'solutions' => [
                ['key' => 'hero', 'label' => 'Hero / Banner', 'icon' => 'fas fa-image', 'enabled' => true, 'data' => [
                    'heading'    => 'Advisory Services',
                    'subheading' => 'Strategic guidance for startups and growth-stage companies',
                ]],
                ['key' => 'list', 'label' => 'Services List', 'icon' => 'fas fa-list', 'enabled' => true, 'data' => [
                    'heading' => 'How I Can Help',
                    'items'   => [
                        ['icon' => '🎯', 'name' => 'Startup Strategy',     'description' => 'Business model validation, GTM strategy, and product-market fit.', 'price' => '₹50,000 / session'],
                        ['icon' => '💰', 'name' => 'Fundraising Advisory', 'description' => 'Pitch deck, investor introductions, and term sheet negotiation.', 'price' => '₹1,00,000 / month'],
                        ['icon' => '📈', 'name' => 'Growth Consulting',    'description' => 'Customer acquisition, unit economics, and scaling playbooks.', 'price' => 'Custom Quote'],
                        ['icon' => '🤝', 'name' => 'Board Advisory',       'description' => 'Ongoing board role with monthly strategy sessions.', 'price' => 'Equity + Retainer'],
                    ],
                ]],
                ['key' => 'cta', 'label' => 'CTA', 'icon' => 'fas fa-envelope', 'enabled' => true, 'data' => [
                    'heading'     => 'Ready to Scale?',
                    'text'        => 'Book a strategy session and let\'s discuss how I can help your startup grow.',
                    'button_text' => 'Book a Session',
                    'button_url'  => '/contact',
                ]],
            ],
        ];
    }

    private function soushanthSettings(): array
    {
        return [
            'profile_title'         => 'Serial Entrepreneur & Startup Advisor',
            'profile_about'         => 'Chennai-based serial entrepreneur. Founded 4 tech ventures across fintech, edtech, and SaaS. Forbes 30 Under 30 (2023). $12M+ raised. 120+ team members across 8 countries.',
            'profile_years'         => '8+',
            'profile_clients'       => '120+',
            'profile_projects'      => '4',
            'profile_ventures_built' => '4',
            'profile_funding_raised' => '$12M+',
            'profile_team_size'     => '120+',
            'profile_industries'    => 'Fintech, EdTech, HealthTech, SaaS',
            'profile_linkedin'      => 'https://linkedin.com/in/soushanth',
            'profile_pitch_link'    => '/contact',
        ];
    }

    public function down(): void
    {
        // No rollback — this is a data seeding migration
    }
};
