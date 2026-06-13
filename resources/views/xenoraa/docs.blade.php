@extends('layouts.xenoraa')
@section('title', 'Documentation — Xenoraa')
@section('meta_description', 'Comprehensive guides and documentation for Xenoraa. Learn how to set up your account, manage your website, run your store, use the CRM, and more.')
@section('styles')
<style>
/* ── Documentation Hub ── */
.xn-docs-hero {
    padding: 5rem 4rem 4rem;
    background: linear-gradient(180deg, #0a0a0a 0%, #000 100%);
    border-bottom: 1px solid #1a1a1a;
    text-align: center;
}
.xn-docs-search-wrap {
    max-width: 560px;
    margin: 2rem auto 0;
    position: relative;
}
.xn-docs-search {
    width: 100%;
    background: #111;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    padding: 0.875rem 1.25rem 0.875rem 3rem;
    color: #fff;
    font-size: 0.9375rem;
    outline: none;
    transition: border-color 0.2s;
}
.xn-docs-search:focus { border-color: #7c3aed; }
.xn-docs-search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #555;
    font-size: 0.875rem;
}
.xn-docs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 4rem 4rem;
    max-width: 1200px;
    margin: 0 auto;
}
.xn-docs-card {
    background: #0d0d0d;
    border: 1px solid #1a1a1a;
    border-radius: 16px;
    padding: 2rem;
    text-decoration: none;
    color: inherit;
    transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
    display: block;
}
.xn-docs-card:hover {
    border-color: #7c3aed;
    transform: translateY(-3px);
    box-shadow: 0 8px 32px rgba(124,58,237,0.12);
    text-decoration: none;
    color: inherit;
}
.xn-docs-card-icon {
    width: 48px;
    height: 48px;
    background: rgba(124,58,237,0.1);
    border: 1px solid rgba(124,58,237,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #a855f7;
    margin-bottom: 1.25rem;
}
.xn-docs-card-title {
    font-size: 1.0625rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 0.5rem;
}
.xn-docs-card-desc {
    font-size: 0.875rem;
    color: #888;
    line-height: 1.6;
    margin-bottom: 1.25rem;
}
.xn-docs-card-topics {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
}
.xn-docs-topic-pill {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 100px;
    padding: 0.2rem 0.625rem;
    font-size: 0.7rem;
    color: #777;
    font-weight: 500;
}
.xn-docs-card:hover .xn-docs-topic-pill {
    background: rgba(124,58,237,0.08);
    border-color: rgba(124,58,237,0.2);
    color: #a855f7;
}
.xn-docs-cta {
    background: #050505;
    border-top: 1px solid #111;
    padding: 4rem;
    text-align: center;
}
@media(max-width:768px){
    .xn-docs-hero{padding:3rem 1.5rem 2.5rem;}
    .xn-docs-grid{padding:2rem 1.5rem;grid-template-columns:1fr;}
    .xn-docs-cta{padding:3rem 1.5rem;}
}
</style>
@endsection
@section('content')
{{-- Hero --}}
<section class="xn-docs-hero">
    <div class="xn-label">Documentation</div>
    <h1 class="xn-heading-xl" style="max-width:700px;margin:0 auto;">How Can We <span style="color:#a855f7;">Help You?</span></h1>
    <p class="xn-body-lg" style="max-width:520px;margin:1rem auto 0;color:#888;">Step-by-step guides, tutorials, and references for every Xenoraa module. Find answers fast and get the most out of your platform.</p>
    <div class="xn-docs-search-wrap">
        <i class="fas fa-search xn-docs-search-icon"></i>
        <input type="text" class="xn-docs-search" id="docsSearch" placeholder="Search documentation…" autocomplete="off">
    </div>
</section>

{{-- Documentation Cards Grid --}}
<div class="xn-docs-grid" id="docsGrid">

    <a href="{{ route('xenoraa.docs.section', 'getting-started') }}" class="xn-docs-card" data-keywords="setup onboarding domain ssl dashboard account">
        <div class="xn-docs-card-icon"><i class="fas fa-rocket"></i></div>
        <div class="xn-docs-card-title">Getting Started</div>
        <div class="xn-docs-card-desc">Create your account, map your custom domain, activate SSL, and navigate your tenant dashboard with confidence.</div>
        <div class="xn-docs-card-topics">
            <span class="xn-docs-topic-pill">Account Setup</span>
            <span class="xn-docs-topic-pill">Domain Mapping</span>
            <span class="xn-docs-topic-pill">SSL Activation</span>
            <span class="xn-docs-topic-pill">Dashboard Tour</span>
        </div>
    </a>

    <a href="{{ route('xenoraa.docs.section', 'website-builder') }}" class="xn-docs-card" data-keywords="website pages menu theme layout branding builder">
        <div class="xn-docs-card-icon"><i class="fas fa-laptop-code"></i></div>
        <div class="xn-docs-card-title">Website Builder</div>
        <div class="xn-docs-card-desc">Build professional web pages, manage navigation menus, apply themes, and customise your personal branding profile.</div>
        <div class="xn-docs-card-topics">
            <span class="xn-docs-topic-pill">Page Manager</span>
            <span class="xn-docs-topic-pill">Menu Builder</span>
            <span class="xn-docs-topic-pill">Themes</span>
            <span class="xn-docs-topic-pill">Layout Blocks</span>
        </div>
    </a>

    <a href="{{ route('xenoraa.docs.section', 'ecommerce-store') }}" class="xn-docs-card" data-keywords="ecommerce store products orders categories inventory import checkout">
        <div class="xn-docs-card-icon"><i class="fas fa-shopping-bag"></i></div>
        <div class="xn-docs-card-title">E-Commerce Store</div>
        <div class="xn-docs-card-desc">Set up your product catalog, manage stock levels, configure pricing, handle orders, and run a professional online store.</div>
        <div class="xn-docs-card-topics">
            <span class="xn-docs-topic-pill">Products</span>
            <span class="xn-docs-topic-pill">Categories</span>
            <span class="xn-docs-topic-pill">Orders</span>
            <span class="xn-docs-topic-pill">Product Import</span>
        </div>
    </a>

    <a href="{{ route('xenoraa.docs.section', 'pos-terminal') }}" class="xn-docs-card" data-keywords="pos point of sale terminal session walk-in receipt payment retail">
        <div class="xn-docs-card-icon"><i class="fas fa-cash-register"></i></div>
        <div class="xn-docs-card-title">POS Terminal</div>
        <div class="xn-docs-card-desc">Open and close POS sessions, process walk-in sales, accept multiple payment methods, and print receipts for retail customers.</div>
        <div class="xn-docs-card-topics">
            <span class="xn-docs-topic-pill">Sessions</span>
            <span class="xn-docs-topic-pill">Walk-In Sales</span>
            <span class="xn-docs-topic-pill">Receipts</span>
            <span class="xn-docs-topic-pill">Inventory Sync</span>
        </div>
    </a>

    <a href="{{ route('xenoraa.docs.section', 'crm-sales') }}" class="xn-docs-card" data-keywords="crm leads deals contacts pipeline kanban tasks activities sales">
        <div class="xn-docs-card-icon"><i class="fas fa-users"></i></div>
        <div class="xn-docs-card-title">CRM & Sales</div>
        <div class="xn-docs-card-desc">Capture leads, manage contacts, track deals through the Kanban pipeline, schedule activities, and close more business.</div>
        <div class="xn-docs-card-topics">
            <span class="xn-docs-topic-pill">Leads</span>
            <span class="xn-docs-topic-pill">Deals Pipeline</span>
            <span class="xn-docs-topic-pill">Kanban Board</span>
            <span class="xn-docs-topic-pill">Activities</span>
        </div>
    </a>

    <a href="{{ route('xenoraa.docs.section', 'accounts-finance') }}" class="xn-docs-card" data-keywords="accounts finance banking transactions journal chart of accounts reports profit loss balance sheet">
        <div class="xn-docs-card-icon"><i class="fas fa-chart-line"></i></div>
        <div class="xn-docs-card-title">Accounts & Finance</div>
        <div class="xn-docs-card-desc">Manage bank accounts, record income and expenses, maintain double-entry journals, and generate financial reports.</div>
        <div class="xn-docs-card-topics">
            <span class="xn-docs-topic-pill">Banking</span>
            <span class="xn-docs-topic-pill">Transactions</span>
            <span class="xn-docs-topic-pill">Journal Entries</span>
            <span class="xn-docs-topic-pill">Reports</span>
        </div>
    </a>

    <a href="{{ route('xenoraa.docs.section', 'ai-hub') }}" class="xn-docs-card" data-keywords="ai xena assistant training chat automation intelligence">
        <div class="xn-docs-card-icon"><i class="fas fa-brain"></i></div>
        <div class="xn-docs-card-title">AI Hub (Xena AI)</div>
        <div class="xn-docs-card-desc">Train Xena AI with your business data, use the AI assistant for instant answers, and automate repetitive workflows intelligently.</div>
        <div class="xn-docs-card-topics">
            <span class="xn-docs-topic-pill">AI Assistant</span>
            <span class="xn-docs-topic-pill">Custom Training</span>
            <span class="xn-docs-topic-pill">Conversations</span>
            <span class="xn-docs-topic-pill">Automation</span>
        </div>
    </a>

    <a href="{{ route('xenoraa.docs.section', 'billing-subscriptions') }}" class="xn-docs-card" data-keywords="billing subscription plan pricing solo duo all-access invoice payment upgrade">
        <div class="xn-docs-card-icon"><i class="fas fa-credit-card"></i></div>
        <div class="xn-docs-card-title">Billing & Subscriptions</div>
        <div class="xn-docs-card-desc">Understand the three subscription tiers, choose the right apps for your business, manage your plan, and view invoice history.</div>
        <div class="xn-docs-card-topics">
            <span class="xn-docs-topic-pill">Solo App</span>
            <span class="xn-docs-topic-pill">Duo Bundle</span>
            <span class="xn-docs-topic-pill">All-Access</span>
            <span class="xn-docs-topic-pill">Invoices</span>
        </div>
    </a>

</div>

{{-- Popular Topics --}}
<section class="xn-section" style="background:#050505;border-top:1px solid #111;padding:3rem 4rem;">
    <div class="xn-container">
        <h2 class="xn-heading-sm" style="margin-bottom:1.5rem;text-align:center;">Popular Topics</h2>
        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;justify-content:center;">
            <a href="{{ route('xenoraa.docs.section', 'getting-started') }}#domain-mapping" class="xn-docs-topic-pill" style="font-size:0.8125rem;padding:0.4rem 0.875rem;">How to map a custom domain</a>
            <a href="{{ route('xenoraa.docs.section', 'ecommerce-store') }}#product-import" class="xn-docs-topic-pill" style="font-size:0.8125rem;padding:0.4rem 0.875rem;">Import products via Excel</a>
            <a href="{{ route('xenoraa.docs.section', 'crm-sales') }}#kanban-board" class="xn-docs-topic-pill" style="font-size:0.8125rem;padding:0.4rem 0.875rem;">Using the Deals Kanban board</a>
            <a href="{{ route('xenoraa.docs.section', 'pos-terminal') }}#open-session" class="xn-docs-topic-pill" style="font-size:0.8125rem;padding:0.4rem 0.875rem;">Opening a POS session</a>
            <a href="{{ route('xenoraa.docs.section', 'accounts-finance') }}#journal-entries" class="xn-docs-topic-pill" style="font-size:0.8125rem;padding:0.4rem 0.875rem;">Recording journal entries</a>
            <a href="{{ route('xenoraa.docs.section', 'billing-subscriptions') }}#choose-plan" class="xn-docs-topic-pill" style="font-size:0.8125rem;padding:0.4rem 0.875rem;">Choosing the right plan</a>
            <a href="{{ route('xenoraa.docs.section', 'ai-hub') }}#training" class="xn-docs-topic-pill" style="font-size:0.8125rem;padding:0.4rem 0.875rem;">Training Xena AI</a>
            <a href="{{ route('xenoraa.docs.section', 'website-builder') }}#menu-builder" class="xn-docs-topic-pill" style="font-size:0.8125rem;padding:0.4rem 0.875rem;">Building navigation menus</a>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="xn-docs-cta">
    <div class="xn-container" style="max-width:600px;">
        <h2 class="xn-heading-md" style="margin-bottom:1rem;">Still Have Questions?</h2>
        <p class="xn-body" style="color:#888;margin-bottom:2rem;">Our support team is here to help. Reach out via email or start a chat and we'll get back to you within one business day.</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="mailto:support@xenoraa.com" class="xn-btn-primary-lg"><i class="fas fa-envelope" style="margin-right:0.5rem;"></i>Email Support</a>
            <a href="{{ route('xenoraa.get-started') }}" class="xn-btn-secondary-lg">Start Free Trial</a>
        </div>
    </div>
</section>

<script>
// Live search filter
document.getElementById('docsSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('#docsGrid .xn-docs-card').forEach(function(card) {
        const text = (card.querySelector('.xn-docs-card-title').textContent + ' ' +
                      card.querySelector('.xn-docs-card-desc').textContent + ' ' +
                      (card.dataset.keywords || '')).toLowerCase();
        card.style.display = (!q || text.includes(q)) ? '' : 'none';
    });
});
</script>
@endsection
