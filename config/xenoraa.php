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
    'tagline' => 'Run Your Entire Business on One Platform',

    // Super Admin email(s) — these users get superadmin access
    'superadmin_emails' => explode(',', env('SUPERADMIN_EMAILS', 'gopi@gopi.blog,support@gopi.blog')),

    /*
    |--------------------------------------------------------------------------
    | App Definitions
    |--------------------------------------------------------------------------
    | Each "app" is a self-contained product that a tenant can activate.
    | The modules listed here are the sidebar modules unlocked when that app
    | is active for the tenant.
    |
    | App keys: website, ecommerce, pos, crm
    |--------------------------------------------------------------------------
    */
    'apps' => [
        'website' => [
            'name'        => 'Website',
            'icon'        => 'fa-globe',
            'color'       => '#3b82f6',
            'description' => 'Build professional websites, landing pages, blogs, and portfolios with the drag-and-drop Site Builder.',
            'modules'     => [
                'site_builder',   // Site Builder / Page Manager
                'content',        // Blog + Forum
                'recruitment',    // Jobs Board
                'analytics',      // Analytics
                'newsletter',     // Newsletter
            ],
        ],
        'ecommerce' => [
            'name'        => 'E-Commerce',
            'icon'        => 'fa-shopping-cart',
            'color'       => '#10b981',
            'description' => 'Sell products online with a full-featured store — catalog, orders, payments, and inventory.',
            'modules'     => [
                'ecommerce',      // E-Commerce / Shop
                'accounts',       // Accounts & Finance (shared)
                'analytics',      // Analytics
            ],
        ],
        'pos' => [
            'name'        => 'POS',
            'icon'        => 'fa-cash-register',
            'color'       => '#f59e0b',
            'description' => 'Run a physical retail or service business with the Point of Sale terminal, sessions, and receipts.',
            'modules'     => [
                'pos',            // Point of Sale
                'accounts',       // Accounts & Finance (shared)
                'analytics',      // Analytics
            ],
        ],
        'crm' => [
            'name'        => 'CRM',
            'icon'        => 'fa-users',
            'color'       => '#8b5cf6',
            'description' => 'Manage leads, contacts, deals, projects, services, and support with the full CRM suite.',
            'modules'     => [
                'crm',            // CRM (Leads, Contacts, Deals, Accounts, Activities)
                'inventory',      // Inventory (Quotes, SOs, POs, Invoices, Vendors)
                'projects',       // Projects & Tasks
                'services',       // Services & Bookings
                'support',        // Support (Cases & Solutions)
                'ai',             // AI Hub (Xena AI)
                'accounts',       // Accounts & Finance (shared)
                'analytics',      // Analytics
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    | Plans define how many app slots a tenant gets and which apps are
    | available to them. The tenant then selects their apps within those limits.
    |
    | Plans:
    |   solo_app    – 1 app slot  – choose 1 from: website, ecommerce, pos, crm
    |   duo_bundle  – 2 app slots – choose 2 from: website, ecommerce, pos, crm
    |   all_access  – 4 app slots – all apps always included, no choice needed
    |--------------------------------------------------------------------------
    */
    'plans' => [
        'solo_app' => [
            'name'          => 'Solo App',
            'app_slots'     => 1,
            'available_apps' => ['website', 'ecommerce', 'pos', 'crm'],
            'price_monthly' => 499,
            'price_yearly'  => 4990,
            'description'   => 'Perfect for businesses that need one focused app. Pick the one that fits your workflow.',
            'badge'         => null,
            'color'         => '#71717a',
            'features' => [
                'Choose 1 app: Website, E-Commerce, POS, or CRM',
                'Custom Domain & SSL',
                'Mobile Responsive',
                '5 GB Storage',
                'Email Support',
                'Analytics Dashboard',
            ],
        ],
        'duo_bundle' => [
            'name'          => 'Duo Bundle',
            'app_slots'     => 2,
            'available_apps' => ['website', 'ecommerce', 'pos', 'crm'],
            'price_monthly' => 999,
            'price_yearly'  => 9990,
            'description'   => 'Combine two apps for a more complete business platform. Popular combos: Website+CRM, E-Commerce+POS.',
            'badge'         => 'Most Popular',
            'color'         => '#7c3aed',
            'features' => [
                'Choose 2 apps from: Website, E-Commerce, POS, CRM',
                'Popular combos: Website+E-Commerce, E-Commerce+POS, Website+CRM',
                'Custom Domain & SSL',
                'Mobile Responsive',
                '20 GB Storage',
                'Priority Email Support',
                'Advanced Analytics',
                'Accounts & Finance (shared)',
            ],
        ],
        'all_access' => [
            'name'          => 'All-Access',
            'app_slots'     => 4,
            'available_apps' => ['website', 'ecommerce', 'pos', 'crm'],
            'price_monthly' => 1999,
            'price_yearly'  => 19990,
            'description'   => 'The complete Xenoraa platform. All 4 apps, every module, unlimited potential.',
            'badge'         => 'Best Value',
            'color'         => '#eab308',
            'features' => [
                'All 4 apps: Website, E-Commerce, POS, CRM',
                'Full Inventory Management',
                'Projects & Tasks',
                'Services & Bookings',
                'Support (Cases & Solutions)',
                'AI Hub (Xena AI)',
                'Accounts & Finance',
                'Unlimited Storage',
                'Dedicated Support',
                'All Current & Future Modules',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Legacy Plan Modules Map (backward compatibility)
    |--------------------------------------------------------------------------
    | Used by planHasModule() for tenants still on old plan names.
    | New tenants use selected_apps + apps.*.modules above.
    |--------------------------------------------------------------------------
    */
    'plan_modules' => [
        'starter' => [
            'site_builder', 'content', 'ecommerce', 'recruitment', 'analytics',
        ],
        'professional' => [
            'site_builder', 'content', 'ecommerce', 'recruitment', 'analytics',
            'crm', 'ai', 'pos', 'accounts', 'newsletter',
        ],
        'business' => [
            'site_builder', 'content', 'ecommerce', 'recruitment', 'analytics',
            'crm', 'ai', 'pos', 'accounts', 'newsletter',
            'inventory', 'projects', 'services', 'support',
        ],
        // Legacy aliases
        'business_pro' => [
            'site_builder', 'content', 'ecommerce', 'recruitment', 'analytics',
            'crm', 'ai', 'pos', 'accounts', 'newsletter',
            'inventory', 'projects', 'services', 'support',
        ],
    ],

    // Free tier disabled — no free plan, no trial
    'free_tier' => false,
    'trial_days' => 0,
];
