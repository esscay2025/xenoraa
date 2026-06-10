@extends('layouts.admin')
@section('title', 'Mail Configuration — CRM Integrations')
@section('content')
<link rel="stylesheet" href="{{ asset('css/crm2.css') }}?v={{ filemtime(public_path('css/crm2.css')) }}">

<div class="crm2-page">
    {{-- Page Header --}}
    <div class="crm2-page-header">
        <div>
            <h1 class="crm2-page-title"><i class="fas fa-envelope-open-text" style="color:var(--accent);margin-right:0.5rem;"></i>Mail Configuration</h1>
            <p class="crm2-page-subtitle">Configure your outbound SMTP settings for CRM email flows</p>
        </div>
        <div style="display:flex;gap:0.75rem;align-items:center;">
            @if($config && $config->verified_at)
            <span style="display:inline-flex;align-items:center;gap:0.4rem;background:#dcfce7;color:#166534;padding:0.35rem 0.85rem;border-radius:20px;font-size:0.8rem;font-weight:600;">
                <i class="fas fa-check-circle"></i> Verified {{ $config->verified_at->diffForHumans() }}
            </span>
            @elseif($config)
            <span style="display:inline-flex;align-items:center;gap:0.4rem;background:#fef3c7;color:#92400e;padding:0.35rem 0.85rem;border-radius:20px;font-size:0.8rem;font-weight:600;">
                <i class="fas fa-exclamation-triangle"></i> Not Verified
            </span>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="crm2-alert crm2-alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="crm2-alert crm2-alert-danger"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;">

        {{-- Main Config Form --}}
        <div class="crm2-card">
            <div class="crm2-card-header">
                <h3 class="crm2-card-title"><i class="fas fa-server"></i> SMTP Settings</h3>
            </div>
            <div class="crm2-card-body">
                <form method="POST" action="{{ route('admin.crm2.integrations.mail-config.save') }}" id="mailConfigForm">
                    @csrf

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

                        {{-- Mail Driver --}}
                        <div class="crm2-field" style="grid-column:1/-1;">
                            <label class="crm2-label">Mail Driver</label>
                            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                                @foreach(['smtp'=>'SMTP','sendmail'=>'Sendmail','mailgun'=>'Mailgun','ses'=>'Amazon SES'] as $val=>$label)
                                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;padding:0.5rem 1rem;border:1px solid var(--border);border-radius:8px;font-size:0.875rem;transition:all 0.2s;"
                                    onclick="this.parentElement.querySelectorAll('label').forEach(l=>l.style.background='');this.parentElement.querySelectorAll('label').forEach(l=>l.style.borderColor='var(--border)');this.style.background='var(--accent)20';this.style.borderColor='var(--accent)';">
                                    <input type="radio" name="mail_driver" value="{{ $val }}" {{ ($config->mail_driver ?? 'smtp') == $val ? 'checked' : '' }} style="accent-color:var(--accent);">
                                    {{ $label }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Host --}}
                        <div class="crm2-field">
                            <label class="crm2-label">SMTP Host <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="mail_host" class="crm2-input" value="{{ $config->mail_host ?? '' }}"
                                placeholder="e.g. smtp.gmail.com" required>
                            <small class="crm2-hint">Common: smtp.gmail.com, smtp.office365.com, smtp.zoho.com</small>
                        </div>

                        {{-- Port --}}
                        <div class="crm2-field">
                            <label class="crm2-label">SMTP Port <span style="color:var(--danger);">*</span></label>
                            <select name="mail_port" class="crm2-select">
                                @foreach([587=>'587 (TLS — Recommended)',465=>'465 (SSL)',25=>'25 (Default)',2525=>'2525 (Alternative)'] as $p=>$pl)
                                <option value="{{ $p }}" {{ ($config->mail_port ?? 587) == $p ? 'selected' : '' }}>{{ $pl }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Username --}}
                        <div class="crm2-field">
                            <label class="crm2-label">SMTP Username <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="mail_username" class="crm2-input" value="{{ $config->mail_username ?? '' }}"
                                placeholder="your@email.com" autocomplete="off">
                        </div>

                        {{-- Password --}}
                        <div class="crm2-field">
                            <label class="crm2-label">SMTP Password <span style="color:var(--danger);">*</span></label>
                            <div style="position:relative;">
                                <input type="password" name="mail_password" id="mailPassword" class="crm2-input"
                                    value="" placeholder="{{ $config ? '••••••••••••' : 'Enter password' }}"
                                    autocomplete="new-password" style="padding-right:2.5rem;">
                                <button type="button" onclick="togglePwd()" style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);">
                                    <i class="fas fa-eye" id="pwdIcon"></i>
                                </button>
                            </div>
                            @if($config && $config->mail_password)
                            <small class="crm2-hint">Leave blank to keep current password</small>
                            @endif
                        </div>

                        {{-- Encryption --}}
                        <div class="crm2-field">
                            <label class="crm2-label">Encryption</label>
                            <select name="mail_encryption" class="crm2-select">
                                <option value="tls" {{ ($config->mail_encryption ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS (Recommended)</option>
                                <option value="ssl" {{ ($config->mail_encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="none" {{ ($config->mail_encryption ?? '') == 'none' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>

                        {{-- From Address --}}
                        <div class="crm2-field">
                            <label class="crm2-label">From Email Address <span style="color:var(--danger);">*</span></label>
                            <input type="email" name="from_address" class="crm2-input" value="{{ $config->from_address ?? '' }}"
                                placeholder="noreply@yourdomain.com" required>
                        </div>

                        {{-- From Name --}}
                        <div class="crm2-field">
                            <label class="crm2-label">From Name</label>
                            <input type="text" name="from_name" class="crm2-input" value="{{ $config->from_name ?? '' }}"
                                placeholder="Your Company Name">
                        </div>

                        {{-- Reply To --}}
                        <div class="crm2-field">
                            <label class="crm2-label">Reply-To Address</label>
                            <input type="email" name="reply_to" class="crm2-input" value="{{ $config->reply_to ?? '' }}"
                                placeholder="support@yourdomain.com">
                            <small class="crm2-hint">Optional — replies go here instead of From address</small>
                        </div>

                        {{-- Active toggle --}}
                        <div class="crm2-field" style="grid-column:1/-1;">
                            <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                                <div style="position:relative;width:44px;height:24px;">
                                    <input type="checkbox" name="is_active" value="1" id="isActive"
                                        {{ ($config->is_active ?? false) ? 'checked' : '' }}
                                        style="opacity:0;width:0;height:0;position:absolute;">
                                    <span onclick="document.getElementById('isActive').click()" id="toggleTrack"
                                        style="position:absolute;inset:0;border-radius:24px;cursor:pointer;transition:0.3s;background:{{ ($config->is_active ?? false) ? 'var(--accent)' : 'var(--border)' }};">
                                        <span style="position:absolute;top:3px;left:{{ ($config->is_active ?? false) ? '23px' : '3px' }};width:18px;height:18px;background:#fff;border-radius:50%;transition:0.3s;" id="toggleThumb"></span>
                                    </span>
                                </div>
                                <span style="font-size:0.9rem;font-weight:500;">Use this mail config for CRM outbound emails</span>
                            </label>
                        </div>

                    </div>

                    <div style="display:flex;gap:0.75rem;margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid var(--border);">
                        <button type="submit" class="crm2-btn crm2-btn-primary">
                            <i class="fas fa-save"></i> Save Configuration
                        </button>
                        @if($config && $config->mail_host)
                        <button type="button" class="crm2-btn crm2-btn-secondary" onclick="openTestModal()">
                            <i class="fas fa-paper-plane"></i> Send Test Email
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Status Card --}}
            <div class="crm2-card">
                <div class="crm2-card-header"><h3 class="crm2-card-title"><i class="fas fa-info-circle"></i> Status</h3></div>
                <div class="crm2-card-body" style="padding:1.25rem;">
                    @if($config)
                    <div style="display:flex;flex-direction:column;gap:0.75rem;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:0.8rem;color:var(--text-muted);">Host</span>
                            <span style="font-size:0.8rem;font-weight:600;">{{ $config->mail_host ?? '—' }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:0.8rem;color:var(--text-muted);">Port</span>
                            <span style="font-size:0.8rem;font-weight:600;">{{ $config->mail_port ?? '—' }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:0.8rem;color:var(--text-muted);">Encryption</span>
                            <span style="font-size:0.8rem;font-weight:600;text-transform:uppercase;">{{ $config->mail_encryption ?? '—' }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:0.8rem;color:var(--text-muted);">Active</span>
                            <span style="font-size:0.75rem;font-weight:600;padding:0.2rem 0.6rem;border-radius:12px;background:{{ $config->is_active ? '#dcfce7' : '#fee2e2' }};color:{{ $config->is_active ? '#166534' : '#991b1b' }};">
                                {{ $config->is_active ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        @if($config->verified_at)
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:0.8rem;color:var(--text-muted);">Verified</span>
                            <span style="font-size:0.8rem;color:#16a34a;font-weight:600;"><i class="fas fa-check"></i> {{ $config->verified_at->format('d M Y') }}</span>
                        </div>
                        @endif
                        @if($config->last_error)
                        <div style="background:#fee2e2;border-radius:6px;padding:0.75rem;margin-top:0.5rem;">
                            <p style="margin:0;font-size:0.75rem;color:#991b1b;"><i class="fas fa-exclamation-circle"></i> <strong>Last Error:</strong><br>{{ Str::limit($config->last_error, 120) }}</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <p style="margin:0;font-size:0.875rem;color:var(--text-muted);text-align:center;padding:1rem 0;">No configuration saved yet.</p>
                    @endif
                </div>
            </div>

            {{-- Quick Guide --}}
            <div class="crm2-card">
                <div class="crm2-card-header"><h3 class="crm2-card-title"><i class="fas fa-lightbulb"></i> Quick Guide</h3></div>
                <div class="crm2-card-body" style="padding:1.25rem;">
                    <div style="display:flex;flex-direction:column;gap:1rem;">
                        <div>
                            <p style="margin:0 0 4px;font-size:0.8rem;font-weight:600;color:var(--text-primary);">Gmail / Google Workspace</p>
                            <p style="margin:0;font-size:0.75rem;color:var(--text-muted);">Host: smtp.gmail.com<br>Port: 587 | TLS<br>Use App Password (not account password)</p>
                        </div>
                        <div>
                            <p style="margin:0 0 4px;font-size:0.8rem;font-weight:600;color:var(--text-primary);">Microsoft 365 / Outlook</p>
                            <p style="margin:0;font-size:0.75rem;color:var(--text-muted);">Host: smtp.office365.com<br>Port: 587 | TLS</p>
                        </div>
                        <div>
                            <p style="margin:0 0 4px;font-size:0.8rem;font-weight:600;color:var(--text-primary);">Zoho Mail</p>
                            <p style="margin:0;font-size:0.75rem;color:var(--text-muted);">Host: smtp.zoho.com<br>Port: 587 | TLS</p>
                        </div>
                        <div>
                            <p style="margin:0 0 4px;font-size:0.8rem;font-weight:600;color:var(--text-primary);">SendGrid / Mailgun</p>
                            <p style="margin:0;font-size:0.75rem;color:var(--text-muted);">Use API key as password.<br>Port: 587 | TLS</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Test Email Modal --}}
<div id="testModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.5);display:none;align-items:center;justify-content:center;">
    <div style="background:var(--bg-card);border-radius:12px;padding:2rem;width:100%;max-width:420px;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <h3 style="margin:0 0 1rem;font-size:1.1rem;">Send Test Email</h3>
        <p style="margin:0 0 1rem;font-size:0.875rem;color:var(--text-muted);">Enter the email address where you want to receive the test email.</p>
        <input type="email" id="testEmail" class="crm2-input" placeholder="test@example.com" value="{{ $config->from_address ?? '' }}" style="width:100%;margin-bottom:1rem;">
        <div id="testResult" style="display:none;padding:0.75rem;border-radius:8px;margin-bottom:1rem;font-size:0.875rem;"></div>
        <div style="display:flex;gap:0.75rem;">
            <button type="button" class="crm2-btn crm2-btn-primary" id="sendTestBtn" onclick="sendTestEmail()">
                <i class="fas fa-paper-plane"></i> Send Test
            </button>
            <button type="button" class="crm2-btn crm2-btn-secondary" onclick="closeTestModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
function togglePwd() {
    const inp = document.getElementById('mailPassword');
    const icon = document.getElementById('pwdIcon');
    if (inp.type === 'password') { inp.type = 'text'; icon.className = 'fas fa-eye-slash'; }
    else { inp.type = 'password'; icon.className = 'fas fa-eye'; }
}

function openTestModal() {
    document.getElementById('testModal').style.display = 'flex';
}
function closeTestModal() {
    document.getElementById('testModal').style.display = 'none';
    document.getElementById('testResult').style.display = 'none';
}

function sendTestEmail() {
    const btn = document.getElementById('sendTestBtn');
    const result = document.getElementById('testResult');
    const email = document.getElementById('testEmail').value;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    result.style.display = 'none';

    fetch('{{ route("admin.crm2.integrations.mail-config.test") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ test_email: email })
    })
    .then(r => r.json())
    .then(data => {
        result.style.display = 'block';
        if (data.success) {
            result.style.background = '#dcfce7'; result.style.color = '#166534';
            result.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
        } else {
            result.style.background = '#fee2e2'; result.style.color = '#991b1b';
            result.innerHTML = '<i class="fas fa-times-circle"></i> ' + data.message;
        }
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Test';
    });
}

// Toggle track visual
document.getElementById('isActive').addEventListener('change', function() {
    const track = document.getElementById('toggleTrack');
    const thumb = document.getElementById('toggleThumb');
    if (this.checked) {
        track.style.background = 'var(--accent)';
        thumb.style.left = '23px';
    } else {
        track.style.background = 'var(--border)';
        thumb.style.left = '3px';
    }
});
</script>
@endsection
