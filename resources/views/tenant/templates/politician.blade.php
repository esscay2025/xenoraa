@extends('layouts.app')
@section('title', ($profile['name'] ?? $tenant->name) . ' — ' . ($profile['title'] ?? 'Political Leader'))
@section('description', $profile['about'] ?? 'Dedicated political leader committed to public service, development, and community welfare.')
@section('content')
@php
    $accent = $accentColor ?? '#f59e0b';
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
    $agendaData       = $homePage ? $homePage->getSectionData('agenda')       : [];
    $blogData         = $homePage ? $homePage->getSectionData('blog')         : [];
    $contactData      = $homePage ? $homePage->getSectionData('contact')      : [];
    $heroHeading    = $heroData['heading']    ?? $profile['name']    ?? $tenant->name;
    $heroSubheading = $heroData['subheading'] ?? $profile['tagline'] ?? ($profile['title'] ?? 'Member of Legislative Assembly');
    $heroCta        = $heroData['cta_text']   ?? 'Join the Movement';
    $heroCtaUrl     = $heroData['cta_url']    ?? ($tenantContactUrl ?? '/contact');
    $statsItems = $statsData['items'] ?? $profile['stats'] ?? [
        ['icon' => '🗳️', 'value' => $profile['years_in_service'] ?? '12+', 'label' => 'Years in Service'],
        ['icon' => '🏗️', 'value' => $profile['projects_completed'] ?? '150+','label' => 'Projects Completed'],
        ['icon' => '👥', 'value' => $profile['constituents_served'] ?? '5L+', 'label' => 'Constituents Served'],
        ['icon' => '🏆', 'value' => $profile['awards'] ?? '8+',             'label' => 'Awards Received'],
    ];
    $aboutText = $aboutData['text'] ?? $profile['about'] ?? 'I have dedicated my life to public service, working tirelessly for the development and welfare of our constituency. My vision is a prosperous, equitable, and progressive community where every citizen has access to quality education, healthcare, and economic opportunity.';
    $agendaItems = $agendaData['items'] ?? $profile['agenda_list'] ?? [
        ['icon' => '🎓', 'title' => 'Education for All',    'text' => 'Building 50 new schools, upgrading existing infrastructure, and providing scholarships to 10,000 students'],
        ['icon' => '🏥', 'title' => 'Healthcare Access',    'text' => 'Establishing community health centers in every ward and free medical camps quarterly'],
        ['icon' => '🛣️', 'title' => 'Infrastructure',       'text' => '500km of new roads, 3 flyovers, and complete drainage system overhaul by 2026'],
        ['icon' => '💼', 'title' => 'Employment',           'text' => 'Creating 25,000 jobs through industrial parks, IT hubs, and skill development centers'],
        ['icon' => '🌿', 'title' => 'Environment',          'text' => 'Planting 1 million trees, solar power for all government buildings, clean water for all'],
        ['icon' => '👩', 'title' => 'Women Empowerment',    'text' => 'Self-help groups, microfinance, safety initiatives, and women-led enterprises'],
    ];
    $testimonials = $testimonialsData['items'] ?? $profile['testimonials'] ?? [
        ['name' => 'Rajan Pillai',    'role' => 'Ward Councillor, Sector 12',  'text' => 'Under this leadership, our ward received the best infrastructure development in 20 years. Real change.'],
        ['name' => 'Meenakshi Devi',  'role' => 'Resident, North Constituency','text' => 'The education initiative helped my children get scholarships. This leader truly cares about us.'],
        ['name' => 'Subramaniam K.',  'role' => 'Local Business Owner',        'text' => 'The industrial park project created 500 jobs in our area. Visionary leadership in action.'],
    ];
    $contactHeading = $contactData['heading']     ?? "Connect With Your Representative";
    $contactText    = $contactData['text']        ?? "Your voice matters. Reach out for constituency matters, grievances, or to join our development initiatives.";
    $contactBtn     = $contactData['button_text'] ?? 'Contact Office';
    $contactBtnUrl  = $contactData['button_url']  ?? ($tenantContactUrl ?? '/contact');
    $tenantBase     = isset($tenant) && $tenant->custom_domain ? '' : ('/' . ($tenant->username ?? ''));
