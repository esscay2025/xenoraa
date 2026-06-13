@extends('layouts.xenoraa')
@section('title', $post->title . ' — Xenoraa Blog')
@section('meta_description', $post->summary ?? Str::limit(strip_tags($post->content), 160))
@section('styles')
<style>
.xn-post-hero { padding: 4rem 0 3rem; background: #000; border-bottom: 1px solid #111; }
.xn-post-hero-inner { max-width: 820px; margin: 0 auto; }
.xn-post-breadcrumb { font-size: 0.775rem; color: #52525b; margin-bottom: 1.25rem; }
.xn-post-breadcrumb a { color: #7c3aed; text-decoration: none; }
.xn-post-breadcrumb a:hover { color: #a855f7; }
.xn-post-cat-pill { display: inline-block; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #a855f7; background: rgba(168,85,247,0.08); border: 1px solid rgba(168,85,247,0.15); border-radius: 4px; padding: 0.25rem 0.65rem; margin-bottom: 1rem; }
.xn-post-title { font-family: 'Space Grotesk', sans-serif; font-size: clamp(1.6rem, 4vw, 2.5rem); font-weight: 800; color: #fff; line-height: 1.2; margin-bottom: 1rem; }
.xn-post-summary { font-size: 1.05rem; color: #71717a; line-height: 1.75; margin-bottom: 1.5rem; }
.xn-post-meta { display: flex; align-items: center; gap: 1.5rem; font-size: 0.8rem; color: #52525b; flex-wrap: wrap; }
.xn-post-meta-item { display: flex; align-items: center; gap: 0.4rem; }
.xn-post-section { background: #000; padding: 3rem 0 5rem; }
.xn-post-layout { display: grid; grid-template-columns: 1fr 300px; gap: 3.5rem; max-width: 1200px; margin: 0 auto; }
.xn-post-hero-img { width: 100%; max-height: 440px; object-fit: cover; border-radius: 10px; margin-bottom: 2.5rem; border: 1px solid #1a1a1a; }
/* Article content */
.xn-post-content { color: #a1a1aa; font-size: 0.975rem; line-height: 1.85; }
.xn-post-content h2 { font-family: 'Space Grotesk', sans-serif; font-size: 1.4rem; font-weight: 700; color: #fff; margin: 2.5rem 0 1rem; padding-top: 0.5rem; border-top: 1px solid #1a1a1a; }
.xn-post-content h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; font-weight: 700; color: #e4e4e7; margin: 1.75rem 0 0.75rem; }
.xn-post-content p { margin-bottom: 1.25rem; }
.xn-post-content ul, .xn-post-content ol { margin: 0 0 1.25rem 1.5rem; }
.xn-post-content li { margin-bottom: 0.5rem; }
.xn-post-content strong { color: #e4e4e7; font-weight: 600; }
.xn-post-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; font-size: 0.875rem; }
.xn-post-content th { background: #0f0f0f; color: #a855f7; font-weight: 700; padding: 0.75rem 1rem; text-align: left; border: 1px solid #1a1a1a; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; }
.xn-post-content td { padding: 0.75rem 1rem; border: 1px solid #111; color: #a1a1aa; }
.xn-post-content tr:hover td { background: #0a0a0a; }
/* Sidebar */
.xn-post-sidebar { position: sticky; top: 80px; }
.xn-sidebar-box { background: #0a0a0a; border: 1px solid #1a1a1a; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; }
.xn-sidebar-heading { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #52525b; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #111; }
.xn-related-card { display: block; text-decoration: none; margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #111; }
.xn-related-card:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
.xn-related-card-img { width: 100%; height: 110px; object-fit: cover; border-radius: 6px; margin-bottom: 0.75rem; }
.xn-related-card-title { font-size: 0.825rem; font-weight: 600; color: #a1a1aa; line-height: 1.4; transition: color 0.2s; }
.xn-related-card:hover .xn-related-card-title { color: #fff; }
.xn-related-card-date { font-size: 0.7rem; color: #3f3f46; margin-top: 0.35rem; }
.xn-sidebar-cta { background: linear-gradient(135deg, rgba(124,58,237,0.12), rgba(168,85,247,0.06)); border: 1px solid rgba(168,85,247,0.2); border-radius: 10px; padding: 1.75rem; text-align: center; }
.xn-sidebar-cta h4 { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; }
.xn-sidebar-cta p { font-size: 0.8rem; color: #71717a; line-height: 1.6; margin-bottom: 1.25rem; }
.xn-sidebar-cta-btn { display: inline-block; background: #7c3aed; color: #fff; font-size: 0.8rem; font-weight: 700; padding: 0.625rem 1.25rem; border-radius: 6px; text-decoration: none; transition: background 0.2s; }
.xn-sidebar-cta-btn:hover { background: #6d28d9; }
@media(max-width:1024px) { .xn-post-layout { grid-template-columns: 1fr; } .xn-post-sidebar { position: static; } }
</style>
@endsection
@section('content')
@php
    $imgSrc = $post->featured_image
        ? (str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . $post->featured_image))
        : null;
    $readTime = max(3, (int)(str_word_count(strip_tags($post->content ?? '')) / 200));
@endphp
{{-- Post Hero --}}
<section class="xn-post-hero">
    <div class="xn-container">
        <div class="xn-post-hero-inner">
            <div class="xn-post-breadcrumb">
                <a href="{{ route('xenoraa.home') }}">Home</a> &nbsp;/&nbsp;
                <a href="{{ route('xenoraa.blog') }}">Blog</a> &nbsp;/&nbsp;
                <span style="color:#71717a;">{{ Str::limit($post->title, 40) }}</span>
            </div>
            <div class="xn-post-cat-pill">Business &amp; SaaS</div>
            <h1 class="xn-post-title">{{ $post->title }}</h1>
            @if($post->summary)
            <p class="xn-post-summary">{{ $post->summary }}</p>
            @endif
            <div class="xn-post-meta">
                <span class="xn-post-meta-item"><i class="fas fa-calendar"></i> {{ $post->published_at ? $post->published_at->format('M d, Y') : date('M d, Y') }}</span>
                <span class="xn-post-meta-item"><i class="fas fa-clock"></i> {{ $readTime }} min read</span>
                <span class="xn-post-meta-item"><i class="fas fa-building"></i> Xenoraa Team</span>
            </div>
        </div>
    </div>
</section>

{{-- Post Body --}}
<section class="xn-post-section">
    <div class="xn-container">
        <div class="xn-post-layout">
            {{-- Article --}}
            <article>
                @if($imgSrc)
                    <img src="{{ $imgSrc }}" alt="{{ $post->title }}" class="xn-post-hero-img">
                @endif
                <div class="xn-post-content">
                    {!! $post->content !!}
                </div>
                {{-- Back to blog --}}
                <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid #1a1a1a;">
                    <a href="{{ route('xenoraa.blog') }}" style="color:#7c3aed;font-size:0.875rem;font-weight:600;text-decoration:none;">← Back to Blog</a>
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="xn-post-sidebar">
                @if($related->count() > 0)
                <div class="xn-sidebar-box">
                    <div class="xn-sidebar-heading">Related Articles</div>
                    @foreach($related as $rp)
                    @php
                        $rpImg = $rp->featured_image
                            ? (str_starts_with($rp->featured_image, 'http') ? $rp->featured_image : asset('storage/' . $rp->featured_image))
                            : null;
                    @endphp
                    <a href="{{ route('xenoraa.blog.show', $rp->slug) }}" class="xn-related-card">
                        @if($rpImg)
                            <img src="{{ $rpImg }}" alt="{{ $rp->title }}" class="xn-related-card-img">
                        @endif
                        <div class="xn-related-card-title">{{ $rp->title }}</div>
                        <div class="xn-related-card-date">{{ $rp->published_at ? $rp->published_at->format('M d, Y') : '' }}</div>
                    </a>
                    @endforeach
                </div>
                @endif
                <div class="xn-sidebar-cta">
                    <h4>Run Your Business on Xenoraa</h4>
                    <p>Website, E-Commerce, POS, and CRM — all in one platform. Start your free trial today.</p>
                    <a href="{{ route('xenoraa.get-started') }}" class="xn-sidebar-cta-btn">Get Started Free →</a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
