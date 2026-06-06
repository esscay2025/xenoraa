@extends('layouts.app')
@section('title', ($profile['name'] ?? $tenant->name) . ' — ' . ($profile['niche'] ?? 'Lifestyle Creator'))
@section('description', $profile['about'] ?? 'Lifestyle creator, content creator and digital influencer.')
@section('content')
@php
    $accent = $accentColor ?? '#f43f5e';
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
    $followersData    = $homePage ? $homePage->getSectionData('followers')    : [];
    $blogData         = $homePage ? $homePage->getSectionData('blog')         : [];
    $contactData      = $homePage ? $homePage->getSectionData('contact')      : [];
    $shopData         = $homePage ? $homePage->getSectionData('shop')         : [];
    $heroHeading    = $heroData['heading']    ?? $profile['name']    ?? $tenant->name;
    $heroSubheading = $heroData['subheading'] ?? $profile['tagline'] ?? ($profile['niche'] ?? 'Lifestyle Creator · Fashion · Travel · Wellness');
    $heroCta        = $heroData['cta_text']   ?? 'Collaborate With Me';
    $heroCtaUrl     = $heroData['cta_url']    ?? ($tenantContactUrl ?? '/contact');
    $statsItems = $statsData['items'] ?? $profile['stats'] ?? [];
    $aboutText = $aboutData['text'] ?? $profile['about'] ?? 'I am a passionate lifestyle creator sharing my journey through fashion, travel, beauty, and wellness.';
    $services = $servicesData['items'] ?? $profile['services'] ?? [
        ['icon' => '📸', 'title' => 'Sponsored Posts',      'text' => 'Authentic brand integrations across Instagram, YouTube, and TikTok'],
        ['icon' => '🎬', 'title' => 'Video Content',        'text' => 'Short-form reels, YouTube vlogs, and branded video campaigns'],
        ['icon' => '🌟', 'title' => 'Brand Ambassador',     'text' => 'Long-term partnership and brand representation'],
        ['icon' => '🎤', 'title' => 'Events & Appearances', 'text' => 'Product launches, brand events, and speaking engagements'],
        ['icon' => '📦', 'title' => 'Product Reviews',      'text' => 'Honest, detailed reviews reaching a highly engaged audience'],
        ['icon' => '✍️', 'title' => 'Blog & Newsletter',    'text' => 'Written content and email campaigns for deeper engagement'],
    ];
    $testimonials = $testimonialsData['items'] ?? $profile['testimonials'] ?? [
        ['name' => 'Ananya Kapoor', 'role' => 'Marketing Head, Luxe Beauty',  'text' => 'Working with this creator was an absolute pleasure. The content quality and audience engagement were exceptional.'],
        ['name' => 'Rahul Mehta',   'role' => 'Brand Manager, TravelEasy',    'text' => 'The campaign exceeded all our KPIs. Authentic storytelling that resonated deeply with the target audience.'],
        ['name' => 'Sneha Iyer',    'role' => 'Founder, Glow Organics',       'text' => 'Our product sold out within 48 hours of the collaboration post. Incredible reach and trust factor.'],
    ];
    $followersInstagram = $followersData['instagram'] ?? $profile['instagram_followers'] ?? '';
    $followersYoutube   = $followersData['youtube']   ?? $profile['youtube_subscribers'] ?? '';
    $followersTwitter   = $followersData['twitter']   ?? $profile['twitter_followers']   ?? '';
    $followersTiktok    = $followersData['tiktok']    ?? $profile['tiktok_followers']    ?? '';
    $contactHeading = $contactData['heading']     ?? "Let's Create Together";
    $contactText    = $contactData['text']        ?? 'Open to brand collaborations, sponsored content, product reviews, and long-term partnerships.';
    $contactBtn     = $contactData['button_text'] ?? 'Send Collaboration Request';
    $contactBtnUrl  = $contactData['button_url']  ?? ($tenantContactUrl ?? '/contact');
    $tenantBase     = isset($tenant) && $tenant->custom_domain ? '' : ('/' . ($tenant->username ?? ''));
