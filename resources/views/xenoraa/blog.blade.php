@extends('layouts.xenoraa')
@section('title', 'Blog — Xenoraa')
@section('meta_description', 'Insights, guides, and resources for professionals building their digital identity with Xenoraa.')

@section('styles')
<style>
.xn-page-hero { padding: 5rem 4rem 4rem; background: linear-gradient(180deg, #0a0a0a 0%, #000 100%); border-bottom: 1px solid #1a1a1a; }
.xn-blog-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; }
.xn-blog-main-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
.xn-blog-card {
    background: #0d0d0d; border: 1px solid #1f1f1f;
    border-radius: 16px; overflow: hidden;
    transition: all 0.3s; text-decoration: none; display: block;
}
.xn-blog-card:hover { border-color: #7c3aed; transform: translateY(-4px); }
.xn-blog-card-img { width: 100%; aspect-ratio: 16/9; object-fit: cover; background: linear-gradient(135deg, #111, #1a1a1a); }
.xn-blog-card-img-placeholder { width: 100%; aspect-ratio: 16/9; background: linear-gradient(135deg, #0f0f0f, #1a1a1a); display: flex; align-items: center; justify-content: center; color: rgba(124,58,237,0.2); font-size: 2.5rem; }
.xn-blog-card-body { padding: 1.5rem; }
.xn-blog-cat { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #7c3aed; margin-bottom: 0.75rem; }
.xn-blog-title { font-weight: 700; color: #fff; font-size: 0.95rem; line-height: 1.4; margin-bottom: 0.5rem; }
.xn-blog-excerpt { font-size: 0.8rem; color: #71717a; line-height: 1.65; margin-bottom: 1rem; }
.xn-blog-meta { display: flex; align-items: center; gap: 1rem; font-size: 0.75rem; color: #52525b; }
.xn-blog-featured { background: #0d0d0d; border: 1px solid #1f1f1f; border-radius: 16px; overflow: hidden; margin-bottom: 1.5rem; text-decoration: none; display: block; transition: all 0.3s; }
.xn-blog-featured:hover { border-color: #7c3aed; }
.xn-blog-featured-img { width: 100%; aspect-ratio: 21/9; object-fit: cover; background: linear-gradient(135deg, #111, #1a1a1a); }
.xn-blog-featured-img-placeholder { width: 100%; aspect-ratio: 21/9; background: linear-gradient(135deg, #0f0f0f, #1a1a1a); display: flex; align-items: center; justify-content: center; color: rgba(124,58,237,0.2); font-size: 3rem; }
.xn-blog-featured-body { padding: 2rem; }
.xn-sidebar-section { margin-bottom: 2.5rem; }
.xn-sidebar-heading { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #52525b; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #1a1a1a; }
.xn-sidebar-post { display: flex; gap: 1rem; margin-bottom: 1.25rem; text-decoration: none; }
.xn-sidebar-post-num { font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; font-weight: 800; color: #1f1f1f; flex-shrink: 0; line-height: 1; }
.xn-sidebar-post-title { font-size: 0.825rem; font-weight: 600; color: #a1a1aa; line-height: 1.4; transition: color 0.2s; }
.xn-sidebar-post:hover .xn-sidebar-post-title { color: #fff; }
.xn-sidebar-cats { display: flex; flex-direction: column; gap: 0.5rem; }
.xn-sidebar-cat { display: flex; justify-content: space-between; align-items: center; padding: 0.625rem 0; border-bottom: 1px solid #111; text-decoration: none; color: #71717a; font-size: 0.825rem; transition: color 0.2s; }
.xn-sidebar-cat:hover { color: #a855f7; }
.xn-sidebar-cat-count { background: rgba(124,58,237,0.1); color: #7c3aed; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 4px; }
@media(max-width:1024px){.xn-blog-grid{grid-template-columns:1fr;}.xn-blog-main-grid{grid-template-columns:1fr;}}
@media(max-width:768px){.xn-page-hero{padding:3rem 1.5rem 2.5rem;}}
</style>
@endsection

@section('content')
<section class="xn-page-hero">
    <div class="xn-container">
        <div class="xn-label">Blog</div>
        <h1 class="xn-heading-xl" style="max-width:700px;">Insights for the<br><span style="color:#a855f7;">Modern Professional</span></h1>
        <p class="xn-body-lg" style="max-width:560px;margin-top:1.25rem;">Guides, strategies, and resources to help you build your digital identity and grow your professional brand.</p>
    </div>
</section>

<section class="xn-section" style="background:#000;">
    <div class="xn-container">
        <div class="xn-blog-grid">
            <div>
                {{-- Featured Post --}}
                <a href="#" class="xn-blog-featured">
                    <div class="xn-blog-featured-img-placeholder"><i class="fas fa-pen-nib"></i></div>
                    <div class="xn-blog-featured-body">
                        <div class="xn-blog-cat">Featured · Platform Updates</div>
                        <div style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;color:#fff;line-height:1.3;margin-bottom:0.75rem;">How Xenoraa Helps Professionals Build a Powerful Digital Identity in 2025</div>
                        <div style="font-size:0.875rem;color:#71717a;line-height:1.7;margin-bottom:1rem;">Discover how thousands of professionals are using Xenoraa to showcase their expertise, manage clients, and grow their personal brand from a single unified platform.</div>
                        <div style="display:flex;align-items:center;gap:1rem;font-size:0.75rem;color:#52525b;">
                            <span><i class="fas fa-calendar" style="margin-right:0.4rem;"></i>{{ date('M d, Y') }}</span>
                            <span><i class="fas fa-clock" style="margin-right:0.4rem;"></i>5 min read</span>
                            <span style="color:#7c3aed;">Read More →</span>
                        </div>
                    </div>
                </a>

                {{-- Blog Grid --}}
                <div class="xn-blog-main-grid">
                    @php
                    $posts = [
                        ['cat'=>'Personal Branding','title'=>'10 Ways to Build Your Professional Online Presence','excerpt'=>'A step-by-step guide to establishing your digital identity as a professional.','icon'=>'fa-user-circle'],
                        ['cat'=>'CRM & Clients','title'=>'How to Manage Client Relationships Like a Pro','excerpt'=>'Best practices for tracking leads, managing conversations, and closing deals.','icon'=>'fa-users'],
                        ['cat'=>'AI & Automation','title'=>'Using AI to Grow Your Professional Practice','excerpt'=>'How AI-powered tools can help you save time and focus on what matters most.','icon'=>'fa-robot'],
                        ['cat'=>'Content Marketing','title'=>'Why Every Professional Needs a Blog in 2025','excerpt'=>'Content marketing strategies that build authority and attract the right clients.','icon'=>'fa-pen-nib'],
                        ['cat'=>'E-Commerce','title'=>'Monetize Your Expertise: Selling Services Online','excerpt'=>'How to package and sell your knowledge, services, and digital products.','icon'=>'fa-shopping-bag'],
                        ['cat'=>'Growth','title'=>'From Zero to 1000 Profile Views: A Growth Playbook','excerpt'=>'Proven strategies to increase your profile visibility and attract opportunities.','icon'=>'fa-chart-line'],
                    ];
                    @endphp
                    @foreach($posts as $post)
                    <a href="#" class="xn-blog-card">
                        <div class="xn-blog-card-img-placeholder"><i class="fas {{ $post['icon'] }}"></i></div>
                        <div class="xn-blog-card-body">
                            <div class="xn-blog-cat">{{ $post['cat'] }}</div>
                            <div class="xn-blog-title">{{ $post['title'] }}</div>
                            <div class="xn-blog-excerpt">{{ $post['excerpt'] }}</div>
                            <div class="xn-blog-meta">
                                <span><i class="fas fa-calendar" style="margin-right:0.3rem;"></i>{{ date('M Y') }}</span>
                                <span><i class="fas fa-clock" style="margin-right:0.3rem;"></i>3 min</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Sidebar --}}
            <div>
                <div class="xn-sidebar-section">
                    <div class="xn-sidebar-heading">Trending Posts</div>
                    @foreach(['How to Choose the Right Plan for Your Profession','Setting Up Your Custom Domain on Xenoraa','AI Chat Widget: Training Guide for Professionals','Building a Portfolio That Attracts Clients','Newsletter Marketing for Consultants'] as $i => $title)
                    <a href="#" class="xn-sidebar-post">
                        <div class="xn-sidebar-post-num">0{{ $i+1 }}</div>
                        <div class="xn-sidebar-post-title">{{ $title }}</div>
                    </a>
                    @endforeach
                </div>
                <div class="xn-sidebar-section">
                    <div class="xn-sidebar-heading">Categories</div>
                    <div class="xn-sidebar-cats">
                        @foreach(['Personal Branding'=>12,'CRM & Clients'=>8,'AI & Automation'=>6,'Content Marketing'=>10,'E-Commerce'=>5,'Growth Strategies'=>9,'Platform Updates'=>4] as $cat => $count)
                        <a href="#" class="xn-sidebar-cat">{{ $cat }}<span class="xn-sidebar-cat-count">{{ $count }}</span></a>
                        @endforeach
                    </div>
                </div>
                <div style="background:linear-gradient(135deg,rgba(124,58,237,0.15),rgba(0,0,0,0));border:1px solid rgba(124,58,237,0.3);border-radius:16px;padding:2rem;text-align:center;">
                    <div style="font-size:1.5rem;margin-bottom:0.75rem;">🚀</div>
                    <div style="font-weight:700;color:#fff;margin-bottom:0.5rem;">Start Your Free Trial</div>
                    <div style="font-size:0.8rem;color:#71717a;margin-bottom:1.5rem;line-height:1.6;">Build your digital identity on Xenoraa today. No credit card required.</div>
                    <a href="{{ route('xenoraa.get-started') }}" class="xn-btn-primary" style="width:100%;text-align:center;display:block;padding:0.75rem;">Get Started Free</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
