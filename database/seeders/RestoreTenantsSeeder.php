<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RestoreTenantsSeeder extends Seeder
{
    public function run(): void
    {
        // Get tenant IDs
        $gopi  = DB::table('users')->where('email', 'gopi@outlook.in')->first();
        $priya = DB::table('users')->where('email', 'priya@xenoraa.com')->first();
        $arjun = DB::table('users')->where('email', 'arjun@xenoraa.com')->first();

        if ($gopi)  $this->restoreGopi($gopi->id);
        if ($priya) $this->bootstrapTenant($priya->id, 'priya', 'influencer');
        if ($arjun) $this->bootstrapTenant($arjun->id, 'arjun', 'advocate');
    }

    // ─── Restore Gopi's Full Content ────────────────────────────────────────

    private function restoreGopi(int $tid): void
    {
        $this->command->info("Restoring Gopi (user_id={$tid})...");

        // ── Site Settings ──────────────────────────────────────────────────
        $settings = [
            'site_name'           => 'Gopi K',
            'site_tagline'        => 'IT Professional & Solutions Architect',
            'site_description'    => 'Helping businesses build scalable digital solutions with 10+ years of IT expertise.',
            'profile_template'    => 'consultant',
            'chatbot_enabled'     => '1',
            'ai_assistant_name'   => 'Gopi AI',
            'ai_assistant_tagline'=> 'Ask me about IT solutions & consulting',
            'color_accent'        => '#6366f1',
            'color_bg'            => '#0a0a0a',
            'profile_title'       => 'IT Professional & Solutions Architect',
            'profile_about'       => 'I am Gopi K, an IT professional with over 10 years of experience in software architecture, cloud infrastructure, and digital transformation. I have worked with startups and enterprises across India and Southeast Asia, helping them build scalable, secure, and cost-efficient technology solutions. My expertise spans full-stack development, DevOps, cloud migration, and AI integration.',
            'profile_years'       => '10',
            'profile_clients'     => '50',
            'profile_projects'    => '120',
            'profile_revenue'     => '₹5Cr+',
            'profile_booking_link'=> 'https://calendly.com/gopik',
            'profile_expertise'   => json_encode(['Cloud Architecture', 'DevOps', 'AI/ML', 'Full Stack', 'Digital Transformation', 'SaaS']),
            'profile_services'    => json_encode([
                ['icon' => '☁️', 'title' => 'Cloud Architecture', 'text' => 'AWS, Azure & GCP infrastructure design and migration'],
                ['icon' => '🤖', 'title' => 'AI Integration', 'text' => 'LLM integration, chatbots, and intelligent automation'],
                ['icon' => '⚙️', 'title' => 'DevOps & CI/CD', 'text' => 'Docker, Kubernetes, GitHub Actions pipelines'],
                ['icon' => '💻', 'title' => 'Full Stack Dev', 'text' => 'Laravel, React, Vue — end-to-end web applications'],
                ['icon' => '🔒', 'title' => 'Security Audit', 'text' => 'Penetration testing, VAPT, and compliance review'],
                ['icon' => '📊', 'title' => 'Tech Consulting', 'text' => 'CTO-as-a-Service and technology roadmap planning'],
            ]),
        ];
        foreach ($settings as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['user_id' => $tid, 'key' => $key],
                ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        // ── Portfolio Experiences ──────────────────────────────────────────
        if (DB::table('portfolio_experiences')->where('user_id', $tid)->count() === 0) {
            $experiences = [
                [
                    'user_id'     => $tid,
                    'company'     => 'Xenoraa Technologies',
                    'position'    => 'Founder & CTO',
                    'description' => 'Founded Xenoraa — a multi-tenant SaaS platform for professionals. Built the entire technology stack from scratch including multi-tenancy, AI chatbot, CRM, e-commerce, and job portal modules.',
                    'start_date'  => '2022-01-01',
                    'end_date'    => null,
                    'is_current'  => 1,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'user_id'     => $tid,
                    'company'     => 'TechCorp India Pvt Ltd',
                    'position'    => 'Senior Solutions Architect',
                    'description' => 'Led cloud migration projects for 15+ enterprise clients. Designed microservices architecture on AWS, reducing infrastructure costs by 40%. Managed a team of 12 engineers.',
                    'start_date'  => '2018-06-01',
                    'end_date'    => '2021-12-31',
                    'is_current'  => 0,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'user_id'     => $tid,
                    'company'     => 'Infosys BPM',
                    'position'    => 'Software Engineer',
                    'description' => 'Developed enterprise-grade Java applications for banking and insurance clients. Implemented RESTful APIs, database optimization, and automated testing frameworks.',
                    'start_date'  => '2014-08-01',
                    'end_date'    => '2018-05-31',
                    'is_current'  => 0,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ];
            DB::table('portfolio_experiences')->insert($experiences);
        }

        // ── Social Links ───────────────────────────────────────────────────
        if (DB::table('social_links')->where('user_id', $tid)->count() === 0) {
            $socials = [
                ['user_id' => $tid, 'platform' => 'LinkedIn',  'url' => 'https://linkedin.com/in/gopik',  'icon' => 'fab fa-linkedin', 'is_active' => 1, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'platform' => 'GitHub',    'url' => 'https://github.com/gopik',      'icon' => 'fab fa-github',   'is_active' => 1, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'platform' => 'Twitter',   'url' => 'https://twitter.com/gopik',     'icon' => 'fab fa-twitter',  'is_active' => 1, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'platform' => 'YouTube',   'url' => 'https://youtube.com/@gopik',    'icon' => 'fab fa-youtube',  'is_active' => 1, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ];
            DB::table('social_links')->insert($socials);
        }

        // ── Blog Posts ─────────────────────────────────────────────────────
        $existingBlogCount = DB::table('blog_posts')->where('user_id', $tid)->count();
        if ($existingBlogCount < 3) {
            $catId = DB::table('blog_categories')->where('slug', 'technology')->value('id')
                ?? DB::table('blog_categories')->insertGetId(['name' => 'Technology', 'slug' => 'technology', 'created_at' => now(), 'updated_at' => now()]);

            $posts = [
                [
                    'user_id'      => $tid,
                    'category_id'  => $catId,
                    'title'        => 'Building a Multi-Tenant SaaS Platform with Laravel',
                    'slug'         => 'building-multi-tenant-saas-laravel',
                    'excerpt'      => 'A deep dive into how I architected Xenoraa — a multi-tenant SaaS platform using Laravel, with tenant isolation, custom domains, and modular features.',
                    'content'      => '<h2>Introduction</h2><p>Building a multi-tenant SaaS platform is one of the most challenging and rewarding engineering projects you can undertake. In this post, I\'ll walk you through the architectural decisions I made when building Xenoraa.</p><h2>Tenant Isolation Strategy</h2><p>The core challenge of multi-tenancy is ensuring that each tenant\'s data is completely isolated. We use a shared database with tenant_id columns approach, which gives us the flexibility to scale while keeping operational complexity manageable.</p><h2>Custom Domain Routing</h2><p>One of the most impressive features is the ability for each tenant to map their own domain (like gopi.blog) to their Xenoraa site. This is achieved through nginx server_name wildcards and Laravel middleware that resolves the tenant from the HTTP host header.</p><h2>Conclusion</h2><p>Multi-tenancy is complex but incredibly powerful. The key is to plan your data isolation strategy from day one and build it into every query.</p>',
                    'status'       => 'published',
                    'views_count'  => 1240,
                    'published_at' => Carbon::now()->subDays(30),
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ],
                [
                    'user_id'      => $tid,
                    'category_id'  => $catId,
                    'title'        => 'AWS vs Azure vs GCP: Which Cloud is Right for Your Startup?',
                    'slug'         => 'aws-azure-gcp-comparison-startups',
                    'excerpt'      => 'A practical comparison of the three major cloud providers from the perspective of a startup CTO who has worked with all three.',
                    'content'      => '<h2>The Cloud Dilemma</h2><p>Every startup faces the same question: which cloud provider should we use? After working with AWS, Azure, and GCP across 50+ projects, here\'s my honest assessment.</p><h2>AWS — The Safe Choice</h2><p>AWS has the largest ecosystem, the most mature services, and the biggest community. If you\'re unsure, start with AWS. The learning curve is steep but the resources are abundant.</p><h2>Azure — The Enterprise Choice</h2><p>If your clients are Microsoft shops or you\'re building enterprise software, Azure\'s integration with Active Directory and Office 365 is unmatched.</p><h2>GCP — The Data Choice</h2><p>Google Cloud excels in data analytics, machine learning, and Kubernetes. If your product is data-heavy or AI-first, GCP\'s BigQuery and Vertex AI are world-class.</p><h2>My Recommendation</h2><p>For most Indian startups: start with AWS, migrate to multi-cloud as you scale. Keep your infrastructure as code from day one.</p>',
                    'status'       => 'published',
                    'views_count'  => 890,
                    'published_at' => Carbon::now()->subDays(15),
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ],
            ];
            DB::table('blog_posts')->insert($posts);
        }

        // ── Jobs ───────────────────────────────────────────────────────────
        if (DB::table('jobs')->where('user_id', $tid)->count() === 0) {
            $jobs = [
                [
                    'user_id'      => $tid,
                    'title'        => 'Senior Laravel Developer',
                    'slug'         => 'senior-laravel-developer',
                    'company'      => 'Xenoraa Technologies',
                    'location'     => 'Chennai / Remote',
                    'type'         => 'full-time',
                    'description'  => '<p>We are looking for a Senior Laravel Developer to join the Xenoraa core team. You will work on our multi-tenant SaaS platform, building new features and maintaining existing modules.</p><h3>Requirements</h3><ul><li>5+ years of Laravel experience</li><li>Strong MySQL and Redis knowledge</li><li>Experience with multi-tenancy patterns</li><li>Familiarity with Vue.js or React</li></ul>',
                    'requirements' => '5+ years Laravel, MySQL, Redis, REST APIs',
                    'salary_min'   => 800000,
                    'salary_max'   => 1500000,
                    'status'       => 'active',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ],
                [
                    'user_id'      => $tid,
                    'title'        => 'DevOps Engineer',
                    'slug'         => 'devops-engineer',
                    'company'      => 'Xenoraa Technologies',
                    'location'     => 'Remote',
                    'type'         => 'full-time',
                    'description'  => '<p>Join our infrastructure team as a DevOps Engineer. You will manage our AWS infrastructure, CI/CD pipelines, and ensure 99.9% uptime for our SaaS platform.</p><h3>Requirements</h3><ul><li>3+ years DevOps experience</li><li>AWS certified preferred</li><li>Docker and Kubernetes expertise</li><li>GitHub Actions or Jenkins experience</li></ul>',
                    'requirements' => '3+ years DevOps, AWS, Docker, Kubernetes',
                    'salary_min'   => 700000,
                    'salary_max'   => 1200000,
                    'status'       => 'active',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ],
            ];
            DB::table('jobs')->insert($jobs);
        }

        // ── Chatbot Training ───────────────────────────────────────────────
        if (DB::table('chatbot_training')->where('user_id', $tid)->count() === 0) {
            $training = [
                ['user_id' => $tid, 'question' => 'What services do you offer?', 'answer' => 'I offer Cloud Architecture, AI Integration, DevOps & CI/CD, Full Stack Development, Security Audits, and Tech Consulting (CTO-as-a-Service). Each service is tailored to your business needs.', 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'question' => 'How can I book a consultation?', 'answer' => 'You can book a free 30-minute strategy call via my Calendly link at calendly.com/gopik. I typically respond within 24 hours.', 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'question' => 'What is your experience?', 'answer' => 'I have 10+ years of IT experience, having worked with 50+ clients across 120+ projects. I\'ve worked at Infosys, TechCorp India, and now run Xenoraa Technologies as Founder & CTO.', 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'question' => 'What is Xenoraa?', 'answer' => 'Xenoraa is a multi-tenant SaaS platform I built that allows professionals (IT, legal, healthcare, influencers) to create their own branded website with AI chatbot, CRM, blog, jobs, and e-commerce modules.', 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'question' => 'Do you work with startups?', 'answer' => 'Yes! I love working with early-stage startups. I offer CTO-as-a-Service packages where I help you build your MVP, choose the right tech stack, and scale your infrastructure as you grow.', 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'question' => 'What cloud platforms do you work with?', 'answer' => 'I work with all three major cloud providers: AWS (my primary), Microsoft Azure, and Google Cloud Platform. I\'m AWS certified and have delivered 30+ cloud migration projects.', 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'question' => 'What is your pricing?', 'answer' => 'Pricing varies by project scope. Consulting starts at ₹5,000/hour. Fixed-price projects start at ₹50,000. CTO-as-a-Service retainers start at ₹75,000/month. Book a call to discuss your specific needs.', 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'question' => 'Can you help with AI integration?', 'answer' => 'Absolutely! AI integration is one of my core specialties. I can integrate OpenAI GPT, Google Gemini, or custom LLMs into your existing applications, build AI chatbots, and implement intelligent automation workflows.', 'created_at' => now(), 'updated_at' => now()],
            ];
            DB::table('chatbot_training')->insert($training);
        }

        // ── Default Menu ───────────────────────────────────────────────────
        if (DB::table('site_menus')->where('user_id', $tid)->count() === 0) {
            $menuItems = [
                ['user_id' => $tid, 'label' => 'Home',     'url' => '/',        'target' => '_self', 'sort_order' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'label' => 'About',    'url' => '/about',   'target' => '_self', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'label' => 'Blog',     'url' => '/blog',    'target' => '_self', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'label' => 'Jobs',     'url' => '/jobs',    'target' => '_self', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $tid, 'label' => 'Contact',  'url' => '/#contact','target' => '_self', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ];
            DB::table('site_menus')->insert($menuItems);
        }

        $this->command->info("Gopi restored successfully.");
    }

    // ─── Bootstrap Default Layout for Empty Tenants ─────────────────────────

    private function bootstrapTenant(int $tid, string $username, string $template): void
    {
        $this->command->info("Bootstrapping {$username} (user_id={$tid}, template={$template})...");

        $user = DB::table('users')->find($tid);
        $name = $user->name ?? ucfirst($username);

        $templateDefaults = [
            'influencer' => [
                'title'        => 'Lifestyle Influencer & Content Creator',
                'about'        => "Hi! I'm {$name}, a lifestyle and fashion content creator based in Mumbai. I create authentic content about fashion, travel, beauty, and everyday life. With a growing community of engaged followers, I partner with brands that align with my values and aesthetic.",
                'expertise'    => ['Fashion', 'Travel', 'Beauty', 'Lifestyle', 'Content Creation', 'Brand Partnerships'],
                'services'     => [
                    ['icon' => '📸', 'title' => 'Instagram Posts', 'text' => 'High-quality lifestyle and fashion content'],
                    ['icon' => '🎬', 'title' => 'Reels & Videos', 'text' => 'Engaging short-form video content'],
                    ['icon' => '✈️', 'title' => 'Travel Content', 'text' => 'Destination reviews and travel guides'],
                    ['icon' => '💄', 'title' => 'Beauty Reviews', 'text' => 'Honest product reviews and tutorials'],
                    ['icon' => '🤝', 'title' => 'Brand Collaborations', 'text' => 'Long-term brand ambassador partnerships'],
                    ['icon' => '📧', 'title' => 'Newsletter', 'text' => 'Weekly lifestyle and trend updates'],
                ],
                'platforms'    => ['Instagram', 'YouTube', 'Pinterest', 'TikTok'],
                'ai_name'      => $name . ' AI',
                'ai_tagline'   => 'Ask about collaborations & content',
                'color_accent' => '#f43f5e',
                'color_bg'     => '#0f0f0f',
                'blog_cat'     => 'Lifestyle',
                'blog_cat_slug'=> 'lifestyle',
                'blog_posts'   => [
                    [
                        'title'   => '10 Fashion Essentials Every Woman Needs in 2025',
                        'slug'    => "fashion-essentials-2025-{$username}",
                        'excerpt' => 'From timeless classics to modern must-haves, here are the 10 pieces that should be in every woman\'s wardrobe this year.',
                        'content' => '<h2>Building a Capsule Wardrobe</h2><p>A capsule wardrobe is the foundation of effortless style. Instead of having a closet full of clothes with nothing to wear, you curate a small collection of versatile, high-quality pieces that work together seamlessly.</p><h2>The Essential 10</h2><ol><li><strong>White Button-Down Shirt</strong> — The ultimate versatile piece</li><li><strong>Well-Fitted Jeans</strong> — Dark wash for day-to-night</li><li><strong>Little Black Dress</strong> — For every occasion</li><li><strong>Blazer</strong> — Instantly elevates any outfit</li><li><strong>Trench Coat</strong> — Timeless outerwear</li><li><strong>Silk Blouse</strong> — Effortlessly chic</li><li><strong>Tailored Trousers</strong> — Professional and polished</li><li><strong>Cashmere Sweater</strong> — Luxurious comfort</li><li><strong>White Sneakers</strong> — The modern essential</li><li><strong>Leather Bag</strong> — Investment piece that lasts</li></ol><p>Start with these 10 pieces and build from there. Quality over quantity is always the rule.</p>',
                        'views'   => 2340,
                    ],
                    [
                        'title'   => 'My Bali Travel Diary: Hidden Gems & Must-Visit Spots',
                        'slug'    => "bali-travel-diary-{$username}",
                        'excerpt' => 'Bali is more than just Kuta Beach. Here\'s my honest guide to the hidden gems, best restaurants, and Instagram-worthy spots you shouldn\'t miss.',
                        'content' => '<h2>Why Bali Never Gets Old</h2><p>I\'ve been to Bali three times now and every trip reveals something new. This time, I went off the beaten path and discovered some truly magical places that most tourists never see.</p><h2>Hidden Gems</h2><p><strong>Tibumana Waterfall</strong> — Less crowded than Tegenungan, absolutely stunning. Go early morning for the best light and fewer people.</p><p><strong>Sidemen Valley</strong> — The most beautiful rice terraces in Bali, without the crowds of Tegalalang. Stay at a local homestay for the full experience.</p><h2>Food Highlights</h2><p>Skip the tourist restaurants and head to <strong>Warung Babi Guling Ibu Oka</strong> in Ubud for the best suckling pig you\'ll ever have. For a splurge, <strong>Locavore</strong> is world-class.</p><h2>My Tips</h2><ul><li>Rent a scooter — it\'s the best way to explore</li><li>Visit temples early morning or late afternoon</li><li>Always carry a sarong for temple visits</li><li>Learn a few words of Bahasa — locals love it</li></ul>',
                        'views'   => 1890,
                    ],
                ],
                'jobs' => [
                    [
                        'title'       => 'Brand Collaboration Manager',
                        'slug'        => "brand-collab-manager-{$username}",
                        'company'     => $name . ' Media',
                        'location'    => 'Mumbai / Remote',
                        'type'        => 'full-time',
                        'description' => '<p>Looking for a Brand Collaboration Manager to handle partnership enquiries, negotiate deals, and manage brand relationships for ' . $name . '\'s growing influencer business.</p><h3>Responsibilities</h3><ul><li>Manage inbound brand collaboration requests</li><li>Negotiate partnership terms and deliverables</li><li>Coordinate content creation timelines</li><li>Track campaign performance and ROI</li></ul>',
                        'requirements'=> 'Marketing background, negotiation skills, social media knowledge',
                        'salary_min'  => 400000,
                        'salary_max'  => 700000,
                    ],
                ],
            ],
            'advocate' => [
                'title'        => 'Senior Advocate & Legal Consultant',
                'about'        => "I am {$name}, a Senior Advocate practising at the Madras High Court with over 8 years of experience in corporate law, intellectual property, and civil litigation. I provide strategic legal counsel to businesses, startups, and individuals navigating complex legal challenges.",
                'expertise'    => ['Corporate Law', 'IP & Trademarks', 'Civil Litigation', 'Contract Drafting', 'Startup Legal', 'Arbitration'],
                'services'     => [
                    ['icon' => '⚖️', 'title' => 'Civil Litigation', 'text' => 'Representation in High Courts and District Courts'],
                    ['icon' => '🏢', 'title' => 'Corporate Law', 'text' => 'Company formation, compliance, and M&A advisory'],
                    ['icon' => '💡', 'title' => 'IP & Trademarks', 'text' => 'Patent filing, trademark registration, IP protection'],
                    ['icon' => '📝', 'title' => 'Contract Drafting', 'text' => 'Commercial agreements, NDAs, and employment contracts'],
                    ['icon' => '🚀', 'title' => 'Startup Legal', 'text' => 'Founder agreements, term sheets, and investor contracts'],
                    ['icon' => '🤝', 'title' => 'Arbitration', 'text' => 'Commercial dispute resolution and mediation'],
                ],
                'practice_areas' => ['Corporate & Commercial', 'Intellectual Property', 'Civil & Criminal', 'Family Law', 'Real Estate'],
                'ai_name'      => $name . ' Legal AI',
                'ai_tagline'   => 'Ask about legal services & consultations',
                'color_accent' => '#0ea5e9',
                'color_bg'     => '#0a0f1e',
                'blog_cat'     => 'Legal',
                'blog_cat_slug'=> 'legal',
                'blog_posts'   => [
                    [
                        'title'   => 'Consumer Protection Act 2019: What Every Indian Must Know',
                        'slug'    => "consumer-protection-act-2019-{$username}",
                        'excerpt' => 'The Consumer Protection Act 2019 significantly strengthened consumer rights in India. Here\'s a comprehensive guide to understanding your rights and how to file a complaint.',
                        'content' => '<h2>Overview of the Consumer Protection Act 2019</h2><p>The Consumer Protection Act 2019 replaced the 1986 Act and brought sweeping changes to strengthen consumer rights in the digital age. Key highlights include e-commerce regulations, product liability provisions, and faster dispute resolution.</p><h2>Key Rights Under the Act</h2><ul><li><strong>Right to Safety</strong> — Protection against hazardous goods and services</li><li><strong>Right to Information</strong> — Full disclosure about products and services</li><li><strong>Right to Choose</strong> — Access to a variety of goods at competitive prices</li><li><strong>Right to be Heard</strong> — Consumer grievances must be addressed</li><li><strong>Right to Seek Redressal</strong> — Compensation for defective goods or deficient services</li><li><strong>Right to Consumer Education</strong> — Awareness about consumer rights</li></ul><h2>How to File a Complaint</h2><p>Complaints can be filed online at consumerhelpline.gov.in or at the District Consumer Disputes Redressal Commission. The pecuniary jurisdiction is: District Forum up to ₹1 crore, State Commission up to ₹10 crore, National Commission above ₹10 crore.</p><h2>E-Commerce Provisions</h2><p>The 2019 Act specifically addresses e-commerce, making platforms liable for misleading advertisements and requiring them to display seller information prominently.</p>',
                        'views'   => 3120,
                    ],
                    [
                        'title'   => 'Legal Checklist for Indian Startups: 10 Things to Do Before Launch',
                        'slug'    => "startup-legal-checklist-india-{$username}",
                        'excerpt' => 'Launching a startup without proper legal foundations is a recipe for disaster. Here are the 10 essential legal steps every Indian startup must complete before going live.',
                        'content' => '<h2>Why Legal Foundations Matter</h2><p>Most startup founders focus on product and growth, leaving legal matters for later. This is a costly mistake. Proper legal structure from day one protects founders, attracts investors, and prevents disputes.</p><h2>The 10-Point Checklist</h2><ol><li><strong>Choose the Right Business Structure</strong> — Private Limited Company is recommended for startups seeking investment</li><li><strong>Register Your Company</strong> — File with MCA through SPICe+ form</li><li><strong>Trademark Your Brand</strong> — File trademark application early, even before launch</li><li><strong>Draft Founder Agreements</strong> — Define equity splits, roles, vesting schedules, and IP assignment</li><li><strong>Intellectual Property Assignment</strong> — All IP created by founders must be assigned to the company</li><li><strong>Employee Agreements</strong> — Include NDA, IP assignment, and non-compete clauses</li><li><strong>Privacy Policy & Terms of Service</strong> — Mandatory for any digital product</li><li><strong>GST Registration</strong> — Required if turnover exceeds ₹20 lakhs</li><li><strong>Startup India Registration</strong> — For tax benefits and government schemes</li><li><strong>Shareholders\' Agreement</strong> — Critical before taking any external investment</li></ol><p>Contact me for a comprehensive legal audit of your startup at a flat fee.</p>',
                        'views'   => 2560,
                    ],
                ],
                'jobs' => [
                    [
                        'title'       => 'Junior Advocate — Corporate Law',
                        'slug'        => "junior-advocate-corporate-{$username}",
                        'company'     => $name . ' & Associates',
                        'location'    => 'Chennai',
                        'type'        => 'full-time',
                        'description' => '<p>We are looking for a Junior Advocate to join our corporate law practice. You will assist in drafting commercial agreements, conducting legal research, and appearing before courts.</p><h3>Requirements</h3><ul><li>LLB/LLM from a recognised university</li><li>Enrolled with Bar Council of Tamil Nadu</li><li>Strong drafting and research skills</li><li>0-3 years of experience</li></ul>',
                        'requirements'=> 'LLB/LLM, Bar Council enrollment, drafting skills',
                        'salary_min'  => 300000,
                        'salary_max'  => 500000,
                    ],
                    [
                        'title'       => 'Legal Research Associate',
                        'slug'        => "legal-research-associate-{$username}",
                        'company'     => $name . ' & Associates',
                        'location'    => 'Chennai / Remote',
                        'type'        => 'part-time',
                        'description' => '<p>We need a Legal Research Associate to assist with case research, preparing legal briefs, and maintaining our legal knowledge database.</p><h3>Requirements</h3><ul><li>LLB student (final year) or fresh graduate</li><li>Excellent research and writing skills</li><li>Familiarity with SCC Online and Manupatra</li></ul>',
                        'requirements'=> 'LLB student/graduate, research skills, legal databases',
                        'salary_min'  => 150000,
                        'salary_max'  => 250000,
                    ],
                ],
            ],
        ];

        $def = $templateDefaults[$template] ?? $templateDefaults['influencer'];

        // ── Site Settings ──────────────────────────────────────────────────
        $settings = [
            'site_name'           => $name,
            'site_tagline'        => $def['title'],
            'site_description'    => $def['about'],
            'profile_template'    => $template,
            'chatbot_enabled'     => '1',
            'ai_assistant_name'   => $def['ai_name'],
            'ai_assistant_tagline'=> $def['ai_tagline'],
            'color_accent'        => $def['color_accent'],
            'color_bg'            => $def['color_bg'],
            'profile_title'       => $def['title'],
            'profile_about'       => $def['about'],
            'profile_expertise'   => json_encode($def['expertise']),
            'profile_services'    => json_encode($def['services']),
        ];
        if (!empty($def['practice_areas'])) {
            $settings['profile_practice_areas'] = json_encode($def['practice_areas']);
        }
        if (!empty($def['platforms'])) {
            $settings['profile_platforms'] = json_encode($def['platforms']);
        }

        foreach ($settings as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['user_id' => $tid, 'key' => $key],
                ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        // ── Blog Posts ─────────────────────────────────────────────────────
        DB::table('blog_posts')->where('user_id', $tid)->delete();
        $catId = DB::table('blog_categories')->where('slug', $def['blog_cat_slug'])->value('id');
        if (!$catId) {
            $catId = DB::table('blog_categories')->insertGetId([
                'name' => $def['blog_cat'], 'slug' => $def['blog_cat_slug'],
                'created_at' => now(), 'updated_at' => now()
            ]);
        }
        foreach ($def['blog_posts'] as $post) {
            DB::table('blog_posts')->insert([
                'user_id'      => $tid,
                'category_id'  => $catId,
                'title'        => $post['title'],
                'slug'         => $post['slug'],
                'excerpt'      => $post['excerpt'],
                'content'      => $post['content'],
                'status'       => 'published',
                'views_count'  => $post['views'],
                'published_at' => Carbon::now()->subDays(rand(5, 30)),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // ── Jobs ───────────────────────────────────────────────────────────
        DB::table('jobs')->where('user_id', $tid)->delete();
        foreach ($def['jobs'] as $job) {
            DB::table('jobs')->insert([
                'user_id'      => $tid,
                'title'        => $job['title'],
                'slug'         => $job['slug'],
                'company'      => $job['company'],
                'location'     => $job['location'],
                'type'         => $job['type'],
                'description'  => $job['description'],
                'requirements' => $job['requirements'],
                'salary_min'   => $job['salary_min'],
                'salary_max'   => $job['salary_max'],
                'status'       => 'active',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // ── Chatbot Training ───────────────────────────────────────────────
        DB::table('chatbot_training')->where('user_id', $tid)->delete();
        $trainingData = $template === 'influencer' ? [
            ['q' => 'What type of content do you create?',    'a' => "I create lifestyle, fashion, travel, and beauty content. My style is authentic and aspirational — I share real experiences and honest reviews."],
            ['q' => 'How can brands collaborate with you?',   'a' => "Brands can reach out for Instagram posts, Reels, Stories, YouTube videos, blog features, and long-term ambassador partnerships. Please share your brand name, product, and campaign brief."],
            ['q' => 'What are your rates?',                   'a' => "Rates depend on the deliverables and campaign scope. Please share your campaign brief and I\'ll provide a customised media kit with rates."],
            ['q' => 'What platforms are you on?',             'a' => "I\'m active on Instagram, YouTube, Pinterest, and TikTok. Instagram is my primary platform with the highest engagement."],
            ['q' => 'Do you do gifted collaborations?',       'a' => "I consider gifted collaborations for brands that align with my values, but I primarily work on paid partnerships. Let\'s discuss your campaign goals."],
            ['q' => 'What is your audience demographic?',     'a' => "My audience is primarily women aged 18-35, based in India and Southeast Asia, interested in fashion, travel, and lifestyle content."],
        ] : [
            ['q' => 'What legal services do you offer?',      'a' => "I offer Civil Litigation, Corporate Law, IP & Trademarks, Contract Drafting, Startup Legal advisory, and Arbitration services. Book a consultation to discuss your specific legal matter."],
            ['q' => 'How do I book a consultation?',          'a' => "You can book a consultation by calling my office or filling out the contact form. Initial consultations are 30 minutes and help me understand your legal needs."],
            ['q' => 'What are your fees?',                    'a' => "Fees vary by matter complexity. Consultation fees start at ₹2,000. Retainer arrangements are available for ongoing corporate clients. Please contact me for a fee estimate."],
            ['q' => 'Do you handle criminal cases?',          'a' => "My primary practice areas are corporate law, IP, and civil litigation. For criminal matters, I can refer you to a specialist in my network."],
            ['q' => 'Can you help register a trademark?',     'a' => "Yes, trademark registration is one of my core services. I handle the entire process from trademark search to filing and prosecution. Contact me for a trademark audit."],
            ['q' => 'Do you work with startups?',             'a' => "Absolutely. I offer startup legal packages covering company formation, founder agreements, IP assignment, and investor documentation. Early-stage startups get special rates."],
        ];
        foreach ($trainingData as $t) {
            DB::table('chatbot_training')->insert([
                'user_id'    => $tid,
                'question'   => $t['q'],
                'answer'     => $t['a'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── Social Links ───────────────────────────────────────────────────
        if (DB::table('social_links')->where('user_id', $tid)->count() === 0) {
            $socials = $template === 'influencer' ? [
                ['platform' => 'Instagram', 'url' => 'https://instagram.com/' . $username, 'icon' => 'fab fa-instagram', 'sort_order' => 1],
                ['platform' => 'YouTube',   'url' => 'https://youtube.com/@' . $username,  'icon' => 'fab fa-youtube',   'sort_order' => 2],
                ['platform' => 'Pinterest', 'url' => 'https://pinterest.com/' . $username, 'icon' => 'fab fa-pinterest', 'sort_order' => 3],
            ] : [
                ['platform' => 'LinkedIn',  'url' => 'https://linkedin.com/in/' . $username, 'icon' => 'fab fa-linkedin', 'sort_order' => 1],
                ['platform' => 'Twitter',   'url' => 'https://twitter.com/' . $username,     'icon' => 'fab fa-twitter',  'sort_order' => 2],
            ];
            foreach ($socials as $s) {
                DB::table('social_links')->insert(array_merge($s, [
                    'user_id' => $tid, 'is_active' => 1,
                    'created_at' => now(), 'updated_at' => now()
                ]));
            }
        }

        // ── Default Menu ───────────────────────────────────────────────────
        if (DB::table('site_menus')->where('user_id', $tid)->count() === 0) {
            $menuItems = [
                ['label' => 'Home',    'url' => '/'],
                ['label' => 'About',   'url' => '/about'],
                ['label' => 'Blog',    'url' => '/blog'],
                ['label' => $template === 'advocate' ? 'Vacancies' : 'Jobs', 'url' => '/jobs'],
                ['label' => 'Contact', 'url' => '/#contact'],
            ];
            foreach ($menuItems as $i => $item) {
                DB::table('site_menus')->insert([
                    'user_id'    => $tid,
                    'label'      => $item['label'],
                    'url'        => $item['url'],
                    'target'     => '_self',
                    'sort_order' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info("{$username} bootstrapped successfully.");
    }
}
