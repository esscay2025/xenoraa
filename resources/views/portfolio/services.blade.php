@extends('layouts.app')
@section('title', ($servicesPage ? $servicesPage->title : 'Services') . ' — ' . $siteName)
@section('content')
<style>
.xn-svc-hero { background: linear-gradient(135deg, var(--accent, #6366f1) 0%, color-mix(in srgb, var(--accent, #6366f1) 70%, #000) 100%); padding: 4rem 2rem 3rem; text-align: center; color: #fff; }
.xn-svc-hero h1 { font-size: 2.5rem; font-weight: 800; margin: 0 0 0.75rem; }
.xn-svc-hero p { font-size: 1.1rem; opacity: 0.85; margin: 0; }
.xn-svc-body { max-width: 1100px; margin: 0 auto; padding: 3rem 1.5rem; }
.xn-svc-section-title { font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem; }
.xn-svc-section-sub { color: var(--text-muted, #6b7280); margin: 0 0 2.5rem; font-size: 1rem; }
.xn-svc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 3rem; }
.xn-svc-card { background: var(--bg-card, #fff); border: 1px solid var(--border, #e5e7eb); border-radius: 16px; padding: 2rem; transition: all 0.2s; }
.xn-svc-card:hover { border-color: var(--accent, #6366f1); box-shadow: 0 8px 32px rgba(0,0,0,0.08); transform: translateY(-2px); }
.xn-svc-icon { font-size: 2rem; margin-bottom: 1rem; }
.xn-svc-name { font-size: 1.1rem; font-weight: 700; margin: 0 0 0.5rem; }
.xn-svc-desc { font-size: 0.875rem; color: var(--text-secondary, #6b7280); line-height: 1.6; margin: 0; }
.xn-svc-price { font-size: 0.85rem; font-weight: 700; color: var(--accent, #6366f1); margin-top: 0.75rem; }
.xn-svc-cta { background: var(--bg-secondary, #f9fafb); border: 1px solid var(--border, #e5e7eb); border-radius: 16px; padding: 3rem 2rem; text-align: center; margin-top: 3rem; }
.xn-svc-cta h2 { font-size: 1.75rem; font-weight: 800; margin: 0 0 0.75rem; }
.xn-svc-cta p { color: var(--text-secondary, #6b7280); margin: 0 0 2rem; }
.xn-svc-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2rem; background: var(--accent, #6366f1); color: #fff; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 1rem; }
.xn-svc-btn:hover { opacity: 0.9; }
.xn-page-content { line-height: 1.8; color: var(--text-secondary, #374151); }
.xn-page-content h2 { font-size: 1.5rem; font-weight: 800; margin: 2rem 0 1rem; color: var(--text-primary, #111); }
.xn-page-content h3 { font-size: 1.2rem; font-weight: 700; margin: 1.5rem 0 0.75rem; color: var(--text-primary, #111); }
</style>
@php
$heroHeading = 'Services';
$heroSub     = '';
$serviceItems = [];
$ctaHeading  = "Let's Work Together";
$ctaText     = 'Ready to take the next step? Get in touch and let\'s discuss your needs.';
$ctaBtn      = 'Contact Me';
$ctaUrl      = '/contact';
if ($servicesPage) {
    $heroData = $servicesPage->getSectionData('hero');
    $heroHeading = $heroData['heading'] ?? $servicesPage->title ?? 'Services';
    $heroSub     = $heroData['subheading'] ?? '';
    $listData    = $servicesPage->getSectionData('list');
    $serviceItems = $listData['items'] ?? [];
    $ctaData     = $servicesPage->getSectionData('cta');
    $ctaHeading  = $ctaData['heading'] ?? $ctaHeading;
    $ctaText     = $ctaData['text']    ?? $ctaText;
    $ctaBtn      = $ctaData['button_text'] ?? $ctaBtn;
    $ctaUrl      = $ctaData['button_url']  ?? $ctaUrl;
}
// Fallback to profile services
if (empty($serviceItems) && !empty($profile['services'])) {
    $serviceItems = $profile['services'];
}
@endphp
<div class="xn-svc-hero" style="--accent:{{ $accentColor }};">
    <h1>{{ $heroHeading }}</h1>
    @if($heroSub)<p>{{ $heroSub }}</p>@endif
</div>
<div class="xn-svc-body" style="--accent:{{ $accentColor }};">
    @if($servicesPage && $servicesPage->content)
    <div class="xn-page-content" style="margin-bottom:3rem;">
        {!! $servicesPage->content !!}
    </div>
    @endif

    @if(!empty($serviceItems))
    <div class="xn-svc-section-title">What I Offer</div>
    <p class="xn-svc-section-sub">Comprehensive services tailored to your needs</p>
    <div class="xn-svc-grid">
        @foreach($serviceItems as $svc)
        <div class="xn-svc-card">
            <div class="xn-svc-icon">{{ $svc['icon'] ?? '💼' }}</div>
            <div class="xn-svc-name">{{ $svc['title'] ?? $svc['name'] ?? '' }}</div>
            <p class="xn-svc-desc">{{ $svc['text'] ?? $svc['description'] ?? '' }}</p>
            @if(!empty($svc['price']))
            <div class="xn-svc-price">{{ $svc['price'] }}</div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    @if($servicesPage && $servicesPage->isSectionEnabled('cta'))
    <div class="xn-svc-cta">
        <h2>{{ $ctaHeading }}</h2>
        <p>{{ $ctaText }}</p>
        <a href="{{ $ctaUrl }}" class="xn-svc-btn"><i class="fas fa-arrow-right"></i> {{ $ctaBtn }}</a>
    </div>
    @endif
</div>
@endsection