@endphp
<style>
:root { --pol-accent: {{ $accent }}; }
.xn-pol-wrap { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 4rem; }
/* HERO */
.xn-pol-hero { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; padding: 5rem 0 4rem; }
@media(max-width:768px){ .xn-pol-hero{grid-template-columns:1fr;text-align:center;gap:2rem;padding:3rem 0 2rem;} }
.xn-pol-hero-flag { display: inline-flex; align-items: center; gap: 0.5rem; background: color-mix(in srgb, var(--pol-accent) 12%, transparent); color: var(--pol-accent); font-size: 0.72rem; font-weight: 800; padding: 0.35rem 1rem; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.08em; border: 1px solid color-mix(in srgb, var(--pol-accent) 22%, transparent); margin-bottom: 1.25rem; }
.xn-pol-hero-name { font-size: clamp(2.2rem, 4vw, 3.5rem); font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 0.5rem; }
.xn-pol-hero-title { font-size: 1.05rem; color: var(--pol-accent); font-weight: 600; margin-bottom: 0.75rem; }
.xn-pol-hero-meta { display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
@media(max-width:768px){ .xn-pol-hero-meta{justify-content:center;} }
.xn-pol-hero-meta-item { display: flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; color: #94a3b8; }
.xn-pol-hero-meta-item i { color: var(--pol-accent); }
.xn-pol-hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
@media(max-width:768px){ .xn-pol-hero-actions{justify-content:center;} }
.xn-pol-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.75rem; border-radius: 4px; font-size: 0.9rem; font-weight: 700; text-decoration: none; transition: all 0.25s; cursor: pointer; border: none; }
.xn-pol-btn-primary { background: var(--pol-accent); color: #000; box-shadow: 0 8px 24px color-mix(in srgb, var(--pol-accent) 30%, transparent); }
.xn-pol-btn-primary:hover { transform: translateY(-2px); color:#000; }
.xn-pol-btn-outline { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,0.22); }
.xn-pol-btn-outline:hover { background: rgba(255,255,255,0.07); color:#fff; }
.xn-pol-hero-img { border-radius: 4px; overflow: hidden; aspect-ratio: 3/4; background: #1a1a1a; display: flex; align-items: center; justify-content: center; font-size: 6rem; border: 1px solid rgba(255,255,255,0.07); position: relative; }
.xn-pol-hero-img img { width: 100%; height: 100%; object-fit: cover; }
.xn-pol-hero-quote { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.9)); padding: 3rem 1.5rem 1.5rem; }
.xn-pol-hero-quote-text { font-size: 0.85rem; color: #e2e8f0; font-style: italic; line-height: 1.5; }
/* STATS */
.xn-pol-stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1px; background: rgba(255,255,255,0.07); border-radius: 4px; overflow: hidden; border: 1px solid rgba(255,255,255,0.07); margin-bottom: 4rem; }
.xn-pol-stat-cell { background: #1a1a1a; padding: 2rem 1rem; text-align: center; transition: background 0.2s; }
.xn-pol-stat-cell:hover { background: color-mix(in srgb, var(--pol-accent) 6%, #1a1a1a); }
.xn-pol-stat-icon { font-size: 1.5rem; margin-bottom: 0.5rem; }
.xn-pol-stat-num { font-size: 2rem; font-weight: 900; color: #fff; line-height: 1; }
.xn-pol-stat-label { font-size: 0.72rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.3rem; }
/* SECTION */
.xn-pol-section { padding: 4rem 0; }
.xn-pol-section-header { text-align: center; margin-bottom: 3rem; }
.xn-pol-badge { display: inline-block; background: color-mix(in srgb, var(--pol-accent) 12%, transparent); color: var(--pol-accent); font-size: 0.7rem; font-weight: 800; padding: 0.3rem 0.9rem; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem; border: 1px solid color-mix(in srgb, var(--pol-accent) 22%, transparent); }
.xn-pol-section-title { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: #fff; margin: 0 0 0.5rem; }
.xn-pol-section-sub { font-size: 1rem; color: #64748b; max-width: 520px; margin: 0 auto; }
.xn-pol-divider { width: 48px; height: 3px; background: var(--pol-accent); border-radius: 2px; margin: 1rem auto 0; }
/* AGENDA */
.xn-pol-agenda-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-pol-agenda-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 4px; padding: 1.75rem; transition: all 0.25s; border-left: 4px solid var(--pol-accent); }
.xn-pol-agenda-card:hover { transform: translateX(4px); box-shadow: 0 8px 24px rgba(0,0,0,0.3); }
.xn-pol-agenda-icon { font-size: 2rem; margin-bottom: 1rem; }
.xn-pol-agenda-title { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; }
.xn-pol-agenda-text { font-size: 0.85rem; color: #64748b; line-height: 1.6; }
/* ABOUT */
.xn-pol-about-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 3rem; align-items: center; }
@media(max-width:768px){ .xn-pol-about-grid{grid-template-columns:1fr;} }
.xn-pol-about-text { font-size: 1rem; color: #94a3b8; line-height: 1.9; margin-bottom: 1.5rem; }
.xn-pol-about-img { border-radius: 4px; overflow: hidden; aspect-ratio: 3/4; background: #1a1a1a; display: flex; align-items: center; justify-content: center; font-size: 5rem; border: 1px solid rgba(255,255,255,0.07); }
.xn-pol-about-img img { width: 100%; height: 100%; object-fit: cover; }
/* TESTIMONIALS */
.xn-pol-testimonials-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-pol-testimonial-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 4px; padding: 1.75rem; }
.xn-pol-testimonial-text { font-size: 0.9rem; color: #94a3b8; line-height: 1.7; margin-bottom: 1.25rem; font-style: italic; }
.xn-pol-testimonial-author { display: flex; align-items: center; gap: 0.75rem; }
.xn-pol-testimonial-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--pol-accent); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 700; color: #000; flex-shrink: 0; }
.xn-pol-testimonial-name { font-size: 0.875rem; font-weight: 700; color: #fff; }
.xn-pol-testimonial-role { font-size: 0.75rem; color: #64748b; }
/* CTA */
.xn-pol-cta { background: linear-gradient(135deg, color-mix(in srgb, var(--pol-accent) 12%, transparent) 0%, rgba(245,158,11,0.06) 100%); border: 1px solid color-mix(in srgb, var(--pol-accent) 22%, transparent); border-radius: 4px; padding: 4rem 2rem; text-align: center; }
.xn-pol-cta h2 { font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 900; color: #fff; margin-bottom: 1rem; }
.xn-pol-cta p { font-size: 1rem; color: #94a3b8; max-width: 500px; margin: 0 auto 2rem; }
@media(max-width:640px){
    .xn-pol-agenda-grid,.xn-pol-testimonials-grid{grid-template-columns:1fr;}
    .xn-pol-stats-row{grid-template-columns:repeat(2,1fr);}
}
</style>

<div class="xn-pol-wrap">

{{-- HERO --}}
@if($_show('hero'))
<div class="xn-pol-hero">
    <div>
        <div class="xn-pol-hero-flag"><i class="fas fa-flag"></i> {{ $profile['party'] ?? 'Public Servant' }}</div>
        <h1 class="xn-pol-hero-name">{{ $heroHeading }}</h1>
        <div class="xn-pol-hero-title">{{ $heroSubheading }}</div>
        <div class="xn-pol-hero-meta">
            @if(!empty($profile['constituency']))<div class="xn-pol-hero-meta-item"><i class="fas fa-map-marker-alt"></i> {{ $profile['constituency'] }}</div>@endif
            @if(!empty($profile['party']))<div class="xn-pol-hero-meta-item"><i class="fas fa-flag"></i> {{ $profile['party'] }}</div>@endif
            @if(!empty($profile['years_in_service']))<div class="xn-pol-hero-meta-item"><i class="fas fa-calendar"></i> {{ $profile['years_in_service'] }} Years in Service</div>@endif
        </div>
        <div class="xn-pol-hero-actions">
            <a href="{{ $heroCtaUrl }}" class="xn-pol-btn xn-pol-btn-primary"><i class="fas fa-users"></i> {{ $heroCta }}</a>
            <a href="{{ url($tenantBase . '/about') }}" class="xn-pol-btn xn-pol-btn-outline"><i class="fas fa-user"></i> My Profile</a>
        </div>
    </div>
    <div class="xn-pol-hero-img">
        @if($tenant->avatar)<img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}">
        @else🏛️@endif
        @if(!empty($heroData['subheading']) || !empty($profile['tagline']))
        <div class="xn-pol-hero-quote">
            <div class="xn-pol-hero-quote-text">"{{ $heroData['subheading'] ?? $profile['tagline'] ?? 'Committed to serving the people.' }}"</div>
        </div>
        @endif
    </div>
</div>
@endif

{{-- STATS --}}
@if($_show('stats') && count($statsItems))
<div class="xn-pol-stats-row">
    @foreach($statsItems as $stat)
    <div class="xn-pol-stat-cell">
        @if(!empty($stat['icon']))<div class="xn-pol-stat-icon">{{ $stat['icon'] }}</div>@endif
        <div class="xn-pol-stat-num">{{ $stat['value'] ?? $stat['num'] ?? '' }}</div>
        <div class="xn-pol-stat-label">{{ $stat['label'] ?? '' }}</div>
    </div>
    @endforeach
</div>
@endif

{{-- AGENDA --}}
@if($_show('agenda') && count($agendaItems))
<div class="xn-pol-section" style="padding-top:0;">
    <div class="xn-pol-section-header">
        <div class="xn-pol-badge">Vision 2030</div>
        <h2 class="xn-pol-section-title">{{ $agendaData['heading'] ?? 'My Development Agenda' }}</h2>
        @if(!empty($agendaData['subheading']))<p class="xn-pol-section-sub">{{ $agendaData['subheading'] }}</p>@endif
        <div class="xn-pol-divider"></div>
    </div>
    <div class="xn-pol-agenda-grid">
        @foreach($agendaItems as $item)
        <div class="xn-pol-agenda-card">
            <div class="xn-pol-agenda-icon">{{ $item['icon'] ?? '🏛️' }}</div>
            <div class="xn-pol-agenda-title">{{ $item['title'] ?? '' }}</div>
            <div class="xn-pol-agenda-text">{{ $item['text'] ?? $item['description'] ?? '' }}</div>
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ url($tenantBase . '/vision') }}" class="xn-pol-btn xn-pol-btn-outline"><i class="fas fa-scroll"></i> Full Vision Document</a>
    </div>
</div>
@endif

{{-- ABOUT --}}
@if($_show('about'))
<div class="xn-pol-section">
    <div class="xn-pol-about-grid">
        <div class="xn-pol-about-img">
            @if(!empty($aboutData['image']))<img src="{{ $aboutData['image'] }}" alt="{{ $tenant->name }}">
            @elseif($tenant->avatar)<img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}">
            @else🏛️@endif
        </div>
        <div>
            <div class="xn-pol-badge">About</div>
            <h2 class="xn-pol-section-title" style="text-align:left;margin-bottom:1rem;">{{ $aboutData['heading'] ?? 'My Journey' }}</h2>
            <p class="xn-pol-about-text">{{ $aboutData['text'] ?? $profile['about'] ?? $aboutText }}</p>
            <a href="{{ url($tenantBase . '/about') }}" class="xn-pol-btn xn-pol-btn-primary"><i class="fas fa-user"></i> Full Biography</a>
        </div>
    </div>
</div>
@endif

{{-- TESTIMONIALS --}}
@if($_show('testimonials') && count($testimonials))
<div class="xn-pol-section">
    <div class="xn-pol-section-header">
        <div class="xn-pol-badge">People's Voice</div>
        <h2 class="xn-pol-section-title">{{ $testimonialsData['heading'] ?? 'What Constituents Say' }}</h2>
        <div class="xn-pol-divider"></div>
    </div>
    <div class="xn-pol-testimonials-grid">
        @foreach($testimonials as $t)
        <div class="xn-pol-testimonial-card">
            <p class="xn-pol-testimonial-text">"{{ $t['text'] ?? '' }}"</p>
            <div class="xn-pol-testimonial-author">
                <div class="xn-pol-testimonial-avatar">{{ strtoupper(substr($t['name'] ?? 'A', 0, 1)) }}</div>
                <div>
                    <div class="xn-pol-testimonial-name">{{ $t['name'] ?? '' }}</div>
                    <div class="xn-pol-testimonial-role">{{ $t['role'] ?? '' }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- BLOG --}}
@if($_show('blog') && (isset($featuredPost) && $featuredPost || !empty($categoryPosts)))
<div class="xn-pol-section">
    <div class="xn-pol-section-header">
        <div class="xn-pol-badge">Updates</div>
        <h2 class="xn-pol-section-title">{{ $blogData['heading'] ?? 'Latest Updates' }}</h2>
        <div class="xn-pol-divider"></div>
    </div>
    @php
        $blogPosts = collect();
        foreach($categoryPosts ?? [] as $cp) { $blogPosts = $blogPosts->merge($cp['posts'] ?? []); }
        if($blogPosts->isEmpty() && isset($featuredPost) && $featuredPost) $blogPosts = collect([$featuredPost]);
        $blogPosts = $blogPosts->unique('id')->take($blogData['count'] ?? 3);
    @endphp
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;">
        @foreach($blogPosts as $post)
        <div style="background:#1a1a1a;border:1px solid rgba(255,255,255,0.07);border-radius:4px;overflow:hidden;transition:all 0.25s;">
            @if($post->featured_image)<img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" style="width:100%;height:160px;object-fit:cover;">@else<div style="height:160px;background:linear-gradient(135deg,color-mix(in srgb,var(--pol-accent) 20%,transparent),rgba(245,158,11,0.1));display:flex;align-items:center;justify-content:center;font-size:3rem;">🏛️</div>@endif
            <div style="padding:1.25rem;">
                <a href="{{ url($tenantBase . '/blog/' . $post->slug) }}" style="font-size:0.95rem;font-weight:700;color:#fff;text-decoration:none;display:block;margin-bottom:0.5rem;line-height:1.4;">{{ $post->title }}</a>
                <div style="font-size:0.8rem;color:#64748b;line-height:1.6;">{{ Str::limit(strip_tags($post->content ?? $post->excerpt ?? ''), 90) }}</div>
            </div>
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ url($tenantBase . '/blog') }}" class="xn-pol-btn xn-pol-btn-outline"><i class="fas fa-newspaper"></i> All Updates</a>
    </div>
</div>
@endif

{{-- CTA --}}
@if($_show('contact'))
<div class="xn-pol-section">
    <div class="xn-pol-cta">
        <div class="xn-pol-badge" style="margin-bottom:1rem;">Contact</div>
        <h2>{{ $contactHeading }}</h2>
        <p>{{ $contactText }}</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ $contactBtnUrl }}" class="xn-pol-btn xn-pol-btn-primary" style="font-size:1rem;padding:1rem 2.5rem;"><i class="fas fa-envelope"></i> {{ $contactBtn }}</a>
            @if(!empty($profile['phone']))<a href="tel:{{ $profile['phone'] }}" class="xn-pol-btn xn-pol-btn-outline"><i class="fas fa-phone"></i> {{ $profile['phone'] }}</a>@endif
        </div>
    </div>
</div>
@endif

</div>
@endsection
