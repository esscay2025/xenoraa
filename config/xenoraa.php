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

    // Subscription plans
    'plans' => [
        'starter' => [
            'name'          => 'Starter',
            'price_monthly' => 499,
            'price_yearly'  => 4999,
            'features' => [
                'portfolio_limit'   => 10,
                'blog_limit'        => 20,
                'custom_domain'     => false,
                'crm'               => false,
                'ai_chat'           => false,
                'ecommerce'         => false,
                'newsletter'        => false,
                'analytics'         => 'basic',
                'team_members'      => 0,
            ],
        ],
        'professional' => [
            'name'          => 'Professional',
            'price_monthly' => 999,
            'price_yearly'  => 9999,
            'features' => [
                'portfolio_limit'   => -1, // unlimited
                'blog_limit'        => -1,
                'custom_domain'     => true,
                'crm'               => true,
                'ai_chat'           => true,
                'ecommerce'         => false,
                'newsletter'        => true,
                'analytics'         => 'advanced',
                'team_members'      => 0,
            ],
        ],
        'business' => [
            'name'          => 'Business Pro',
            'price_monthly' => 1999,
            'price_yearly'  => 19999,
            'features' => [
                'portfolio_limit'   => -1,
                'blog_limit'        => -1,
                'custom_domain'     => true,
                'crm'               => true,
                'ai_chat'           => true,
                'ecommerce'         => true,
                'newsletter'        => true,
                'analytics'         => 'advanced',
                'team_members'      => 5,
            ],
        ],
    ],

    // Trial period in days
    'trial_days' => 14,

    // Free tier (before payment)
    'free_tier' => true,
];
