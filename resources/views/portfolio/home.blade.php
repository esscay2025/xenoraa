@extends('layouts.app')

@php
    $siteSettings = \App\Models\SiteSetting::getSettings();
@endphp

@section('title', ($siteSettings['owner_name'] ?? 'Gopi K') . ' | Founder of ' . ($siteSettings['company_name'] ?? 'Go Esscay Solutions'))
@section('description', 'Personal portfolio of ' . ($siteSettings['owner_name'] ?? 'Gopi K') . ' - Founder of ' . ($siteSettings['company_name'] ?? 'Go Esscay Solutions') . '. IT, Automation & Open-Source Expert.')

@push('styles')
<style>
    .hero {
        min-height: 90vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #0a0a0a 0%, #111111 50%, #0a0a0a 100%);
        position: relative;
        overflow: hidden;
        padding: 4rem 2rem;
    }
    .hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 70%);
        border-radius: 50%;
    }
    .hero-inner {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }
    .hero-badge::before {
        content: '';
        width: 8px;
        height: 8px;
        background: var(--success);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
    .hero h1 {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.1;
        margin: 0 0 1.5rem;
        letter-spacing: -1px;
    }
    .hero h1 span { color: var(--text-secondary); }
    .hero p { font-size: 1.1rem; color: var(--text-secondary); max-width: 500px; margin-bottom: 2rem; }
    .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
    .hero-image {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .hero-avatar {
        width: 340px;
        height: 340px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
        border: 3px solid rgba(255,255,255,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8rem;
        color: var(--text-secondary);
        position: relative;
        overflow: hidden;
        box-shadow: 0 0 60px rgba(255,255,255,0.05), 0 20px 60px rgba(0,0,0,0.5);
    }
    .hero-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        border-radius: 50%;
        display: block;
        transition: transform 0.4s ease;
    }
    .hero-avatar:hover img {
        transform: scale(1.04);
    }
    .section { padding: 6rem 2rem; }
    .section-title { font-size: 2.25rem; font-weight: 800; margin: 0 0 2.5rem; letter-spacing: -0.5px; }
    .stat-card { background-color: var(--bg-secondary); border: 1px solid var(--border); border-radius: 12px; padding: 2rem; text-align: center; }
    .stat-number { font-size: 3rem; font-weight: 800; line-height: 1; margin-bottom: 0.5rem; }
    .stat-label { font-size: 0.875rem; color: var(--text-secondary); font-weight: 500; }
    .skills-grid { display: flex; flex-wrap: wrap; gap: 0.75rem; }
    .skill-tag { background-color: var(--bg-card); border: 1px solid var(--border); color: var(--text-primary); padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; }
    .experience-item { display: flex; gap: 2rem; position: relative; padding-bottom: 2.5rem; }
    .experience-item:last-child { padding-bottom: 0; }
    .experience-item::before { content: ''; position: absolute; left: 15px; top: 24px; bottom: 0; width: 2px; background-color: var(--border); }
    .experience-item:last-child::before { display: none; }
    .exp-dot { width: 32px; height: 32px; border-radius: 50%; background-color: var(--bg-primary); border: 2px solid var(--text-primary); display: flex; align-items: center; justify-content: center; font-size: 0.875rem; z-index: 1; flex-shrink: 0; }
    /* ── Blog Section ── */
    .blog-section-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
    .blog-tabs { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 2rem; }
    .blog-tab { padding: 0.45rem 1rem; border-radius: 20px; border: 1px solid var(--border); background: transparent; color: var(--text-secondary); font-size: 0.82rem; font-weight: 500; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .blog-tab:hover { background: var(--bg-hover); color: var(--text-primary); }
    .blog-tab.active { background: var(--text-primary); color: var(--bg-primary); border-color: var(--text-primary); }
    .blog-category-panel { display: none; }
    .blog-category-panel.active { display: block; }
    .blog-grid-home { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    @media (max-width: 900px) { .blog-grid-home { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .blog-grid-home { grid-template-columns: 1fr; } }
    .blog-card { background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; display: flex; flex-direction: column; height: 100%; text-decoration: none; color: inherit; }
    .blog-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(0,0,0,0.3); }
    .blog-card-img { height: 180px; background-color: var(--bg-secondary); background-size: cover; background-position: center; border-bottom: 1px solid var(--border); position: relative; }
    .blog-card-cat-badge { position: absolute; top: 0.75rem; left: 0.75rem; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.1); color: #fff; font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 10px; text-transform: uppercase; letter-spacing: 0.05em; }
    .blog-card-content { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .blog-card-meta { display: flex; align-items: center; gap: 0.75rem; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.6rem; }
    .blog-card-title { font-size: 1rem; font-weight: 700; margin: 0 0 0.5rem; line-height: 1.4; }
    .blog-card-excerpt { font-size: 0.85rem; color: var(--text-secondary); margin: 0; flex: 1; line-height: 1.6; }
    .blog-card-footer { margin-top: 1rem; display: flex; align-items: center; justify-content: space-between; }
    .blog-read-more { font-size: 0.8rem; color: var(--text-primary); font-weight: 600; display: flex; align-items: center; gap: 0.3rem; }
    .blog-featured { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; display: grid; grid-template-columns: 1fr 1fr; margin-bottom: 2rem; text-decoration: none; color: inherit; transition: box-shadow 0.2s; }
    .blog-featured:hover { box-shadow: 0 8px 40px rgba(0,0,0,0.4); }
    .blog-featured-img { min-height: 280px; background-size: cover; background-position: center; background-color: var(--bg-secondary); }
    .blog-featured-content { padding: 2rem; display: flex; flex-direction: column; justify-content: center; }
    .blog-featured-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted); margin-bottom: 0.75rem; }
    .blog-featured-title { font-size: 1.5rem; font-weight: 800; line-height: 1.3; margin: 0 0 1rem; }
    .blog-featured-excerpt { font-size: 0.9rem; color: var(--text-secondary); line-height: 1.7; margin: 0 0 1.5rem; }
    @media (max-width: 700px) { .blog-featured { grid-template-columns: 1fr; } .blog-featured-img { min-height: 200px; } }
    @media (max-width: 992px) {
        .hero-inner { grid-template-columns: 1fr; gap: 3rem; text-align: center; }
        .hero h1 { font-size: 2.75rem; }
        .hero p { margin: 0 auto 2rem; }
        .hero-actions { justify-content: center; }
        .hero-avatar { width: 280px; height: 380px; }
        .hero-image { display: none; }
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<section class="hero">
    <div class="hero-inner">
        <div>
            <div class="hero-badge">Available for Projects</div>
            <h1>{!! nl2br(e($siteSettings['hero_title'] ?? "Hi, I'm Gopi K")) !!}<br><span>{{ $siteSettings['hero_subtitle'] ?? 'Founder & Tech Entrepreneur' }}</span></h1>
            <p>{{ $siteSettings['hero_description'] ?? 'Founder of Go Esscay Solutions. Passionate about creating impact in society through IT, automation, and open-source technologies. Turning ideas into reality.' }}</p>
            <div class="hero-actions">
                <a href="{{ route('blog') }}" class="btn btn-primary">
                    <i class="fas fa-pen-nib"></i> Read My Blog
                </a>
                <a href="{{ route('jobs') }}" class="btn btn-outline">
                    <i class="fas fa-briefcase"></i> View Jobs
                </a>
            </div>
            <div class="social-links" style="margin-top: 2rem;">
                @foreach($socialLinks as $social)
                <a href="{{ $social->url }}" class="social-link" target="_blank" rel="noopener" title="{{ ucfirst($social->platform) }}">
                    <i class="{{ $social->icon_class }}"></i>
                </a>
                @endforeach
            </div>
        </div>
        <div class="hero-image">
            <div class="hero-avatar">
                <img src="{{ asset('images/gopi-profile.png') }}" alt="Gopi K — Founder of Go Esscay Solutions" loading="eager">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section" style="padding-top: 3rem; padding-bottom: 3rem;">
    <div class="container">
        <div class="grid-4">
            <div class="stat-card">
                <div class="stat-number">14+</div>
                <div class="stat-label">Years in Technology</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">3</div>
                <div class="stat-label">Global Enterprises</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">2025</div>
                <div class="stat-label">Founded Go Esscay</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">∞</div>
                <div class="stat-label">Passion for Tech</div>
            </div>
        </div>
    </div>
</section>

<!-- About / Skills Section -->
<section class="section" style="background-color: var(--bg-secondary);">
    <div class="container">
        <div class="grid-2" style="align-items: start;">
            <div>
                <p class="text-sm text-secondary" style="text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">About Me</p>
                <h2 class="section-title">{{ $siteSettings['about_title'] ?? 'Turning Ideas Into Reality' }}</h2>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                    {{ $siteSettings['about_text_1'] ?? 'I am the Founder of Go Esscay Solutions, a company dedicated to helping startups and small businesses implement IT, automation, and open-source applications to run their operations smarter, faster, and more efficiently.' }}
                </p>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                    {{ $siteSettings['about_text_2'] ?? 'Based in Greater Chennai Area, I am passionate about making technology simple, affordable, and accessible for every business. My mission is to bridge the gap between complex technology and everyday business needs.' }}
                </p>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.75rem;">
                    <div style="text-align: center; padding: 1rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; min-width: 100px;">
                        <div style="font-size: 1.25rem; font-weight: 700;">{{ $siteSettings['location'] ?? 'Chennai' }}</div>
                        <div class="text-sm text-muted">Location</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; min-width: 100px;">
                        <div style="font-size: 1.25rem; font-weight: 700;">{{ $siteSettings['founded_year'] ?? '2025' }}</div>
                        <div class="text-sm text-muted">Founded</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; min-width: 100px;">
                        <div style="font-size: 1.25rem; font-weight: 700;">14+</div>
                        <div class="text-sm text-muted">Years Exp.</div>
                    </div>
                </div>
                <a href="{{ route('about') }}" class="btn btn-outline" style="padding: 0.625rem 1.5rem;">
                    <i class="fas fa-user"></i> Read More About Me &nbsp;<i class="fas fa-arrow-right" style="font-size:0.75rem;"></i>
                </a>
            </div>
            <div>
                <p class="text-sm text-secondary" style="text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Skills</p>
                <h2 class="section-title">Core Expertise</h2>
                <div class="skills-grid">
                    <span class="skill-tag">Enterprise Architecture</span>
                    <span class="skill-tag">Agile Methodologies</span>
                    <span class="skill-tag">Open-Source Software</span>
                    <span class="skill-tag">Java</span>
                    <span class="skill-tag">Spring Framework</span>
                    <span class="skill-tag">IT Automation</span>
                    <span class="skill-tag">Sales & Marketing</span>
                    <span class="skill-tag">Business Strategy</span>
                    <span class="skill-tag">Digital Transformation</span>
                    <span class="skill-tag">Startup Consulting</span>
                    <span class="skill-tag">PHP / Laravel</span>
                    <span class="skill-tag">MySQL</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Experience Section -->
<section class="section">
    <div class="container">
        <p class="text-sm text-secondary" style="text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Career</p>
        <h2 class="section-title">Professional Experience</h2>
        <div class="card">
            @forelse($experiences as $exp)
            <div class="experience-item">
                <div class="exp-dot"></div>
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <h3 style="font-size: 1.15rem; font-weight: 700; margin: 0;">{{ $exp->title }}</h3>
                            <div style="font-size: 0.95rem; color: var(--text-secondary); margin-top: 0.25rem;">{{ $exp->company }}</div>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">
                            {{ $exp->start_date->format('M Y') }} &mdash; {{ $exp->is_current ? 'Present' : $exp->end_date->format('M Y') }}
                        </div>
                    </div>
                    <p style="font-size: 0.9rem; color: var(--text-secondary); margin-top: 1rem; line-height: 1.6;">
                        {{ $exp->description }}
                    </p>
                </div>
            </div>
            @empty
            <p class="text-muted">No experience history found.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Blog Section (Category-Based) -->
<section class="section" style="background-color: var(--bg-secondary);">
    <div class="container">
        <div class="blog-section-header">
            <div>
                <p class="text-sm text-secondary" style="text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Knowledge Hub</p>
                <h2 class="section-title" style="margin: 0;">Explore by Category</h2>
            </div>
            <a href="{{ route('blog') }}" class="btn btn-outline btn-sm"><i class="fas fa-book-open" style="margin-right:0.4rem;"></i>All Articles</a>
        </div>

        {{-- Featured Post --}}
        @if($featuredPost)
        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="blog-featured">
            <div class="blog-featured-img" style="background-image: url('{{ $featuredPost->featured_image ? Storage::url($featuredPost->featured_image) : asset('images/blog-placeholder.png') }}')"></div>
            <div class="blog-featured-content">
                <div class="blog-featured-label"><i class="fas fa-star" style="margin-right:0.3rem;"></i>Featured Article</div>
                @if($featuredPost->category)
                    <span style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);margin-bottom:0.5rem;display:block;">{{ $featuredPost->category->name }}</span>
                @endif
                <h3 class="blog-featured-title">{{ $featuredPost->title }}</h3>
                <p class="blog-featured-excerpt">{{ Str::limit($featuredPost->summary, 140) }}</p>
                <div style="display:flex;align-items:center;gap:1rem;font-size:0.8rem;color:var(--text-muted);">
                    <span><i class="fas fa-calendar" style="margin-right:0.3rem;"></i>{{ $featuredPost->published_at->format('M d, Y') }}</span>
                    <span><i class="fas fa-eye" style="margin-right:0.3rem;"></i>{{ number_format($featuredPost->views_count) }} views</span>
                    <span class="blog-read-more">Read Article <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>
        </a>
        @endif

        {{-- Category Tabs --}}
        @if($blogCategories->isNotEmpty())
        <div class="blog-tabs" id="blogTabs">
            @foreach($blogCategories as $i => $cat)
            <button class="blog-tab {{ $i === 0 ? 'active' : '' }}"
                    onclick="switchBlogTab('{{ $cat->slug }}', this)">
                {{ $cat->name }}
                <span style="font-size:0.7rem;opacity:0.6;margin-left:0.3rem;">({{ $cat->posts_count }})</span>
            </button>
            @endforeach
        </div>

        {{-- Category Panels --}}
        @foreach($blogCategories as $i => $cat)
        @php $catData = $categoryPosts[$cat->slug] ?? null; @endphp
        <div class="blog-category-panel {{ $i === 0 ? 'active' : '' }}" id="panel-{{ $cat->slug }}">
            @if($catData && $catData['posts']->isNotEmpty())
            <div class="blog-grid-home">
                @foreach($catData['posts'] as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card">
                    <div class="blog-card-img" style="background-image: url('{{ $post->featured_image ? Storage::url($post->featured_image) : asset('images/blog-placeholder.png') }}')">
                        <span class="blog-card-cat-badge">{{ $cat->name }}</span>
                    </div>
                    <div class="blog-card-content">
                        <div class="blog-card-meta">
                            <span><i class="fas fa-calendar" style="margin-right:0.2rem;"></i>{{ $post->published_at->format('M d, Y') }}</span>
                            <span><i class="fas fa-eye" style="margin-right:0.2rem;"></i>{{ number_format($post->views_count) }}</span>
                        </div>
                        <h3 class="blog-card-title">{{ $post->title }}</h3>
                        <p class="blog-card-excerpt">{{ Str::limit($post->summary, 90) }}</p>
                        <div class="blog-card-footer">
                            <span class="blog-read-more">Read More <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div style="text-align:center;margin-top:1.5rem;">
                <a href="{{ route('blog', ['category' => $cat->slug]) }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-th-list" style="margin-right:0.4rem;"></i>All {{ $cat->name }} Articles
                </a>
            </div>
            @else
            <p class="text-muted" style="text-align:center;padding:2rem;">No posts in this category yet.</p>
            @endif
        </div>
        @endforeach
        @endif
    </div>
</section>

@push('scripts')
<script>
function switchBlogTab(slug, btn) {
    document.querySelectorAll('.blog-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.blog-category-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    const panel = document.getElementById('panel-' + slug);
    if (panel) panel.classList.add('active');
}
</script>
@endpush

<div class="container">
    <x-newsletter-subscribe variant="hero" />
</div>

@endsection
