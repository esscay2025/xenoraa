@extends('layouts.app')
@section('title', ($profile['name'] ?? $tenant->name) . ' — Advocate & Legal Consultant')
@section('description', $profile['about'] ?? 'Experienced advocate offering legal consultation, litigation, and advisory services.')
@section('content')
@php
    $accent = $accentColor ?? '#b45309';
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
    $blogData         = $homePage ? $homePage->getSectionData('blog')         : [];
    $contactData      = $homePage ? $homePage->getSectionData('contact')      : [];
    $heroHeading    = $heroData['heading']    ?? $profile['name']    ?? $tenant->name;
    $heroSubheading = $heroData['subheading'] ?? $profile['tagline'] ?? ($profile['title'] ?? 'Advocate & Legal Consultant');
    $heroCta        = $heroData['cta_text']   ?? 'Book Free Consultation';
    $heroCtaUrl     = $heroData['cta_url']    ?? ($profile['consultation_link'] ?? ($tenantContactUrl ?? '/contact'));
    $statsItems = $statsData['items'] ?? $profile['stats'] ?? [
        ['icon' => '⚖️', 'value' => $profile['years_experience'] ?? '18+',  'label' => 'Years Experience'],
        ['icon' => '🏛️', 'value' => $profile['cases_won']        ?? '1200+', 'label' => 'Cases Won'],
        ['icon' => '👥', 'value' => $profile['clients_served']   ?? '800+',  'label' => 'Clients Served'],
        ['icon' => '🏆', 'value' => $profile['courts']           ?? '15+',   'label' => 'Courts Practiced'],
    ];
    $aboutText = $aboutData['text'] ?? $profile['about'] ?? 'With over 18 years of distinguished legal practice, I bring deep expertise in civil, criminal, and corporate law. My approach combines rigorous legal analysis with practical, client-focused solutions to achieve the best possible outcomes.';
    $services = $servicesData['items'] ?? $profile['services'] ?? [
        ['icon' => '⚖️', 'title' => 'Civil Litigation',      'text' => 'Property disputes, contract enforcement, injunctions, and civil appeals across all courts'],
        ['icon' => '🏛️', 'title' => 'Criminal Defense',      'text' => 'Bail applications, trial defense, appeals, and white-collar crime representation'],
        ['icon' => '🏢', 'title' => 'Corporate Law',          'text' => 'Mergers & acquisitions, compliance, IPR, and corporate governance advisory'],
        ['icon' => '👨‍👩‍👧', 'title' => 'Family Law',           'text' => 'Divorce, child custody, maintenance, succession, and matrimonial disputes'],
        ['icon' => '🏗️', 'title' => 'Property & Real Estate', 'text' => 'Title verification, registration, RERA disputes, and landlord-tenant matters'],
        ['icon' => '📋', 'title' => 'Contract Drafting',      'text' => 'Agreements, MoUs, employment contracts, and legal documentation'],
    ];
    $testimonials = $testimonialsData['items'] ?? $profile['testimonials'] ?? [
        ['name' => 'Ramesh Kumar',   'role' => 'Managing Director, Kumar Industries', 'text' => 'Exceptional legal acumen. Won a complex corporate dispute that had been pending for 3 years. Highly recommended.'],
        ['name' => 'Sunita Sharma',  'role' => 'Client, Family Law Matter',           'text' => 'Compassionate, thorough, and effective. Handled my divorce case with utmost professionalism and sensitivity.'],
        ['name' => 'Anil Patel',     'role' => 'CEO, Patel Constructions',            'text' => 'Outstanding property law expertise. Resolved a major title dispute that saved our ₹50 crore project.'],
    ];
    $contactHeading = $contactData['heading']     ?? "Need Legal Advice?";
    $contactText    = $contactData['text']        ?? "Schedule a confidential consultation to discuss your legal matter. Initial consultation is free.";
    $contactBtn     = $contactData['button_text'] ?? 'Book Free Consultation';
    $contactBtnUrl  = $contactData['button_url']  ?? ($profile['consultation_link'] ?? ($tenantContactUrl ?? '/contact'));
    $tenantBase     = isset($tenant) && $tenant->custom_domain ? '' : ('/' . ($tenant->username ?? ''));
