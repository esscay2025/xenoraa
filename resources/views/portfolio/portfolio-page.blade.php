@extends('layouts.app')
@section('title', ($portfolioPage ? $portfolioPage->title : 'Portfolio') . ' — ' . $siteName)
@section('content')
<style>
.xn-port-hero { background: linear-gradient(135deg, var(--accent, #6366f1) 0%, color-mix(in srgb, var(--accent, #6366f1) 70%, #000) 100%); padding: 4rem 2rem 3rem; text-align: center; color: #fff; }
.xn-port-hero h1 { font-size: 2.5rem; font-weight: 800; margin: 0 0 0.75rem; }
.xn-port-hero p { font-size: 1.1rem; opacity: 0.85; margin: 0; }
.xn-port-body { max-width: 1100px; margin: 0 auto; padding: 3rem 1.5rem; }
.xn-port-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
.xn-port-card { background: var(--bg-card, #fff); border: 1px solid var(--border, #e5e7eb); border-radius: 16px; overflow: hidden; transition: all 0.2s; }
.xn-port-card:hover { border-color: var(--accent, #6366f1); box-shadow: 0 8px 32px rgba(0,0,0,0.1); transform: translateY(-2px); }
.xn-port-card-img { height: 200px; background: linear-gradient(135deg, var(--accent, #6366f1)22, var(--accent, #6366f1)44); display: flex; align-items: center; justify-content: center; font-size: 3rem; }
.xn-port-card-body { padding: 1.5rem; }
.xn-port-card-tag { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--accent, #6366f1); margin-bottom: 0.5rem; }
.xn-port-card-title { font-size: 1.1rem; font-weight: 800; margin: 0 0 0.5rem; }
.xn-port-card-desc { font-size: 0.875rem; color: var(--text-secondary, #6b7280); line-height: 1.6; margin: 0; }
.xn-port-card-link { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; font-weight: 700; color: var(--accent, #6366f1); text-decoration: none; margin-top: 1rem; }
.xn-page-content { line-height: 1.8; color: var(--text-secondary, #374151); margin-bottom: 3rem; }
.xn-page-content h2 { font-size: 1.5rem; font-weight: 800; margin: 2rem 0 1rem; color: var(--text-primary, #111); }
.xn-port-section-title { font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem; }
.xn-port-section-sub { color: var(--text-muted, #6b7280); margin: 0 0 2.5rem; font-size: 1rem; }
</style>
@php
$heroHeading = $portfolioPage ? ($portfolioPage->getSectionData('hero')['heading'] ?? $portfolioPage->title ?? 'Portfolio') : 'Portfolio';
$heroSub     = $portfolioPage ? ($portfolioPage->getSectionData('hero')['subheading'] ?? '') : '';
$projects    = $portfolioPage ? ($portfolioPage->getSectionData('projects')['items'] ?? []) : [];
// Fallback to profile ventures for entrepreneur
if (empty($projects) && !empty($profile['ventures'])) {
    $projects = $profile['ventures'];
}
// Fallback to profile achievements for politician
if (empty($projects) && !empty($profile['achievements'])) {
    $projects = $profile['achievements'];
}
@endphp
<div class="xn-port-hero" style="--accent:{{ $accentColor }};">
    <h1>{{ $heroHeading }}</h1>
    @if($heroSub)<p>{{ $heroSub }}</p>@endif
</div>
<div class="xn-port-body" style="--accent:{{ $accentColor }};">
    @if($portfolioPage && $portfolioPage->content)
    <div class="xn-page-content">
        {!! $portfolioPage->content !!}
    </div>
    @endif

    @if(!empty($projects))
    <div class="xn-port-section-title">Featured Work</div>
    <p class="xn-port-section-sub">A selection of projects and achievements</p>
    <div class="xn-port-grid">
        @foreach($projects as $proj)
        <div class="xn-port-card">
            <div class="xn-port-card-img">
                @if(!empty($proj['image']))
                    <img src="{{ $proj['image'] }}" alt="{{ $proj['title'] ?? '' }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ $proj['icon'] ?? '🚀' }}
                @endif
            </div>
            <div class="xn-port-card-body">
                @if(!empty($proj['category']))<div class="xn-port-card-tag">{{ $proj['category'] }}</div>@endif
                <div class="xn-port-card-title">{{ $proj['title'] ?? $proj['name'] ?? '' }}</div>
                <p class="xn-port-card-desc">{{ $proj['text'] ?? $proj['description'] ?? '' }}</p>
                @if(!empty($proj['url']))
                <a href="{{ $proj['url'] }}" target="_blank" class="xn-port-card-link"><i class="fas fa-external-link-alt"></i> View Project</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @elseif(!$portfolioPage || !$portfolioPage->content)
    <div style="text-align:center;padding:4rem 2rem;color:var(--text-muted,#6b7280);">
        <i class="fas fa-briefcase" style="font-size:3rem;opacity:0.3;display:block;margin-bottom:1rem;"></i>
        <p>Portfolio content coming soon.</p>
    </div>
    @endif
</div>
@endsection
