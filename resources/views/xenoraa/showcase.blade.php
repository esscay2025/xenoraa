@extends('layouts.xenoraa')
@section('title', 'Showcase — Xenoraa')
@section('meta_description', 'See how professionals use Xenoraa to build their digital identity. Real profiles, real results.')

@section('styles')
<style>
.xn-page-hero { padding: 5rem 4rem 4rem; background: linear-gradient(180deg, #0a0a0a 0%, #000 100%); border-bottom: 1px solid #1a1a1a; text-align: center; }
.xn-showcase-filters { display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: center; margin: 2rem 0; }
.xn-filter-btn { padding: 0.4rem 1.25rem; border: 1px solid #1f1f1f; border-radius: 100px; background: transparent; color: #71717a; font-size: 0.8rem; font-weight: 500; cursor: pointer; transition: all 0.2s; }
.xn-filter-btn:hover, .xn-filter-btn.active { border-color: #7c3aed; color: #a855f7; background: rgba(124,58,237,0.08); }
.xn-showcase-masonry { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
.xn-showcase-item {
    background: #0d0d0d; border: 1px solid #1f1f1f;
    border-radius: 16px; overflow: hidden;
    transition: all 0.3s; cursor: pointer;
    text-decoration: none; display: block;
}
.xn-showcase-item:hover { border-color: #7c3aed; transform: translateY(-6px); box-shadow: 0 30px 60px rgba(124,58,237,0.1); }
.xn-showcase-cover { width: 100%; aspect-ratio: 16/9; object-fit: cover; background: linear-gradient(135deg, #111, #1a1a1a); display: flex; align-items: center; justify-content: center; }
.xn-showcase-cover-placeholder { width: 100%; aspect-ratio: 16/9; background: linear-gradient(135deg, #0f0f0f, #1a1a1a); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: rgba(124,58,237,0.3); }
.xn-showcase-body { padding: 1.5rem; }
.xn-showcase-avatar { width: 48px; height: 48px; border-radius: 50%; background: rgba(124,58,237,0.2); border: 2px solid rgba(124,58,237,0.3); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #a855f7; font-size: 1.1rem; margin-bottom: 1rem; }
.xn-showcase-name { font-weight: 700; color: #fff; font-size: 1rem; margin-bottom: 0.25rem; }
.xn-showcase-role { font-size: 0.8rem; color: #71717a; margin-bottom: 0.75rem; }
.xn-showcase-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem; }
.xn-showcase-tag { padding: 0.2rem 0.6rem; background: rgba(124,58,237,0.08); border: 1px solid rgba(124,58,237,0.15); color: #7c3aed; font-size: 0.7rem; border-radius: 4px; }
.xn-showcase-url { display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: #52525b; transition: color 0.2s; }
.xn-showcase-item:hover .xn-showcase-url { color: #a855f7; }
.xn-featured-showcase { background: linear-gradient(135deg, rgba(124,58,237,0.1), rgba(0,0,0,0)); border: 1px solid rgba(124,58,237,0.3); border-radius: 20px; padding: 3rem; display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; margin-bottom: 4rem; }
.xn-featured-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.3); color: #a855f7; font-size: 0.7rem; font-weight: 700; padding: 0.3rem 0.875rem; border-radius: 100px; margin-bottom: 1rem; }
@media(max-width:1024px){.xn-showcase-masonry{grid-template-columns:repeat(2,1fr);}.xn-featured-showcase{grid-template-columns:1fr;}}
@media(max-width:768px){.xn-showcase-masonry{grid-template-columns:1fr;}.xn-page-hero{padding:3rem 1.5rem 2.5rem;}.xn-featured-showcase{padding:2rem;}}
</style>
@endsection

@section('content')
<section class="xn-page-hero">
    <div class="xn-container">
        <div class="xn-label">Showcase</div>
        <h1 class="xn-heading-xl">Real Professionals.<br><span style="color:#a855f7;">Real Results.</span></h1>
        <p class="xn-body-lg" style="max-width:560px;margin:1.25rem auto 0;">See how professionals across industries are using Xenoraa to build their digital presence and manage their business operations.</p>
        <div class="xn-showcase-filters">
            @foreach(['All', 'Business', 'Consultant', 'Developer', 'Creator', 'Doctor', 'Advocate', 'Educator'] as $filter)
            <button class="xn-filter-btn {{ $filter === 'All' ? 'active' : '' }}" onclick="filterShowcase('{{ $filter }}', this)">{{ $filter }}</button>
            @endforeach
        </div>
    </div>
</section>

<section class="xn-section" style="background:#000;">
    <div class="xn-container">

        {{-- Featured Profile --}}
        <div class="xn-featured-showcase">
            <div>
                <div class="xn-featured-badge">⭐ Featured Profile</div>
                <h2 class="xn-heading-md" style="margin-bottom:0.75rem;">Gopi K.</h2>
                <div style="font-size:0.875rem;color:#a855f7;margin-bottom:1rem;">Founder & Business Owner — Go Esscay Solutions</div>
                <p style="font-size:0.875rem;color:#71717a;line-height:1.7;margin-bottom:1.5rem;">Software solutions provider, digital transformation consultant, and entrepreneur. Using Xenoraa to manage portfolio, clients, blog, and business operations from a single platform.</p>
                <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1.5rem;">
                    @foreach(['Business Owner','Software Solutions','Digital Transformation','Consulting','AI & Automation'] as $tag)
                    <span class="xn-showcase-tag">{{ $tag }}</span>
                    @endforeach
                </div>
                <a href="https://gopi.blog" target="_blank" class="xn-btn-primary-lg" style="font-size:0.875rem;padding:0.75rem 1.75rem;">
                    Visit Profile <i class="fas fa-external-link-alt" style="font-size:0.75rem;"></i>
                </a>
            </div>
            <div>
                <img src="/images/xenoraa/showcase-mockup.jpg" alt="Gopi's Portfolio" style="width:100%;border-radius:12px;border:1px solid rgba(124,58,237,0.2);">
            </div>
        </div>

        {{-- Showcase Grid --}}
        <div class="xn-showcase-masonry" id="showcaseGrid">
            @php
            $profiles = [
                ['name'=>'Dr. Priya Sharma','role'=>'Cardiologist & Health Consultant','category'=>'Doctor','tags'=>['Healthcare','Cardiology','Wellness'],'url'=>'xenoraa.com/priya','initial'=>'P'],
                ['name'=>'Adv. Rajan Mehta','role'=>'Senior Advocate, High Court','category'=>'Advocate','tags'=>['Law','Corporate','Litigation'],'url'=>'xenoraa.com/rajan','initial'=>'R'],
                ['name'=>'Ananya Krishnan','role'=>'Digital Marketing Consultant','category'=>'Consultant','tags'=>['Marketing','SEO','Growth'],'url'=>'xenoraa.com/ananya','initial'=>'A'],
                ['name'=>'Vikram Nair','role'=>'Full Stack Developer & Freelancer','category'=>'Developer','tags'=>['React','Laravel','SaaS'],'url'=>'xenoraa.com/vikram','initial'=>'V'],
                ['name'=>'Meera Iyer','role'=>'Life Coach & Motivational Speaker','category'=>'Creator','tags'=>['Coaching','Mindfulness','Speaking'],'url'=>'xenoraa.com/meera','initial'=>'M'],
                ['name'=>'Prof. Suresh Kumar','role'=>'Professor & Education Consultant','category'=>'Educator','tags'=>['Education','Research','Mentoring'],'url'=>'xenoraa.com/suresh','initial'=>'S'],
                ['name'=>'Kavitha Reddy','role'=>'Startup Founder & Entrepreneur','category'=>'Business','tags'=>['Startup','Fintech','Innovation'],'url'=>'xenoraa.com/kavitha','initial'=>'K'],
                ['name'=>'Arjun Patel','role'=>'Chartered Accountant & Tax Consultant','category'=>'Consultant','tags'=>['Finance','Tax','Audit'],'url'=>'xenoraa.com/arjun','initial'=>'A'],
                ['name'=>'Divya Thomas','role'=>'UX Designer & Brand Strategist','category'=>'Creator','tags'=>['Design','Branding','UX'],'url'=>'xenoraa.com/divya','initial'=>'D'],
            ];
            @endphp
            @foreach($profiles as $p)
            <div class="xn-showcase-item" data-category="{{ $p['category'] }}" href="#">
                <div class="xn-showcase-cover-placeholder">
                    <span style="font-size:4rem;color:rgba(124,58,237,0.2);">{{ $p['initial'] }}</span>
                </div>
                <div class="xn-showcase-body">
                    <div class="xn-showcase-avatar">{{ $p['initial'] }}</div>
                    <div class="xn-showcase-name">{{ $p['name'] }}</div>
                    <div class="xn-showcase-role">{{ $p['role'] }}</div>
                    <div class="xn-showcase-tags">
                        @foreach($p['tags'] as $tag)
                        <span class="xn-showcase-tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <div class="xn-showcase-url"><i class="fas fa-link" style="font-size:0.65rem;"></i> {{ $p['url'] }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align:center;margin-top:3rem;">
            <p style="color:#52525b;font-size:0.875rem;margin-bottom:1.5rem;">Want to be featured here?</p>
            <a href="{{ route('xenoraa.get-started') }}" class="xn-btn-primary-lg">Create Your Profile 🚀</a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
function filterShowcase(category, btn) {
    document.querySelectorAll('.xn-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('#showcaseGrid .xn-showcase-item').forEach(item => {
        if (category === 'All' || item.dataset.category === category) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
@endsection
