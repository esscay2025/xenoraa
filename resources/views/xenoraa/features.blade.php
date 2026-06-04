@extends('layouts.xenoraa')
@section('title', 'Features — Xenoraa')
@section('meta_description', 'Explore everything Xenoraa offers: personal branding, CRM, AI assistant, e-commerce, calendar, blog publishing, analytics, and more.')

@section('styles')
<style>
.xn-page-hero { padding: 5rem 4rem 4rem; background: linear-gradient(180deg, #0a0a0a 0%, #000 100%); border-bottom: 1px solid #1a1a1a; }
.xn-features-detail { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; padding: 5rem 0; border-bottom: 1px solid #111; }
.xn-features-detail:last-child { border-bottom: none; }
.xn-features-detail.reverse { direction: rtl; }
.xn-features-detail.reverse > * { direction: ltr; }
.xn-features-detail-img { width: 100%; border-radius: 16px; border: 1px solid #1f1f1f; }
.xn-feature-tag { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(124,58,237,0.1); border: 1px solid rgba(124,58,237,0.25); color: #a855f7; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; padding: 0.3rem 0.875rem; border-radius: 100px; margin-bottom: 1.25rem; }
.xn-mini-features { display: flex; flex-direction: column; gap: 1rem; margin-top: 1.75rem; }
.xn-mini-feature { display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; color: #a1a1aa; }
.xn-mini-feature i { color: #7c3aed; font-size: 0.75rem; }
@media(max-width:768px){
    .xn-page-hero{padding:3rem 1.5rem 2.5rem;}
    .xn-features-detail{grid-template-columns:1fr;gap:2rem;padding:3rem 0;}
    .xn-features-detail.reverse{direction:ltr;}
}
</style>
@endsection

@section('content')
<section class="xn-page-hero">
    <div class="xn-container">
        <div class="xn-label">Features</div>
        <h1 class="xn-heading-xl" style="max-width:700px;">Everything Xenoraa<br><span style="color:#a855f7;">Offers</span></h1>
        <p class="xn-body-lg" style="max-width:560px;margin-top:1.25rem;">One platform. Every tool a professional needs to build their brand, manage their business, and grow their influence.</p>
    </div>
</section>

<section class="xn-section" style="background:#000;">
    <div class="xn-container">

        {{-- Feature 1: Personal Branding --}}
        <div class="xn-features-detail">
            <div>
                <div class="xn-feature-tag"><i class="fas fa-user-circle"></i> Personal Branding</div>
                <h2 class="xn-heading-md">Your Digital Identity,<br><span style="color:#a855f7;">Perfectly Crafted</span></h2>
                <div class="xn-divider"></div>
                <p class="xn-body">Create a powerful public profile that showcases your experience, achievements, services, projects, and expertise. Your profile lives at <strong style="color:#fff;">xenoraa.com/yourname</strong> or your own custom domain.</p>
                <div class="xn-mini-features">
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Custom profile URL (xenoraa.com/username)</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Portfolio & project showcase</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Services & expertise listing</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Testimonials & social proof</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Custom domain mapping</div>
                </div>
            </div>
            <img src="/images/xenoraa/showcase-mockup.jpg" alt="Personal Branding" class="xn-features-detail-img">
        </div>

        {{-- Feature 2: CRM --}}
        <div class="xn-features-detail reverse">
            <div>
                <div class="xn-feature-tag"><i class="fas fa-users"></i> CRM & Lead Management</div>
                <h2 class="xn-heading-md">Manage Every<br><span style="color:#a855f7;">Relationship</span></h2>
                <div class="xn-divider"></div>
                <p class="xn-body">Manage leads, clients, supporters, customers, and professional relationships effortlessly. Track conversations, requirements, and follow-ups from a single dashboard.</p>
                <div class="xn-mini-features">
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Lead capture & tracking</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Conversation history</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Requirement gathering</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Email reply with PDF proposals</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Lead status & priority management</div>
                </div>
            </div>
            <img src="/images/xenoraa/features-grid.jpg" alt="CRM" class="xn-features-detail-img">
        </div>

        {{-- Feature 3: AI --}}
        <div class="xn-features-detail">
            <div>
                <div class="xn-feature-tag"><i class="fas fa-robot"></i> AI-Powered Assistant</div>
                <h2 class="xn-heading-md">Your 24/7<br><span style="color:#a855f7;">AI Business Partner</span></h2>
                <div class="xn-divider"></div>
                <p class="xn-body">An intelligent AI chatbot trained on your services, expertise, and business context. It acts as a business analyst, sales person, and requirement gatherer — engaging visitors and capturing leads automatically.</p>
                <div class="xn-mini-features">
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Trained on your services & expertise</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Requirement gathering from visitors</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Lead capture with email collection</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> GPT-4 powered responses</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Custom training categories</div>
                </div>
            </div>
            <img src="/images/xenoraa/hero-professional.jpg" alt="AI Assistant" class="xn-features-detail-img">
        </div>

        {{-- Feature 4: E-Commerce --}}
        <div class="xn-features-detail reverse">
            <div>
                <div class="xn-feature-tag"><i class="fas fa-shopping-bag"></i> E-Commerce Store</div>
                <h2 class="xn-heading-md">Sell Anything.<br><span style="color:#a855f7;">Earn Everything.</span></h2>
                <div class="xn-divider"></div>
                <p class="xn-body">Sell products, services, digital downloads, courses, memberships, and consultation packages directly from your profile. Manage inventory, orders, and reviews from one dashboard.</p>
                <div class="xn-mini-features">
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Product & category management</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Digital products & downloads</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Customer reviews & ratings</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Order management</div>
                    <div class="xn-mini-feature"><i class="fas fa-check-circle"></i> Consultation booking</div>
                </div>
            </div>
            <img src="/images/xenoraa/features-grid.jpg" alt="E-Commerce" class="xn-features-detail-img">
        </div>

    </div>
</section>

{{-- All Features Grid --}}
<section class="xn-section" style="background:#050505;">
    <div class="xn-container">
        <div style="text-align:center;margin-bottom:3rem;">
            <div class="xn-label">Complete Feature Set</div>
            <h2 class="xn-heading-lg">Every Tool You<br><span style="color:#a855f7;">Will Ever Need</span></h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;">
            @php
            $allFeatures = [
                ['icon'=>'fa-calendar-alt','title'=>'Smart Calendar','desc'=>'Schedule appointments, consultations, meetings, events, and reminders.'],
                ['icon'=>'fa-sticky-note','title'=>'Notes & Knowledge Hub','desc'=>'Store ideas, meeting notes, documents, and important information securely.'],
                ['icon'=>'fa-pen-nib','title'=>'Blog Publishing','desc'=>'Share articles, updates, achievements, and thought leadership content.'],
                ['icon'=>'fa-briefcase','title'=>'Portfolio Showcase','desc'=>'Display projects, certifications, testimonials, and media appearances.'],
                ['icon'=>'fa-tasks','title'=>'Task Management','desc'=>'Never miss important deadlines, meetings, follow-ups, or commitments.'],
                ['icon'=>'fa-chart-bar','title'=>'Analytics & Insights','desc'=>'Track profile views, engagement, lead sources, and business performance.'],
                ['icon'=>'fa-globe','title'=>'Custom Domain','desc'=>'Launch your professional website using your own domain and branding.'],
                ['icon'=>'fa-envelope','title'=>'Email Marketing','desc'=>'Newsletter subscriptions, automated emails, and subscriber management.'],
                ['icon'=>'fa-forum','title'=>'Community Forum','desc'=>'Build a community around your brand with discussion forums.'],
                ['icon'=>'fa-comments','title'=>'Live Chat Monitor','desc'=>'Monitor and reply to all visitor conversations from your admin panel.'],
                ['icon'=>'fa-dollar-sign','title'=>'Expense Tracking','desc'=>'Track business and personal expenses with approval workflows.'],
                ['icon'=>'fa-user-tie','title'=>'Job Board','desc'=>'Post jobs, receive applications, and manage your hiring pipeline.'],
            ];
            @endphp
            @foreach($allFeatures as $f)
            <div class="xn-card">
                <div class="xn-card-icon"><i class="fas {{ $f['icon'] }}"></i></div>
                <h3 style="font-size:0.95rem;font-weight:700;color:#fff;margin-bottom:0.5rem;">{{ $f['title'] }}</h3>
                <p style="font-size:0.825rem;color:#71717a;line-height:1.65;">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="xn-section" style="background:#000;text-align:center;">
    <div class="xn-container">
        <h2 class="xn-heading-lg" style="max-width:600px;margin:0 auto 1rem;">Ready to Build Your<br><span style="color:#a855f7;">Digital Identity?</span></h2>
        <p class="xn-body" style="max-width:480px;margin:0 auto 2.5rem;">Start your free trial today and experience the full power of Xenoraa.</p>
        <a href="{{ route('xenoraa.get-started') }}" class="xn-btn-primary-lg">Start Free Trial 🚀</a>
    </div>
</section>
@endsection
