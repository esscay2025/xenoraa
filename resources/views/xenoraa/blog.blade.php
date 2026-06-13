@extends('layouts.xenoraa')
@section('title', 'Blog — Xenoraa | Business Insights & Guides')
@section('meta_description', 'Expert guides, strategies, and insights to help Indian businesses grow with Xenoraa — covering CRM, E-Commerce, POS, and unified business platforms.')
@section('styles')
<style>
.xn-blog-hero { padding: 5rem 0 3.5rem; background: #000; border-bottom: 1px solid #111; }
.xn-blog-hero-label { display: inline-block; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #a855f7; background: rgba(168,85,247,0.08); border: 1px solid rgba(168,85,247,0.2); border-radius: 4px; padding: 0.3rem 0.75rem; margin-bottom: 1.25rem; }
.xn-blog-hero h1 { font-family: 'Space Grotesk', sans-serif; font-size: clamp(2rem, 5vw, 3.25rem); font-weight: 800; color: #fff; line-height: 1.15; margin-bottom: 1rem; }
.xn-blog-hero p { font-size: 1.05rem; color: #71717a; max-width: 560px; line-height: 1.7; }
.xn-blog-section { background: #000; padding: 3.5rem 0 5rem; }
.xn-blog-layout { display: grid; grid-template-columns: 1fr 340px; gap: 3rem; }
/* Featured post */
.xn-blog-featured { display: block; text-decoration: none; background: #0a0a0a; border: 1px solid #1a1a1a; border-radius: 12px; overflow: hidden; transition: border-color 0.2s; margin-bottom: 2.5rem; }
.xn-blog-featured:hover { border-color: rgba(168,85,247,0.4); }
.xn-blog-featured-img { width: 100%; height: 340px; object-fit: cover; display: block; }
.xn-blog-featured-img-placeholder { width: 100%; height: 340px; background: linear-gradient(135deg, #0d0d0d, #1a0a2e); display: flex; align-items: center; justify-content: center; color: rgba(168,85,247,0.3); font-size: 4rem; }
.xn-blog-featured-body { padding: 2rem 2.5rem 2.5rem; }
.xn-blog-cat-pill { display: inline-block; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #a855f7; background: rgba(168,85,247,0.08); border: 1px solid rgba(168,85,247,0.15); border-radius: 4px; padding: 0.25rem 0.65rem; margin-bottom: 1rem; }
.xn-blog-featured-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.6rem; font-weight: 700; color: #fff; line-height: 1.3; margin-bottom: 0.875rem; }
.xn-blog-featured-excerpt { font-size: 0.9rem; color: #71717a; line-height: 1.7; margin-bottom: 1.25rem; }
.xn-blog-featured-meta { display: flex; align-items: center; gap: 1.25rem; font-size: 0.775rem; color: #52525b; }
.xn-blog-featured-meta .read-more { color: #a855f7; font-weight: 600; margin-left: auto; }
/* Grid posts */
.xn-blog-grid-posts { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
.xn-blog-card { display: block; text-decoration: none; background: #0a0a0a; border: 1px solid #1a1a1a; border-radius: 10px; overflow: hidden; transition: border-color 0.2s, transform 0.2s; }
.xn-blog-card:hover { border-color: rgba(168,85,247,0.35); transform: translateY(-2px); }
.xn-blog-card-img { width: 100%; height: 180px; object-fit: cover; display: block; }
.xn-blog-card-img-placeholder { width: 100%; height: 180px; background: linear-gradient(135deg, #0d0d0d, #1a0a2e); display: flex; align-items: center; justify-content: center; color: rgba(168,85,247,0.25); font-size: 2rem; }
.xn-blog-card-body { padding: 1.25rem 1.5rem 1.5rem; }
.xn-blog-card-cat { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #7c3aed; margin-bottom: 0.6rem; }
.xn-blog-card-title { font-family: 'Space Grotesk', sans-serif; font-size: 0.95rem; font-weight: 700; color: #e4e4e7; line-height: 1.4; margin-bottom: 0.6rem; }
.xn-blog-card-excerpt { font-size: 0.8rem; color: #52525b; line-height: 1.6; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.xn-blog-card-meta { font-size: 0.7rem; color: #3f3f46; display: flex; gap: 0.75rem; }
/* Sidebar */
.xn-blog-sidebar { position: sticky; top: 80px; }
.xn-sidebar-box { background: #0a0a0a; border: 1px solid #1a1a1a; border-radius: 10px; padding: 1.75rem; margin-bottom: 1.5rem; }
.xn-sidebar-heading { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #52525b; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #111; }
.xn-sidebar-post-link { display: flex; gap: 0.875rem; margin-bottom: 1.25rem; text-decoration: none; align-items: flex-start; }
.xn-sidebar-post-link:last-child { margin-bottom: 0; }
.xn-sidebar-post-num { font-family: 'Space Grotesk', sans-serif; font-size: 1.4rem; font-weight: 800; color: #1f1f1f; flex-shrink: 0; line-height: 1; width: 28px; }
.xn-sidebar-post-title { font-size: 0.8rem; font-weight: 600; color: #71717a; line-height: 1.45; transition: color 0.2s; }
.xn-sidebar-post-link:hover .xn-sidebar-post-title { color: #d4d4d8; }
.xn-sidebar-cta { background: linear-gradient(135deg, rgba(124,58,237,0.12), rgba(168,85,247,0.06)); border: 1px solid rgba(168,85,247,0.2); border-radius: 10px; padding: 1.75rem; text-align: center; }
.xn-sidebar-cta h4 { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; }
.xn-sidebar-cta p { font-size: 0.8rem; color: #71717a; line-height: 1.6; margin-bottom: 1.25rem; }
.xn-sidebar-cta-btn { display: inline-block; background: #7c3aed; color: #fff; font-size: 0.8rem; font-weight: 700; padding: 0.625rem 1.25rem; border-radius: 6px; text-decoration: none; transition: background 0.2s; }
.xn-sidebar-cta-btn:hover { background: #6d28d9; }
@media(max-width:1024px) { .xn-blog-layout { grid-template-columns: 1fr; } .xn-blog-sidebar { position: static; } }
@media(max-width:640px) { .xn-blog-grid-posts { grid-template-columns: 1fr; } .xn-blog-featured-body { padding: 1.5rem; } }
</style>
@endsection
@section('content')
{{-- Hero --}}
<section class="xn-blog-hero">
    <div class="xn-container">
        <div class="xn-blog-hero-label">Blog</div>
        <h1>Insights for the<br><span style="color:#a855f7;">Modern Business Owner</span></h1>
        <p>Practical guides, strategic frameworks, and real-world insights to help Indian businesses grow smarter with the right tools.</p>
    </div>
</section>

{{-- Blog Content --}}
<section class="xn-blog-section">
    <div class="xn-container">
        <div class="xn-blog-layout">
            {{-- Main Content --}}
            <div>
                {{-- Featured Post --}}
                @if($featured)
                @php
                    $featImgSrc = $featured->featured_image
                        ? (str_starts_with($featured->featured_image, 'http') ? $featured->featured_image : asset('storage/' . $featured->featured_image))
                        : null;
                    $readTime = max(3, (int)(str_word_count(strip_tags($featured->content ?? '')) / 200));
                @endphp
                <a href="{{ route('xenoraa.blog.show', $featured->slug) }}" class="xn-blog-featured">
                    @if($featImgSrc)
                        <img src="{{ $featImgSrc }}" alt="{{ $featured->title }}" class="xn-blog-featured-img">
                    @else
                        <div class="xn-blog-featured-img-placeholder"><i class="fas fa-newspaper"></i></div>
                    @endif
                    <div class="xn-blog-featured-body">
                        <div class="xn-blog-cat-pill">Featured · Business Insights</div>
                        <div class="xn-blog-featured-title">{{ $featured->title }}</div>
                        <div class="xn-blog-featured-excerpt">{{ $featured->summary ?? Str::limit(strip_tags($featured->content), 160) }}</div>
                        <div class="xn-blog-featured-meta">
                            <span><i class="fas fa-calendar" style="margin-right:0.35rem;"></i>{{ $featured->published_at ? $featured->published_at->format('M d, Y') : date('M d, Y') }}</span>
                            <span><i class="fas fa-clock" style="margin-right:0.35rem;"></i>{{ $readTime }} min read</span>
                            <span class="read-more">Read Article →</span>
                        </div>
                    </div>
                </a>
                @endif

                {{-- Grid Posts --}}
                <div class="xn-blog-grid-posts">
                    @foreach($gridPosts as $post)
                    @php
                        $imgSrc = $post->featured_image
                            ? (str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . $post->featured_image))
                            : null;
                        $rt = max(3, (int)(str_word_count(strip_tags($post->content ?? '')) / 200));
                    @endphp
                    <a href="{{ route('xenoraa.blog.show', $post->slug) }}" class="xn-blog-card">
                        @if($imgSrc)
                            <img src="{{ $imgSrc }}" alt="{{ $post->title }}" class="xn-blog-card-img">
                        @else
                            <div class="xn-blog-card-img-placeholder"><i class="fas fa-file-alt"></i></div>
                        @endif
                        <div class="xn-blog-card-body">
                            <div class="xn-blog-card-cat">Business &amp; SaaS</div>
                            <div class="xn-blog-card-title">{{ $post->title }}</div>
                            <div class="xn-blog-card-excerpt">{{ $post->summary ?? Str::limit(strip_tags($post->content), 100) }}</div>
                            <div class="xn-blog-card-meta">
                                <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : date('M d, Y') }}</span>
                                <span>{{ $rt }} min read</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="xn-blog-sidebar">
                {{-- Popular Posts --}}
                <div class="xn-sidebar-box">
                    <div class="xn-sidebar-heading">Popular Articles</div>
                    @foreach($posts->take(5) as $i => $sp)
                    <a href="{{ route('xenoraa.blog.show', $sp->slug) }}" class="xn-sidebar-post-link">
                        <span class="xn-sidebar-post-num">0{{ $i + 1 }}</span>
                        <span class="xn-sidebar-post-title">{{ $sp->title }}</span>
                    </a>
                    @endforeach
                </div>
                {{-- Topics --}}
                <div class="xn-sidebar-box">
                    <div class="xn-sidebar-heading">Topics</div>
                    @php
                    $topics = [
                        ['name' => 'Business Strategy', 'count' => 3],
                        ['name' => 'CRM & Sales', 'count' => 2],
                        ['name' => 'E-Commerce', 'count' => 2],
                        ['name' => 'Platform Guides', 'count' => 2],
                        ['name' => 'POS & Inventory', 'count' => 1],
                    ];
                    @endphp
                    @foreach($topics as $topic)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:0.6rem 0;border-bottom:1px solid #111;font-size:0.825rem;color:#71717a;">
                        <span>{{ $topic['name'] }}</span>
                        <span style="background:rgba(124,58,237,0.1);color:#7c3aed;font-size:0.7rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:4px;">{{ $topic['count'] }}</span>
                    </div>
                    @endforeach
                </div>
                {{-- CTA --}}
                <div class="xn-sidebar-cta">
                    <h4>Ready to Simplify Your Business?</h4>
                    <p>Join hundreds of businesses already running on Xenoraa's unified platform.</p>
                    <a href="{{ route('xenoraa.get-started') }}" class="xn-sidebar-cta-btn">Start Free Trial →</a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
