@extends('layouts.app')
@section('title', $post->title . ' | Gopi K Blog')
@section('description', Str::limit(strip_tags($post->summary ?? $post->content), 160))
@push('styles')
<style>
    /* ── Reading Progress Bar ── */
    #readingProgress {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #60a5fa, #8b5cf6);
        width: 0%;
        z-index: 9999;
        transition: width 0.1s linear;
    }

    /* ── Page Wrapper ── */
    .blog-page-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2.5rem 2rem 5rem;
    }

    /* ── Three-Column Layout ── */
    .blog-three-col {
        display: grid;
        grid-template-columns: 260px 1fr 240px;
        gap: 3rem;
        align-items: start;
        margin-top: 2.5rem;
    }

    /* ── LEFT: Sticky TOC ── */
    .blog-toc-sidebar {
        position: sticky;
        top: 90px;
        max-height: calc(100vh - 110px);
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.1) transparent;
    }
    .blog-toc-sidebar::-webkit-scrollbar { width: 4px; }
    .blog-toc-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

    .toc-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: rgba(255,255,255,0.35);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }
    .toc-list li a {
        display: block;
        font-size: 0.875rem;
        color: rgba(255,255,255,0.45);
        text-decoration: none;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        border-left: 2px solid transparent;
        line-height: 1.4;
        transition: all 0.2s ease;
        font-weight: 400;
    }
    .toc-list li a:hover {
        color: rgba(255,255,255,0.85);
        background: rgba(255,255,255,0.05);
        border-left-color: rgba(255,255,255,0.3);
    }
    .toc-list li a.toc-active {
        color: #60a5fa;
        background: rgba(96,165,250,0.08);
        border-left-color: #60a5fa;
        font-weight: 600;
    }

    /* ── CENTER: Main Content ── */
    .blog-main-content {
        min-width: 0;
    }

    /* Hero Image */
    .blog-hero-image {
        width: 100%;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        margin-bottom: 3rem;
        aspect-ratio: 16/7;
        background: #111;
    }
    .blog-hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }
    .blog-hero-image:hover img { transform: scale(1.02); }

    /* Content sections */
    .blog-content-sections {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .blog-intro-text {
        font-size: 1.15rem;
        line-height: 1.9;
        color: rgba(255,255,255,0.7);
        margin-bottom: 3.5rem;
        padding-bottom: 3rem;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    .blog-section-block {
        padding: 3rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        scroll-margin-top: 100px;
    }
    .blog-section-block:last-child {
        border-bottom: none;
    }

    .blog-section-heading {
        font-size: 1.65rem;
        font-weight: 800;
        line-height: 1.25;
        color: #ffffff;
        margin: 0 0 1.5rem 0;
        letter-spacing: -0.5px;
    }

    .blog-section-body {
        font-size: 1.05rem;
        line-height: 1.9;
        color: rgba(255,255,255,0.68);
    }
    .blog-section-body p { margin: 0 0 1.5rem; }
    .blog-section-body p:last-child { margin-bottom: 0; }
    .blog-section-body strong { color: rgba(255,255,255,0.9); font-weight: 600; }
    .blog-section-body em { color: rgba(255,255,255,0.8); font-style: italic; }
    .blog-section-body a { color: #93c5fd; text-decoration: underline; }
    .blog-section-body a:hover { color: white; }

    /* Code */
    .blog-section-body code {
        background: #1a1a2e;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        color: #e2e8f0;
    }
    .blog-section-body pre {
        background: #0d1117;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 1.5rem;
        border-radius: 12px;
        overflow-x: auto;
        margin: 1.5rem 0;
    }
    .blog-section-body pre code { background: none; border: none; padding: 0; font-size: 0.875rem; line-height: 1.7; color: #e2e8f0; }

    /* Tables */
    .blog-section-body table { border-collapse: collapse; width: 100%; margin: 1.5rem 0; font-size: 0.9rem; border-radius: 10px; overflow: hidden; }
    .blog-section-body table th { background: rgba(255,255,255,0.06); font-weight: 600; color: #fff; text-align: left; padding: 0.75rem 1rem; }
    .blog-section-body table td { border: 1px solid rgba(255,255,255,0.07); padding: 0.65rem 1rem; color: rgba(255,255,255,0.65); }
    .blog-section-body table tr:nth-child(even) td { background: rgba(255,255,255,0.02); }

    /* Blockquote */
    .blog-section-body blockquote {
        border-left: 3px solid #3b82f6;
        padding: 0.75rem 1.5rem;
        margin: 1.5rem 0;
        background: rgba(59,130,246,0.06);
        border-radius: 0 10px 10px 0;
        font-style: italic;
        color: rgba(255,255,255,0.7);
    }

    /* Lists */
    .blog-section-body ul, .blog-section-body ol {
        padding-left: 1.75rem;
        margin: 1rem 0 1.5rem;
    }
    .blog-section-body li { margin-bottom: 0.5rem; color: rgba(255,255,255,0.68); line-height: 1.7; }

    /* ── Visual Cards (from seeder HTML) ── */
    .blog-section-body .visual-card {
        background: linear-gradient(145deg, #161616, #111111);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 1.75rem;
        margin: 2rem 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    }
    .blog-section-body .visual-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .blog-section-body .visual-card-title i { color: #60a5fa; }
    .blog-section-body .comparison-box {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .blog-section-body .comparison-col {
        background: rgba(255,255,255,0.03);
        border-radius: 10px;
        padding: 1.25rem;
    }
    .blog-section-body .pros { border-top: 3px solid #4ade80; }
    .blog-section-body .cons { border-top: 3px solid #f87171; }
    .blog-section-body .comparison-header {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .blog-section-body .pros .comparison-header { color: #4ade80; }
    .blog-section-body .cons .comparison-header { color: #f87171; }
    .blog-section-body .comparison-list { list-style: none; padding: 0; margin: 0; }
    .blog-section-body .comparison-list li {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.65);
        margin-bottom: 0.65rem;
        padding-left: 1.4rem;
        position: relative;
        line-height: 1.5;
    }
    .blog-section-body .pros .comparison-list li::before { content: "✓"; position: absolute; left: 0; color: #4ade80; font-weight: 700; }
    .blog-section-body .cons .comparison-list li::before { content: "✗"; position: absolute; left: 0; color: #f87171; font-weight: 700; }
    .blog-section-body .process-flow { display: flex; flex-direction: column; gap: 1rem; }
    .blog-section-body .process-step-item {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        background: rgba(255,255,255,0.03);
        border-radius: 10px;
        padding: 1rem 1.25rem;
    }
    .blog-section-body .step-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: white;
        font-weight: 700;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .blog-section-body .step-content h4 { font-size: 0.95rem; font-weight: 700; color: #fff; margin: 0 0 0.35rem; }
    .blog-section-body .step-content p { font-size: 0.875rem; color: rgba(255,255,255,0.6); margin: 0; line-height: 1.6; }
    .blog-section-body .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 1rem;
    }
    .blog-section-body .metric-item {
        background: rgba(255,255,255,0.03);
        border-radius: 10px;
        padding: 1.25rem;
        text-align: center;
        border: 1px solid rgba(255,255,255,0.06);
    }
    .blog-section-body .metric-value { font-size: 2rem; font-weight: 800; color: #60a5fa; line-height: 1; margin-bottom: 0.4rem; }
    .blog-section-body .metric-label { font-size: 0.8rem; color: rgba(255,255,255,0.5); font-weight: 500; }

    /* ── RIGHT: Sidebar ── */
    .blog-right-sidebar {
        position: sticky;
        top: 90px;
    }
    .sidebar-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 14px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .sidebar-card-title {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: rgba(255,255,255,0.35);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    /* ── Mobile Responsive ── */
    @media (max-width: 1200px) {
        .blog-three-col {
            grid-template-columns: 220px 1fr;
        }
        .blog-right-sidebar { display: none; }
    }
    @media (max-width: 900px) {
        .blog-three-col {
            grid-template-columns: 1fr;
        }
        .blog-toc-sidebar {
            position: static;
            max-height: none;
            overflow: visible;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px;
            padding: 1.25rem;
        }
        .toc-list {
            flex-direction: row;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .toc-list li a {
            border-left: none;
            border-bottom: 2px solid transparent;
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
            background: rgba(255,255,255,0.04);
            border-radius: 20px;
        }
        .toc-list li a.toc-active {
            border-left: none;
            border-bottom: none;
            background: rgba(96,165,250,0.15);
        }
        .blog-page-wrapper { padding: 1.5rem 1rem 4rem; }
        .blog-section-heading { font-size: 1.35rem; }
        .blog-section-body .comparison-box { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<div id="readingProgress"></div>

<div class="blog-page-wrapper">

    {{-- Breadcrumb --}}
    <div style="margin-bottom: 1.5rem; font-size: 0.85rem; color: rgba(255,255,255,0.35);">
        <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.35); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='rgba(255,255,255,0.7)'" onmouseout="this.style.color='rgba(255,255,255,0.35)'">Home</a>
        <span style="margin: 0 0.5rem;">›</span>
        <a href="{{ route('blog') }}" style="color: rgba(255,255,255,0.35); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='rgba(255,255,255,0.7)'" onmouseout="this.style.color='rgba(255,255,255,0.35)'">Blog</a>
        <span style="margin: 0 0.5rem;">›</span>
        <span style="color: rgba(255,255,255,0.6);">{{ Str::limit($post->title, 55) }}</span>
    </div>

    {{-- Category + Title + Meta ── ABOVE the 3-col layout ── --}}
    <div style="max-width: 900px; margin-bottom: 2rem;">
        @if($post->category)
        <a href="{{ route('blog.category', $post->category->slug) }}" style="display: inline-block; background: rgba(96,165,250,0.12); border: 1px solid rgba(96,165,250,0.3); color: #93c5fd; padding: 0.3rem 0.9rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem; text-decoration: none;">{{ $post->category->name }}</a>
        @endif

        <h1 style="font-size: clamp(1.75rem, 4vw, 2.75rem); font-weight: 800; line-height: 1.2; margin: 0 0 1.5rem; letter-spacing: -0.75px; color: #ffffff;">{{ $post->title }}</h1>

        <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.07);">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <img src="{{ asset('images/gopi-profile.png') }}" alt="Gopi K" style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(255,255,255,0.15);">
                <span style="font-size: 0.875rem; color: rgba(255,255,255,0.7); font-weight: 500;">{{ $post->author->name ?? 'Gopi K' }}</span>
            </div>
            <span style="font-size: 0.85rem; color: rgba(255,255,255,0.4);"><i class="fas fa-calendar-alt" style="margin-right: 0.35rem;"></i>{{ $post->published_at?->format('M d, Y') }}</span>
            <span style="font-size: 0.85rem; color: rgba(255,255,255,0.4);"><i class="fas fa-eye" style="margin-right: 0.35rem;"></i>{{ number_format($post->views_count) }} views</span>
            <span style="font-size: 0.85rem; color: rgba(255,255,255,0.4);"><i class="fas fa-clock" style="margin-right: 0.35rem;"></i><span id="readingTime">...</span> min read</span>
        </div>
    </div>

    @php
        // Parse content into sections by H2
        $rawContent = $post->content;
        $parts = preg_split('/(<h2[^>]*>.*?<\/h2>)/is', $rawContent, -1, PREG_SPLIT_DELIM_CAPTURE);
        $intro = '';
        if (count($parts) > 0 && !preg_match('/^<h2/i', trim($parts[0]))) {
            $intro = array_shift($parts);
        }
        $sections = [];
        for ($i = 0; $i < count($parts); $i += 2) {
            $headingHtml = $parts[$i] ?? '';
            $bodyHtml    = $parts[$i+1] ?? '';
            preg_match('/<h2[^>]*>(.*?)<\/h2>/is', $headingHtml, $m);
            $headingText = strip_tags($m[1] ?? 'Section');
            $anchorId = 'section-' . ($i/2 + 1) . '-' . Str::slug(Str::limit($headingText, 30));
            $sections[] = [
                'id'      => $anchorId,
                'heading' => $headingText,
                'body'    => $bodyHtml,
            ];
        }
    @endphp

    {{-- Three-Column Layout --}}
    <div class="blog-three-col">

        {{-- LEFT: Sticky TOC --}}
        <aside class="blog-toc-sidebar" aria-label="Table of Contents">
            <div class="toc-label">On This Page</div>
            <ul class="toc-list" id="tocList">
                @if(!empty(trim($intro)))
                <li><a href="#blog-intro" class="toc-link">Introduction</a></li>
                @endif
                @foreach($sections as $section)
                <li><a href="#{{ $section['id'] }}" class="toc-link">{{ $section['heading'] }}</a></li>
                @endforeach
            </ul>
        </aside>

        {{-- CENTER: Main Content --}}
        <main class="blog-main-content" id="blogContent">

            {{-- Hero Image --}}
            @if($post->featured_image)
            <div class="blog-hero-image">
                <img src="{{ $post->featured_image }}"
                     alt="{{ $post->title }}"
                     loading="eager"
                     onerror="this.parentElement.style.display='none'">
            </div>
            @endif

            <div class="blog-content-sections">

                {{-- Intro paragraph(s) --}}
                @if(!empty(trim($intro)))
                <div class="blog-intro-text" id="blog-intro">
                    {!! $intro !!}
                </div>
                @endif

                {{-- Sections --}}
                @foreach($sections as $section)
                <div class="blog-section-block" id="{{ $section['id'] }}">
                    <h2 class="blog-section-heading">{{ $section['heading'] }}</h2>
                    <div class="blog-section-body">
                        {!! $section['body'] !!}
                    </div>
                </div>
                @endforeach

            </div>

            {{-- Comments --}}
            <div style="margin-top: 5rem; border-top: 1px solid rgba(255,255,255,0.07); padding-top: 3rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem; color: #fff;">Comments ({{ $post->comments->count() }})</h2>

                @if($post->comments->isNotEmpty())
                <div style="display: flex; flex-direction: column; gap: 1.25rem; margin-bottom: 3rem;">
                    @foreach($post->comments as $comment)
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #8b5cf6); display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 0.875rem; flex-shrink: 0;">
                                {{ strtoupper(substr($comment->user?->name ?? $comment->visitor_name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-size: 0.875rem; font-weight: 600; color: #fff; margin: 0;">{{ $comment->user?->name ?? $comment->visitor_name ?? 'Anonymous' }}</p>
                                <p style="font-size: 0.75rem; color: rgba(255,255,255,0.35); margin: 0;">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p style="color: rgba(255,255,255,0.65); font-size: 0.95rem; margin: 0; line-height: 1.7;">{{ $comment->comment }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <p style="color: rgba(255,255,255,0.35); font-style: italic; margin-bottom: 2rem;">No comments yet. Be the first to share your thoughts!</p>
                @endif

                {{-- Comment Form --}}
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; padding: 2rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 1.5rem; color: #fff;">Leave a Comment</h3>
                    @if(session('comment_success'))
                    <div style="background: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.3); color: #4ade80; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                        {{ session('comment_success') }}
                    </div>
                    @endif
                    <form method="POST" action="{{ route('blog.comment', $post->slug) }}">
                        @csrf
                        @if(!auth()->check())
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem; display: block; color: rgba(255,255,255,0.5);">Your Name</label>
                                <input type="text" name="visitor_name" placeholder="John Doe" value="{{ old('visitor_name') }}" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 0.7rem 1rem; border-radius: 8px; width: 100%; font-size: 0.9rem;">
                            </div>
                            <div>
                                <label style="font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem; display: block; color: rgba(255,255,255,0.5);">Your Email</label>
                                <input type="email" name="visitor_email" placeholder="john@example.com" value="{{ old('visitor_email') }}" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 0.7rem 1rem; border-radius: 8px; width: 100%; font-size: 0.9rem;">
                            </div>
                        </div>
                        @endif
                        <div style="margin-bottom: 1.25rem;">
                            <label style="font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem; display: block; color: rgba(255,255,255,0.5);">Comment *</label>
                            <textarea name="comment" rows="4" placeholder="Share your thoughts, ideas, or feedback..." required style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 0.7rem 1rem; border-radius: 8px; width: 100%; resize: vertical; font-size: 0.9rem; line-height: 1.6;">{{ old('comment') }}</textarea>
                            @error('comment')<p style="color: #f87171; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" style="background: linear-gradient(135deg, #3b82f6, #8b5cf6); color: white; font-weight: 600; border: none; padding: 0.75rem 1.75rem; border-radius: 8px; cursor: pointer; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                            <i class="fas fa-paper-plane"></i> Submit Comment
                        </button>
                    </form>
                </div>
            </div>
        </main>

        {{-- RIGHT: Sidebar --}}
        <aside class="blog-right-sidebar">
            {{-- Share Card --}}
            <div class="sidebar-card">
                <div class="sidebar-card-title">Share This Post</div>
                <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" style="display: flex; align-items: center; gap: 0.6rem; color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.875rem; padding: 0.5rem 0.75rem; border-radius: 8px; background: rgba(255,255,255,0.03); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.07)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                        <i class="fab fa-twitter" style="color: #1da1f2; width: 16px;"></i> Share on X
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" style="display: flex; align-items: center; gap: 0.6rem; color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.875rem; padding: 0.5rem 0.75rem; border-radius: 8px; background: rgba(255,255,255,0.03); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.07)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                        <i class="fab fa-linkedin" style="color: #0a66c2; width: 16px;"></i> Share on LinkedIn
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ request()->url() }}').then(()=>{this.textContent='Copied!';setTimeout(()=>{this.innerHTML='<i class=\'fas fa-link\' style=\'margin-right:0.4rem;\'></i>Copy Link'},1500)})" style="display: flex; align-items: center; gap: 0.6rem; color: rgba(255,255,255,0.6); font-size: 0.875rem; padding: 0.5rem 0.75rem; border-radius: 8px; background: rgba(255,255,255,0.03); border: none; cursor: pointer; width: 100%; text-align: left; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.07)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                        <i class="fas fa-link" style="color: rgba(255,255,255,0.4); width: 16px;"></i> Copy Link
                    </button>
                </div>
            </div>

            {{-- Related Posts --}}
            @if(isset($relatedPosts) && $relatedPosts->count())
            <div class="sidebar-card">
                <div class="sidebar-card-title">Related Posts</div>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($relatedPosts as $related)
                    <a href="{{ route('blog.show', $related->slug) }}" style="text-decoration: none; display: flex; gap: 0.75rem; align-items: flex-start;">
                        @if($related->featured_image)
                        <img src="{{ $related->featured_image }}" alt="{{ $related->title }}" style="width: 56px; height: 42px; object-fit: cover; border-radius: 6px; flex-shrink: 0; border: 1px solid rgba(255,255,255,0.07);">
                        @endif
                        <span style="font-size: 0.825rem; color: rgba(255,255,255,0.6); line-height: 1.4; transition: color 0.2s;" onmouseover="this.style.color='rgba(255,255,255,0.9)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">{{ Str::limit($related->title, 60) }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Newsletter --}}
            <div class="sidebar-card" style="background: linear-gradient(145deg, rgba(59,130,246,0.08), rgba(139,92,246,0.08)); border-color: rgba(96,165,250,0.2);">
                <div class="sidebar-card-title">Newsletter</div>
                <p style="font-size: 0.825rem; color: rgba(255,255,255,0.5); margin-bottom: 1rem; line-height: 1.5;">Get the latest posts on AI, automation & business growth.</p>
                <form method="POST" action="{{ route('newsletter.subscribe') }}">
                    @csrf
                    <input type="email" name="email" placeholder="your@email.com" required style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 0.6rem 0.9rem; border-radius: 8px; width: 100%; font-size: 0.85rem; margin-bottom: 0.75rem;">
                    <button type="submit" style="background: linear-gradient(135deg, #3b82f6, #8b5cf6); color: white; border: none; padding: 0.6rem 1rem; border-radius: 8px; width: 100%; font-size: 0.85rem; font-weight: 600; cursor: pointer;">Subscribe</button>
                </form>
            </div>
        </aside>

    </div>{{-- end .blog-three-col --}}
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Reading Time ──
    const content = document.getElementById('blogContent');
    if (content) {
        const words = content.textContent.trim().split(/\s+/).length;
        const mins  = Math.max(1, Math.ceil(words / 225));
        const el = document.getElementById('readingTime');
        if (el) el.textContent = mins;
    }

    // ── Reading Progress Bar ──
    const bar = document.getElementById('readingProgress');
    window.addEventListener('scroll', function () {
        const scrollTop  = window.scrollY;
        const docHeight  = document.documentElement.scrollHeight - window.innerHeight;
        const pct        = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        if (bar) bar.style.width = Math.min(100, pct) + '%';
    }, { passive: true });

    // ── TOC Active Highlight ──
    const tocLinks = document.querySelectorAll('.toc-link');
    const sections = [];
    tocLinks.forEach(link => {
        const id = link.getAttribute('href').replace('#', '');
        const el = document.getElementById(id);
        if (el) sections.push({ id, el, link });
    });

    function updateTOC() {
        let current = null;
        sections.forEach(s => {
            const rect = s.el.getBoundingClientRect();
            if (rect.top <= 120) current = s;
        });
        tocLinks.forEach(l => l.classList.remove('toc-active'));
        if (current) current.link.classList.add('toc-active');
    }

    window.addEventListener('scroll', updateTOC, { passive: true });
    updateTOC();

    // ── Smooth scroll for TOC links ──
    tocLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const id  = this.getAttribute('href').replace('#', '');
            const el  = document.getElementById(id);
            if (el) {
                const top = el.getBoundingClientRect().top + window.scrollY - 95;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });
});
</script>
@endpush
@endsection