@endphp
<style>
:root { --inf-accent: {{ $accent }}; }
.xn-inf { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 4rem; }
/* HERO */
.xn-inf-hero { position: relative; min-height: 88vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: 6rem 2rem 4rem; overflow: hidden; }
.xn-inf-hero-bg { position: absolute; inset: 0; background: radial-gradient(ellipse 80% 60% at 50% 0%, color-mix(in srgb, var(--inf-accent) 20%, transparent) 0%, transparent 70%); pointer-events: none; }
.xn-inf-hero-inner { position: relative; z-index: 1; }
.xn-inf-avatar-wrap { position: relative; display: inline-block; margin-bottom: 1.5rem; }
.xn-inf-avatar { width: 140px; height: 140px; border-radius: 50%; overflow: hidden; border: 3px solid var(--inf-accent); box-shadow: 0 0 0 8px color-mix(in srgb, var(--inf-accent) 15%, transparent), 0 20px 60px color-mix(in srgb, var(--inf-accent) 30%, transparent); margin: 0 auto; display: flex; align-items: center; justify-content: center; background: #1a1a1a; font-size: 3rem; }
.xn-inf-avatar img { width: 100%; height: 100%; object-fit: cover; }
.xn-inf-creator-badge { position: absolute; bottom: 8px; right: 8px; background: var(--inf-accent); color: #fff; font-size: 0.62rem; font-weight: 800; padding: 0.2rem 0.55rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.06em; }
.xn-inf-name { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 0.4rem; }
.xn-inf-handle { font-size: 1rem; color: var(--inf-accent); font-weight: 600; margin-bottom: 0.5rem; }
.xn-inf-niche { font-size: 1.05rem; color: #94a3b8; margin-bottom: 2rem; }
.xn-inf-hero-stats { display: flex; gap: 2.5rem; justify-content: center; flex-wrap: wrap; margin-bottom: 2.5rem; }
.xn-inf-hero-stat-num { font-size: 1.6rem; font-weight: 800; color: #fff; line-height: 1; }
.xn-inf-hero-stat-label { font-size: 0.72rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.2rem; }
.xn-inf-hero-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.xn-inf-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 2rem; border-radius: 50px; font-size: 0.9rem; font-weight: 700; text-decoration: none; transition: all 0.25s; cursor: pointer; border: none; }
.xn-inf-btn-primary { background: var(--inf-accent); color: #fff; box-shadow: 0 8px 24px color-mix(in srgb, var(--inf-accent) 35%, transparent); }
.xn-inf-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 32px color-mix(in srgb, var(--inf-accent) 50%, transparent); color:#fff; }
.xn-inf-btn-outline { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,0.22); }
.xn-inf-btn-outline:hover { background: rgba(255,255,255,0.07); color:#fff; }
/* SECTION */
.xn-inf-section { padding: 4rem 0; }
.xn-inf-section-header { text-align: center; margin-bottom: 3rem; }
.xn-inf-badge { display: inline-block; background: color-mix(in srgb, var(--inf-accent) 12%, transparent); color: var(--inf-accent); font-size: 0.7rem; font-weight: 800; padding: 0.3rem 0.9rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem; border: 1px solid color-mix(in srgb, var(--inf-accent) 22%, transparent); }
.xn-inf-section-title { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: #fff; margin: 0 0 0.5rem; }
.xn-inf-section-sub { font-size: 1rem; color: #64748b; max-width: 520px; margin: 0 auto; }
.xn-inf-divider { width: 48px; height: 3px; background: var(--inf-accent); border-radius: 2px; margin: 1rem auto 0; }
/* STATS STRIP */
.xn-inf-stats-strip { display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.07); }
.xn-inf-stat-cell { background: #1a1a1a; padding: 1.75rem 1rem; text-align: center; border-right: 1px solid rgba(255,255,255,0.07); transition: background 0.2s; }
.xn-inf-stat-cell:last-child { border-right: none; }
.xn-inf-stat-cell:hover { background: color-mix(in srgb, var(--inf-accent) 6%, #1a1a1a); }
.xn-inf-stat-icon { font-size: 1.5rem; margin-bottom: 0.5rem; }
.xn-inf-stat-num { font-size: 1.8rem; font-weight: 900; color: #fff; line-height: 1; }
.xn-inf-stat-label { font-size: 0.72rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.3rem; }
/* ABOUT */
.xn-inf-about-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 3rem; align-items: center; }
@media(max-width:768px){ .xn-inf-about-grid{grid-template-columns:1fr;} }
.xn-inf-about-img { border-radius: 20px; overflow: hidden; aspect-ratio: 4/5; background: #1a1a1a; display: flex; align-items: center; justify-content: center; font-size: 5rem; border: 1px solid rgba(255,255,255,0.07); }
.xn-inf-about-img img { width: 100%; height: 100%; object-fit: cover; }
.xn-inf-about-text { font-size: 1rem; color: #94a3b8; line-height: 1.9; margin-bottom: 1.5rem; }
.xn-inf-niches { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; }
.xn-inf-niche-tag { background: color-mix(in srgb, var(--inf-accent) 10%, transparent); color: var(--inf-accent); border: 1px solid color-mix(in srgb, var(--inf-accent) 22%, transparent); padding: 0.3rem 0.85rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
/* SERVICES */
.xn-inf-services-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; }
.xn-inf-service-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; padding: 1.75rem; transition: all 0.25s; }
.xn-inf-service-card:hover { border-color: color-mix(in srgb, var(--inf-accent) 35%, transparent); transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.3); }
.xn-inf-service-icon { font-size: 2rem; margin-bottom: 1rem; }
.xn-inf-service-title { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; }
.xn-inf-service-text { font-size: 0.85rem; color: #64748b; line-height: 1.6; }
/* TESTIMONIALS */
.xn-inf-testimonials-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-inf-testimonial-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; padding: 1.75rem; }
.xn-inf-testimonial-stars { color: var(--inf-accent); font-size: 0.85rem; margin-bottom: 1rem; }
.xn-inf-testimonial-text { font-size: 0.9rem; color: #94a3b8; line-height: 1.7; margin-bottom: 1.25rem; font-style: italic; }
.xn-inf-testimonial-author { display: flex; align-items: center; gap: 0.75rem; }
.xn-inf-testimonial-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--inf-accent); display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 700; color: #fff; flex-shrink: 0; overflow: hidden; }
.xn-inf-testimonial-avatar img { width: 100%; height: 100%; object-fit: cover; }
.xn-inf-testimonial-name { font-size: 0.875rem; font-weight: 700; color: #fff; }
.xn-inf-testimonial-role { font-size: 0.75rem; color: #64748b; }
/* SOCIAL CHANNELS */
.xn-inf-social-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; }
.xn-inf-social-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; padding: 1.5rem; text-align: center; text-decoration: none; transition: all 0.25s; display: block; }
.xn-inf-social-card:hover { transform: translateY(-3px); border-color: color-mix(in srgb, var(--inf-accent) 40%, transparent); box-shadow: 0 8px 24px rgba(0,0,0,0.3); }
.xn-inf-social-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
.xn-inf-social-platform { font-size: 0.875rem; font-weight: 700; color: #fff; margin-bottom: 0.25rem; }
.xn-inf-social-count { font-size: 0.8rem; color: var(--inf-accent); font-weight: 600; }
/* BLOG */
.xn-inf-blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-inf-blog-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; overflow: hidden; transition: all 0.25s; }
.xn-inf-blog-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.3); }
.xn-inf-blog-img { height: 180px; background: linear-gradient(135deg, color-mix(in srgb, var(--inf-accent) 20%, transparent), rgba(168,85,247,0.2)); display: flex; align-items: center; justify-content: center; font-size: 3rem; overflow: hidden; }
.xn-inf-blog-img img { width: 100%; height: 100%; object-fit: cover; }
.xn-inf-blog-body { padding: 1.25rem; }
.xn-inf-blog-cat { font-size: 0.7rem; font-weight: 700; color: var(--inf-accent); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.5rem; }
.xn-inf-blog-title { font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; line-height: 1.4; text-decoration: none; display: block; }
.xn-inf-blog-title:hover { color: var(--inf-accent); }
.xn-inf-blog-excerpt { font-size: 0.8rem; color: #64748b; line-height: 1.6; }
/* CTA */
.xn-inf-cta { background: linear-gradient(135deg, color-mix(in srgb, var(--inf-accent) 12%, transparent) 0%, rgba(168,85,247,0.08) 100%); border: 1px solid color-mix(in srgb, var(--inf-accent) 22%, transparent); border-radius: 24px; padding: 4rem 2rem; text-align: center; }
.xn-inf-cta h2 { font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 900; color: #fff; margin-bottom: 1rem; }
.xn-inf-cta p { font-size: 1rem; color: #94a3b8; max-width: 500px; margin: 0 auto 2rem; }
.xn-inf-collab-types { display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; margin-bottom: 2rem; }
.xn-inf-collab-type { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #e2e8f0; padding: 0.35rem 0.9rem; border-radius: 100px; font-size: 0.8rem; }
@media(max-width:640px){
    .xn-inf-hero{min-height:70vh;padding:4rem 1rem 3rem;}
    .xn-inf-hero-stats{gap:1rem;}
    .xn-inf-hero-stat-num{font-size:1.2rem;}
    .xn-inf-services-grid,.xn-inf-testimonials-grid,.xn-inf-blog-grid{grid-template-columns:1fr;}
    .xn-inf-stats-strip{grid-template-columns:repeat(2,1fr);}
}
</style>

<div class="xn-inf">

{{-- HERO --}}
@if($_show('hero'))
<div class="xn-inf-hero">
    <div class="xn-inf-hero-bg"></div>
    <div class="xn-inf-hero-inner">
        <div class="xn-inf-avatar-wrap">
            <div class="xn-inf-avatar">
                @if($tenant->avatar)<img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}">@else✨@endif
            </div>
            <div class="xn-inf-creator-badge">Creator</div>
        </div>
        <div class="xn-inf-name">{{ $heroHeading }}</div>
        @if(!empty($profile['handle']))<div class="xn-inf-handle">@{{ $profile['handle'] }}</div>@endif
        <div class="xn-inf-niche">{{ $heroSubheading }}</div>
        @if(!empty($followersInstagram) || !empty($followersYoutube) || !empty($followersTwitter) || !empty($followersTiktok))
        <div class="xn-inf-hero-stats">
            @if(!empty($followersInstagram))<div><div class="xn-inf-hero-stat-num">{{ $followersInstagram }}</div><div class="xn-inf-hero-stat-label">Instagram</div></div>@endif
            @if(!empty($followersYoutube))<div><div class="xn-inf-hero-stat-num">{{ $followersYoutube }}</div><div class="xn-inf-hero-stat-label">YouTube</div></div>@endif
            @if(!empty($followersTwitter))<div><div class="xn-inf-hero-stat-num">{{ $followersTwitter }}</div><div class="xn-inf-hero-stat-label">Twitter/X</div></div>@endif
            @if(!empty($followersTiktok))<div><div class="xn-inf-hero-stat-num">{{ $followersTiktok }}</div><div class="xn-inf-hero-stat-label">TikTok</div></div>@endif
        </div>
        @endif
        <div class="xn-inf-hero-actions">
            <a href="{{ $heroCtaUrl }}" class="xn-inf-btn xn-inf-btn-primary"><i class="fas fa-handshake"></i> {{ $heroCta }}</a>
            @if(!empty($profile['media_kit']))<a href="{{ $profile['media_kit'] }}" class="xn-inf-btn xn-inf-btn-outline" target="_blank"><i class="fas fa-file-pdf"></i> Media Kit</a>@endif
            @if(!empty($profile['instagram']))<a href="{{ $profile['instagram'] }}" class="xn-inf-btn xn-inf-btn-outline" target="_blank"><i class="fab fa-instagram"></i> Follow</a>@endif
        </div>
    </div>
</div>
@endif

{{-- STATS STRIP --}}
@if($_show('stats') && count($statsItems))
<div class="xn-inf-section" style="padding-top:0;">
    <div class="xn-inf-stats-strip">
        @foreach($statsItems as $stat)
        <div class="xn-inf-stat-cell">
            @if(!empty($stat['icon']))<div class="xn-inf-stat-icon">{{ $stat['icon'] }}</div>@endif
            <div class="xn-inf-stat-num">{{ $stat['value'] ?? $stat['num'] ?? '' }}</div>
            <div class="xn-inf-stat-label">{{ $stat['label'] ?? '' }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ABOUT --}}
@if($_show('about'))
<div class="xn-inf-section">
    <div class="xn-inf-about-grid">
        <div class="xn-inf-about-img">
            @if(!empty($aboutData['image']))<img src="{{ $aboutData['image'] }}" alt="{{ $tenant->name }}">
            @elseif($tenant->avatar)<img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}">
            @else🌟@endif
        </div>
        <div>
            <div class="xn-inf-badge">About Me</div>
            <h2 class="xn-inf-section-title" style="text-align:left;margin-bottom:1rem;">{{ $aboutData['heading'] ?? 'My Story' }}</h2>
            <p class="xn-inf-about-text">{{ $aboutText }}</p>
            @if(!empty($profile['niche']))
            <div class="xn-inf-niches">
                @foreach(explode('·', $profile['niche']) as $n)
                <span class="xn-inf-niche-tag">{{ trim($n) }}</span>
                @endforeach
            </div>
            @endif
            <a href="{{ url($tenantBase . '/about') }}" class="xn-inf-btn xn-inf-btn-primary"><i class="fas fa-user"></i> Read My Story</a>
        </div>
    </div>
</div>
@endif

{{-- SOCIAL CHANNELS --}}
@if($_show('followers'))
<div class="xn-inf-section">
    <div class="xn-inf-section-header">
        <div class="xn-inf-badge">Social Media</div>
        <h2 class="xn-inf-section-title">{{ $followersData['heading'] ?? 'My Channels' }}</h2>
        <div class="xn-inf-divider"></div>
    </div>
    <div class="xn-inf-social-grid">
        @if(!empty($profile['instagram']))<a href="{{ $profile['instagram'] }}" class="xn-inf-social-card" target="_blank"><div class="xn-inf-social-icon">📸</div><div class="xn-inf-social-platform">Instagram</div><div class="xn-inf-social-count">{{ $followersInstagram ?: 'Follow' }}</div></a>@endif
        @if(!empty($profile['youtube']))<a href="{{ $profile['youtube'] }}" class="xn-inf-social-card" target="_blank"><div class="xn-inf-social-icon">▶️</div><div class="xn-inf-social-platform">YouTube</div><div class="xn-inf-social-count">{{ $followersYoutube ?: 'Subscribe' }}</div></a>@endif
        @if(!empty($profile['twitter']))<a href="{{ $profile['twitter'] }}" class="xn-inf-social-card" target="_blank"><div class="xn-inf-social-icon">🐦</div><div class="xn-inf-social-platform">Twitter/X</div><div class="xn-inf-social-count">{{ $followersTwitter ?: 'Follow' }}</div></a>@endif
        @if(!empty($profile['tiktok']))<a href="{{ $profile['tiktok'] }}" class="xn-inf-social-card" target="_blank"><div class="xn-inf-social-icon">🎵</div><div class="xn-inf-social-platform">TikTok</div><div class="xn-inf-social-count">{{ $followersTiktok ?: 'Follow' }}</div></a>@endif
        @if(!empty($profile['linkedin']))<a href="{{ $profile['linkedin'] }}" class="xn-inf-social-card" target="_blank"><div class="xn-inf-social-icon">💼</div><div class="xn-inf-social-platform">LinkedIn</div><div class="xn-inf-social-count">Connect</div></a>@endif
    </div>
</div>
@endif

{{-- SERVICES / COLLABORATIONS --}}
@if($_show('services'))
<div class="xn-inf-section">
    <div class="xn-inf-section-header">
        <div class="xn-inf-badge">Work With Me</div>
        <h2 class="xn-inf-section-title">{{ $servicesData['heading'] ?? 'Collaboration Services' }}</h2>
        @if(!empty($servicesData['subheading']))<p class="xn-inf-section-sub">{{ $servicesData['subheading'] }}</p>@endif
        <div class="xn-inf-divider"></div>
    </div>
    <div class="xn-inf-services-grid">
        @foreach($services as $svc)
        <div class="xn-inf-service-card">
            <div class="xn-inf-service-icon">{{ $svc['icon'] ?? '✨' }}</div>
            <div class="xn-inf-service-title">{{ $svc['title'] ?? $svc['name'] ?? '' }}</div>
            <div class="xn-inf-service-text">{{ $svc['text'] ?? $svc['description'] ?? '' }}</div>
            @if(!empty($svc['price']))<div style="margin-top:0.75rem;font-size:0.8rem;font-weight:700;color:var(--inf-accent);">{{ $svc['price'] }}</div>@endif
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- TESTIMONIALS --}}
@if($_show('testimonials') && count($testimonials))
<div class="xn-inf-section">
    <div class="xn-inf-section-header">
        <div class="xn-inf-badge">Brand Love</div>
        <h2 class="xn-inf-section-title">{{ $testimonialsData['heading'] ?? 'What Brands Say' }}</h2>
        <div class="xn-inf-divider"></div>
    </div>
    <div class="xn-inf-testimonials-grid">
        @foreach($testimonials as $t)
        <div class="xn-inf-testimonial-card">
            <div class="xn-inf-testimonial-stars">★★★★★</div>
            <p class="xn-inf-testimonial-text">"{{ $t['text'] ?? '' }}"</p>
            <div class="xn-inf-testimonial-author">
                <div class="xn-inf-testimonial-avatar">
                    @if(!empty($t['avatar']))<img src="{{ $t['avatar'] }}" alt="{{ $t['name'] ?? '' }}">@else{{ strtoupper(substr($t['name'] ?? 'A', 0, 1)) }}@endif
                </div>
                <div>
                    <div class="xn-inf-testimonial-name">{{ $t['name'] ?? '' }}</div>
                    <div class="xn-inf-testimonial-role">{{ $t['role'] ?? '' }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- BLOG --}}
@if($_show('blog') && (isset($featuredPost) && $featuredPost || !empty($categoryPosts)))
<div class="xn-inf-section">
    <div class="xn-inf-section-header">
        <div class="xn-inf-badge">Latest</div>
        <h2 class="xn-inf-section-title">{{ $blogData['heading'] ?? 'From My Blog' }}</h2>
        <div class="xn-inf-divider"></div>
    </div>
    <div class="xn-inf-blog-grid">
        @php
            $blogPosts = collect();
            foreach($categoryPosts ?? [] as $cp) { $blogPosts = $blogPosts->merge($cp['posts'] ?? []); }
            if($blogPosts->isEmpty() && isset($featuredPost) && $featuredPost) $blogPosts = collect([$featuredPost]);
            $blogPosts = $blogPosts->unique('id')->take($blogData['count'] ?? 3);
        @endphp
        @foreach($blogPosts as $post)
        <div class="xn-inf-blog-card">
            <div class="xn-inf-blog-img">
                @if($post->featured_image)<img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}">@else✍️@endif
            </div>
            <div class="xn-inf-blog-body">
                @if(isset($post->category) && $post->category)<div class="xn-inf-blog-cat">{{ $post->category->name ?? '' }}</div>@endif
                <a href="{{ url($tenantBase . '/blog/' . $post->slug) }}" class="xn-inf-blog-title">{{ $post->title }}</a>
                <div class="xn-inf-blog-excerpt">{{ Str::limit(strip_tags($post->content ?? $post->excerpt ?? ''), 90) }}</div>
            </div>
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ url($tenantBase . '/blog') }}" class="xn-inf-btn xn-inf-btn-outline"><i class="fas fa-newspaper"></i> View All Posts</a>
    </div>
</div>
@endif

{{-- SHOP --}}
@if($_show('shop'))
<div class="xn-inf-section">
    <div class="xn-inf-section-header">
        <div class="xn-inf-badge">Shop</div>
        <h2 class="xn-inf-section-title">{{ $shopData['heading'] ?? 'My Picks' }}</h2>
        <div class="xn-inf-divider"></div>
    </div>
    <div style="text-align:center;padding:3rem;background:#1a1a1a;border-radius:16px;border:1px solid rgba(255,255,255,0.07);">
        <div style="font-size:3rem;margin-bottom:1rem;">🛍️</div>
        <p style="color:#64748b;margin-bottom:1.5rem;">Discover my curated collection of favourite products across fashion, beauty, and lifestyle.</p>
        <a href="{{ url($tenantBase . '/shop') }}" class="xn-inf-btn xn-inf-btn-primary"><i class="fas fa-shopping-bag"></i> Shop My Picks</a>
    </div>
</div>
@endif

{{-- CONTACT / CTA --}}
@if($_show('contact'))
<div class="xn-inf-section">
    <div class="xn-inf-cta">
        <div class="xn-inf-badge" style="margin-bottom:1rem;">Collaborate</div>
        <h2>{{ $contactHeading }}</h2>
        <p>{{ $contactText }}</p>
        <div class="xn-inf-collab-types">
            @foreach($profile['collab_types'] ?? ['Sponsored Posts','Product Reviews','Brand Ambassador','Events','Reels/Shorts','Podcast Guest'] as $ct)
            <span class="xn-inf-collab-type">{{ $ct }}</span>
            @endforeach
        </div>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ $contactBtnUrl }}" class="xn-inf-btn xn-inf-btn-primary" style="font-size:1rem;padding:1rem 2.5rem;"><i class="fas fa-handshake"></i> {{ $contactBtn }}</a>
            @if(!empty($profile['collab_email']))<a href="mailto:{{ $profile['collab_email'] }}" class="xn-inf-btn xn-inf-btn-outline"><i class="fas fa-envelope"></i> {{ $profile['collab_email'] }}</a>@endif
        </div>
    </div>
</div>
@endif

</div>
@endsection
