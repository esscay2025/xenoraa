@extends('layouts.app')
@section('title', ($profile['name'] ?? $tenant->name) . ' — ' . ($profile['tagline'] ?? 'Business & Company'))
@section('description', $profile['about'] ?? 'Professional business solutions tailored to your needs. Trusted by hundreds of clients across industries.')
@section('content')
@php
    $accent = $accentColor ?? '#0ea5e9';
    $homePage = $homePage ?? null;
    $_show = function(string $key) use ($homePage): bool {
        if (!$homePage) return true;
        return $homePage->isSectionEnabled($key);
    };
    $heroData         = $homePage ? $homePage->getSectionData('hero')         : [];
    $statsData        = $homePage ? $homePage->getSectionData('stats')        : [];
    $aboutData        = $homePage ? $homePage->getSectionData('about')        : [];
    $servicesData     = $homePage ? $homePage->getSectionData('services')     : [];
    $testimonialsData = $homePage ? $homePage->getSectionData('testimonials') : [];
    $venturesData     = $homePage ? $homePage->getSectionData('ventures')     : [];
    $blogData         = $homePage ? $homePage->getSectionData('blog')         : [];
    $contactData      = $homePage ? $homePage->getSectionData('contact')      : [];

    $heroHeading    = $heroData['heading']    ?? $profile['name']    ?? $tenant->name;
    $heroSubheading = $heroData['subheading'] ?? $profile['tagline'] ?? ($profile['title'] ?? 'Your Trusted Business Partner');
    $heroCta        = $heroData['cta_text']   ?? 'Get a Free Quote';
    $heroCtaUrl     = $heroData['cta_url']    ?? ($tenantContactUrl ?? '/contact');

    $statsItems = $statsData['items'] ?? $profile['stats'] ?? [
        ['icon' => '🏆', 'value' => $profile['years_experience'] ?? '15+',  'label' => 'Years Experience'],
        ['icon' => '😊', 'value' => $profile['clients_served']   ?? '500+', 'label' => 'Clients Served'],
        ['icon' => '🌍', 'value' => $profile['cities']           ?? '20+',  'label' => 'Cities'],
        ['icon' => '⭐', 'value' => $profile['satisfaction']     ?? '98%',  'label' => 'Satisfaction Rate'],
    ];

    $aboutText = $aboutData['text'] ?? $profile['about'] ?? 'We are a results-driven company committed to delivering exceptional value to our clients. With deep industry expertise and a client-first approach, we help businesses grow, scale, and succeed in competitive markets.';

    $services = $servicesData['items'] ?? $profile['services'] ?? [
        ['icon' => '🏗️', 'title' => 'Project Management',    'text' => 'End-to-end project planning, execution, and delivery on time and within budget'],
        ['icon' => '📈', 'title' => 'Business Consulting',    'text' => 'Strategic advisory services to optimise operations and accelerate growth'],
        ['icon' => '🤝', 'title' => 'Partnership & Alliances','text' => 'Building strategic partnerships that create mutual value and long-term success'],
        ['icon' => '💡', 'title' => 'Innovation & R&D',       'text' => 'Research-driven solutions and product development for competitive advantage'],
        ['icon' => '🌐', 'title' => 'Digital Transformation', 'text' => 'Modernising business processes with cutting-edge technology and automation'],
        ['icon' => '📊', 'title' => 'Analytics & Insights',   'text' => 'Data-driven decision making with actionable business intelligence and reporting'],
    ];

    $ventures = $venturesData['items'] ?? $profile['venture_list'] ?? [
        ['icon' => '🏢', 'title' => 'Corporate Solutions',    'text' => 'Comprehensive business solutions for large enterprises and corporates', 'url' => ''],
        ['icon' => '🏘️', 'title' => 'Real Estate Division',  'text' => 'Premium residential and commercial property development and management', 'url' => ''],
        ['icon' => '✈️', 'title' => 'Travel & Hospitality',   'text' => 'Curated travel experiences, tour packages, and hospitality management', 'url' => ''],
    ];

    $testimonials = $testimonialsData['items'] ?? $profile['testimonials'] ?? [
        ['name' => 'Suresh Iyer',     'role' => 'CEO, Meridian Corp',        'text' => 'Their team delivered beyond expectations. The project was completed on time and the quality was outstanding. Highly recommended.'],
        ['name' => 'Kavitha Nair',    'role' => 'Director, Apex Properties', 'text' => 'Professional, reliable, and results-oriented. They understood our requirements perfectly and executed flawlessly.'],
        ['name' => 'Rajan Pillai',    'role' => 'MD, Horizon Travels',       'text' => 'Working with them has been a game-changer for our business. Their strategic insights helped us grow 3x in 18 months.'],
    ];

    $contactHeading = $contactData['heading']     ?? 'Let\'s Work Together';
    $contactText    = $contactData['text']        ?? 'Ready to take your business to the next level? Get in touch with our team for a free consultation and customised proposal.';
    $contactBtn     = $contactData['button_text'] ?? 'Request a Free Quote';
    $contactBtnUrl  = $contactData['button_url']  ?? ($tenantContactUrl ?? '/contact');

    $tenantBase = isset($tenant) && $tenant->custom_domain ? '' : ('/' . ($tenant->username ?? ''));
