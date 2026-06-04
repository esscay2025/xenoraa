{{-- Doctor / Medical Professional Template --}}
<style>
.xn-doctor { font-family: 'Inter', sans-serif; background: #0a0f1e; color: #e2e8f0; min-height: 100vh; }
.xn-doc-hero { background: linear-gradient(135deg, #0a0f1e 0%, #0d1f3c 50%, #0a1628 100%); padding: 5rem 2rem 4rem; text-align: center; position: relative; overflow: hidden; border-bottom: 1px solid rgba(59,130,246,0.2); }
.xn-doc-hero::before { content:''; position:absolute; inset:0; background: radial-gradient(ellipse at 50% 0%, rgba(59,130,246,0.15) 0%, transparent 70%); }
.xn-doc-avatar { width: 120px; height: 120px; border-radius: 50%; border: 3px solid rgba(59,130,246,0.5); margin: 0 auto 1.5rem; object-fit: cover; background: #1e3a5f; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #60a5fa; }
.xn-doc-name { font-size: 2.25rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; }
.xn-doc-title { font-size: 1rem; color: #60a5fa; font-weight: 500; margin-bottom: 0.5rem; }
.xn-doc-reg { font-size: 0.8rem; color: #64748b; margin-bottom: 1.5rem; }
.xn-doc-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; margin-bottom: 2rem; }
.xn-doc-tag { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.25); color: #93c5fd; padding: 0.3rem 0.9rem; border-radius: 100px; font-size: 0.75rem; font-weight: 500; }
.xn-doc-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.xn-doc-btn { padding: 0.75rem 2rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; text-decoration: none; transition: all 0.2s; cursor: pointer; border: none; }
.xn-doc-btn-primary { background: #2563eb; color: #fff; }
.xn-doc-btn-primary:hover { background: #1d4ed8; }
.xn-doc-btn-outline { background: transparent; border: 1px solid rgba(59,130,246,0.4); color: #93c5fd; }
.xn-doc-btn-outline:hover { background: rgba(59,130,246,0.1); }
.xn-doc-section { max-width: 900px; margin: 0 auto; padding: 3rem 2rem; }
.xn-doc-section-title { font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
.xn-doc-section-title::after { content:''; flex:1; height:1px; background: rgba(59,130,246,0.15); }
.xn-doc-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
.xn-doc-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(59,130,246,0.12); border-radius: 12px; padding: 1.5rem; text-align: center; }
.xn-doc-card-icon { font-size: 1.75rem; margin-bottom: 0.75rem; }
.xn-doc-card-title { font-size: 0.875rem; font-weight: 700; color: #e2e8f0; margin-bottom: 0.35rem; }
.xn-doc-card-text { font-size: 0.775rem; color: #64748b; }
.xn-doc-about { background: rgba(255,255,255,0.02); border: 1px solid rgba(59,130,246,0.1); border-radius: 12px; padding: 2rem; line-height: 1.8; color: #94a3b8; font-size: 0.9rem; }
.xn-doc-contact { background: linear-gradient(135deg, rgba(37,99,235,0.1), rgba(59,130,246,0.05)); border: 1px solid rgba(59,130,246,0.2); border-radius: 16px; padding: 2.5rem; text-align: center; }
.xn-doc-contact h3 { font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 0.75rem; }
.xn-doc-contact p { color: #64748b; margin-bottom: 1.5rem; font-size: 0.9rem; }
.xn-doc-contact-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; }
.xn-doc-contact-item { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 1rem; }
.xn-doc-contact-item i { color: #60a5fa; margin-bottom: 0.5rem; }
.xn-doc-contact-item p { font-size: 0.75rem; color: #64748b; margin: 0; }
.xn-doc-contact-item strong { font-size: 0.8rem; color: #e2e8f0; display: block; margin-top: 0.25rem; }
</style>

<div class="xn-doctor">
    <div class="xn-doc-hero">
        <div class="xn-doc-avatar">
            @if($tenant->profile_photo)
                <img src="{{ asset('storage/'.$tenant->profile_photo) }}" alt="{{ $tenant->name }}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
            @else
                <i class="fas fa-user-md"></i>
            @endif
        </div>
        <div class="xn-doc-name">{{ $profile['name'] ?? $tenant->name }}</div>
        <div class="xn-doc-title">{{ $profile['title'] ?? 'Medical Professional' }}</div>
        @if(!empty($profile['registration_no']))
        <div class="xn-doc-reg"><i class="fas fa-id-card"></i> Reg. No: {{ $profile['registration_no'] }}</div>
        @endif
        <div class="xn-doc-tags">
            @foreach($profile['specializations'] ?? ['General Medicine'] as $spec)
            <span class="xn-doc-tag">{{ $spec }}</span>
            @endforeach
        </div>
        <div class="xn-doc-actions">
            @if(!empty($profile['appointment_link']))
            <a href="{{ $profile['appointment_link'] }}" class="xn-doc-btn xn-doc-btn-primary"><i class="fas fa-calendar-check"></i> Book Appointment</a>
            @endif
            @if(!empty($profile['phone']))
            <a href="tel:{{ $profile['phone'] }}" class="xn-doc-btn xn-doc-btn-outline"><i class="fas fa-phone"></i> Call Now</a>
            @endif
        </div>
    </div>

    <div class="xn-doc-section">
        <div class="xn-doc-section-title"><i class="fas fa-stethoscope" style="color:#60a5fa;"></i> Services</div>
        <div class="xn-doc-grid">
            @foreach($profile['services'] ?? [['icon'=>'🩺','title'=>'Consultation','desc'=>'In-person & online'],['icon'=>'💊','title'=>'Prescription','desc'=>'Digital prescriptions'],['icon'=>'🔬','title'=>'Diagnostics','desc'=>'Lab referrals'],['icon'=>'🏥','title'=>'Follow-up','desc'=>'Regular monitoring']] as $svc)
            <div class="xn-doc-card">
                <div class="xn-doc-card-icon">{{ $svc['icon'] }}</div>
                <div class="xn-doc-card-title">{{ $svc['title'] }}</div>
                <div class="xn-doc-card-text">{{ $svc['desc'] ?? $svc['description'] ?? '' }}</div>
            </div>
            @endforeach
        </div>
    </div>

    @if(!empty($profile['about']))
    <div class="xn-doc-section" style="padding-top:0;">
        <div class="xn-doc-section-title"><i class="fas fa-user" style="color:#60a5fa;"></i> About</div>
        <div class="xn-doc-about">{{ $profile['about'] }}</div>
    </div>
    @endif

    <div class="xn-doc-section" style="padding-top:0;">
        <div class="xn-doc-contact">
            <h3>Get in Touch</h3>
            <p>Schedule a consultation or reach out for more information</p>
            <div class="xn-doc-contact-grid">
                @if(!empty($profile['phone']))<div class="xn-doc-contact-item"><i class="fas fa-phone"></i><p>Phone</p><strong>{{ $profile['phone'] }}</strong></div>@endif
                @if(!empty($profile['email']))<div class="xn-doc-contact-item"><i class="fas fa-envelope"></i><p>Email</p><strong>{{ $profile['email'] }}</strong></div>@endif
                @if(!empty($profile['clinic']))<div class="xn-doc-contact-item"><i class="fas fa-hospital"></i><p>Clinic</p><strong>{{ $profile['clinic'] }}</strong></div>@endif
                @if(!empty($profile['timings']))<div class="xn-doc-contact-item"><i class="fas fa-clock"></i><p>Timings</p><strong>{{ $profile['timings'] }}</strong></div>@endif
            </div>
        </div>
    </div>
</div>
