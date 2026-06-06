@extends('layouts.app')
@section('title', ($profile['name'] ?? $tenant->name) . ' — ' . ($profile['title'] ?? 'Medical Professional'))
@section('description', $profile['about'] ?? 'Experienced medical professional providing compassionate, evidence-based healthcare.')
@section('content')
@php
    $accent = $accentColor ?? '#0ea5e9';
    $homePage = $homePage ?? null;
    $_show = function(string $key) use ($homePage): bool {
        if (!$homePage) return true;
        return $homePage->isSectionEnabled($key);
    };
    $heroData         = $homePage ? $homePage->getSectionData('hero')         : [];
    $statsData        = $homePage ? $homePage->getSectionData('stats')        : [];
    $aboutData        = $homePage ? $homePage->getSectionData('about')        : [];
    $servicesData     = $homePage ? $homePage->getSectionData('services')     : [];
    $testimonialsData = $homePage ? $homePage->getSectionData('testimonials') : [];
    $blogData         = $homePage ? $homePage->getSectionData('blog')         : [];
    $contactData      = $homePage ? $homePage->getSectionData('contact')      : [];
    $heroHeading    = $heroData['heading']    ?? $profile['name']    ?? $tenant->name;
    $heroSubheading = $heroData['subheading'] ?? $profile['tagline'] ?? ($profile['title'] ?? 'MBBS, MD — Specialist Physician');
    $heroCta        = $heroData['cta_text']   ?? 'Book Appointment';
    $heroCtaUrl     = $heroData['cta_url']    ?? ($profile['appointment_link'] ?? ($tenantContactUrl ?? '/contact'));
    $statsItems = $statsData['items'] ?? $profile['stats'] ?? [
        ['icon' => '🏥', 'value' => $profile['years_experience'] ?? '20+', 'label' => 'Years Experience'],
        ['icon' => '👥', 'value' => $profile['patients_treated'] ?? '50K+', 'label' => 'Patients Treated'],
        ['icon' => '⭐', 'value' => $profile['success_rate']     ?? '98%',  'label' => 'Success Rate'],
        ['icon' => '🏆', 'value' => $profile['awards']           ?? '12+',  'label' => 'Awards Won'],
    ];
    $aboutText = $aboutData['text'] ?? $profile['about'] ?? 'I am dedicated to providing compassionate, evidence-based medical care. With over two decades of clinical experience, I combine the latest medical advances with a patient-first approach to deliver the best possible health outcomes.';
    $services = $servicesData['items'] ?? $profile['services'] ?? [
        ['icon' => '🩺', 'title' => 'General Consultation',    'text' => 'Comprehensive health assessment, diagnosis, and treatment planning'],
        ['icon' => '🔬', 'title' => 'Specialist Consultation', 'text' => 'In-depth specialist evaluation and expert medical opinion'],
        ['icon' => '💊', 'title' => 'Preventive Care',         'text' => 'Health screenings, vaccinations, and lifestyle counseling'],
        ['icon' => '🏃', 'title' => 'Chronic Disease Mgmt',   'text' => 'Long-term management of diabetes, hypertension, and other conditions'],
        ['icon' => '🧠', 'title' => 'Mental Health Support',   'text' => 'Stress management, anxiety, and mental wellness counseling'],
        ['icon' => '📋', 'title' => 'Health Reports & Certs',  'text' => 'Medical certificates, fitness reports, and insurance documentation'],
    ];
    $testimonials = $testimonialsData['items'] ?? $profile['testimonials'] ?? [
        ['name' => 'Kavitha Rajan',   'role' => 'Patient, Diabetic Management', 'text' => 'Dr. has been managing my diabetes for 5 years. My HbA1c is now perfectly controlled. Exceptional care and guidance.'],
        ['name' => 'Suresh Menon',    'role' => 'Patient, Cardiac Care',        'text' => 'After my heart procedure, the follow-up care was outstanding. I feel healthier than ever at 62.'],
        ['name' => 'Priya Nambiar',   'role' => 'Patient, Preventive Care',     'text' => 'The annual health check program detected an issue early. Truly life-changing preventive medicine.'],
    ];
    $contactHeading = $contactData['heading']     ?? "Book Your Appointment";
    $contactText    = $contactData['text']        ?? "Your health is our priority. Schedule a consultation today for expert medical care.";
    $contactBtn     = $contactData['button_text'] ?? 'Book Appointment';
    $contactBtnUrl  = $contactData['button_url']  ?? ($profile['appointment_link'] ?? ($tenantContactUrl ?? '/contact'));
    $tenantBase     = isset($tenant) && $tenant->custom_domain ? '' : ('/' . ($tenant->username ?? ''));
