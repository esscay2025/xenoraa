@extends('layouts.admin')
@section('title', 'E-commerce Mail Config')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = false;
    $ecommerceActive = true; $siteActive = false;
    $integrationsActive = true;
@endphp
@section('content')
<style>
.mc-page{padding:2rem;max-width:900px;margin:0 auto}
.mc-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem}
.mc-header h1{font-size:1.5rem;font-weight:700;color:var(--text-primary);margin:0}
.mc-header p{color:var(--text-muted);margin:.25rem 0 0;font-size:.875rem}
.mc-card{background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:1.5rem}
.mc-card-header{padding:1rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:.75rem}
.mc-card-header h3{margin:0;font-size:1rem;font-weight:600;color:var(--text-primary)}
.mc-card-header .mc-icon{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem}
.mc-card-body{padding:1.5rem}
.mc-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.mc-form-grid.cols-3{grid-template-columns:1fr 1fr 1fr}
.mc-form-grid.cols-1{grid-template-columns:1fr}
.mc-field{display:flex;flex-direction:column;gap:.4rem}
.mc-field label{font-size:.8rem;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.04em}
.mc-field input,.mc-field select{background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;padding:.6rem .9rem;font-size:.9rem;color:var(--text-primary);outline:none;transition:border-color .2s}
.mc-field input:focus,.mc-field select:focus{border-color:var(--accent)}
.mc-field input[type=password]{font-family:monospace}
.mc-divider{height:1px;background:var(--border);margin:1.25rem 0}
.mc-toggle-row{display:flex;align-items:center;justify-content:space-between;padding:.75rem 0}
.mc-toggle-row label{font-size:.9rem;color:var(--text-primary);font-weight:500}
.mc-toggle-row small{display:block;font-size:.78rem;color:var(--text-muted);margin-top:.1rem}
.toggle-switch{position:relative;display:inline-block;width:44px;height:24px}
.toggle-switch input{opacity:0;width:0;height:0}
.toggle-slider{position:absolute;cursor:pointer;inset:0;background:#475569;border-radius:24px;transition:.3s}
.toggle-slider:before{content:"";position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s}
input:checked+.toggle-slider{background:var(--accent)}
input:checked+.toggle-slider:before{transform:translateX(20px)}
.mc-actions{display:flex;align-items:center;gap:.75rem;margin-top:1.5rem;flex-wrap:wrap}
.mc-btn{display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1.25rem;border-radius:8px;font-size:.875rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
.mc-btn.primary{background:var(--accent);color:#fff}
.mc-btn.primary:hover{opacity:.9}
.mc-btn.outline{background:transparent;border:1px solid var(--border);color:var(--text-primary)}
.mc-btn.outline:hover{background:var(--bg-secondary)}
.mc-btn.success{background:#10b981;color:#fff}
.mc-status{display:inline-flex;align-items:center;gap:.4rem;padding:.3rem .75rem;border-radius:20px;font-size:.78rem;font-weight:600}
.mc-status.active{background:rgba(16,185,129,.12);color:#10b981}
.mc-status.inactive{background:rgba(100,116,139,.12);color:#64748b}
.mc-status.verified{background:rgba(99,102,241,.12);color:var(--accent)}
.mc-test-result{margin-top:.75rem;padding:.75rem 1rem;border-radius:8px;font-size:.875rem;display:none}
.mc-test-result.success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#10b981}
.mc-test-result.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#ef4444}
.mc-guide{background:var(--bg-secondary);border:1px solid var(--border);border-radius:10px;padding:1rem 1.25rem}
.mc-guide h4{margin:0 0 .75rem;font-size:.875rem;font-weight:600;color:var(--text-primary)}
.mc-guide-grid{display:grid;grid-template-columns:1fr 1fr;gap:.75rem}
.mc-guide-item{background:var(--bg-card);border:1px solid var(--border);border-radius:8px;padding:.75rem 1rem}
.mc-guide-item h5{margin:0 0 .4rem;font-size:.8rem;font-weight:700;color:var(--accent)}
.mc-guide-item p{margin:0;font-size:.75rem;color:var(--text-muted);line-height:1.5}
.mc-alert{padding:.75rem 1rem;border-radius:8px;font-size:.875rem;margin-bottom:1.25rem;display:flex;align-items:flex-start;gap:.6rem}
.mc-alert.success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#10b981}
.mc-alert.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#ef4444}
@media(max-width:640px){.mc-form-grid,.mc-form-grid.cols-3{grid-template-columns:1fr}.mc-guide-grid{grid-template-columns:1fr}}
</style>

<div class="mc-page">
  {{-- Header --}}
  <div class="mc-header">
    <div>
      <h1><i class="fas fa-plug" style="color:var(--accent);margin-right:.5rem"></i> Mail Configuration</h1>
      <p>Configure your outbound email settings for E-commerce transactional emails.</p>
    </div>
    <div style="display:flex;align-items:center;gap:.75rem">
      @if($config)
        <span class="mc-status {{ $config->is_active ? 'active' : 'inactive' }}">
          <i class="fas fa-circle" style="font-size:.5rem"></i>
          {{ $config->is_active ? 'Active' : 'Inactive' }}
        </span>
        @if($config->verified_at)
        <span class="mc-status verified">
          <i class="fas fa-check-circle"></i> Verified
        </span>
        @endif
      @endif
    </div>
  </div>

  @if(session('success'))
  <div class="mc-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
  <div class="mc-alert error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
  @endif

  <form method="POST" action="{{ route('admin.ecommerce.integrations.mail-config.save') }}">
    @csrf

    {{-- SMTP Settings --}}
    <div class="mc-card">
      <div class="mc-card-header">
        <div class="mc-icon" style="background:rgba(99,102,241,.12);color:var(--accent)"><i class="fas fa-server"></i></div>
        <div>
          <h3>SMTP Server Settings</h3>
          <p style="margin:0;font-size:.8rem;color:var(--text-muted)">Connection details for your outgoing mail server</p>
        </div>
      </div>
      <div class="mc-card-body">
        <div class="mc-form-grid" style="margin-bottom:1rem">
          <div class="mc-field">
            <label>Mail Driver</label>
            <select name="mail_driver">
              @foreach(['smtp'=>'SMTP','sendmail'=>'Sendmail','mailgun'=>'Mailgun','ses'=>'Amazon SES'] as $val=>$lbl)
              <option value="{{ $val }}" {{ ($config?->mail_driver ?? 'smtp') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
              @endforeach
            </select>
          </div>
          <div class="mc-field">
            <label>Encryption</label>
            <select name="mail_encryption">
              @foreach(['tls'=>'TLS (Recommended)','ssl'=>'SSL','none'=>'None'] as $val=>$lbl)
              <option value="{{ $val }}" {{ ($config?->mail_encryption ?? 'tls') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="mc-form-grid cols-3" style="margin-bottom:1rem">
          <div class="mc-field" style="grid-column:1/3">
            <label>SMTP Host</label>
            <input type="text" name="mail_host" value="{{ $config?->mail_host }}" placeholder="mail.yourdomain.com">
          </div>
          <div class="mc-field">
            <label>Port</label>
            <input type="number" name="mail_port" value="{{ $config?->mail_port ?? 587 }}" placeholder="587">
          </div>
        </div>
        <div class="mc-form-grid">
          <div class="mc-field">
            <label>Username</label>
            <input type="text" name="mail_username" value="{{ $config?->mail_username }}" placeholder="your@email.com" autocomplete="off">
          </div>
          <div class="mc-field">
            <label>Password</label>
            <input type="password" name="mail_password" value="" placeholder="{{ $config ? '••••••••••••' : 'Enter password' }}" autocomplete="new-password">
            @if($config?->mail_password) <small style="font-size:.75rem;color:var(--text-muted)">Leave blank to keep existing password</small> @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Sender Identity --}}
    <div class="mc-card">
      <div class="mc-card-header">
        <div class="mc-icon" style="background:rgba(16,185,129,.12);color:#10b981"><i class="fas fa-envelope"></i></div>
        <div>
          <h3>Sender Identity</h3>
          <p style="margin:0;font-size:.8rem;color:var(--text-muted)">How your emails appear to recipients</p>
        </div>
      </div>
      <div class="mc-card-body">
        <div class="mc-form-grid" style="margin-bottom:1rem">
          <div class="mc-field">
            <label>From Address</label>
            <input type="email" name="from_address" value="{{ $config?->from_address }}" placeholder="orders@yourstore.com" required>
          </div>
          <div class="mc-field">
            <label>From Name</label>
            <input type="text" name="from_name" value="{{ $config?->from_name }}" placeholder="Your Store Name" required>
          </div>
        </div>
        <div class="mc-form-grid cols-1">
          <div class="mc-field">
            <label>Reply-To (optional)</label>
            <input type="email" name="reply_to" value="{{ $config?->reply_to }}" placeholder="support@yourstore.com">
          </div>
        </div>
        <div class="mc-divider"></div>
        <div class="mc-toggle-row">
          <div>
            <label>Activate this mail configuration</label>
            <small>Use this config for all E-commerce outbound emails</small>
          </div>
          <label class="toggle-switch">
            <input type="checkbox" name="is_active" value="1" {{ $config?->is_active ? 'checked' : '' }}>
            <span class="toggle-slider"></span>
          </label>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="mc-actions">
      <button type="submit" class="mc-btn primary">
        <i class="fas fa-save"></i> Save Configuration
      </button>
      @if($config)
      <button type="button" class="mc-btn outline" onclick="sendTestEmail()">
        <i class="fas fa-paper-plane"></i> Send Test Email
      </button>
      @endif
    </div>
    <div class="mc-test-result" id="testResult"></div>
  </form>

  {{-- Last Error --}}
  @if($config?->last_error)
  <div class="mc-alert error" style="margin-top:1rem">
    <i class="fas fa-exclamation-triangle"></i>
    <div><strong>Last Error:</strong> {{ $config->last_error }}</div>
  </div>
  @endif

  {{-- Quick Guide --}}
  <div class="mc-card" style="margin-top:1.5rem">
    <div class="mc-card-header">
      <div class="mc-icon" style="background:rgba(245,158,11,.12);color:#f59e0b"><i class="fas fa-book"></i></div>
      <h3>Quick Setup Guide</h3>
    </div>
    <div class="mc-card-body">
      <div class="mc-guide-grid">
        <div class="mc-guide-item">
          <h5><i class="fab fa-google" style="margin-right:.3rem"></i> Gmail / Google Workspace</h5>
          <p>Host: smtp.gmail.com | Port: 587 | Encryption: TLS<br>Use App Password (not your Google password)</p>
        </div>
        <div class="mc-guide-item">
          <h5><i class="fab fa-microsoft" style="margin-right:.3rem"></i> Outlook / Office 365</h5>
          <p>Host: smtp.office365.com | Port: 587 | Encryption: TLS<br>Use your full email as username</p>
        </div>
        <div class="mc-guide-item">
          <h5><i class="fas fa-mail-bulk" style="margin-right:.3rem"></i> Zoho Mail</h5>
          <p>Host: smtp.zoho.com | Port: 587 | Encryption: TLS<br>Enable SMTP in Zoho Mail settings first</p>
        </div>
        <div class="mc-guide-item">
          <h5><i class="fas fa-bolt" style="margin-right:.3rem"></i> SendGrid</h5>
          <p>Host: smtp.sendgrid.net | Port: 587 | Encryption: TLS<br>Username: apikey | Password: your API key</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function sendTestEmail() {
    const btn = event.target.closest('button');
    const result = document.getElementById('testResult');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    result.style.display = 'none';

    fetch('{{ route("admin.ecommerce.integrations.mail-config.test") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({})
    })
    .then(r => r.json())
    .then(data => {
        result.className = 'mc-test-result ' + (data.success ? 'success' : 'error');
        result.innerHTML = '<i class="fas fa-' + (data.success ? 'check-circle' : 'times-circle') + '"></i> ' + data.message;
        result.style.display = 'flex';
        result.style.alignItems = 'center';
        result.style.gap = '.5rem';
    })
    .catch(() => {
        result.className = 'mc-test-result error';
        result.innerHTML = '<i class="fas fa-times-circle"></i> Request failed. Please check your connection.';
        result.style.display = 'flex';
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Test Email';
    });
}
</script>
@endsection
