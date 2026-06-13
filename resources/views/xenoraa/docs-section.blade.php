@extends('layouts.xenoraa')
@section('title', $section['title'] . ' — Xenoraa Docs')
@section('meta_description', $section['subtitle'])
@section('styles')
<style>
/* ── Documentation Section Page ── */
.xn-docs-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    min-height: calc(100vh - 72px);
    background: #000;
}
.xn-docs-sidebar {
    background: #080808;
    border-right: 1px solid #1a1a1a;
    padding: 2rem 0;
    position: sticky;
    top: 72px;
    height: calc(100vh - 72px);
    overflow-y: auto;
}
.xn-docs-sidebar-header {
    padding: 0 1.5rem 1.25rem;
    border-bottom: 1px solid #1a1a1a;
    margin-bottom: 1rem;
}
.xn-docs-sidebar-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: #666;
    text-decoration: none;
    margin-bottom: 0.75rem;
    transition: color 0.2s;
}
.xn-docs-sidebar-back:hover { color: #a855f7; text-decoration: none; }
.xn-docs-sidebar-title {
    font-size: 0.8125rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}
.xn-docs-nav-section {
    padding: 0 1rem 1rem;
}
.xn-docs-nav-section-label {
    font-size: 0.65rem;
    font-weight: 700;
    color: #444;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 0.5rem 0.5rem 0.25rem;
    margin-top: 0.5rem;
}
.xn-docs-nav-link {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.8125rem;
    color: #888;
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
    margin-bottom: 2px;
}
.xn-docs-nav-link:hover { background: #111; color: #fff; text-decoration: none; }
.xn-docs-nav-link.active { background: rgba(124,58,237,0.12); color: #a855f7; }
.xn-docs-nav-link i { width: 16px; text-align: center; font-size: 0.75rem; }
.xn-docs-content {
    padding: 3rem 4rem;
    max-width: 860px;
}
.xn-docs-breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: #555;
    margin-bottom: 2rem;
}
.xn-docs-breadcrumb a { color: #666; text-decoration: none; }
.xn-docs-breadcrumb a:hover { color: #a855f7; }
.xn-docs-breadcrumb i { font-size: 0.6rem; }
.xn-docs-page-header {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #1a1a1a;
}
.xn-docs-page-icon {
    width: 56px;
    height: 56px;
    background: rgba(124,58,237,0.1);
    border: 1px solid rgba(124,58,237,0.2);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #a855f7;
    margin-bottom: 1.25rem;
}
.xn-docs-page-title {
    font-size: 2rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}
.xn-docs-page-subtitle {
    font-size: 1rem;
    color: #888;
    line-height: 1.6;
}
/* Markdown content styles */
.xn-docs-body h2 {
    font-size: 1.375rem;
    font-weight: 700;
    color: #fff;
    margin: 2.5rem 0 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #1a1a1a;
}
.xn-docs-body h3 {
    font-size: 1.0625rem;
    font-weight: 700;
    color: #e2e2e2;
    margin: 1.75rem 0 0.75rem;
}
.xn-docs-body h4 {
    font-size: 0.9375rem;
    font-weight: 600;
    color: #ccc;
    margin: 1.25rem 0 0.5rem;
}
.xn-docs-body p {
    font-size: 0.9375rem;
    color: #aaa;
    line-height: 1.8;
    margin-bottom: 1rem;
}
.xn-docs-body ul, .xn-docs-body ol {
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}
.xn-docs-body li {
    font-size: 0.9375rem;
    color: #aaa;
    line-height: 1.8;
    margin-bottom: 0.25rem;
}
.xn-docs-body strong { color: #e2e2e2; }
.xn-docs-body code {
    background: #111;
    border: 1px solid #222;
    border-radius: 4px;
    padding: 0.1rem 0.4rem;
    font-size: 0.8125rem;
    color: #a855f7;
    font-family: 'Fira Code', 'Courier New', monospace;
}
.xn-docs-body pre {
    background: #0d0d0d;
    border: 1px solid #1f1f1f;
    border-radius: 10px;
    padding: 1.25rem;
    overflow-x: auto;
    margin: 1.25rem 0;
}
.xn-docs-body pre code {
    background: none;
    border: none;
    padding: 0;
    color: #ccc;
    font-size: 0.875rem;
}
.xn-docs-body table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
    font-size: 0.875rem;
}
.xn-docs-body table th {
    background: #0d0d0d;
    border: 1px solid #1f1f1f;
    padding: 0.625rem 1rem;
    text-align: left;
    color: #ccc;
    font-weight: 600;
    font-size: 0.8125rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.xn-docs-body table td {
    border: 1px solid #1a1a1a;
    padding: 0.625rem 1rem;
    color: #aaa;
    vertical-align: top;
}
.xn-docs-body table tr:hover td { background: #0a0a0a; }
.xn-docs-body blockquote {
    border-left: 3px solid #7c3aed;
    background: rgba(124,58,237,0.05);
    border-radius: 0 8px 8px 0;
    padding: 1rem 1.25rem;
    margin: 1.25rem 0;
    color: #bbb;
    font-style: italic;
}
.xn-docs-tip {
    background: rgba(34,197,94,0.05);
    border: 1px solid rgba(34,197,94,0.15);
    border-radius: 10px;
    padding: 1rem 1.25rem;
    margin: 1.25rem 0;
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
}
.xn-docs-tip i { color: #22c55e; margin-top: 2px; flex-shrink: 0; }
.xn-docs-tip-text { font-size: 0.875rem; color: #aaa; line-height: 1.6; }
.xn-docs-warning {
    background: rgba(245,158,11,0.05);
    border: 1px solid rgba(245,158,11,0.15);
    border-radius: 10px;
    padding: 1rem 1.25rem;
    margin: 1.25rem 0;
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
}
.xn-docs-warning i { color: #f59e0b; margin-top: 2px; flex-shrink: 0; }
.xn-docs-warning-text { font-size: 0.875rem; color: #aaa; line-height: 1.6; }
.xn-docs-step {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.25rem;
    align-items: flex-start;
}
.xn-docs-step-num {
    width: 28px;
    height: 28px;
    background: rgba(124,58,237,0.15);
    border: 1px solid rgba(124,58,237,0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    color: #a855f7;
    flex-shrink: 0;
    margin-top: 2px;
}
.xn-docs-step-content { flex: 1; }
.xn-docs-step-title { font-size: 0.9375rem; font-weight: 600; color: #e2e2e2; margin-bottom: 0.25rem; }
.xn-docs-step-desc { font-size: 0.875rem; color: #888; line-height: 1.6; }
/* Navigation between sections */
.xn-docs-nav-footer {
    display: flex;
    justify-content: space-between;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #1a1a1a;
    gap: 1rem;
}
.xn-docs-nav-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: #0d0d0d;
    border: 1px solid #1f1f1f;
    border-radius: 10px;
    color: #888;
    text-decoration: none;
    font-size: 0.875rem;
    transition: border-color 0.2s, color 0.2s;
    max-width: 220px;
}
.xn-docs-nav-btn:hover { border-color: #7c3aed; color: #a855f7; text-decoration: none; }
.xn-docs-nav-btn-label { font-size: 0.7rem; color: #555; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 2px; }
.xn-docs-nav-btn-title { font-weight: 600; color: #ccc; font-size: 0.875rem; }
@media(max-width:900px){
    .xn-docs-layout{grid-template-columns:1fr;}
    .xn-docs-sidebar{display:none;}
    .xn-docs-content{padding:2rem 1.5rem;}
}
</style>
@endsection
@section('content')
<div class="xn-docs-layout">
    {{-- Sidebar --}}
    <aside class="xn-docs-sidebar">
        <div class="xn-docs-sidebar-header">
            <a href="{{ route('xenoraa.docs') }}" class="xn-docs-sidebar-back"><i class="fas fa-arrow-left"></i> All Docs</a>
            <div class="xn-docs-sidebar-title">Documentation</div>
        </div>
        <nav class="xn-docs-nav-section">
            <div class="xn-docs-nav-section-label">Sections</div>
            @foreach($allSections as $s)
            <a href="{{ route('xenoraa.docs.section', $s['slug']) }}" class="xn-docs-nav-link {{ $s['slug'] === $section['slug'] ? 'active' : '' }}">
                <i class="{{ $s['icon'] }}"></i> {{ $s['title'] }}
            </a>
            @endforeach
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="xn-docs-content">
        {{-- Breadcrumb --}}
        <div class="xn-docs-breadcrumb">
            <a href="{{ route('xenoraa.home') }}">Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="{{ route('xenoraa.docs') }}">Documentation</a>
            <i class="fas fa-chevron-right"></i>
            <span style="color:#a855f7;">{{ $section['title'] }}</span>
        </div>

        {{-- Page Header --}}
        <div class="xn-docs-page-header">
            <div class="xn-docs-page-icon"><i class="{{ $section['icon'] }}"></i></div>
            <h1 class="xn-docs-page-title">{{ $section['title'] }}</h1>
            <p class="xn-docs-page-subtitle">{{ $section['subtitle'] }}</p>
        </div>

        {{-- Content --}}
        <div class="xn-docs-body">
            {!! $section['html'] !!}
        </div>

        {{-- Prev / Next Navigation --}}
        <div class="xn-docs-nav-footer">
            @if($prev)
            <a href="{{ route('xenoraa.docs.section', $prev['slug']) }}" class="xn-docs-nav-btn" style="flex-direction:column;align-items:flex-start;">
                <span class="xn-docs-nav-btn-label"><i class="fas fa-arrow-left" style="margin-right:4px;"></i> Previous</span>
                <span class="xn-docs-nav-btn-title">{{ $prev['title'] }}</span>
            </a>
            @else
            <div></div>
            @endif
            @if($next)
            <a href="{{ route('xenoraa.docs.section', $next['slug']) }}" class="xn-docs-nav-btn" style="flex-direction:column;align-items:flex-end;text-align:right;">
                <span class="xn-docs-nav-btn-label">Next <i class="fas fa-arrow-right" style="margin-left:4px;"></i></span>
                <span class="xn-docs-nav-btn-title">{{ $next['title'] }}</span>
            </a>
            @else
            <div></div>
            @endif
        </div>
    </main>
</div>
@endsection