@endphp
<style>
:root { --doc-accent: {{ $accent }}; }
.xn-doc-wrap { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 4rem; }
/* HERO */
.xn-doc-hero { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; padding: 5rem 0 4rem; }
@media(max-width:768px){ .xn-doc-hero{grid-template-columns:1fr;text-align:center;gap:2rem;padding:3rem 0 2rem;} }
.xn-doc-hero-credential { display: inline-flex; align-items: center; gap: 0.5rem; background: color-mix(in srgb, var(--doc-accent) 12%, transparent); color: var(--doc-accent); font-size: 0.72rem; font-weight: 800; padding: 0.35rem 1rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.08em; border: 1px solid color-mix(in srgb, var(--doc-accent) 22%, transparent); margin-bottom: 1.25rem; }
.xn-doc-hero-name { font-size: clamp(2.2rem, 4vw, 3.5rem); font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 0.5rem; }
.xn-doc-hero-title { font-size: 1.05rem; color: var(--doc-accent); font-weight: 600; margin-bottom: 0.75rem; }
.xn-doc-hero-reg { font-size: 0.8rem; color: #64748b; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
@media(max-width:768px){ .xn-doc-hero-reg{justify-content:center;} }
.xn-doc-hero-specialties { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; }
@media(max-width:768px){ .xn-doc-hero-specialties{justify-content:center;} }
.xn-doc-specialty-tag { background: color-mix(in srgb, var(--doc-accent) 10%, transparent); color: var(--doc-accent); border: 1px solid color-mix(in srgb, var(--doc-accent) 22%, transparent); padding: 0.3rem 0.85rem; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
.xn-doc-hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
@media(max-width:768px){ .xn-doc-hero-actions{justify-content:center;} }
.xn-doc-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.75rem; border-radius: 8px; font-size: 0.9rem; font-weight: 700; text-decoration: none; transition: all 0.25s; cursor: pointer; border: none; }
.xn-doc-btn-primary { background: var(--doc-accent); color: #fff; box-shadow: 0 8px 24px color-mix(in srgb, var(--doc-accent) 30%, transparent); }
.xn-doc-btn-primary:hover { transform: translateY(-2px); color:#fff; }
.xn-doc-btn-outline { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,0.22); }
.xn-doc-btn-outline:hover { background: rgba(255,255,255,0.07); color:#fff; }
.xn-doc-hero-img { border-radius: 20px; overflow: hidden; aspect-ratio: 3/4; background: #1a1a1a; display: flex; align-items: center; justify-content: center; font-size: 6rem; border: 1px solid rgba(255,255,255,0.07); position: relative; }
.xn-doc-hero-img img { width: 100%; height: 100%; object-fit: cover; }
.xn-doc-availability { position: absolute; bottom: 1.5rem; left: 1.5rem; right: 1.5rem; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 1rem; }
.xn-doc-availability-title { font-size: 0.7rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; }
.xn-doc-availability-val { font-size: 0.85rem; color: #fff; font-weight: 600; }
/* STATS */
.xn-doc-stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1px; background: rgba(255,255,255,0.07); border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.07); margin-bottom: 4rem; }
.xn-doc-stat-cell { background: #1a1a1a; padding: 2rem 1rem; text-align: center; transition: background 0.2s; }
.xn-doc-stat-cell:hover { background: color-mix(in srgb, var(--doc-accent) 6%, #1a1a1a); }
.xn-doc-stat-icon { font-size: 1.5rem; margin-bottom: 0.5rem; }
.xn-doc-stat-num { font-size: 2rem; font-weight: 900; color: #fff; line-height: 1; }
.xn-doc-stat-label { font-size: 0.72rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.3rem; }
/* SECTION */
.xn-doc-section { padding: 4rem 0; }
.xn-doc-section-header { text-align: center; margin-bottom: 3rem; }
.xn-doc-badge { display: inline-block; background: color-mix(in srgb, var(--doc-accent) 12%, transparent); color: var(--doc-accent); font-size: 0.7rem; font-weight: 800; padding: 0.3rem 0.9rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem; border: 1px solid color-mix(in srgb, var(--doc-accent) 22%, transparent); }
.xn-doc-section-title { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: #fff; margin: 0 0 0.5rem; }
.xn-doc-section-sub { font-size: 1rem; color: #64748b; max-width: 520px; margin: 0 auto; }
.xn-doc-divider { width: 48px; height: 3px; background: var(--doc-accent); border-radius: 2px; margin: 1rem auto 0; }
/* SERVICES */
.xn-doc-services-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-doc-service-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; padding: 1.75rem; transition: all 0.25s; }
.xn-doc-service-card:hover { border-color: color-mix(in srgb, var(--doc-accent) 35%, transparent); transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.3); }
.xn-doc-service-icon { font-size: 2rem; margin-bottom: 1rem; }
.xn-doc-service-title { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; }
.xn-doc-service-text { font-size: 0.85rem; color: #64748b; line-height: 1.6; }
/* ABOUT */
.xn-doc-about-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 3rem; align-items: center; }
@media(max-width:768px){ .xn-doc-about-grid{grid-template-columns:1fr;} }
.xn-doc-about-text { font-size: 1rem; color: #94a3b8; line-height: 1.9; margin-bottom: 1.5rem; }
.xn-doc-about-img { border-radius: 20px; overflow: hidden; aspect-ratio: 3/4; background: #1a1a1a; display: flex; align-items: center; justify-content: center; font-size: 5rem; border: 1px solid rgba(255,255,255,0.07); }
.xn-doc-about-img img { width: 100%; height: 100%; object-fit: cover; }
.xn-doc-clinic-info { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; }
.xn-doc-clinic-row { display: flex; align-items: flex-start; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
.xn-doc-clinic-row:last-child { border-bottom: none; }
.xn-doc-clinic-icon { width: 36px; height: 36px; border-radius: 8px; background: color-mix(in srgb, var(--doc-accent) 12%, transparent); display: flex; align-items: center; justify-content: center; color: var(--doc-accent); flex-shrink: 0; font-size: 0.9rem; }
.xn-doc-clinic-label { font-size: 0.7rem; color: #64748b; margin-bottom: 0.1rem; text-transform: uppercase; letter-spacing: 0.05em; }
.xn-doc-clinic-val { font-size: 0.875rem; color: #e2e8f0; font-weight: 500; }
/* TESTIMONIALS */
.xn-doc-testimonials-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }
.xn-doc-testimonial-card { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; padding: 1.75rem; }
.xn-doc-testimonial-stars { color: var(--doc-accent); font-size: 0.85rem; margin-bottom: 1rem; }
.xn-doc-testimonial-text { font-size: 0.9rem; color: #94a3b8; line-height: 1.7; margin-bottom: 1.25rem; font-style: italic; }
.xn-doc-testimonial-author { display: flex; align-items: center; gap: 0.75rem; }
.xn-doc-testimonial-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--doc-accent); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 700; color: #fff; flex-shrink: 0; }
.xn-doc-testimonial-name { font-size: 0.875rem; font-weight: 700; color: #fff; }
.xn-doc-testimonial-role { font-size: 0.75rem; color: #64748b; }
/* CTA */
.xn-doc-cta { background: linear-gradient(135deg, color-mix(in srgb, var(--doc-accent) 12%, transparent) 0%, rgba(14,165,233,0.06) 100%); border: 1px solid color-mix(in srgb, var(--doc-accent) 22%, transparent); border-radius: 24px; padding: 4rem 2rem; text-align: center; }
.xn-doc-cta h2 { font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 900; color: #fff; margin-bottom: 1rem; }
.xn-doc-cta p { font-size: 1rem; color: #94a3b8; max-width: 500px; margin: 0 auto 2rem; }
@media(max-width:640px){
    .xn-doc-services-grid,.xn-doc-testimonials-grid{grid-template-columns:1fr;}
    .xn-doc-stats-row{grid-template-columns:repeat(2,1fr);}
}
</style>

<div class="xn-doc-wrap">

{{-- HERO --}}
@if($_show('hero'))
<div class="xn-doc-hero">
    <div>
        <div class="xn-doc-hero-credential"><i class="fas fa-stethoscope"></i> Medical Professional</div>
        <h1 class="xn-doc-hero-name">{{ $heroHeading }}</h1>
        <div class="xn-doc-hero-title">{{ $heroSubheading }}</div>
        @if(!empty($profile['registration_no']))<div class="xn-doc-hero-reg"><i class="fas fa-id-badge"></i> Reg. No: {{ $profile['registration_no'] }}</div>@endif
        <div class="xn-doc-hero-specialties">
            @foreach($profile['specialties'] ?? ['General Medicine','Preventive Care','Chronic Disease Management'] as $sp)
            <span class="xn-doc-specialty-tag">{{ $sp }}</span>
            @endforeach
        </div>
        <div class="xn-doc-hero-actions">
            <a href="{{ $heroCtaUrl }}" class="xn-doc-btn xn-doc-btn-primary"><i class="fas fa-calendar-plus"></i> {{ $heroCta }}</a>
            @if(!empty($profile['phone']))<a href="tel:{{ $profile['phone'] }}" class="xn-doc-btn xn-doc-btn-outline"><i class="fas fa-phone"></i> Call Clinic</a>@endif
        </div>
    </div>
    <div class="xn-doc-hero-img">
        @if($tenant->avatar)<img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}">
        @else🩺@endif
        @if(!empty($profile['timings']) || !empty($profile['clinic']))
        <div class="xn-doc-availability">
            <div class="xn-doc-availability-title">{{ !empty($profile['clinic']) ? $profile['clinic'] : 'Clinic' }}</div>
            <div class="xn-doc-availability-val">{{ $profile['timings'] ?? 'Mon–Sat: 9am–6pm' }}</div>
        </div>
        @endif
    </div>
</div>
@endif

{{-- STATS --}}
@if($_show('stats') && count($statsItems))
<div class="xn-doc-stats-row">
    @foreach($statsItems as $stat)
    <div class="xn-doc-stat-cell">
        @if(!empty($stat['icon']))<div class="xn-doc-stat-icon">{{ $stat['icon'] }}</div>@endif
        <div class="xn-doc-stat-num">{{ $stat['value'] ?? $stat['num'] ?? '' }}</div>
        <div class="xn-doc-stat-label">{{ $stat['label'] ?? '' }}</div>
    </div>
    @endforeach
</div>
@endif

{{-- SERVICES --}}
@if($_show('services'))
<div class="xn-doc-section" style="padding-top:0;">
    <div class="xn-doc-section-header">
        <div class="xn-doc-badge">Medical Services</div>
        <h2 class="xn-doc-section-title">{{ $servicesData['heading'] ?? 'Services Offered' }}</h2>
        @if(!empty($servicesData['subheading']))<p class="xn-doc-section-sub">{{ $servicesData['subheading'] }}</p>@endif
        <div class="xn-doc-divider"></div>
    </div>
    <div class="xn-doc-services-grid">
        @foreach($services as $svc)
        <div class="xn-doc-service-card">
            <div class="xn-doc-service-icon">{{ $svc['icon'] ?? '🩺' }}</div>
            <div class="xn-doc-service-title">{{ $svc['title'] ?? $svc['name'] ?? '' }}</div>
            <div class="xn-doc-service-text">{{ $svc['text'] ?? $svc['description'] ?? '' }}</div>
            @if(!empty($svc['price']))<div style="margin-top:0.75rem;font-size:0.8rem;font-weight:700;color:var(--doc-accent);">{{ $svc['price'] }}</div>@endif
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ url($tenantBase . '/appointments') }}" class="xn-doc-btn xn-doc-btn-outline"><i class="fas fa-calendar-plus"></i> Book Appointment</a>
    </div>
</div>
@endif

{{-- ABOUT --}}
@if($_show('about'))
<div class="xn-doc-section">
    <div class="xn-doc-about-grid">
        <div class="xn-doc-about-img">
            @if(!empty($aboutData['image']))<img src="{{ $aboutData['image'] }}" alt="{{ $tenant->name }}">
            @elseif($tenant->avatar)<img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}">
            @else🏥@endif
        </div>
        <div>
            <div class="xn-doc-badge">About</div>
            <h2 class="xn-doc-section-title" style="text-align:left;margin-bottom:1rem;">{{ $aboutData['heading'] ?? 'About Me' }}</h2>
            <p class="xn-doc-about-text">{{ $aboutData['text'] ?? $profile['about'] ?? $aboutText }}</p>
            @if(!empty($profile['clinic']) || !empty($profile['timings']) || !empty($profile['phone']) || !empty($profile['email']))
            <div class="xn-doc-clinic-info">
                @if(!empty($profile['clinic']))<div class="xn-doc-clinic-row"><div class="xn-doc-clinic-icon"><i class="fas fa-hospital"></i></div><div><div class="xn-doc-clinic-label">Clinic</div><div class="xn-doc-clinic-val">{{ $profile['clinic'] }}</div></div></div>@endif
                @if(!empty($profile['timings']))<div class="xn-doc-clinic-row"><div class="xn-doc-clinic-icon"><i class="fas fa-clock"></i></div><div><div class="xn-doc-clinic-label">Timings</div><div class="xn-doc-clinic-val">{{ $profile['timings'] }}</div></div></div>@endif
                @if(!empty($profile['phone']))<div class="xn-doc-clinic-row"><div class="xn-doc-clinic-icon"><i class="fas fa-phone"></i></div><div><div class="xn-doc-clinic-label">Phone</div><div class="xn-doc-clinic-val">{{ $profile['phone'] }}</div></div></div>@endif
                @if(!empty($profile['email']))<div class="xn-doc-clinic-row"><div class="xn-doc-clinic-icon"><i class="fas fa-envelope"></i></div><div><div class="xn-doc-clinic-label">Email</div><div class="xn-doc-clinic-val">{{ $profile['email'] }}</div></div></div>@endif
            </div>
            @endif
            <a href="{{ url($tenantBase . '/about') }}" class="xn-doc-btn xn-doc-btn-primary"><i class="fas fa-user-md"></i> Full Profile</a>
        </div>
    </div>
</div>
@endif

{{-- TESTIMONIALS --}}
@if($_show('testimonials') && count($testimonials))
<div class="xn-doc-section">
    <div class="xn-doc-section-header">
        <div class="xn-doc-badge">Patient Reviews</div>
        <h2 class="xn-doc-section-title">{{ $testimonialsData['heading'] ?? 'What Patients Say' }}</h2>
        <div class="xn-doc-divider"></div>
    </div>
    <div class="xn-doc-testimonials-grid">
        @foreach($testimonials as $t)
        <div class="xn-doc-testimonial-card">
            <div class="xn-doc-testimonial-stars">★★★★★</div>
            <p class="xn-doc-testimonial-text">"{{ $t['text'] ?? '' }}"</p>
            <div class="xn-doc-testimonial-author">
                <div class="xn-doc-testimonial-avatar">{{ strtoupper(substr($t['name'] ?? 'A', 0, 1)) }}</div>
                <div>
                    <div class="xn-doc-testimonial-name">{{ $t['name'] ?? '' }}</div>
                    <div class="xn-doc-testimonial-role">{{ $t['role'] ?? '' }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- BLOG --}}
@if($_show('blog') && (isset($featuredPost) && $featuredPost || !empty($categoryPosts)))
<div class="xn-doc-section">
    <div class="xn-doc-section-header">
        <div class="xn-doc-badge">Health Tips</div>
        <h2 class="xn-doc-section-title">{{ $blogData['heading'] ?? 'Health Articles' }}</h2>
        <div class="xn-doc-divider"></div>
    </div>
    @php
        $blogPosts = collect();
        foreach($categoryPosts ?? [] as $cp) { $blogPosts = $blogPosts->merge($cp['posts'] ?? []); }
        if($blogPosts->isEmpty() && isset($featuredPost) && $featuredPost) $blogPosts = collect([$featuredPost]);
        $blogPosts = $blogPosts->unique('id')->take($blogData['count'] ?? 3);
    @endphp
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;">
        @foreach($blogPosts as $post)
        <div style="background:#1a1a1a;border:1px solid rgba(255,255,255,0.07);border-radius:16px;overflow:hidden;transition:all 0.25s;">
            @if($post->featured_image)<img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" style="width:100%;height:160px;object-fit:cover;">@else<div style="height:160px;background:linear-gradient(135deg,color-mix(in srgb,var(--doc-accent) 20%,transparent),rgba(14,165,233,0.1));display:flex;align-items:center;justify-content:center;font-size:3rem;">🏥</div>@endif
            <div style="padding:1.25rem;">
                <a href="{{ url($tenantBase . '/blog/' . $post->slug) }}" style="font-size:0.95rem;font-weight:700;color:#fff;text-decoration:none;display:block;margin-bottom:0.5rem;line-height:1.4;">{{ $post->title }}</a>
                <div style="font-size:0.8rem;color:#64748b;line-height:1.6;">{{ Str::limit(strip_tags($post->content ?? $post->excerpt ?? ''), 90) }}</div>
            </div>
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ url($tenantBase . '/blog') }}" class="xn-doc-btn xn-doc-btn-outline"><i class="fas fa-newspaper"></i> All Health Articles</a>
    </div>
</div>
@endif

{{-- CTA --}}
@if($_show('contact'))
<div class="xn-doc-section">
    <div class="xn-doc-cta">
        <div class="xn-doc-badge" style="margin-bottom:1rem;">Appointments</div>
        <h2>{{ $contactHeading }}</h2>
        <p>{{ $contactText }}</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ $contactBtnUrl }}" class="xn-doc-btn xn-doc-btn-primary" style="font-size:1rem;padding:1rem 2.5rem;"><i class="fas fa-calendar-plus"></i> {{ $contactBtn }}</a>
            @if(!empty($profile['phone']))<a href="tel:{{ $profile['phone'] }}" class="xn-doc-btn xn-doc-btn-outline"><i class="fas fa-phone"></i> {{ $profile['phone'] }}</a>@endif
        </div>
    </div>
</div>
@endif

</div>
@endsection
