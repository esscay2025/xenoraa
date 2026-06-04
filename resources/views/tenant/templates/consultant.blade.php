@extends('layouts.app')
@section('content')
{{-- Consultant / Business Advisor Template --}}
<style>
.xn-con { font-family: 'Inter', sans-serif; background: #09090b; color: #e2e8f0; min-height: 100vh; }
.xn-con-hero { background: linear-gradient(135deg, #09090b 0%, #120a1e 50%, #09090b 100%); padding: 5rem 2rem 4rem; text-align: center; border-bottom: 1px solid rgba(124,58,237,0.2); position: relative; overflow: hidden; }
.xn-con-hero::before { content:''; position:absolute; inset:0; background: radial-gradient(ellipse at 50% 0%, rgba(124,58,237,0.12) 0%, transparent 70%); }
.xn-con-avatar { width: 120px; height: 120px; border-radius: 16px; border: 2px solid rgba(124,58,237,0.4); margin: 0 auto 1.5rem; background: #120a1e; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #a855f7; overflow: hidden; }
.xn-con-name { font-size: 2.25rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; }
.xn-con-title { font-size: 1rem; color: #a855f7; font-weight: 500; margin-bottom: 1rem; }
.xn-con-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; margin-bottom: 2rem; }
.xn-con-tag { background: rgba(124,58,237,0.1); border: 1px solid rgba(124,58,237,0.2); color: #c084fc; padding: 0.3rem 0.9rem; border-radius: 6px; font-size: 0.75rem; font-weight: 500; }
.xn-con-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.xn-con-btn { padding: 0.75rem 2rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; text-decoration: none; transition: all 0.2s; cursor: pointer; border: none; }
.xn-con-btn-primary { background: #7c3aed; color: #fff; }
.xn-con-btn-primary:hover { background: #6d28d9; }
.xn-con-btn-outline { background: transparent; border: 1px solid rgba(124,58,237,0.3); color: #c084fc; }
.xn-con-btn-outline:hover { background: rgba(124,58,237,0.08); }
.xn-con-section { max-width: 900px; margin: 0 auto; padding: 3rem 2rem; }
.xn-con-section-title { font-size: 1.1rem; font-weight: 700; color: #a855f7; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.75rem; }
.xn-con-section-title::after { content:''; flex:1; height:1px; background: rgba(124,58,237,0.1); }
.xn-con-services { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
.xn-con-service { background: rgba(255,255,255,0.02); border: 1px solid rgba(124,58,237,0.12); border-radius: 12px; padding: 1.5rem; transition: all 0.2s; }
.xn-con-service:hover { border-color: rgba(124,58,237,0.3); background: rgba(124,58,237,0.05); }
.xn-con-service-icon { font-size: 1.75rem; margin-bottom: 0.75rem; }
.xn-con-service-title { font-size: 0.9rem; font-weight: 700; color: #e2e8f0; margin-bottom: 0.35rem; }
.xn-con-service-text { font-size: 0.775rem; color: #64748b; line-height: 1.5; }
.xn-con-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
.xn-con-stat { text-align: center; background: rgba(124,58,237,0.05); border: 1px solid rgba(124,58,237,0.1); border-radius: 10px; padding: 1.25rem 0.75rem; }
.xn-con-stat-num { font-size: 1.75rem; font-weight: 800; color: #a855f7; line-height: 1; }
.xn-con-stat-label { font-size: 0.7rem; color: #64748b; margin-top: 0.3rem; }
.xn-con-about { background: rgba(255,255,255,0.02); border: 1px solid rgba(124,58,237,0.1); border-radius: 12px; padding: 2rem; line-height: 1.8; color: #94a3b8; font-size: 0.9rem; }
.xn-con-cta { background: linear-gradient(135deg, rgba(124,58,237,0.15), rgba(168,85,247,0.08)); border: 1px solid rgba(124,58,237,0.25); border-radius: 16px; padding: 3rem 2rem; text-align: center; }
.xn-con-cta h3 { font-size: 1.75rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem; }
.xn-con-cta p { color: #94a3b8; margin-bottom: 2rem; font-size: 0.95rem; }
@media(max-width:600px){ .xn-con-stats{grid-template-columns:repeat(2,1fr);} }
</style>

<div class="xn-con">
    <div class="xn-con-hero">
        <div class="xn-con-avatar">
            @if($tenant->profile_photo)
                <img src="{{ asset('storage/'.$tenant->profile_photo) }}" alt="{{ $tenant->name }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <i class="fas fa-chart-line"></i>
            @endif
        </div>
        <div class="xn-con-name">{{ $profile['name'] ?? $tenant->name }}</div>
        <div class="xn-con-title">{{ $profile['title'] ?? 'Business Consultant & Advisor' }}</div>
        <div class="xn-con-tags">
            @foreach($profile['expertise'] ?? ['Strategy','Operations','Growth','Leadership'] as $e)
            <span class="xn-con-tag">{{ $e }}</span>
            @endforeach
        </div>
        <div class="xn-con-actions">
            @if(!empty($profile['booking_link']))<a href="{{ $profile['booking_link'] }}" class="xn-con-btn xn-con-btn-primary"><i class="fas fa-calendar-check"></i> Book a Call</a>@endif
            @if(!empty($profile['email']))<a href="mailto:{{ $profile['email'] }}" class="xn-con-btn xn-con-btn-outline"><i class="fas fa-envelope"></i> Email Me</a>@endif
        </div>
    </div>

    @if(!empty($profile['clients']) || !empty($profile['projects']) || !empty($profile['years']) || !empty($profile['revenue']))
    <div class="xn-con-section">
        <div class="xn-con-stats">
            @if(!empty($profile['years']))<div class="xn-con-stat"><div class="xn-con-stat-num">{{ $profile['years'] }}+</div><div class="xn-con-stat-label">Years Experience</div></div>@endif
            @if(!empty($profile['clients']))<div class="xn-con-stat"><div class="xn-con-stat-num">{{ $profile['clients'] }}+</div><div class="xn-con-stat-label">Clients</div></div>@endif
            @if(!empty($profile['projects']))<div class="xn-con-stat"><div class="xn-con-stat-num">{{ $profile['projects'] }}+</div><div class="xn-con-stat-label">Projects</div></div>@endif
            @if(!empty($profile['revenue']))<div class="xn-con-stat"><div class="xn-con-stat-num">{{ $profile['revenue'] }}</div><div class="xn-con-stat-label">Revenue Generated</div></div>@endif
        </div>
    </div>
    @endif

    <div class="xn-con-section" style="padding-top:0;">
        <div class="xn-con-section-title"><i class="fas fa-cogs"></i> Services</div>
        <div class="xn-con-services">
            @foreach($profile['services'] ?? [['icon'=>'📊','title'=>'Business Strategy','text'=>'Market analysis and growth planning'],['icon'=>'🔄','title'=>'Process Optimization','text'=>'Streamline operations and reduce costs'],['icon'=>'💡','title'=>'Innovation Consulting','text'=>'Digital transformation and new ventures'],['icon'=>'🎯','title'=>'Executive Coaching','text'=>'Leadership and performance coaching']] as $svc)
            <div class="xn-con-service">
                <div class="xn-con-service-icon">{{ $svc['icon'] }}</div>
                <div class="xn-con-service-title">{{ $svc['title'] }}</div>
                <div class="xn-con-service-text">{{ $svc['text'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    @if(!empty($profile['about']))
    <div class="xn-con-section" style="padding-top:0;">
        <div class="xn-con-section-title"><i class="fas fa-user"></i> About</div>
        <div class="xn-con-about">{{ $profile['about'] }}</div>
    </div>
    @endif

    <div class="xn-con-section" style="padding-top:0;">
        <div class="xn-con-cta">
            <h3>Ready to Transform Your Business?</h3>
            <p>Let's discuss your challenges and build a roadmap to success.</p>
            @if(!empty($profile['booking_link']))<a href="{{ $profile['booking_link'] }}" class="xn-con-btn xn-con-btn-primary" style="font-size:1rem;padding:1rem 2.5rem;">Schedule Free Strategy Call</a>@endif
        </div>
    </div>
</div>
@endsection
