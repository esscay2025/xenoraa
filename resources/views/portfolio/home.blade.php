@extends('layouts.app')

@section('title', 'Gopi K | Founder of Go Esscay Solutions')
@section('description', 'Personal portfolio of Gopi K - Founder of Go Esscay Solutions. IT, Automation & Open-Source Expert based in Greater Chennai Area.')

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
    .hero-avatar::after {
        content: '';
        position: absolute;
        inset: -10px;
        border-radius: 50%;
        border: 1px dashed rgba(255,255,255,0.12);
        pointer-events: none;
    }
    .section { padding: 5rem 2rem; }
    .section-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }
    .section-subtitle { color: var(--text-secondary); margin-bottom: 3rem; }
    .skills-grid { display: flex; flex-wrap: wrap; gap: 0.75rem; }
    .skill-tag {
        padding: 0.5rem 1rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.875rem;
        color: var(--text-secondary);
        transition: all 0.2s;
    }
    .skill-tag:hover { border-color: #555; color: var(--text-primary); }
    .experience-item {
        display: flex;
        gap: 1.5rem;
        padding: 1.5rem 0;
        border-bottom: 1px solid var(--border);
    }
    .experience-item:last-child { border-bottom: none; }
    .exp-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--text-primary);
        margin-top: 0.4rem;
        flex-shrink: 0;
    }
    .blog-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .blog-card:hover { border-color: #444; transform: translateY(-2px); }
    .blog-card-body { padding: 1.25rem; }
    .blog-card-title { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
    .blog-card-summary { font-size: 0.875rem; color: var(--text-secondary); }
    .job-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .job-card:hover { border-color: #444; }
    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
    }
    .stat-number { font-size: 2.5rem; font-weight: 800; }
    .stat-label { color: var(--text-secondary); font-size: 0.875rem; }
    @media (max-width: 768px) {
        .hero-inner { grid-template-columns: 1fr; gap: 2rem; }
        .hero h1 { font-size: 2.5rem; }
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
            <h1>Hi, I'm <span>Gopi K</span><br>Founder & Tech<br>Entrepreneur</h1>
            <p>Founder of Go Esscay Solutions. Passionate about creating impact in society through IT, automation, and open-source technologies. Turning ideas into reality.</p>
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
                <div class="stat-number">1+</div>
                <div class="stat-label">Year of Entrepreneurship</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">242</div>
                <div class="stat-label">LinkedIn Connections</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">∞</div>
                <div class="stat-label">Ideas in Progress</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">100%</div>
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
                <h2 class="section-title">Turning Ideas Into Reality</h2>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                    I am the Founder of <strong style="color: white;">Go Esscay Solutions</strong>, a company dedicated to helping startups and small businesses implement IT, automation, and open-source applications to run their operations smarter, faster, and more efficiently.
                </p>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                    Based in Greater Chennai Area, I am passionate about making technology <strong style="color: white;">simple, affordable, and accessible</strong> for every business. My mission is to bridge the gap between complex technology and everyday business needs.
                </p>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <div style="text-align: center; padding: 1rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; min-width: 100px;">
                        <div style="font-size: 1.5rem; font-weight: 700;">Chennai</div>
                        <div class="text-sm text-muted">Location</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; min-width: 100px;">
                        <div style="font-size: 1.5rem; font-weight: 700;">2025</div>
                        <div class="text-sm text-muted">Founded</div>
                    </div>
                </div>
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
                            <h3 style="font-size: 1.1rem; font-weight: 600; margin: 0 0 0.25rem;">{{ $exp->role }}</h3>
                            <p style="color: var(--text-secondary); margin: 0 0 0.5rem; font-size: 0.9rem;">{{ $exp->company_name }}</p>
                        </div>
                        <div style="text-align: right;">
                            <span class="text-sm text-muted">{{ $exp->start_date->format('M Y') }} &mdash; {{ $exp->is_current ? 'Present' : $exp->end_date->format('M Y') }}</span>
                            @if($exp->is_current)
                                <span class="badge badge-success" style="display: block; margin-top: 0.25rem;">Current</span>
                            @endif
                        </div>
                    </div>
                    @if($exp->description)
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin: 0;">{{ $exp->description }}</p>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-secondary">No experience listed yet.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Recent Blog Posts -->
@if($recentPosts->count() > 0)
<section class="section" style="background-color: var(--bg-secondary);">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <p class="text-sm text-secondary" style="text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Writing</p>
                <h2 class="section-title" style="margin-bottom: 0;">Latest Blog Posts</h2>
            </div>
            <a href="{{ route('blog') }}" class="btn btn-outline btn-sm">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="grid-3">
            @foreach($recentPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="blog-card">
                @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" style="width: 100%; height: 180px; object-fit: cover;">
                @else
                <div style="width: 100%; height: 180px; background: linear-gradient(135deg, #1a1a1a, #2a2a2a); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-pen-nib" style="font-size: 2rem; color: var(--text-muted);"></i>
                </div>
                @endif
                <div class="blog-card-body">
                    @if($post->category)
                    <span class="badge badge-secondary" style="margin-bottom: 0.5rem;">{{ $post->category->name }}</span>
                    @endif
                    <h3 class="blog-card-title">{{ $post->title }}</h3>
                    <p class="blog-card-summary">{{ Str::limit($post->summary ?? strip_tags($post->content), 100) }}</p>
                    <p class="text-xs text-muted" style="margin-top: 0.75rem;">{{ $post->published_at?->format('M d, Y') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Active Jobs -->
@if($activeJobs->count() > 0)
<section class="section">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <p class="text-sm text-secondary" style="text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Hiring</p>
                <h2 class="section-title" style="margin-bottom: 0;">Open Positions</h2>
            </div>
            <a href="{{ route('jobs') }}" class="btn btn-outline btn-sm">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="grid-3">
            @foreach($activeJobs as $job)
            <a href="{{ route('jobs.show', $job->slug) }}" class="job-card">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem;">
                    <h3 style="font-size: 1rem; font-weight: 600; margin: 0;">{{ $job->title }}</h3>
                    <span class="badge badge-success">Active</span>
                </div>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 0.75rem;">
                    <span class="text-sm text-secondary"><i class="fas fa-map-marker-alt"></i> {{ $job->location }}</span>
                    <span class="text-sm text-secondary"><i class="fas fa-clock"></i> {{ $job->type }}</span>
                </div>
                @if($job->salary_range)
                <p class="text-sm text-muted">{{ $job->salary_range }}</p>
                @endif
                <p class="text-sm" style="color: var(--info); margin-top: 0.5rem;">Apply Now <i class="fas fa-arrow-right"></i></p>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="section" style="background-color: var(--bg-secondary); text-align: center;">
    <div class="container" style="max-width: 600px;">
        <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem;">Let's Work Together</h2>
        <p style="color: var(--text-secondary); margin-bottom: 2rem;">Whether you need IT consulting, automation solutions, or want to explore open-source opportunities for your business, I'm here to help.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="mailto:gopi@esscay.com" class="btn btn-primary"><i class="fas fa-envelope"></i> Get In Touch</a>
            <a href="{{ route('jobs') }}" class="btn btn-outline"><i class="fas fa-briefcase"></i> View Opportunities</a>
        </div>
    </div>
</section>

@endsection
