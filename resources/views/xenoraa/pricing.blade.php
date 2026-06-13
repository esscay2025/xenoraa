@extends('layouts.xenoraa')
@section('title', 'Pricing — Xenoraa | Choose Your App Bundle')
@section('meta_description', 'Xenoraa offers three flexible subscription tiers — Solo App, Duo Bundle, and All-Access. Choose the apps you need: Website, E-Commerce, POS, or CRM. Start free today.')
@section('styles')
<style>
/* ===== PRICING PAGE ===== */
.xn-pricing-hero {
    background: #000;
    padding: 6rem 0 4rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.xn-pricing-hero::before {
    content: '';
    position: absolute;
    top: -200px; left: 50%; transform: translateX(-50%);
    width: 800px; height: 800px;
    background: radial-gradient(circle, rgba(124,58,237,0.12) 0%, transparent 70%);
    pointer-events: none;
}
/* ===== BILLING TOGGLE ===== */
.xn-billing-toggle {
    display: inline-flex; align-items: center; gap: 0.875rem;
    background: #0a0a0a; border: 1px solid #1f1f1f;
    border-radius: 100px; padding: 0.5rem 1.25rem;
    margin-bottom: 3.5rem;
}
.xn-billing-toggle label { font-size: 0.875rem; color: #71717a; cursor: pointer; font-weight: 500; }
.xn-billing-toggle label.active { color: #fff; }
.xn-toggle-switch { position: relative; width: 44px; height: 24px; }
.xn-toggle-switch input { opacity: 0; width: 0; height: 0; }
.xn-toggle-slider {
    position: absolute; inset: 0; cursor: pointer;
    background: #1f1f1f; border-radius: 100px;
    transition: 0.3s;
}
.xn-toggle-slider:before {
    content: ''; position: absolute;
    width: 18px; height: 18px; border-radius: 50%;
    background: #71717a; left: 3px; top: 3px;
    transition: 0.3s;
}
.xn-toggle-switch input:checked + .xn-toggle-slider { background: rgba(124,58,237,0.3); }
.xn-toggle-switch input:checked + .xn-toggle-slider:before { transform: translateX(20px); background: #a855f7; }
.xn-save-badge {
    background: rgba(168,85,247,0.15); color: #a855f7;
    font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em;
    text-transform: uppercase; padding: 0.2rem 0.6rem;
    border-radius: 100px; border: 1px solid rgba(168,85,247,0.3);
}
/* ===== APP ICONS ROW ===== */
.xn-apps-row {
    display: flex; justify-content: center; gap: 1.5rem;
    flex-wrap: wrap; margin-bottom: 3rem;
}
.xn-app-pill {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.5rem 1.1rem;
    border: 1px solid #1f1f1f; border-radius: 100px;
    font-size: 0.8rem; font-weight: 600; color: #a1a1aa;
    background: #0a0a0a;
}
.xn-app-pill i { color: #a855f7; }
/* ===== PLAN CARDS ===== */
.xn-plans-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    max-width: 1100px; margin: 0 auto;
    align-items: start;
}
.xn-plan-card {
    background: #0a0a0a;
    border: 1px solid #1a1a1a;
    border-radius: 20px;
    padding: 2.25rem 2rem;
    position: relative;
    transition: border-color 0.3s, transform 0.3s;
}
.xn-plan-card:hover {
    border-color: #3f3f46;
    transform: translateY(-4px);
}
.xn-plan-card.popular {
    border-color: rgba(124,58,237,0.5);
    background: linear-gradient(180deg, rgba(124,58,237,0.06) 0%, #0a0a0a 40%);
}
.xn-plan-card.popular:hover { border-color: rgba(168,85,247,0.7); }
.xn-popular-tag {
    position: absolute; top: -14px; left: 50%; transform: translateX(-50%);
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    color: #fff; font-size: 0.7rem; font-weight: 700;
    letter-spacing: 0.08em; text-transform: uppercase;
    padding: 0.3rem 1rem; border-radius: 100px;
    white-space: nowrap;
}
.xn-plan-tier {
    font-size: 0.65rem; font-weight: 700; letter-spacing: 0.15em;
    text-transform: uppercase; color: #a855f7; margin-bottom: 0.5rem;
}
.xn-plan-name-lg {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.6rem; font-weight: 800; color: #fff;
    margin-bottom: 0.25rem;
}
.xn-plan-tagline {
    font-size: 0.8rem; color: #52525b; margin-bottom: 1.5rem;
    line-height: 1.5;
}
.xn-plan-price-block { margin-bottom: 1.5rem; }
.xn-plan-price-lg {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 3rem; font-weight: 900; color: #fff;
    line-height: 1; display: flex; align-items: flex-start; gap: 0.25rem;
}
.xn-plan-price-lg sup { font-size: 1.25rem; margin-top: 0.5rem; color: #a1a1aa; font-weight: 600; }
.xn-plan-price-lg sub { font-size: 0.9rem; color: #52525b; align-self: flex-end; margin-bottom: 0.25rem; }
.xn-plan-billing-note { font-size: 0.75rem; color: #52525b; margin-top: 0.4rem; }
.xn-plan-billing-note .yearly-note { display: none; }
/* ===== APP BUNDLES ===== */
.xn-bundle-label {
    font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em;
    text-transform: uppercase; color: #3f3f46; margin-bottom: 0.75rem;
}
.xn-bundle-apps {
    display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.75rem;
}
.xn-bundle-app {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.35rem 0.75rem;
    border-radius: 8px; font-size: 0.78rem; font-weight: 600;
    border: 1px solid;
}
.xn-bundle-app.website  { background: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.3); color: #60a5fa; }
.xn-bundle-app.ecommerce{ background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.3); color: #34d399; }
.xn-bundle-app.pos      { background: rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.3); color: #fbbf24; }
.xn-bundle-app.crm      { background: rgba(168,85,247,0.1); border-color: rgba(168,85,247,0.3); color: #c084fc; }
.xn-bundle-app.all      { background: rgba(124,58,237,0.15); border-color: rgba(124,58,237,0.4); color: #a855f7; }
.xn-bundle-or {
    font-size: 0.7rem; color: #3f3f46; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.1em;
    display: flex; align-items: center; gap: 0.5rem;
    margin: 0.5rem 0;
}
.xn-bundle-or::before, .xn-bundle-or::after {
    content: ''; flex: 1; height: 1px; background: #1a1a1a;
}
/* ===== FEATURES LIST ===== */
.xn-plan-features-list {
    list-style: none; padding: 0; margin: 0 0 2rem;
    display: flex; flex-direction: column; gap: 0.6rem;
}
.xn-plan-features-list li {
    display: flex; align-items: flex-start; gap: 0.6rem;
    font-size: 0.83rem; color: #a1a1aa; line-height: 1.5;
}
.xn-plan-features-list li i.yes { color: #a855f7; font-size: 0.75rem; margin-top: 0.2rem; flex-shrink: 0; }
.xn-plan-features-list li i.no  { color: #27272a; font-size: 0.75rem; margin-top: 0.2rem; flex-shrink: 0; }
.xn-plan-features-list li.muted { color: #3f3f46; }
.xn-plan-features-list li .feat-badge {
    font-size: 0.6rem; font-weight: 700; letter-spacing: 0.06em;
    text-transform: uppercase; padding: 0.1rem 0.4rem;
    border-radius: 4px; margin-left: 0.25rem;
    background: rgba(168,85,247,0.15); color: #a855f7;
    border: 1px solid rgba(168,85,247,0.25);
    white-space: nowrap;
}
/* ===== COMPARISON TABLE ===== */
.xn-compare-table {
    width: 100%; border-collapse: collapse;
    font-size: 0.85rem;
}
.xn-compare-table th {
    padding: 1rem 1.25rem; text-align: left;
    font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em;
    text-transform: uppercase; color: #52525b;
    border-bottom: 1px solid #1a1a1a;
}
.xn-compare-table th:first-child { color: #71717a; }
.xn-compare-table th.highlight { color: #a855f7; }
.xn-compare-table td {
    padding: 0.875rem 1.25rem;
    border-bottom: 1px solid #0f0f0f;
    color: #a1a1aa; vertical-align: middle;
}
.xn-compare-table td:not(:first-child) { text-align: center; }
.xn-compare-table tr:hover td { background: rgba(255,255,255,0.01); }
.xn-compare-table .section-row td {
    font-size: 0.7rem; font-weight: 700; letter-spacing: 0.12em;
    text-transform: uppercase; color: #3f3f46;
    padding-top: 1.5rem; padding-bottom: 0.5rem;
    border-bottom: none; background: transparent;
}
.xn-check-yes { color: #a855f7; }
.xn-check-no  { color: #27272a; }
/* ===== FAQ ===== */
.xn-faq-item {
    border-bottom: 1px solid #1a1a1a;
    padding: 1.5rem 0;
}
.xn-faq-q { font-weight: 600; color: #fff; margin-bottom: 0.5rem; font-size: 0.95rem; }
.xn-faq-a { font-size: 0.875rem; color: #71717a; line-height: 1.7; }
/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .xn-plans-grid { grid-template-columns: 1fr; max-width: 480px; }
    .xn-plan-card.popular { order: -1; }
}
</style>
@endsection
@section('content')
{{-- ===== HERO ===== --}}
<section class="xn-pricing-hero">
    <div class="xn-container">
        <div class="xn-label">Transparent Pricing</div>
        <h1 class="xn-heading-xl" style="max-width:700px;margin:0 auto 1rem;">
            Pick Your <span style="color:#a855f7;">App Bundle.</span><br>Pay Only for What You Use.
        </h1>
        <p class="xn-body-lg" style="max-width:560px;margin:0 auto 2.5rem;color:#71717a;">
            Xenoraa is built around four core apps — <strong style="color:#a1a1aa;">Website, E-Commerce, POS,</strong> and <strong style="color:#a1a1aa;">CRM.</strong>
            Choose one, combine two, or unlock all four. Every plan includes hosting, AI tools, and analytics.
        </p>
        {{-- App Pills --}}
        <div class="xn-apps-row">
            <div class="xn-app-pill"><i class="fas fa-globe"></i> Website &amp; Site Builder</div>
            <div class="xn-app-pill"><i class="fas fa-shopping-bag"></i> E-Commerce Store</div>
            <div class="xn-app-pill"><i class="fas fa-cash-register"></i> Point of Sale (POS)</div>
            <div class="xn-app-pill"><i class="fas fa-users"></i> CRM &amp; Sales</div>
        </div>
        {{-- Billing Toggle --}}
        <div class="xn-billing-toggle">
            <label for="billingToggle" id="monthlyLabel" class="active">Monthly</label>
            <div class="xn-toggle-switch">
                <input type="checkbox" id="billingToggle" onchange="toggleBilling()">
                <span class="xn-toggle-slider"></span>
            </div>
            <label for="billingToggle" id="yearlyLabel">Yearly</label>
            <span class="xn-save-badge">Save 17%</span>
        </div>
    </div>
</section>

{{-- ===== PLAN CARDS ===== --}}
<section class="xn-section" style="background:#000;padding-top:0;">
    <div class="xn-container">
        <div class="xn-plans-grid">

            {{-- ── PLAN 1: SOLO APP ── --}}
            <div class="xn-plan-card">
                <div class="xn-plan-tier">Tier 1</div>
                <div class="xn-plan-name-lg">Solo App</div>
                <div class="xn-plan-tagline">One powerful app. Full features. Perfect for focused professionals.</div>
                <div class="xn-plan-price-block">
                    <div class="xn-plan-price-lg">
                        <sup>₹</sup>
                        <span class="price-monthly">499</span>
                        <span class="price-yearly" style="display:none;">416</span>
                        <sub>/mo</sub>
                    </div>
                    <div class="xn-plan-billing-note">
                        <span class="monthly-note">Billed monthly</span>
                        <span class="yearly-note" style="display:none;">₹4,999/year — save ₹989</span>
                    </div>
                </div>

                <div class="xn-bundle-label">Choose any ONE app</div>
                <div class="xn-bundle-apps">
                    <span class="xn-bundle-app website"><i class="fas fa-globe"></i> Website</span>
                    <div class="xn-bundle-or">or</div>
                    <span class="xn-bundle-app ecommerce"><i class="fas fa-shopping-bag"></i> E-Commerce</span>
                    <div class="xn-bundle-or">or</div>
                    <span class="xn-bundle-app pos"><i class="fas fa-cash-register"></i> POS</span>
                    <div class="xn-bundle-or">or</div>
                    <span class="xn-bundle-app crm"><i class="fas fa-users"></i> CRM</span>
                </div>

                <ul class="xn-plan-features-list">
                    <li><i class="fas fa-check-circle yes"></i> Full access to your chosen app</li>
                    <li><i class="fas fa-check-circle yes"></i> Custom domain mapping</li>
                    <li><i class="fas fa-check-circle yes"></i> AI Content Assistant</li>
                    <li><i class="fas fa-check-circle yes"></i> Analytics &amp; Insights</li>
                    <li><i class="fas fa-check-circle yes"></i> Secure hosting &amp; SSL</li>
                    <li><i class="fas fa-check-circle yes"></i> Mobile-responsive design</li>
                    <li><i class="fas fa-check-circle yes"></i> Email support</li>
                    <li class="muted"><i class="fas fa-times-circle no"></i> Multi-app integration</li>
                    <li class="muted"><i class="fas fa-times-circle no"></i> AI Chat Widget</li>
                    <li class="muted"><i class="fas fa-times-circle no"></i> Team members</li>
                </ul>
                <a href="{{ route('xenoraa.get-started') }}?plan=solo&billing=monthly" class="xn-btn-ghost" style="width:100%;text-align:center;display:block;padding:0.875rem;font-size:0.9rem;font-family:'Inter',sans-serif;text-decoration:none;">Get Started</a>
            </div>

            {{-- ── PLAN 2: DUO BUNDLE ── --}}
            <div class="xn-plan-card popular">
                <div class="xn-popular-tag">⭐ Most Popular</div>
                <div class="xn-plan-tier">Tier 2</div>
                <div class="xn-plan-name-lg">Duo Bundle</div>
                <div class="xn-plan-tagline">Two apps, deeply integrated. Built for growing businesses.</div>
                <div class="xn-plan-price-block">
                    <div class="xn-plan-price-lg">
                        <sup>₹</sup>
                        <span class="price-monthly">999</span>
                        <span class="price-yearly" style="display:none;">833</span>
                        <sub>/mo</sub>
                    </div>
                    <div class="xn-plan-billing-note">
                        <span class="monthly-note">Billed monthly</span>
                        <span class="yearly-note" style="display:none;">₹9,999/year — save ₹1,989</span>
                    </div>
                </div>

                <div class="xn-bundle-label">Choose any TWO-app combination</div>
                <div class="xn-bundle-apps">
                    <span class="xn-bundle-app website"><i class="fas fa-globe"></i> Website</span>
                    <span class="xn-bundle-app ecommerce"><i class="fas fa-shopping-bag"></i> E-Commerce</span>
                </div>
                <div class="xn-bundle-or">or</div>
                <div class="xn-bundle-apps">
                    <span class="xn-bundle-app ecommerce"><i class="fas fa-shopping-bag"></i> E-Commerce</span>
                    <span class="xn-bundle-app pos"><i class="fas fa-cash-register"></i> POS</span>
                </div>
                <div class="xn-bundle-or">or</div>
                <div class="xn-bundle-apps">
                    <span class="xn-bundle-app website"><i class="fas fa-globe"></i> Website</span>
                    <span class="xn-bundle-app crm"><i class="fas fa-users"></i> CRM</span>
                </div>

                <ul class="xn-plan-features-list">
                    <li><i class="fas fa-check-circle yes"></i> Full access to both chosen apps</li>
                    <li><i class="fas fa-check-circle yes"></i> Cross-app data integration</li>
                    <li><i class="fas fa-check-circle yes"></i> Custom domain mapping</li>
                    <li><i class="fas fa-check-circle yes"></i> AI Content Assistant</li>
                    <li><i class="fas fa-check-circle yes"></i> AI Chat Widget <span class="feat-badge">New</span></li>
                    <li><i class="fas fa-check-circle yes"></i> Advanced Analytics</li>
                    <li><i class="fas fa-check-circle yes"></i> Newsletter &amp; Subscribers</li>
                    <li><i class="fas fa-check-circle yes"></i> Accounts &amp; Finance module</li>
                    <li><i class="fas fa-check-circle yes"></i> Priority support</li>
                    <li class="muted"><i class="fas fa-times-circle no"></i> Team members</li>
                    <li class="muted"><i class="fas fa-times-circle no"></i> White label branding</li>
                </ul>
                <a href="{{ route('xenoraa.get-started') }}?plan=duo&billing=monthly" class="xn-btn-primary" style="width:100%;text-align:center;display:block;padding:0.875rem;font-size:0.9rem;font-family:'Inter',sans-serif;text-decoration:none;">Get Started</a>
            </div>

            {{-- ── PLAN 3: ALL-ACCESS ── --}}
            <div class="xn-plan-card">
                <div class="xn-plan-tier">Tier 3</div>
                <div class="xn-plan-name-lg">All-Access</div>
                <div class="xn-plan-tagline">Every app, every feature, every module. The complete Xenoraa ecosystem.</div>
                <div class="xn-plan-price-block">
                    <div class="xn-plan-price-lg">
                        <sup>₹</sup>
                        <span class="price-monthly">1,999</span>
                        <span class="price-yearly" style="display:none;">1,666</span>
                        <sub>/mo</sub>
                    </div>
                    <div class="xn-plan-billing-note">
                        <span class="monthly-note">Billed monthly</span>
                        <span class="yearly-note" style="display:none;">₹19,999/year — save ₹3,989</span>
                    </div>
                </div>

                <div class="xn-bundle-label">All 4 apps unlocked</div>
                <div class="xn-bundle-apps">
                    <span class="xn-bundle-app all"><i class="fas fa-bolt"></i> All Apps Included</span>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:0.4rem;margin-bottom:1.75rem;">
                    <span class="xn-bundle-app website"><i class="fas fa-globe"></i> Website</span>
                    <span class="xn-bundle-app ecommerce"><i class="fas fa-shopping-bag"></i> E-Commerce</span>
                    <span class="xn-bundle-app pos"><i class="fas fa-cash-register"></i> POS</span>
                    <span class="xn-bundle-app crm"><i class="fas fa-users"></i> CRM</span>
                </div>

                <ul class="xn-plan-features-list">
                    <li><i class="fas fa-check-circle yes"></i> Everything in Duo Bundle</li>
                    <li><i class="fas fa-check-circle yes"></i> CRM — Leads, Deals, Contacts, Accounts</li>
                    <li><i class="fas fa-check-circle yes"></i> Inventory — Quotes, Orders, Invoices</li>
                    <li><i class="fas fa-check-circle yes"></i> Projects &amp; Task Management</li>
                    <li><i class="fas fa-check-circle yes"></i> Services &amp; Booking Management</li>
                    <li><i class="fas fa-check-circle yes"></i> AI Hub &amp; Automation Workflows</li>
                    <li><i class="fas fa-check-circle yes"></i> Team Members (up to 5)</li>
                    <li><i class="fas fa-check-circle yes"></i> White Label Branding</li>
                    <li><i class="fas fa-check-circle yes"></i> API Access</li>
                    <li><i class="fas fa-check-circle yes"></i> Dedicated support &amp; SLA</li>
                    <li><i class="fas fa-check-circle yes"></i> Future modules included</li>
                </ul>
                <a href="{{ route('xenoraa.get-started') }}?plan=allaccess&billing=monthly" class="xn-btn-ghost" style="width:100%;text-align:center;display:block;padding:0.875rem;font-size:0.9rem;font-family:'Inter',sans-serif;text-decoration:none;">Get Started</a>
            </div>

        </div>

        <p style="text-align:center;margin-top:2rem;font-size:0.8rem;color:#3f3f46;">
            30-day money-back guarantee &nbsp;·&nbsp; No setup fees &nbsp;·&nbsp; Cancel anytime
        </p>
    </div>
</section>

{{-- ===== WHAT'S INSIDE EACH APP ===== --}}
<section class="xn-section" style="background:#050505;">
    <div class="xn-container">
        <div style="text-align:center;margin-bottom:3.5rem;">
            <div class="xn-label">What's Inside Each App</div>
            <h2 class="xn-heading-lg">Four Apps. <span style="color:#a855f7;">One Platform.</span></h2>
            <p class="xn-body" style="max-width:520px;margin:1rem auto 0;color:#71717a;">Every app is a complete, production-ready module with its own dashboard, workflows, and data — all connected under your Xenoraa account.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;">

            {{-- Website --}}
            <div class="xn-card" style="padding:2rem;">
                <div style="display:flex;align-items:center;gap:0.875rem;margin-bottom:1.25rem;">
                    <div style="width:44px;height:44px;background:rgba(59,130,246,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#60a5fa;font-size:1.2rem;flex-shrink:0;">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div>
                        <div style="font-size:0.65rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#3f3f46;">App 1</div>
                        <div style="font-family:'Space Grotesk',sans-serif;font-size:1.2rem;font-weight:700;color:#fff;">Website &amp; Site Builder</div>
                    </div>
                </div>
                <p style="font-size:0.85rem;color:#71717a;line-height:1.65;margin-bottom:1.25rem;">Build a stunning professional website with a drag-and-drop page builder, blog, portfolio, team pages, and SEO tools — all without writing a single line of code.</p>
                <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                    @foreach(['Page Builder','Blog & Articles','Portfolio Showcase','Team Members','SEO Tools','Custom Domain','Contact Forms','Job Board','Newsletter','Forum'] as $f)
                    <span style="font-size:0.72rem;padding:0.2rem 0.6rem;border:1px solid #1f1f1f;border-radius:6px;color:#71717a;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- E-Commerce --}}
            <div class="xn-card" style="padding:2rem;">
                <div style="display:flex;align-items:center;gap:0.875rem;margin-bottom:1.25rem;">
                    <div style="width:44px;height:44px;background:rgba(16,185,129,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#34d399;font-size:1.2rem;flex-shrink:0;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div>
                        <div style="font-size:0.65rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#3f3f46;">App 2</div>
                        <div style="font-family:'Space Grotesk',sans-serif;font-size:1.2rem;font-weight:700;color:#fff;">E-Commerce Store</div>
                    </div>
                </div>
                <p style="font-size:0.85rem;color:#71717a;line-height:1.65;margin-bottom:1.25rem;">Launch a full-featured online store. Manage products, categories, inventory, orders, and customer reviews — with CSV import/export and analytics built in.</p>
                <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                    @foreach(['Product Management','Categories','Order Management','Inventory Tracking','Customer Reviews','CSV Import/Export','Product Variants','Store Analytics','Discount Codes','Digital Products'] as $f)
                    <span style="font-size:0.72rem;padding:0.2rem 0.6rem;border:1px solid #1f1f1f;border-radius:6px;color:#71717a;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- POS --}}
            <div class="xn-card" style="padding:2rem;">
                <div style="display:flex;align-items:center;gap:0.875rem;margin-bottom:1.25rem;">
                    <div style="width:44px;height:44px;background:rgba(245,158,11,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fbbf24;font-size:1.2rem;flex-shrink:0;">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <div>
                        <div style="font-size:0.65rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#3f3f46;">App 3</div>
                        <div style="font-family:'Space Grotesk',sans-serif;font-size:1.2rem;font-weight:700;color:#fff;">Point of Sale (POS)</div>
                    </div>
                </div>
                <p style="font-size:0.85rem;color:#71717a;line-height:1.65;margin-bottom:1.25rem;">Run your retail or service counter from any device. Accept payments, manage sessions, print receipts, track daily sales, and view real-time reports.</p>
                <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                    @foreach(['POS Terminal','Session Management','Receipt Printing','Daily Sales Reports','Cash & Card Payments','Product Search','Barcode Support','Shift Reports','Customer Lookup','Refunds'] as $f)
                    <span style="font-size:0.72rem;padding:0.2rem 0.6rem;border:1px solid #1f1f1f;border-radius:6px;color:#71717a;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- CRM --}}
            <div class="xn-card" style="padding:2rem;">
                <div style="display:flex;align-items:center;gap:0.875rem;margin-bottom:1.25rem;">
                    <div style="width:44px;height:44px;background:rgba(168,85,247,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#c084fc;font-size:1.2rem;flex-shrink:0;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div style="font-size:0.65rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#3f3f46;">App 4</div>
                        <div style="font-family:'Space Grotesk',sans-serif;font-size:1.2rem;font-weight:700;color:#fff;">CRM &amp; Sales</div>
                    </div>
                </div>
                <p style="font-size:0.85rem;color:#71717a;line-height:1.65;margin-bottom:1.25rem;">Manage your entire sales pipeline — from leads and contacts to deals, forecasts, and invoices. Includes projects, services, support cases, and a full inventory module.</p>
                <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                    @foreach(['Leads & Contacts','Deals & Pipeline','Accounts','Forecasting','Inventory & Invoices','Quotes & Orders','Projects & Tasks','Services & Booking','Support Cases','Activities'] as $f)
                    <span style="font-size:0.72rem;padding:0.2rem 0.6rem;border:1px solid #1f1f1f;border-radius:6px;color:#71717a;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===== COMPARISON TABLE ===== --}}
<section class="xn-section" style="background:#000;">
    <div class="xn-container">
        <div style="text-align:center;margin-bottom:3rem;">
            <div class="xn-label">Full Comparison</div>
            <h2 class="xn-heading-lg">Everything <span style="color:#a855f7;">Side by Side</span></h2>
        </div>
        <div style="overflow-x:auto;border:1px solid #1a1a1a;border-radius:16px;">
            <table class="xn-compare-table">
                <thead>
                    <tr>
                        <th style="width:40%;">Feature</th>
                        <th>Solo App<br><span style="color:#52525b;font-size:0.7rem;font-weight:400;">₹499/mo</span></th>
                        <th class="highlight">Duo Bundle<br><span style="color:#71717a;font-size:0.7rem;font-weight:400;">₹999/mo</span></th>
                        <th>All-Access<br><span style="color:#52525b;font-size:0.7rem;font-weight:400;">₹1,999/mo</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="section-row"><td colspan="4">Apps &amp; Modules</td></tr>
                    <tr><td>Apps Included</td><td>1 of your choice</td><td>2 of your choice</td><td>All 4 apps</td></tr>
                    <tr><td>Website &amp; Site Builder</td><td>If selected</td><td>If selected</td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>E-Commerce Store</td><td>If selected</td><td>If selected</td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Point of Sale (POS)</td><td>If selected</td><td>If selected</td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>CRM &amp; Sales</td><td>If selected</td><td>If selected</td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Accounts &amp; Finance</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Projects &amp; Tasks</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Services &amp; Booking</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Inventory &amp; Invoicing</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr class="section-row"><td colspan="4">Platform Features</td></tr>
                    <tr><td>Custom Domain Mapping</td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Secure Hosting &amp; SSL</td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Mobile Responsive</td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Analytics &amp; Insights</td><td>Basic</td><td>Advanced</td><td>Advanced</td></tr>
                    <tr><td>Newsletter &amp; Subscribers</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Team Members</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td>Up to 5</td></tr>
                    <tr><td>White Label Branding</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>API Access</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr class="section-row"><td colspan="4">AI &amp; Automation</td></tr>
                    <tr><td>AI Content Assistant</td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>AI Chat Widget</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>AI Hub &amp; Conversations</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Automation Workflows</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr class="section-row"><td colspan="4">Support</td></tr>
                    <tr><td>Email Support</td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Priority Support</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Dedicated Support &amp; SLA</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Onboarding Assistance</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="xn-section" style="background:#050505;">
    <div class="xn-container">
        <div style="max-width:700px;margin:0 auto;">
            <div style="text-align:center;margin-bottom:3rem;">
                <div class="xn-label">FAQ</div>
                <h2 class="xn-heading-md">Frequently Asked <span style="color:#a855f7;">Questions</span></h2>
            </div>
            @php
            $faqs = [
                ['q'=>'What exactly is an "app" in Xenoraa?',
                 'a'=>'An app is a complete, self-contained module — Website, E-Commerce, POS, or CRM. Each app has its own dashboard, features, and workflows. On the Duo Bundle you get two apps working together with shared data. On All-Access you get all four.'],
                ['q'=>'Can I switch which apps I use after subscribing?',
                 'a'=>'Yes. On the Solo App plan you can switch your selected app at any time. On the Duo Bundle you can change your two-app combination. Upgrades take effect immediately; downgrades take effect at the next billing cycle.'],
                ['q'=>'How does the Website + CRM combination work?',
                 'a'=>'Your website captures leads via contact forms, enquiry buttons, and booking widgets. Those leads flow directly into your CRM pipeline — no manual data entry. You can track every visitor-to-customer journey from one dashboard.'],
                ['q'=>'Can I use my own domain?',
                 'a'=>'Yes, custom domain mapping is available on all three plans. Point your domain (e.g., vignesh.solutions) to your Xenoraa account and we handle the SSL certificate automatically.'],
                ['q'=>'How does the sign-up process work?',
                 'a'=>'Click "Get Started" on any plan, fill in your business details, choose your app(s), and complete payment. Your workspace is created instantly. The AI onboarding wizard helps you set up your first app in under 10 minutes.'],
                ['q'=>'What happens to my data if I cancel?',
                 'a'=>'Your data is retained for 30 days after cancellation. You can export all your data — products, contacts, orders, leads, invoices — at any time from your admin panel.'],
                ['q'=>'Do you offer refunds?',
                 'a'=>'Yes, we offer a 30-day money-back guarantee on all plans. No questions asked.'],
            ];
            @endphp
            @foreach($faqs as $faq)
            <div class="xn-faq-item">
                <div class="xn-faq-q">{{ $faq['q'] }}</div>
                <div class="xn-faq-a">{{ $faq['a'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="xn-section" style="background:#000;text-align:center;">
    <div class="xn-container">
        <h2 class="xn-heading-lg" style="max-width:600px;margin:0 auto 1rem;">Start Building Your<br><span style="color:#a855f7;">Digital Business Today</span></h2>
        <p class="xn-body" style="max-width:480px;margin:0 auto 2.5rem;">Join professionals and businesses already using Xenoraa to manage their website, store, sales, and operations from one platform.</p>
        <a href="{{ route('xenoraa.get-started') }}" class="xn-btn-primary-lg">Get Started Now 🚀</a>
        <p style="margin-top:1rem;font-size:0.8rem;color:#3f3f46;">30-day money-back guarantee &nbsp;·&nbsp; No credit card required to explore &nbsp;·&nbsp; Cancel anytime</p>
    </div>
</section>
@endsection
@section('scripts')
<script>
let currentBilling = 'monthly';
function toggleBilling() {
    const yearly = document.getElementById('billingToggle').checked;
    currentBilling = yearly ? 'yearly' : 'monthly';
    document.getElementById('monthlyLabel').classList.toggle('active', !yearly);
    document.getElementById('yearlyLabel').classList.toggle('active', yearly);
    document.querySelectorAll('.price-monthly').forEach(el => el.style.display = yearly ? 'none' : 'inline');
    document.querySelectorAll('.price-yearly').forEach(el => el.style.display = yearly ? 'inline' : 'none');
    document.querySelectorAll('.yearly-note').forEach(el => el.style.display = yearly ? 'inline' : 'none');
    document.querySelectorAll('.monthly-note').forEach(el => el.style.display = yearly ? 'none' : 'inline');
    document.querySelectorAll('a[href*="get-started"]').forEach(link => {
        try {
            const url = new URL(link.href, window.location.origin);
            url.searchParams.set('billing', currentBilling);
            link.href = url.toString();
        } catch(e) {}
    });
}
</script>
@endsection
