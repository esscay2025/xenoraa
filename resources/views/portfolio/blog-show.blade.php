@extends('layouts.app')
@section('title', $post->title . ' | Gopi K Blog')
@section('description', Str::limit(strip_tags($post->excerpt ?? $post->content), 160))
@push('styles')
<style>
    /* ── Blog Layout ── */
    .blog-layout {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 2rem;
    }

    /* ── Reading Progress Bar ── */
    #readingProgress {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #60a5fa, #3b82f6);
        width: 0%;
        z-index: 9999;
        transition: width 0.1s;
    }

    /* ── Split Section Layout ── */
    .blog-sections-container {
        display: flex;
        flex-direction: column;
        gap: 5rem;
        margin-top: 3rem;
    }
    .blog-intro-section {
        font-size: 1.2rem;
        line-height: 1.85;
        color: var(--text-primary);
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--border);
        padding-bottom: 3rem;
        font-weight: 400;
    }
    .blog-row-section {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 4rem;
        align-items: start;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding-bottom: 4rem;
    }
    .blog-row-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .blog-section-left {
        position: sticky;
        top: 100px;
    }
    .blog-section-left h2 {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1.3;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #ffffff 0%, #a0a0a0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .blog-section-right {
        font-size: 1.05rem;
        line-height: 1.9;
        color: var(--text-secondary);
    }
    .blog-section-right p {
        margin-top: 0;
        margin-bottom: 1.5rem;
    }
    .blog-section-right p:last-child {
        margin-bottom: 0;
    }

    /* ── Pictorial & Visual Styles ── */
    .visual-card {
        background: linear-gradient(145deg, #161616, #111111);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 2rem;
        margin: 2rem 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .visual-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .visual-card-title i {
        color: #60a5fa;
    }
    
    /* Process Steps (Pictorial Flow) */
    .process-flow {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        margin: 2rem 0;
    }
    .process-step-item {
        display: flex;
        gap: 1.5rem;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.05);
        padding: 1.5rem;
        border-radius: 12px;
        transition: transform 0.2s, border-color 0.2s;
    }
    .process-step-item:hover {
        transform: translateX(5px);
        border-color: rgba(255,255,255,0.1);
    }
    .step-badge {
        width: 40px;
        height: 40px;
        background: rgba(59,130,246,0.1);
        border: 1px solid rgba(59,130,246,0.2);
        color: #60a5fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .step-content h4 {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }
    .step-content p {
        font-size: 0.925rem;
        color: var(--text-secondary);
        margin: 0 !important;
        line-height: 1.6;
    }

    /* Metric Grid (Pictorial Stats) */
    .metric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }
    .metric-item {
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.05);
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
    }
    .metric-value {
        font-size: 2.25rem;
        font-weight: 800;
        color: #60a5fa;
        line-height: 1;
        margin-bottom: 0.5rem;
        font-family: monospace;
    }
    .metric-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Comparison Box (Pros & Cons) */
    .comparison-box {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin: 2rem 0;
    }
    .comparison-column {
        background: rgba(255,255,255,0.01);
        border: 1px solid rgba(255,255,255,0.05);
        padding: 1.5rem;
        border-radius: 12px;
    }
    .comparison-column.pros {
        border-top: 3px solid #22c55e;
    }
    .comparison-column.cons {
        border-top: 3px solid #ef4444;
    }
    .comparison-header {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .pros .comparison-header { color: #4ade80; }
    .cons .comparison-header { color: #f87171; }
    .comparison-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .comparison-list li {
        font-size: 0.925rem;
        color: var(--text-secondary);
        margin-bottom: 0.75rem;
        padding-left: 1.5rem;
        position: relative;
        line-height: 1.5;
    }
    .comparison-list li::before {
        position: absolute;
        left: 0;
        top: 2px;
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
    }
    .pros .comparison-list li::before {
        content: "\f00c";
        color: #4ade80;
    }
    .cons .comparison-list li::before {
        content: "\f00d";
        color: #f87171;
    }

    /* Code blocks, Tables & Quotes */
    .blog-section-right code {
        background: #1a1a1a;
        border: 1px solid var(--border);
        padding: 0.2rem 0.45rem;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        color: #e2e8f0;
    }
    .blog-section-right pre {
        background: #0d1117;
        border: 1px solid var(--border);
        padding: 1.25rem;
        border-radius: 10px;
        overflow-x: auto;
        margin: 1.5rem 0;
    }
    .blog-section-right pre code { background: none; border: none; padding: 0; font-size: 0.875rem; line-height: 1.6; }
    .blog-section-right table { border-collapse: collapse; width: 100%; margin: 1.5rem 0; font-size: 0.9rem; }
    .blog-section-right table th { background: var(--bg-card); font-weight: 600; color: var(--text-primary); text-align: left; }
    .blog-section-right table th, .blog-section-right table td { border: 1px solid var(--border); padding: 0.6rem 0.9rem; }
    .blog-section-right table tr:nth-child(even) td { background: rgba(255,255,255,0.02); }
    .blog-section-right blockquote {
        border-left: 3px solid rgba(255,255,255,0.3);
        padding: 0.75rem 1.25rem;
        margin: 1.5rem 0;
        background: rgba(255,255,255,0.03);
        border-radius: 0 8px 8px 0;
        font-style: italic;
        color: var(--text-secondary);
    }
    .blog-section-right a { color: #93c5fd; text-decoration: underline; }
    .blog-section-right a:hover { color: white; }

    /* ── Mobile ── */
    @media (max-width: 1024px) {
        .blog-layout { padding: 2rem 1rem; }
        .blog-row-section {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding-bottom: 3rem;
        }
        .blog-section-left {
            position: static;
        }
        .blog-section-left h2 {
            font-size: 1.45rem;
        }
        .comparison-box {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

{{-- Reading Progress Bar --}}
<div id="readingProgress"></div>

<div class="blog-layout">

    {{-- Breadcrumb --}}
    <div style="margin-bottom: 2rem; font-size: 0.875rem; color: var(--text-muted);">
        <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none;">Home</a>
        <span style="margin: 0 0.5rem;">/</span>
        <a href="{{ route('blog') }}" style="color: var(--text-muted); text-decoration: none;">Blog</a>
        <span style="margin: 0 0.5rem;">/</span>
        <span style="color: var(--text-secondary);">{{ Str::limit($post->title, 50) }}</span>
    </div>

    {{-- Category Badge --}}
    @if($post->category)
    <span style="display: inline-block; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); padding: 0.3rem 0.9rem; border-radius: 20px; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1rem; color: var(--text-secondary);">{{ $post->category->name }}</span>
    @endif

    {{-- Title --}}
    <h1 style="font-size: 2.75rem; font-weight: 800; line-height: 1.2; margin-bottom: 1.5rem; letter-spacing: -1px; color: var(--text-primary);">{{ $post->title }}</h1>

    {{-- Meta --}}
    <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--border); flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <img src="{{ asset('images/gopi-profile.png') }}" alt="Gopi K" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border);">
            <span style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">{{ $post->author->name }}</span>
        </div>
        <span style="font-size: 0.875rem; color: var(--text-muted);"><i class="fas fa-calendar-alt" style="margin-right: 0.3rem;"></i>{{ $post->published_at?->format('F d, Y') }}</span>
        <span style="font-size: 0.875rem; color: var(--text-muted);"><i class="fas fa-eye" style="margin-right: 0.3rem;"></i>{{ $post->views_count }} views</span>
        <span style="font-size: 0.875rem; color: var(--text-muted);"><i class="fas fa-clock" style="margin-right: 0.3rem;"></i><span id="readingTime">...</span> min read</span>
    </div>

    {{-- Featured Image --}}
    @if($post->featured_image)
    <div style="margin-bottom: 3rem; border-radius: 16px; overflow: hidden; border: 1px solid var(--border); box-shadow: 0 10px 40px rgba(0,0,0,0.4);">
        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" style="width: 100%; height: auto; max-height: 500px; object-fit: cover; display: block;">
    </div>
    @endif

    {{-- ── Split Section Content ── --}}
    <div class="blog-sections-container">
        @php
            $content = $post->content;
            // Parse content to split by H2 tags
            $parts = preg_split('/(<h2[^>]*>.*?<\/h2>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
            $intro = '';
            if (count($parts) > 0 && !str_starts_with(trim($parts[0]), '<h2')) {
                $intro = array_shift($parts);
            }
        @endphp

        @if(!empty(trim($intro)))
            <div class="blog-intro-section">
                {!! $intro !!}
            </div>
        @endif

        @for($i = 0; $i < count($parts); $i += 2)
            @php
                $headingHtml = $parts[$i] ?? '';
                $bodyHtml = $parts[$i+1] ?? '';
                
                // Extract plain text from heading tag
                preg_match('/<h2[^>]*>(.*?)<\/h2>/i', $headingHtml, $matches);
                $headingText = $matches[1] ?? 'Section';
            @endphp
            <div class="blog-row-section">
                <div class="blog-section-left">
                    <h2>{{ strip_tags($headingText) }}</h2>
                </div>
                <div class="blog-section-right">
                    {!! $bodyHtml !!}
                </div>
            </div>
        @endfor
    </div>

    {{-- Comments Section --}}
    <div style="margin-top: 5rem; border-top: 1px solid var(--border); padding-top: 3rem; max-width: 800px;">
        <h2 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 2rem;">Comments ({{ $post->comments->count() }})</h2>
        
        @if($post->comments->isEmpty())
        <p style="color: var(--text-muted); font-style: italic; margin-bottom: 2rem;">No comments yet. Be the first to share your thoughts!</p>
        @else
        <div style="display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 3rem;">
            @foreach($post->comments as $comment)
            <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-weight: 600; color: var(--text-primary);">
                        {{ strtoupper(substr($comment->user?->name ?? $comment->visitor_name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <p style="font-size: 0.875rem; font-weight: 600; color: var(--text-primary); margin: 0;">{{ $comment->user?->name ?? $comment->visitor_name ?? 'Anonymous' }}</p>
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <p style="color: var(--text-secondary); font-size: 0.95rem; margin: 0; line-height: 1.6;">{{ $comment->comment }}</p>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Comment Form --}}
        <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2rem;">
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.25rem; color: var(--text-primary);">Leave a Comment</h3>
            <form method="POST" action="{{ route('blog.comment', $post->slug) }}">
                @csrf
                @if(!auth()->check())
                <div class="grid-2" style="margin-bottom: 1.25rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; display: block; color: var(--text-secondary);">Your Name</label>
                        <input type="text" name="visitor_name" class="form-control" placeholder="John Doe" value="{{ old('visitor_name') }}" style="background: rgba(0,0,0,0.2); border: 1px solid var(--border); color: white; padding: 0.75rem 1rem; border-radius: 8px; width: 100%;">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; display: block; color: var(--text-secondary);">Your Email</label>
                        <input type="email" name="visitor_email" class="form-control" placeholder="john@example.com" value="{{ old('visitor_email') }}" style="background: rgba(0,0,0,0.2); border: 1px solid var(--border); color: white; padding: 0.75rem 1rem; border-radius: 8px; width: 100%;">
                    </div>
                </div>
                @endif
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; display: block; color: var(--text-secondary);">Comment *</label>
                    <textarea name="comment" class="form-control" rows="4" placeholder="Share your thoughts, ideas, or feedback..." required style="background: rgba(0,0,0,0.2); border: 1px solid var(--border); color: white; padding: 0.75rem 1rem; border-radius: 8px; width: 100%; resize: vertical;">{{ old('comment') }}</textarea>
                    @error('comment')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn btn-primary" style="background: white; color: black; font-weight: 600; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-paper-plane"></i> Submit Comment
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.getElementById('readingProgress');
    const container = document.querySelector('.blog-sections-container');
    
    // Calculate reading time
    const text = container ? container.textContent : '';
    const words = text.trim().split(/\s+/).length;
    const minutes = Math.max(1, Math.ceil(words / 225)); // average reading speed 225 wpm
    document.getElementById('readingTime').textContent = minutes;

    // Reading progress bar
    window.addEventListener('scroll', function() {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        progressBar.style.width = Math.min(100, progress) + '%';
    });
});
</script>
@endpush
@endsection
