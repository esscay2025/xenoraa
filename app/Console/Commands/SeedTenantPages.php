<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedTenantPages extends Command
{
    protected $signature   = 'xenoraa:seed-tenant-pages {--user=all : User ID or "all"}';
    protected $description = 'Seed / update page section data for all tenants';

    public function handle(): int
    {
        $userId = $this->option('user');

        if ($userId === 'all' || $userId == 1) {
            $this->seedGopi();
        }
        if ($userId === 'all' || $userId == 9) {
            $this->seedPriya();
        }
        if ($userId === 'all' || $userId == 10) {
            $this->seedArjun();
        }
        if ($userId === 'all' || $userId == 11) {
            $this->seedSoushanth();
        }

        $this->info('✅ All tenant page sections seeded successfully.');
        return 0;
    }

    // ─── GOPI K (user 1) — IT Professional & Solutions Architect ──────────────
    private function seedGopi(): void
    {
        $this->info('Seeding Gopi K (user 1)...');

        // HOME PAGE
        $this->upsert(1, 'home', 'home', [
            ['key' => 'hero', 'enabled' => true, 'data' => [
                'badge'      => '💼 IT Professional & Solutions Architect',
                'heading'    => 'Gopi K',
                'subheading' => '10+ Years in Cloud Architecture, DevOps & Digital Transformation',
                'cta_text'   => 'Book a Consultation',
                'cta_url'    => '/contact',
                'image'      => null,
                'bg_color'   => null,
            ]],
            ['key' => 'stats', 'enabled' => true, 'data' => [
                'heading' => 'By the Numbers',
                'items'   => [
                    ['icon' => '🏆', 'label' => 'Years Experience',  'value' => '10+'],
                    ['icon' => '😊', 'label' => 'Happy Clients',     'value' => '500+'],
                    ['icon' => '✅', 'label' => 'Projects Done',     'value' => '189+'],
                    ['icon' => '☁️', 'label' => 'Cloud Migrations',  'value' => '50+'],
                ],
            ]],
            ['key' => 'about', 'enabled' => true, 'data' => [
                'heading'  => 'About Me',
                'text'     => 'I am Gopi K, an IT professional with over 10 years of experience in software architecture, cloud infrastructure, and digital transformation. I have worked with startups and enterprises across India and Southeast Asia, helping them build scalable, secure, and cost-efficient technology solutions. My expertise spans full-stack development, DevOps, cloud migration, and AI integration.',
                'cta_text' => 'Full Profile',
                'cta_url'  => '/about',
                'image'    => null,
            ]],
            ['key' => 'services', 'enabled' => true, 'data' => [
                'heading'    => 'What I Do',
                'subheading' => 'End-to-end technology solutions for modern businesses',
                'items'      => [
                    ['icon' => '☁️', 'title' => 'Cloud Architecture',    'text' => 'Design and implement scalable AWS, Azure, and GCP infrastructure with cost optimisation and 99.9% uptime SLAs.'],
                    ['icon' => '🔄', 'title' => 'DevOps & CI/CD',        'text' => 'Automate deployments with Docker, Kubernetes, GitHub Actions, and Jenkins for faster, reliable software delivery.'],
                    ['icon' => '🤖', 'title' => 'AI & Automation',       'text' => 'Integrate AI/ML solutions, chatbots, and workflow automation to reduce manual effort and drive business efficiency.'],
                    ['icon' => '💻', 'title' => 'Full-Stack Development', 'text' => 'Build modern web and mobile applications using React, Node.js, Laravel, and Python with clean, maintainable code.'],
                    ['icon' => '🔒', 'title' => 'Cybersecurity',         'text' => 'Security audits, penetration testing, compliance (ISO 27001, SOC2), and zero-trust architecture implementation.'],
                    ['icon' => '📊', 'title' => 'Digital Transformation', 'text' => 'End-to-end digital strategy, legacy system modernisation, and technology roadmaps aligned to business goals.'],
                ],
            ]],
            ['key' => 'testimonials', 'enabled' => true, 'data' => [
                'heading' => 'What Clients Say',
                'items'   => [
                    ['name' => 'Rajesh Kumar',  'role' => 'CTO, FinTech Startup',   'text' => 'Gopi architected our entire cloud infrastructure from scratch. We went from zero to production in 3 months with a system that handles 100K+ daily transactions flawlessly.', 'avatar' => null],
                    ['name' => 'Meera Sharma',  'role' => 'CEO, EduTech Platform',  'text' => 'The DevOps pipeline Gopi built reduced our deployment time from 2 hours to 8 minutes. His expertise in Kubernetes saved us ₹40L annually in infrastructure costs.', 'avatar' => null],
                    ['name' => 'Arjun Nair',    'role' => 'Founder, SaaS Company',  'text' => 'Gopi transformed our legacy monolith into a microservices architecture. The system now scales automatically and our team ships features 5x faster.', 'avatar' => null],
                ],
            ]],
            ['key' => 'blog',    'enabled' => true, 'data' => ['heading' => 'Latest Articles', 'count' => '3']],
            ['key' => 'jobs',    'enabled' => true, 'data' => ['heading' => 'Open Positions',  'count' => '3']],
            ['key' => 'contact', 'enabled' => true, 'data' => [
                'heading'     => "Let's Build Something Great",
                'text'        => 'Have a technology challenge? Let\'s discuss how I can help you build scalable, secure, and efficient solutions.',
                'button_text' => 'Get in Touch',
                'button_url'  => '/contact',
            ]],
        ]);

        // ABOUT PAGE
        $this->upsert(1, 'about', 'about', [
            ['key' => 'hero', 'enabled' => true, 'data' => [
                'heading'    => 'About Gopi K',
                'subheading' => 'IT Professional · Cloud Architect · Digital Transformation Expert',
                'image'      => null,
            ]],
            ['key' => 'bio', 'enabled' => true, 'data' => [
                'heading' => 'My Story',
                'text'    => 'With over a decade of hands-on experience in enterprise technology, I have helped more than 200 companies across India and Southeast Asia modernise their technology stack and achieve their digital transformation goals. I started my career as a software developer and grew into architecture and leadership roles, working with organisations ranging from early-stage startups to Fortune 500 companies. My passion lies in solving complex technical problems with elegant, scalable solutions. I hold certifications from AWS, Google Cloud, and Microsoft Azure, and I am a regular speaker at technology conferences across India.',
                'image'   => null,
            ]],
            ['key' => 'stats', 'enabled' => true, 'data' => [
                'items' => [
                    ['icon' => '🏆', 'label' => 'Years Experience',    'value' => '10+'],
                    ['icon' => '😊', 'label' => 'Clients Served',      'value' => '200+'],
                    ['icon' => '✅', 'label' => 'Projects Completed',  'value' => '189+'],
                    ['icon' => '☁️', 'label' => 'Cloud Migrations',    'value' => '50+'],
                ],
            ]],
            ['key' => 'skills',         'enabled' => true, 'data' => ['heading' => 'Technical Skills']],
            ['key' => 'experience',     'enabled' => true, 'data' => ['heading' => 'Work Experience']],
            ['key' => 'education',      'enabled' => true, 'data' => ['heading' => 'Education']],
            ['key' => 'certifications', 'enabled' => true, 'data' => ['heading' => 'Certifications']],
            ['key' => 'social',         'enabled' => true, 'data' => ['heading' => 'Connect with Me']],
        ]);

        // SERVICES PAGE
        $this->upsert(1, 'services', 'services', [
            ['key' => 'hero', 'enabled' => true, 'data' => [
                'heading'    => 'Services',
                'subheading' => 'Comprehensive technology solutions tailored to your business needs',
            ]],
            ['key' => 'list', 'enabled' => true, 'data' => [
                'heading' => 'What I Offer',
                'layout'  => 'grid',
                'items'   => [
                    ['icon' => '☁️', 'title' => 'Cloud Architecture & Migration',  'text' => 'Design, migrate, and optimise your infrastructure on AWS, Azure, or GCP. Includes architecture review, cost optimisation, and 24/7 monitoring setup.', 'price' => '₹75,000+'],
                    ['icon' => '🔄', 'title' => 'DevOps & CI/CD Pipeline',         'text' => 'End-to-end DevOps implementation with Docker, Kubernetes, Terraform, and automated testing pipelines for faster, reliable deployments.', 'price' => '₹60,000+'],
                    ['icon' => '🤖', 'title' => 'AI Integration & Automation',     'text' => 'Custom AI chatbots, ML model integration, workflow automation, and intelligent data processing solutions for your business.', 'price' => '₹80,000+'],
                    ['icon' => '💻', 'title' => 'Full-Stack Web Development',      'text' => 'Modern, responsive web applications built with React, Next.js, Laravel, or Node.js. From MVPs to enterprise-grade platforms.', 'price' => '₹50,000+'],
                    ['icon' => '🔒', 'title' => 'Security Audit & Compliance',     'text' => 'Comprehensive security assessment, penetration testing, vulnerability remediation, and compliance preparation (ISO 27001, SOC2, GDPR).', 'price' => '₹40,000+'],
                    ['icon' => '📊', 'title' => 'Digital Transformation Consulting','text' => 'Strategic technology roadmap, legacy modernisation, vendor selection, and change management for your digital journey.', 'price' => '₹1,00,000+'],
                ],
            ]],
            ['key' => 'process', 'enabled' => true, 'data' => [
                'heading' => 'My Process',
                'items'   => [
                    ['step' => '1', 'title' => 'Discovery',  'text' => 'Deep-dive into your current technology landscape, business goals, pain points, and constraints.'],
                    ['step' => '2', 'title' => 'Strategy',   'text' => 'Develop a tailored technology roadmap with clear milestones, timelines, and ROI projections.'],
                    ['step' => '3', 'title' => 'Execution',  'text' => 'Hands-on implementation with regular progress updates, code reviews, and quality assurance.'],
                    ['step' => '4', 'title' => 'Delivery',   'text' => 'Go-live support, team training, documentation, and 30-day post-launch monitoring.'],
                ],
            ]],
            ['key' => 'pricing', 'enabled' => true, 'data' => [
                'heading' => 'Engagement Models',
                'items'   => [
                    ['name' => 'Project-Based', 'price' => '₹50,000', 'period' => '/project', 'features_text' => "Fixed scope and timeline\nDetailed project plan\nWeekly progress reports\nPost-delivery support"],
                    ['name' => 'Retainer',      'price' => '₹80,000', 'period' => '/month',   'features_text' => "40 hours/month\nPriority response\nMonthly strategy call\nUnlimited email support"],
                    ['name' => 'Enterprise',    'price' => 'Custom',   'period' => '',          'features_text' => "Dedicated engagement\nOn-site availability\nTeam augmentation\nSLA-backed delivery"],
                ],
            ]],
            ['key' => 'cta', 'enabled' => true, 'data' => [
                'heading'    => 'Ready to Transform Your Technology?',
                'text'       => "Let's discuss your project and build a solution that scales with your business.",
                'button_text'=> 'Schedule Free Consultation',
                'button_url' => '/contact',
            ]],
        ]);

        // CONTACT PAGE
        $this->upsert(1, 'contact', 'contact', [
            ['key' => 'hero', 'enabled' => true, 'data' => [
                'heading'    => 'Get in Touch',
                'subheading' => "Let's discuss your technology challenges and build something great together",
            ]],
            ['key' => 'form', 'enabled' => true, 'data' => [
                'email'         => 'gopi@xenoraa.com',
                'phone'         => '+91 98765 43210',
                'address'       => 'Chennai, Tamil Nadu, India',
                'working_hours' => 'Mon–Fri: 9:00 AM – 6:00 PM IST',
            ]],
            ['key' => 'social', 'enabled' => true, 'data' => ['heading' => 'Connect on Social']],
        ]);

        // PORTFOLIO PAGE
        $this->upsert(1, 'portfolio', 'portfolio', [
            ['key' => 'hero', 'enabled' => true, 'data' => [
                'heading'    => 'My Work',
                'subheading' => 'A selection of projects that showcase my expertise in cloud, DevOps, and full-stack development',
            ]],
            ['key' => 'filter',   'enabled' => true, 'data' => []],
            ['key' => 'projects', 'enabled' => true, 'data' => [
                'items' => [
                    ['icon' => '☁️', 'title' => 'FinTech Cloud Migration',       'text' => 'Migrated a 10-year-old monolithic banking application to AWS microservices. Achieved 99.99% uptime and 60% cost reduction.', 'category' => 'Cloud',       'url' => null, 'image' => null],
                    ['icon' => '🔄', 'title' => 'E-Commerce DevOps Pipeline',    'text' => 'Built a full CI/CD pipeline for a 50-developer team. Reduced deployment time from 4 hours to 12 minutes with zero-downtime releases.', 'category' => 'DevOps',      'url' => null, 'image' => null],
                    ['icon' => '🤖', 'title' => 'AI Customer Support Bot',       'text' => 'Developed an NLP-powered chatbot handling 80% of customer queries autonomously, reducing support costs by ₹25L/year.', 'category' => 'AI',          'url' => null, 'image' => null],
                    ['icon' => '💻', 'title' => 'SaaS Platform Development',     'text' => 'Built a multi-tenant SaaS platform from scratch serving 5,000+ businesses across India with 99.9% uptime.', 'category' => 'Development', 'url' => null, 'image' => null],
                    ['icon' => '🔒', 'title' => 'Healthcare Security Audit',     'text' => 'Conducted comprehensive security audit for a 500-bed hospital, achieving HIPAA compliance and securing 2M+ patient records.', 'category' => 'Security',    'url' => null, 'image' => null],
                    ['icon' => '📊', 'title' => 'Retail Digital Transformation', 'text' => 'Led end-to-end digital transformation for a 200-store retail chain, integrating ERP, POS, and e-commerce into a unified platform.', 'category' => 'Consulting',  'url' => null, 'image' => null],
                ],
            ]],
        ]);

        $this->info('  ✓ Gopi K pages seeded.');
    }

    // ─── PRIYA (user 9) — Fill missing agenda/achievements/ventures ──────────
    private function seedPriya(): void
    {
        $this->info('Seeding Priya (user 9)...');

        // Patch ventures, agenda, achievements on home page
        $page = DB::table('custom_pages')->where('user_id', 9)->where('page_type', 'home')->first();
        if ($page && $page->sections) {
            $sections = json_decode($page->sections, true);
            foreach ($sections as &$s) {
                if ($s['key'] === 'ventures') {
                    $s['data'] = [
                        'heading' => 'My Ventures',
                        'items'   => [
                            ['icon' => '💄', 'title' => 'GlowByAbi',    'text' => 'My curated beauty and skincare brand featuring products I personally test and recommend.', 'url' => null],
                            ['icon' => '🎓', 'title' => 'CreatorAcademy', 'text' => 'Online courses teaching aspiring creators how to grow, monetise, and collaborate with brands.', 'url' => null],
                        ],
                    ];
                }
                if ($s['key'] === 'agenda') {
                    $s['data'] = [
                        'heading' => 'My Values',
                        'items'   => [
                            ['icon' => '🌿', 'title' => 'Sustainability', 'text' => 'I only partner with brands that share my commitment to eco-friendly and ethical practices.'],
                            ['icon' => '💯', 'title' => 'Authenticity',   'text' => 'Every piece of content I create is genuine — I only recommend products I truly believe in.'],
                            ['icon' => '🤝', 'title' => 'Community',      'text' => 'Building a positive, inclusive community where everyone feels welcome and inspired.'],
                        ],
                    ];
                }
                if ($s['key'] === 'achievements') {
                    $s['data'] = [
                        'heading' => 'Key Milestones',
                        'items'   => [
                            ['icon' => '🏆', 'title' => 'Forbes 30 Under 30', 'year' => '2024', 'text' => 'Recognised as one of India\'s most influential digital creators under 30.'],
                            ['icon' => '🥇', 'title' => '2M+ Followers',      'year' => '2023', 'text' => 'Crossed 2 million combined followers across Instagram, YouTube, and TikTok.'],
                        ],
                    ];
                }
            }
            unset($s);
            DB::table('custom_pages')->where('user_id', 9)->where('page_type', 'home')
                ->update(['sections' => json_encode($sections)]);
        }

        // Contact page for Priya
        $this->upsert(9, 'contact', 'contact', [
            ['key' => 'hero', 'enabled' => true, 'data' => [
                'heading'    => 'Work With Me',
                'subheading' => "Let's create authentic content that resonates with your audience and drives real results",
            ]],
            ['key' => 'form', 'enabled' => true, 'data' => [
                'email'         => 'collabs@abirami.info',
                'phone'         => '+91 98765 00001',
                'address'       => 'Mumbai, Maharashtra, India',
                'working_hours' => 'Mon–Sat: 10:00 AM – 7:00 PM IST',
            ]],
            ['key' => 'social', 'enabled' => true, 'data' => ['heading' => 'Follow Me']],
        ]);

        $this->info('  ✓ Priya pages seeded.');
    }

    // ─── ARJUN (user 10) — Fill missing ventures/agenda/achievements ─────────
    private function seedArjun(): void
    {
        $this->info('Seeding Arjun Mehta (user 10)...');

        $page = DB::table('custom_pages')->where('user_id', 10)->where('page_type', 'home')->first();
        if ($page && $page->sections) {
            $sections = json_decode($page->sections, true);
            foreach ($sections as &$s) {
                if ($s['key'] === 'ventures') {
                    $s['data'] = [
                        'heading' => 'My Practice Areas',
                        'items'   => [
                            ['icon' => '🏢', 'title' => 'Mehta & Associates',  'text' => 'My law chambers at Supreme Court of India, specialising in corporate and IP law.', 'url' => null],
                            ['icon' => '📚', 'title' => 'Legal Insights Blog', 'text' => 'Weekly analysis of landmark judgements and legal developments affecting businesses.', 'url' => null],
                        ],
                    ];
                }
                if ($s['key'] === 'agenda') {
                    $s['data'] = [
                        'heading' => 'My Principles',
                        'items'   => [
                            ['icon' => '⚖️', 'title' => 'Justice First',    'text' => 'Every client deserves zealous representation regardless of the complexity of their matter.'],
                            ['icon' => '🔍', 'title' => 'Meticulous Research', 'text' => 'Thorough legal research and case preparation is the foundation of every successful outcome.'],
                            ['icon' => '🤝', 'title' => 'Client Partnership', 'text' => 'I believe in transparent communication and treating clients as partners in their legal journey.'],
                        ],
                    ];
                }
                if ($s['key'] === 'achievements') {
                    $s['data'] = [
                        'heading' => 'Key Achievements',
                        'items'   => [
                            ['icon' => '🏆', 'title' => 'Senior Advocate Designation', 'year' => '2020', 'text' => 'Designated as Senior Advocate by the Delhi High Court for outstanding legal contribution.'],
                            ['icon' => '🥇', 'title' => 'Best Corporate Lawyer Award', 'year' => '2022', 'text' => 'Recognised by Legal500 India as one of the top corporate lawyers in the country.'],
                        ],
                    ];
                }
            }
            unset($s);
            DB::table('custom_pages')->where('user_id', 10)->where('page_type', 'home')
                ->update(['sections' => json_encode($sections)]);
        }

        // Contact page for Arjun
        $this->upsert(10, 'contact', 'contact', [
            ['key' => 'hero', 'enabled' => true, 'data' => [
                'heading'    => 'Schedule a Legal Consultation',
                'subheading' => 'Every legal matter deserves expert attention. Contact my chambers for a confidential consultation.',
            ]],
            ['key' => 'form', 'enabled' => true, 'data' => [
                'email'         => 'chambers@arjunmehta.in',
                'phone'         => '+91 11 4567 8901',
                'address'       => 'Supreme Court of India, New Delhi',
                'working_hours' => 'Mon–Fri: 10:00 AM – 5:00 PM IST',
            ]],
            ['key' => 'social', 'enabled' => true, 'data' => ['heading' => 'Connect']],
        ]);

        $this->info('  ✓ Arjun Mehta pages seeded.');
    }

    // ─── SOUSHANTH (user 11) — Fill missing agenda/achievements ─────────────
    private function seedSoushanth(): void
    {
        $this->info('Seeding Soushanth (user 11)...');

        $page = DB::table('custom_pages')->where('user_id', 11)->where('page_type', 'home')->first();
        if ($page && $page->sections) {
            $sections = json_decode($page->sections, true);
            foreach ($sections as &$s) {
                if ($s['key'] === 'agenda') {
                    $s['data'] = [
                        'heading' => 'My Mission',
                        'items'   => [
                            ['icon' => '🚀', 'title' => 'Build for Scale',   'text' => 'Every product I build is designed to serve millions of users from day one.'],
                            ['icon' => '🌍', 'title' => 'Global Impact',     'text' => 'Technology should solve real problems for real people, regardless of geography.'],
                            ['icon' => '🤝', 'title' => 'Founder Community', 'text' => 'Paying it forward by mentoring the next generation of Indian tech entrepreneurs.'],
                        ],
                    ];
                }
                if ($s['key'] === 'achievements') {
                    $s['data'] = [
                        'heading' => 'Key Achievements',
                        'items'   => [
                            ['icon' => '🏆', 'title' => 'Forbes 30 Under 30',      'year' => '2023', 'text' => 'Recognised as one of India\'s most promising young entrepreneurs.'],
                            ['icon' => '🥇', 'title' => '$12M+ Funding Raised',    'year' => '2022', 'text' => 'Collectively raised over $12 million across four ventures from top-tier VCs.'],
                            ['icon' => '⭐', 'title' => 'TechCorp Acquisition',    'year' => '2022', 'text' => 'AutoDesk AI acquired by TechCorp for $3.2M — my first successful exit.'],
                        ],
                    ];
                }
            }
            unset($s);
            DB::table('custom_pages')->where('user_id', 11)->where('page_type', 'home')
                ->update(['sections' => json_encode($sections)]);
        }

        // Contact page for Soushanth
        $this->upsert(11, 'contact', 'contact', [
            ['key' => 'hero', 'enabled' => true, 'data' => [
                'heading'    => "Let's Build Something Great",
                'subheading' => 'Whether you\'re a founder, investor, or potential partner — I\'d love to connect.',
            ]],
            ['key' => 'form', 'enabled' => true, 'data' => [
                'email'         => 'hello@soushanth.com',
                'phone'         => '+91 98765 11011',
                'address'       => 'Chennai, Tamil Nadu, India',
                'working_hours' => 'Mon–Fri: 9:00 AM – 6:00 PM IST',
            ]],
            ['key' => 'social', 'enabled' => true, 'data' => ['heading' => 'Connect']],
        ]);

        $this->info('  ✓ Soushanth pages seeded.');
    }

    // ─── HELPER: upsert a custom_page record ──────────────────────────────────
    private function upsert(int $userId, string $pageType, string $slug, array $sections): void
    {
        $exists = DB::table('custom_pages')
            ->where('user_id', $userId)
            ->where('page_type', $pageType)
            ->exists();

        $data = [
            'sections'   => json_encode($sections),
            'updated_at' => now(),
        ];

        if ($exists) {
            DB::table('custom_pages')
                ->where('user_id', $userId)
                ->where('page_type', $pageType)
                ->update($data);
        } else {
            DB::table('custom_pages')->insert(array_merge($data, [
                'user_id'    => $userId,
                'page_type'  => $pageType,
                'slug'       => $slug,
                'title'      => ucfirst($pageType),
                'is_active'  => true,
                'created_at' => now(),
            ]));
        }
    }
}
