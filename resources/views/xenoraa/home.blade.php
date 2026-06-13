@extends('layouts.xenoraa')
@section('title', 'Xenoraa — Build Your Digital Identity')
@section('meta_description', 'Xenoraa is the all-in-one SaaS platform for professionals to build their digital identity, manage clients, publish content, and grow their brand.')

@section('styles')
<style>
/* ===== HERO ===== */
.xn-hero {
    position: relative; min-height: 100vh;
    display: flex; align-items: center;
    overflow: hidden;
    background: #000;
}
.xn-hero-bg {
    position: absolute; inset: 0;
    background-image: url('/images/xenoraa/hero-bg.jpg');
    background-size: cover; background-position: center;
    opacity: 0.5;
}
.xn-hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.85) 100%);
}
.xn-hero-content {
    position: relative; z-index: 2;
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 4rem; align-items: center;
    max-width: 1280px; margin: 0 auto;
    padding: 0 4rem;
    width: 100%;
}
.xn-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-size: 0.7rem; font-weight: 700; letter-spacing: 0.15em;
    text-transform: uppercase; color: #a855f7;
    margin-bottom: 1.5rem;
}
.xn-hero-eyebrow::before { content: '>'; color: #7c3aed; font-size: 1rem; }
.xn-hero-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: clamp(3rem, 6vw, 6.5rem);
    font-weight: 900; line-height: 0.95;
    letter-spacing: -0.04em;
    color: #fff; margin-bottom: 1.5rem;
}
.xn-hero-title .accent { color: #a855f7; }
.xn-hero-title .outline {
    -webkit-text-stroke: 2px rgba(255,255,255,0.3);
    color: transparent;
}
.xn-hero-desc {
    font-size: 1.125rem; color: #a1a1aa;
    line-height: 1.75; margin-bottom: 2.5rem;
    max-width: 500px;
}
.xn-hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 3rem; }
.xn-hero-stats { display: flex; gap: 2.5rem; }
.xn-hero-stat-num { font-family: 'Space Grotesk', sans-serif; font-size: 1.75rem; font-weight: 800; color: #fff; }
.xn-hero-stat-label { font-size: 0.75rem; color: #71717a; margin-top: 0.2rem; }
.xn-hero-visual { position: relative; }
.xn-hero-img {
    width: 100%; border-radius: 16px;
    border: 1px solid rgba(124,58,237,0.2);
    box-shadow: 0 40px 80px rgba(0,0,0,0.6), 0 0 80px rgba(124,58,237,0.1);
}
.xn-hero-float-card {
    position: absolute; background: rgba(17,17,17,0.95);
    border: 1px solid rgba(124,58,237,0.3);
    border-radius: 12px; padding: 0.875rem 1.25rem;
    backdrop-filter: blur(10px);
    display: flex; align-items: center; gap: 0.75rem;
    animation: float 4s ease-in-out infinite;
}
.xn-hero-float-card.card1 { top: 10%; right: -5%; animation-delay: 0s; }
.xn-hero-float-card.card2 { bottom: 20%; left: -8%; animation-delay: 1.5s; }
.xn-hero-float-card.card3 { top: 55%; right: -10%; animation-delay: 0.75s; }
@keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
.xn-float-icon { width: 36px; height: 36px; background: rgba(124,58,237,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #a855f7; font-size: 1rem; flex-shrink: 0; }
.xn-float-label { font-size: 0.7rem; color: #71717a; }
.xn-float-value { font-size: 0.875rem; font-weight: 600; color: #fff; }

/* ===== TRUSTED BY ===== */
.xn-trusted { padding: 3rem 4rem; border-top: 1px solid #1a1a1a; border-bottom: 1px solid #1a1a1a; background: #050505; }
.xn-trusted-label { text-align: center; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #3f3f46; margin-bottom: 2rem; }
.xn-trusted-tags { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; }
.xn-trusted-tag {
    padding: 0.5rem 1.25rem;
    border: 1px solid #1f1f1f;
    border-radius: 100px;
    font-size: 0.8rem; color: #52525b;
    font-weight: 500; letter-spacing: 0.02em;
    transition: all 0.2s;
}
.xn-trusted-tag:hover { border-color: #3f3f46; color: #a1a1aa; }

/* ===== OVERVIEW ===== */
.xn-overview-split { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
.xn-overview-img { width: 100%; border-radius: 16px; border: 1px solid #1f1f1f; }
.xn-feature-list { display: flex; flex-direction: column; gap: 1.5rem; margin-top: 2rem; }
.xn-feature-item { display: flex; gap: 1rem; align-items: flex-start; }
.xn-feature-check { width: 24px; height: 24px; background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.3); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #a855f7; font-size: 0.7rem; flex-shrink: 0; margin-top: 2px; }
.xn-feature-title { font-weight: 600; color: #fff; font-size: 0.9rem; margin-bottom: 0.2rem; }
.xn-feature-desc { font-size: 0.825rem; color: #71717a; line-height: 1.6; }

/* ===== FEATURES GRID ===== */
.xn-features-section { background: #050505; }
.xn-features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5px; background: #1a1a1a; border: 1px solid #1a1a1a; border-radius: 16px; overflow: hidden; margin-top: 3rem; }
.xn-feature-block {
    background: #0a0a0a; padding: 2.5rem;
    transition: all 0.3s;
}
.xn-feature-block:hover { background: #111; }
.xn-feature-block-icon { font-size: 1.5rem; color: #7c3aed; margin-bottom: 1.25rem; }
.xn-feature-block-title { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; }
.xn-feature-block-desc { font-size: 0.825rem; color: #71717a; line-height: 1.65; }

/* ===== PRICING TEASER ===== */
.xn-pricing-teaser { background: #000; }
.xn-pricing-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 3rem; }
.xn-pricing-card {
    background: #0d0d0d; border: 1px solid #1f1f1f;
    border-radius: 16px; padding: 2.5rem;
    position: relative; transition: all 0.3s;
}
.xn-pricing-card:hover { border-color: #7c3aed; transform: translateY(-6px); }
.xn-pricing-card.popular { border-color: #7c3aed; background: linear-gradient(180deg, rgba(124,58,237,0.08) 0%, #0d0d0d 100%); }
.xn-popular-badge { position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: #7c3aed; color: #fff; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; padding: 0.3rem 1rem; border-radius: 100px; white-space: nowrap; }
.xn-price-plan { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #a855f7; margin-bottom: 0.75rem; }
.xn-price-amount { font-family: 'Space Grotesk', sans-serif; font-size: 3rem; font-weight: 800; color: #fff; line-height: 1; margin-bottom: 0.25rem; }
.xn-price-amount span { font-size: 1rem; font-weight: 400; color: #71717a; }
.xn-price-yearly { font-size: 0.8rem; color: #52525b; margin-bottom: 1.5rem; }
.xn-price-desc { font-size: 0.875rem; color: #71717a; margin-bottom: 2rem; line-height: 1.6; }
.xn-price-features { list-style: none; display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 2rem; }
.xn-price-features li { display: flex; align-items: center; gap: 0.75rem; font-size: 0.825rem; color: #a1a1aa; }
.xn-price-features li i { color: #7c3aed; font-size: 0.7rem; }

/* ===== SHOWCASE TEASER ===== */
.xn-showcase-section { background: #050505; }
.xn-showcase-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 3rem; }
.xn-showcase-card {
    background: #0d0d0d; border: 1px solid #1f1f1f;
    border-radius: 12px; overflow: hidden;
    transition: all 0.3s;
}
.xn-showcase-card:hover { border-color: #7c3aed; transform: translateY(-4px); }
.xn-showcase-img { width: 100%; aspect-ratio: 16/9; object-fit: cover; }
.xn-showcase-info { padding: 1.25rem; }
.xn-showcase-name { font-weight: 700; color: #fff; margin-bottom: 0.25rem; }
.xn-showcase-role { font-size: 0.8rem; color: #71717a; margin-bottom: 0.75rem; }
.xn-showcase-url { font-size: 0.75rem; color: #7c3aed; text-decoration: none; }
.xn-showcase-url:hover { color: #a855f7; }

/* ===== CTA ===== */
.xn-cta-section {
    background: linear-gradient(135deg, rgba(124,58,237,0.15) 0%, rgba(0,0,0,0) 50%, rgba(124,58,237,0.08) 100%);
    border-top: 1px solid #1a1a1a;
    text-align: center;
}

@media (max-width: 1024px) {
    .xn-hero-content { grid-template-columns: 1fr; padding: 0 2rem; }
    .xn-hero-visual { display: none; }
    .xn-overview-split { grid-template-columns: 1fr; }
    .xn-features-grid { grid-template-columns: repeat(2, 1fr); }
    .xn-pricing-cards { grid-template-columns: 1fr; max-width: 420px; margin-left: auto; margin-right: auto; }
    .xn-showcase-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .xn-hero { min-height: 90vh; }
    .xn-hero-content { padding: 0 1.5rem; }
    .xn-hero-stats { gap: 1.5rem; }
    .xn-trusted { padding: 2rem 1.5rem; }
    .xn-features-grid { grid-template-columns: 1fr; }
    .xn-showcase-grid { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')

{{-- ===== HERO ===== --}}
<section class="xn-hero">
    <div class="xn-hero-bg"></div>
    <div class="xn-hero-overlay"></div>
    <div class="xn-hero-content">
        <div>
            <div class="xn-hero-eyebrow">Website · E-Commerce · POS · CRM</div>
            <h1 class="xn-hero-title">
                BUILD YOUR<br>
                <span class="accent">DIGITAL</span><br>
                <span class="outline">IDENTITY.</span>
            </h1>
            <p class="xn-hero-desc">
                Xenoraa is the all-in-one platform for professionals, consultants, founders, creators, and leaders to showcase their personal brand while managing their daily business operations.
            </p>
            <div class="xn-hero-actions">
                <a href="{{ route('xenoraa.get-started') }}" class="xn-btn-primary-lg">
                    Get Started <i class="fas fa-arrow-right"></i>
                </a>
                <a href="{{ route('xenoraa.showcase') }}" class="xn-btn-outline-lg">
                    View Showcase
                </a>
            </div>
            <div class="xn-hero-stats">
                <div>
                    <div class="xn-hero-stat-num">10+</div>
                    <div class="xn-hero-stat-label">Modules Included</div>
                </div>
                <div>
                    <div class="xn-hero-stat-num">3</div>
                    <div class="xn-hero-stat-label">Pricing Plans</div>
                </div>
                <div>
                    <div class="xn-hero-stat-num">1</div>
                    <div class="xn-hero-stat-label">Unified Platform</div>
                </div>
            </div>
        </div>
        <div class="xn-hero-visual">
            <img src="/images/xenoraa/hero-professional.jpg" alt="Xenoraa Dashboard" class="xn-hero-img">
            <div class="xn-hero-float-card card1">
                <div class="xn-float-icon"><i class="fas fa-user-check"></i></div>
                <div>
                    <div class="xn-float-label">Profile Views</div>
                    <div class="xn-float-value">+2,840 this month</div>
                </div>
            </div>
            <div class="xn-hero-float-card card2">
                <div class="xn-float-icon"><i class="fas fa-robot"></i></div>
                <div>
                    <div class="xn-float-label">AI Assistant</div>
                    <div class="xn-float-value">Active & Learning</div>
                </div>
            </div>
            <div class="xn-hero-float-card card3">
                <div class="xn-float-icon"><i class="fas fa-chart-line"></i></div>
                <div>
                    <div class="xn-float-label">Leads Captured</div>
                    <div class="xn-float-value">48 new leads</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== TRUSTED BY ===== --}}
<div class="xn-trusted">
    <div class="xn-trusted-label">Trusted by professionals across industries</div>
    <div class="xn-trusted-tags">
        @foreach(['Doctors', 'Advocates', 'Politicians', 'Consultants', 'Business Owners', 'Coaches', 'Creators', 'Startup Founders', 'Freelancers', 'Public Speakers', 'Architects', 'Educators'] as $tag)
        <div class="xn-trusted-tag">{{ $tag }}</div>
        @endforeach
    </div>
</div>

{{-- ===== PRODUCT OVERVIEW ===== --}}
<section class="xn-section" style="background:#000;">
    <div class="xn-container">
        <div class="xn-overview-split">
            <div>
                <div class="xn-label">Product Overview</div>
                <h2 class="xn-heading-lg">Your Profile. Your Brand.<br><span style="color:#a855f7;">Your Ecosystem.</span></h2>
                <div class="xn-divider"></div>
                <p class="xn-body-lg">Whether you're a doctor, advocate, politician, consultant, entrepreneur, coach, or freelancer — Xenoraa helps you build credibility and stay organized from a single, powerful platform.</p>
                <div class="xn-feature-list">
                    <div class="xn-feature-item">
                        <div class="xn-feature-check"><i class="fas fa-check"></i></div>
                        <div>
                            <div class="xn-feature-title">Personal Branding Website</div>
                            <div class="xn-feature-desc">Create a stunning public profile at xenoraa.com/yourname or your own custom domain.</div>
                        </div>
                    </div>
                    <div class="xn-feature-item">
                        <div class="xn-feature-check"><i class="fas fa-check"></i></div>
                        <div>
                            <div class="xn-feature-title">CRM & Lead Management</div>
                            <div class="xn-feature-desc">Manage leads, clients, and professional relationships effortlessly with AI-powered insights.</div>
                        </div>
                    </div>
                    <div class="xn-feature-item">
                        <div class="xn-feature-check"><i class="fas fa-check"></i></div>
                        <div>
                            <div class="xn-feature-title">AI-Powered Assistant</div>
                            <div class="xn-feature-desc">Generate content, organize workflows, summarize notes, and boost productivity using AI.</div>
                        </div>
                    </div>
                    <div class="xn-feature-item">
                        <div class="xn-feature-check"><i class="fas fa-check"></i></div>
                        <div>
                            <div class="xn-feature-title">E-Commerce & Services</div>
                            <div class="xn-feature-desc">Sell products, services, digital downloads, courses, and consultation packages.</div>
                        </div>
                    </div>
                </div>
                <div style="margin-top:2.5rem;">
                    <a href="{{ route('xenoraa.features') }}" class="xn-btn-primary-lg">Explore All Features <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div>
                <img src="/images/xenoraa/hero-professional.jpg" alt="Xenoraa Platform" class="xn-overview-img">
            </div>
        </div>
    </div>
</section>

{{-- ===== FEATURES GRID ===== --}}
<section class="xn-section xn-features-section">
    <div class="xn-container">
        <div style="text-align:center;max-width:600px;margin:0 auto;">
            <div class="xn-label">Everything You Need</div>
            <h2 class="xn-heading-lg">One Platform.<br><span style="color:#a855f7;">Infinite Possibilities.</span></h2>
            <p class="xn-body" style="margin-top:1rem;">Every tool a professional needs to build their brand, manage their business, and grow their influence — all in one place.</p>
        </div>
        <div class="xn-features-grid">
            @php
            $features = [
                ['icon'=>'fa-user-circle','title'=>'Personal Branding','desc'=>'Create a powerful public profile showcasing your experience, achievements, services, and expertise.'],
                ['icon'=>'fa-users','title'=>'CRM & Contacts','desc'=>'Manage leads, clients, supporters, and professional relationships effortlessly.'],
                ['icon'=>'fa-calendar-alt','title'=>'Smart Calendar','desc'=>'Schedule appointments, consultations, meetings, events, and reminders seamlessly.'],
                ['icon'=>'fa-sticky-note','title'=>'Notes & Knowledge Hub','desc'=>'Store ideas, meeting notes, documents, and important information securely.'],
                ['icon'=>'fa-shopping-bag','title'=>'E-Commerce Store','desc'=>'Sell products, services, digital downloads, courses, and consultation packages.'],
                ['icon'=>'fa-pen-nib','title'=>'Content Publishing','desc'=>'Share blogs, articles, updates, achievements, and thought leadership content.'],
                ['icon'=>'fa-briefcase','title'=>'Portfolio & Showcase','desc'=>'Display projects, success stories, certifications, testimonials, and media appearances.'],
                ['icon'=>'fa-tasks','title'=>'Task Management','desc'=>'Never miss important deadlines, meetings, follow-ups, or commitments.'],
                ['icon'=>'fa-robot','title'=>'AI-Powered Assistant','desc'=>'Generate content, organize workflows, summarize notes, and boost productivity.'],
                ['icon'=>'fa-globe','title'=>'Custom Domain','desc'=>'Launch your professional website using your own domain and branding.'],
                ['icon'=>'fa-chart-bar','title'=>'Analytics & Insights','desc'=>'Track profile views, engagement, lead sources, and business performance.'],
                ['icon'=>'fa-comments','title'=>'AI Chat Widget','desc'=>'Engage visitors with an intelligent AI assistant trained on your services and expertise.'],
            ];
            @endphp
            @foreach($features as $f)
            <div class="xn-feature-block">
                <div class="xn-feature-block-icon"><i class="fas {{ $f['icon'] }}"></i></div>
                <div class="xn-feature-block-title">{{ $f['title'] }}</div>
                <div class="xn-feature-block-desc">{{ $f['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== SHOWCASE MOCKUP ===== --}}
<section class="xn-section" style="background:#000;text-align:center;">
    <div class="xn-container">
        <div class="xn-label">See It In Action</div>
        <h2 class="xn-heading-lg">Real Professionals.<br><span style="color:#a855f7;">Real Results.</span></h2>
        <p class="xn-body" style="max-width:560px;margin:1rem auto 3rem;">See how professionals across industries are using Xenoraa to build their digital presence and manage their business operations.</p>
        <img src="/images/xenoraa/showcase-mockup.jpg" alt="Xenoraa Showcase" style="width:100%;max-width:1000px;border-radius:16px;border:1px solid #1f1f1f;box-shadow:0 40px 80px rgba(0,0,0,0.6);">
        <div style="margin-top:2.5rem;">
            <a href="{{ route('xenoraa.showcase') }}" class="xn-btn-primary-lg">View All Showcases <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

{{-- ===== PRICING TEASER ===== --}}
<section class="xn-section xn-pricing-teaser">
    <div class="xn-container">
        <div style="text-align:center;max-width:600px;margin:0 auto;">
            <div class="xn-label">Pricing</div>
            <h2 class="xn-heading-lg">Choose The Plan That<br><span style="color:#a855f7;">Fits Your Growth</span></h2>
            <p class="xn-body" style="margin-top:1rem;">Start free and scale as you grow. No hidden fees, no long-term contracts.</p>
        </div>
        <div class="xn-pricing-cards">
            {{-- Solo App --}}
            <div class="xn-pricing-card">
                <div style="font-size:0.65rem;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;color:#a855f7;margin-bottom:0.25rem;">Tier 1</div>
                <div class="xn-price-plan">Solo App</div>
                <div class="xn-price-amount">₹499 <span>/ mo</span></div>
                <div class="xn-price-yearly">₹4,999 / year — save ₹989</div>
                <div class="xn-price-desc">One app — Website, E-Commerce, POS, or CRM. Full features, your choice.</div>
                <ul class="xn-price-features">
                    <li><i class="fas fa-check"></i> 1 app of your choice</li>
                    <li><i class="fas fa-check"></i> Custom domain mapping</li>
                    <li><i class="fas fa-check"></i> AI Content Assistant</li>
                    <li><i class="fas fa-check"></i> Analytics &amp; Insights</li>
                    <li><i class="fas fa-check"></i> Email support</li>
                </ul>
                <a href="{{ route('xenoraa.get-started') }}?plan=solo" class="xn-btn-ghost" style="width:100%;text-align:center;display:block;padding:0.75rem;">Get Started</a>
            </div>
            {{-- Duo Bundle --}}
            <div class="xn-pricing-card popular">
                <div class="xn-popular-badge">Most Popular</div>
                <div style="font-size:0.65rem;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;color:#a855f7;margin-bottom:0.25rem;">Tier 2</div>
                <div class="xn-price-plan">Duo Bundle</div>
                <div class="xn-price-amount">₹999 <span>/ mo</span></div>
                <div class="xn-price-yearly">₹9,999 / year — save ₹1,989</div>
                <div class="xn-price-desc">Two apps, deeply integrated. Website+CRM, E-Commerce+POS, or Website+E-Commerce.</div>
                <ul class="xn-price-features">
                    <li><i class="fas fa-check"></i> 2 apps of your choice</li>
                    <li><i class="fas fa-check"></i> Cross-app data integration</li>
                    <li><i class="fas fa-check"></i> AI Chat Widget</li>
                    <li><i class="fas fa-check"></i> Accounts &amp; Finance module</li>
                    <li><i class="fas fa-check"></i> Priority support</li>
                </ul>
                <a href="{{ route('xenoraa.get-started') }}?plan=duo" class="xn-btn-primary" style="width:100%;text-align:center;display:block;padding:0.75rem;font-size:0.9rem;">Get Started</a>
            </div>
            {{-- All-Access --}}
            <div class="xn-pricing-card">
                <div style="font-size:0.65rem;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;color:#a855f7;margin-bottom:0.25rem;">Tier 3</div>
                <div class="xn-price-plan">All-Access</div>
                <div class="xn-price-amount">₹1,999 <span>/ mo</span></div>
                <div class="xn-price-yearly">₹19,999 / year — save ₹3,989</div>
                <div class="xn-price-desc">All 4 apps unlocked — Website, E-Commerce, POS &amp; CRM. The complete ecosystem.</div>
                <ul class="xn-price-features">
                    <li><i class="fas fa-check"></i> All 4 apps included</li>
                    <li><i class="fas fa-check"></i> Projects, Tasks &amp; Services</li>
                    <li><i class="fas fa-check"></i> AI Hub &amp; Automation</li>
                    <li><i class="fas fa-check"></i> Team members (up to 5)</li>
                    <li><i class="fas fa-check"></i> White label &amp; API access</li>
                </ul>
                <a href="{{ route('xenoraa.get-started') }}?plan=allaccess" class="xn-btn-ghost" style="width:100%;text-align:center;display:block;padding:0.75rem;">Get Started</a>
            </div>
        </div>
        <div style="text-align:center;margin-top:2rem;">
            <a href="{{ route('xenoraa.pricing') }}" style="color:#a855f7;text-decoration:none;font-size:0.875rem;">View full pricing &amp; app details →</a>
        </div>
    </div>
</section>

{{-- ===== SUCCESS STORY ===== --}}
<section class="xn-section" style="background:#050505;">
    <div class="xn-container">
        <div style="max-width:800px;margin:0 auto;text-align:center;">
            <div class="xn-label">Success Story</div>
            <div style="font-size:1.5rem;color:#a855f7;margin-bottom:1.5rem;">"</div>
            <blockquote style="font-family:'Space Grotesk',sans-serif;font-size:clamp(1.25rem,2.5vw,1.75rem);font-weight:600;color:#fff;line-height:1.4;margin-bottom:2rem;">
                Using Xenoraa, I manage my personal portfolio, showcase projects, publish blogs, track leads, schedule meetings, and maintain my professional brand from a single platform.
            </blockquote>
            <div style="display:flex;align-items:center;justify-content:center;gap:1rem;">
                <div style="width:52px;height:52px;background:rgba(124,58,237,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.25rem;color:#a855f7;font-weight:700;">G</div>
                <div style="text-align:left;">
                    <div style="font-weight:700;color:#fff;">Gopi K.</div>
                    <div style="font-size:0.8rem;color:#71717a;">Founder, Go Esscay Solutions · <a href="https://gopi.blog" target="_blank" style="color:#7c3aed;text-decoration:none;">gopi.blog</a></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="xn-section xn-cta-section">
    <div class="xn-container" style="text-align:center;">
        <div class="xn-label">Get Started Today</div>
        <h2 class="xn-heading-lg" style="max-width:700px;margin:0 auto 1rem;">Your Business Deserves<br><span style="color:#a855f7;">One Platform.</span></h2>
        <p class="xn-body-lg" style="max-width:560px;margin:0 auto 2.5rem;">Website, E-Commerce, POS, and CRM — all in one place. Join Xenoraa and run your entire business from a single, beautifully designed platform.</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('xenoraa.get-started') }}" class="xn-btn-primary-lg">Get Started Now 🚀</a>
            <a href="{{ route('xenoraa.features') }}" class="xn-btn-outline-lg">Explore Features</a>
        </div>
        <p style="margin-top:1.5rem;font-size:0.8rem;color:#3f3f46;">30-day money-back guarantee · Setup in minutes · Cancel anytime</p>
    </div>
</section>

@endsection
