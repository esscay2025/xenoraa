{{-- Politician / Public Leader Template --}}
<style>
.xn-pol { font-family: 'Inter', sans-serif; background: #080d16; color: #e2e8f0; min-height: 100vh; }
.xn-pol-hero { background: linear-gradient(160deg, #080d16 0%, #0f1f0a 40%, #080d16 100%); padding: 5rem 2rem 4rem; text-align: center; border-bottom: 1px solid rgba(34,197,94,0.15); position: relative; overflow: hidden; }
.xn-pol-hero::before { content:''; position:absolute; inset:0; background: radial-gradient(ellipse at 50% 0%, rgba(34,197,94,0.08) 0%, transparent 70%); }
.xn-pol-flag { display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 1.5rem; }
.xn-pol-flag span { width: 40px; height: 6px; border-radius: 3px; }
.xn-pol-avatar { width: 130px; height: 130px; border-radius: 50%; border: 3px solid rgba(34,197,94,0.4); margin: 0 auto 1.5rem; background: #0f1f0a; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #4ade80; overflow: hidden; }
.xn-pol-name { font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; }
.xn-pol-title { font-size: 1rem; color: #4ade80; font-weight: 600; margin-bottom: 0.5rem; }
.xn-pol-constituency { font-size: 0.85rem; color: #64748b; margin-bottom: 1.5rem; }
.xn-pol-party { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2); color: #4ade80; padding: 0.4rem 1rem; border-radius: 100px; font-size: 0.8rem; font-weight: 600; margin-bottom: 2rem; }
.xn-pol-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.xn-pol-btn { padding: 0.75rem 2rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; text-decoration: none; transition: all 0.2s; cursor: pointer; border: none; }
.xn-pol-btn-primary { background: #16a34a; color: #fff; }
.xn-pol-btn-primary:hover { background: #15803d; }
.xn-pol-btn-outline { background: transparent; border: 1px solid rgba(34,197,94,0.3); color: #4ade80; }
.xn-pol-btn-outline:hover { background: rgba(34,197,94,0.08); }
.xn-pol-section { max-width: 900px; margin: 0 auto; padding: 3rem 2rem; }
.xn-pol-section-title { font-size: 1.1rem; font-weight: 700; color: #4ade80; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.75rem; }
.xn-pol-section-title::after { content:''; flex:1; height:1px; background: rgba(34,197,94,0.1); }
.xn-pol-manifesto { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; }
.xn-pol-manifesto-item { background: rgba(255,255,255,0.02); border: 1px solid rgba(34,197,94,0.1); border-radius: 12px; padding: 1.5rem; }
.xn-pol-manifesto-icon { font-size: 2rem; margin-bottom: 0.75rem; }
.xn-pol-manifesto-title { font-size: 0.9rem; font-weight: 700; color: #e2e8f0; margin-bottom: 0.5rem; }
.xn-pol-manifesto-text { font-size: 0.775rem; color: #64748b; line-height: 1.6; }
.xn-pol-achievements { list-style: none; }
.xn-pol-achievements li { display: flex; gap: 0.75rem; padding: 0.875rem 0; border-bottom: 1px solid rgba(255,255,255,0.04); font-size: 0.875rem; color: #94a3b8; }
.xn-pol-achievements li::before { content: '✓'; color: #4ade80; font-weight: 700; flex-shrink: 0; }
.xn-pol-achievements li:last-child { border-bottom: none; }
.xn-pol-contact { background: rgba(34,197,94,0.04); border: 1px solid rgba(34,197,94,0.15); border-radius: 14px; padding: 2.5rem; text-align: center; }
.xn-pol-contact h3 { font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 0.75rem; }
.xn-pol-contact p { color: #64748b; margin-bottom: 1.5rem; font-size: 0.9rem; }
.xn-pol-social { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.xn-pol-social a { width: 44px; height: 44px; border: 1px solid rgba(34,197,94,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #4ade80; text-decoration: none; transition: all 0.2s; font-size: 1rem; }
.xn-pol-social a:hover { background: rgba(34,197,94,0.1); }
</style>

<div class="xn-pol">
    <div class="xn-pol-hero">
        <div class="xn-pol-avatar">
            @if($tenant->profile_photo)
                <img src="{{ asset('storage/'.$tenant->profile_photo) }}" alt="{{ $tenant->name }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <i class="fas fa-landmark"></i>
            @endif
        </div>
        <div class="xn-pol-name">{{ $profile['name'] ?? $tenant->name }}</div>
        <div class="xn-pol-title">{{ $profile['title'] ?? 'Political Leader' }}</div>
        @if(!empty($profile['constituency']))<div class="xn-pol-constituency"><i class="fas fa-map-marker-alt"></i> {{ $profile['constituency'] }}</div>@endif
        @if(!empty($profile['party']))<div class="xn-pol-party"><i class="fas fa-flag"></i> {{ $profile['party'] }}</div>@endif
        <div class="xn-pol-actions">
            @if(!empty($profile['petition_link']))<a href="{{ $profile['petition_link'] }}" class="xn-pol-btn xn-pol-btn-primary"><i class="fas fa-pen"></i> Sign Petition</a>@endif
            @if(!empty($profile['contact_link']))<a href="{{ $profile['contact_link'] }}" class="xn-pol-btn xn-pol-btn-outline"><i class="fas fa-envelope"></i> Contact</a>@endif
        </div>
    </div>

    <div class="xn-pol-section">
        <div class="xn-pol-section-title"><i class="fas fa-bullseye"></i> Key Agenda</div>
        <div class="xn-pol-manifesto">
            @foreach($profile['agenda'] ?? [['icon'=>'🏗️','title'=>'Infrastructure','text'=>'Roads, bridges, and public transport'],['icon'=>'📚','title'=>'Education','text'=>'Quality schools and scholarships'],['icon'=>'🏥','title'=>'Healthcare','text'=>'Affordable care for all'],['icon'=>'💼','title'=>'Employment','text'=>'Jobs and skill development']] as $item)
            <div class="xn-pol-manifesto-item">
                <div class="xn-pol-manifesto-icon">{{ $item['icon'] }}</div>
                <div class="xn-pol-manifesto-title">{{ $item['title'] }}</div>
                <div class="xn-pol-manifesto-text">{{ $item['text'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    @if(!empty($profile['achievements']))
    <div class="xn-pol-section" style="padding-top:0;">
        <div class="xn-pol-section-title"><i class="fas fa-trophy"></i> Achievements</div>
        <ul class="xn-pol-achievements">
            @foreach($profile['achievements'] as $ach)<li>{{ $ach }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="xn-pol-section" style="padding-top:0;">
        <div class="xn-pol-contact">
            <h3>Connect with {{ $profile['name'] ?? $tenant->name }}</h3>
            <p>Follow on social media for updates, events, and announcements</p>
            <div class="xn-pol-social">
                @if(!empty($profile['twitter']))<a href="{{ $profile['twitter'] }}" target="_blank"><i class="fab fa-twitter"></i></a>@endif
                @if(!empty($profile['facebook']))<a href="{{ $profile['facebook'] }}" target="_blank"><i class="fab fa-facebook-f"></i></a>@endif
                @if(!empty($profile['instagram']))<a href="{{ $profile['instagram'] }}" target="_blank"><i class="fab fa-instagram"></i></a>@endif
                @if(!empty($profile['youtube']))<a href="{{ $profile['youtube'] }}" target="_blank"><i class="fab fa-youtube"></i></a>@endif
                @if(!empty($profile['whatsapp']))<a href="https://wa.me/{{ $profile['whatsapp'] }}" target="_blank"><i class="fab fa-whatsapp"></i></a>@endif
            </div>
        </div>
    </div>
</div>
