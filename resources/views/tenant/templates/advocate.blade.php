{{-- Advocate / Lawyer Template --}}
<style>
.xn-adv { font-family: 'Inter', sans-serif; background: #0c0c0e; color: #e2e8f0; min-height: 100vh; }
.xn-adv-hero { background: linear-gradient(135deg, #0c0c0e 0%, #1a1206 50%, #0c0c0e 100%); padding: 5rem 2rem 4rem; text-align: center; border-bottom: 1px solid rgba(234,179,8,0.15); position: relative; overflow: hidden; }
.xn-adv-hero::before { content:''; position:absolute; inset:0; background: radial-gradient(ellipse at 50% 0%, rgba(234,179,8,0.08) 0%, transparent 70%); }
.xn-adv-avatar { width: 120px; height: 120px; border-radius: 50%; border: 3px solid rgba(234,179,8,0.4); margin: 0 auto 1.5rem; background: #1a1206; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #fbbf24; overflow: hidden; }
.xn-adv-name { font-size: 2.25rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; }
.xn-adv-title { font-size: 1rem; color: #fbbf24; font-weight: 500; margin-bottom: 0.5rem; }
.xn-adv-bar { width: 60px; height: 2px; background: linear-gradient(90deg, #fbbf24, #f59e0b); margin: 0.75rem auto 1rem; }
.xn-adv-enroll { font-size: 0.8rem; color: #64748b; margin-bottom: 1.5rem; }
.xn-adv-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; margin-bottom: 2rem; }
.xn-adv-tag { background: rgba(234,179,8,0.08); border: 1px solid rgba(234,179,8,0.2); color: #fcd34d; padding: 0.3rem 0.9rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.xn-adv-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.xn-adv-btn { padding: 0.75rem 2rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600; text-decoration: none; transition: all 0.2s; cursor: pointer; border: none; }
.xn-adv-btn-primary { background: #d97706; color: #fff; }
.xn-adv-btn-primary:hover { background: #b45309; }
.xn-adv-btn-outline { background: transparent; border: 1px solid rgba(234,179,8,0.3); color: #fcd34d; }
.xn-adv-btn-outline:hover { background: rgba(234,179,8,0.08); }
.xn-adv-section { max-width: 900px; margin: 0 auto; padding: 3rem 2rem; }
.xn-adv-section-title { font-size: 1.1rem; font-weight: 700; color: #fbbf24; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.75rem; }
.xn-adv-section-title::after { content:''; flex:1; height:1px; background: rgba(234,179,8,0.1); }
.xn-adv-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
.xn-adv-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(234,179,8,0.1); border-radius: 10px; padding: 1.5rem; }
.xn-adv-card-icon { font-size: 1.5rem; margin-bottom: 0.75rem; }
.xn-adv-card-title { font-size: 0.875rem; font-weight: 700; color: #e2e8f0; margin-bottom: 0.35rem; }
.xn-adv-card-text { font-size: 0.775rem; color: #64748b; line-height: 1.5; }
.xn-adv-about { background: rgba(255,255,255,0.02); border-left: 3px solid #fbbf24; border-radius: 0 10px 10px 0; padding: 2rem; line-height: 1.8; color: #94a3b8; font-size: 0.9rem; }
.xn-adv-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
.xn-adv-stat { text-align: center; background: rgba(234,179,8,0.05); border: 1px solid rgba(234,179,8,0.1); border-radius: 10px; padding: 1.5rem 1rem; }
.xn-adv-stat-num { font-size: 2rem; font-weight: 800; color: #fbbf24; line-height: 1; }
.xn-adv-stat-label { font-size: 0.75rem; color: #64748b; margin-top: 0.35rem; }
.xn-adv-contact { background: rgba(234,179,8,0.04); border: 1px solid rgba(234,179,8,0.15); border-radius: 14px; padding: 2.5rem; }
.xn-adv-contact h3 { font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 1.5rem; }
.xn-adv-contact-row { display: flex; align-items: center; gap: 1rem; padding: 0.875rem 0; border-bottom: 1px solid rgba(255,255,255,0.04); }
.xn-adv-contact-row:last-child { border-bottom: none; }
.xn-adv-contact-icon { width: 36px; height: 36px; background: rgba(234,179,8,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fbbf24; font-size: 0.85rem; flex-shrink: 0; }
.xn-adv-contact-label { font-size: 0.72rem; color: #64748b; }
.xn-adv-contact-value { font-size: 0.875rem; color: #e2e8f0; font-weight: 500; }
</style>

<div class="xn-adv">
    <div class="xn-adv-hero">
        <div class="xn-adv-avatar">
            @if($tenant->profile_photo)
                <img src="{{ asset('storage/'.$tenant->profile_photo) }}" alt="{{ $tenant->name }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <i class="fas fa-balance-scale"></i>
            @endif
        </div>
        <div class="xn-adv-name">{{ $profile['name'] ?? $tenant->name }}</div>
        <div class="xn-adv-title">{{ $profile['title'] ?? 'Advocate & Legal Consultant' }}</div>
        <div class="xn-adv-bar"></div>
        @if(!empty($profile['enrollment_no']))
        <div class="xn-adv-enroll"><i class="fas fa-gavel"></i> Bar Council Enrollment: {{ $profile['enrollment_no'] }}</div>
        @endif
        <div class="xn-adv-tags">
            @foreach($profile['practice_areas'] ?? ['Civil Law','Criminal Law','Corporate Law'] as $area)
            <span class="xn-adv-tag">{{ $area }}</span>
            @endforeach
        </div>
        <div class="xn-adv-actions">
            @if(!empty($profile['consultation_link']))
            <a href="{{ $profile['consultation_link'] }}" class="xn-adv-btn xn-adv-btn-primary"><i class="fas fa-calendar"></i> Free Consultation</a>
            @endif
            @if(!empty($profile['phone']))
            <a href="tel:{{ $profile['phone'] }}" class="xn-adv-btn xn-adv-btn-outline"><i class="fas fa-phone"></i> Call Now</a>
            @endif
        </div>
    </div>

    @if(!empty($profile['years_experience']) || !empty($profile['cases_won']) || !empty($profile['clients_served']))
    <div class="xn-adv-section">
        <div class="xn-adv-stats">
            @if(!empty($profile['years_experience']))<div class="xn-adv-stat"><div class="xn-adv-stat-num">{{ $profile['years_experience'] }}+</div><div class="xn-adv-stat-label">Years Experience</div></div>@endif
            @if(!empty($profile['cases_won']))<div class="xn-adv-stat"><div class="xn-adv-stat-num">{{ $profile['cases_won'] }}+</div><div class="xn-adv-stat-label">Cases Won</div></div>@endif
            @if(!empty($profile['clients_served']))<div class="xn-adv-stat"><div class="xn-adv-stat-num">{{ $profile['clients_served'] }}+</div><div class="xn-adv-stat-label">Clients Served</div></div>@endif
        </div>
    </div>
    @endif

    <div class="xn-adv-section" style="padding-top:0;">
        <div class="xn-adv-section-title"><i class="fas fa-briefcase"></i> Practice Areas</div>
        <div class="xn-adv-grid">
            @foreach($profile['services'] ?? [['icon'=>'⚖️','title'=>'Civil Litigation','desc'=>'Disputes, property, contracts'],['icon'=>'🏛️','title'=>'Criminal Defense','desc'=>'Bail, trials, appeals'],['icon'=>'🏢','title'=>'Corporate Law','desc'=>'Mergers, compliance, IPR'],['icon'=>'👨‍👩‍👧','title'=>'Family Law','desc'=>'Divorce, custody, succession']] as $svc)
            <div class="xn-adv-card">
                <div class="xn-adv-card-icon">{{ $svc['icon'] }}</div>
                <div class="xn-adv-card-title">{{ $svc['title'] }}</div>
                <div class="xn-adv-card-text">{{ $svc['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    @if(!empty($profile['about']))
    <div class="xn-adv-section" style="padding-top:0;">
        <div class="xn-adv-section-title"><i class="fas fa-user-tie"></i> About</div>
        <div class="xn-adv-about">{{ $profile['about'] }}</div>
    </div>
    @endif

    <div class="xn-adv-section" style="padding-top:0;">
        <div class="xn-adv-contact">
            <h3>Contact & Office</h3>
            @if(!empty($profile['phone']))<div class="xn-adv-contact-row"><div class="xn-adv-contact-icon"><i class="fas fa-phone"></i></div><div><div class="xn-adv-contact-label">Phone</div><div class="xn-adv-contact-value">{{ $profile['phone'] }}</div></div></div>@endif
            @if(!empty($profile['email']))<div class="xn-adv-contact-row"><div class="xn-adv-contact-icon"><i class="fas fa-envelope"></i></div><div><div class="xn-adv-contact-label">Email</div><div class="xn-adv-contact-value">{{ $profile['email'] }}</div></div></div>@endif
            @if(!empty($profile['chamber']))<div class="xn-adv-contact-row"><div class="xn-adv-contact-icon"><i class="fas fa-map-marker-alt"></i></div><div><div class="xn-adv-contact-label">Chamber</div><div class="xn-adv-contact-value">{{ $profile['chamber'] }}</div></div></div>@endif
            @if(!empty($profile['court']))<div class="xn-adv-contact-row"><div class="xn-adv-contact-icon"><i class="fas fa-landmark"></i></div><div><div class="xn-adv-contact-label">Court</div><div class="xn-adv-contact-value">{{ $profile['court'] }}</div></div></div>@endif
        </div>
    </div>
</div>
