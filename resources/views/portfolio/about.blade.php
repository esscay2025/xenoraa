@extends('layouts.app')
@section('title', 'About ' . $siteName . ' | ' . ($settings['profile_title'] ?? 'Professional'))
@section('description', Str::limit(strip_tags($settings['profile_about'] ?? $tenant->bio ?? 'Learn more about ' . $siteName), 160))
@push('styles')
<style>
:root { --pg-accent: {{ $accentColor }}; }

/* ── Hero ── */
.ab-hero {
    position: relative;
    background: linear-gradient(135deg, #0a0a0a 0%, #111 50%, #0a0a0a 100%);
    overflow: hidden;
    padding: 5rem 0 0;
}
.ab-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 80% 60% at 60% 40%, color-mix(in srgb, var(--pg-accent) 18%, transparent), transparent 70%);
    pointer-events: none;
}
.ab-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 4rem;
    align-items: end;
}
@media (max-width: 900px) {
    .ab-hero-inner { grid-template-columns: 1fr; gap: 2rem; align-items: center; text-align: center; }
}
.ab-photo-col { position: relative; padding-bottom: 3rem; }
.ab-photo-frame {
    position: relative;
    width: 260px;
    height: 320px;
    border-radius: 24px;
    overflow: hidden;
    border: 3px solid color-mix(in srgb, var(--pg-accent) 40%, transparent);
    box-shadow: 0 32px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05);
    background: #1a1a1a;
}
@media (max-width: 900px) { .ab-photo-frame { width: 200px; height: 240px; margin: 0 auto; } }
.ab-photo-frame img { width: 100%; height: 100%; object-fit: cover; object-position: top center; display: block; }
.ab-photo-initials {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 5rem; font-weight: 900; color: var(--pg-accent);
}
.ab-photo-badge {
    position: absolute;
    bottom: 3.5rem;
    right: -1rem;
    background: var(--pg-accent);
    color: #fff;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 700;
    box-shadow: 0 4px 16px rgba(0,0,0,0.3);
    white-space: nowrap;
}
@media (max-width: 900px) { .ab-photo-badge { right: auto; left: 50%; transform: translateX(-50%); bottom: 1rem; } }
.ab-info-col { padding-bottom: 3rem; }
.ab-profession-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: color-mix(in srgb, var(--pg-accent) 15%, transparent);
    border: 1px solid color-mix(in srgb, var(--pg-accent) 40%, transparent);
    color: var(--pg-accent);
    padding: 0.35rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 1.25rem;
}
.ab-name { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; margin: 0 0 0.5rem; line-height: 1.1; color: #fff; }
.ab-tagline { font-size: 1.1rem; color: #a0a0a0; margin: 0 0 1.5rem; }
.ab-bio { font-size: 1rem; line-height: 1.85; color: #c0c0c0; margin: 0 0 2rem; }
.ab-stats-strip { display: flex; gap: 2.5rem; flex-wrap: wrap; padding: 1.5rem 0; border-top: 1px solid #2a2a2a; }
@media (max-width: 900px) { .ab-stats-strip { justify-content: center; } }
.ab-stat-value { font-size: 2rem; font-weight: 900; color: var(--pg-accent); line-height: 1; }
.ab-stat-label { font-size: 0.72rem; color: #666; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 0.25rem; }
.ab-contact-row { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 1.75rem; }
@media (max-width: 900px) { .ab-contact-row { justify-content: center; } }
.ab-contact-chip {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.5rem 1rem; background: #1a1a1a; border: 1px solid #2a2a2a;
    border-radius: 50px; font-size: 0.85rem; color: #a0a0a0; text-decoration: none; transition: all 0.2s;
}
.ab-contact-chip:hover { border-color: var(--pg-accent); color: var(--pg-accent); }
.ab-contact-chip i { color: var(--pg-accent); }

/* ── Body ── */
.ab-body { max-width: 1200px; margin: 0 auto; padding: 4rem 2rem; }
@media (max-width: 768px) { .ab-body { padding: 2.5rem 1rem; } .ab-hero-inner { padding: 0 1rem; } }
.ab-section-label {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;
    color: var(--pg-accent); margin-bottom: 0.4rem;
}
.ab-section-label::before { content: ''; width: 18px; height: 2px; background: var(--pg-accent); border-radius: 1px; }
.ab-section-title { font-size: 1.65rem; font-weight: 800; margin: 0 0 0.4rem; color: #fff; }
.ab-section-sub { color: #666; margin: 0 0 2rem; font-size: 0.9rem; }
.ab-divider { border: none; border-top: 1px solid #1e1e1e; margin: 3rem 0; }

/* Info cards */
.ab-info-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 3rem; }
.ab-info-card { background: #111; border: 1px solid #2a2a2a; border-radius: 16px; padding: 1.25rem; display: flex; align-items: flex-start; gap: 1rem; transition: border-color 0.2s; }
.ab-info-card:hover { border-color: color-mix(in srgb, var(--pg-accent) 50%, transparent); }
.ab-info-card-icon { width: 42px; height: 42px; border-radius: 10px; background: color-mix(in srgb, var(--pg-accent) 15%, transparent); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem; color: var(--pg-accent); }
.ab-info-card-label { font-size: 0.72rem; color: #666; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.2rem; }
.ab-info-card-value { font-size: 0.9rem; font-weight: 600; color: #e0e0e0; }

/* Timeline */
.ab-timeline { position: relative; padding-left: 2rem; }
.ab-timeline::before { content: ''; position: absolute; left: 0.5rem; top: 0; bottom: 0; width: 2px; background: linear-gradient(to bottom, var(--pg-accent), #2a2a2a); border-radius: 1px; }
.ab-timeline-item { position: relative; margin-bottom: 1.75rem; }
.ab-timeline-dot { position: absolute; left: -1.65rem; top: 0.4rem; width: 14px; height: 14px; border-radius: 50%; background: var(--pg-accent); border: 2px solid #0a0a0a; box-shadow: 0 0 0 3px color-mix(in srgb, var(--pg-accent) 30%, transparent); }
.ab-timeline-card { background: #111; border: 1px solid #2a2a2a; border-radius: 16px; padding: 1.5rem; transition: border-color 0.2s; }
.ab-timeline-card:hover { border-color: color-mix(in srgb, var(--pg-accent) 40%, transparent); }
.ab-timeline-header { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.4rem; }
.ab-timeline-title { font-size: 1rem; font-weight: 700; color: #fff; margin: 0; }
.ab-timeline-company { color: var(--pg-accent); font-weight: 600; font-size: 0.875rem; margin: 0.2rem 0 0; }
.ab-timeline-date { font-size: 0.78rem; color: #666; white-space: nowrap; }
.ab-timeline-desc { color: #a0a0a0; font-size: 0.875rem; line-height: 1.6; margin: 0.5rem 0 0; }

/* Skills */
.ab-skills-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem; }
.ab-skill-card { background: #111; border: 1px solid #2a2a2a; border-radius: 12px; padding: 1rem 1.25rem; }
.ab-skill-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
.ab-skill-name { font-weight: 600; font-size: 0.875rem; color: #e0e0e0; }
.ab-skill-pct { font-size: 0.78rem; color: var(--pg-accent); font-weight: 700; }
.ab-skill-bar { height: 5px; background: #2a2a2a; border-radius: 3px; overflow: hidden; }
.ab-skill-fill { height: 100%; background: linear-gradient(90deg, var(--pg-accent), color-mix(in srgb, var(--pg-accent) 60%, #fff)); border-radius: 3px; }
.ab-skill-cat { font-size: 0.72rem; color: #555; margin-top: 0.3rem; }

/* Education */
.ab-edu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr)); gap: 1rem; }
.ab-edu-card { background: #111; border: 1px solid #2a2a2a; border-radius: 16px; padding: 1.5rem; transition: border-color 0.2s; }
.ab-edu-card:hover { border-color: color-mix(in srgb, var(--pg-accent) 40%, transparent); }
.ab-edu-degree { font-size: 1rem; font-weight: 700; color: #fff; margin: 0 0 0.25rem; }
.ab-edu-inst { color: var(--pg-accent); font-weight: 600; font-size: 0.875rem; margin: 0 0 0.2rem; }
.ab-edu-field { color: #a0a0a0; font-size: 0.85rem; margin: 0 0 0.2rem; }
.ab-edu-year { color: #555; font-size: 0.78rem; margin: 0; }

/* Certs */
.ab-cert-card { background: #111; border: 1px solid #2a2a2a; border-radius: 16px; padding: 1.25rem; display: flex; gap: 1rem; align-items: flex-start; transition: border-color 0.2s; }
.ab-cert-card:hover { border-color: color-mix(in srgb, var(--pg-accent) 40%, transparent); }
.ab-cert-icon { width: 42px; height: 42px; border-radius: 10px; background: color-mix(in srgb, var(--pg-accent) 15%, transparent); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--pg-accent); font-size: 1.1rem; }
.ab-cert-name { font-size: 0.9rem; font-weight: 700; color: #fff; margin: 0 0 0.2rem; }
.ab-cert-org { color: #a0a0a0; font-size: 0.82rem; margin: 0 0 0.2rem; }
.ab-cert-date { color: #555; font-size: 0.78rem; margin: 0; }

/* Languages */
.ab-lang-list { display: flex; flex-wrap: wrap; gap: 0.75rem; }
.ab-lang-chip { background: #111; border: 1px solid #2a2a2a; border-radius: 50px; padding: 0.6rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; }
.ab-lang-name { font-weight: 600; font-size: 0.875rem; color: #e0e0e0; }
.ab-lang-level { font-size: 0.72rem; background: color-mix(in srgb, var(--pg-accent) 15%, transparent); color: var(--pg-accent); padding: 0.2rem 0.6rem; border-radius: 20px; font-weight: 700; }

/* Social */
.ab-social-row { display: flex; gap: 0.75rem; flex-wrap: wrap; }
.ab-social-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; background: #111; border: 1px solid #2a2a2a; border-radius: 8px; color: #a0a0a0; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: all 0.2s; }
.ab-social-btn:hover { border-color: var(--pg-accent); color: var(--pg-accent); }

/* CTA */
.ab-cta-band { background: linear-gradient(135deg, color-mix(in srgb, var(--pg-accent) 18%, #0a0a0a), #0a0a0a); border: 1px solid color-mix(in srgb, var(--pg-accent) 30%, transparent); border-radius: 24px; padding: 3rem 2rem; text-align: center; margin-top: 4rem; }
.ab-cta-band h2 { font-size: 1.9rem; font-weight: 800; color: #fff; margin: 0 0 0.75rem; }
.ab-cta-band p { color: #a0a0a0; margin: 0 0 2rem; }
.ab-cta-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2.5rem; background: var(--pg-accent); color: #fff; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 1rem; transition: opacity 0.2s, transform 0.2s; }
.ab-cta-btn:hover { opacity: 0.88; transform: translateY(-1px); }
@media (max-width: 768px) { .ab-cta-band { padding: 2rem 1.25rem; } .ab-cta-band h2 { font-size: 1.4rem; } }
</style>
@endpush
@section('content')
@php
    $accent = $accentColor ?? '#6366f1';
    $profTitle = $settings['profile_title'] ?? ($tenant->profession ?? 'Professional');
    $profAbout = $settings['profile_about'] ?? ($tenant->bio ?? '');
    $profYears = $settings['profile_years'] ?? '';
    $profClients = $settings['profile_clients'] ?? '';
    $profProjects = $settings['profile_projects'] ?? '';
    $profRevenue = $settings['profile_revenue'] ?? '';
    $tmpl = $template ?? 'consultant';

    $profBadgeMap = [
        'influencer'   => ['icon' => 'fas fa-star',          'text' => 'Content Creator'],
        'consultant'   => ['icon' => 'fas fa-briefcase',     'text' => 'Business Consultant'],
        'advocate'     => ['icon' => 'fas fa-balance-scale', 'text' => 'Legal Advocate'],
        'doctor'       => ['icon' => 'fas fa-user-md',       'text' => 'Medical Professional'],
        'entrepreneur' => ['icon' => 'fas fa-rocket',        'text' => 'Entrepreneur'],
        'politician'   => ['icon' => 'fas fa-landmark',      'text' => 'Public Servant'],
    ];
    $badge = $profBadgeMap[$tmpl] ?? ['icon' => 'fas fa-user', 'text' => 'Professional'];

    $infoCards = [];
    if ($tmpl === 'influencer') {
        if (!empty($profile['handle'])) $infoCards[] = ['icon' => 'fas fa-at',       'label' => 'Handle',          'value' => $profile['handle']];
        if (!empty($profile['niche']))  $infoCards[] = ['icon' => 'fas fa-hashtag',  'label' => 'Niche',           'value' => $profile['niche']];
        if (!empty($profile['followers_total'])) $infoCards[] = ['icon' => 'fas fa-users', 'label' => 'Total Followers', 'value' => $profile['followers_total']];
        if (!empty($profile['collab_email'])) $infoCards[] = ['icon' => 'fas fa-envelope', 'label' => 'Collab Email', 'value' => $profile['collab_email']];
    } elseif ($tmpl === 'advocate') {
        if (!empty($profile['enrollment_no'])) $infoCards[] = ['icon' => 'fas fa-id-badge',       'label' => 'Enrollment No.',    'value' => $profile['enrollment_no']];
        if (!empty($profile['bar_number']))    $infoCards[] = ['icon' => 'fas fa-gavel',           'label' => 'Bar Number',        'value' => $profile['bar_number']];
        if (!empty($profile['court']))         $infoCards[] = ['icon' => 'fas fa-landmark',        'label' => 'Court',             'value' => $profile['court']];
        if (!empty($profile['chamber']))       $infoCards[] = ['icon' => 'fas fa-map-marker-alt',  'label' => 'Chamber',           'value' => $profile['chamber']];
        if (!empty($profYears))                $infoCards[] = ['icon' => 'fas fa-calendar-alt',    'label' => 'Years of Practice', 'value' => $profYears . ' Years'];
    } elseif ($tmpl === 'doctor') {
        if (!empty($profile['registration_no'])) $infoCards[] = ['icon' => 'fas fa-id-card',       'label' => 'Reg. No.',          'value' => $profile['registration_no']];
        if (!empty($profile['clinic']))          $infoCards[] = ['icon' => 'fas fa-hospital',       'label' => 'Clinic / Hospital', 'value' => $profile['clinic']];
        if (!empty($profile['timings']))         $infoCards[] = ['icon' => 'fas fa-clock',          'label' => 'Timings',           'value' => $profile['timings']];
        if (!empty($profile['phone']))           $infoCards[] = ['icon' => 'fas fa-phone',          'label' => 'Appointments',      'value' => $profile['phone']];
    } elseif ($tmpl === 'entrepreneur') {
        if (!empty($profile['ventures_built'])) $infoCards[] = ['icon' => 'fas fa-rocket',        'label' => 'Ventures Built',  'value' => $profile['ventures_built']];
        if (!empty($profile['funding_raised'])) $infoCards[] = ['icon' => 'fas fa-dollar-sign',   'label' => 'Funding Raised',  'value' => $profile['funding_raised']];
        if (!empty($profile['team_size']))      $infoCards[] = ['icon' => 'fas fa-users',          'label' => 'Team Size',       'value' => $profile['team_size']];
        if (!empty($profile['industries']))     $infoCards[] = ['icon' => 'fas fa-industry',       'label' => 'Industries',      'value' => is_array($profile['industries']) ? implode(', ', $profile['industries']) : $profile['industries']];
    } elseif ($tmpl === 'politician') {
        if (!empty($profile['constituency'])) $infoCards[] = ['icon' => 'fas fa-map-pin', 'label' => 'Constituency', 'value' => $profile['constituency']];
        if (!empty($profile['party']))        $infoCards[] = ['icon' => 'fas fa-flag',    'label' => 'Party',        'value' => $profile['party']];
        if (!empty($profYears))               $infoCards[] = ['icon' => 'fas fa-calendar','label' => 'Years in Service', 'value' => $profYears];
    } else {
        if (!empty($profYears))    $infoCards[] = ['icon' => 'fas fa-calendar-alt', 'label' => 'Years Experience', 'value' => $profYears . ' Years'];
        if (!empty($profClients))  $infoCards[] = ['icon' => 'fas fa-handshake',    'label' => 'Clients Served',   'value' => $profClients];
        if (!empty($profProjects)) $infoCards[] = ['icon' => 'fas fa-check-circle', 'label' => 'Projects Done',    'value' => $profProjects];
        if (!empty($profile['email'])) $infoCards[] = ['icon' => 'fas fa-envelope', 'label' => 'Email',            'value' => $profile['email']];
    }

    $stats = [];
    if ($tmpl === 'influencer') {
        if (!empty($profile['followers_total']))      $stats[] = ['value' => $profile['followers_total'],      'label' => 'Followers'];
        if (!empty($profile['instagram_followers']))  $stats[] = ['value' => $profile['instagram_followers'],  'label' => 'Instagram'];
        if (!empty($profile['youtube_subscribers']))  $stats[] = ['value' => $profile['youtube_subscribers'],  'label' => 'YouTube'];
        if (!empty($profile['twitter_followers']))    $stats[] = ['value' => $profile['twitter_followers'],    'label' => 'Twitter'];
    } else {
        if (!empty($profYears))    $stats[] = ['value' => $profYears,    'label' => 'Years Exp.'];
        if (!empty($profClients))  $stats[] = ['value' => $profClients,  'label' => 'Clients'];
        if (!empty($profProjects)) $stats[] = ['value' => $profProjects, 'label' => 'Projects'];
        if (!empty($profRevenue))  $stats[] = ['value' => $profRevenue,  'label' => 'Revenue'];
    }

    $ctaMap = [
        'influencer'   => ['heading' => "Let's Create Together",          'sub' => 'Open for brand collaborations, sponsored content, and creative partnerships.', 'btn' => 'Start Collaboration', 'url' => '/collaborations'],
        'consultant'   => ['heading' => 'Ready to Transform Your Business?','sub' => 'Book a free discovery call and let\'s explore how I can help you grow.',   'btn' => 'Book a Call',         'url' => $profile['booking_link'] ?? '/contact'],
        'advocate'     => ['heading' => 'Need Legal Guidance?',            'sub' => 'Schedule a consultation to discuss your legal matter confidentially.',        'btn' => 'Book Consultation',   'url' => $profile['consultation_link'] ?? '/contact'],
        'doctor'       => ['heading' => 'Book an Appointment',             'sub' => 'Schedule your consultation with our experienced medical team.',               'btn' => 'Book Appointment',    'url' => $profile['appointment_link'] ?? '/appointments'],
        'entrepreneur' => ['heading' => "Let's Build Something Great",     'sub' => 'Open to investment discussions, advisory roles, and strategic partnerships.', 'btn' => 'Get in Touch',        'url' => '/contact'],
        'politician'   => ['heading' => 'Connect With Your Representative','sub' => 'Reach out to share your concerns, ideas, or support for our initiatives.',    'btn' => 'Contact Office',      'url' => '/contact'],
    ];
    $cta = $ctaMap[$tmpl] ?? $ctaMap['consultant'];

    $aboutHeroData = $aboutPage ? $aboutPage->getSectionData('hero') : [];
    $pageHeading = !empty($aboutHeroData['heading']) ? $aboutHeroData['heading'] : ('About ' . $siteName);
    $pageSub = !empty($aboutHeroData['subheading']) ? $aboutHeroData['subheading'] : $profTitle;
@endphp

{{-- ══ HERO ══ --}}
<section class="ab-hero">
    <div class="ab-hero-inner">
        <div class="ab-photo-col">
            <div class="ab-photo-frame">
                @if($tenant->avatar)
                    <img src="{{ asset('storage/' . $tenant->avatar) }}" alt="{{ $siteName }}">
                @else
                    <div class="ab-photo-initials">{{ strtoupper(substr($siteName, 0, 1)) }}</div>
                @endif
            </div>
            @if(!empty($profTitle))
            <div class="ab-photo-badge"><i class="{{ $badge['icon'] }}"></i> {{ $profTitle }}</div>
            @endif
        </div>
        <div class="ab-info-col">
            <div class="ab-profession-badge"><i class="{{ $badge['icon'] }}"></i> {{ $badge['text'] }}</div>
            <h1 class="ab-name">{{ $pageHeading }}</h1>
            <p class="ab-tagline">{{ $pageSub }}</p>
            @if(!empty($profAbout))
            <p class="ab-bio">{{ $profAbout }}</p>
            @endif
            @if(!empty($stats))
            <div class="ab-stats-strip">
                @foreach($stats as $stat)
                <div>
                    <div class="ab-stat-value">{{ $stat['value'] }}</div>
                    <div class="ab-stat-label">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
            @endif
            <div class="ab-contact-row">
                @if(!empty($profile['email']))
                <a href="mailto:{{ $profile['email'] }}" class="ab-contact-chip"><i class="fas fa-envelope"></i> {{ $profile['email'] }}</a>
                @endif
                @if(!empty($profile['phone']))
                <a href="tel:{{ $profile['phone'] }}" class="ab-contact-chip"><i class="fas fa-phone"></i> {{ $profile['phone'] }}</a>
                @endif
                @if(!empty($profile['address']))
                <span class="ab-contact-chip"><i class="fas fa-map-marker-alt"></i> {{ $profile['address'] }}</span>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ══ BODY ══ --}}
<div class="ab-body">

    {{-- Profession-specific info cards --}}
    @if(!empty($infoCards))
    <section style="margin-bottom:3.5rem;">
        <div style="margin-bottom:1.75rem;">
            <div class="ab-section-label">Professional Details</div>
            <h2 class="ab-section-title">Key Information</h2>
        </div>
        <div class="ab-info-cards">
            @foreach($infoCards as $ic)
            <div class="ab-info-card">
                <div class="ab-info-card-icon"><i class="{{ $ic['icon'] }}"></i></div>
                <div>
                    <div class="ab-info-card-label">{{ $ic['label'] }}</div>
                    <div class="ab-info-card-value">{{ $ic['value'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Expertise / Specializations --}}
    @php
        $expertiseList = $profile['expertise'] ?? $profile['specializations'] ?? $profile['practice_areas'] ?? [];
        $expertiseLabel = $tmpl === 'advocate' ? 'Practice Areas' : ($tmpl === 'doctor' ? 'Specializations' : 'Areas of Expertise');
    @endphp
    @if(!empty($expertiseList))
    <section style="margin-bottom:3.5rem;">
        <div style="margin-bottom:1.75rem;">
            <div class="ab-section-label">Expertise</div>
            <h2 class="ab-section-title">{{ $expertiseLabel }}</h2>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
            @foreach($expertiseList as $exp)
            <span style="background:color-mix(in srgb, var(--pg-accent) 12%, #111);border:1px solid color-mix(in srgb, var(--pg-accent) 30%, transparent);color:var(--pg-accent);padding:0.5rem 1.25rem;border-radius:50px;font-size:0.875rem;font-weight:600;">
                {{ is_array($exp) ? ($exp['name'] ?? $exp['area'] ?? '') : $exp }}
            </span>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Experience Timeline --}}
    @if($experiences->count() > 0)
    <section style="margin-bottom:3.5rem;">
        <div style="margin-bottom:1.75rem;">
            <div class="ab-section-label">Career</div>
            <h2 class="ab-section-title">Experience</h2>
        </div>
        <div class="ab-timeline">
            @foreach($experiences as $exp)
            <div class="ab-timeline-item">
                <div class="ab-timeline-dot"></div>
                <div class="ab-timeline-card">
                    <div class="ab-timeline-header">
                        <div>
                            <h3 class="ab-timeline-title">{{ $exp->title }}</h3>
                            <p class="ab-timeline-company">{{ $exp->company }}</p>
                        </div>
                        <span class="ab-timeline-date">
                            {{ $exp->start_date?->format('M Y') }} — {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Present' }}
                        </span>
                    </div>
                    @if($exp->description)
                    <p class="ab-timeline-desc">{{ $exp->description }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Skills --}}
    @if($skills->count() > 0)
    <section style="margin-bottom:3.5rem;">
        <div style="margin-bottom:1.75rem;">
            <div class="ab-section-label">Capabilities</div>
            <h2 class="ab-section-title">Skills & Proficiency</h2>
        </div>
        <div class="ab-skills-grid">
            @foreach($skills as $skill)
            <div class="ab-skill-card">
                <div class="ab-skill-header">
                    <span class="ab-skill-name">{{ $skill->name }}</span>
                    <span class="ab-skill-pct">{{ $skill->proficiency }}%</span>
                </div>
                <div class="ab-skill-bar">
                    <div class="ab-skill-fill" style="width:{{ $skill->proficiency }}%;"></div>
                </div>
                @if($skill->category)
                <div class="ab-skill-cat">{{ $skill->category }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Education --}}
    @if($education->count() > 0)
    <section style="margin-bottom:3.5rem;">
        <div style="margin-bottom:1.75rem;">
            <div class="ab-section-label">Academic</div>
            <h2 class="ab-section-title">Education</h2>
        </div>
        <div class="ab-edu-grid">
            @foreach($education as $edu)
            <div class="ab-edu-card">
                <div class="ab-edu-degree">{{ $edu->degree }}</div>
                <div class="ab-edu-inst">{{ $edu->institution }}</div>
                @if($edu->field_of_study)<div class="ab-edu-field">{{ $edu->field_of_study }}</div>@endif
                <div class="ab-edu-year">{{ $edu->start_date?->format('Y') }} — {{ $edu->end_date ? $edu->end_date->format('Y') : 'Present' }}</div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Certifications --}}
    @if($certifications->count() > 0)
    <section style="margin-bottom:3.5rem;">
        <div style="margin-bottom:1.75rem;">
            <div class="ab-section-label">Credentials</div>
            <h2 class="ab-section-title">Certifications</h2>
        </div>
        <div class="ab-edu-grid">
            @foreach($certifications as $cert)
            <div class="ab-cert-card">
                <div class="ab-cert-icon"><i class="fas fa-certificate"></i></div>
                <div>
                    <div class="ab-cert-name">{{ $cert->name }}</div>
                    <div class="ab-cert-org">{{ $cert->issuing_organization }}</div>
                    @if($cert->issue_date)<div class="ab-cert-date">{{ $cert->issue_date->format('M Y') }}</div>@endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Languages --}}
    @if($languages->count() > 0)
    <section style="margin-bottom:3.5rem;">
        <div style="margin-bottom:1.75rem;">
            <div class="ab-section-label">Communication</div>
            <h2 class="ab-section-title">Languages</h2>
        </div>
        <div class="ab-lang-list">
            @foreach($languages as $lang)
            <div class="ab-lang-chip">
                <span class="ab-lang-name">{{ $lang->language }}</span>
                <span class="ab-lang-level">{{ ucfirst($lang->proficiency) }}</span>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Social Links --}}
    @if($socialLinks->count() > 0)
    <section style="margin-bottom:3.5rem;">
        <div style="margin-bottom:1.75rem;">
            <div class="ab-section-label">Social</div>
            <h2 class="ab-section-title">Connect with Me</h2>
        </div>
        <div class="ab-social-row">
            @foreach($socialLinks as $social)
            @if(!empty($social->url) && strlen($social->url) > 15)
            <a href="{{ $social->url }}" target="_blank" rel="noopener" class="ab-social-btn">
                <i class="{{ $social->icon_class }}"></i> {{ ucfirst($social->platform) }}
            </a>
            @endif
            @endforeach
        </div>
    </section>
    @endif

    {{-- CTA Band --}}
    <div class="ab-cta-band">
        <h2>{{ $cta['heading'] }}</h2>
        <p>{{ $cta['sub'] }}</p>
        <a href="{{ $cta['url'] }}" class="ab-cta-btn">
            <i class="fas fa-arrow-right"></i> {{ $cta['btn'] }}
        </a>
    </div>

</div>
@endsection
