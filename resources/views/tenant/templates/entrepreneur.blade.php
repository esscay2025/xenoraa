@extends('layouts.app')
@section('title', ($profile['name'] ?? $tenant->name) . ' — Entrepreneur & Founder')
@section('description', $profile['about'] ?? 'Serial entrepreneur, startup founder, and business builder creating impactful ventures.')
@section('content')
@php
    $accent = $accentColor ?? '#10b981';
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
    $heroSubheading = $heroData['subheading'] ?? $profile['tagline'] ?? ($profile['title'] ?? 'Serial Entrepreneur & Startup Founder');
    $heroCta        = $heroData['cta_text']   ?? 'View My Ventures';
    $heroCtaUrl     = $heroData['cta_url']    ?? ($profile['pitch_link'] ?? ($tenantVenturesUrl ?? '/ventures'));
    $statsItems = $statsData['items'] ?? $profile['stats'] ?? [
        ['icon' => '🚀', 'value' => $profile['ventures']      ?? '5+',   'label' => 'Ventures Built'],
        ['icon' => '💰', 'value' => $profile['funding_raised'] ?? '$10M+','label' => 'Funding Raised'],
        ['icon' => '👥', 'value' => $profile['team_size']      ?? '200+', 'label' => 'Team Members'],
        ['icon' => '🌍', 'value' => $profile['industries']     ?? '4',    'label' => 'Industries'],
    ];
    $aboutText = $aboutData['text'] ?? $profile['about'] ?? 'I am a serial entrepreneur passionate about building businesses that solve real problems. From ideation to scale, I have launched and grown multiple ventures across technology, consumer goods, and services.';
    $ventureItems = $venturesData['items'] ?? $profile['venture_list'] ?? [
        ['icon' => '🚀', 'title' => 'TechStart Solutions', 'text' => 'B2B SaaS platform for SME automation — 10,000+ users', 'url' => ''],
        ['icon' => '🛒', 'title' => 'QuickMart India',     'text' => 'Quick commerce platform — ₹50Cr GMV in Year 1',     'url' => ''],
        ['icon' => '🎓', 'title' => 'LearnFast Academy',   'text' => 'EdTech platform — 25,000+ learners across India',   'url' => ''],
    ];
    $services = $servicesData['items'] ?? $profile['services'] ?? [
        ['icon' => '🎯', 'title' => 'Startup Mentoring',    'text' => 'Guidance on product-market fit, fundraising, and scaling your startup'],
        ['icon' => '💡', 'title' => 'Idea Validation',      'text' => 'Market research, MVP strategy, and go-to-market planning'],
        ['icon' => '💰', 'title' => 'Fundraising Advisory', 'text' => 'Pitch deck review, investor introductions, and term sheet negotiation'],
        ['icon' => '🤝', 'title' => 'Angel Investing',      'text' => 'Strategic investment in early-stage startups with mentorship'],
        ['icon' => '📊', 'title' => 'Growth Strategy',      'text' => 'Customer acquisition, retention, and revenue optimization'],
        ['icon' => '🌐', 'title' => 'Speaking & Keynotes',  'text' => 'Entrepreneurship, innovation, and startup ecosystem talks'],
    ];
    $testimonials = $testimonialsData['items'] ?? $profile['testimonials'] ?? [
        ['name' => 'Kiran Rao',       'role' => 'Founder, DataSync AI',      'text' => 'The mentorship transformed our startup. We closed our Series A in 6 months with the right guidance.'],
        ['name' => 'Pooja Mehta',     'role' => 'CEO, GreenTech Ventures',   'text' => 'Exceptional strategic thinking. The market expansion advice helped us grow 5x in one year.'],
        ['name' => 'Aditya Sharma',   'role' => 'Co-founder, HealthPlus',    'text' => 'The angel investment and mentorship package was invaluable. We went from idea to product in 90 days.'],
    ];
    $contactHeading = $contactData['heading']     ?? "Let's Build Something Great";
    $contactText    = $contactData['text']        ?? "Whether you're a founder seeking mentorship, an investor exploring opportunities, or a brand looking to collaborate — let's connect.";
    $contactBtn     = $contactData['button_text'] ?? 'Connect With Me';
    $contactBtnUrl  = $contactData['button_url']  ?? ($tenantContactUrl ?? '/contact');
    $tenantBase     = isset($tenant) && $tenant->custom_domain ? '' : ('/' . ($tenant->username ?? ''));
    $tenantVenturesUrl = isset($tenantVenturesUrl) ? $tenantVenturesUrl : url($tenantBase . '/ventures');
