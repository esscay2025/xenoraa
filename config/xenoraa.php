<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Xenoraa SaaS Platform Configuration
    |--------------------------------------------------------------------------
    */

    // Main platform domain
    'main_domain' => env('XENORAA_DOMAIN', 'xenoraa.com'),

    // Platform name
    'platform_name' => env('XENORAA_NAME', 'Xenoraa'),

    // Platform tagline
    'tagline' => 'Build Your Digital Identity',

    // Super Admin email(s) — these users get superadmin access
    'superadmin_emails' => explode(',', env('SUPERADMIN_EMAILS', 'gopi@gopi.blog,support@gopi.blog')),

    /*
    |--------------------------------------------------------------------------
    | Plan → Module Access Map
    |--------------------------------------------------------------------------
    | Keys match the $canSee() module keys used in admin.blade.php sidebar.
    | Modules not listed here are ALWAYS visible (e.g. dashboard, calendar).
    |
    | Plans:
    |   starter      – Basic website: Site Builder + Content (Blog/Forum) + E-Commerce + Recruitment (Jobs)
    |   professional – Starter + CRM + AI Hub (AI Assistance) + POS
    |   business     – Everything (all modules)
    */
    'plan_modules' => [
        'starter' => [
            'site_builder',   // Site Builder module
            'content',        // Blog + Forum
            'ecommerce',      // E-Commerce / Shop
            'recruitment',    // Jobs module
            'analytics',      // Basic analytics
        ],
        'professional' => [
            'site_builder',
            'content',
            'ecommerce',
            'recruitment',
            'analytics',
            'crm',            // CRM module
            'ai',             // AI Hub (AI Assistance, AI Conversations)
            'pos',            // Point of Sale
            'newsletter',     // Newsletter
        ],
        'business' => [
            'site_builder',
            'content',
            'ecommerce',
            'recruitment',
            'analytics',
            'crm',
            'ai',
            'pos',
            'newsletter',
            // All future modules are included automatically via planHasModule()
        ],
    ],

    // Subscription plans (pricing & feature descriptions)
    'plans' => [
        'starter' => [
            'name'          => 'Starter',
            'price_monthly' => 499,
            'price_yearly'  => 4999,
            'description'   => 'Perfect for individuals and small businesses getting started online.',
            'features' => [
                'Professional Website (Site Builder)',
                'Blog & Forum',
                'E-Commerce / Shop',
                'Jobs Board',
                'Basic Analytics',
                '1 GB Storage',
                'SSL Certificate',
                'Mobile Responsive',
            ],
        ],
        'professional' => [
            'name'          => 'Professional',
            'price_monthly' => 999,
            'price_yearly'  => 9999,
            'description'   => 'For growing businesses that need CRM, AI and advanced tools.',
            'features' => [
                'Everything in Starter',
                'CRM (Leads, Contacts, Projects, Services)',
                'AI Hub (AI Assistance + Conversations)',
                'Point of Sale (POS)',
                'Newsletter Module',
                'Advanced Analytics',
                'Custom Domain',
                '5 GB Storage',
                'Priority Support',
            ],
        ],
        'business' => [
            'name'          => 'Business Pro',
            'price_monthly' => 1999,
            'price_yearly'  => 19999,
            'description'   => 'Full-featured platform for established businesses and agencies.',
            'features' => [
                'Everything in Professional',
                'All Current & Future Modules',
                'Unlimited Storage',
                'White-label Option',
                'Dedicated Support',
                'Team Members (up to 10)',
                'API Access',
                'Custom Integrations',
            ],
        ],
    ],

    // Free tier disabled — no free plan, no trial
    'free_tier' => false,
    'trial_days' => 0,
];
