@extends('layouts.app')
@section('title', ($profile['name'] ?? $tenant->name) . ' — ' . ($profile['title'] ?? 'Business Consultant'))
@section('description', $profile['about'] ?? 'Expert business consultant offering strategy, transformation, and growth advisory services.')
@section('content')
@php
    $accent = $accentColor ?? '#6366f1';
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
    $heroSubheading = $heroData['subheading'] ?? $profile['tagline'] ?? ($profile['title'] ?? 'Business Strategy & Growth Consultant');
    $heroCta        = $heroData['cta_text']   ?? 'Book Free Strategy Call';
    $heroCtaUrl     = $heroData['cta_url']    ?? ($profile['booking_link'] ?? ($tenantContactUrl ?? '/contact'));
    $statsItems = $statsData['items'] ?? $profile['stats'] ?? [
        ['icon' => '🏆', 'value' => $profile['years']    ?? '15+', 'label' => 'Years Experience'],
        ['icon' => '😊', 'value' => $profile['clients']  ?? '200+','label' => 'Happy Clients'],
        ['icon' => '✅', 'value' => $profile['projects'] ?? '500+','label' => 'Projects Done'],
        ['icon' => '💰', 'value' => $profile['revenue']  ?? '$50M+','label' => 'Revenue Generated'],
    ];
    $aboutText = $aboutData['text'] ?? $profile['about'] ?? 'I help businesses unlock their full potential through strategic consulting, process optimization, and data-driven decision making. With over a decade of experience across industries, I deliver measurable results.';
    $services = $servicesData['items'] ?? $profile['services'] ?? [
        ['icon' => '📊', 'title' => 'Business Strategy',       'text' => 'Market analysis, competitive positioning, and growth roadmaps tailored to your industry'],
        ['icon' => '🔄', 'title' => 'Process Optimization',    'text' => 'Streamline operations, reduce costs, and improve efficiency across your organization'],
        ['icon' => '💡', 'title' => 'Digital Transformation',  'text' => 'Technology adoption, automation, and digital strategy for modern businesses'],
        ['icon' => '🎯', 'title' => 'Executive Coaching',      'text' => 'Leadership development, performance coaching, and C-suite advisory'],
        ['icon' => '📈', 'title' => 'Financial Advisory',      'text' => 'P&L optimization, fundraising strategy, and investor relations'],
        ['icon' => '🌐', 'title' => 'Market Expansion',        'text' => 'Go-to-market strategy, international expansion, and new revenue streams'],
    ];
    $testimonials = $testimonialsData['items'] ?? $profile['testimonials'] ?? [
        ['name' => 'Vikram Nair',     'role' => 'CEO, TechVentures India',   'text' => 'The strategic roadmap delivered transformed our business. Revenue grew 3x in 18 months.'],
        ['name' => 'Meera Krishnan',  'role' => 'MD, Horizon Retail Group',  'text' => 'Exceptional insights and practical execution. Our operational costs dropped by 35%.'],
        ['name' => 'Arjun Reddy',     'role' => 'Founder, ScaleUp Labs',     'text' => 'The best investment we made. Clear frameworks, actionable advice, outstanding results.'],
    ];
    $contactHeading = $contactData['heading']     ?? "Ready to Transform Your Business?";
    $contactText    = $contactData['text']        ?? "Let's discuss your challenges and build a roadmap to sustainable growth and success.";
    $contactBtn     = $contactData['button_text'] ?? 'Schedule Free Strategy Call';
    $contactBtnUrl  = $contactData['button_url']  ?? ($profile['booking_link'] ?? ($tenantContactUrl ?? '/contact'));
    $tenantBase     = isset($tenant) && $tenant->custom_domain ? '' : ('/' . ($tenant->username ?? ''));
@endphp
<style>
:root { --con-accent: {{ $accent }}; }
.xn-con-wrap { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 4rem; }
/* HERO */
.xn-con-hero { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; padding: 5rem 0 4rem; }
@media(max-width:768px){ .xn-con-hero{grid-template-columns:1fr;text-align:center;gap:2rem;padding:3rem 0 2rem;} }
.xn-con-hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: color-mix(in srgb, var(--con-accent) 12%, transparent); color: var(--con-accent); font-size: 0.72rem; font-weight: 800; padding: 0.35rem 1rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.08em; border: 1px solid color-mix(in srgb, var(--con-accent) 22%, transparent); margin-bottom: 1.25rem; }
.xn-con-hero-name { font-size: clamp(2.2rem, 4vw, 3.5rem); font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 0.75rem; }
.xn-con-hero-title { font-size: 1.1rem; color: var(--con-accent); font-weight: 600; margin-bottom: 1rem; }
.xn-con-hero-about { font-size: 1rem; color: #94a3b8; line-height: 1.8; margin-bottom: 2rem; }
.xn-con-hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
@media(max-width:768px){ .xn-con-hero-actions{justify-content:center;} }
.xn-con-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.75rem; border-radius: 8px; font-size: 0.9rem; font-weight: 700; text-decoration: none; transition: all 0.25s; cursor: pointer; border: none; }
.xn-con-btn-primary { background: var(--con-accent); color: #fff; box-shadow: 0 8px 24px color-mix(in srgb, var(--con-accent) 30%, transparent); }
.xn-con-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 32px color-mix(in srgb, var(--con-accent) 45%, transparent); color:#fff; }
.xn-con-btn-outline { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,0.22); }
.xn-con-btn-outline:hover { background: rgba(255,255,255,0.07); color:#fff; }
.xn-con-hero-img { border-radius: 24px; overflow: hidden; aspect-ratio: 1; background: #1a1a1a; display: flex; align-items: center; justify-content: center; font-size: 6rem; border: 1px solid rgba(255,255,255,0.07); position: relative; }
.xn-con-hero-img img { width: 100%; height: 100%; object-fit: cover; }
.xn-con-hero-img-badge { position: absolute; bottom: 1.5rem; left: 1.5rem; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 0.75rem 1rem; }
.xn-con-hero-img-badge-title { font-size: 0.7rem; color: #64748b; margin-bottom: 0.1rem; }
.xn-con-hero-img-badge-val { font-size: 1rem; font-weight: 800; color: #fff; }
/* STATS */
.xn-con-stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1px; background: rgba(255,255,255,0.07); border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.07); margin-bottom: 4rem; }
.xn-con-stat-cell { background: #1a1a1a; padding: 2rem 1rem; text-align: center; transition: background 0.2s; }
.xn-con-stat-cell:hover { background: color-mix(in srgb, var(--con-accent) 6%, #1a1a1a); }
.xn-con-stat-icon { font-size: 1.5rem; margin-bottom: 0.5rem; }
.xn-con-stat-num { font-size: 2rem; font-weight: 900; color: #fff; line-height: 1; }
.xn-con-stat-label { font-size: 0.72rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.3rem; }
/* SECTION */
.xn-con-section { padding: 4rem 0; }
.xn-con-section-header { text-align: center; margin-bottom: 3rem; }
.xn-con-badge { display: inline-block; background: color-mix(in srgb, var(--con-accent) 12%, transparent); color: var(--con-accent); font-size: 0.7rem; font-weight: 800; padding: 0.3rem 0.9rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem; border: 1px solid color-mix(in srgb, var(--con-accent) 22%, transparent); }
.xn-con-section-title { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: #fff; margin: 0 0 0.5rem; }
.xn-con-section-sub { font-size: 1rem; color: #64748b; max-width: 520px; margin: 0 auto; }
.xn-con-divider { width: 48px; height: 3px; background: var(--con-accent); border-radius: 2px; margin: 1rem auto 0; }
/* SERVICES */
.xn-con-services-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-con-service-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; padding: 1.75rem; transition: all 0.25s; border-left: 3px solid transparent; }
.xn-con-service-card:hover { border-left-color: var(--con-accent); transform: translateX(4px); box-shadow: 0 8px 24px rgba(0,0,0,0.3); }
.xn-con-service-icon { font-size: 2rem; margin-bottom: 1rem; }
.xn-con-service-title { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; }
.xn-con-service-text { font-size: 0.85rem; color: #64748b; line-height: 1.6; }
.xn-con-service-price { margin-top: 0.75rem; font-size: 0.8rem; font-weight: 700; color: var(--con-accent); }
/* ABOUT */
.xn-con-about-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 3rem; align-items: center; }
@media(max-width:768px){ .xn-con-about-grid{grid-template-columns:1fr;} }
.xn-con-about-text { font-size: 1rem; color: #94a3b8; line-height: 1.9; margin-bottom: 1.5rem; }
.xn-con-expertise-list { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; }
.xn-con-expertise-tag { background: color-mix(in srgb, var(--con-accent) 10%, transparent); color: var(--con-accent); border: 1px solid color-mix(in srgb, var(--con-accent) 22%, transparent); padding: 0.3rem 0.85rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; }
.xn-con-about-img { border-radius: 20px; overflow: hidden; aspect-ratio: 4/5; background: #1a1a1a; display: flex; align-items: center; justify-content: center; font-size: 5rem; border: 1px solid rgba(255,255,255,0.07); }
.xn-con-about-img img { width: 100%; height: 100%; object-fit: cover; }
/* TESTIMONIALS */
.xn-con-testimonials-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-con-testimonial-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; padding: 1.75rem; position: relative; }
.xn-con-testimonial-card::before { content: '"'; position: absolute; top: 1rem; right: 1.5rem; font-size: 4rem; color: color-mix(in srgb, var(--con-accent) 15%, transparent); font-family: Georgia, serif; line-height: 1; }
.xn-con-testimonial-text { font-size: 0.9rem; color: #94a3b8; line-height: 1.7; margin-bottom: 1.25rem; font-style: italic; }
.xn-con-testimonial-author { display: flex; align-items: center; gap: 0.75rem; }
.xn-con-testimonial-avatar { width: 44px; height: 44px; border-radius: 50%; background: var(--con-accent); display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 700; color: #fff; flex-shrink: 0; overflow: hidden; }
.xn-con-testimonial-avatar img { width: 100%; height: 100%; object-fit: cover; }
.xn-con-testimonial-name { font-size: 0.875rem; font-weight: 700; color: #fff; }
.xn-con-testimonial-role { font-size: 0.75rem; color: #64748b; }
/* BLOG */
.xn-con-blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-con-blog-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; overflow: hidden; transition: all 0.25s; }
.xn-con-blog-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.3); }
.xn-con-blog-img { height: 180px; background: linear-gradient(135deg, color-mix(in srgb, var(--con-accent) 20%, transparent), rgba(99,102,241,0.2)); display: flex; align-items: center; justify-content: center; font-size: 3rem; overflow: hidden; }
.xn-con-blog-img img { width: 100%; height: 100%; object-fit: cover; }
.xn-con-blog-body { padding: 1.25rem; }
.xn-con-blog-cat { font-size: 0.7rem; font-weight: 700; color: var(--con-accent); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.5rem; }
.xn-con-blog-title { font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; line-height: 1.4; text-decoration: none; display: block; }
.xn-con-blog-title:hover { color: var(--con-accent); }
.xn-con-blog-excerpt { font-size: 0.8rem; color: #64748b; line-height: 1.6; }
/* JOBS */
.xn-con-jobs-list { display: flex; flex-direction: column; gap: 0.75rem; }
.xn-con-job-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; transition: all 0.2s; }
.xn-con-job-card:hover { border-color: color-mix(in srgb, var(--con-accent) 35%, transparent); }
.xn-con-job-title { font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 0.25rem; }
.xn-con-job-meta { font-size: 0.8rem; color: #64748b; }
/* CTA */
.xn-con-cta { background: linear-gradient(135deg, color-mix(in srgb, var(--con-accent) 12%, transparent) 0%, rgba(99,102,241,0.06) 100%); border: 1px solid color-mix(in srgb, var(--con-accent) 22%, transparent); border-radius: 24px; padding: 4rem 2rem; text-align: center; }
.xn-con-cta h2 { font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 900; color: #fff; margin-bottom: 1rem; }
.xn-con-cta p { font-size: 1rem; color: #94a3b8; max-width: 500px; margin: 0 auto 2rem; }
@media(max-width:640px){
    .xn-con-services-grid,.xn-con-testimonials-grid,.xn-con-blog-grid{grid-template-columns:1fr;}
    .xn-con-stats-row{grid-template-columns:repeat(2,1fr);}
}
</style>

<div class="xn-con-wrap">

{{-- HERO --}}
@if($_show('hero'))
<div class="xn-con-hero">
    <div>
        <div class="xn-con-hero-badge"><i class="fas fa-briefcase"></i> Business Consultant</div>
        <h1 class="xn-con-hero-name">{{ $heroHeading }}</h1>
        <div class="xn-con-hero-title">{{ $heroSubheading }}</div>
        @if(!empty($aboutData['text']) || !empty($profile['about']))
        <p class="xn-con-hero-about">{{ Str::limit($aboutData['text'] ?? $profile['about'] ?? '', 180) }}</p>
        @endif
        <div class="xn-con-hero-actions">
            <a href="{{ $heroCtaUrl }}" class="xn-con-btn xn-con-btn-primary"><i class="fas fa-calendar-check"></i> {{ $heroCta }}</a>
            <a href="{{ url($tenantBase . '/about') }}" class="xn-con-btn xn-con-btn-outline"><i class="fas fa-user"></i> About Me</a>
        </div>
    </div>
    <div class="xn-con-hero-img">
        @if($tenant->avatar)<img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}">
        @else💼@endif
        @if(!empty($statsItems[0]))
        <div class="xn-con-hero-img-badge">
            <div class="xn-con-hero-img-badge-title">{{ $statsItems[0]['label'] ?? 'Experience' }}</div>
            <div class="xn-con-hero-img-badge-val">{{ $statsItems[0]['value'] ?? '15+' }}</div>
        </div>
        @endif
    </div>
</div>
@endif

{{-- STATS --}}
@if($_show('stats') && count($statsItems))
<div class="xn-con-stats-row">
    @foreach($statsItems as $stat)
    <div class="xn-con-stat-cell">
        @if(!empty($stat['icon']))<div class="xn-con-stat-icon">{{ $stat['icon'] }}</div>@endif
        <div class="xn-con-stat-num">{{ $stat['value'] ?? $stat['num'] ?? '' }}</div>
        <div class="xn-con-stat-label">{{ $stat['label'] ?? '' }}</div>
    </div>
    @endforeach
</div>
@endif

{{-- SERVICES --}}
@if($_show('services'))
<div class="xn-con-section" style="padding-top:0;">
    <div class="xn-con-section-header">
        <div class="xn-con-badge">What I Do</div>
        <h2 class="xn-con-section-title">{{ $servicesData['heading'] ?? 'Consulting Services' }}</h2>
        @if(!empty($servicesData['subheading']))<p class="xn-con-section-sub">{{ $servicesData['subheading'] }}</p>@endif
        <div class="xn-con-divider"></div>
    </div>
    <div class="xn-con-services-grid">
        @foreach($services as $svc)
        <div class="xn-con-service-card">
            <div class="xn-con-service-icon">{{ $svc['icon'] ?? '💼' }}</div>
            <div class="xn-con-service-title">{{ $svc['title'] ?? $svc['name'] ?? '' }}</div>
            <div class="xn-con-service-text">{{ $svc['text'] ?? $svc['description'] ?? '' }}</div>
            @if(!empty($svc['price']))<div class="xn-con-service-price">{{ $svc['price'] }}</div>@endif
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ url($tenantBase . '/services') }}" class="xn-con-btn xn-con-btn-outline"><i class="fas fa-list"></i> View All Services</a>
    </div>
</div>
@endif

{{-- ABOUT --}}
@if($_show('about'))
<div class="xn-con-section">
    <div class="xn-con-about-grid">
        <div>
            <div class="xn-con-badge">About Me</div>
            <h2 class="xn-con-section-title" style="text-align:left;margin-bottom:1rem;">{{ $aboutData['heading'] ?? 'My Approach' }}</h2>
            <p class="xn-con-about-text">{{ $aboutData['text'] ?? $profile['about'] ?? '' }}</p>
            @if(!empty($profile['expertise']))
            <div class="xn-con-expertise-list">
                @foreach((is_array($profile['expertise']) ? $profile['expertise'] : explode(',', $profile['expertise'])) as $exp)
                <span class="xn-con-expertise-tag">{{ trim($exp) }}</span>
                @endforeach
            </div>
            @endif
            <a href="{{ url($tenantBase . '/about') }}" class="xn-con-btn xn-con-btn-primary"><i class="fas fa-user-tie"></i> Full Profile</a>
        </div>
        <div class="xn-con-about-img">
            @if(!empty($aboutData['image']))<img src="{{ $aboutData['image'] }}" alt="{{ $tenant->name }}">
            @elseif($tenant->avatar)<img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}">
            @else💡@endif
        </div>
    </div>
</div>
@endif

{{-- TESTIMONIALS --}}
@if($_show('testimonials') && count($testimonials))
<div class="xn-con-section">
    <div class="xn-con-section-header">
        <div class="xn-con-badge">Client Success</div>
        <h2 class="xn-con-section-title">{{ $testimonialsData['heading'] ?? 'What Clients Say' }}</h2>
        <div class="xn-con-divider"></div>
    </div>
    <div class="xn-con-testimonials-grid">
        @foreach($testimonials as $t)
        <div class="xn-con-testimonial-card">
            <p class="xn-con-testimonial-text">"{{ $t['text'] ?? '' }}"</p>
            <div class="xn-con-testimonial-author">
                <div class="xn-con-testimonial-avatar">
                    @if(!empty($t['avatar']))<img src="{{ $t['avatar'] }}" alt="{{ $t['name'] ?? '' }}">@else{{ strtoupper(substr($t['name'] ?? 'A', 0, 1)) }}@endif
                </div>
                <div>
                    <div class="xn-con-testimonial-name">{{ $t['name'] ?? '' }}</div>
                    <div class="xn-con-testimonial-role">{{ $t['role'] ?? '' }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- BLOG --}}
@if($_show('blog') && (isset($featuredPost) && $featuredPost || !empty($categoryPosts)))
<div class="xn-con-section">
    <div class="xn-con-section-header">
        <div class="xn-con-badge">Insights</div>
        <h2 class="xn-con-section-title">{{ $blogData['heading'] ?? 'Latest Insights' }}</h2>
        <div class="xn-con-divider"></div>
    </div>
    <div class="xn-con-blog-grid">
        @php
            $blogPosts = collect();
            foreach($categoryPosts ?? [] as $cp) { $blogPosts = $blogPosts->merge($cp['posts'] ?? []); }
            if($blogPosts->isEmpty() && isset($featuredPost) && $featuredPost) $blogPosts = collect([$featuredPost]);
            $blogPosts = $blogPosts->unique('id')->take($blogData['count'] ?? 3);
        @endphp
        @foreach($blogPosts as $post)
        <div class="xn-con-blog-card">
            <div class="xn-con-blog-img">
                @if($post->featured_image)<img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}">@else📝@endif
            </div>
            <div class="xn-con-blog-body">
                @if(isset($post->category) && $post->category)<div class="xn-con-blog-cat">{{ $post->category->name ?? '' }}</div>@endif
                <a href="{{ url($tenantBase . '/blog/' . $post->slug) }}" class="xn-con-blog-title">{{ $post->title }}</a>
                <div class="xn-con-blog-excerpt">{{ Str::limit(strip_tags($post->content ?? $post->excerpt ?? ''), 90) }}</div>
            </div>
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ url($tenantBase . '/blog') }}" class="xn-con-btn xn-con-btn-outline"><i class="fas fa-newspaper"></i> View All Insights</a>
    </div>
</div>
@endif

{{-- JOBS --}}
@if($_show('jobs') && isset($activeJobs) && $activeJobs->count())
<div class="xn-con-section">
    <div class="xn-con-section-header">
        <div class="xn-con-badge">Careers</div>
        <h2 class="xn-con-section-title">Open Positions</h2>
        <div class="xn-con-divider"></div>
    </div>
    <div class="xn-con-jobs-list">
        @foreach($activeJobs->take(4) as $job)
        <div class="xn-con-job-card">
            <div>
                <div class="xn-con-job-title">{{ $job->title }}</div>
                <div class="xn-con-job-meta">{{ $job->location }} &bull; {{ ucfirst($job->type ?? 'Full-time') }}</div>
            </div>
            <a href="{{ url($tenantBase . '/jobs/' . $job->slug) }}" class="xn-con-btn xn-con-btn-outline" style="padding:0.5rem 1rem;font-size:0.8rem;">Apply</a>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- CTA --}}
@if($_show('contact'))
<div class="xn-con-section">
    <div class="xn-con-cta">
        <div class="xn-con-badge" style="margin-bottom:1rem;">Get Started</div>
        <h2>{{ $contactHeading }}</h2>
        <p>{{ $contactText }}</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ $contactBtnUrl }}" class="xn-con-btn xn-con-btn-primary" style="font-size:1rem;padding:1rem 2.5rem;"><i class="fas fa-calendar-check"></i> {{ $contactBtn }}</a>
            @if(!empty($profile['email']))<a href="mailto:{{ $profile['email'] }}" class="xn-con-btn xn-con-btn-outline"><i class="fas fa-envelope"></i> Send Email</a>@endif
        </div>
    </div>
</div>
@endif

</div>
@endsection