@endphp
<style>
:root { --ent-accent: {{ $accent }}; }
.xn-ent-wrap { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 4rem; }
/* HERO */
.xn-ent-hero { display: grid; grid-template-columns: 1.2fr 1fr; gap: 4rem; align-items: center; padding: 5rem 0 4rem; }
@media(max-width:768px){ .xn-ent-hero{grid-template-columns:1fr;text-align:center;gap:2rem;padding:3rem 0 2rem;} }
.xn-ent-hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: color-mix(in srgb, var(--ent-accent) 12%, transparent); color: var(--ent-accent); font-size: 0.72rem; font-weight: 800; padding: 0.35rem 1rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.08em; border: 1px solid color-mix(in srgb, var(--ent-accent) 22%, transparent); margin-bottom: 1.25rem; }
.xn-ent-hero-name { font-size: clamp(2.2rem, 4vw, 3.5rem); font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 0.5rem; }
.xn-ent-hero-title { font-size: 1.1rem; color: var(--ent-accent); font-weight: 600; margin-bottom: 1rem; }
.xn-ent-hero-about { font-size: 1rem; color: #94a3b8; line-height: 1.8; margin-bottom: 2rem; }
.xn-ent-hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
@media(max-width:768px){ .xn-ent-hero-actions{justify-content:center;} }
.xn-ent-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.75rem; border-radius: 8px; font-size: 0.9rem; font-weight: 700; text-decoration: none; transition: all 0.25s; cursor: pointer; border: none; }
.xn-ent-btn-primary { background: var(--ent-accent); color: #fff; box-shadow: 0 8px 24px color-mix(in srgb, var(--ent-accent) 30%, transparent); }
.xn-ent-btn-primary:hover { transform: translateY(-2px); color:#fff; }
.xn-ent-btn-outline { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,0.22); }
.xn-ent-btn-outline:hover { background: rgba(255,255,255,0.07); color:#fff; }
.xn-ent-hero-visual { position: relative; }
.xn-ent-hero-img { border-radius: 20px; overflow: hidden; aspect-ratio: 1; background: #1a1a1a; display: flex; align-items: center; justify-content: center; font-size: 6rem; border: 1px solid rgba(255,255,255,0.07); }
.xn-ent-hero-img img { width: 100%; height: 100%; object-fit: cover; }
.xn-ent-hero-metric { position: absolute; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 0.75rem 1rem; }
.xn-ent-hero-metric-1 { top: 1.5rem; right: -1.5rem; }
.xn-ent-hero-metric-2 { bottom: 1.5rem; left: -1.5rem; }
@media(max-width:768px){ .xn-ent-hero-metric-1,.xn-ent-hero-metric-2{display:none;} }
.xn-ent-metric-label { font-size: 0.65rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
.xn-ent-metric-val { font-size: 1.1rem; font-weight: 800; color: #fff; }
/* STATS */
.xn-ent-stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1px; background: rgba(255,255,255,0.07); border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.07); margin-bottom: 4rem; }
.xn-ent-stat-cell { background: #1a1a1a; padding: 2rem 1rem; text-align: center; transition: background 0.2s; }
.xn-ent-stat-cell:hover { background: color-mix(in srgb, var(--ent-accent) 6%, #1a1a1a); }
.xn-ent-stat-icon { font-size: 1.5rem; margin-bottom: 0.5rem; }
.xn-ent-stat-num { font-size: 2rem; font-weight: 900; color: #fff; line-height: 1; }
.xn-ent-stat-label { font-size: 0.72rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.3rem; }
/* SECTION */
.xn-ent-section { padding: 4rem 0; }
.xn-ent-section-header { text-align: center; margin-bottom: 3rem; }
.xn-ent-badge { display: inline-block; background: color-mix(in srgb, var(--ent-accent) 12%, transparent); color: var(--ent-accent); font-size: 0.7rem; font-weight: 800; padding: 0.3rem 0.9rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem; border: 1px solid color-mix(in srgb, var(--ent-accent) 22%, transparent); }
.xn-ent-section-title { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: #fff; margin: 0 0 0.5rem; }
.xn-ent-section-sub { font-size: 1rem; color: #64748b; max-width: 520px; margin: 0 auto; }
.xn-ent-divider { width: 48px; height: 3px; background: var(--ent-accent); border-radius: 2px; margin: 1rem auto 0; }
/* VENTURES */
.xn-ent-ventures-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-ent-venture-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; padding: 1.75rem; transition: all 0.25s; position: relative; overflow: hidden; }
.xn-ent-venture-card::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--ent-accent); transform: scaleX(0); transition: transform 0.3s; }
.xn-ent-venture-card:hover::after { transform: scaleX(1); }
.xn-ent-venture-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.3); }
.xn-ent-venture-icon { font-size: 2rem; margin-bottom: 1rem; }
.xn-ent-venture-title { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; }
.xn-ent-venture-text { font-size: 0.85rem; color: #64748b; line-height: 1.6; }
/* SERVICES */
.xn-ent-services-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; }
.xn-ent-service-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 1.5rem; transition: all 0.25s; display: flex; gap: 1rem; align-items: flex-start; }
.xn-ent-service-card:hover { border-color: color-mix(in srgb, var(--ent-accent) 35%, transparent); }
.xn-ent-service-icon { font-size: 1.75rem; flex-shrink: 0; }
.xn-ent-service-title { font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 0.35rem; }
.xn-ent-service-text { font-size: 0.82rem; color: #64748b; line-height: 1.6; }
/* TESTIMONIALS */
.xn-ent-testimonials-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-ent-testimonial-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; padding: 1.75rem; }
.xn-ent-testimonial-text { font-size: 0.9rem; color: #94a3b8; line-height: 1.7; margin-bottom: 1.25rem; font-style: italic; }
.xn-ent-testimonial-author { display: flex; align-items: center; gap: 0.75rem; }
.xn-ent-testimonial-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--ent-accent); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 700; color: #fff; flex-shrink: 0; }
.xn-ent-testimonial-name { font-size: 0.875rem; font-weight: 700; color: #fff; }
.xn-ent-testimonial-role { font-size: 0.75rem; color: #64748b; }
/* CTA */
.xn-ent-cta { background: linear-gradient(135deg, color-mix(in srgb, var(--ent-accent) 12%, transparent) 0%, rgba(16,185,129,0.06) 100%); border: 1px solid color-mix(in srgb, var(--ent-accent) 22%, transparent); border-radius: 24px; padding: 4rem 2rem; text-align: center; }
.xn-ent-cta h2 { font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 900; color: #fff; margin-bottom: 1rem; }
.xn-ent-cta p { font-size: 1rem; color: #94a3b8; max-width: 500px; margin: 0 auto 2rem; }
@media(max-width:640px){
    .xn-ent-ventures-grid,.xn-ent-services-grid,.xn-ent-testimonials-grid{grid-template-columns:1fr;}
    .xn-ent-stats-row{grid-template-columns:repeat(2,1fr);}
    .xn-ent-service-card{flex-direction:column;}
}
</style>

<div class="xn-ent-wrap">

{{-- HERO --}}
@if($_show('hero'))
<div class="xn-ent-hero">
    <div>
        <div class="xn-ent-hero-badge"><i class="fas fa-rocket"></i> Entrepreneur &amp; Founder</div>
        <h1 class="xn-ent-hero-name">{{ $heroHeading }}</h1>
        <div class="xn-ent-hero-title">{{ $heroSubheading }}</div>
        @if(!empty($aboutData['text']) || !empty($profile['about']))
        <p class="xn-ent-hero-about">{{ Str::limit($aboutData['text'] ?? $profile['about'] ?? '', 180) }}</p>
        @endif
        <div class="xn-ent-hero-actions">
            <a href="{{ $heroCtaUrl }}" class="xn-ent-btn xn-ent-btn-primary"><i class="fas fa-rocket"></i> {{ $heroCta }}</a>
            <a href="{{ url($tenantBase . '/about') }}" class="xn-ent-btn xn-ent-btn-outline"><i class="fas fa-user"></i> My Story</a>
        </div>
    </div>
    <div class="xn-ent-hero-visual">
        <div class="xn-ent-hero-img">
            @if($tenant->avatar)<img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}">
            @else🚀@endif
        </div>
        @if(!empty($statsItems[0]))
        <div class="xn-ent-hero-metric xn-ent-hero-metric-1">
            <div class="xn-ent-metric-label">{{ $statsItems[0]['label'] ?? 'Ventures' }}</div>
            <div class="xn-ent-metric-val">{{ $statsItems[0]['value'] ?? '5+' }}</div>
        </div>
        @endif
        @if(!empty($statsItems[1]))
        <div class="xn-ent-hero-metric xn-ent-hero-metric-2">
            <div class="xn-ent-metric-label">{{ $statsItems[1]['label'] ?? 'Funding' }}</div>
            <div class="xn-ent-metric-val">{{ $statsItems[1]['value'] ?? '$10M+' }}</div>
        </div>
        @endif
    </div>
</div>
@endif

{{-- STATS --}}
@if($_show('stats'))
<div class="xn-ent-stats-row">
    @foreach($statsItems as $stat)
    <div class="xn-ent-stat-cell">
        @if(!empty($stat['icon']))<div class="xn-ent-stat-icon">{{ $stat['icon'] }}</div>@endif
        <div class="xn-ent-stat-num">{{ $stat['value'] ?? $stat['num'] ?? '' }}</div>
        <div class="xn-ent-stat-label">{{ $stat['label'] ?? '' }}</div>
    </div>
    @endforeach
</div>
@endif

{{-- VENTURES --}}
@if($_show('ventures'))
<div class="xn-ent-section" style="padding-top:0;">
    <div class="xn-ent-section-header">
        <div class="xn-ent-badge">Portfolio</div>
        <h2 class="xn-ent-section-title">{{ $venturesData['heading'] ?? 'My Ventures' }}</h2>
        <div class="xn-ent-divider"></div>
    </div>
    <div class="xn-ent-ventures-grid">
        @foreach($ventureItems as $v)
        <div class="xn-ent-venture-card">
            <div class="xn-ent-venture-icon">{{ $v['icon'] ?? '🚀' }}</div>
            <div class="xn-ent-venture-title">{{ $v['title'] ?? '' }}</div>
            <div class="xn-ent-venture-text">{{ $v['text'] ?? $v['description'] ?? '' }}</div>
            @if(!empty($v['url']))<a href="{{ $v['url'] }}" target="_blank" style="display:inline-flex;align-items:center;gap:0.4rem;margin-top:0.75rem;font-size:0.8rem;color:var(--ent-accent);text-decoration:none;font-weight:600;">Visit <i class="fas fa-external-link-alt" style="font-size:0.7rem;"></i></a>@endif
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ $tenantVenturesUrl }}" class="xn-ent-btn xn-ent-btn-outline"><i class="fas fa-briefcase"></i> All Ventures</a>
    </div>
</div>
@endif

{{-- SERVICES --}}
@if($_show('services'))
<div class="xn-ent-section">
    <div class="xn-ent-section-header">
        <div class="xn-ent-badge">Advisory</div>
        <h2 class="xn-ent-section-title">{{ $servicesData['heading'] ?? 'How I Can Help' }}</h2>
        @if(!empty($servicesData['subheading']))<p class="xn-ent-section-sub">{{ $servicesData['subheading'] }}</p>@endif
        <div class="xn-ent-divider"></div>
    </div>
    <div class="xn-ent-services-grid">
        @foreach($services as $svc)
        <div class="xn-ent-service-card">
            <div class="xn-ent-service-icon">{{ $svc['icon'] ?? '💡' }}</div>
            <div>
                <div class="xn-ent-service-title">{{ $svc['title'] ?? $svc['name'] ?? '' }}</div>
                <div class="xn-ent-service-text">{{ $svc['text'] ?? $svc['description'] ?? '' }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- TESTIMONIALS --}}
@if($_show('testimonials'))
<div class="xn-ent-section">
    <div class="xn-ent-section-header">
        <div class="xn-ent-badge">Success Stories</div>
        <h2 class="xn-ent-section-title">{{ $testimonialsData['heading'] ?? 'Founder Testimonials' }}</h2>
        <div class="xn-ent-divider"></div>
    </div>
    <div class="xn-ent-testimonials-grid">
        @foreach($testimonials as $t)
        <div class="xn-ent-testimonial-card">
            <p class="xn-ent-testimonial-text">"{{ $t['text'] ?? '' }}"</p>
            <div class="xn-ent-testimonial-author">
                <div class="xn-ent-testimonial-avatar">{{ strtoupper(substr($t['name'] ?? 'A', 0, 1)) }}</div>
                <div>
                    <div class="xn-ent-testimonial-name">{{ $t['name'] ?? '' }}</div>
                    <div class="xn-ent-testimonial-role">{{ $t['role'] ?? '' }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- BLOG --}}
@if($_show('blog'))
<div class="xn-ent-section">
    <div class="xn-ent-section-header">
        <div class="xn-ent-badge">Insights</div>
        <h2 class="xn-ent-section-title">{{ $blogData['heading'] ?? 'Startup Insights' }}</h2>
        <div class="xn-ent-divider"></div>
    </div>
    @php
        $blogPosts = collect();
        foreach($categoryPosts ?? [] as $cp) { $blogPosts = $blogPosts->merge($cp['posts'] ?? []); }
        if($blogPosts->isEmpty() && isset($featuredPost) && $featuredPost) $blogPosts = collect([$featuredPost]);
        $blogPosts = $blogPosts->unique('id')->take($blogData['count'] ?? 3);
    @endphp
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;">
        @foreach($blogPosts as $post)
        <div style="background:#1a1a1a;border:1px solid rgba(255,255,255,0.07);border-radius:16px;overflow:hidden;transition:all 0.25s;">
            @if($post->featured_image)<img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" style="width:100%;height:160px;object-fit:cover;">@else<div style="height:160px;background:linear-gradient(135deg,color-mix(in srgb,var(--ent-accent) 20%,transparent),rgba(16,185,129,0.1));display:flex;align-items:center;justify-content:center;font-size:3rem;">📈</div>@endif
            <div style="padding:1.25rem;">
                <a href="{{ url($tenantBase . '/blog/' . $post->slug) }}" style="font-size:0.95rem;font-weight:700;color:#fff;text-decoration:none;display:block;margin-bottom:0.5rem;line-height:1.4;">{{ $post->title }}</a>
                <div style="font-size:0.8rem;color:#64748b;line-height:1.6;">{{ Str::limit(strip_tags($post->content ?? $post->excerpt ?? ''), 90) }}</div>
            </div>
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ url($tenantBase . '/blog') }}" class="xn-ent-btn xn-ent-btn-outline"><i class="fas fa-newspaper"></i> All Insights</a>
    </div>
</div>
@endif

{{-- CTA --}}
@if($_show('contact'))
<div class="xn-ent-section">
    <div class="xn-ent-cta">
        <div class="xn-ent-badge" style="margin-bottom:1rem;">Connect</div>
        <h2>{{ $contactHeading }}</h2>
        <p>{{ $contactText }}</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ $contactBtnUrl }}" class="xn-ent-btn xn-ent-btn-primary" style="font-size:1rem;padding:1rem 2.5rem;"><i class="fas fa-handshake"></i> {{ $contactBtn }}</a>
            @if(!empty($profile['pitch_link']))<a href="{{ $profile['pitch_link'] }}" class="xn-ent-btn xn-ent-btn-outline" target="_blank"><i class="fas fa-file-powerpoint"></i> View Pitch Deck</a>@endif
        </div>
    </div>
</div>
@endif

</div>
@endsection
