<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        // ── Resolve current tenant from domain or route parameter ──────────────
        $layoutTenant = null;
        $host = request()->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');
        $isMainDomain = ($host === $mainDomain || $host === 'www.' . $mainDomain);

        if (!$isMainDomain) {
            // Custom domain (e.g. gopi.blog)
            $layoutTenant = \App\Models\User::where('custom_domain', $host)
                ->orWhere('custom_domain', 'www.' . $host)
                ->first();
        }

        if (!$layoutTenant) {
            // Username-based route: xenoraa.com/priya/...
            $routeUsername = request()->route('username');
            if ($routeUsername) {
                $layoutTenant = \App\Models\User::where('username', $routeUsername)->first();
            }
        }

        if (!$layoutTenant && auth()->check() && auth()->user()->isAdmin()) {
            $layoutTenant = auth()->user();
        }

        // ── Tenant-aware URL helpers ───────────────────────────────────────────
        $tenantBase = '';
        $tenantLoginUrl = route('login');
        $tenantRegisterUrl = route('register');

        if ($layoutTenant) {
            if ($layoutTenant->custom_domain && !$isMainDomain) {
                // On gopi.blog — no prefix needed
                $tenantBase = '';
                $tenantLoginUrl = url('/login');
            } else {
                // On xenoraa.com/{username}/...
                $tenantBase = '/' . $layoutTenant->username;
                $tenantLoginUrl = url('/' . $layoutTenant->username . '/login');
            }
        }

        $tenantHomeUrl   = $tenantBase ? url($tenantBase) : route('home');
        $tenantAboutUrl  = $tenantBase ? url($tenantBase . '/about') : route('about');
        $tenantBlogUrl   = $tenantBase ? url($tenantBase . '/blog') : route('blog');
        $tenantJobsUrl   = $tenantBase ? url($tenantBase . '/jobs') : route('jobs');
        $tenantShopUrl   = $tenantBase ? url($tenantBase . '/shop') : route('shop');

        // ── Tenant site settings ───────────────────────────────────────────────
        $tenantSettings = [];
        if ($layoutTenant) {
            $tenantSettings = \App\Models\SiteSetting::where('user_id', $layoutTenant->id)
                ->pluck('value', 'key')
                ->toArray();
        }

        $siteName    = $tenantSettings['site_name']    ?? ($layoutTenant?->name ?? 'Xenoraa');
        $siteTagline = $tenantSettings['site_tagline'] ?? ($layoutTenant?->profile_tagline ?? '');
        $footerTagline = $tenantSettings['site_description'] ?? $tenantSettings['footer_tagline'] ?? $siteTagline;

        // ── Tenant social links ────────────────────────────────────────────────
        $footerSocials = $layoutTenant
            ? \App\Models\SocialLink::where('user_id', $layoutTenant->id)->where('is_active', true)->get()
            : collect();

        // ── Tenant profile template — read from site_settings first ────────────
        $tenantTemplate = $tenantSettings['profile_template']
            ?? $layoutTenant?->getProfileTemplate()
            ?? 'consultant';

        // ── Tenant favicon and logo ────────────────────────────────────────────
        $tenantFavicon = $tenantSettings['favicon_path'] ?? null;
        $tenantLogo    = $tenantSettings['logo_path'] ?? null;
        $tenantAccent  = $tenantSettings['color_accent'] ?? '#6366f1';
        $chatbotEnabled = $tenantSettings['chatbot_enabled'] ?? '1';
    @endphp
    <title>@yield('title', $siteName . ' | Portfolio')</title>
    <meta name="description" content="@yield('description', $footerTagline)">
    @if($tenantFavicon)
    <link rel="shortcut icon" href="{{ $tenantFavicon }}">
    <link rel="icon" type="image/png" href="{{ $tenantFavicon }}">
    <link rel="apple-touch-icon" href="{{ $tenantFavicon }}">
    @else
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon-64.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg-primary: #0a0a0a; --bg-secondary: #111111; --bg-card: #1a1a1a; --bg-hover: #222222;
            --text-primary: #ffffff; --text-secondary: #a0a0a0; --text-muted: #666666;
            --accent: #ffffff; --border: #2a2a2a; --border-light: #333333;
            --success: #22c55e; --danger: #ef4444; --warning: #f59e0b; --info: #3b82f6;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-primary); color: var(--text-primary); margin: 0; padding: 0; line-height: 1.6; }

        /* ── NAVBAR ── */
        .navbar { background-color: rgba(10,10,10,0.97); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 1000; padding: 0 2rem; }
        .navbar-inner { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; height: 64px; }
        .navbar-brand { display: flex; align-items: center; text-decoration: none; flex-shrink: 0; gap: 0.5rem; }
        .navbar-brand img { height: 36px; width: auto; display: block; }
        .navbar-nav { display: flex; align-items: center; gap: 0.25rem; list-style: none; margin: 0; padding: 0; }
        .navbar-nav > li { position: relative; }
        .navbar-nav > li > a, .navbar-nav > li > button { color: var(--text-secondary); text-decoration: none; padding: 0.5rem 0.75rem; border-radius: 6px; font-size: 0.9rem; font-weight: 500; transition: all 0.2s; white-space: nowrap; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.3rem; }
        .navbar-nav > li > a:hover, .navbar-nav > li > a.active, .navbar-nav > li > button:hover { color: var(--text-primary); background-color: var(--bg-card); }

        /* ── DROPDOWN MEGA MENU ── */
        .nav-dropdown { position: absolute; top: calc(100% + 0.5rem); left: 50%; transform: translateX(-50%); background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 12px; padding: 1rem; min-width: 220px; box-shadow: 0 20px 40px rgba(0,0,0,0.6); opacity: 0; visibility: hidden; transition: opacity 0.2s, visibility 0.2s, transform 0.2s; transform: translateX(-50%) translateY(-6px); z-index: 2000; }
        .navbar-nav > li:hover .nav-dropdown { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }
        .nav-dropdown a { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.625rem 0.75rem; border-radius: 8px; color: var(--text-secondary); text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: all 0.15s; }
        .nav-dropdown a:hover { background: var(--bg-hover); color: var(--text-primary); }
        .nav-dropdown a i { margin-top: 2px; font-size: 0.85rem; color: var(--text-muted); flex-shrink: 0; width: 16px; text-align: center; }
        .nav-dropdown a:hover i { color: var(--text-primary); }
        .nav-dropdown-divider { border: none; border-top: 1px solid var(--border); margin: 0.5rem 0; }
        .nav-dropdown-label { font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em; padding: 0.25rem 0.75rem; }

        /* ── BLOG MEGA MENU (wider) ── */
        .nav-mega { min-width: 520px; display: grid; grid-template-columns: 1fr 1fr; gap: 0.25rem; }

        /* ── HAMBURGER BUTTON ── */
        .navbar-toggler { display: none; background: none; border: 1px solid var(--border-light); border-radius: 6px; color: var(--text-primary); padding: 0.4rem 0.6rem; cursor: pointer; font-size: 1.1rem; line-height: 1; }
        .navbar-toggler:hover { background-color: var(--bg-card); }

        /* ── MOBILE MENU ── */
        .mobile-menu { display: none; position: fixed; top: 64px; left: 0; right: 0; bottom: 0; background-color: rgba(10,10,10,0.99); z-index: 999; padding: 1rem 1.25rem 2rem; overflow-y: auto; flex-direction: column; gap: 0; }
        .mobile-menu.open { display: flex; }
        .mobile-menu a.mob-link { color: var(--text-primary); text-decoration: none; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.95rem; font-weight: 500; display: flex; align-items: center; gap: 0.75rem; transition: background 0.2s; }
        .mobile-menu a.mob-link:hover { background-color: var(--bg-card); }
        .mob-accordion-btn { color: var(--text-primary); padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.95rem; font-weight: 500; border: none; background: none; cursor: pointer; text-align: left; width: 100%; display: flex; align-items: center; gap: 0.75rem; transition: background 0.2s; }
        .mob-accordion-btn:hover { background-color: var(--bg-card); }
        .mob-accordion-btn .mob-chevron { margin-left: auto; font-size: 0.75rem; transition: transform 0.25s; color: var(--text-muted); }
        .mob-accordion-btn.open .mob-chevron { transform: rotate(180deg); }
        .mob-accordion-panel { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        .mob-accordion-panel.open { max-height: 600px; }
        .mob-sub-link { color: var(--text-secondary) !important; text-decoration: none; padding: 0.6rem 1rem 0.6rem 2.75rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 0.65rem; transition: background 0.2s; }
        .mob-sub-link:hover { background-color: var(--bg-card); color: var(--text-primary) !important; }
        .mobile-divider { border: none; border-top: 1px solid var(--border); margin: 0.5rem 0; width: 100%; }
        .mobile-auth { display: flex; flex-direction: column; gap: 0.5rem; margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border); }
        .btn-mobile-primary { background-color: var(--text-primary) !important; color: var(--bg-primary) !important; border-radius: 8px; padding: 0.875rem 1rem !important; font-weight: 600 !important; justify-content: center !important; }
        .btn-mobile-outline { border: 1px solid var(--border-light) !important; border-radius: 8px; padding: 0.875rem 1rem !important; justify-content: center !important; }

        /* ── BUTTONS ── */
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1.25rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
        .btn-primary { background-color: var(--text-primary); color: var(--bg-primary); }
        .btn-primary:hover { background-color: #e0e0e0; color: var(--bg-primary); }
        .btn-outline { background-color: transparent; color: var(--text-primary); border: 1px solid var(--border-light); }
        .btn-outline:hover { background-color: var(--bg-card); }
        .btn-danger { background-color: var(--danger); color: white; }
        .btn-success { background-color: var(--success); color: white; }
        .btn-sm { padding: 0.3rem 0.75rem; font-size: 0.8rem; }

        /* ── COMPONENTS ── */
        .card { background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; }
        .alert { padding: 0.875rem 1.25rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-success { background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #86efac; }
        .alert-error { background-color: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.4rem; }
        .form-control { width: 100%; padding: 0.625rem 0.875rem; background-color: var(--bg-secondary); border: 1px solid var(--border-light); border-radius: 8px; color: var(--text-primary); font-size: 0.9rem; transition: border-color 0.2s; }
        .form-control:focus { outline: none; border-color: #555; }
        .form-control::placeholder { color: var(--text-muted); }
        textarea.form-control { resize: vertical; min-height: 120px; }
        select.form-control { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 12px; appearance: none; }
        .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-success { background: rgba(34,197,94,0.15); color: #86efac; }
        .badge-danger { background: rgba(239,68,68,0.15); color: #fca5a5; }
        .badge-warning { background: rgba(245,158,11,0.15); color: #fcd34d; }
        .badge-info { background: rgba(59,130,246,0.15); color: #93c5fd; }
        .badge-secondary { background: rgba(255,255,255,0.1); color: #a0a0a0; }
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .table th { text-align: left; padding: 0.75rem 1rem; background-color: var(--bg-secondary); color: var(--text-secondary); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); }
        .table td { padding: 0.875rem 1rem; border-bottom: 1px solid var(--border); color: var(--text-primary); }
        .table tr:hover td { background-color: var(--bg-hover); }

        /* ── FOOTER ── */
        .footer { background-color: var(--bg-secondary); border-top: 1px solid var(--border); padding: 3rem 2rem; margin-top: 5rem; }
        .footer-inner { max-width: 1200px; margin: 0 auto; }
        .social-links { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .social-link { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; background-color: var(--bg-card); border: 1px solid var(--border); color: var(--text-secondary); text-decoration: none; font-size: 1rem; transition: all 0.2s; }
        .social-link:hover { background-color: var(--text-primary); color: var(--bg-primary); border-color: var(--text-primary); }

        /* ── UTILITIES ── */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
        .mt-4 { margin-top: 1rem; } .mt-6 { margin-top: 1.5rem; } .mt-8 { margin-top: 2rem; }
        .mb-4 { margin-bottom: 1rem; } .mb-6 { margin-bottom: 1.5rem; } .mb-8 { margin-bottom: 2rem; }
        .flex { display: flex; } .items-center { align-items: center; } .justify-between { justify-content: space-between; }
        .gap-2 { gap: 0.5rem; } .gap-4 { gap: 1rem; }
        .text-sm { font-size: 0.875rem; } .text-xs { font-size: 0.75rem; }
        .text-muted { color: var(--text-muted); } .text-secondary { color: var(--text-secondary); }
        .font-bold { font-weight: 700; } .font-semibold { font-weight: 600; }
        .grid { display: grid; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        .grid-3 { grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .grid-4 { grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .navbar-nav { display: none; }
            .navbar-toggler { display: block; }
        }
        @media (max-width: 768px) {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
            .container { padding: 0 1rem; }
            .navbar { padding: 0 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            {{-- Brand / Logo — tenant-aware --}}
            <a href="{{ $tenantHomeUrl }}" class="navbar-brand" title="{{ $siteName }}">
                @if($layoutTenant && $layoutTenant->profile_photo)
                    <img src="{{ asset('storage/' . $layoutTenant->profile_photo) }}" alt="{{ $siteName }}" style="height:36px;width:36px;border-radius:50%;object-fit:cover;">
                @elseif(!$layoutTenant || $isMainDomain)
                    <img src="{{ asset('images/gopi-logo-transparent.png') }}" alt="Gopi K Logo" loading="eager" style="height:40px;width:auto;">
                @else
                    <span style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#a78bfa);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1rem;color:#fff;flex-shrink:0;">{{ strtoupper(substr($siteName,0,1)) }}</span>
                @endif
                <span style="font-size:0.95rem;font-weight:700;color:var(--text-primary);">{{ $siteName }}</span>
            </a>

            {{-- Desktop Nav --}}
            <ul class="navbar-nav">
                <li><a href="{{ $tenantHomeUrl }}" class="{{ request()->routeIs('home') || request()->routeIs('tenant.profile') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ $tenantAboutUrl }}" class="{{ request()->routeIs('about') || request()->routeIs('tenant.about') ? 'active' : '' }}">About</a></li>

                {{-- Solutions — only for Gopi (IT Professional) --}}
                @if(!$layoutTenant || $layoutTenant->email === 'gopi@outlook.in' || $tenantTemplate === 'entrepreneur' || $tenantTemplate === 'consultant')
                <li>
                    <button class="{{ request()->routeIs('solutions.*') ? 'active' : '' }}" type="button">
                        Solutions <i class="fas fa-chevron-down" style="font-size:0.7rem;"></i>
                    </button>
                    <div class="nav-dropdown">
                        <div class="nav-dropdown-label">What We Offer</div>
                        <a href="{{ route('solutions.ai-automation') }}"><i class="fas fa-robot"></i> AI Solutions & Automation</a>
                        <a href="{{ route('solutions.custom-app') }}"><i class="fas fa-laptop-code"></i> Custom Application Development</a>
                        <a href="{{ route('solutions.digital-transformation') }}"><i class="fas fa-sync-alt"></i> Digital Transformation</a>
                        <a href="{{ route('solutions.startup-product') }}"><i class="fas fa-rocket"></i> Startup Product Development</a>
                        <a href="{{ route('solutions.branding-digital') }}"><i class="fas fa-palette"></i> Branding & Digital Presence</a>
                    </div>
                </li>
                @endif

                {{-- Services — for Advocate --}}
                @if($tenantTemplate === 'advocate')
                <li><a href="{{ $tenantAboutUrl }}#services">Services</a></li>
                @endif

                {{-- Collaborations — for Influencer --}}
                @if($tenantTemplate === 'influencer')
                <li><a href="{{ $tenantAboutUrl }}#collaborations">Collaborations</a></li>
                @endif

                {{-- Blog --}}
                <li>
                    <a href="{{ $tenantBlogUrl }}" class="{{ request()->routeIs('blog*') || request()->routeIs('tenant.blog*') ? 'active' : '' }}">
                        Blog <i class="fas fa-chevron-down" style="font-size:0.7rem;"></i>
                    </a>
                    <div class="nav-dropdown nav-mega">
                        <div style="grid-column: 1/-1;"><div class="nav-dropdown-label">Browse by Category</div></div>
                        @if($tenantTemplate === 'influencer')
                            <a href="{{ $tenantBlogUrl }}?category=fashion"><i class="fas fa-tshirt"></i> Fashion & Style</a>
                            <a href="{{ $tenantBlogUrl }}?category=beauty"><i class="fas fa-spa"></i> Beauty & Skincare</a>
                            <a href="{{ $tenantBlogUrl }}?category=travel"><i class="fas fa-plane"></i> Travel</a>
                            <a href="{{ $tenantBlogUrl }}?category=lifestyle"><i class="fas fa-heart"></i> Lifestyle</a>
                        @elseif($tenantTemplate === 'advocate')
                            <a href="{{ $tenantBlogUrl }}?category=corporate-law"><i class="fas fa-building"></i> Corporate Law</a>
                            <a href="{{ $tenantBlogUrl }}?category=consumer-rights"><i class="fas fa-shield-alt"></i> Consumer Rights</a>
                            <a href="{{ $tenantBlogUrl }}?category=startup-legal"><i class="fas fa-rocket"></i> Startup Legal</a>
                            <a href="{{ $tenantBlogUrl }}?category=ip-rights"><i class="fas fa-copyright"></i> IP Rights</a>
                        @else
                            <a href="{{ $tenantBlogUrl }}?category=ai-automation"><i class="fas fa-robot"></i> AI & Automation</a>
                            <a href="{{ $tenantBlogUrl }}?category=hacking-security"><i class="fas fa-shield-alt"></i> Hacking & Security</a>
                            <a href="{{ $tenantBlogUrl }}?category=startup-product"><i class="fas fa-rocket"></i> Startup & Product Dev</a>
                            <a href="{{ $tenantBlogUrl }}?category=software-technology"><i class="fas fa-code"></i> Software & Technology</a>
                            <a href="{{ $tenantBlogUrl }}?category=digital-transformation"><i class="fas fa-chart-line"></i> Digital Transformation</a>
                            <a href="{{ $tenantBlogUrl }}?category=personal-branding"><i class="fas fa-user-tie"></i> Personal Branding</a>
                        @endif
                        <div style="grid-column: 1/-1;"><hr class="nav-dropdown-divider"></div>
                        <div style="grid-column: 1/-1;"><a href="{{ $tenantBlogUrl }}" style="font-weight:600;"><i class="fas fa-th-large"></i> View All Posts</a></div>
                    </div>
                </li>

                <li><a href="{{ $tenantJobsUrl }}" class="{{ request()->routeIs('jobs*') || request()->routeIs('tenant.jobs*') ? 'active' : '' }}">
                    {{ $tenantTemplate === 'advocate' ? 'Vacancies' : 'Jobs' }}
                </a></li>
                <li><a href="{{ route('forum.index') }}" class="{{ request()->routeIs('forum*') ? 'active' : '' }}">Forum</a></li>
                <li><a href="{{ $tenantShopUrl }}" class="{{ request()->routeIs('shop*') || request()->routeIs('tenant.shop*') ? 'active' : '' }}">Shop</a></li>

                @auth
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">Dashboard</a></li>
                    @elseif(auth()->user()->isStaff())
                        <li><a href="{{ route('staff.dashboard') }}">Staff Panel</a></li>
                    @else
                        <li><a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                    @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm">Sign Out</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ $tenantLoginUrl }}">Sign In</a></li>
                    @if($isMainDomain)
                    <li><a href="{{ route('register') }}" class="btn btn-primary btn-sm">Sign Up</a></li>
                    @endif
                @endauth
            </ul>

            {{-- Mobile Hamburger --}}
            <button class="navbar-toggler" id="navToggler" aria-label="Toggle navigation" aria-expanded="false">
                <i class="fas fa-bars" id="navIcon"></i>
            </button>
        </div>
    </nav>

    {{-- Mobile Slide-down Menu --}}
    <div class="mobile-menu" id="mobileMenu">

        <a href="{{ $tenantHomeUrl }}" class="mob-link">
            <i class="fas fa-home" style="width:20px;color:var(--text-muted);"></i> Home
        </a>
        <a href="{{ $tenantAboutUrl }}" class="mob-link">
            <i class="fas fa-user" style="width:20px;color:var(--text-muted);"></i> About
        </a>

        {{-- Solutions accordion — only for IT/Entrepreneur --}}
        @if(!$layoutTenant || $layoutTenant->email === 'gopi@outlook.in' || $tenantTemplate === 'entrepreneur' || $tenantTemplate === 'consultant')
        <button class="mob-accordion-btn" onclick="toggleAccordion('mobSolutions', this)">
            <i class="fas fa-lightbulb" style="width:20px;color:var(--text-muted);"></i>
            Solutions
            <i class="fas fa-chevron-down mob-chevron"></i>
        </button>
        <div class="mob-accordion-panel" id="mobSolutions">
            <a href="{{ route('solutions.ai-automation') }}" class="mob-sub-link"><i class="fas fa-robot" style="width:16px;"></i> AI Solutions & Automation</a>
            <a href="{{ route('solutions.custom-app') }}" class="mob-sub-link"><i class="fas fa-laptop-code" style="width:16px;"></i> Custom App Development</a>
            <a href="{{ route('solutions.digital-transformation') }}" class="mob-sub-link"><i class="fas fa-sync-alt" style="width:16px;"></i> Digital Transformation</a>
            <a href="{{ route('solutions.startup-product') }}" class="mob-sub-link"><i class="fas fa-rocket" style="width:16px;"></i> Startup Product Dev</a>
            <a href="{{ route('solutions.branding-digital') }}" class="mob-sub-link"><i class="fas fa-palette" style="width:16px;"></i> Branding & Digital Presence</a>
        </div>
        @endif

        {{-- Blog accordion --}}
        <button class="mob-accordion-btn" onclick="toggleAccordion('mobBlog', this)">
            <i class="fas fa-pen-nib" style="width:20px;color:var(--text-muted);"></i>
            Blog
            <i class="fas fa-chevron-down mob-chevron"></i>
        </button>
        <div class="mob-accordion-panel" id="mobBlog">
            <a href="{{ $tenantBlogUrl }}" class="mob-sub-link"><i class="fas fa-th-large" style="width:16px;"></i> All Posts</a>
            @if($tenantTemplate === 'influencer')
                <a href="{{ $tenantBlogUrl }}?category=fashion" class="mob-sub-link"><i class="fas fa-tshirt" style="width:16px;"></i> Fashion & Style</a>
                <a href="{{ $tenantBlogUrl }}?category=beauty" class="mob-sub-link"><i class="fas fa-spa" style="width:16px;"></i> Beauty & Skincare</a>
                <a href="{{ $tenantBlogUrl }}?category=travel" class="mob-sub-link"><i class="fas fa-plane" style="width:16px;"></i> Travel</a>
            @elseif($tenantTemplate === 'advocate')
                <a href="{{ $tenantBlogUrl }}?category=corporate-law" class="mob-sub-link"><i class="fas fa-building" style="width:16px;"></i> Corporate Law</a>
                <a href="{{ $tenantBlogUrl }}?category=consumer-rights" class="mob-sub-link"><i class="fas fa-shield-alt" style="width:16px;"></i> Consumer Rights</a>
                <a href="{{ $tenantBlogUrl }}?category=startup-legal" class="mob-sub-link"><i class="fas fa-rocket" style="width:16px;"></i> Startup Legal</a>
            @else
                <a href="{{ $tenantBlogUrl }}?category=ai-automation" class="mob-sub-link"><i class="fas fa-robot" style="width:16px;"></i> AI & Automation</a>
                <a href="{{ $tenantBlogUrl }}?category=hacking-security" class="mob-sub-link"><i class="fas fa-shield-alt" style="width:16px;"></i> Hacking & Security</a>
                <a href="{{ $tenantBlogUrl }}?category=startup-product" class="mob-sub-link"><i class="fas fa-rocket" style="width:16px;"></i> Startup & Product Dev</a>
            @endif
        </div>

        <hr class="mobile-divider">
        <a href="{{ $tenantJobsUrl }}" class="mob-link">
            <i class="fas fa-briefcase" style="width:20px;color:var(--text-muted);"></i>
            {{ $tenantTemplate === 'advocate' ? 'Vacancies' : 'Jobs' }}
        </a>
        <a href="{{ route('forum.index') }}" class="mob-link">
            <i class="fas fa-comments" style="width:20px;color:var(--text-muted);"></i> Forum
        </a>
        <a href="{{ $tenantShopUrl }}" class="mob-link">
            <i class="fas fa-shopping-bag" style="width:20px;color:var(--text-muted);"></i> Shop
        </a>

        @auth
            <hr class="mobile-divider">
            @if(auth()->user()->isAdmin())
                <button class="mob-accordion-btn" onclick="toggleAccordion('mobAdminDash', this)">
                    <i class="fas fa-tachometer-alt" style="width:20px;color:var(--text-muted);"></i>
                    Admin Dashboard
                    <i class="fas fa-chevron-down mob-chevron"></i>
                </button>
                <div class="mob-accordion-panel" id="mobAdminDash">
                    <a href="{{ route('admin.dashboard') }}" class="mob-sub-link"><i class="fas fa-home" style="width:16px;"></i> Overview</a>
                    <a href="{{ route('admin.blog.index') }}" class="mob-sub-link"><i class="fas fa-pen-nib" style="width:16px;"></i> Blog Posts</a>
                    <a href="{{ route('admin.jobs.index') }}" class="mob-sub-link"><i class="fas fa-briefcase" style="width:16px;"></i> Job Listings</a>
                    <a href="{{ route('admin.expenses.index') }}" class="mob-sub-link"><i class="fas fa-wallet" style="width:16px;"></i> Expenses</a>
                    <a href="{{ route('admin.users.index') }}" class="mob-sub-link"><i class="fas fa-users" style="width:16px;"></i> Users</a>
                    <a href="{{ route('admin.crm.leads') }}" class="mob-sub-link"><i class="fas fa-handshake" style="width:16px;"></i> CRM Leads</a>
                    <a href="{{ route('admin.crm.training') }}" class="mob-sub-link"><i class="fas fa-robot" style="width:16px;"></i> AI Training</a>
                    <a href="{{ route('admin.ecommerce.dashboard') }}" class="mob-sub-link"><i class="fas fa-store" style="width:16px;"></i> E-commerce</a>
                    <a href="{{ route('admin.settings.index') }}" class="mob-sub-link"><i class="fas fa-cog" style="width:16px;"></i> Site Settings</a>
                </div>
            @elseif(auth()->user()->isStaff())
                <a href="{{ route('staff.dashboard') }}" class="mob-link">
                    <i class="fas fa-user-tie" style="width:20px;color:var(--text-muted);"></i> Staff Panel
                </a>
            @else
                <button class="mob-accordion-btn" onclick="toggleAccordion('mobUserDash', this)">
                    <i class="fas fa-th-large" style="width:20px;color:var(--text-muted);"></i>
                    My Dashboard
                    <i class="fas fa-chevron-down mob-chevron"></i>
                </button>
                <div class="mob-accordion-panel" id="mobUserDash">
                    <a href="{{ route('user.dashboard') }}" class="mob-sub-link"><i class="fas fa-home" style="width:16px;"></i> Overview</a>
                    <a href="{{ route('calendar.index') }}" class="mob-sub-link"><i class="fas fa-calendar-alt" style="width:16px;"></i> Calendar</a>
                    <a href="{{ route('chat.index') }}" class="mob-sub-link"><i class="fas fa-comment-dots" style="width:16px;"></i> Chat</a>
                    <a href="{{ route('forum.index') }}" class="mob-sub-link"><i class="fas fa-comments" style="width:16px;"></i> Forum</a>
                    <a href="{{ route('profile.edit') }}" class="mob-sub-link"><i class="fas fa-user-cog" style="width:16px;"></i> Profile</a>
                </div>
            @endif
            <hr class="mobile-divider">
            <form method="POST" action="{{ route('logout') }}" style="width:100%;">
                @csrf
                <button type="submit" style="color:#fca5a5;width:100%;display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;background:none;border:none;cursor:pointer;font-size:0.95rem;border-radius:8px;">
                    <i class="fas fa-sign-out-alt" style="width:20px;"></i> Sign Out
                </button>
            </form>
        @else
            <hr class="mobile-divider">
            <div class="mobile-auth">
                <a href="{{ $tenantLoginUrl }}" class="mob-link btn-mobile-outline">
                    <i class="fas fa-sign-in-alt" style="width:20px;"></i> Sign In
                </a>
                @if($isMainDomain)
                <a href="{{ route('register') }}" class="mob-link btn-mobile-primary">
                    <i class="fas fa-user-plus" style="width:20px;"></i> Sign Up
                </a>
                @endif
            </div>
        @endauth
    </div>

    @if(session('success'))
        <div class="container mt-4"><div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div></div>
    @endif
    @if(session('error'))
        <div class="container mt-4"><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div></div>
    @endif
    @if(session('newsletter_success'))
        <div class="container mt-4"><div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('newsletter_success') }}</div></div>
    @endif

    <main>@yield('content')</main>

    {{-- ── FOOTER ── --}}
    <footer class="footer">
        <div class="footer-inner">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1.6fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    @if($layoutTenant && $layoutTenant->profile_photo)
                        <img src="{{ asset('storage/' . $layoutTenant->profile_photo) }}" alt="{{ $siteName }}" style="height: 44px; width: 44px; border-radius: 50%; object-fit: cover; margin-bottom: 0.75rem; display: block;">
                    @elseif(!$layoutTenant || $isMainDomain)
                        <img src="{{ asset('images/gopi-logo-transparent.png') }}" alt="Gopi K" style="height: 44px; width: auto; margin-bottom: 0.75rem; display: block;">
                    @else
                        <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#a78bfa);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.2rem;color:#fff;margin-bottom:0.75rem;">{{ strtoupper(substr($siteName,0,1)) }}</div>
                    @endif
                    <p class="text-secondary text-sm">{{ $footerTagline ?: 'Professional portfolio powered by Xenoraa.' }}</p>
                </div>
                <div>
                    <h4 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Quick Links</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                        <a href="{{ $tenantHomeUrl }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">Home</a>
                        <a href="{{ $tenantAboutUrl }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">About</a>
                        <a href="{{ $tenantBlogUrl }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">Blog</a>
                        <a href="{{ $tenantJobsUrl }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">{{ $tenantTemplate === 'advocate' ? 'Vacancies' : 'Jobs' }}</a>
                    </div>
                </div>
                <div>
                    <h4 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Connect</h4>
                    <div class="social-links">
                        @foreach($footerSocials as $social)
                        <a href="{{ $social->url }}" class="social-link" target="_blank" rel="noopener" title="{{ ucfirst($social->platform) }}">
                            <i class="{{ $social->icon_class }}"></i>
                        </a>
                        @endforeach
                    </div>
                    <p class="text-sm text-muted" style="margin-top: 1rem;">
                        @if(!empty($tenantSettings['contact_phone']))<i class="fas fa-phone" style="margin-right: 0.4rem;"></i>{{ $tenantSettings['contact_phone'] }}<br>@endif
                        @if(!empty($tenantSettings['contact_email']))<i class="fas fa-envelope" style="margin-right: 0.4rem;"></i>{{ $tenantSettings['contact_email'] }}@endif
                    </p>
                </div>
                <div>
                    <x-newsletter-subscribe variant="compact" />
                </div>
            </div>
            <div style="border-top: 1px solid var(--border); padding-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <p class="text-muted text-sm">&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved. &mdash; Powered by <a href="https://xenoraa.com" style="color:var(--text-muted);">Xenoraa</a></p>
                <div style="display:flex;gap:1rem;">
                    <a href="{{ route('legal.privacy') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.875rem;">Privacy Policy</a>
                    <a href="{{ route('legal.terms') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.875rem;">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- AI Chatbot Widget — only if enabled for this tenant --}}
    @php
        $chatbotEnabled = $chatbotEnabled ?? \App\Models\SiteSetting::getValueForTenant($layoutTenant?->id ?? 0, 'chatbot_enabled', '1');
    @endphp
    @if($chatbotEnabled == '1' || $chatbotEnabled === true)
        @include('components.chatbot-widget')
    @endif

    @stack('scripts')
    <script>
        const toggler = document.getElementById('navToggler');
        const mobileMenu = document.getElementById('mobileMenu');
        const navIcon = document.getElementById('navIcon');

        toggler.addEventListener('click', function() {
            const isOpen = mobileMenu.classList.toggle('open');
            navIcon.className = isOpen ? 'fas fa-times' : 'fas fa-bars';
            toggler.setAttribute('aria-expanded', isOpen);
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });

        mobileMenu.querySelectorAll('a.mob-link, a.mob-sub-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                navIcon.className = 'fas fa-bars';
                document.body.style.overflow = '';
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024) {
                mobileMenu.classList.remove('open');
                navIcon.className = 'fas fa-bars';
                document.body.style.overflow = '';
            }
        });

        function toggleAccordion(panelId, btn) {
            const panel = document.getElementById(panelId);
            const isOpen = panel.classList.contains('open');
            document.querySelectorAll('.mob-accordion-panel').forEach(p => p.classList.remove('open'));
            document.querySelectorAll('.mob-accordion-btn').forEach(b => b.classList.remove('open'));
            if (!isOpen) {
                panel.classList.add('open');
                btn.classList.add('open');
            }
        }
    </script>
</body>
</html>
