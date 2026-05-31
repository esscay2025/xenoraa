<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gopi K | Portfolio')</title>
    <meta name="description" content="@yield('description', 'Founder of Go Esscay Solutions | IT, Automation & Open-Source Expert | Greater Chennai Area')">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon-64.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
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
        .navbar-brand { display: flex; align-items: center; text-decoration: none; flex-shrink: 0; }
        .navbar-brand img { height: 36px; width: auto; display: block; }
        .navbar-nav { display: flex; align-items: center; gap: 0.25rem; list-style: none; margin: 0; padding: 0; }
        .navbar-nav a { color: var(--text-secondary); text-decoration: none; padding: 0.5rem 0.75rem; border-radius: 6px; font-size: 0.9rem; font-weight: 500; transition: all 0.2s; white-space: nowrap; }
        .navbar-nav a:hover, .navbar-nav a.active { color: var(--text-primary); background-color: var(--bg-card); }

        /* ── HAMBURGER BUTTON ── */
        .navbar-toggler { display: none; background: none; border: 1px solid var(--border-light); border-radius: 6px; color: var(--text-primary); padding: 0.4rem 0.6rem; cursor: pointer; font-size: 1.1rem; line-height: 1; }
        .navbar-toggler:hover { background-color: var(--bg-card); }

        /* ── MOBILE MENU ── */
        .mobile-menu { display: none; position: fixed; top: 64px; left: 0; right: 0; bottom: 0; background-color: rgba(10,10,10,0.98); z-index: 999; padding: 1.5rem 2rem; overflow-y: auto; flex-direction: column; gap: 0.5rem; }
        .mobile-menu.open { display: flex; }
        .mobile-menu a, .mobile-menu button { color: var(--text-primary); text-decoration: none; padding: 0.875rem 1rem; border-radius: 8px; font-size: 1rem; font-weight: 500; border: none; background: none; cursor: pointer; text-align: left; width: 100%; display: flex; align-items: center; gap: 0.75rem; transition: background 0.2s; }
        .mobile-menu a:hover, .mobile-menu button:hover { background-color: var(--bg-card); }
        .mobile-menu .mobile-divider { border: none; border-top: 1px solid var(--border); margin: 0.5rem 0; }
        .mobile-menu .mobile-auth { display: flex; flex-direction: column; gap: 0.5rem; margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border); }
        .mobile-menu .btn-mobile-primary { background-color: var(--text-primary); color: var(--bg-primary); border-radius: 8px; padding: 0.875rem 1rem; font-weight: 600; justify-content: center; }
        .mobile-menu .btn-mobile-outline { border: 1px solid var(--border-light); border-radius: 8px; padding: 0.875rem 1rem; justify-content: center; }

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
        @media (max-width: 768px) {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
            .navbar-nav { display: none; }
            .navbar-toggler { display: block; }
            .container { padding: 0 1rem; }
            .navbar { padding: 0 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="navbar-brand" title="Gopi K | Home">
                <img src="{{ asset('images/gopi-logo-transparent.png') }}" alt="Gopi K Logo" loading="eager" style="height:40px;width:auto;">
            </a>

            {{-- Desktop Nav --}}
            <ul class="navbar-nav">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                <li><a href="{{ route('blog') }}" class="{{ request()->routeIs('blog*') ? 'active' : '' }}">Blog</a></li>
                <li><a href="{{ route('jobs') }}" class="{{ request()->routeIs('jobs*') ? 'active' : '' }}">Jobs</a></li>
                @auth
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">Dashboard</a></li>
                    @elseif(auth()->user()->isStaff())
                        <li><a href="{{ route('staff.dashboard') }}">Staff Panel</a></li>
                    @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm">Sign Out</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Sign In</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn-primary btn-sm">Sign Up</a></li>
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
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home" style="width:20px;"></i> Home
        </a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
            <i class="fas fa-user" style="width:20px;"></i> About
        </a>
        <a href="{{ route('blog') }}" class="{{ request()->routeIs('blog*') ? 'active' : '' }}">
            <i class="fas fa-pen-nib" style="width:20px;"></i> Blog
        </a>
        <a href="{{ route('jobs') }}" class="{{ request()->routeIs('jobs*') ? 'active' : '' }}">
            <i class="fas fa-briefcase" style="width:20px;"></i> Jobs
        </a>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt" style="width:20px;"></i> Admin Dashboard
                </a>
            @elseif(auth()->user()->isStaff())
                <a href="{{ route('staff.dashboard') }}">
                    <i class="fas fa-user-tie" style="width:20px;"></i> Staff Panel
                </a>
            @endif
            <hr class="mobile-divider">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="color:#fca5a5;">
                    <i class="fas fa-sign-out-alt" style="width:20px;"></i> Sign Out
                </button>
            </form>
        @else
            <hr class="mobile-divider">
            <div class="mobile-auth">
                <a href="{{ route('login') }}" class="btn-mobile-outline">
                    <i class="fas fa-sign-in-alt" style="width:20px;"></i> Sign In
                </a>
                <a href="{{ route('register') }}" class="btn-mobile-primary">
                    <i class="fas fa-user-plus" style="width:20px;"></i> Sign Up
                </a>
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

    <footer class="footer">
        <div class="footer-inner">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1.6fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <img src="{{ asset('images/gopi-logo-transparent.png') }}" alt="Gopi K" style="height: 44px; width: auto; margin-bottom: 0.75rem; display: block;">
                    @php $siteSettings = \App\Models\SiteSetting::getSettings(); @endphp
                    <p class="text-secondary text-sm">{{ $siteSettings['footer_tagline'] ?? 'Founder of Go Esscay Solutions. Passionate about creating impact through technology, automation, and open-source solutions.' }}</p>
                </div>
                <div>
                    <h4 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Quick Links</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                        <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">Home</a>
                        <a href="{{ route('about') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">About</a>
                        <a href="{{ route('blog') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">Blog</a>
                        <a href="{{ route('jobs') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">Jobs</a>
                    </div>
                </div>
                <div>
                    <h4 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Connect</h4>
                    <div class="social-links">
                        @php $footerSocials = \App\Models\SocialLink::where('is_active', true)->get(); @endphp
                        @foreach($footerSocials as $social)
                        <a href="{{ $social->url }}" class="social-link" target="_blank" rel="noopener" title="{{ ucfirst($social->platform) }}">
                            <i class="{{ $social->icon_class }}"></i>
                        </a>
                        @endforeach
                    </div>
                    <p class="text-sm text-muted" style="margin-top: 1rem;">
                        @if(!empty($siteSettings['contact_phone']))<i class="fas fa-phone" style="margin-right: 0.4rem;"></i>{{ $siteSettings['contact_phone'] }}<br>@endif
                        @if(!empty($siteSettings['contact_website']))<i class="fas fa-globe" style="margin-right: 0.4rem;"></i>{{ $siteSettings['contact_website'] }}@endif
                    </p>
                </div>
                <div>
                    <x-newsletter-subscribe variant="compact" />
                </div>
            </div>
            <div style="border-top: 1px solid var(--border); padding-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <p class="text-muted text-sm">&copy; {{ date('Y') }} {{ $siteSettings['owner_name'] ?? 'Gopi K' }} &mdash; {{ $siteSettings['company_name'] ?? 'Go Esscay Solutions' }}. All rights reserved.</p>
                <p class="text-muted text-sm">{{ $siteSettings['location'] ?? 'Greater Chennai Area, India' }}</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script>
        // Mobile menu toggle
        const toggler = document.getElementById('navToggler');
        const mobileMenu = document.getElementById('mobileMenu');
        const navIcon = document.getElementById('navIcon');

        toggler.addEventListener('click', function() {
            const isOpen = mobileMenu.classList.toggle('open');
            navIcon.className = isOpen ? 'fas fa-times' : 'fas fa-bars';
            toggler.setAttribute('aria-expanded', isOpen);
            // Prevent body scroll when menu is open
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });

        // Close mobile menu when a link is clicked
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                navIcon.className = 'fas fa-bars';
                document.body.style.overflow = '';
            });
        });

        // Close on resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                mobileMenu.classList.remove('open');
                navIcon.className = 'fas fa-bars';
                document.body.style.overflow = '';
            }
        });
    </script>
</body>
</html>
