@extends('layouts.xenoraa')
@section('title', 'Careers — Xenoraa')
@section('meta_description', 'Join the team at Go Esscay Solutions Private Limited. Explore career opportunities, our remote-first culture, and help us build the future of business SaaS.')
@section('styles')
<style>
.xn-page-hero { padding: 6rem 4rem 4rem; background: linear-gradient(180deg, #0a0a0a 0%, #000 100%); border-bottom: 1px solid #1a1a1a; }
.xn-benefits-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-top: 3rem; }
.xn-benefit-card { background: #0a0a0a; border: 1px solid #111; border-radius: 12px; padding: 2rem; transition: border-color 0.2s; }
.xn-benefit-card:hover { border-color: #7c3aed; }
.xn-benefit-icon { font-size: 1.75rem; color: #a855f7; margin-bottom: 1.25rem; }
.xn-benefit-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 0.75rem; }
.xn-benefit-desc { font-size: 0.875rem; color: #71717a; line-height: 1.6; }
.xn-jobs-section { padding: 5rem 0; }
.xn-jobs-list { display: flex; flex-direction: column; gap: 1.5rem; margin-top: 3rem; }
.xn-job-card { background: #050505; border: 1px solid #111; border-radius: 12px; padding: 2rem; display: flex; justify-content: space-between; align-items: center; transition: transform 0.2s, border-color 0.2s; }
.xn-job-card:hover { transform: translateY(-2px); border-color: #1a1a1a; }
.xn-job-info h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1.35rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; }
.xn-job-meta { display: flex; gap: 1.5rem; font-size: 0.85rem; color: #71717a; }
.xn-job-meta span { display: flex; align-items: center; gap: 0.5rem; }
.xn-job-meta i { color: #a855f7; }
@media(max-width:768px){
    .xn-page-hero { padding: 4rem 1.5rem 3rem; }
    .xn-benefits-grid { grid-template-columns: 1fr; gap: 1rem; }
    .xn-job-card { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
    .xn-job-meta { flex-wrap: wrap; gap: 0.75rem; }
}
</style>
@endsection
@section('content')
{{-- ===== HERO SECTION ===== --}}
<section class="xn-page-hero">
    <div class="xn-container">
        <div class="xn-feature-tag">
            <i class="fas fa-briefcase"></i> Careers
        </div>
        <h1 class="xn-heading-xl" style="max-width:800px;">Build the Future of<br><span style="color:#a855f7;">Business Software.</span></h1>
        <p class="xn-body-lg" style="max-width:600px;margin-top:1.25rem;">Join us at Go Esscay Solutions Private Limited. We are a fast-growing, remote-first team building Xenoraa — the unified business operating system.</p>
    </div>
</section>

{{-- ===== WHY JOIN US ===== --}}
<section class="xn-section" style="background:#000;">
    <div class="xn-container">
        <div style="text-align:center;max-width:600px;margin:0 auto;">
            <div class="xn-feature-tag" style="margin:0 auto 1rem;">Our Culture</div>
            <h2 class="xn-heading-lg">Why Work With Us?</h2>
            <p class="xn-body-lg" style="color:#71717a;margin-top:0.5rem;">We value autonomy, deep craft, and continuous learning. We design our work environment to help you do your best work.</p>
        </div>
        <div class="xn-benefits-grid">
            <div class="xn-benefit-card">
                <div class="xn-benefit-icon"><i class="fas fa-laptop-house"></i></div>
                <h3 class="xn-benefit-title">Remote-First &amp; Flexible</h3>
                <p class="xn-benefit-desc">Work from anywhere in India. We focus on outcomes, not hours. You manage your own schedule to fit your lifestyle and productivity peaks.</p>
            </div>
            <div class="xn-benefit-card">
                <div class="xn-benefit-icon"><i class="fas fa-rocket"></i></div>
                <h3 class="xn-benefit-title">High Ownership</h3>
                <p class="xn-benefit-desc">We don't micromanage. You own your projects from concept to production. You have the freedom to make key architectural and design decisions.</p>
            </div>
            <div class="xn-benefit-card">
                <div class="xn-benefit-icon"><i class="fas fa-chart-line"></i></div>
                <h3 class="xn-benefit-title">Growth &amp; Learning</h3>
                <p class="xn-benefit-desc">We support your professional development. We provide learning budgets for courses, books, and conferences, and encourage experimenting with new tech.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== OPEN POSITIONS ===== --}}
<section class="xn-jobs-section" style="background:#050505;border-top:1px solid #111;">
    <div class="xn-container">
        <div style="text-align:center;max-width:600px;margin:0 auto;">
            <div class="xn-feature-tag" style="margin:0 auto 1rem;">Open Roles</div>
            <h2 class="xn-heading-lg">Join the Mission</h2>
            <p class="xn-body-lg" style="color:#71717a;margin-top:0.5rem;">We are always looking for passionate engineers, designers, and thinkers to join our core product team.</p>
        </div>
        <div class="xn-jobs-list">
            {{-- Job 1 --}}
            <div class="xn-job-card">
                <div class="xn-job-info">
                    <h3>Senior Full-Stack Engineer (Laravel &amp; Vue/React)</h3>
                    <div class="xn-job-meta">
                        <span><i class="fas fa-map-marker-alt"></i> Remote (India)</span>
                        <span><i class="fas fa-clock"></i> Full-Time</span>
                        <span><i class="fas fa-briefcase"></i> 5+ Years Exp</span>
                    </div>
                </div>
                <div>
                    <a href="mailto:careers@esscay.in?subject=Application: Senior Full-Stack Engineer" class="xn-btn-primary" style="padding:0.6rem 1.5rem;font-size:0.85rem;">Apply Now</a>
                </div>
            </div>
            {{-- Job 2 --}}
            <div class="xn-job-card">
                <div class="xn-job-info">
                    <h3>UI/UX Product Designer</h3>
                    <div class="xn-job-meta">
                        <span><i class="fas fa-map-marker-alt"></i> Remote (India)</span>
                        <span><i class="fas fa-clock"></i> Full-Time</span>
                        <span><i class="fas fa-briefcase"></i> 3+ Years Exp</span>
                    </div>
                </div>
                <div>
                    <a href="mailto:careers@esscay.in?subject=Application: UI/UX Product Designer" class="xn-btn-primary" style="padding:0.6rem 1.5rem;font-size:0.85rem;">Apply Now</a>
                </div>
            </div>
            {{-- Job 3 --}}
            <div class="xn-job-card">
                <div class="xn-job-info">
                    <h3>Technical Support Engineer</h3>
                    <div class="xn-job-meta">
                        <span><i class="fas fa-map-marker-alt"></i> Remote (India)</span>
                        <span><i class="fas fa-clock"></i> Full-Time</span>
                        <span><i class="fas fa-briefcase"></i> 2+ Years Exp</span>
                    </div>
                </div>
                <div>
                    <a href="mailto:careers@esscay.in?subject=Application: Technical Support Engineer" class="xn-btn-primary" style="padding:0.6rem 1.5rem;font-size:0.85rem;">Apply Now</a>
                </div>
            </div>
        </div>
        <div style="text-align:center;margin-top:3rem;color:#71717a;font-size:0.9rem;">
            Don't see a role that fits? We always love meeting talented people. Drop us a line at <a href="mailto:careers@esscay.in" style="color:#a855f7;text-decoration:none;font-weight:600;">careers@esscay.in</a>.
        </div>
    </div>
</section>
@endsection
