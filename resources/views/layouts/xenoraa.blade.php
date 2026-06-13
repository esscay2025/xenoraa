<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $xnSeo = \DB::table('site_settings')->whereNull('user_id')->pluck('value','key')->toArray();
        $xnGtagId      = $xnSeo['google_tag_id'] ?? 'G-SKMW277LED';
        $xnGtagEnabled = ($xnSeo['google_tag_enabled'] ?? '1') === '1';
        $xnGtagFull    = str_starts_with($xnGtagId, 'G-') ? $xnGtagId : 'G-'.$xnGtagId;
    @endphp
    <title>@yield('title', $xnSeo['seo_meta_title'] ?? 'Xenoraa — Build Your Digital Identity')</title>
    <meta name="description" content="@yield('meta_description', $xnSeo['seo_meta_description'] ?? 'Xenoraa is the all-in-one platform for professionals to build their digital identity.')">
    @if(!empty($xnSeo['seo_meta_keywords']))
    <meta name="keywords" content="{{ $xnSeo['seo_meta_keywords'] }}">
    @endif
    @if(!empty($xnSeo['seo_robots']))
    <meta name="robots" content="{{ $xnSeo['seo_robots'] }}">
    @endif
    @if(!empty($xnSeo['seo_canonical_url']))
    <link rel="canonical" href="{{ $xnSeo['seo_canonical_url'] }}">
    @endif
    {{-- Open Graph --}}
    <meta property="og:type"        content="{{ $xnSeo['og_type'] ?? 'website' }}">
    <meta property="og:site_name"   content="{{ $xnSeo['og_site_name'] ?? 'Xenoraa' }}">
    <meta property="og:title"       content="@yield('og_title', $xnSeo['og_title'] ?? 'Xenoraa — Build Your Digital Identity')">
    <meta property="og:description" content="@yield('og_description', $xnSeo['og_description'] ?? 'Xenoraa is the all-in-one SaaS platform for professionals.')">
    @if(!empty($xnSeo['og_image']))
    <meta property="og:image" content="{{ $xnSeo['og_image'] }}">
    @endif
    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="{{ $xnSeo['twitter_card'] ?? 'summary_large_image' }}">
    @if(!empty($xnSeo['twitter_site']))
    <meta name="twitter:site"        content="{{ $xnSeo['twitter_site'] }}">
    @endif
    <meta name="twitter:title"       content="@yield('twitter_title', $xnSeo['twitter_title'] ?? 'Xenoraa — Build Your Digital Identity')">
    <meta name="twitter:description" content="@yield('twitter_description', $xnSeo['twitter_description'] ?? 'Xenoraa is the all-in-one SaaS platform for professionals.')">
    @if(!empty($xnSeo['twitter_image']))
    <meta name="twitter:image" content="{{ $xnSeo['twitter_image'] }}">
    @endif
    {{-- Google Tag (gtag.js) --}}
    @if($xnGtagEnabled && !empty($xnGtagFull))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $xnGtagFull }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ $xnGtagFull }}');
    </script>
    @endif
    <link rel="icon" type="image/png" href="/images/xenoraa/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --xn-black: #000000;
            --xn-dark: #0a0a0a;
            --xn-card: #111111;
            --xn-card2: #161616;
            --xn-border: #222222;
            --xn-border2: #2a2a2a;
            --xn-purple: #7c3aed;
            --xn-purple-light: #a855f7;
            --xn-purple-bright: #c084fc;
            --xn-purple-glow: rgba(124, 58, 237, 0.15);
            --xn-white: #ffffff;
            --xn-gray: #a1a1aa;
            --xn-gray2: #71717a;
            --xn-accent: #8b5cf6;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--xn-black);
            color: var(--xn-white);
            line-height: 1.6;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, h5 { font-family: 'Space Grotesk', sans-serif; }

        /* ===== NAVIGATION ===== */
        .xn-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 4rem;
            height: 72px;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            transition: all 0.3s ease;
        }
        .xn-nav.scrolled { background: rgba(0,0,0,0.97); }
        .xn-nav-logo {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .xn-nav-logo img { height: 32px; }
        .xn-nav-logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.4rem; font-weight: 700;
            color: var(--xn-white);
            letter-spacing: -0.02em;
        }
        .xn-nav-links {
            display: flex; align-items: center; gap: 2.5rem;
            list-style: none;
        }
        .xn-nav-links a {
            color: var(--xn-gray); text-decoration: none;
            font-size: 0.875rem; font-weight: 500;
            letter-spacing: 0.01em;
            transition: color 0.2s;
            position: relative;
        }
        .xn-nav-links a:hover, .xn-nav-links a.active { color: var(--xn-white); }
        .xn-nav-links a.active::after {
            content: ''; position: absolute; bottom: -4px; left: 0; right: 0;
            height: 2px; background: var(--xn-purple-light);
        }
        .xn-nav-actions { display: flex; align-items: center; gap: 1rem; }
        .xn-btn-ghost {
            color: var(--xn-white); text-decoration: none;
            font-size: 0.875rem; font-weight: 500;
            padding: 0.5rem 1.25rem;
            border: 1px solid var(--xn-border2);
            border-radius: 6px;
            transition: all 0.2s;
            background: transparent;
            cursor: pointer;
        }
        .xn-btn-ghost:hover { border-color: var(--xn-purple-light); color: var(--xn-purple-bright); }
        .xn-btn-primary {
            background: var(--xn-purple);
            color: var(--xn-white); text-decoration: none;
            font-size: 0.875rem; font-weight: 600;
            padding: 0.55rem 1.5rem;
            border-radius: 6px;
            border: none; cursor: pointer;
            transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .xn-btn-primary:hover { background: #6d28d9; transform: translateY(-1px); }
        .xn-btn-primary-lg {
            background: var(--xn-purple);
            color: var(--xn-white); text-decoration: none;
            font-size: 1rem; font-weight: 600;
            padding: 0.875rem 2.5rem;
            border-radius: 8px;
            border: none; cursor: pointer;
            transition: all 0.25s;
            display: inline-flex; align-items: center; gap: 0.75rem;
        }
        .xn-btn-primary-lg:hover { background: #6d28d9; transform: translateY(-2px); box-shadow: 0 20px 40px rgba(124,58,237,0.3); }
        .xn-btn-outline-lg {
            background: transparent;
            color: var(--xn-white); text-decoration: none;
            font-size: 1rem; font-weight: 600;
            padding: 0.875rem 2.5rem;
            border-radius: 8px;
            border: 1px solid var(--xn-border2);
            cursor: pointer;
            transition: all 0.25s;
            display: inline-flex; align-items: center; gap: 0.75rem;
        }
        .xn-btn-outline-lg:hover { border-color: var(--xn-purple-light); color: var(--xn-purple-bright); }

        /* ===== HAMBURGER MOBILE ===== */
        .xn-hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 4px; }
        .xn-hamburger span { display: block; width: 24px; height: 2px; background: var(--xn-white); transition: all 0.3s; }

        /* ===== SECTIONS ===== */
        .xn-section { padding: 6rem 4rem; }
        .xn-section-sm { padding: 4rem 4rem; }
        .xn-container { max-width: 1280px; margin: 0 auto; }
        .xn-label {
            display: inline-block;
            font-size: 0.7rem; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--xn-purple-light);
            margin-bottom: 1rem;
        }
        .xn-label::before { content: '> '; color: var(--xn-purple); }
        .xn-heading-xl {
            font-size: clamp(2.5rem, 5vw, 5rem);
            font-weight: 800; line-height: 1.05;
            letter-spacing: -0.03em;
            color: var(--xn-white);
        }
        .xn-heading-lg {
            font-size: clamp(2rem, 3.5vw, 3.5rem);
            font-weight: 800; line-height: 1.1;
            letter-spacing: -0.025em;
            color: var(--xn-white);
        }
        .xn-heading-md {
            font-size: clamp(1.5rem, 2.5vw, 2.25rem);
            font-weight: 700; line-height: 1.2;
            letter-spacing: -0.02em;
        }
        .xn-purple-text { color: var(--xn-purple-light); }
        .xn-body { font-size: 1rem; color: var(--xn-gray); line-height: 1.7; }
        .xn-body-lg { font-size: 1.125rem; color: var(--xn-gray); line-height: 1.75; }
        .xn-divider { width: 48px; height: 3px; background: var(--xn-purple); margin: 1.5rem 0; }

        /* ===== CARDS ===== */
        .xn-card {
            background: var(--xn-card);
            border: 1px solid var(--xn-border);
            border-radius: 12px;
            padding: 2rem;
            transition: all 0.3s;
        }
        .xn-card:hover { border-color: var(--xn-purple); transform: translateY(-4px); box-shadow: 0 20px 60px rgba(124,58,237,0.1); }
        .xn-card-icon {
            width: 52px; height: 52px;
            background: var(--xn-purple-glow);
            border: 1px solid rgba(124,58,237,0.3);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem; color: var(--xn-purple-light);
            margin-bottom: 1.25rem;
        }

        /* ===== GRID ===== */
        .xn-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; }
        .xn-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }
        .xn-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }

        /* ===== FOOTER ===== */
        .xn-footer {
            background: var(--xn-dark);
            border-top: 1px solid var(--xn-border);
            padding: 4rem 4rem 2rem;
        }
        .xn-footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem; margin-bottom: 3rem;
        }
        .xn-footer-logo { font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; }
        .xn-footer-desc { font-size: 0.875rem; color: var(--xn-gray2); line-height: 1.7; margin-bottom: 1.5rem; }
        .xn-footer-heading { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--xn-gray); margin-bottom: 1.25rem; }
        .xn-footer-links { list-style: none; display: flex; flex-direction: column; gap: 0.75rem; }
        .xn-footer-links a { color: var(--xn-gray2); text-decoration: none; font-size: 0.875rem; transition: color 0.2s; }
        .xn-footer-links a:hover { color: var(--xn-white); }
        .xn-footer-bottom { border-top: 1px solid var(--xn-border); padding-top: 2rem; display: flex; justify-content: space-between; align-items: center; }
        .xn-footer-copy { font-size: 0.8rem; color: var(--xn-gray2); }
        .xn-social-links { display: flex; gap: 1rem; }
        .xn-social-links a {
            width: 36px; height: 36px;
            border: 1px solid var(--xn-border2);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: var(--xn-gray2); text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        .xn-social-links a:hover { border-color: var(--xn-purple-light); color: var(--xn-purple-light); }

        /* ===== BADGE ===== */
        .xn-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(124,58,237,0.1);
            border: 1px solid rgba(124,58,237,0.3);
            color: var(--xn-purple-bright);
            font-size: 0.75rem; font-weight: 600;
            padding: 0.35rem 0.875rem;
            border-radius: 100px;
            margin-bottom: 1.5rem;
        }
        .xn-badge-dot { width: 6px; height: 6px; background: var(--xn-purple-light); border-radius: 50%; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

        /* ===== MOBILE MENU ===== */
        .xn-mobile-menu {
            display: none; position: fixed; top: 72px; left: 0; right: 0;
            background: rgba(0,0,0,0.98); backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--xn-border);
            padding: 1.5rem 2rem; z-index: 999;
            flex-direction: column; gap: 0;
        }
        .xn-mobile-menu.open { display: flex; }
        .xn-mobile-menu a {
            color: var(--xn-gray); text-decoration: none;
            font-size: 1rem; font-weight: 500;
            padding: 0.875rem 0;
            border-bottom: 1px solid var(--xn-border);
            transition: color 0.2s;
        }
        .xn-mobile-menu a:last-child { border-bottom: none; }
        .xn-mobile-menu a:hover { color: var(--xn-white); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .xn-nav { padding: 0 2rem; }
            .xn-section { padding: 4rem 2rem; }
            .xn-footer { padding: 3rem 2rem 1.5rem; }
            .xn-footer-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
            .xn-grid-4 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .xn-nav-links, .xn-nav-actions { display: none; }
            .xn-hamburger { display: flex; }
            .xn-section { padding: 3rem 1.5rem; }
            .xn-grid-2, .xn-grid-3, .xn-grid-4 { grid-template-columns: 1fr; }
            .xn-footer-grid { grid-template-columns: 1fr; }
            .xn-footer-bottom { flex-direction: column; gap: 1rem; text-align: center; }
        }
    </style>
    @yield('styles')
    {{-- Custom head scripts from SEO settings --}}
    @if(!empty($xnSeo['custom_head_scripts']))
    {!! $xnSeo['custom_head_scripts'] !!}
    @endif
    {{-- Schema.org JSON-LD --}}
    @if(!empty($xnSeo['schema_org_name'] ?? ''))
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "{{ $xnSeo['schema_org_type'] ?? 'Organization' }}",
      "name": "{{ $xnSeo['schema_org_name'] ?? 'Xenoraa' }}",
      "url": "{{ $xnSeo['schema_org_url'] ?? 'https://xenoraa.com' }}"
      @if(!empty($xnSeo['schema_org_logo'] ?? '')),"logo": "{{ $xnSeo['schema_org_logo'] }}"@endif
      @if(!empty($xnSeo['schema_org_description'] ?? '')),"description": "{{ addslashes($xnSeo['schema_org_description']) }}"@endif
      @if(!empty($xnSeo['schema_org_phone'] ?? '')),"telephone": "{{ $xnSeo['schema_org_phone'] }}"@endif
      @if(!empty($xnSeo['schema_org_email'] ?? '')),"email": "{{ $xnSeo['schema_org_email'] }}"@endif
      @if(!empty($xnSeo['schema_org_address'] ?? '')),"address": {"@type": "PostalAddress", "addressLocality": "{{ $xnSeo['schema_org_address'] }}"}@endif
    }
    </script>
    @endif
