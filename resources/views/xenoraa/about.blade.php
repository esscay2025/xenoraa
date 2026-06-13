@extends('layouts.xenoraa')
@section('title', 'About Us — Xenoraa')
@section('meta_description', 'Learn about Xenoraa, our parent company Go Esscay Solutions Private Limited, and our mission to simplify business operations and empower professional growth.')
@section('styles')
<style>
.xn-page-hero { padding: 6rem 4rem 4rem; background: linear-gradient(180deg, #0a0a0a 0%, #000 100%); border-bottom: 1px solid #1a1a1a; }
.xn-section-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 4rem; padding: 5rem 0; border-bottom: 1px solid #111; align-items: center; }
.xn-section-grid:last-child { border-bottom: none; }
.xn-section-grid.reverse { grid-template-columns: 1fr 1.2fr; }
.xn-section-title { font-family: 'Space Grotesk', sans-serif; font-size: clamp(1.75rem, 3vw, 2.5rem); font-weight: 700; color: #fff; line-height: 1.2; margin-bottom: 1.5rem; }
.xn-about-card { background: #0a0a0a; border: 1px solid #1a1a1a; border-radius: 16px; padding: 2.5rem; }
.xn-stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-top: 3rem; }
.xn-stat-card { background: #0a0a0a; border: 1px solid #111; border-radius: 12px; padding: 1.75rem; text-align: center; transition: border-color 0.2s; }
.xn-stat-card:hover { border-color: #7c3aed; }
.xn-stat-num { font-family: 'Space Grotesk', sans-serif; font-size: 2.5rem; font-weight: 800; color: #a855f7; margin-bottom: 0.5rem; }
.xn-stat-label { font-size: 0.875rem; color: #71717a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.xn-value-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-top: 3rem; }
.xn-value-card { background: #050505; border: 1px solid #111; border-radius: 12px; padding: 2rem; transition: transform 0.2s, border-color 0.2s; }
.xn-value-card:hover { transform: translateY(-4px); border-color: #1a1a1a; }
.xn-value-icon { font-size: 1.75rem; color: #a855f7; margin-bottom: 1.25rem; }
.xn-value-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 0.75rem; }
.xn-value-desc { font-size: 0.875rem; color: #71717a; line-height: 1.6; }
@media(max-width:768px){
    .xn-page-hero { padding: 4rem 1.5rem 3rem; }
    .xn-section-grid { grid-template-columns: 1fr !important; gap: 2rem; padding: 3rem 0; }
    .xn-stat-grid { grid-template-columns: 1fr; gap: 1rem; }
    .xn-value-grid { grid-template-columns: 1fr; gap: 1rem; }
}
</style>
@endsection
@section('content')
{{-- ===== HERO SECTION ===== --}}
<section class="xn-page-hero">
    <div class="xn-container">
        <div class="xn-feature-tag">
            <i class="fas fa-info-circle"></i> Our Story
        </div>
        <h1 class="xn-heading-xl" style="max-width:800px;">Simplifying Operations.<br><span style="color:#a855f7;">Empowering Growth.</span></h1>
        <p class="xn-body-lg" style="max-width:600px;margin-top:1.25rem;">Xenoraa is a product of Go Esscay Solutions Private Limited, built with a vision to simplify how professionals and businesses manage their digital ecosystem.</p>
    </div>
</section>

{{-- ===== THE VISION ===== --}}
<section class="xn-section" style="background:#000;">
    <div class="xn-container">
        <div class="xn-section-grid">
            <div>
                <h2 class="xn-section-title">The Genesis of <span style="color:#a855f7;">Xenoraa</span></h2>
                <p class="xn-body-lg" style="color:#a1a1aa;line-height:1.7;margin-bottom:1.5rem;">Founded as a service-based organization specializing in web development, UI/UX design, branding, and digital transformation, <strong>Go Esscay Solutions Private Limited</strong> worked closely with hundreds of businesses across various industries.</p>
                <p class="xn-body-lg" style="color:#a1a1aa;line-height:1.7;margin-bottom:1.5rem;">Through this journey, we recognized a massive, growing challenge: professionals and growing businesses were forced to juggle multiple disconnected tools — paying separately for web hosting, CRM software, e-commerce systems, and POS terminals.</p>
                <p class="xn-body-lg" style="color:#a1a1aa;line-height:1.7;">This fragmented workflow wasted valuable time, created data silos, and escalated operational costs. We believed there had to be a better way. This vision led to the creation of <strong>Xenoraa</strong> — our flagship all-in-one SaaS platform.</p>
            </div>
            <div>
                <div class="xn-about-card">
                    <div style="font-size:2.5rem;color:#a855f7;margin-bottom:1.5rem;"><i class="fas fa-quote-left"></i></div>
                    <blockquote style="font-family:'Space Grotesk',sans-serif;font-size:1.25rem;font-weight:600;color:#fff;line-height:1.5;margin-bottom:1.5rem;">
                        At Go Esscay Solutions, we believe technology should not be complicated — it should empower people. Our mission is to build products that solve real-world challenges, improve productivity, and create new opportunities for growth through innovation, automation, and artificial intelligence.
                    </blockquote>
                    <div style="font-weight:700;color:#fff;font-size:0.9rem;">Go Esscay Solutions Private Limited</div>
                    <div style="font-size:0.8rem;color:#71717a;margin-top:0.25rem;">Innovating Today. Empowering Tomorrow.</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== STATS ===== --}}
<section class="xn-section" style="background:#050505;border-top:1px solid #111;border-bottom:1px solid #111;">
    <div class="xn-container">
        <div style="text-align:center;max-width:600px;margin:0 auto;">
            <div class="xn-feature-tag" style="margin:0 auto 1rem;">Xenoraa Today</div>
            <h2 class="xn-heading-lg">Our Impact in Numbers</h2>
            <p class="xn-body-lg" style="color:#71717a;margin-top:0.5rem;">We are proud to power businesses and professionals across India and globally.</p>
        </div>
        <div class="xn-stat-grid">
            <div class="xn-stat-card">
                <div class="xn-stat-num">4+</div>
                <div class="xn-stat-label">Powerful Apps</div>
            </div>
            <div class="xn-stat-card">
                <div class="xn-stat-num">10k+</div>
                <div class="xn-stat-label">Leads Processed</div>
            </div>
            <div class="xn-stat-card">
                <div class="xn-stat-num">99.9%</div>
                <div class="xn-stat-label">Platform Uptime</div>
            </div>
        </div>
    </div>
</section>

{{-- ===== VALUES ===== --}}
<section class="xn-section" style="background:#000;">
    <div class="xn-container">
        <div style="text-align:center;max-width:600px;margin:0 auto;">
            <div class="xn-feature-tag" style="margin:0 auto 1rem;">Our Values</div>
            <h2 class="xn-heading-lg">What Drives Us</h2>
            <p class="xn-body-lg" style="color:#71717a;margin-top:0.5rem;">These core principles guide our product decisions, engineering standards, and customer relationships.</p>
        </div>
        <div class="xn-value-grid">
            <div class="xn-value-card">
                <div class="xn-value-icon"><i class="fas fa-heart"></i></div>
                <h3 class="xn-value-title">User-Centric Design</h3>
                <p class="xn-value-desc">We build products with our users in mind. Every interface is crafted to be intuitive, clean, and fast, reducing the learning curve to minutes.</p>
            </div>
            <div class="xn-value-card">
                <div class="xn-value-icon"><i class="fas fa-shield-alt"></i></div>
                <h3 class="xn-value-title">Reliability &amp; Security</h3>
                <p class="xn-value-desc">Your business runs on our platform. We invest heavily in robust infrastructure, automatic SSL, and secure database practices to keep your data safe.</p>
            </div>
            <div class="xn-value-card">
                <div class="xn-value-icon"><i class="fas fa-bolt"></i></div>
                <h3 class="xn-value-title">Continuous Innovation</h3>
                <p class="xn-value-desc">We are constantly improving. From integrating advanced AI content helpers to double-entry accounting modules, we bring enterprise-grade tech to growing businesses.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="xn-section xn-cta-section" style="border-top:1px solid #111;">
    <div class="xn-container" style="text-align:center;">
        <h2 class="xn-heading-lg" style="max-width:700px;margin:0 auto 1rem;">Ready to Simplify Your<br><span style="color:#a855f7;">Business Operations?</span></h2>
        <p class="xn-body-lg" style="max-width:560px;margin:0 auto 2.5rem;">Join thousands of professionals who have ditched multiple subscriptions for the unified power of Xenoraa.</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('xenoraa.get-started') }}" class="xn-btn-primary-lg">Get Started Today 🚀</a>
            <a href="{{ route('xenoraa.pricing') }}" class="xn-btn-outline-lg">View Plans</a>
        </div>
    </div>
</section>
@endsection