@endphp

<style>
:root { --biz-accent: {{ $accent }}; --biz-accent-dark: color-mix(in srgb, var(--biz-accent) 80%, #000); }
.xn-biz-wrap { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }

/* ── HERO ── */
.biz-hero { background: linear-gradient(135deg, #020b18 0%, #051525 50%, #020b18 100%); padding: 6rem 0 5rem; position: relative; overflow: hidden; }
.biz-hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
.biz-hero::after { content: ''; position: absolute; top: -20%; right: -5%; width: 500px; height: 500px; background: radial-gradient(circle, color-mix(in srgb, var(--biz-accent) 12%, transparent) 0%, transparent 70%); border-radius: 50%; }
.biz-hero-inner { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 5rem; align-items: center; position: relative; z-index: 1; }
@media(max-width:900px){ .biz-hero-inner { grid-template-columns: 1fr; gap: 3rem; } }
.biz-hero-eyebrow { display: inline-flex; align-items: center; gap: 0.5rem; background: color-mix(in srgb, var(--biz-accent) 12%, transparent); color: var(--biz-accent); font-size: 0.72rem; font-weight: 800; padding: 0.35rem 1rem; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.1em; border-left: 3px solid var(--biz-accent); margin-bottom: 1.5rem; }
.biz-hero-h1 { font-size: clamp(2.4rem, 4.5vw, 4rem); font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 1.25rem; }
.biz-hero-h1 .highlight { color: var(--biz-accent); }
.biz-hero-sub { font-size: 1.05rem; color: #94a3b8; line-height: 1.8; margin-bottom: 2.5rem; max-width: 520px; }
.biz-hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2.5rem; }
.biz-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.9rem 2rem; border-radius: 6px; font-size: 0.9rem; font-weight: 700; text-decoration: none; transition: all 0.25s; cursor: pointer; border: none; }
.biz-btn-primary { background: var(--biz-accent); color: #fff; box-shadow: 0 8px 24px color-mix(in srgb, var(--biz-accent) 30%, transparent); }
.biz-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 32px color-mix(in srgb, var(--biz-accent) 45%, transparent); color: #fff; }
.biz-btn-outline { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,0.2); }
.biz-btn-outline:hover { background: rgba(255,255,255,0.06); color: #fff; }
.biz-hero-trust { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.biz-hero-trust-text { font-size: 0.8rem; color: #64748b; }
.biz-hero-trust-logos { display: flex; gap: 0.75rem; }
.biz-hero-trust-logo { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 6px; padding: 0.4rem 0.9rem; font-size: 0.7rem; font-weight: 700; color: #94a3b8; }
.biz-hero-panel { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 20px; padding: 2rem; }
.biz-hero-panel-title { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1.25rem; }
.biz-hero-service-item { display: flex; align-items: center; gap: 1rem; padding: 0.85rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
.biz-hero-service-item:last-child { border-bottom: none; }
.biz-hero-service-icon { width: 38px; height: 38px; background: color-mix(in srgb, var(--biz-accent) 12%, transparent); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.biz-hero-service-name { font-size: 0.88rem; font-weight: 600; color: #e2e8f0; }
.biz-hero-service-desc { font-size: 0.75rem; color: #64748b; }

/* ── STATS BAR ── */
.biz-stats-bar { background: var(--biz-accent); padding: 2.5rem 0; }
.biz-stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1px; }
@media(max-width:640px){ .biz-stats-row { grid-template-columns: repeat(2, 1fr); } }
.biz-stat-item { text-align: center; padding: 0.5rem 1rem; border-right: 1px solid rgba(255,255,255,0.2); }
.biz-stat-item:last-child { border-right: none; }
.biz-stat-num { font-size: 2rem; font-weight: 900; color: #fff; line-height: 1; }
.biz-stat-label { font-size: 0.75rem; color: rgba(255,255,255,0.75); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.25rem; }

/* ── SECTION COMMON ── */
.biz-section { padding: 5.5rem 0; }
.biz-section-header { text-align: center; margin-bottom: 4rem; }
.biz-section-eyebrow { display: inline-block; color: var(--biz-accent); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 0.75rem; }
.biz-section-title { font-size: clamp(1.75rem, 3vw, 2.5rem); font-weight: 900; color: #fff; margin-bottom: 0.75rem; line-height: 1.2; }
.biz-section-sub { font-size: 1rem; color: #64748b; max-width: 580px; margin: 0 auto; line-height: 1.7; }

/* ── SERVICES ── */
.biz-services { background: #030d1a; }
.biz-services-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
@media(max-width:900px){ .biz-services-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:560px){ .biz-services-grid { grid-template-columns: 1fr; } }
.biz-service-card { background: #071020; border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; padding: 2rem; transition: all 0.3s; position: relative; overflow: hidden; }
.biz-service-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--biz-accent); transform: scaleX(0); transition: transform 0.3s; }
.biz-service-card:hover { border-color: color-mix(in srgb, var(--biz-accent) 30%, transparent); transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.4); }
.biz-service-card:hover::before { transform: scaleX(1); }
.biz-service-icon { width: 52px; height: 52px; background: color-mix(in srgb, var(--biz-accent) 12%, transparent); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem; }
.biz-service-name { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.6rem; }
.biz-service-desc { font-size: 0.85rem; color: #64748b; line-height: 1.6; }

/* ── ABOUT ── */
.biz-about { background: #020b18; }
.biz-about-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 6rem; align-items: center; }
@media(max-width:900px){ .biz-about-inner { grid-template-columns: 1fr; gap: 3rem; } }
.biz-about-img { position: relative; }
.biz-about-main-img { background: linear-gradient(135deg, #071020, #0d1f35); border: 1px solid rgba(255,255,255,0.07); border-radius: 20px; padding: 3rem; min-height: 340px; display: flex; flex-direction: column; justify-content: space-between; }
.biz-about-company-name { font-size: 1.5rem; font-weight: 900; color: #fff; margin-bottom: 0.5rem; }
.biz-about-company-tag { font-size: 0.85rem; color: var(--biz-accent); font-weight: 600; }
.biz-about-badge-float { position: absolute; bottom: -1rem; right: -1rem; background: var(--biz-accent); color: #fff; border-radius: 12px; padding: 1rem 1.25rem; text-align: center; box-shadow: 0 8px 24px color-mix(in srgb, var(--biz-accent) 40%, transparent); }
.biz-about-badge-float-num { font-size: 1.5rem; font-weight: 900; }
.biz-about-badge-float-label { font-size: 0.7rem; font-weight: 600; opacity: 0.9; }
.biz-about-eyebrow { color: var(--biz-accent); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 0.75rem; }
.biz-about-h2 { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 900; color: #fff; margin-bottom: 1.25rem; line-height: 1.2; }
.biz-about-text { font-size: 0.95rem; color: #94a3b8; line-height: 1.8; margin-bottom: 2rem; }
.biz-about-features { list-style: none; padding: 0; margin: 0 0 2.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
.biz-about-features li { display: flex; align-items: center; gap: 0.6rem; font-size: 0.85rem; color: #e2e8f0; }
.biz-about-features li::before { content: ''; width: 18px; height: 18px; background: color-mix(in srgb, var(--biz-accent) 15%, transparent); border: 1px solid color-mix(in srgb, var(--biz-accent) 30%, transparent); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath fill='%230ea5e9' d='M10 3L5 8.5 2 5.5l-1 1 4 4 6-7z'/%3E%3C/svg%3E"); background-size: 10px; background-repeat: no-repeat; background-position: center; }

/* ── DIVISIONS ── */
.biz-divisions { background: #030d1a; }
.biz-div-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
@media(max-width:768px){ .biz-div-grid { grid-template-columns: 1fr; } }
.biz-div-card { background: #071020; border: 1px solid rgba(255,255,255,0.06); border-radius: 20px; padding: 2.5rem; text-align: center; transition: all 0.3s; }
.biz-div-card:hover { border-color: color-mix(in srgb, var(--biz-accent) 30%, transparent); transform: translateY(-6px); box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
.biz-div-icon { font-size: 3rem; margin-bottom: 1.25rem; }
.biz-div-name { font-size: 1.1rem; font-weight: 800; color: #fff; margin-bottom: 0.6rem; }
.biz-div-desc { font-size: 0.85rem; color: #64748b; line-height: 1.6; margin-bottom: 1.25rem; }
.biz-div-link { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; font-weight: 700; color: var(--biz-accent); text-decoration: none; }
.biz-div-link:hover { gap: 0.7rem; }

/* ── TESTIMONIALS ── */
.biz-testimonials { background: #020b18; }
.biz-test-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
@media(max-width:768px){ .biz-test-grid { grid-template-columns: 1fr; } }
.biz-test-card { background: #071020; border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; padding: 2rem; position: relative; }
.biz-test-quote { font-size: 3rem; color: var(--biz-accent); opacity: 0.3; line-height: 1; margin-bottom: 0.5rem; font-family: Georgia, serif; }
.biz-test-text { font-size: 0.9rem; color: #94a3b8; line-height: 1.7; margin-bottom: 1.5rem; }
.biz-test-author { display: flex; align-items: center; gap: 0.75rem; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1rem; }
.biz-test-avatar { width: 44px; height: 44px; border-radius: 50%; background: color-mix(in srgb, var(--biz-accent) 20%, #071020); display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; color: var(--biz-accent); flex-shrink: 0; }
.biz-test-name { font-size: 0.9rem; font-weight: 700; color: #fff; }
.biz-test-role { font-size: 0.75rem; color: #64748b; }

/* ── CTA BANNER ── */
.biz-cta { background: linear-gradient(135deg, color-mix(in srgb, var(--biz-accent) 15%, #020b18) 0%, #020b18 100%); border-top: 1px solid color-mix(in srgb, var(--biz-accent) 20%, transparent); border-bottom: 1px solid color-mix(in srgb, var(--biz-accent) 20%, transparent); padding: 5rem 0; text-align: center; }
.biz-cta-title { font-size: clamp(1.75rem, 3.5vw, 2.75rem); font-weight: 900; color: #fff; margin-bottom: 1rem; }
.biz-cta-sub { font-size: 1rem; color: #94a3b8; margin-bottom: 2.5rem; max-width: 560px; margin-left: auto; margin-right: auto; line-height: 1.7; }
.biz-cta-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }

/* ── CONTACT ── */
.biz-contact { background: #030d1a; }
.biz-contact-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: start; }
@media(max-width:900px){ .biz-contact-inner { grid-template-columns: 1fr; gap: 3rem; } }
.biz-contact-h2 { font-size: 1.75rem; font-weight: 900; color: #fff; margin-bottom: 0.75rem; }
.biz-contact-sub { font-size: 0.95rem; color: #94a3b8; line-height: 1.7; margin-bottom: 2rem; }
.biz-contact-items { display: flex; flex-direction: column; gap: 1.25rem; }
.biz-contact-item { display: flex; align-items: flex-start; gap: 1rem; }
.biz-contact-item-icon { width: 44px; height: 44px; background: color-mix(in srgb, var(--biz-accent) 12%, transparent); border: 1px solid color-mix(in srgb, var(--biz-accent) 20%, transparent); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.biz-contact-item-label { font-size: 0.72rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.2rem; }
.biz-contact-item-val { font-size: 0.9rem; color: #e2e8f0; font-weight: 600; }
.biz-form { background: #071020; border: 1px solid rgba(255,255,255,0.06); border-radius: 20px; padding: 2.5rem; }
.biz-form-title { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 1.5rem; }
.biz-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
@media(max-width:480px){ .biz-form-row { grid-template-columns: 1fr; } }
.biz-form-group { margin-bottom: 1rem; }
.biz-form-label { display: block; font-size: 0.78rem; font-weight: 600; color: #64748b; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.04em; }
.biz-form-input { width: 100%; background: #030d1a; border: 1px solid rgba(255,255,255,0.07); color: #fff; padding: 0.8rem 1rem; border-radius: 8px; font-size: 0.9rem; outline: none; box-sizing: border-box; }
.biz-form-input:focus { border-color: var(--biz-accent); }
.biz-form-textarea { min-height: 120px; resize: vertical; }
</style>

{{-- HERO --}}
@if($_show('hero'))
<section class="biz-hero">
    <div class="xn-biz-wrap">
        <div class="biz-hero-inner">
            <div>
                <div class="biz-hero-eyebrow">Trusted Business Partner</div>
                <h1 class="biz-hero-h1">
                    {{ $heroHeading }}<br>
                    <span class="highlight">{{ $heroSubheading }}</span>
                </h1>
                <p class="biz-hero-sub">We deliver world-class business solutions — from real estate and travel to corporate consulting and digital services. Your success is our mission.</p>
                <div class="biz-hero-actions">
                    <a href="{{ $heroCtaUrl }}" class="biz-btn biz-btn-primary">
                        <i class="fas fa-phone-alt"></i> {{ $heroCta }}
                    </a>
                    <a href="{{ $tenantBase }}/about" class="biz-btn biz-btn-outline">
                        <i class="fas fa-play-circle"></i> About Us
                    </a>
                </div>
                <div class="biz-hero-trust">
                    <span class="biz-hero-trust-text">Trusted by:</span>
                    <div class="biz-hero-trust-logos">
                        <div class="biz-hero-trust-logo">ISO Certified</div>
                        <div class="biz-hero-trust-logo">Award Winning</div>
                        <div class="biz-hero-trust-logo">500+ Clients</div>
                    </div>
                </div>
            </div>
            <div class="biz-hero-panel">
                <div class="biz-hero-panel-title">Our Core Services</div>
                @foreach(array_slice($services, 0, 4) as $svc)
                <div class="biz-hero-service-item">
                    <div class="biz-hero-service-icon">{{ $svc['icon'] ?? '🏢' }}</div>
                    <div>
                        <div class="biz-hero-service-name">{{ $svc['title'] ?? $svc['name'] ?? 'Service' }}</div>
                        <div class="biz-hero-service-desc">{{ Str::limit($svc['text'] ?? $svc['description'] ?? '', 60) }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- STATS BAR --}}
@if($_show('stats'))
<div class="biz-stats-bar">
    <div class="xn-biz-wrap">
        <div class="biz-stats-row">
            @foreach($statsItems as $stat)
            <div class="biz-stat-item">
                <div class="biz-stat-num">{{ $stat['value'] ?? $stat['num'] ?? '—' }}</div>
                <div class="biz-stat-label">{{ $stat['label'] ?? '' }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- SERVICES --}}
@if($_show('services'))
<section class="biz-section biz-services">
    <div class="xn-biz-wrap">
        <div class="biz-section-header">
            <div class="biz-section-eyebrow">What We Offer</div>
            <h2 class="biz-section-title">Our Services & Solutions</h2>
            <p class="biz-section-sub">Comprehensive business services designed to help you grow, scale, and succeed in today's competitive landscape.</p>
        </div>
        <div class="biz-services-grid">
            @foreach($services as $svc)
            <div class="biz-service-card">
                <div class="biz-service-icon">{{ $svc['icon'] ?? '🏢' }}</div>
                <div class="biz-service-name">{{ $svc['title'] ?? $svc['name'] ?? 'Service' }}</div>
                <div class="biz-service-desc">{{ $svc['text'] ?? $svc['description'] ?? '' }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ABOUT --}}
@if($_show('about'))
<section class="biz-section biz-about">
    <div class="xn-biz-wrap">
        <div class="biz-about-inner">
            <div class="biz-about-img">
                <div class="biz-about-main-img">
                    <div>
                        <div class="biz-about-company-name">{{ $heroHeading }}</div>
                        <div class="biz-about-company-tag">{{ $heroSubheading }}</div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        @foreach(array_slice($statsItems, 0, 4) as $stat)
                        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.06);border-radius:10px;padding:1rem;text-align:center;">
                            <div style="font-size:1.4rem;font-weight:900;color:var(--biz-accent);">{{ $stat['value'] ?? '—' }}</div>
                            <div style="font-size:0.7rem;color:#64748b;margin-top:0.2rem;">{{ $stat['label'] ?? '' }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="biz-about-badge-float">
                    <div class="biz-about-badge-float-num">{{ $statsItems[0]['value'] ?? '15+' }}</div>
                    <div class="biz-about-badge-float-label">Years in Business</div>
                </div>
            </div>
            <div>
                <div class="biz-about-eyebrow">About Our Company</div>
                <h2 class="biz-about-h2">Building Businesses, Creating Value</h2>
                <p class="biz-about-text">{{ $aboutText }}</p>
                <ul class="biz-about-features">
                    <li>Industry-leading expertise</li>
                    <li>Client-first approach</li>
                    <li>Proven track record</li>
                    <li>Transparent processes</li>
                    <li>Dedicated support team</li>
                    <li>Competitive pricing</li>
                </ul>
                <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                    <a href="{{ $heroCtaUrl }}" class="biz-btn biz-btn-primary">
                        <i class="fas fa-handshake"></i> Work With Us
                    </a>
                    <a href="{{ $tenantBase }}/about" class="biz-btn biz-btn-outline">
                        <i class="fas fa-arrow-right"></i> Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- DIVISIONS --}}
@if($_show('ventures') && !empty($ventures))
<section class="biz-section biz-divisions">
    <div class="xn-biz-wrap">
        <div class="biz-section-header">
            <div class="biz-section-eyebrow">Our Divisions</div>
            <h2 class="biz-section-title">Business Verticals</h2>
            <p class="biz-section-sub">We operate across multiple industries, bringing specialised expertise and proven results to each domain.</p>
        </div>
        <div class="biz-div-grid">
            @foreach($ventures as $div)
            <div class="biz-div-card">
                <div class="biz-div-icon">{{ $div['icon'] ?? '🏢' }}</div>
                <div class="biz-div-name">{{ $div['title'] ?? $div['name'] ?? 'Division' }}</div>
                <div class="biz-div-desc">{{ $div['text'] ?? $div['description'] ?? '' }}</div>
                <a href="{{ $div['url'] ?? '#' }}" class="biz-div-link">
                    Explore <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- TESTIMONIALS --}}
@if($_show('testimonials'))
<section class="biz-section biz-testimonials">
    <div class="xn-biz-wrap">
        <div class="biz-section-header">
            <div class="biz-section-eyebrow">Client Testimonials</div>
            <h2 class="biz-section-title">What Our Clients Say</h2>
            <p class="biz-section-sub">Trusted by businesses across industries. Here is what our clients have to say about working with us.</p>
        </div>
        <div class="biz-test-grid">
            @foreach($testimonials as $t)
            <div class="biz-test-card">
                <div class="biz-test-quote">"</div>
                <p class="biz-test-text">{{ $t['text'] ?? '' }}</p>
                <div class="biz-test-author">
                    <div class="biz-test-avatar">{{ strtoupper(substr($t['name'] ?? 'C', 0, 1)) }}</div>
                    <div>
                        <div class="biz-test-name">{{ $t['name'] ?? 'Client' }}</div>
                        <div class="biz-test-role">{{ $t['role'] ?? 'Business Client' }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA BANNER --}}
<div class="biz-cta">
    <div class="xn-biz-wrap">
        <h2 class="biz-cta-title">{{ $contactHeading }}</h2>
        <p class="biz-cta-sub">{{ $contactText }}</p>
        <div class="biz-cta-actions">
            <a href="{{ $contactBtnUrl }}" class="biz-btn biz-btn-primary">
                <i class="fas fa-phone-alt"></i> {{ $contactBtn }}
            </a>
            <a href="{{ $tenantBase }}/blog" class="biz-btn biz-btn-outline">
                <i class="fas fa-newspaper"></i> Read Our Blog
            </a>
        </div>
    </div>
</div>

{{-- CONTACT --}}
@if($_show('contact'))
<section class="biz-section biz-contact">
    <div class="xn-biz-wrap">
        <div class="biz-contact-inner">
            <div>
                <h2 class="biz-contact-h2">Contact Us</h2>
                <p class="biz-contact-sub">Reach out to our team for enquiries, partnerships, or to schedule a consultation. We respond within 24 hours.</p>
                <div class="biz-contact-items">
                    @if(!empty($profile['email']))
                    <div class="biz-contact-item">
                        <div class="biz-contact-item-icon">📧</div>
                        <div>
                            <div class="biz-contact-item-label">Email</div>
                            <div class="biz-contact-item-val">{{ $profile['email'] }}</div>
                        </div>
                    </div>
                    @endif
                    @if(!empty($profile['phone']))
                    <div class="biz-contact-item">
                        <div class="biz-contact-item-icon">📞</div>
                        <div>
                            <div class="biz-contact-item-label">Phone</div>
                            <div class="biz-contact-item-val">{{ $profile['phone'] }}</div>
                        </div>
                    </div>
                    @endif
                    @if(!empty($profile['location']))
                    <div class="biz-contact-item">
                        <div class="biz-contact-item-icon">📍</div>
                        <div>
                            <div class="biz-contact-item-label">Office</div>
                            <div class="biz-contact-item-val">{{ $profile['location'] }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="biz-contact-item">
                        <div class="biz-contact-item-icon">🕐</div>
                        <div>
                            <div class="biz-contact-item-label">Business Hours</div>
                            <div class="biz-contact-item-val">Mon–Fri, 9 AM – 6 PM</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="biz-form">
                <div class="biz-form-title">Send Us a Message</div>
                <form action="{{ $tenantBase }}/contact" method="POST">
                    @csrf
                    <div class="biz-form-row">
                        <div class="biz-form-group">
                            <label class="biz-form-label">Full Name</label>
                            <input type="text" name="name" class="biz-form-input" placeholder="Your name" required>
                        </div>
                        <div class="biz-form-group">
                            <label class="biz-form-label">Company</label>
                            <input type="text" name="company" class="biz-form-input" placeholder="Your company">
                        </div>
                    </div>
                    <div class="biz-form-row">
                        <div class="biz-form-group">
                            <label class="biz-form-label">Email</label>
                            <input type="email" name="email" class="biz-form-input" placeholder="your@email.com" required>
                        </div>
                        <div class="biz-form-group">
                            <label class="biz-form-label">Phone</label>
                            <input type="tel" name="phone" class="biz-form-input" placeholder="+91 98765 43210">
                        </div>
                    </div>
                    <div class="biz-form-group">
                        <label class="biz-form-label">Service Required</label>
                        <input type="text" name="subject" class="biz-form-input" placeholder="e.g. Real Estate, Travel Package, Consulting">
                    </div>
                    <div class="biz-form-group">
                        <label class="biz-form-label">Message</label>
                        <textarea name="message" class="biz-form-input biz-form-textarea" placeholder="Tell us about your requirements..." required></textarea>
                    </div>
                    <button type="submit" class="biz-btn biz-btn-primary" style="width:100%;justify-content:center;">
                        <i class="fas fa-paper-plane"></i> Send Enquiry
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endif

@endsection
