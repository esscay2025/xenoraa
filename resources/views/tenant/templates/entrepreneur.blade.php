@extends('layouts.app')
@section('content')
@php
$_sec  = isset($homePage) && $homePage ? $homePage : null;
$_show = fn(string $k) => !$_sec || $_sec->isSectionEnabled($k);
@endphp
{{-- Entrepreneur / Startup Founder Template --}}
<style>
.xn-ent { font-family: 'Inter', sans-serif; background: #080808; color: #e2e8f0; min-height: 100vh; }
.xn-ent-hero { background: linear-gradient(135deg, #080808 0%, #0d0d0d 100%); padding: 5rem 2rem 4rem; text-align: center; border-bottom: 1px solid rgba(249,115,22,0.15); position: relative; overflow: hidden; }
.xn-ent-hero::before { content:''; position:absolute; inset:0; background: radial-gradient(ellipse at 50% 0%, rgba(249,115,22,0.1) 0%, transparent 70%); }
.xn-ent-avatar { width: 120px; height: 120px; border-radius: 20px; border: 2px solid rgba(249,115,22,0.4); margin: 0 auto 1.5rem; background: #111; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #fb923c; overflow: hidden; }
.xn-ent-name { font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; }
.xn-ent-title { font-size: 1rem; color: #fb923c; font-weight: 500; margin-bottom: 1rem; }
.xn-ent-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; margin-bottom: 2rem; }
.xn-ent-tag { background: rgba(249,115,22,0.08); border: 1px solid rgba(249,115,22,0.2); color: #fdba74; padding: 0.3rem 0.9rem; border-radius: 6px; font-size: 0.75rem; font-weight: 500; }
.xn-ent-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.xn-ent-btn { padding: 0.75rem 2rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; text-decoration: none; transition: all 0.2s; cursor: pointer; border: none; }
.xn-ent-btn-primary { background: #ea580c; color: #fff; }
.xn-ent-btn-primary:hover { background: #c2410c; }
.xn-ent-btn-outline { background: transparent; border: 1px solid rgba(249,115,22,0.3); color: #fb923c; }
.xn-ent-btn-outline:hover { background: rgba(249,115,22,0.08); }
.xn-ent-section { max-width: 900px; margin: 0 auto; padding: 3rem 2rem; }
.xn-ent-section-title { font-size: 1.1rem; font-weight: 700; color: #fb923c; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.75rem; }
.xn-ent-section-title::after { content:''; flex:1; height:1px; background: rgba(249,115,22,0.1); }
.xn-ent-ventures { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; }
.xn-ent-venture { background: rgba(255,255,255,0.02); border: 1px solid rgba(249,115,22,0.1); border-radius: 14px; padding: 1.75rem; transition: all 0.2s; }
.xn-ent-venture:hover { border-color: rgba(249,115,22,0.3); }
.xn-ent-venture-logo { font-size: 2rem; margin-bottom: 0.75rem; }
.xn-ent-venture-name { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.35rem; }
.xn-ent-venture-desc { font-size: 0.8rem; color: #64748b; line-height: 1.5; margin-bottom: 0.75rem; }
.xn-ent-venture-link { font-size: 0.75rem; color: #fb923c; text-decoration: none; }
.xn-ent-venture-link:hover { text-decoration: underline; }
.xn-ent-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
.xn-ent-stat { text-align: center; background: rgba(249,115,22,0.05); border: 1px solid rgba(249,115,22,0.1); border-radius: 10px; padding: 1.5rem 1rem; }
.xn-ent-stat-num { font-size: 1.75rem; font-weight: 800; color: #fb923c; line-height: 1; }
.xn-ent-stat-label { font-size: 0.7rem; color: #64748b; margin-top: 0.3rem; }
.xn-ent-about { background: rgba(255,255,255,0.02); border: 1px solid rgba(249,115,22,0.1); border-radius: 12px; padding: 2rem; line-height: 1.8; color: #94a3b8; font-size: 0.9rem; }
.xn-ent-cta { background: linear-gradient(135deg, rgba(249,115,22,0.12), rgba(234,88,12,0.06)); border: 1px solid rgba(249,115,22,0.2); border-radius: 16px; padding: 3rem 2rem; text-align: center; }
.xn-ent-cta h3 { font-size: 1.75rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem; }
.xn-ent-cta p { color: #94a3b8; margin-bottom: 2rem; font-size: 0.95rem; }
</style>

<div class="xn-ent">
    <div class="xn-ent-hero">
        <div class="xn-ent-avatar">
            @if($tenant->avatar)
                <img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <i class="fas fa-rocket"></i>
            @endif
        </div>
        <div class="xn-ent-name">{{ $profile['name'] ?? $tenant->name }}</div>
        <div class="xn-ent-title">{{ $profile['title'] ?? 'Entrepreneur & Startup Founder' }}</div>
        <div class="xn-ent-tags">
            @foreach($profile['industries'] ?? ['Technology','SaaS','E-commerce','Innovation'] as $ind)
            <span class="xn-ent-tag">{{ $ind }}</span>
            @endforeach
        </div>
        <div class="xn-ent-actions">
            @if(!empty($profile['pitch_link']))<a href="{{ $profile['pitch_link'] }}" class="xn-ent-btn xn-ent-btn-primary"><i class="fas fa-handshake"></i> Partner With Me</a>@endif
            @if(!empty($profile['linkedin']))<a href="{{ $profile['linkedin'] }}" class="xn-ent-btn xn-ent-btn-outline" target="_blank"><i class="fab fa-linkedin"></i> LinkedIn</a>@endif
        </div>
    </div>

    @if(!empty($profile['ventures_built']) || !empty($profile['funding_raised']) || !empty($profile['team_size']))
    <div class="xn-ent-section">
        <div class="xn-ent-stats">
            @if(!empty($profile['ventures_built']))<div class="xn-ent-stat"><div class="xn-ent-stat-num">{{ $profile['ventures_built'] }}</div><div class="xn-ent-stat-label">Ventures Built</div></div>@endif
            @if(!empty($profile['funding_raised']))<div class="xn-ent-stat"><div class="xn-ent-stat-num">{{ $profile['funding_raised'] }}</div><div class="xn-ent-stat-label">Funding Raised</div></div>@endif
            @if(!empty($profile['team_size']))<div class="xn-ent-stat"><div class="xn-ent-stat-num">{{ $profile['team_size'] }}+</div><div class="xn-ent-stat-label">Team Members</div></div>@endif
        </div>
    </div>
    @endif

    @if(!empty($profile['ventures']))
    <div class="xn-ent-section" style="padding-top:0;">
        <div class="xn-ent-section-title"><i class="fas fa-rocket"></i> Ventures</div>
        <div class="xn-ent-ventures">
            @foreach($profile['ventures'] as $v)
            <div class="xn-ent-venture">
                <div class="xn-ent-venture-logo">{{ $v['logo'] ?? '🚀' }}</div>
                <div class="xn-ent-venture-name">{{ $v['name'] }}</div>
                <div class="xn-ent-venture-desc">{{ $v['desc'] ?? $v['description'] ?? '' }}</div>
                @if(!empty($v['url']))<a href="{{ $v['url'] }}" class="xn-ent-venture-link" target="_blank">Visit →</a>@endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($profile['about']))
    <div class="xn-ent-section" style="padding-top:0;">
        <div class="xn-ent-section-title"><i class="fas fa-user"></i> My Story</div>
        <div class="xn-ent-about">{{ $profile['about'] }}</div>
    </div>
    @endif

    <div class="xn-ent-section" style="padding-top:0;">
        <div class="xn-ent-cta">
            <h3>Let's Build Something Great</h3>
            <p>Open to collaborations, investments, and partnerships.</p>
            @if(!empty($profile['email']))<a href="mailto:{{ $profile['email'] }}" class="xn-ent-btn xn-ent-btn-primary" style="font-size:1rem;padding:1rem 2.5rem;">Get in Touch</a>@endif
        </div>
    </div>
</div>
@endsection
