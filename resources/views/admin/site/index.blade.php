@extends('layouts.admin')
@section('title', 'Site Builder')
@section('page-title', 'Site Builder')

@section('content')
<style>
.sb-hub-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.25rem; margin-top: 1.5rem; }
.sb-hub-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1.75rem 1.5rem;
    text-decoration: none;
    color: var(--text-primary);
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    transition: all 0.2s;
    position: relative;
    overflow: hidden;
}
.sb-hub-card:hover { border-color: var(--accent, #6366f1); transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.3); }
.sb-hub-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--accent, #6366f1); opacity: 0; transition: opacity 0.2s; }
.sb-hub-card:hover::before { opacity: 1; }
.sb-hub-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 0.25rem; }
.sb-hub-title { font-size: 1.05rem; font-weight: 700; }
.sb-hub-desc { font-size: 0.82rem; color: var(--text-secondary); line-height: 1.5; }
.sb-hub-badge { font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 20px; background: rgba(99,102,241,0.15); color: #818cf8; width: fit-content; }
.sb-hub-meta { font-size: 0.78rem; color: var(--text-muted); margin-top: auto; padding-top: 0.75rem; border-top: 1px solid var(--border); display: flex; align-items: center; gap: 0.4rem; }
.sb-active-theme { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem; }
.sb-theme-dot { width: 12px; height: 12px; border-radius: 50%; background: var(--accent, #6366f1); }
</style>

<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.5rem;">
    <div>
        <h1 style="font-size:1.75rem;font-weight:800;margin:0;">Site Builder</h1>
        <p style="color:var(--text-secondary);margin:0.25rem 0 0;font-size:0.9rem;">Manage your public website — theme, pages, navigation, and branding.</p>
    </div>
    @php
        $tenantUser = auth()->user();
        $siteUrl = $tenantUser->custom_domain ? 'https://'.$tenantUser->custom_domain : url('/'.$tenantUser->username);
    @endphp
    <a href="{{ $siteUrl }}" target="_blank" class="btn btn-outline btn-sm" style="white-space:nowrap;">
        <i class="fas fa-external-link-alt"></i> View Site
    </a>
</div>

{{-- Active Theme Banner --}}
<div class="sb-active-theme">
    <div class="sb-theme-dot"></div>
    <div>
        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Active Theme</div>
        <div style="font-weight:700;font-size:1rem;margin-top:0.1rem;">
            @php
                $themeNames = ['consultant'=>'Nexus Pro','influencer'=>'Aura','advocate'=>'Lex','entrepreneur'=>'Momentum','doctor'=>'Vitae','politician'=>'Civitas'];
            @endphp
            {{ $themeNames[$activeTheme] ?? ucfirst($activeTheme) }}
            <span style="font-size:0.78rem;color:var(--text-secondary);font-weight:400;margin-left:0.5rem;">{{ ucfirst($activeTheme) }} template</span>
        </div>
    </div>
    <a href="{{ route('admin.site.themes') }}" class="btn btn-sm" style="margin-left:auto;background:var(--accent,#6366f1);color:#fff;border:none;">
        <i class="fas fa-palette"></i> Change Theme
    </a>
</div>

<div class="sb-hub-grid">
    {{-- Theme Store --}}
    <a href="{{ route('admin.site.themes') }}" class="sb-hub-card">
        <div class="sb-hub-icon" style="background:rgba(99,102,241,0.12);color:#818cf8;"><i class="fas fa-palette"></i></div>
        <div class="sb-hub-title">Theme Store</div>
        <div class="sb-hub-desc">Choose from 6 premium, profession-specific themes. Live preview before activating.</div>
        <div class="sb-hub-badge">6 Premium Themes</div>
        <div class="sb-hub-meta"><i class="fas fa-check-circle" style="color:var(--success);"></i> Active: {{ $themeNames[$activeTheme] ?? ucfirst($activeTheme) }}</div>
    </a>

    {{-- Page Manager --}}
    <a href="{{ route('admin.site.pages') }}" class="sb-hub-card">
        <div class="sb-hub-icon" style="background:rgba(16,185,129,0.12);color:#34d399;"><i class="fas fa-file-alt"></i></div>
        <div class="sb-hub-title">Page Manager</div>
        <div class="sb-hub-desc">Create and manage custom pages — About, Services, Portfolio, or any page you need.</div>
        <div class="sb-hub-badge">Custom Pages</div>
        <div class="sb-hub-meta"><i class="fas fa-layer-group" style="color:var(--text-muted);"></i> {{ $pageCount }} page{{ $pageCount !== 1 ? 's' : '' }} created</div>
    </a>

    {{-- Menu Builder --}}
    <a href="{{ route('admin.site.menu') }}" class="sb-hub-card">
        <div class="sb-hub-icon" style="background:rgba(245,158,11,0.12);color:#fbbf24;"><i class="fas fa-bars"></i></div>
        <div class="sb-hub-title">Menu Builder</div>
        <div class="sb-hub-desc">Drag and drop to build your site navigation. Add links, dropdowns, and custom URLs.</div>
        <div class="sb-hub-badge">Navigation</div>
        <div class="sb-hub-meta"><i class="fas fa-link" style="color:var(--text-muted);"></i> {{ $menuItemCount }} menu item{{ $menuItemCount !== 1 ? 's' : '' }}</div>
    </a>

    {{-- Branding --}}
    <a href="{{ route('admin.site.branding') }}" class="sb-hub-card">
        <div class="sb-hub-icon" style="background:rgba(244,63,94,0.12);color:#fb7185;"><i class="fas fa-paint-brush"></i></div>
        <div class="sb-hub-title">Branding</div>
        <div class="sb-hub-desc">Upload your logo, favicon, set your site name, tagline, and brand accent colour.</div>
        <div class="sb-hub-badge">Logo & Favicon</div>
        <div class="sb-hub-meta">
            @if($logo)
                <i class="fas fa-check-circle" style="color:var(--success);"></i> Logo uploaded
            @else
                <i class="fas fa-exclamation-circle" style="color:var(--warning);"></i> No logo yet
            @endif
        </div>
    </a>

    {{-- Blog --}}
    <a href="{{ route('admin.blog.index') }}" class="sb-hub-card">
        <div class="sb-hub-icon" style="background:rgba(59,130,246,0.12);color:#60a5fa;"><i class="fas fa-rss"></i></div>
        <div class="sb-hub-title">Blog</div>
        <div class="sb-hub-desc">Write and publish blog posts. Manage categories, tags, and featured images.</div>
        <div class="sb-hub-badge">Content</div>
        <div class="sb-hub-meta"><i class="fas fa-arrow-right" style="color:var(--text-muted);"></i> Go to Blog Manager</div>
    </a>

    {{-- Shop --}}
    <a href="{{ route('admin.ecommerce.products') }}" class="sb-hub-card">
        <div class="sb-hub-icon" style="background:rgba(168,85,247,0.12);color:#c084fc;"><i class="fas fa-shopping-bag"></i></div>
        <div class="sb-hub-title">Shop</div>
        <div class="sb-hub-desc">Manage your online store &mdash; products, categories, and orders.</div>
        <div class="sb-hub-badge">E-Commerce</div>
        <div class="sb-hub-meta"><i class="fas fa-arrow-right" style="color:var(--text-muted);"></i> Go to Shop Manager</div>
    </a>

    {{-- Portfolio --}}
    <a href="{{ route('admin.projects.index') }}" class="sb-hub-card">
        <div class="sb-hub-icon" style="background:rgba(20,184,166,0.12);color:#2dd4bf;"><i class="fas fa-project-diagram"></i></div>
        <div class="sb-hub-title">Portfolio</div>
        <div class="sb-hub-desc">Showcase your projects, case studies, and work samples with rich media and tech tags.</div>
        <div class="sb-hub-badge">Projects</div>
        <div class="sb-hub-meta"><i class="fas fa-arrow-right" style="color:var(--text-muted);"></i> Manage Projects</div>
    </a>

    {{-- Testimonials --}}
    <a href="{{ route('admin.testimonials.index') }}" class="sb-hub-card">
        <div class="sb-hub-icon" style="background:rgba(234,179,8,0.12);color:#facc15;"><i class="fas fa-quote-left"></i></div>
        <div class="sb-hub-title">Testimonials</div>
        <div class="sb-hub-desc">Collect and display client reviews, star ratings, and social proof on your site.</div>
        <div class="sb-hub-badge">Social Proof</div>
        <div class="sb-hub-meta"><i class="fas fa-arrow-right" style="color:var(--text-muted);"></i> Manage Testimonials</div>
    </a>

    {{-- Profile --}}
    <a href="{{ route('admin.profile-enhanced.index') }}" class="sb-hub-card">
        <div class="sb-hub-icon" style="background:rgba(239,68,68,0.12);color:#f87171;"><i class="fas fa-user-graduate"></i></div>
        <div class="sb-hub-title">Profile</div>
        <div class="sb-hub-desc">Add your skills, education history, certifications, and languages to enrich your profile page.</div>
        <div class="sb-hub-badge">Skills &amp; Bio</div>
        <div class="sb-hub-meta"><i class="fas fa-arrow-right" style="color:var(--text-muted);"></i> Manage Profile</div>
    </a>
</div>
@endsection