@endphp
<style>
:root { --adv-accent: {{ $accent }}; }
.xn-adv-wrap { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 4rem; }
/* HERO */
.xn-adv-hero { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; padding: 5rem 0 4rem; }
@media(max-width:768px){ .xn-adv-hero{grid-template-columns:1fr;text-align:center;gap:2rem;padding:3rem 0 2rem;} }
.xn-adv-hero-credential { display: inline-flex; align-items: center; gap: 0.5rem; background: color-mix(in srgb, var(--adv-accent) 10%, transparent); color: var(--adv-accent); font-size: 0.72rem; font-weight: 800; padding: 0.35rem 1rem; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.08em; border: 1px solid color-mix(in srgb, var(--adv-accent) 22%, transparent); margin-bottom: 1.25rem; }
.xn-adv-hero-name { font-size: clamp(2.2rem, 4vw, 3.5rem); font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 0.5rem; }
.xn-adv-hero-title { font-size: 1.05rem; color: var(--adv-accent); font-weight: 600; margin-bottom: 0.75rem; }
.xn-adv-hero-enroll { font-size: 0.8rem; color: #64748b; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
@media(max-width:768px){ .xn-adv-hero-enroll{justify-content:center;} }
.xn-adv-hero-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; }
@media(max-width:768px){ .xn-adv-hero-tags{justify-content:center;} }
.xn-adv-tag { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #e2e8f0; padding: 0.3rem 0.85rem; border-radius: 4px; font-size: 0.78rem; font-weight: 600; }
.xn-adv-hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
@media(max-width:768px){ .xn-adv-hero-actions{justify-content:center;} }
.xn-adv-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.75rem; border-radius: 6px; font-size: 0.9rem; font-weight: 700; text-decoration: none; transition: all 0.25s; cursor: pointer; border: none; }
.xn-adv-btn-primary { background: var(--adv-accent); color: #fff; box-shadow: 0 8px 24px color-mix(in srgb, var(--adv-accent) 30%, transparent); }
.xn-adv-btn-primary:hover { transform: translateY(-2px); color:#fff; }
.xn-adv-btn-outline { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,0.22); }
.xn-adv-btn-outline:hover { background: rgba(255,255,255,0.07); color:#fff; }
.xn-adv-hero-img { border-radius: 4px; overflow: hidden; aspect-ratio: 3/4; background: #1a1a1a; display: flex; align-items: center; justify-content: center; font-size: 6rem; border: 1px solid rgba(255,255,255,0.07); position: relative; }
.xn-adv-hero-img img { width: 100%; height: 100%; object-fit: cover; }
.xn-adv-hero-seal { position: absolute; bottom: 1.5rem; right: 1.5rem; width: 80px; height: 80px; border-radius: 50%; background: rgba(0,0,0,0.85); border: 2px solid var(--adv-accent); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; }
.xn-adv-hero-seal-num { font-size: 1.2rem; font-weight: 900; color: var(--adv-accent); line-height: 1; }
.xn-adv-hero-seal-label { font-size: 0.55rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
/* STATS */
.xn-adv-stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1px; background: rgba(255,255,255,0.07); border-radius: 8px; overflow: hidden; border: 1px solid rgba(255,255,255,0.07); margin-bottom: 4rem; }
.xn-adv-stat-cell { background: #1a1a1a; padding: 2rem 1rem; text-align: center; transition: background 0.2s; }
.xn-adv-stat-cell:hover { background: color-mix(in srgb, var(--adv-accent) 6%, #1a1a1a); }
.xn-adv-stat-icon { font-size: 1.5rem; margin-bottom: 0.5rem; }
.xn-adv-stat-num { font-size: 2rem; font-weight: 900; color: #fff; line-height: 1; }
.xn-adv-stat-label { font-size: 0.72rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.3rem; }
/* SECTION */
.xn-adv-section { padding: 4rem 0; }
.xn-adv-section-header { text-align: center; margin-bottom: 3rem; }
.xn-adv-badge { display: inline-block; background: color-mix(in srgb, var(--adv-accent) 10%, transparent); color: var(--adv-accent); font-size: 0.7rem; font-weight: 800; padding: 0.3rem 0.9rem; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem; border: 1px solid color-mix(in srgb, var(--adv-accent) 22%, transparent); }
.xn-adv-section-title { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: #fff; margin: 0 0 0.5rem; }
.xn-adv-section-sub { font-size: 1rem; color: #64748b; max-width: 520px; margin: 0 auto; }
.xn-adv-divider { width: 48px; height: 3px; background: var(--adv-accent); border-radius: 2px; margin: 1rem auto 0; }
/* PRACTICE AREAS */
.xn-adv-practice-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-adv-practice-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 8px; padding: 1.75rem; transition: all 0.25s; border-top: 3px solid transparent; }
.xn-adv-practice-card:hover { border-top-color: var(--adv-accent); transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.3); }
.xn-adv-practice-icon { font-size: 2rem; margin-bottom: 1rem; }
.xn-adv-practice-title { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; }
.xn-adv-practice-text { font-size: 0.85rem; color: #64748b; line-height: 1.6; }
/* ABOUT */
.xn-adv-about-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 3rem; align-items: start; }
@media(max-width:768px){ .xn-adv-about-grid{grid-template-columns:1fr;} }
.xn-adv-about-text { font-size: 1rem; color: #94a3b8; line-height: 1.9; margin-bottom: 1.5rem; }
.xn-adv-about-img { border-radius: 8px; overflow: hidden; aspect-ratio: 3/4; background: #1a1a1a; display: flex; align-items: center; justify-content: center; font-size: 5rem; border: 1px solid rgba(255,255,255,0.07); }
.xn-adv-about-img img { width: 100%; height: 100%; object-fit: cover; }
/* CONTACT INFO */
.xn-adv-contact-box { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 8px; padding: 2rem; }
.xn-adv-contact-row { display: flex; align-items: flex-start; gap: 1rem; padding: 0.875rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
.xn-adv-contact-row:last-child { border-bottom: none; }
.xn-adv-contact-icon { width: 40px; height: 40px; border-radius: 8px; background: color-mix(in srgb, var(--adv-accent) 12%, transparent); border: 1px solid color-mix(in srgb, var(--adv-accent) 22%, transparent); display: flex; align-items: center; justify-content: center; color: var(--adv-accent); flex-shrink: 0; }
.xn-adv-contact-label { font-size: 0.72rem; color: #64748b; margin-bottom: 0.2rem; text-transform: uppercase; letter-spacing: 0.05em; }
.xn-adv-contact-value { font-size: 0.9rem; color: #e2e8f0; font-weight: 500; }
/* TESTIMONIALS */
.xn-adv-testimonials-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-adv-testimonial-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-left: 3px solid var(--adv-accent); border-radius: 8px; padding: 1.75rem; }
.xn-adv-testimonial-text { font-size: 0.9rem; color: #94a3b8; line-height: 1.7; margin-bottom: 1.25rem; font-style: italic; }
.xn-adv-testimonial-author { display: flex; align-items: center; gap: 0.75rem; }
.xn-adv-testimonial-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--adv-accent); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 700; color: #fff; flex-shrink: 0; }
.xn-adv-testimonial-name { font-size: 0.875rem; font-weight: 700; color: #fff; }
.xn-adv-testimonial-role { font-size: 0.75rem; color: #64748b; }
/* CTA */
.xn-adv-cta { background: linear-gradient(135deg, color-mix(in srgb, var(--adv-accent) 10%, transparent) 0%, rgba(0,0,0,0.3) 100%); border: 1px solid color-mix(in srgb, var(--adv-accent) 20%, transparent); border-radius: 8px; padding: 4rem 2rem; text-align: center; }
.xn-adv-cta h2 { font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 900; color: #fff; margin-bottom: 1rem; }
.xn-adv-cta p { font-size: 1rem; color: #94a3b8; max-width: 500px; margin: 0 auto 2rem; }
@media(max-width:640px){
    .xn-adv-practice-grid,.xn-adv-testimonials-grid{grid-template-columns:1fr;}
    .xn-adv-stats-row{grid-template-columns:repeat(2,1fr);}
}
</style>

<div class="xn-adv-wrap">

{{-- HERO --}}
@if($_show('hero'))
<div class="xn-adv-hero">
    <div>
        <div class="xn-adv-hero-credential"><i class="fas fa-gavel"></i> Advocate &amp; Legal Consultant</div>
        <h1 class="xn-adv-hero-name">{{ $heroHeading }}</h1>
        <div class="xn-adv-hero-title">{{ $heroSubheading }}</div>
        @if(!empty($profile['enrollment_no']))<div class="xn-adv-hero-enroll"><i class="fas fa-id-card"></i> Bar Council Enrollment: {{ $profile['enrollment_no'] }}</div>@endif
        <div class="xn-adv-hero-tags">
            @foreach($profile['practice_areas'] ?? ['Civil Law','Criminal Law','Corporate Law','Family Law'] as $area)
            <span class="xn-adv-tag">{{ $area }}</span>
            @endforeach
        </div>
        <div class="xn-adv-hero-actions">
            <a href="{{ $heroCtaUrl }}" class="xn-adv-btn xn-adv-btn-primary"><i class="fas fa-calendar"></i> {{ $heroCta }}</a>
            @if(!empty($profile['phone']))<a href="tel:{{ $profile['phone'] }}" class="xn-adv-btn xn-adv-btn-outline"><i class="fas fa-phone"></i> Call Now</a>@endif
        </div>
    </div>
    <div class="xn-adv-hero-img">
        @if($tenant->avatar)<img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}">
        @else⚖️@endif
        @if(!empty($statsItems[0]))
        <div class="xn-adv-hero-seal">
            <div class="xn-adv-hero-seal-num">{{ $statsItems[0]['value'] ?? '18+' }}</div>
            <div class="xn-adv-hero-seal-label">{{ $statsItems[0]['label'] ?? 'Years' }}</div>
        </div>
        @endif
    </div>
</div>
@endif

{{-- STATS --}}
@if($_show('stats'))
<div class="xn-adv-stats-row">
    @foreach($statsItems as $stat)
    <div class="xn-adv-stat-cell">
        @if(!empty($stat['icon']))<div class="xn-adv-stat-icon">{{ $stat['icon'] }}</div>@endif
        <div class="xn-adv-stat-num">{{ $stat['value'] ?? $stat['num'] ?? '' }}</div>
        <div class="xn-adv-stat-label">{{ $stat['label'] ?? '' }}</div>
    </div>
    @endforeach
</div>
@endif

{{-- PRACTICE AREAS --}}
@if($_show('services'))
<div class="xn-adv-section" style="padding-top:0;">
    <div class="xn-adv-section-header">
        <div class="xn-adv-badge">Legal Services</div>
        <h2 class="xn-adv-section-title">{{ $servicesData['heading'] ?? 'Practice Areas' }}</h2>
        @if(!empty($servicesData['subheading']))<p class="xn-adv-section-sub">{{ $servicesData['subheading'] }}</p>@endif
        <div class="xn-adv-divider"></div>
    </div>
    <div class="xn-adv-practice-grid">
        @foreach($services as $svc)
        <div class="xn-adv-practice-card">
            <div class="xn-adv-practice-icon">{{ $svc['icon'] ?? '⚖️' }}</div>
            <div class="xn-adv-practice-title">{{ $svc['title'] ?? $svc['name'] ?? '' }}</div>
            <div class="xn-adv-practice-text">{{ $svc['text'] ?? $svc['description'] ?? $svc['desc'] ?? '' }}</div>
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ url($tenantBase . '/practice-areas') }}" class="xn-adv-btn xn-adv-btn-outline"><i class="fas fa-gavel"></i> All Practice Areas</a>
    </div>
</div>
@endif

{{-- ABOUT --}}
@if($_show('about'))
<div class="xn-adv-section">
    <div class="xn-adv-about-grid">
        <div class="xn-adv-about-img">
            @if(!empty($aboutData['image']))<img src="{{ $aboutData['image'] }}" alt="{{ $tenant->name }}">
            @elseif($tenant->avatar)<img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}">
            @else⚖️@endif
        </div>
        <div>
            <div class="xn-adv-badge">About</div>
            <h2 class="xn-adv-section-title" style="text-align:left;margin-bottom:1rem;">{{ $aboutData['heading'] ?? 'About Me' }}</h2>
            <p class="xn-adv-about-text">{{ $aboutData['text'] ?? $profile['about'] ?? $aboutText }}</p>
            @if(!empty($profile['chamber']) || !empty($profile['court']) || !empty($profile['phone']) || !empty($profile['email']))
            <div class="xn-adv-contact-box" style="margin-bottom:1.5rem;">
                @if(!empty($profile['phone']))<div class="xn-adv-contact-row"><div class="xn-adv-contact-icon"><i class="fas fa-phone"></i></div><div><div class="xn-adv-contact-label">Phone</div><div class="xn-adv-contact-value">{{ $profile['phone'] }}</div></div></div>@endif
                @if(!empty($profile['email']))<div class="xn-adv-contact-row"><div class="xn-adv-contact-icon"><i class="fas fa-envelope"></i></div><div><div class="xn-adv-contact-label">Email</div><div class="xn-adv-contact-value">{{ $profile['email'] }}</div></div></div>@endif
                @if(!empty($profile['chamber']))<div class="xn-adv-contact-row"><div class="xn-adv-contact-icon"><i class="fas fa-map-marker-alt"></i></div><div><div class="xn-adv-contact-label">Chamber</div><div class="xn-adv-contact-value">{{ $profile['chamber'] }}</div></div></div>@endif
                @if(!empty($profile['court']))<div class="xn-adv-contact-row"><div class="xn-adv-contact-icon"><i class="fas fa-landmark"></i></div><div><div class="xn-adv-contact-label">Court</div><div class="xn-adv-contact-value">{{ $profile['court'] }}</div></div></div>@endif
            </div>
            @endif
            <a href="{{ url($tenantBase . '/about') }}" class="xn-adv-btn xn-adv-btn-primary"><i class="fas fa-user-tie"></i> Full Profile</a>
        </div>
    </div>
</div>
@endif

{{-- TESTIMONIALS --}}
@if($_show('testimonials'))
<div class="xn-adv-section">
    <div class="xn-adv-section-header">
        <div class="xn-adv-badge">Client Testimonials</div>
        <h2 class="xn-adv-section-title">{{ $testimonialsData['heading'] ?? 'What Clients Say' }}</h2>
        <div class="xn-adv-divider"></div>
    </div>
    <div class="xn-adv-testimonials-grid">
        @foreach($testimonials as $t)
        <div class="xn-adv-testimonial-card">
            <p class="xn-adv-testimonial-text">"{{ $t['text'] ?? '' }}"</p>
            <div class="xn-adv-testimonial-author">
                <div class="xn-adv-testimonial-avatar">{{ strtoupper(substr($t['name'] ?? 'A', 0, 1)) }}</div>
                <div>
                    <div class="xn-adv-testimonial-name">{{ $t['name'] ?? '' }}</div>
                    <div class="xn-adv-testimonial-role">{{ $t['role'] ?? '' }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- BLOG --}}
@if($_show('blog'))
<div class="xn-adv-section">
    <div class="xn-adv-section-header">
        <div class="xn-adv-badge">Legal Insights</div>
        <h2 class="xn-adv-section-title">{{ $blogData['heading'] ?? 'Legal Articles' }}</h2>
        <div class="xn-adv-divider"></div>
    </div>
    @php
        $blogPosts = collect();
        foreach($categoryPosts ?? [] as $cp) { $blogPosts = $blogPosts->merge($cp['posts'] ?? []); }
        if($blogPosts->isEmpty() && isset($featuredPost) && $featuredPost) $blogPosts = collect([$featuredPost]);
        $blogPosts = $blogPosts->unique('id')->take($blogData['count'] ?? 3);
    @endphp
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;">
        @foreach($blogPosts as $post)
        <div style="background:#1a1a1a;border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:1.5rem;transition:all 0.25s;" onmouseover="this.style.borderColor='color-mix(in srgb, var(--adv-accent) 35%, transparent)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.07)'">
            <div style="font-size:0.7rem;font-weight:700;color:var(--adv-accent);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">{{ isset($post->category) && $post->category ? $post->category->name : 'Legal' }}</div>
            <a href="{{ url($tenantBase . '/blog/' . $post->slug) }}" style="font-size:0.95rem;font-weight:700;color:#fff;text-decoration:none;display:block;margin-bottom:0.5rem;line-height:1.4;">{{ $post->title }}</a>
            <div style="font-size:0.8rem;color:#64748b;line-height:1.6;">{{ Str::limit(strip_tags($post->content ?? $post->excerpt ?? ''), 90) }}</div>
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ url($tenantBase . '/blog') }}" class="xn-adv-btn xn-adv-btn-outline"><i class="fas fa-newspaper"></i> All Articles</a>
    </div>
</div>
@endif

{{-- CTA --}}
@if($_show('contact'))
<div class="xn-adv-section">
    <div class="xn-adv-cta">
        <div class="xn-adv-badge" style="margin-bottom:1rem;">Free Consultation</div>
        <h2>{{ $contactHeading }}</h2>
        <p>{{ $contactText }}</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ $contactBtnUrl }}" class="xn-adv-btn xn-adv-btn-primary" style="font-size:1rem;padding:1rem 2.5rem;"><i class="fas fa-calendar"></i> {{ $contactBtn }}</a>
            @if(!empty($profile['phone']))<a href="tel:{{ $profile['phone'] }}" class="xn-adv-btn xn-adv-btn-outline"><i class="fas fa-phone"></i> {{ $profile['phone'] }}</a>@endif
        </div>
    </div>
</div>
@endif

</div>
@endsection
