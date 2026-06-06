@extends('layouts.app')

@section('title', 'About ' . $siteName)

@section('content')
{{-- Hero Section --}}
<section style="background:linear-gradient(135deg, var(--accent, #6366f1) 0%, #4f46e5 100%);color:#fff;padding:5rem 0 3rem;">
    <div style="max-width:1100px;margin:0 auto;padding:0 1.5rem;display:flex;gap:3rem;align-items:center;flex-wrap:wrap;">
        <div style="flex:1;min-width:260px;">
            @if($tenant->avatar)
                <img src="{{ asset('storage/' . $tenant->avatar) }}" alt="{{ $siteName }}"
                     style="width:140px;height:140px;border-radius:50%;object-fit:cover;border:4px solid rgba(255,255,255,0.3);margin-bottom:1.5rem;">
            @else
                <div style="width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:3rem;font-weight:800;margin-bottom:1.5rem;border:4px solid rgba(255,255,255,0.3);">
                    {{ strtoupper(substr($siteName, 0, 1)) }}
                </div>
            @endif
            <h1 style="font-size:2.5rem;font-weight:800;margin:0 0 0.5rem;">{{ $siteName }}</h1>
            <p style="font-size:1.1rem;opacity:0.85;margin:0 0 1rem;">{{ $settings['site_tagline'] ?? $tenant->profession ?? 'Professional' }}</p>
            @if(!empty($settings['contact_email']))
            <p style="opacity:0.75;font-size:0.9rem;margin:0;"><i class="fas fa-envelope" style="margin-right:0.4rem;"></i> {{ $settings['contact_email'] }}</p>
            @endif
        </div>
        <div style="flex:2;min-width:260px;">
            <p style="font-size:1.1rem;line-height:1.8;opacity:0.9;margin:0 0 2rem;">
                {{ $settings['profile_about'] ?? $tenant->bio ?? 'Welcome to my profile. I am a dedicated professional committed to delivering excellence.' }}
            </p>
            {{-- Stats --}}
            @if(!empty($settings['profile_years']) || !empty($settings['profile_clients']) || !empty($settings['profile_projects']))
            <div style="display:flex;gap:2rem;flex-wrap:wrap;">
                @if(!empty($settings['profile_years']))
                <div style="text-align:center;">
                    <div style="font-size:2rem;font-weight:800;">{{ $settings['profile_years'] }}</div>
                    <div style="font-size:0.8rem;opacity:0.75;text-transform:uppercase;letter-spacing:0.05em;">Years Experience</div>
                </div>
                @endif
                @if(!empty($settings['profile_clients']))
                <div style="text-align:center;">
                    <div style="font-size:2rem;font-weight:800;">{{ $settings['profile_clients'] }}</div>
                    <div style="font-size:0.8rem;opacity:0.75;text-transform:uppercase;letter-spacing:0.05em;">Clients</div>
                </div>
                @endif
                @if(!empty($settings['profile_projects']))
                <div style="text-align:center;">
                    <div style="font-size:2rem;font-weight:800;">{{ $settings['profile_projects'] }}</div>
                    <div style="font-size:0.8rem;opacity:0.75;text-transform:uppercase;letter-spacing:0.05em;">Projects</div>
                </div>
                @endif
                @if(!empty($settings['profile_revenue']))
                <div style="text-align:center;">
                    <div style="font-size:2rem;font-weight:800;">{{ $settings['profile_revenue'] }}</div>
                    <div style="font-size:0.8rem;opacity:0.75;text-transform:uppercase;letter-spacing:0.05em;">Revenue</div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</section>

