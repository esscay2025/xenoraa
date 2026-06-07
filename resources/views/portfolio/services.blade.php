@extends('layouts.app')
@section('title', ($servicesPage ? $servicesPage->title : 'Services') . ' | ' . $siteName)
@section('description', Str::limit(strip_tags($settings['profile_about'] ?? 'Professional services by ' . $siteName), 160))
@push('styles')
<style>
:root { --sv-accent: {{ $accentColor }}; }
.sv-hero {
    background: linear-gradient(135deg, #0a0a0a 0%, #111 60%, #0a0a0a 100%);
    position: relative; overflow: hidden; padding: 5rem 0 4rem; text-align: center;
}
.sv-hero::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 70% 50% at 50% 30%, color-mix(in srgb, var(--sv-accent) 15%, transparent), transparent 70%);
    pointer-events: none;
}
.sv-hero-inner { max-width: 760px; margin: 0 auto; padding: 0 2rem; position: relative; }
.sv-badge {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: color-mix(in srgb, var(--sv-accent) 15%, transparent);
    border: 1px solid color-mix(in srgb, var(--sv-accent) 40%, transparent);
    color: var(--sv-accent); padding: 0.35rem 1rem; border-radius: 50px;
    font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1.25rem;
}
.sv-hero h1 { font-size: clamp(2rem, 5vw, 3.2rem); font-weight: 900; color: #fff; margin: 0 0 1rem; line-height: 1.15; }
.sv-hero p  { font-size: 1.05rem; color: #a0a0a0; margin: 0 auto 2rem; max-width: 600px; line-height: 1.7; }
.sv-hero-cta { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2.5rem; background: var(--sv-accent); color: #fff; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 1rem; transition: opacity 0.2s, transform 0.2s; }
.sv-hero-cta:hover { opacity: 0.88; transform: translateY(-2px); }
.sv-body { max-width: 1200px; margin: 0 auto; padding: 4rem 2rem; }
@media (max-width: 768px) { .sv-body { padding: 2.5rem 1rem; } }
.sv-section-label { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--sv-accent); margin-bottom: 0.4rem; }
.sv-section-label::before { content: ''; width: 18px; height: 2px; background: var(--sv-accent); border-radius: 1px; }
.sv-section-title { font-size: 1.75rem; font-weight: 800; margin: 0 0 0.4rem; color: #fff; }
.sv-section-sub   { color: #666; margin: 0 0 2.5rem; font-size: 0.9rem; }
.sv-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
@media (max-width: 640px) { .sv-grid { grid-template-columns: 1fr; } }
.sv-card { background: #111; border: 1px solid #2a2a2a; border-radius: 20px; padding: 1.75rem; transition: border-color 0.25s, transform 0.25s, box-shadow 0.25s; position: relative; overflow: hidden; }
.sv-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--sv-accent), color-mix(in srgb, var(--sv-accent) 50%, transparent)); opacity: 0; transition: opacity 0.25s; }
.sv-card:hover { border-color: color-mix(in srgb, var(--sv-accent) 40%, transparent); transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.3); }
.sv-card:hover::before { opacity: 1; }
.sv-card-icon { width: 52px; height: 52px; border-radius: 14px; background: color-mix(in srgb, var(--sv-accent) 15%, transparent); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-bottom: 1.25rem; }
.sv-card-name { font-size: 1.05rem; font-weight: 700; color: #fff; margin: 0 0 0.5rem; }
.sv-card-desc { color: #a0a0a0; font-size: 0.875rem; line-height: 1.65; margin: 0 0 1.25rem; }
.sv-card-price { display: inline-flex; align-items: center; gap: 0.4rem; background: color-mix(in srgb, var(--sv-accent) 12%, transparent); border: 1px solid color-mix(in srgb, var(--sv-accent) 30%, transparent); color: var(--sv-accent); padding: 0.35rem 0.875rem; border-radius: 50px; font-size: 0.82rem; font-weight: 700; }
.sv-process { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem; margin: 2.5rem 0; }
.sv-step { background: #111; border: 1px solid #2a2a2a; border-radius: 16px; padding: 1.5rem; transition: border-color 0.2s; }
.sv-step:hover { border-color: color-mix(in srgb, var(--sv-accent) 40%, transparent); }
.sv-step-num { width: 36px; height: 36px; border-radius: 50%; background: var(--sv-accent); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; margin-bottom: 1rem; }
.sv-step-title { font-size: 0.95rem; font-weight: 700; color: #fff; margin: 0 0 0.4rem; }
.sv-step-desc  { color: #a0a0a0; font-size: 0.82rem; line-height: 1.6; margin: 0; }
.sv-stats-band { background: linear-gradient(135deg, color-mix(in srgb, var(--sv-accent) 12%, #0a0a0a), #0a0a0a); border: 1px solid color-mix(in srgb, var(--sv-accent) 25%, transparent); border-radius: 20px; padding: 2.5rem 2rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 1.5rem; text-align: center; margin: 3rem 0; }
.sv-stat-value { font-size: 2.2rem; font-weight: 900; color: var(--sv-accent); line-height: 1; }
.sv-stat-label { font-size: 0.72rem; color: #666; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 0.3rem; }
.sv-testimonials { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; }
.sv-testi { background: #111; border: 1px solid #2a2a2a; border-radius: 16px; padding: 1.5rem; transition: border-color 0.2s; }
.sv-testi:hover { border-color: color-mix(in srgb, var(--sv-accent) 40%, transparent); }
.sv-stars { color: #f59e0b; font-size: 0.85rem; margin-bottom: 0.75rem; }
.sv-testi-text { color: #c0c0c0; font-size: 0.875rem; line-height: 1.7; margin: 0 0 1rem; font-style: italic; }
.sv-testi-name { font-weight: 700; color: #fff; font-size: 0.875rem; margin: 0; }
.sv-testi-role { color: #666; font-size: 0.78rem; margin: 0.1rem 0 0; }
.sv-cta { background: linear-gradient(135deg, color-mix(in srgb, var(--sv-accent) 18%, #0a0a0a), #0a0a0a); border: 1px solid color-mix(in srgb, var(--sv-accent) 30%, transparent); border-radius: 24px; padding: 3rem 2rem; text-align: center; margin-top: 4rem; }
.sv-cta h2 { font-size: 1.9rem; font-weight: 800; color: #fff; margin: 0 0 0.75rem; }
.sv-cta p  { color: #a0a0a0; margin: 0 0 2rem; }
.sv-cta-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2.5rem; background: var(--sv-accent); color: #fff; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 1rem; transition: opacity 0.2s, transform 0.2s; }
.sv-cta-btn:hover { opacity: 0.88; transform: translateY(-1px); }
@media (max-width: 768px) { .sv-cta { padding: 2rem 1.25rem; } .sv-cta h2 { font-size: 1.4rem; } }
</style>
@endpush
@section('content')
@php
    $tmpl = $template ?? 'consultant';
    $page = $servicesPage ?? null;
    $heroData    = $page ? $page->getSectionData('hero')         : [];
    $listData    = $page ? $page->getSectionData('list')         : [];
    $statsData   = $page ? $page->getSectionData('stats')        : [];
    $testData    = $page ? $page->getSectionData('testimonials') : [];
    $processData = $page ? $page->getSectionData('process')      : [];
    $ctaData     = $page ? $page->getSectionData('cta')          : [];
    $heroHeading = !empty($heroData['heading']) ? $heroData['heading'] : match($tmpl) {
        'influencer'   => 'Collaboration Packages',
        'advocate'     => 'Practice Areas & Legal Services',
        'doctor'       => 'Medical Services & Specializations',
        'entrepreneur' => 'Advisory & Consulting Services',
        'politician'   => 'Initiatives & Public Services',
        default        => 'Services & Solutions',
    };
    $heroSub = !empty($heroData['subheading']) ? $heroData['subheading'] : match($tmpl) {
        'influencer'   => 'Authentic content creation packages for brands of all sizes',
        'advocate'     => 'Expert legal representation across multiple practice areas',
        'doctor'       => 'Comprehensive healthcare services with compassionate care',
        'entrepreneur' => 'Strategic guidance for startups and growth-stage companies',
        'politician'   => 'Serving the community through policy, development, and outreach',
        default        => 'Professional services tailored to your unique needs',
    };
    $badgeText = match($tmpl) {
        'influencer'   => '✨ 150+ Brand Partnerships',
        'advocate'     => '⚖️ 18+ Years of Legal Practice',
        'doctor'       => '🏥 Trusted Healthcare Provider',
        'entrepreneur' => '🚀 4 Ventures · $12M+ Raised',
        'politician'   => '🏛️ Serving the Public',
        default        => '⭐ Professional Services',
    };
    $serviceItems = !empty($listData['items']) ? $listData['items'] : [];
    if (empty($serviceItems) && !empty($services)) {
        foreach ($services as $svc) {
            $serviceItems[] = ['icon' => $svc->icon ?? '⭐', 'name' => $svc->title ?? $svc->name, 'description' => $svc->description ?? '', 'price' => $svc->price ?? ''];
        }
    }
    if (empty($serviceItems)) {
        $serviceItems = match($tmpl) {
            'influencer' => [
                ['icon' => '📸', 'name' => 'Instagram Post',      'description' => 'High-quality feed posts with professional photography and authentic captions.', 'price' => '₹25,000 – ₹60,000'],
                ['icon' => '🎬', 'name' => 'Instagram Reel',      'description' => 'Engaging short-form video content optimized for maximum reach.', 'price' => '₹40,000 – ₹1,20,000'],
                ['icon' => '▶️', 'name' => 'YouTube Integration', 'description' => 'Brand integration in long-form YouTube videos.', 'price' => '₹80,000 – ₹2,50,000'],
                ['icon' => '📦', 'name' => 'Full Campaign',       'description' => 'Multi-platform campaign with strategy and analytics.', 'price' => 'Custom Quote'],
            ],
            'advocate' => [
                ['icon' => '🏢', 'name' => 'Corporate Law',    'description' => 'Company formation, M&A, shareholder disputes, and commercial contracts.', 'price' => ''],
                ['icon' => '💡', 'name' => 'IP Law',           'description' => 'Trademark, patent, copyright protection and IP litigation.', 'price' => ''],
                ['icon' => '⚖️', 'name' => 'Civil Litigation', 'description' => 'Property disputes, contract enforcement, and appellate advocacy.', 'price' => ''],
                ['icon' => '👨‍👩‍👧', 'name' => 'Family Law', 'description' => 'Divorce, child custody, matrimonial property disputes.', 'price' => ''],
            ],
            'doctor' => [
                ['icon' => '🩺', 'name' => 'General Consultation',  'description' => 'Comprehensive health assessment and personalized treatment plans.', 'price' => '₹500'],
                ['icon' => '💊', 'name' => 'Specialist Consultation','description' => 'Expert diagnosis and treatment for specialized conditions.', 'price' => '₹1,200'],
                ['icon' => '🔬', 'name' => 'Diagnostic Services',   'description' => 'Advanced diagnostic testing with quick turnaround.', 'price' => 'Varies'],
                ['icon' => '📱', 'name' => 'Telemedicine',          'description' => 'Online consultations from the comfort of your home.', 'price' => '₹400'],
            ],
            'entrepreneur' => [
                ['icon' => '🎯', 'name' => 'Startup Strategy',     'description' => 'Business model validation and go-to-market strategy.', 'price' => '₹50,000 / session'],
                ['icon' => '💰', 'name' => 'Fundraising Advisory', 'description' => 'Pitch deck review and investor introductions.', 'price' => '₹1,00,000 / month'],
                ['icon' => '📈', 'name' => 'Growth Consulting',    'description' => 'Customer acquisition and scaling playbooks.', 'price' => 'Custom Quote'],
                ['icon' => '🤝', 'name' => 'Board Advisory',       'description' => 'Ongoing board role with monthly strategy sessions.', 'price' => 'Equity + Retainer'],
            ],
            default => [
                ['icon' => '💼', 'name' => 'Strategy Consulting', 'description' => 'Business strategy, market analysis, and growth planning.', 'price' => 'Custom Quote'],
                ['icon' => '📊', 'name' => 'Business Analysis',   'description' => 'Data-driven insights and performance optimization.', 'price' => 'Custom Quote'],
                ['icon' => '🎯', 'name' => 'Project Management',  'description' => 'End-to-end project delivery with proven methodologies.', 'price' => 'Custom Quote'],
                ['icon' => '🤝', 'name' => 'Advisory Services',   'description' => 'Ongoing advisory and mentorship for your business.', 'price' => 'Custom Quote'],
            ],
        };
    }
    $statItems = !empty($statsData['items']) ? $statsData['items'] : [
        ['label' => 'Years Experience', 'value' => $settings['profile_years']    ?? '10+'],
        ['label' => 'Clients Served',   'value' => $settings['profile_clients']  ?? '200+'],
        ['label' => 'Projects Done',    'value' => $settings['profile_projects'] ?? '500+'],
        ['label' => 'Success Rate',     'value' => '96%'],
    ];
    $testiItems = !empty($testData['items']) ? $testData['items'] : [];
    $processItems = !empty($processData['items']) ? $processData['items'] : match($tmpl) {
        'influencer' => [
            ['title' => 'Brief & Discovery',  'desc' => 'Share your brand brief, goals, and target audience.'],
            ['title' => 'Concept & Proposal', 'desc' => 'I develop creative concepts aligned with your brand.'],
            ['title' => 'Content Creation',   'desc' => 'Professional content creation with multiple revisions.'],
            ['title' => 'Review & Publish',   'desc' => 'Final approval and scheduled publishing with analytics.'],
        ],
        'advocate' => [
            ['title' => 'Initial Consultation', 'desc' => 'Confidential discussion of your legal matter.'],
            ['title' => 'Case Assessment',      'desc' => 'Thorough analysis of facts, evidence, and legal options.'],
            ['title' => 'Strategy & Planning',  'desc' => 'Develop a tailored legal strategy for your case.'],
            ['title' => 'Representation',       'desc' => 'Expert advocacy and representation through resolution.'],
        ],
        'doctor' => [
            ['title' => 'Book Appointment', 'desc' => 'Schedule online or call our clinic directly.'],
            ['title' => 'Consultation',     'desc' => 'Thorough examination and health assessment.'],
            ['title' => 'Diagnosis',        'desc' => 'Accurate diagnosis with advanced diagnostic tools.'],
            ['title' => 'Treatment Plan',   'desc' => 'Personalized treatment plan with follow-up care.'],
        ],
        default => [
            ['title' => 'Discovery Call',   'desc' => 'Understand your goals, challenges, and requirements.'],
            ['title' => 'Proposal',         'desc' => 'Tailored proposal with scope, timeline, and pricing.'],
            ['title' => 'Execution',        'desc' => 'Deliver results with regular progress updates.'],
            ['title' => 'Review & Handoff', 'desc' => 'Final review, documentation, and ongoing support.'],
        ],
    };
    $ctaHeading = !empty($ctaData['heading'])     ? $ctaData['heading']     : 'Ready to Get Started?';
    $ctaText    = !empty($ctaData['text'])         ? $ctaData['text']        : "Let's discuss how I can help you achieve your goals.";
    $ctaBtn     = !empty($ctaData['button_text'])  ? $ctaData['button_text'] : 'Get in Touch';
    $ctaUrl     = !empty($ctaData['button_url'])   ? $ctaData['button_url']  : '/contact';
@endphp
<section class="sv-hero">
    <div class="sv-hero-inner">
        <div class="sv-badge">{{ $badgeText }}</div>
        <h1>{{ $heroHeading }}</h1>
        <p>{{ $heroSub }}</p>
        <a href="{{ $ctaUrl }}" class="sv-hero-cta"><i class="fas fa-arrow-right"></i> {{ $ctaBtn }}</a>
    </div>
</section>
<div class="sv-body">
    <div class="sv-stats-band">
        @foreach($statItems as $stat)
        <div>
            <div class="sv-stat-value">{{ is_array($stat) ? ($stat['value'] ?? '') : $stat }}</div>
            <div class="sv-stat-label">{{ is_array($stat) ? ($stat['label'] ?? '') : '' }}</div>
        </div>
        @endforeach
    </div>
    <section style="margin-bottom:4rem;">
        <div style="margin-bottom:2rem;">
            <div class="sv-section-label">What I Offer</div>
            <h2 class="sv-section-title">{{ !empty($listData['heading']) ? $listData['heading'] : 'Services & Solutions' }}</h2>
            @if(!empty($listData['subheading']))<p class="sv-section-sub">{{ $listData['subheading'] }}</p>@endif
        </div>
        <div class="sv-grid">
            @foreach($serviceItems as $item)
            <div class="sv-card">
                <div class="sv-card-icon">{{ is_array($item) ? ($item['icon'] ?? '⭐') : '⭐' }}</div>
                <h3 class="sv-card-name">{{ is_array($item) ? ($item['name'] ?? $item['title'] ?? '') : $item }}</h3>
                @if(is_array($item) && !empty($item['description'] ?? $item['text'] ?? ''))
                <p class="sv-card-desc">{{ $item['description'] ?? $item['text'] }}</p>
                @endif
                @if(is_array($item) && !empty($item['price'] ?? ''))
                <span class="sv-card-price"><i class="fas fa-tag"></i> {{ $item['price'] }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    <section style="margin-bottom:4rem;">
        <div style="margin-bottom:2rem;">
            <div class="sv-section-label">How It Works</div>
            <h2 class="sv-section-title">My Process</h2>
            <p class="sv-section-sub">A clear, transparent process from start to finish</p>
        </div>
        <div class="sv-process">
            @foreach($processItems as $i => $step)
            <div class="sv-step">
                <div class="sv-step-num">{{ $i + 1 }}</div>
                <h3 class="sv-step-title">{{ is_array($step) ? ($step['title'] ?? $step['name'] ?? '') : $step }}</h3>
                @if(is_array($step) && !empty($step['desc'] ?? $step['description'] ?? ''))
                <p class="sv-step-desc">{{ $step['desc'] ?? $step['description'] }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @if(!empty($testiItems))
    <section style="margin-bottom:4rem;">
        <div style="margin-bottom:2rem;">
            <div class="sv-section-label">Social Proof</div>
            <h2 class="sv-section-title">What Clients Say</h2>
        </div>
        <div class="sv-testimonials">
            @foreach($testiItems as $t)
            <div class="sv-testi">
                <div class="sv-stars">@for($s=0;$s<($t['rating']??5);$s++)<i class="fas fa-star"></i>@endfor</div>
                <p class="sv-testi-text">"{{ $t['text'] ?? '' }}"</p>
                <p class="sv-testi-name">{{ $t['name'] ?? '' }}</p>
                <p class="sv-testi-role">{{ $t['role'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif
    <div class="sv-cta">
        <h2>{{ $ctaHeading }}</h2>
        <p>{{ $ctaText }}</p>
        <a href="{{ $ctaUrl }}" class="sv-cta-btn"><i class="fas fa-arrow-right"></i> {{ $ctaBtn }}</a>
    </div>
</div>
@endsection
