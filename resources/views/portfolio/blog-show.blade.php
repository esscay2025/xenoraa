@extends('layouts.app')
@section('title', $post->title . ' | Gopi K Blog')
@section('description', Str::limit(strip_tags($post->excerpt ?? $post->content), 160))

@push('styles')
<style>
    /* ── Blog Layout ── */
    .blog-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 3rem;
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 2rem;
        align-items: start;
    }

    /* ── Table of Contents (Left Sidebar) ── */
    .toc-sidebar {
        position: sticky;
        top: 90px;
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.25rem;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }
    .toc-sidebar::-webkit-scrollbar { width: 4px; }
    .toc-sidebar::-webkit-scrollbar-track { background: transparent; }
    .toc-sidebar::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }
    .toc-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--text-muted);
        margin: 0 0 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border);
    }
    .toc-list { list-style: none; padding: 0; margin: 0; }
    .toc-list li { margin-bottom: 0.15rem; }
    .toc-list a {
        display: block;
        padding: 0.3rem 0.5rem;
        font-size: 0.82rem;
        color: var(--text-secondary);
        text-decoration: none;
        border-radius: 5px;
        transition: all 0.15s;
        border-left: 2px solid transparent;
        line-height: 1.4;
    }
    .toc-list a:hover { color: white; background: rgba(255,255,255,0.05); }
    .toc-list a.active { color: white; border-left-color: white; background: rgba(255,255,255,0.07); }
    .toc-h1 { padding-left: 0.5rem !important; font-weight: 600; }
    .toc-h2 { padding-left: 0.5rem !important; font-weight: 600; }
    .toc-h3 { padding-left: 1.25rem !important; }
    .toc-h4 { padding-left: 2rem !important; font-size: 0.78rem !important; color: var(--text-muted) !important; }
    .toc-h5, .toc-h6 { padding-left: 2.5rem !important; font-size: 0.75rem !important; color: var(--text-muted) !important; }
    .toc-empty { font-size: 0.82rem; color: var(--text-muted); font-style: italic; }

    /* ── Blog Content ── */
    .blog-article { min-width: 0; }
    .blog-content {
        color: var(--text-secondary);
        line-height: 1.85;
        font-size: 1.05rem;
        margin-bottom: 3rem;
    }
    .blog-content h1, .blog-content h2, .blog-content h3,
    .blog-content h4, .blog-content h5, .blog-content h6 {
        color: var(--text-primary);
        font-weight: 700;
        margin: 2rem 0 1rem;
        line-height: 1.3;
        scroll-margin-top: 90px;
    }
    .blog-content h1 { font-size: 2rem; }
    .blog-content h2 { font-size: 1.6rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; }
    .blog-content h3 { font-size: 1.3rem; }
    .blog-content h4 { font-size: 1.1rem; }
    .blog-content h5 { font-size: 1rem; }
    .blog-content h6 { font-size: 0.9rem; color: var(--text-secondary); }
    .blog-content p { margin: 0 0 1.25rem; }
    .blog-content ul, .blog-content ol { margin: 0 0 1.25rem 1.75rem; }
    .blog-content li { margin-bottom: 0.4rem; }
    .blog-content blockquote {
        border-left: 3px solid rgba(255,255,255,0.3);
        padding: 0.75rem 1.25rem;
        margin: 1.5rem 0;
        background: rgba(255,255,255,0.03);
        border-radius: 0 8px 8px 0;
        font-style: italic;
        color: var(--text-secondary);
    }
    .blog-content code {
        background: #1a1a1a;
        border: 1px solid var(--border);
        padding: 0.2rem 0.45rem;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        color: #e2e8f0;
    }
    .blog-content pre {
        background: #0d1117;
        border: 1px solid var(--border);
        padding: 1.25rem;
        border-radius: 10px;
        overflow-x: auto;
        margin: 1.5rem 0;
        position: relative;
    }
    .blog-content pre code { background: none; border: none; padding: 0; font-size: 0.875rem; line-height: 1.6; }
    .blog-content table { border-collapse: collapse; width: 100%; margin: 1.5rem 0; font-size: 0.9rem; }
    .blog-content table th { background: var(--bg-card); font-weight: 600; color: var(--text-primary); }
    .blog-content table th, .blog-content table td { border: 1px solid var(--border); padding: 0.6rem 0.9rem; }
    .blog-content table tr:nth-child(even) td { background: rgba(255,255,255,0.02); }
    .blog-content img { max-width: 100%; border-radius: 10px; margin: 1rem 0; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
    .blog-content img.align-left { float: left; margin-right: 1.5rem; margin-bottom: 0.5rem; max-width: 45%; }
    .blog-content img.align-right { float: right; margin-left: 1.5rem; margin-bottom: 0.5rem; max-width: 45%; }
    .blog-content img.align-center { display: block; margin: 1rem auto; }
    .blog-content a { color: #93c5fd; text-decoration: underline; }
    .blog-content a:hover { color: white; }
    .blog-content hr { border: none; border-top: 1px solid var(--border); margin: 2rem 0; }
    .blog-content iframe { max-width: 100%; border-radius: 10px; }

    /* ── Reading Progress Bar ── */
    #readingProgress {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: white;
        width: 0%;
        z-index: 9999;
        transition: width 0.1s;
    }

    /* ── Mobile ── */
    @media (max-width: 900px) {
        .blog-layout { grid-template-columns: 1fr; }
        .toc-sidebar { position: static; max-height: none; }
        .toc-sidebar { display: none; }
        .toc-mobile-toggle { display: flex !important; }
    }
    .toc-mobile-toggle { display: none; }
</style>
@endpush

@section('content')

{{-- Reading Progress Bar --}}
<div id="readingProgress"></div>

<div class="blog-layout">

    {{-- ── LEFT: Table of Contents ── --}}
    <aside class="toc-sidebar" id="tocSidebar">
        <p class="toc-title"><i class="fas fa-list" style="margin-right: 0.4rem;"></i> Contents</p>
        <ul class="toc-list" id="tocList">
            <li><span class="toc-empty">Loading...</span></li>
        </ul>
    </aside>

    {{-- ── RIGHT: Article ── --}}
    <article class="blog-article">

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
        <h1 style="font-size: 2.5rem; font-weight: 800; line-height: 1.2; margin-bottom: 1.5rem; letter-spacing: -0.5px;">{{ $post->title }}</h1>

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
        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" style="width: 100%; border-radius: 12px; margin-bottom: 2rem; max-height: 450px; object-fit: cover;">
        @endif

        {{-- Mobile TOC Toggle --}}
        <button class="toc-mobile-toggle btn btn-outline" onclick="document.getElementById('tocSidebar').style.display = document.getElementById('tocSidebar').style.display === 'block' ? 'none' : 'block';" style="margin-bottom: 1.5rem; font-size: 0.875rem;">
            <i class="fas fa-list"></i> Table of Contents
        </button>

        {{-- Post Content --}}
        <div class="blog-content" id="blogContent">
            {!! $post->content !!}
        </div>

        {{-- Tags / Share --}}
        <div style="padding: 1.5rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; margin-bottom: 3rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <span style="font-weight: 600;">Share this post</span>
            <div style="display: flex; gap: 0.75rem;">
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fab fa-x-twitter"></i></a>
                <a href="https://www.linkedin.com/shareArticle?url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fab fa-facebook-f"></i></a>
                <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); this.innerHTML='<i class=\'fas fa-check\'></i>'; setTimeout(()=>this.innerHTML='<i class=\'fas fa-link\'></i>',2000);" class="btn btn-outline btn-sm"><i class="fas fa-link"></i></button>
            </div>
        </div>

        {{-- Comments Section --}}
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem;">
                Comments ({{ $comments->count() }})
            </h2>

            @if($comments->count() > 0)
            <div style="margin-bottom: 2rem;">
                @foreach($comments as $comment)
                <div style="padding: 1.25rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--bg-secondary); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-user" style="font-size: 0.875rem; color: var(--text-secondary);"></i>
                        </div>
                        <div>
                            <p style="font-weight: 600; font-size: 0.9rem; margin: 0;">{{ $comment->user?->name ?? $comment->visitor_name ?? 'Anonymous' }}</p>
                            <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin: 0;">{{ $comment->comment }}</p>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Comment Form --}}
            <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1.25rem;">Leave a Comment</h3>
                <form method="POST" action="{{ route('blog.comment', $post->slug) }}">
                    @csrf
                    @if(!auth()->check())
                    <div class="grid-2" style="margin-bottom: 1rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="visitor_name" class="form-control" placeholder="John Doe" value="{{ old('visitor_name') }}">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Your Email</label>
                            <input type="email" name="visitor_email" class="form-control" placeholder="john@example.com" value="{{ old('visitor_email') }}">
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label">Comment *</label>
                        <textarea name="comment" class="form-control" rows="4" placeholder="Share your thoughts, ideas, or feedback..." required>{{ old('comment') }}</textarea>
                        @error('comment')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Comment
                    </button>
                </form>
            </div>
        </div>

    </article>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('blogContent');
    const tocList = document.getElementById('tocList');
    const progressBar = document.getElementById('readingProgress');

    // ── Build Table of Contents ──
    const headings = content.querySelectorAll('h1, h2, h3, h4, h5, h6');
    
    if (headings.length === 0) {
        tocList.innerHTML = '<li><span class="toc-empty">No headings found</span></li>';
    } else {
        tocList.innerHTML = '';
        headings.forEach(function(heading, index) {
            // Add ID to heading for anchor linking
            const id = 'heading-' + index + '-' + heading.tagName.toLowerCase();
            heading.id = id;

            const level = heading.tagName.toLowerCase();
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#' + id;
            a.textContent = heading.textContent;
            a.className = 'toc-' + level;
            a.dataset.target = id;

            a.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.getElementById(id);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });

            li.appendChild(a);
            tocList.appendChild(li);
        });
    }

    // ── Reading Time ──
    const text = content.textContent || '';
    const words = text.trim().split(/\s+/).length;
    const minutes = Math.max(1, Math.ceil(words / 200));
    document.getElementById('readingTime').textContent = minutes;

    // ── Reading Progress Bar ──
    window.addEventListener('scroll', function() {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        progressBar.style.width = Math.min(100, progress) + '%';

        // ── Active TOC Highlight ──
        let activeId = null;
        headings.forEach(function(heading) {
            const rect = heading.getBoundingClientRect();
            if (rect.top <= 100) {
                activeId = heading.id;
            }
        });

        tocList.querySelectorAll('a').forEach(function(a) {
            a.classList.toggle('active', a.dataset.target === activeId);
        });
    });
});
</script>
@endpush

@endsection
