@extends('layouts.app')
@section('content')
@php
$_sec  = isset($homePage) && $homePage ? $homePage : null;
$_show = fn(string $k) => !$_sec || $_sec->isSectionEnabled($k);
@endphp
{{-- Influencer / Content Creator Template --}}
<style>
.xn-inf { font-family: 'Inter', sans-serif; background: #0a0a0a; color: #e2e8f0; min-height: 100vh; }
.xn-inf-hero { background: linear-gradient(135deg, #0a0a0a 0%, #1a0a1e 50%, #0a0a0a 100%); padding: 5rem 2rem 4rem; text-align: center; border-bottom: 1px solid rgba(236,72,153,0.2); position: relative; overflow: hidden; }
.xn-inf-hero::before { content:''; position:absolute; inset:0; background: radial-gradient(ellipse at 50% 0%, rgba(236,72,153,0.12) 0%, transparent 70%); }
.xn-inf-avatar { width: 130px; height: 130px; border-radius: 50%; border: 3px solid transparent; background: linear-gradient(#0a0a0a, #0a0a0a) padding-box, linear-gradient(135deg, #ec4899, #a855f7, #3b82f6) border-box; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #f472b6; overflow: hidden; }
.xn-inf-name { font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem; }
.xn-inf-handle { font-size: 1rem; color: #f472b6; font-weight: 500; margin-bottom: 0.5rem; }
.xn-inf-niche { font-size: 0.85rem; color: #64748b; margin-bottom: 1.5rem; }
.xn-inf-stats-row { display: flex; gap: 2rem; justify-content: center; margin-bottom: 2rem; flex-wrap: wrap; }
.xn-inf-stat-item { text-align: center; }
.xn-inf-stat-num { font-size: 1.5rem; font-weight: 800; color: #fff; line-height: 1; }
.xn-inf-stat-platform { font-size: 0.7rem; color: #64748b; margin-top: 0.25rem; }
.xn-inf-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.xn-inf-btn { padding: 0.75rem 2rem; border-radius: 100px; font-size: 0.875rem; font-weight: 600; text-decoration: none; transition: all 0.2s; cursor: pointer; border: none; }
.xn-inf-btn-primary { background: linear-gradient(135deg, #ec4899, #a855f7); color: #fff; }
.xn-inf-btn-primary:hover { opacity: 0.9; }
.xn-inf-btn-outline { background: transparent; border: 1px solid rgba(236,72,153,0.3); color: #f472b6; border-radius: 100px; }
.xn-inf-btn-outline:hover { background: rgba(236,72,153,0.08); }
.xn-inf-section { max-width: 900px; margin: 0 auto; padding: 3rem 2rem; }
.xn-inf-section-title { font-size: 1.1rem; font-weight: 700; color: #f472b6; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.75rem; }
.xn-inf-section-title::after { content:''; flex:1; height:1px; background: rgba(236,72,153,0.1); }
.xn-inf-social-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; }
.xn-inf-social-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 14px; padding: 1.5rem; text-align: center; transition: all 0.2s; text-decoration: none; }
.xn-inf-social-card:hover { border-color: rgba(236,72,153,0.3); background: rgba(236,72,153,0.05); }
.xn-inf-social-icon { font-size: 1.75rem; margin-bottom: 0.75rem; }
.xn-inf-social-platform { font-size: 0.8rem; font-weight: 700; color: #e2e8f0; margin-bottom: 0.25rem; }
.xn-inf-social-count { font-size: 1.1rem; font-weight: 800; color: #f472b6; }
.xn-inf-content-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; }
.xn-inf-content-item { aspect-ratio: 1; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; overflow: hidden; }
.xn-inf-content-item img { width: 100%; height: 100%; object-fit: cover; }
.xn-inf-collab { background: linear-gradient(135deg, rgba(236,72,153,0.1), rgba(168,85,247,0.06)); border: 1px solid rgba(236,72,153,0.2); border-radius: 16px; padding: 3rem 2rem; text-align: center; }
.xn-inf-collab h3 { font-size: 1.75rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem; }
.xn-inf-collab p { color: #94a3b8; margin-bottom: 2rem; font-size: 0.95rem; }
.xn-inf-collab-types { display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: center; margin-bottom: 2rem; }
.xn-inf-collab-type { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #e2e8f0; padding: 0.4rem 1rem; border-radius: 100px; font-size: 0.8rem; }
</style>

<div class="xn-inf">
    <div class="xn-inf-hero">
        <div class="xn-inf-avatar">
            @if($tenant->avatar)
                <img src="{{ asset('storage/'.$tenant->avatar) }}" alt="{{ $tenant->name }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <i class="fas fa-star"></i>
            @endif
        </div>
        <div class="xn-inf-name">{{ $profile['name'] ?? $tenant->name }}</div>
        @if(!empty($profile['handle']))<div class="xn-inf-handle">@{{ $profile['handle'] }}</div>@endif
        <div class="xn-inf-niche">{{ $profile['niche'] ?? 'Content Creator & Digital Influencer' }}</div>
        @if(!empty($profile['followers_total']))
        <div class="xn-inf-stats-row">
            @if(!empty($profile['instagram_followers']))<div class="xn-inf-stat-item"><div class="xn-inf-stat-num">{{ $profile['instagram_followers'] }}</div><div class="xn-inf-stat-platform">Instagram</div></div>@endif
            @if(!empty($profile['youtube_subscribers']))<div class="xn-inf-stat-item"><div class="xn-inf-stat-num">{{ $profile['youtube_subscribers'] }}</div><div class="xn-inf-stat-platform">YouTube</div></div>@endif
            @if(!empty($profile['twitter_followers']))<div class="xn-inf-stat-item"><div class="xn-inf-stat-num">{{ $profile['twitter_followers'] }}</div><div class="xn-inf-stat-platform">Twitter/X</div></div>@endif
            @if(!empty($profile['tiktok_followers']))<div class="xn-inf-stat-item"><div class="xn-inf-stat-num">{{ $profile['tiktok_followers'] }}</div><div class="xn-inf-stat-platform">TikTok</div></div>@endif
        </div>
        @endif
        <div class="xn-inf-actions">
            @if(!empty($profile['collab_email']))<a href="mailto:{{ $profile['collab_email'] }}" class="xn-inf-btn xn-inf-btn-primary"><i class="fas fa-handshake"></i> Collaborate</a>@endif
            @if(!empty($profile['media_kit']))<a href="{{ $profile['media_kit'] }}" class="xn-inf-btn xn-inf-btn-outline" target="_blank"><i class="fas fa-file-pdf"></i> Media Kit</a>@endif
        </div>
    </div>

    <div class="xn-inf-section">
        <div class="xn-inf-section-title"><i class="fas fa-share-alt"></i> Social Channels</div>
        <div class="xn-inf-social-grid">
            @if(!empty($profile['instagram']))<a href="{{ $profile['instagram'] }}" class="xn-inf-social-card" target="_blank"><div class="xn-inf-social-icon">📸</div><div class="xn-inf-social-platform">Instagram</div><div class="xn-inf-social-count">{{ $profile['instagram_followers'] ?? 'Follow' }}</div></a>@endif
            @if(!empty($profile['youtube']))<a href="{{ $profile['youtube'] }}" class="xn-inf-social-card" target="_blank"><div class="xn-inf-social-icon">▶️</div><div class="xn-inf-social-platform">YouTube</div><div class="xn-inf-social-count">{{ $profile['youtube_subscribers'] ?? 'Subscribe' }}</div></a>@endif
            @if(!empty($profile['twitter']))<a href="{{ $profile['twitter'] }}" class="xn-inf-social-card" target="_blank"><div class="xn-inf-social-icon">🐦</div><div class="xn-inf-social-platform">Twitter/X</div><div class="xn-inf-social-count">{{ $profile['twitter_followers'] ?? 'Follow' }}</div></a>@endif
            @if(!empty($profile['tiktok']))<a href="{{ $profile['tiktok'] }}" class="xn-inf-social-card" target="_blank"><div class="xn-inf-social-icon">🎵</div><div class="xn-inf-social-platform">TikTok</div><div class="xn-inf-social-count">{{ $profile['tiktok_followers'] ?? 'Follow' }}</div></a>@endif
        </div>
    </div>

    @if(!empty($profile['about']))
    <div class="xn-inf-section" style="padding-top:0;">
        <div class="xn-inf-section-title"><i class="fas fa-user"></i> About Me</div>
        <p style="background:rgba(255,255,255,0.02);border:1px solid rgba(236,72,153,0.1);border-radius:12px;padding:2rem;line-height:1.8;color:#94a3b8;font-size:0.9rem;">{{ $profile['about'] }}</p>
    </div>
    @endif

    <div class="xn-inf-section" style="padding-top:0;">
        <div class="xn-inf-collab">
            <h3>Work With Me</h3>
            <p>Open to brand collaborations, sponsored content, and partnerships</p>
            <div class="xn-inf-collab-types">
                @foreach($profile['collab_types'] ?? ['Sponsored Posts','Product Reviews','Brand Ambassador','Events','Reels/Shorts','Podcast Guest'] as $ct)
                <span class="xn-inf-collab-type">{{ $ct }}</span>
                @endforeach
            </div>
            @if(!empty($profile['collab_email']))<a href="mailto:{{ $profile['collab_email'] }}" class="xn-inf-btn xn-inf-btn-primary" style="font-size:1rem;padding:1rem 2.5rem;">Send Collaboration Request</a>@endif
        </div>
    </div>
</div>
@endsection