</head>
<body>

{{-- NAVIGATION --}}
<nav class="xn-nav" id="xnNav">
    <a href="{{ route('xenoraa.home') }}" class="xn-nav-logo">
        <img src="/images/xenoraa/logo.png" alt="Xenoraa" style="height:28px;filter:brightness(0) invert(1);">
    </a>
    <ul class="xn-nav-links">
        <li><a href="{{ route('xenoraa.home') }}" class="{{ request()->routeIs('xenoraa.home') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ route('xenoraa.features') }}" class="{{ request()->routeIs('xenoraa.features') ? 'active' : '' }}">Features</a></li>
        <li><a href="{{ route('xenoraa.pricing') }}" class="{{ request()->routeIs('xenoraa.pricing') ? 'active' : '' }}">Pricing</a></li>
        <li><a href="{{ route('xenoraa.showcase') }}" class="{{ request()->routeIs('xenoraa.showcase') ? 'active' : '' }}">Showcase</a></li>
        <li><a href="{{ route('xenoraa.blog') }}" class="{{ request()->routeIs('xenoraa.blog*') ? 'active' : '' }}">Blog</a></li>
    </ul>
    <div class="xn-nav-actions">
        <a href="{{ route('login') }}" class="xn-btn-ghost">Login</a>
        <a href="{{ route('xenoraa.get-started') }}" class="xn-btn-primary"><i class="fas fa-arrow-right"></i> Get Started</a>
    </div>
    <div class="xn-hamburger" onclick="toggleMobileMenu()" id="xnHamburger">
        <span></span><span></span><span></span>
    </div>
</nav>

{{-- MOBILE MENU --}}
<div class="xn-mobile-menu" id="xnMobileMenu">
    <a href="{{ route('xenoraa.home') }}">Home</a>
    <a href="{{ route('xenoraa.features') }}">Features</a>
    <a href="{{ route('xenoraa.pricing') }}">Pricing</a>
    <a href="{{ route('xenoraa.showcase') }}">Showcase</a>
    <a href="{{ route('xenoraa.blog') }}">Blog</a>
    <a href="{{ route('login') }}">Login</a>
    <a href="{{ route('xenoraa.get-started') }}" style="color:var(--xn-purple-light);">Get Started →</a>
</div>

{{-- MAIN CONTENT --}}
<main style="padding-top: 72px;">
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="xn-footer">
    <div class="xn-container">
        <div class="xn-footer-grid">
            <div>
                <div class="xn-footer-logo">xenoraa</div>
                <p class="xn-footer-desc">The all-in-one platform for professionals, consultants, founders, creators, and leaders to build their digital identity and manage their business operations.</p>
                <div class="xn-social-links">
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div>
                <div class="xn-footer-heading">Product</div>
                <ul class="xn-footer-links">
                    <li><a href="{{ route('xenoraa.features') }}">Features</a></li>
                    <li><a href="{{ route('xenoraa.pricing') }}">Pricing</a></li>
                    <li><a href="{{ route('xenoraa.showcase') }}">Showcase</a></li>
                    <li><a href="{{ route('xenoraa.get-started') }}">Get Started</a></li>
                </ul>
            </div>
            <div>
                <div class="xn-footer-heading">Resources</div>
                <ul class="xn-footer-links">
                    <li><a href="{{ route('xenoraa.blog') }}">Blog</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">API Reference</a></li>
                    <li><a href="#">Support</a></li>
                </ul>
            </div>
            <div>
                <div class="xn-footer-heading">Company</div>
                <ul class="xn-footer-links">
                    <li><a href="{{ route('xenoraa.about') }}">About Us</a></li>
                    <li><a href="{{ route('xenoraa.careers') }}">Careers</a></li>
                    <li><a href="{{ route('legal.privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('legal.terms') }}">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="xn-footer-bottom">
            <div class="xn-footer-copy">© {{ date('Y') }} Xenoraa. All rights reserved. Built with ♥ in India.</div>
            <div style="font-size:0.8rem;color:var(--xn-gray2);">xenoraa.com — Your Digital Identity. Unified.</div>
        </div>
    </div>
</footer>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('xnMobileMenu');
    menu.classList.toggle('open');
}
window.addEventListener('scroll', () => {
    const nav = document.getElementById('xnNav');
    if (window.scrollY > 20) nav.classList.add('scrolled');
    else nav.classList.remove('scrolled');
});
</script>
@yield('scripts')
<x-xena-widget />
{{-- Custom body scripts from SEO settings --}}
@if(!empty($xnSeo['custom_body_scripts'] ?? ''))
{!! $xnSeo['custom_body_scripts'] !!}
@endif
</body>
</html>
