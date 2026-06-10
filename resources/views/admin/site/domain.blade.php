@extends('layouts.admin')
@section('title', 'Domain Configuration')
@section('page-title', 'Domain Configuration')

@section('content')
<style>
.dom-card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; padding:1.75rem; margin-bottom:1.5rem; }
.dom-section-title { font-size:0.82rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.06em; margin:0 0 1.25rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border); }
.dom-step { display:flex; gap:1rem; margin-bottom:1.25rem; align-items:flex-start; }
.dom-step-num { width:32px; height:32px; border-radius:50%; background:rgba(99,102,241,0.15); border:1px solid rgba(99,102,241,0.3); color:#6366f1; font-weight:800; font-size:0.875rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.dom-step-body { flex:1; }
.dom-step-title { font-size:0.9rem; font-weight:700; margin:0 0 0.35rem; }
.dom-step-desc { font-size:0.82rem; color:var(--text-secondary); line-height:1.6; margin:0; }
.dom-code { background:var(--bg-secondary); border:1px solid var(--border); border-radius:8px; padding:0.75rem 1rem; font-family:'Courier New',monospace; font-size:0.82rem; color:var(--text-primary); margin:0.5rem 0; display:flex; align-items:center; justify-content:space-between; gap:0.5rem; }
.dom-code span { word-break:break-all; }
.dom-copy-btn { background:rgba(99,102,241,0.1); border:1px solid rgba(99,102,241,0.3); color:#6366f1; border-radius:6px; padding:0.25rem 0.6rem; font-size:0.75rem; cursor:pointer; white-space:nowrap; transition:all 0.2s; }
.dom-copy-btn:hover { background:rgba(99,102,241,0.2); }
.dom-badge { display:inline-flex; align-items:center; gap:0.35rem; padding:0.25rem 0.65rem; border-radius:20px; font-size:0.75rem; font-weight:600; }
.dom-badge.active { background:rgba(34,197,94,0.12); color:#22c55e; border:1px solid rgba(34,197,94,0.25); }
.dom-badge.pending { background:rgba(245,158,11,0.12); color:#f59e0b; border:1px solid rgba(245,158,11,0.25); }
.dom-badge.none { background:rgba(160,160,160,0.1); color:var(--text-muted); border:1px solid var(--border); }
.dom-url-row { display:flex; align-items:center; gap:0.75rem; background:var(--bg-secondary); border:1px solid var(--border); border-radius:10px; padding:0.75rem 1rem; margin-bottom:0.75rem; }
.dom-url-label { font-size:0.75rem; color:var(--text-muted); min-width:80px; }
.dom-url-val { font-size:0.875rem; color:var(--text-primary); font-weight:500; flex:1; word-break:break-all; }
.dom-dns-table { width:100%; border-collapse:collapse; font-size:0.82rem; }
.dom-dns-table th { text-align:left; padding:0.5rem 0.75rem; background:var(--bg-secondary); color:var(--text-muted); font-weight:600; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; }
.dom-dns-table td { padding:0.6rem 0.75rem; border-bottom:1px solid var(--border); color:var(--text-primary); }
.dom-dns-table tr:last-child td { border-bottom:none; }
.dom-dns-table code { background:rgba(99,102,241,0.1); color:#a5b4fc; padding:0.15rem 0.4rem; border-radius:4px; font-size:0.8rem; }
.dom-alert { display:flex; gap:0.75rem; padding:1rem; border-radius:10px; font-size:0.82rem; line-height:1.6; }
.dom-alert.info { background:rgba(59,130,246,0.08); border:1px solid rgba(59,130,246,0.2); color:var(--text-secondary); }
.dom-alert.warning { background:rgba(245,158,11,0.08); border:1px solid rgba(245,158,11,0.2); color:var(--text-secondary); }
.dom-alert.success { background:rgba(34,197,94,0.08); border:1px solid rgba(34,197,94,0.2); color:var(--text-secondary); }
</style>

<div style="margin-bottom:1rem;">
    <a href="{{ route('admin.site.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Site Builder</a>
</div>
<h1 style="font-size:1.75rem;font-weight:800;margin:0 0 0.25rem;">Domain Configuration</h1>
<p style="color:var(--text-secondary);margin:0 0 1.75rem;font-size:0.9rem;">Connect your own domain (e.g. <strong>priya.in</strong>) to your Xenoraa site.</p>

@php
    $user = auth()->user();
    $username = $user->username ?? 'your-username';
    $customDomain = $user->custom_domain;
    $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');
    $serverIp = '69.62.75.225';
    $defaultUrl = 'https://' . $mainDomain . '/' . $username;
    $customUrl  = $customDomain ? 'https://' . $customDomain : null;
@endphp

{{-- Current URLs --}}
<div class="dom-card">
    <div class="dom-section-title"><i class="fas fa-link" style="margin-right:0.4rem;"></i> Your Site URLs</div>
    <div class="dom-url-row">
        <div class="dom-url-label">Default URL</div>
        <div class="dom-url-val"><a href="{{ $defaultUrl }}" target="_blank" style="color:#6366f1;">{{ $defaultUrl }}</a></div>
        <span class="dom-badge active"><i class="fas fa-check-circle"></i> Active</span>
    </div>
    @if($customDomain)
    <div class="dom-url-row">
        <div class="dom-url-label">Custom Domain</div>
        <div class="dom-url-val"><a href="{{ $customUrl }}" target="_blank" style="color:#22c55e;">{{ $customUrl }}</a></div>
        <span class="dom-badge active"><i class="fas fa-check-circle"></i> Connected</span>
    </div>
    @else
    <div class="dom-url-row">
        <div class="dom-url-label">Custom Domain</div>
        <div class="dom-url-val" style="color:var(--text-muted);">Not configured</div>
        <span class="dom-badge none"><i class="fas fa-minus-circle"></i> Not Set</span>
    </div>
    @endif
</div>

{{-- Update Custom Domain Form --}}
<div class="dom-card">
    <div class="dom-section-title"><i class="fas fa-edit" style="margin-right:0.4rem;"></i> Set Your Custom Domain</div>
    <form method="POST" action="{{ route('admin.site.domain.save') }}">
        @csrf
        <div style="display:flex;gap:1rem;align-items:flex-end;">
            <div class="form-group" style="flex:1;margin:0;">
                <label class="form-label">Custom Domain</label>
                <input type="text" name="custom_domain" class="form-control"
                    value="{{ old('custom_domain', $customDomain) }}"
                    placeholder="e.g. priya.in or www.priya.in">
                <div style="font-size:0.75rem;color:var(--text-muted);margin-top:0.35rem;">Enter your domain without https://. Leave blank to remove.</div>
            </div>
            <button type="submit" class="btn btn-primary" style="white-space:nowrap;padding:0.65rem 1.25rem;">
                <i class="fas fa-save"></i> Save Domain
            </button>
        </div>
        @error('custom_domain')
        <div style="color:var(--danger);font-size:0.8rem;margin-top:0.5rem;">{{ $message }}</div>
        @enderror
    </form>
</div>

{{-- DNS Setup Instructions --}}
<div class="dom-card">
    <div class="dom-section-title"><i class="fas fa-server" style="margin-right:0.4rem;"></i> DNS Setup Instructions</div>
    <p style="font-size:0.85rem;color:var(--text-secondary);margin:0 0 1.25rem;">After saving your domain above, configure the following DNS records at your domain registrar (GoDaddy, Namecheap, Cloudflare, etc.):</p>

    <div class="dom-alert info" style="margin-bottom:1.25rem;">
        <i class="fas fa-info-circle" style="color:#3b82f6;flex-shrink:0;margin-top:0.1rem;"></i>
        <div>Log in to your domain registrar's DNS management panel and add the records below. Changes typically propagate within <strong>15 minutes to 48 hours</strong>.</div>
    </div>

    <table class="dom-dns-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Host / Name</th>
                <th>Value / Points To</th>
                <th>TTL</th>
                <th>Purpose</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>A</code></td>
                <td><code>@</code> (root domain)</td>
                <td><code>{{ $serverIp }}</code></td>
                <td>Auto / 3600</td>
                <td>Points your domain to Xenoraa server</td>
            </tr>
            <tr>
                <td><code>A</code></td>
                <td><code>www</code></td>
                <td><code>{{ $serverIp }}</code></td>
                <td>Auto / 3600</td>
                <td>Points www.yourdomain to Xenoraa server</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top:1.25rem;">
        <div style="font-size:0.82rem;font-weight:600;margin-bottom:0.5rem;">Server IP Address to use:</div>
        <div class="dom-code">
            <span>{{ $serverIp }}</span>
            <button class="dom-copy-btn" onclick="copyText('{{ $serverIp }}', this)"><i class="fas fa-copy"></i> Copy</button>
        </div>
    </div>
</div>

{{-- Step-by-step for popular registrars --}}
<div class="dom-card">
    <div class="dom-section-title"><i class="fas fa-list-ol" style="margin-right:0.4rem;"></i> Step-by-Step Setup Guide</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        {{-- Cloudflare --}}
        <div>
            <div style="font-size:0.875rem;font-weight:700;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.5rem;">
                <span style="background:#f38020;width:20px;height:20px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.65rem;color:#fff;font-weight:800;">CF</span>
                Cloudflare
            </div>
            <div class="dom-step">
                <div class="dom-step-num">1</div>
                <div class="dom-step-body">
                    <div class="dom-step-title">Log in to Cloudflare</div>
                    <div class="dom-step-desc">Go to dash.cloudflare.com and select your domain.</div>
                </div>
            </div>
            <div class="dom-step">
                <div class="dom-step-num">2</div>
                <div class="dom-step-body">
                    <div class="dom-step-title">Open DNS Settings</div>
                    <div class="dom-step-desc">Click <strong>DNS → Records</strong> in the left sidebar.</div>
                </div>
            </div>
            <div class="dom-step">
                <div class="dom-step-num">3</div>
                <div class="dom-step-body">
                    <div class="dom-step-title">Add A Record</div>
                    <div class="dom-step-desc">Type: <strong>A</strong>, Name: <strong>@</strong>, IPv4: <strong>{{ $serverIp }}</strong>, Proxy: <strong>DNS only (grey cloud)</strong>.</div>
                </div>
            </div>
            <div class="dom-step">
                <div class="dom-step-num">4</div>
                <div class="dom-step-body">
                    <div class="dom-step-title">Add www Record</div>
                    <div class="dom-step-desc">Repeat with Name: <strong>www</strong>, same IP, DNS only.</div>
                </div>
            </div>
        </div>

        {{-- GoDaddy / Namecheap --}}
        <div>
            <div style="font-size:0.875rem;font-weight:700;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.5rem;">
                <span style="background:#1bdbdb;width:20px;height:20px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.65rem;color:#fff;font-weight:800;">GD</span>
                GoDaddy / Namecheap
            </div>
            <div class="dom-step">
                <div class="dom-step-num">1</div>
                <div class="dom-step-body">
                    <div class="dom-step-title">Log in to your registrar</div>
                    <div class="dom-step-desc">Go to your domain's DNS management page.</div>
                </div>
            </div>
            <div class="dom-step">
                <div class="dom-step-num">2</div>
                <div class="dom-step-body">
                    <div class="dom-step-title">Find DNS Records / Zone Editor</div>
                    <div class="dom-step-desc">Look for "DNS Management", "Zone Editor", or "Advanced DNS".</div>
                </div>
            </div>
            <div class="dom-step">
                <div class="dom-step-num">3</div>
                <div class="dom-step-body">
                    <div class="dom-step-title">Add / Edit A Record</div>
                    <div class="dom-step-desc">Change the existing A record (or add new) to point to <strong>{{ $serverIp }}</strong>.</div>
                </div>
            </div>
            <div class="dom-step">
                <div class="dom-step-num">4</div>
                <div class="dom-step-body">
                    <div class="dom-step-title">Save and Wait</div>
                    <div class="dom-step-desc">Save changes. DNS propagation takes 15 min – 48 hours.</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SSL Note --}}
<div class="dom-card">
    <div class="dom-section-title"><i class="fas fa-lock" style="margin-right:0.4rem;"></i> SSL Certificate (HTTPS)</div>
    @php
        $sslActive = $customDomain && file_exists('/etc/letsencrypt/live/' . $customDomain . '/fullchain.pem');
        $sslProvisioning = $customDomain && !$sslActive && file_exists(storage_path('logs/ssl-provision.log'));
    @endphp
    @if($sslActive)
    <div class="dom-alert success">
        <i class="fas fa-shield-alt" style="color:#22c55e;flex-shrink:0;margin-top:0.1rem;"></i>
        <div>
            <strong>SSL Active!</strong> Your domain <strong>{{ $customDomain }}</strong> is secured with a free Let's Encrypt SSL certificate. Your site is accessible at <a href="https://{{ $customDomain }}" target="_blank" style="color:#22c55e;">https://{{ $customDomain }}</a>.
        </div>
    </div>
    @elseif($customDomain)
    <div class="dom-alert warning">
        <i class="fas fa-spinner fa-spin" style="color:#f59e0b;flex-shrink:0;margin-top:0.1rem;"></i>
        <div>
            <strong>SSL Provisioning in Progress.</strong> Your SSL certificate for <strong>{{ $customDomain }}</strong> is being automatically provisioned. This usually takes 1–2 minutes after your DNS has propagated. Refresh this page to check status.
        </div>
    </div>
    @else
    <div class="dom-alert success">
        <i class="fas fa-shield-alt" style="color:#22c55e;flex-shrink:0;margin-top:0.1rem;"></i>
        <div>
            <strong>Automatic SSL via Let's Encrypt.</strong> When you save a custom domain and your DNS is pointing to our server, SSL is provisioned <strong>automatically</strong> — no manual steps needed. Your site will load on <strong>https://</strong> within 1–2 minutes.
        </div>
    </div>
    @endif
    <div class="dom-alert warning" style="margin-top:1rem;">
        <i class="fas fa-exclamation-triangle" style="color:#f59e0b;flex-shrink:0;margin-top:0.1rem;"></i>
        <div>
            <strong>Important:</strong> If using Cloudflare, set the proxy mode to <strong>"DNS only" (grey cloud)</strong> — not "Proxied" (orange cloud) — until SSL is issued. After SSL is active, you may enable Cloudflare proxy if desired.
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        btn.style.background = 'rgba(34,197,94,0.15)';
        btn.style.borderColor = 'rgba(34,197,94,0.4)';
        btn.style.color = '#22c55e';
        setTimeout(() => {
            btn.innerHTML = orig;
            btn.style.background = '';
            btn.style.borderColor = '';
            btn.style.color = '';
        }, 2000);
    });
}
</script>
@endpush
@endsection