<div style="max-width:1100px;margin:0 auto;padding:3rem 1.5rem;">

    {{-- Experience Section --}}
    @if($experiences->count() > 0)
    <section style="margin-bottom:4rem;">
        <h2 style="font-size:1.75rem;font-weight:800;margin:0 0 2rem;display:flex;align-items:center;gap:0.75rem;">
            <span style="width:4px;height:2rem;background:{{ $accentColor }};border-radius:2px;display:inline-block;"></span>
            Experience
        </h2>
        <div style="position:relative;padding-left:2rem;">
            <div style="position:absolute;left:0.5rem;top:0;bottom:0;width:2px;background:var(--border, #e5e7eb);"></div>
            @foreach($experiences as $exp)
            <div style="position:relative;margin-bottom:2rem;padding-left:1.5rem;">
                <div style="position:absolute;left:-1.6rem;top:0.3rem;width:12px;height:12px;border-radius:50%;background:{{ $accentColor }};border:2px solid #fff;box-shadow:0 0 0 3px {{ $accentColor }}33;"></div>
                <div style="background:var(--bg-card, #fff);border:1px solid var(--border, #e5e7eb);border-radius:12px;padding:1.25rem 1.5rem;">
                    <div style="display:flex;justify-content:space-between;align-items:start;flex-wrap:wrap;gap:0.5rem;margin-bottom:0.5rem;">
                        <div>
                            <h3 style="font-size:1.05rem;font-weight:700;margin:0;">{{ $exp->title }}</h3>
                            <p style="color:{{ $accentColor }};font-weight:600;margin:0.25rem 0 0;font-size:0.9rem;">{{ $exp->company }}</p>
                        </div>
                        <span style="font-size:0.8rem;color:var(--text-muted, #9ca3af);white-space:nowrap;">
                            {{ $exp->start_date?->format('M Y') }} — {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Present' }}
                        </span>
                    </div>
                    @if($exp->description)
                    <p style="color:var(--text-secondary, #6b7280);font-size:0.9rem;margin:0.5rem 0 0;line-height:1.6;">{{ $exp->description }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Skills Section --}}
    @if($skills->count() > 0)
    <section style="margin-bottom:4rem;">
        <h2 style="font-size:1.75rem;font-weight:800;margin:0 0 2rem;display:flex;align-items:center;gap:0.75rem;">
            <span style="width:4px;height:2rem;background:{{ $accentColor }};border-radius:2px;display:inline-block;"></span>
            Skills
        </h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem;">
            @foreach($skills as $skill)
            <div style="background:var(--bg-card, #fff);border:1px solid var(--border, #e5e7eb);border-radius:10px;padding:1rem 1.25rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
                    <span style="font-weight:600;font-size:0.9rem;">{{ $skill->name }}</span>
                    <span style="font-size:0.8rem;color:var(--text-muted, #9ca3af);">{{ $skill->proficiency }}%</span>
                </div>
                <div style="height:6px;background:var(--border, #e5e7eb);border-radius:3px;overflow:hidden;">
                    <div style="height:100%;width:{{ $skill->proficiency }}%;background:{{ $accentColor }};border-radius:3px;"></div>
                </div>
                @if($skill->category)
                <span style="font-size:0.75rem;color:var(--text-muted, #9ca3af);margin-top:0.3rem;display:block;">{{ $skill->category }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Education Section --}}
    @if($education->count() > 0)
    <section style="margin-bottom:4rem;">
        <h2 style="font-size:1.75rem;font-weight:800;margin:0 0 2rem;display:flex;align-items:center;gap:0.75rem;">
            <span style="width:4px;height:2rem;background:{{ $accentColor }};border-radius:2px;display:inline-block;"></span>
            Education
        </h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1rem;">
            @foreach($education as $edu)
            <div style="background:var(--bg-card, #fff);border:1px solid var(--border, #e5e7eb);border-radius:12px;padding:1.25rem 1.5rem;">
                <h3 style="font-size:1rem;font-weight:700;margin:0 0 0.25rem;">{{ $edu->degree }}</h3>
                <p style="color:{{ $accentColor }};font-weight:600;font-size:0.9rem;margin:0 0 0.25rem;">{{ $edu->institution }}</p>
                @if($edu->field_of_study)
                <p style="color:var(--text-secondary, #6b7280);font-size:0.85rem;margin:0 0 0.25rem;">{{ $edu->field_of_study }}</p>
                @endif
                <p style="color:var(--text-muted, #9ca3af);font-size:0.8rem;margin:0;">
                    {{ $edu->start_date?->format('Y') }} — {{ $edu->end_date ? $edu->end_date->format('Y') : 'Present' }}
                </p>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Certifications Section --}}
    @if($certifications->count() > 0)
    <section style="margin-bottom:4rem;">
        <h2 style="font-size:1.75rem;font-weight:800;margin:0 0 2rem;display:flex;align-items:center;gap:0.75rem;">
            <span style="width:4px;height:2rem;background:{{ $accentColor }};border-radius:2px;display:inline-block;"></span>
            Certifications
        </h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
            @foreach($certifications as $cert)
            <div style="background:var(--bg-card, #fff);border:1px solid var(--border, #e5e7eb);border-radius:12px;padding:1.25rem 1.5rem;display:flex;gap:1rem;align-items:start;">
                <div style="width:44px;height:44px;border-radius:10px;background:{{ $accentColor }}20;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-certificate" style="color:{{ $accentColor }};font-size:1.1rem;"></i>
                </div>
                <div>
                    <h3 style="font-size:0.95rem;font-weight:700;margin:0 0 0.2rem;">{{ $cert->name }}</h3>
                    <p style="color:var(--text-secondary, #6b7280);font-size:0.85rem;margin:0 0 0.2rem;">{{ $cert->issuing_organization }}</p>
                    @if($cert->issue_date)
                    <p style="color:var(--text-muted, #9ca3af);font-size:0.8rem;margin:0;">{{ $cert->issue_date->format('M Y') }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Languages Section --}}
    @if($languages->count() > 0)
    <section style="margin-bottom:4rem;">
        <h2 style="font-size:1.75rem;font-weight:800;margin:0 0 2rem;display:flex;align-items:center;gap:0.75rem;">
            <span style="width:4px;height:2rem;background:{{ $accentColor }};border-radius:2px;display:inline-block;"></span>
            Languages
        </h2>
        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
            @foreach($languages as $lang)
            <div style="background:var(--bg-card, #fff);border:1px solid var(--border, #e5e7eb);border-radius:10px;padding:0.75rem 1.25rem;display:flex;align-items:center;gap:0.75rem;">
                <span style="font-weight:600;font-size:0.9rem;">{{ $lang->language }}</span>
                <span style="font-size:0.75rem;background:{{ $accentColor }}20;color:{{ $accentColor }};padding:0.2rem 0.6rem;border-radius:20px;font-weight:600;">{{ ucfirst($lang->proficiency) }}</span>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Social Links --}}
    @if($socialLinks->count() > 0)
    <section style="margin-bottom:4rem;text-align:center;">
        <h2 style="font-size:1.5rem;font-weight:800;margin:0 0 1.5rem;">Connect with Me</h2>
        <div style="display:flex;justify-content:center;flex-wrap:wrap;gap:0.75rem;">
            @foreach($socialLinks as $social)
            <a href="{{ $social->url }}" target="_blank" rel="noopener"
               style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.6rem 1.25rem;background:var(--bg-card, #fff);border:1px solid var(--border, #e5e7eb);border-radius:8px;color:var(--text-primary);text-decoration:none;font-size:0.9rem;font-weight:600;transition:all 0.2s;"
               onmouseover="this.style.borderColor='{{ $accentColor }}';this.style.color='{{ $accentColor }}'"
               onmouseout="this.style.borderColor='var(--border, #e5e7eb)';this.style.color='var(--text-primary)'">
                <i class="{{ $social->icon_class }}"></i>
                {{ ucfirst($social->platform) }}
            </a>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection
