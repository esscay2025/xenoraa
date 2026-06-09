@extends('layouts.app')

@push('styles')
<style>
/* ── About Page ── */
:root {
    /* Use the layout's computed theme variables directly — works for both light and dark themes */
    --pg-bg:         var(--bg-primary, #0a0a0a);
    --pg-card:       var(--bg-card, #1a1a1a);
    --pg-border:     var(--border, #2a2a2a);
    --pg-text:       var(--text-primary, #ffffff);
    --pg-text-sec:   var(--text-secondary, #a0a0a0);
    --pg-text-muted: var(--text-muted, #666666);
    --pg-accent:     var(--accent, #6366f1);
}
.ab-wrap { max-width: 1100px; margin: 0 auto; padding: 2rem 1.25rem 4rem; }
/* Hero */
.ab-hero { background: linear-gradient(135deg, color-mix(in srgb, var(--pg-accent) 8%, var(--pg-bg)), var(--pg-bg)); border-bottom: 1px solid var(--pg-border); padding: 3.5rem 1.25rem 3rem; margin-bottom: 0; }
.ab-hero-inner { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: 340px 1fr; gap: 3rem; align-items: center; }
@media (max-width: 768px) { .ab-hero-inner { grid-template-columns: 1fr; gap: 2rem; align-items: center; text-align: center; } }
.ab-logo-col { display: flex; flex-direction: column; align-items: center; gap: 1rem; }
.ab-logo-frame { width: 180px; height: 180px; border-radius: 24px; overflow: hidden; background: var(--pg-card); border: 2px solid var(--pg-border); display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 32px rgba(0,0,0,0.12); }
.ab-logo-frame img { width: 100%; height: 100%; object-fit: contain; padding: 1rem; }
.ab-logo-initials { font-size: 4rem; font-weight: 900; color: var(--pg-accent); }
.ab-est-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 1rem; background: color-mix(in srgb, var(--pg-accent) 12%, var(--pg-bg)); color: var(--pg-accent); border-radius: 50px; font-size: 0.82rem; font-weight: 700; }
.ab-info-col {}
.ab-profession-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.35rem 1rem; background: color-mix(in srgb, var(--pg-accent) 12%, var(--pg-bg)); color: var(--pg-accent); border-radius: 50px; font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 1rem; }
.ab-name { font-size: 2.5rem; font-weight: 900; color: var(--pg-text); margin: 0 0 0.5rem; line-height: 1.15; }
@media (max-width: 768px) { .ab-name { font-size: 1.8rem; } }
.ab-tagline { font-size: 1.1rem; color: var(--pg-text-sec); margin: 0 0 1.25rem; }
.ab-bio { font-size: 0.95rem; color: var(--pg-text-sec); line-height: 1.75; margin: 0 0 1.5rem; }
.ab-stats-strip { display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1.5rem; }
.ab-stats-strip > div { text-align: center; }
.ab-stat-value { font-size: 1.5rem; font-weight: 900; color: var(--pg-accent); }
.ab-stat-label { font-size: 0.72rem; color: var(--pg-text-muted); text-transform: uppercase; letter-spacing: 0.06em; margin-top: 0.1rem; }
.ab-contact-row { display: flex; flex-wrap: wrap; gap: 0.6rem; }
.ab-contact-chip { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.9rem; background: var(--pg-card); border: 1px solid var(--pg-border); border-radius: 50px; font-size: 0.82rem; color: var(--pg-text-sec); text-decoration: none; transition: border-color 0.2s; }
.ab-contact-chip:hover { border-color: var(--pg-accent); color: var(--pg-accent); }
/* Body */
.ab-body { max-width: 1100px; margin: 0 auto; padding: 3rem 1.25rem 0; }
.ab-section-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--pg-accent); margin-bottom: 0.4rem; }
.ab-section-title { font-size: 1.75rem; font-weight: 800; color: var(--pg-text); margin: 0 0 0.5rem; }
.ab-section-sub { color: var(--pg-text-sec); font-size: 0.95rem; margin: 0; }
.ab-divider { border: none; border-top: 1px solid var(--pg-border); margin: 3rem 0; }
/* Info cards */
.ab-info-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 3rem; }
.ab-info-card { background: var(--pg-card); border: 1px solid var(--pg-border); border-radius: 16px; padding: 1.5rem; }
.ab-info-card-icon { width: 44px; height: 44px; border-radius: 12px; background: color-mix(in srgb, var(--pg-accent) 12%, var(--pg-bg)); display: flex; align-items: center; justify-content: center; color: var(--pg-accent); font-size: 1.1rem; margin-bottom: 0.75rem; }
.ab-info-card-title { font-size: 0.9rem; font-weight: 700; color: var(--pg-text); margin: 0 0 0.3rem; }
.ab-info-card-desc { font-size: 0.82rem; color: var(--pg-text-sec); line-height: 1.5; margin: 0; }
/* Feature grid */
.ab-feature-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 3rem; }
.ab-feature-card { background: var(--pg-card); border: 1px solid var(--pg-border); border-radius: 20px; padding: 1.75rem; transition: border-color 0.2s, transform 0.2s; }
.ab-feature-card:hover { border-color: color-mix(in srgb, var(--pg-accent) 40%, transparent); transform: translateY(-2px); }
.ab-feature-icon { width: 52px; height: 52px; border-radius: 14px; background: color-mix(in srgb, var(--pg-accent) 12%, var(--pg-bg)); display: flex; align-items: center; justify-content: center; color: var(--pg-accent); font-size: 1.3rem; margin-bottom: 1rem; }
.ab-feature-title { font-size: 1rem; font-weight: 700; color: var(--pg-text); margin: 0 0 0.5rem; }
.ab-feature-desc { font-size: 0.875rem; color: var(--pg-text-sec); line-height: 1.6; margin: 0; }
/* Skills */
.ab-skills-grid { display: flex; flex-wrap: wrap; gap: 0.6rem; margin-bottom: 3rem; }
.ab-skill-tag { padding: 0.4rem 1rem; background: color-mix(in srgb, var(--pg-accent) 10%, var(--pg-bg)); color: var(--pg-accent); border: 1px solid color-mix(in srgb, var(--pg-accent) 25%, transparent); border-radius: 50px; font-size: 0.82rem; font-weight: 600; }
/* Services */
.ab-services-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.25rem; margin-bottom: 3rem; }
.ab-service-card { background: var(--pg-card); border: 1px solid var(--pg-border); border-radius: 16px; padding: 1.5rem; transition: border-color 0.2s; }
.ab-service-card:hover { border-color: color-mix(in srgb, var(--pg-accent) 40%, transparent); }
.ab-service-icon { font-size: 1.75rem; margin-bottom: 0.75rem; }
.ab-service-title { font-size: 0.95rem; font-weight: 700; color: var(--pg-text); margin: 0 0 0.4rem; }
.ab-service-desc { font-size: 0.82rem; color: var(--pg-text-sec); line-height: 1.5; margin: 0; }
/* Dept grid */
.ab-dept-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 3rem; }
.ab-dept-card { background: var(--pg-card); border: 1px solid var(--pg-border); border-radius: 16px; padding: 1.25rem; text-align: center; transition: border-color 0.2s; }
.ab-dept-card:hover { border-color: color-mix(in srgb, var(--pg-accent) 40%, transparent); }
.ab-dept-icon { font-size: 1.5rem; color: var(--pg-accent); margin-bottom: 0.5rem; }
.ab-dept-name { font-size: 0.9rem; font-weight: 700; color: var(--pg-text); margin: 0 0 0.25rem; }
.ab-dept-desc { font-size: 0.78rem; color: var(--pg-text-sec); margin: 0; }
/* Story grid */
.ab-story-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; margin-bottom: 3rem; }
@media (max-width: 768px) { .ab-story-grid { grid-template-columns: 1fr; gap: 2rem; } }
.ab-story-text { font-size: 0.95rem; color: var(--pg-text-sec); line-height: 1.8; }
.ab-story-text p { margin: 0 0 1rem; }
.ab-story-text p:last-child { margin: 0; }
.ab-story-visual { background: color-mix(in srgb, var(--pg-accent) 8%, var(--pg-bg)); border: 1px solid var(--pg-border); border-radius: 24px; padding: 2.5rem; display: flex; flex-direction: column; gap: 1.25rem; }
.ab-story-stat { display: flex; align-items: center; gap: 1rem; }
.ab-story-stat-icon { width: 40px; height: 40px; border-radius: 10px; background: color-mix(in srgb, var(--pg-accent) 15%, var(--pg-bg)); display: flex; align-items: center; justify-content: center; color: var(--pg-accent); flex-shrink: 0; }
.ab-story-stat-val { font-size: 1.25rem; font-weight: 800; color: var(--pg-text); }
.ab-story-stat-lbl { font-size: 0.78rem; color: var(--pg-text-muted); }
/* Timeline */
.ab-timeline { position: relative; padding-left: 2rem; }
.ab-timeline::before { content: ''; position: absolute; left: 0.5rem; top: 0; bottom: 0; width: 2px; background: linear-gradient(to bottom, var(--pg-accent), var(--pg-border)); border-radius: 1px; }
.ab-timeline-item { position: relative; margin-bottom: 1.75rem; }
.ab-timeline-dot { position: absolute; left: -1.65rem; top: 0.4rem; width: 14px; height: 14px; border-radius: 50%; background: var(--pg-accent); border: 2px solid var(--pg-bg); box-shadow: 0 0 0 3px color-mix(in srgb, var(--pg-accent) 30%, transparent); }
.ab-timeline-card { background: var(--pg-card); border: 1px solid var(--pg-border); border-radius: 16px; padding: 1.5rem; transition: border-color 0.2s; }
.ab-timeline-card:hover { border-color: color-mix(in srgb, var(--pg-accent) 40%, transparent); }
.ab-timeline-header { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.4rem; }
.ab-timeline-title { font-size: 1rem; font-weight: 700; color: var(--pg-text); margin: 0; }
.ab-timeline-company { color: var(--pg-accent); font-weight: 600; font-size: 0.875rem; margin: 0.2rem 0 0; }
.ab-timeline-date { font-size: 0.78rem; color: var(--pg-text-muted); white-space: nowrap; }
.ab-timeline-desc { color: var(--pg-text-sec); font-size: 0.875rem; line-height: 1.6; margin: 0.5rem 0 0; }
/* Certifications / Badges */
.ab-cert-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem; margin-bottom: 3rem; }
.ab-cert-card { background: var(--pg-card); border: 1px solid var(--pg-border); border-radius: 16px; padding: 1.25rem; display: flex; gap: 1rem; align-items: flex-start; transition: border-color 0.2s; }
.ab-cert-card:hover { border-color: color-mix(in srgb, var(--pg-accent) 40%, transparent); }
.ab-cert-icon { width: 42px; height: 42px; border-radius: 10px; background: color-mix(in srgb, var(--pg-accent) 15%, transparent); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--pg-accent); font-size: 1.1rem; }
.ab-cert-name { font-size: 0.9rem; font-weight: 700; color: var(--pg-text); margin: 0 0 0.2rem; }
.ab-cert-org { color: var(--pg-text-sec); font-size: 0.82rem; margin: 0 0 0.2rem; }
.ab-cert-date { color: var(--pg-text-muted); font-size: 0.78rem; margin: 0; }
/* CTA */
.ab-cta-band { background: linear-gradient(135deg, var(--navbar-bg, #16a34a), color-mix(in srgb, var(--navbar-bg, #16a34a) 70%, #000)); border-radius: 24px; padding: 3rem 2rem; text-align: center; margin-top: 4rem; }
.ab-cta-band h2 { font-size: 1.9rem; font-weight: 800; color: #fff; margin: 0 0 0.75rem; }
.ab-cta-band p { color: rgba(255,255,255,0.85); margin: 0 0 2rem; }
.ab-cta-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2.5rem; background: #fff; color: var(--navbar-bg, #16a34a); border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 1rem; transition: opacity 0.2s, transform 0.2s; }
.ab-cta-btn:hover { opacity: 0.9; transform: translateY(-1px); }
.ab-cta-btn-sec { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2rem; background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.4); border-radius: 50px; font-weight: 600; text-decoration: none; font-size: 0.95rem; transition: all 0.2s; margin-left: 1rem; }
.ab-cta-btn-sec:hover { background: rgba(255,255,255,0.25); }
@media (max-width: 768px) { .ab-cta-band { padding: 2rem 1.25rem; } .ab-cta-band h2 { font-size: 1.4rem; } .ab-cta-btn-sec { margin-left: 0; margin-top: 0.75rem; } }
/* Factsheet table */
.ab-factsheet { background: var(--pg-card); border: 1px solid var(--pg-border); border-radius: 16px; overflow: hidden; margin-bottom: 3rem; }
.ab-factsheet-row { display: grid; grid-template-columns: 200px 1fr; border-bottom: 1px solid var(--pg-border); }
.ab-factsheet-row:last-child { border-bottom: none; }
.ab-factsheet-key { padding: 0.875rem 1.25rem; font-size: 0.82rem; font-weight: 700; color: var(--pg-text-muted); text-transform: uppercase; letter-spacing: 0.05em; background: color-mix(in srgb, var(--pg-accent) 5%, var(--pg-card)); border-right: 1px solid var(--pg-border); }
.ab-factsheet-val { padding: 0.875rem 1.25rem; font-size: 0.9rem; color: var(--pg-text); font-weight: 500; }
@media (max-width: 600px) { .ab-factsheet-row { grid-template-columns: 1fr; } .ab-factsheet-key { border-right: none; border-bottom: 1px solid var(--pg-border); } }
</style>
@endpush

@section('content')
@php
    $accent      = $accentColor ?? '#6366f1';
    $tmpl        = $template ?? 'consultant';
    $isEcommerce = ($tmpl === 'ecommerce');

    // Core profile data from settings
    $profTitle   = $settings['profile_title']   ?? ($tenant->profession ?? 'Professional');
    $profAbout   = $settings['profile_about']   ?? ($tenant->bio ?? '');
    $profYears   = $settings['profile_years']   ?? null;
    $profClients = $settings['profile_clients'] ?? null;
    $profProjects= $settings['profile_projects']?? null;
    $profTeam    = $settings['profile_team_size'] ?? null;

    // Contact info
    $contactEmail   = $settings['contact_email']   ?? ($tenant->email ?? null);
    $contactPhone   = $settings['contact_phone']   ?? ($tenant->phone ?? null);
    $contactAddress = $settings['contact_address'] ?? (($tenant->city ?? '') . ($tenant->state ? ', ' . $tenant->state : ''));
    $contactWebsite = $settings['contact_website'] ?? ($tenant->website ?? null);

    // Page heading
    $aboutHeroData  = $aboutPage ? $aboutPage->getSectionData('hero') : [];
    $_rawHeading    = $aboutHeroData['heading'] ?? '';
    $_genericH      = ['About Me', 'About', 'About Us'];
    $pageHeading    = (!empty($_rawHeading) && !in_array(trim($_rawHeading), $_genericH))
                        ? $_rawHeading
                        : 'About ' . $siteName;
    $pageSub        = !empty($aboutHeroData['subheading']) ? $aboutHeroData['subheading'] : $profTitle;

    // Profession badge label
    $professionBadge = $profTitle;

    // Stats strip — built from available data
    $stats = [];
    if ($profYears)    $stats[] = ['value' => $profYears,    'label' => 'Years Experience'];
    if ($profClients)  $stats[] = ['value' => $profClients,  'label' => ($isEcommerce ? 'Happy Clients' : 'Clients Served')];
    if ($profProjects) $stats[] = ['value' => $profProjects, 'label' => ($isEcommerce ? 'Products' : 'Projects Done')];
    if ($profTeam)     $stats[] = ['value' => $profTeam,     'label' => 'Team Members'];
    // Fallback stats for ecommerce if none set
    if ($isEcommerce && empty($stats)) {
        $stats = [
            ['value' => $settings['profile_years'] ?? '38+',  'label' => 'Years Experience'],
            ['value' => $settings['profile_clients'] ?? '500+','label' => 'Happy Clients'],
            ['value' => $settings['profile_projects'] ?? '5000+','label' => 'Products'],
        ];
    }

    // Services from settings
    $servicesRaw = $settings['profile_services'] ?? null;
    $services    = $servicesRaw ? (json_decode($servicesRaw, true) ?? []) : [];

    // Skills / expertise
    $expertiseRaw = $settings['profile_expertise'] ?? $settings['profile_practice_areas'] ?? null;
    $expertiseTags = $expertiseRaw ? (json_decode($expertiseRaw, true) ?? []) : [];

    // Features (ecommerce: features_1_*, features_2_*, etc.)
    $features = [];
    for ($fi = 1; $fi <= 6; $fi++) {
        $ftitle = $settings["features_{$fi}_title"] ?? null;
        if ($ftitle) {
            $features[] = [
                'icon'  => $settings["features_{$fi}_icon"]  ?? 'fas fa-check',
                'title' => $ftitle,
                'desc'  => $settings["features_{$fi}_desc"]  ?? '',
            ];
        }
    }

    // CTA URLs
    $tenantShopUrl    = '/' . $tenant->username . '/shop';
    $tenantContactUrl = '/' . $tenant->username . '/contact';

    // Est year badge
    $estYear = $settings['profile_est_year'] ?? ($settings['about_est_year'] ?? null);

    // Business info (ecommerce factsheet)
    $businessInfo = $tenant->business_info ? (is_array($tenant->business_info) ? $tenant->business_info : json_decode($tenant->business_info, true)) : [];
@endphp

{{-- ══ HERO ══ --}}
<section class="ab-hero">
    <div class="ab-hero-inner">
        <div class="ab-logo-col">
            <div class="ab-logo-frame">
                @if($logoPath)
                    <img src="{{ $logoPath }}" alt="{{ $siteName }}">
                @else
                    <div class="ab-logo-initials">{{ strtoupper(substr($siteName, 0, 1)) }}</div>
                @endif
            </div>
            @if($estYear)
            <div class="ab-est-badge"><i class="fas fa-award"></i> Est. {{ $estYear }}</div>
            @endif
        </div>
        <div class="ab-info-col">
            <div class="ab-profession-badge">
                <i class="fas {{ $isEcommerce ? 'fa-industry' : ($tmpl === 'advocate' ? 'fa-balance-scale' : ($tmpl === 'influencer' ? 'fa-star' : ($tmpl === 'entrepreneur' ? 'fa-rocket' : 'fa-user-tie'))) }}"></i>
                {{ $professionBadge }}
            </div>
            <h1 class="ab-name">{{ $pageHeading }}</h1>
            <p class="ab-tagline">{{ $pageSub }}</p>
            @if($profAbout)
            <p class="ab-bio">{{ $profAbout }}</p>
            @endif
            @if(!empty($stats))
            <div class="ab-stats-strip">
                @foreach($stats as $stat)
                <div>
                    <div class="ab-stat-value">{{ $stat['value'] }}</div>
                    <div class="ab-stat-label">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
            @endif
            <div class="ab-contact-row">
                @if($contactEmail)
                <a href="mailto:{{ $contactEmail }}" class="ab-contact-chip"><i class="fas fa-envelope"></i> {{ $contactEmail }}</a>
                @endif
                @if($contactPhone)
                <a href="tel:{{ $contactPhone }}" class="ab-contact-chip"><i class="fas fa-phone"></i> {{ $contactPhone }}</a>
                @endif
                @if($contactAddress)
                <span class="ab-contact-chip"><i class="fas fa-map-marker-alt"></i> {{ $contactAddress }}</span>
                @endif
                @if($contactWebsite)
                <a href="{{ $contactWebsite }}" target="_blank" class="ab-contact-chip"><i class="fas fa-globe"></i> Website</a>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ══ BODY ══ --}}
<div class="ab-body">

    {{-- ── ECOMMERCE TEMPLATE ── --}}
    @if($isEcommerce)

        {{-- Company Story --}}
        @php
            $storyTitle = $settings['about_title'] ?? ('About ' . $siteName);
            $storyDesc  = $settings['about_description'] ?? $profAbout;
        @endphp
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">Our Story</div>
                <h2 class="ab-section-title">{{ $storyTitle }}</h2>
            </div>
            <div class="ab-story-grid">
                <div class="ab-story-text">
                    <p>{{ $storyDesc }}</p>
                    @if(!empty($settings['about_story_extra']))
                    <p>{{ $settings['about_story_extra'] }}</p>
                    @endif
                </div>
                <div class="ab-story-visual">
                    @if(!empty($stats))
                    @foreach($stats as $stat)
                    <div class="ab-story-stat">
                        <div class="ab-story-stat-icon"><i class="fas fa-chart-line"></i></div>
                        <div>
                            <div class="ab-story-stat-val">{{ $stat['value'] }}</div>
                            <div class="ab-story-stat-lbl">{{ $stat['label'] }}</div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </section>
        <hr class="ab-divider">

        {{-- Features / Why Choose Us --}}
        @if(!empty($features))
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">Why Choose Us</div>
                <h2 class="ab-section-title">Our Strengths</h2>
                <p class="ab-section-sub">What makes {{ $siteName }} stand out</p>
            </div>
            <div class="ab-feature-grid">
                @foreach($features as $feat)
                <div class="ab-feature-card">
                    <div class="ab-feature-icon"><i class="{{ $feat['icon'] }}"></i></div>
                    <div class="ab-feature-title">{{ $feat['title'] }}</div>
                    <p class="ab-feature-desc">{{ $feat['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- Services / What We Offer --}}
        @if(!empty($services))
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">What We Offer</div>
                <h2 class="ab-section-title">Our Products & Services</h2>
                <p class="ab-section-sub">Explore our complete range</p>
            </div>
            <div class="ab-services-grid">
                @foreach($services as $svc)
                <div class="ab-service-card">
                    <div class="ab-service-icon">{{ $svc['icon'] ?? '📦' }}</div>
                    <div class="ab-service-title">{{ $svc['title'] ?? '' }}</div>
                    <p class="ab-service-desc">{{ $svc['text'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- Business Factsheet --}}
        @php
            $factsheet = [];
            if (!empty($businessInfo['nature']))       $factsheet['Nature of Business']    = $businessInfo['nature'];
            if (!empty($businessInfo['director']))     $factsheet['Managing Director']      = $businessInfo['director'];
            if (!empty($businessInfo['ceo']))          $factsheet['Company CEO']            = $businessInfo['ceo'];
            if (!empty($contactAddress))               $factsheet['Registered Address']     = $contactAddress;
            if (!empty($estYear))                      $factsheet['Year of Establishment']  = $estYear;
            if (!empty($businessInfo['employees']))    $factsheet['Total Employees']        = $businessInfo['employees'];
            if (!empty($businessInfo['legal_status'])) $factsheet['Legal Status']           = $businessInfo['legal_status'];
            if (!empty($businessInfo['turnover']))     $factsheet['Annual Turnover']        = $businessInfo['turnover'];
            if (!empty($businessInfo['gst']))          $factsheet['GST Registration']       = $businessInfo['gst'];
            if (!empty($businessInfo['products']))     $factsheet['Product Range']          = $businessInfo['products'];
        @endphp
        @if(!empty($factsheet))
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">Factsheet</div>
                <h2 class="ab-section-title">Basic Information</h2>
                <p class="ab-section-sub">Key facts about {{ $siteName }} at a glance</p>
            </div>
            <div class="ab-factsheet">
                @foreach($factsheet as $key => $val)
                <div class="ab-factsheet-row">
                    <div class="ab-factsheet-key">{{ $key }}</div>
                    <div class="ab-factsheet-val">{{ $val }}</div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- CTA --}}
        <div class="ab-cta-band">
            <h2>Ready to Explore Our Products?</h2>
            <p>Browse our complete catalog of premium products. Quality guaranteed.</p>
            <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:0.75rem;">
                <a href="{{ $tenantShopUrl }}" class="ab-cta-btn">
                    <i class="fas fa-shopping-bag"></i> Shop Now
                </a>
                <a href="{{ $tenantContactUrl }}" class="ab-cta-btn-sec">
                    <i class="fas fa-phone"></i> Contact Us
                </a>
            </div>
        </div>

    {{-- ── INFLUENCER TEMPLATE ── --}}
    @elseif($tmpl === 'influencer')

        {{-- About / Story --}}
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">My Story</div>
                <h2 class="ab-section-title">Who I Am</h2>
            </div>
            <p style="font-size:1rem;color:var(--pg-text-sec);line-height:1.8;max-width:720px;">{{ $profAbout }}</p>
        </section>
        <hr class="ab-divider">

        {{-- Content Niches / Expertise --}}
        @if(!empty($expertiseTags))
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.25rem;">
                <div class="ab-section-label">Content Niches</div>
                <h2 class="ab-section-title">What I Create</h2>
            </div>
            <div class="ab-skills-grid">
                @foreach($expertiseTags as $tag)
                <span class="ab-skill-tag">{{ $tag }}</span>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- Services --}}
        @if(!empty($services))
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">Collaborations</div>
                <h2 class="ab-section-title">What I Offer Brands</h2>
            </div>
            <div class="ab-services-grid">
                @foreach($services as $svc)
                <div class="ab-service-card">
                    <div class="ab-service-icon">{{ $svc['icon'] ?? '📸' }}</div>
                    <div class="ab-service-title">{{ $svc['title'] ?? '' }}</div>
                    <p class="ab-service-desc">{{ $svc['text'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- CTA --}}
        <div class="ab-cta-band">
            <h2>Let's Collaborate!</h2>
            <p>Interested in working together? I'd love to hear from you.</p>
            <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:0.75rem;">
                <a href="{{ $tenantContactUrl }}" class="ab-cta-btn">
                    <i class="fas fa-envelope"></i> Get in Touch
                </a>
            </div>
        </div>

    {{-- ── ADVOCATE TEMPLATE ── --}}
    @elseif($tmpl === 'advocate')

        {{-- About --}}
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">Background</div>
                <h2 class="ab-section-title">Professional Profile</h2>
            </div>
            <p style="font-size:1rem;color:var(--pg-text-sec);line-height:1.8;max-width:720px;">{{ $profAbout }}</p>
        </section>
        <hr class="ab-divider">

        {{-- Practice Areas --}}
        @if(!empty($expertiseTags))
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.25rem;">
                <div class="ab-section-label">Expertise</div>
                <h2 class="ab-section-title">Practice Areas</h2>
            </div>
            <div class="ab-skills-grid">
                @foreach($expertiseTags as $tag)
                <span class="ab-skill-tag">{{ $tag }}</span>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- Legal Services --}}
        @if(!empty($services))
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">Services</div>
                <h2 class="ab-section-title">Legal Services Offered</h2>
                <p class="ab-section-sub">Comprehensive legal representation and advisory</p>
            </div>
            <div class="ab-services-grid">
                @foreach($services as $svc)
                <div class="ab-service-card">
                    <div class="ab-service-icon">{{ $svc['icon'] ?? '⚖️' }}</div>
                    <div class="ab-service-title">{{ $svc['title'] ?? '' }}</div>
                    <p class="ab-service-desc">{{ $svc['text'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- Certifications / Bar --}}
        @if(!empty($certifications) && $certifications->count())
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">Credentials</div>
                <h2 class="ab-section-title">Bar Enrolment & Certifications</h2>
            </div>
            <div class="ab-cert-grid">
                @foreach($certifications as $cert)
                <div class="ab-cert-card">
                    <div class="ab-cert-icon"><i class="fas fa-certificate"></i></div>
                    <div>
                        <div class="ab-cert-name">{{ $cert->title }}</div>
                        <div class="ab-cert-org">{{ $cert->issuer ?? '' }}</div>
                        <div class="ab-cert-date">{{ $cert->issue_date ? \Carbon\Carbon::parse($cert->issue_date)->format('M Y') : '' }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- CTA --}}
        <div class="ab-cta-band">
            <h2>Need Legal Assistance?</h2>
            <p>Schedule a consultation to discuss your legal needs.</p>
            <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:0.75rem;">
                <a href="{{ $tenantContactUrl }}" class="ab-cta-btn">
                    <i class="fas fa-calendar-check"></i> Book Consultation
                </a>
            </div>
        </div>

    {{-- ── ENTREPRENEUR TEMPLATE ── --}}
    @elseif($tmpl === 'entrepreneur')

        {{-- About --}}
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">Background</div>
                <h2 class="ab-section-title">My Journey</h2>
            </div>
            <p style="font-size:1rem;color:var(--pg-text-sec);line-height:1.8;max-width:720px;">{{ $profAbout }}</p>
        </section>
        <hr class="ab-divider">

        {{-- Industries / Expertise --}}
        @php
            $industriesRaw = $settings['profile_industries'] ?? null;
            $industries = $industriesRaw ? (is_array($industriesRaw) ? $industriesRaw : explode(',', $industriesRaw)) : [];
        @endphp
        @if(!empty($industries))
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.25rem;">
                <div class="ab-section-label">Focus Areas</div>
                <h2 class="ab-section-title">Industries & Domains</h2>
            </div>
            <div class="ab-skills-grid">
                @foreach($industries as $ind)
                <span class="ab-skill-tag">{{ trim($ind) }}</span>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- Services --}}
        @if(!empty($services))
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">What I Do</div>
                <h2 class="ab-section-title">Advisory & Services</h2>
            </div>
            <div class="ab-services-grid">
                @foreach($services as $svc)
                <div class="ab-service-card">
                    <div class="ab-service-icon">{{ $svc['icon'] ?? '🚀' }}</div>
                    <div class="ab-service-title">{{ $svc['title'] ?? '' }}</div>
                    <p class="ab-service-desc">{{ $svc['text'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- Experience --}}
        @if(!empty($experiences) && $experiences->count())
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">Experience</div>
                <h2 class="ab-section-title">Ventures & Roles</h2>
            </div>
            <div class="ab-timeline">
                @foreach($experiences as $exp)
                <div class="ab-timeline-item">
                    <div class="ab-timeline-dot"></div>
                    <div class="ab-timeline-card">
                        <div class="ab-timeline-header">
                            <div>
                                <div class="ab-timeline-title">{{ $exp->title }}</div>
                                <div class="ab-timeline-company">{{ $exp->company }}</div>
                            </div>
                            <div class="ab-timeline-date">
                                {{ $exp->start_date ? \Carbon\Carbon::parse($exp->start_date)->format('M Y') : '' }}
                                — {{ $exp->is_current ? 'Present' : ($exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : '') }}
                            </div>
                        </div>
                        @if($exp->description)
                        <div class="ab-timeline-desc">{{ $exp->description }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- CTA --}}
        <div class="ab-cta-band">
            <h2>Let's Build Something Together</h2>
            <p>Interested in collaborating, investing, or getting mentorship? Reach out.</p>
            <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:0.75rem;">
                <a href="{{ $tenantContactUrl }}" class="ab-cta-btn">
                    <i class="fas fa-handshake"></i> Connect With Me
                </a>
            </div>
        </div>

    {{-- ── CONSULTANT / DEFAULT TEMPLATE ── --}}
    @else

        {{-- About --}}
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">Background</div>
                <h2 class="ab-section-title">About Me</h2>
            </div>
            <p style="font-size:1rem;color:var(--pg-text-sec);line-height:1.8;max-width:720px;">{{ $profAbout }}</p>
        </section>
        <hr class="ab-divider">

        {{-- Skills --}}
        @if(!empty($skills) && $skills->count())
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.25rem;">
                <div class="ab-section-label">Expertise</div>
                <h2 class="ab-section-title">Skills & Technologies</h2>
            </div>
            <div class="ab-skills-grid">
                @foreach($skills as $skill)
                <span class="ab-skill-tag">{{ $skill->name }}</span>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @elseif(!empty($expertiseTags))
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.25rem;">
                <div class="ab-section-label">Expertise</div>
                <h2 class="ab-section-title">Skills & Technologies</h2>
            </div>
            <div class="ab-skills-grid">
                @foreach($expertiseTags as $tag)
                <span class="ab-skill-tag">{{ $tag }}</span>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- Services --}}
        @if(!empty($services))
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">What I Do</div>
                <h2 class="ab-section-title">Services Offered</h2>
            </div>
            <div class="ab-services-grid">
                @foreach($services as $svc)
                <div class="ab-service-card">
                    <div class="ab-service-icon">{{ $svc['icon'] ?? '💼' }}</div>
                    <div class="ab-service-title">{{ $svc['title'] ?? '' }}</div>
                    <p class="ab-service-desc">{{ $svc['text'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- Experience --}}
        @if(!empty($experiences) && $experiences->count())
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">Experience</div>
                <h2 class="ab-section-title">Work History</h2>
            </div>
            <div class="ab-timeline">
                @foreach($experiences as $exp)
                <div class="ab-timeline-item">
                    <div class="ab-timeline-dot"></div>
                    <div class="ab-timeline-card">
                        <div class="ab-timeline-header">
                            <div>
                                <div class="ab-timeline-title">{{ $exp->title }}</div>
                                <div class="ab-timeline-company">{{ $exp->company }}</div>
                            </div>
                            <div class="ab-timeline-date">
                                {{ $exp->start_date ? \Carbon\Carbon::parse($exp->start_date)->format('M Y') : '' }}
                                — {{ $exp->is_current ? 'Present' : ($exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : '') }}
                            </div>
                        </div>
                        @if($exp->description)
                        <div class="ab-timeline-desc">{{ $exp->description }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- Education --}}
        @if(!empty($education) && $education->count())
        <section style="margin-bottom:3.5rem;">
            <div style="margin-bottom:1.75rem;">
                <div class="ab-section-label">Education</div>
                <h2 class="ab-section-title">Academic Background</h2>
            </div>
            <div class="ab-timeline">
                @foreach($education as $edu)
                <div class="ab-timeline-item">
                    <div class="ab-timeline-dot"></div>
                    <div class="ab-timeline-card">
                        <div class="ab-timeline-header">
                            <div>
                                <div class="ab-timeline-title">{{ $edu->degree }}</div>
                                <div class="ab-timeline-company">{{ $edu->institution }}</div>
                            </div>
                            <div class="ab-timeline-date">
                                {{ $edu->start_date ? \Carbon\Carbon::parse($edu->start_date)->format('Y') : '' }}
                                — {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('Y') : 'Present' }}
                            </div>
                        </div>
                        @if($edu->description)
                        <div class="ab-timeline-desc">{{ $edu->description }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        <hr class="ab-divider">
        @endif

        {{-- CTA --}}
        <div class="ab-cta-band">
            <h2>Let's Work Together</h2>
            <p>Have a project in mind? I'd love to hear about it.</p>
            <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:0.75rem;">
                <a href="{{ $tenantContactUrl }}" class="ab-cta-btn">
                    <i class="fas fa-envelope"></i> Get in Touch
                </a>
            </div>
        </div>

    @endif

</div>
@endsection
