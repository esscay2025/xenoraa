@extends('layouts.superadmin')
@section('title', 'Plan & App Assignment — Super Admin')
@section('page_title', 'Plan & App Assignment')
@section('content')
<div class="sa-content">

    {{-- ─── Page Header ─────────────────────────────────────────────────────── --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap;">
        <div>
            <h2 style="font-size:1.25rem;font-weight:700;margin-bottom:0.25rem;">Plan &amp; App Assignment</h2>
            <p style="font-size:0.8rem;color:#71717a;">
                Manage subscription plans and assign apps to each tenant.
                Each plan defines how many app slots a tenant can activate.
            </p>
        </div>
        <a href="{{ route('superadmin.subscriptions') }}" class="sa-action-btn">
            <i class="fas fa-arrow-left"></i> Back to Subscriptions
        </a>
    </div>

    {{-- ─── Alert Banner ────────────────────────────────────────────────────── --}}
    <div id="sa-alert" style="display:none;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;font-size:0.85rem;display:flex;align-items:center;gap:0.5rem;"></div>
    @if(session('success'))
    <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);color:#22c55e;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;font-size:0.85rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════════
         SECTION 1 — Plan Definitions (read-only reference cards)
    ══════════════════════════════════════════════════════════════════════════ --}}
    <div style="margin-bottom:2rem;">
        <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:1rem;">
            <div style="width:3px;height:1.1rem;background:#7c3aed;border-radius:2px;"></div>
            <h3 style="font-size:0.9rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#a1a1aa;">
                Subscription Plans
            </h3>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;">
            @foreach($plans as $planKey => $plan)
            @php
                $accentColor = match($planKey) {
                    'solo_app'   => '#71717a',
                    'duo_bundle' => '#7c3aed',
                    'all_access' => '#eab308',
                    default      => '#7c3aed',
                };
                $accentGlow = match($planKey) {
                    'solo_app'   => 'rgba(113,113,122,0.10)',
                    'duo_bundle' => 'rgba(124,58,237,0.10)',
                    'all_access' => 'rgba(234,179,8,0.10)',
                    default      => 'rgba(124,58,237,0.10)',
                };
                $planBadge = $plan['badge'] ?? null;
                $appSlots  = $plan['app_slots'] ?? 0;
                $tenantCount = $tenantsByPlan[$planKey] ?? 0;
            @endphp
            <div class="sa-card" style="border-color:{{ $accentColor }}44;position:relative;overflow:hidden;">
                {{-- Glow orb --}}
                <div style="position:absolute;top:-30px;right:-30px;width:100px;height:100px;background:{{ $accentColor }}18;border-radius:50%;filter:blur(25px);pointer-events:none;"></div>

                {{-- Card Header --}}
                <div style="padding:1.25rem 1.5rem;border-bottom:1px solid {{ $accentColor }}22;background:{{ $accentGlow }};position:relative;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.75rem;">
                        <div>
                            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.4rem;">
                                <span style="font-size:0.58rem;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;color:{{ $accentColor }};background:{{ $accentGlow }};border:1px solid {{ $accentColor }}44;padding:0.15rem 0.55rem;border-radius:4px;">
                                    {{ strtoupper(str_replace('_', ' ', $planKey)) }}
                                </span>
                                @if($planBadge)
                                <span style="font-size:0.58rem;font-weight:700;color:#fff;background:#7c3aed;padding:0.15rem 0.55rem;border-radius:4px;">{{ $planBadge }}</span>
                                @endif
                            </div>
                            <div style="font-size:1rem;font-weight:700;color:#fff;">{{ $plan['name'] }}</div>
                            <div style="font-size:0.72rem;color:#71717a;margin-top:0.2rem;">{{ $plan['description'] ?? '' }}</div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <div style="font-size:1.15rem;font-weight:800;color:{{ $accentColor }};">
                                ₹{{ number_format($plan['price_monthly']) }}<span style="font-size:0.68rem;font-weight:500;color:#71717a;">/mo</span>
                            </div>
                            <div style="font-size:0.68rem;color:#52525b;">₹{{ number_format($plan['price_yearly']) }}/yr</div>
                        </div>
                    </div>
                </div>

                {{-- App Slots Info --}}
                <div style="padding:1rem 1.5rem;border-bottom:1px solid #1a1a1a;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
                        <span style="font-size:0.72rem;font-weight:600;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.06em;">App Slots</span>
                        <span style="font-size:0.72rem;color:#71717a;">{{ $tenantCount }} tenant{{ $tenantCount !== 1 ? 's' : '' }}</span>
                    </div>
                    @if($appSlots >= 99)
                    <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.8rem;color:{{ $accentColor }};font-weight:600;">
                        <i class="fas fa-infinity" style="font-size:0.75rem;"></i> All 4 apps included
                    </div>
                    @else
                    <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                        @for($s = 1; $s <= 4; $s++)
                        <div style="width:28px;height:28px;border-radius:6px;border:1.5px solid {{ $s <= $appSlots ? $accentColor : '#2a2a2a' }};background:{{ $s <= $appSlots ? $accentColor . '18' : 'transparent' }};display:flex;align-items:center;justify-content:center;">
                            @if($s <= $appSlots)
                            <i class="fas fa-check" style="font-size:0.6rem;color:{{ $accentColor }};"></i>
                            @else
                            <i class="fas fa-lock" style="font-size:0.55rem;color:#3a3a3a;"></i>
                            @endif
                        </div>
                        @endfor
                        <span style="font-size:0.75rem;color:#71717a;align-self:center;margin-left:0.25rem;">{{ $appSlots }} of 4 apps</span>
                    </div>
                    @endif
                </div>

                {{-- Available Apps --}}
                <div style="padding:1rem 1.5rem;">
                    <div style="font-size:0.68rem;font-weight:600;color:#52525b;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.6rem;">Available Apps</div>
                    <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                        @foreach($appDefs as $appKey => $appDef)
                        @php
                            $appAvail = ($appSlots >= 99) || in_array($appKey, $plan['available_apps'] ?? []);
                            $appColor = $appDef['color'] ?? '#7c3aed';
                        @endphp
                        <span style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.68rem;font-weight:500;padding:0.2rem 0.55rem;border-radius:5px;
                            background:{{ $appAvail ? $appColor . '18' : 'rgba(255,255,255,0.03)' }};
                            border:1px solid {{ $appAvail ? $appColor . '44' : '#2a2a2a' }};
                            color:{{ $appAvail ? $appColor : '#3a3a3a' }};">
                            <i class="fas {{ $appDef['icon'] }}" style="font-size:0.6rem;"></i>
                            {{ $appDef['name'] }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         SECTION 2 — Tenant App Assignment Table
    ══════════════════════════════════════════════════════════════════════════ --}}
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
            <div style="display:flex;align-items:center;gap:0.6rem;">
                <div style="width:3px;height:1.1rem;background:#7c3aed;border-radius:2px;"></div>
                <h3 style="font-size:0.9rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#a1a1aa;">
                    Tenant App Assignment
                </h3>
            </div>
            <span style="font-size:0.75rem;color:#52525b;">{{ count($tenants) }} tenants</span>
        </div>

        <div class="sa-card" style="overflow:hidden;">
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;min-width:700px;">
                    <thead>
                        <tr style="border-bottom:1px solid #1e1e1e;">
                            <th style="padding:0.75rem 1rem;text-align:left;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#52525b;white-space:nowrap;">Tenant</th>
                            <th style="padding:0.75rem 1rem;text-align:left;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#52525b;white-space:nowrap;">Plan</th>
                            <th style="padding:0.75rem 1rem;text-align:left;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#52525b;white-space:nowrap;">Selected Apps</th>
                            <th style="padding:0.75rem 1rem;text-align:left;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#52525b;white-space:nowrap;">Status</th>
                            <th style="padding:0.75rem 1rem;text-align:right;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#52525b;white-space:nowrap;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenants as $tenant)
                        @php
                            $tPlan       = $tenant->plan ?? 'solo_app';
                            $tApps       = is_array($tenant->selected_apps) ? $tenant->selected_apps : (json_decode($tenant->selected_apps ?? '[]', true) ?? []);
                            $tSlots      = $plans[$tPlan]['app_slots'] ?? 1;
                            $tAccent     = match($tPlan) {
                                'solo_app'   => '#71717a',
                                'duo_bundle' => '#7c3aed',
                                'all_access' => '#eab308',
                                default      => '#71717a',
                            };
                            $tStatus     = $tenant->status ?? 'active';
                        @endphp
                        <tr class="tenant-row" data-user-id="{{ $tenant->id }}" style="border-bottom:1px solid #111;transition:background 0.15s;">
                            {{-- Tenant Info --}}
                            <td style="padding:0.9rem 1rem;">
                                <div style="display:flex;align-items:center;gap:0.65rem;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(124,58,237,0.12);border:1px solid rgba(124,58,237,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.8rem;font-weight:700;color:#a855f7;">
                                        {{ strtoupper(substr($tenant->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-size:0.82rem;font-weight:600;color:#e4e4e7;">{{ $tenant->name }}</div>
                                        <div style="font-size:0.68rem;color:#52525b;">{{ $tenant->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Plan Selector --}}
                            <td style="padding:0.9rem 1rem;">
                                <select class="plan-select" data-user-id="{{ $tenant->id }}"
                                    style="background:#111;border:1px solid #2a2a2a;color:#e4e4e7;font-size:0.78rem;padding:0.35rem 0.6rem;border-radius:6px;cursor:pointer;outline:none;min-width:130px;"
                                    onchange="onPlanChange(this)">
                                    @foreach($plans as $pk => $pv)
                                    <option value="{{ $pk }}" {{ $tPlan === $pk ? 'selected' : '' }}>{{ $pv['name'] }}</option>
                                    @endforeach
                                </select>
                            </td>

                            {{-- App Checkboxes --}}
                            <td style="padding:0.9rem 1rem;">
                                <div class="app-checkboxes" data-user-id="{{ $tenant->id }}" data-slots="{{ $tSlots }}"
                                    style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                                    @foreach($appDefs as $appKey => $appDef)
                                    @php
                                        $isChecked  = in_array($appKey, $tApps);
                                        $appColor   = $appDef['color'] ?? '#7c3aed';
                                    @endphp
                                    <label class="app-chip" data-app="{{ $appKey }}" data-user-id="{{ $tenant->id }}"
                                        style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.68rem;font-weight:500;
                                            padding:0.25rem 0.6rem;border-radius:5px;cursor:pointer;user-select:none;transition:all 0.15s;
                                            background:{{ $isChecked ? $appColor . '18' : 'rgba(255,255,255,0.03)' }};
                                            border:1.5px solid {{ $isChecked ? $appColor . '66' : '#2a2a2a' }};
                                            color:{{ $isChecked ? $appColor : '#52525b' }};"
                                        onclick="toggleApp(this, '{{ $appKey }}', '{{ $appColor }}')">
                                        <input type="checkbox" class="app-cb" style="display:none;"
                                            name="apps[]" value="{{ $appKey }}" {{ $isChecked ? 'checked' : '' }}>
                                        <i class="fas {{ $appDef['icon'] }}" style="font-size:0.6rem;"></i>
                                        {{ $appDef['name'] }}
                                    </label>
                                    @endforeach
                                </div>
                                <div class="slot-hint" style="font-size:0.65rem;color:#52525b;margin-top:0.3rem;">
                                    <span class="slot-count">{{ count($tApps) }}</span> / <span class="slot-max">{{ $tSlots >= 99 ? '4' : $tSlots }}</span> apps selected
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td style="padding:0.9rem 1rem;">
                                @php
                                    $statusColor = match($tStatus) {
                                        'active'    => '#22c55e',
                                        'suspended' => '#ef4444',
                                        'trial'     => '#f59e0b',
                                        default     => '#71717a',
                                    };
                                @endphp
                                <span style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.68rem;font-weight:600;
                                    padding:0.2rem 0.55rem;border-radius:5px;
                                    background:{{ $statusColor }}18;border:1px solid {{ $statusColor }}44;color:{{ $statusColor }};">
                                    <span style="width:5px;height:5px;border-radius:50%;background:{{ $statusColor }};"></span>
                                    {{ ucfirst($tStatus) }}
                                </span>
                            </td>

                            {{-- Save Button --}}
                            <td style="padding:0.9rem 1rem;text-align:right;">
                                <button class="save-btn sa-btn-primary"
                                    data-user-id="{{ $tenant->id }}"
                                    onclick="saveTenant({{ $tenant->id }}, this)"
                                    style="padding:0.35rem 0.9rem;font-size:0.75rem;white-space:nowrap;">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ─── App Reference Legend ─────────────────────────────────────────── --}}
    <div class="sa-card" style="margin-top:1.5rem;">
        <div class="sa-card-header">
            <span class="sa-card-title"><i class="fas fa-info-circle" style="color:#7c3aed;margin-right:0.5rem;"></i>App Module Reference</span>
            <span style="font-size:0.72rem;color:#52525b;">What each app includes</span>
        </div>
        <div style="padding:1.25rem;display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:0.75rem;">
            @foreach($appDefs as $appKey => $appDef)
            @php $appColor = $appDef['color'] ?? '#7c3aed'; @endphp
            <div style="padding:0.75rem;border-radius:8px;background:rgba(255,255,255,0.02);border:1px solid #1a1a1a;">
                <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;">
                    <div style="width:28px;height:28px;border-radius:7px;background:{{ $appColor }}18;border:1px solid {{ $appColor }}33;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas {{ $appDef['icon'] }}" style="font-size:0.7rem;color:{{ $appColor }};"></i>
                    </div>
                    <div>
                        <div style="font-size:0.78rem;font-weight:700;color:#e4e4e7;">{{ $appDef['name'] }}</div>
                        <div style="font-size:0.62rem;color:#52525b;">{{ $appDef['description'] ?? '' }}</div>
                    </div>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:0.3rem;">
                    @foreach($appDef['modules'] as $mod)
                    <span style="font-size:0.6rem;padding:0.1rem 0.4rem;border-radius:3px;background:{{ $appColor }}12;border:1px solid {{ $appColor }}22;color:{{ $appColor }};">{{ $mod }}</span>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ─── CSRF Token for AJAX ──────────────────────────────────────────────── --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
// ── Plan slot limits (from config)
const planSlots = @json(array_map(fn($p) => $p['app_slots'] ?? 1, $plans));

// ── When plan dropdown changes, update slot count and enforce limits
function onPlanChange(select) {
    const userId = select.dataset.userId;
    const plan   = select.value;
    const slots  = planSlots[plan] ?? 1;

    const chipContainer = document.querySelector(`.app-checkboxes[data-user-id="${userId}"]`);
    const slotMax       = chipContainer.querySelector('.slot-max');
    const slotHint      = chipContainer.closest('td').querySelector('.slot-hint');

    // Update slot display
    chipContainer.dataset.slots = slots;
    if (slotMax) slotMax.textContent = slots >= 99 ? '4' : slots;

    // For all_access: auto-select all apps
    if (slots >= 99) {
        chipContainer.querySelectorAll('.app-chip').forEach(chip => {
            const cb    = chip.querySelector('.app-cb');
            const color = chip.dataset.appColor || '#7c3aed';
            cb.checked  = true;
            applyChipStyle(chip, true, color);
        });
    } else {
        // Enforce slot limit — uncheck extras
        enforceSlotLimit(userId, slots);
    }
    updateSlotCount(userId);
}

// ── Toggle app chip on/off (respects slot limit)
function toggleApp(label, appKey, appColor) {
    const cb      = label.querySelector('.app-cb');
    const userId  = label.dataset.userId;
    const container = document.querySelector(`.app-checkboxes[data-user-id="${userId}"]`);
    const slots   = parseInt(container.dataset.slots) || 1;
    const checked = container.querySelectorAll('.app-cb:checked').length;

    if (!cb.checked) {
        // Trying to check — enforce limit
        if (slots < 99 && checked >= slots) {
            showAlert('This plan allows only ' + slots + ' app' + (slots > 1 ? 's' : '') + '. Uncheck another app first.', 'warn');
            return;
        }
        cb.checked = true;
        applyChipStyle(label, true, appColor);
    } else {
        cb.checked = false;
        applyChipStyle(label, false, appColor);
    }
    updateSlotCount(userId);
}

// ── Apply visual style to a chip
function applyChipStyle(label, active, color) {
    label.style.background   = active ? color + '18' : 'rgba(255,255,255,0.03)';
    label.style.borderColor  = active ? color + '66' : '#2a2a2a';
    label.style.color        = active ? color : '#52525b';
}

// ── Uncheck chips beyond slot limit
function enforceSlotLimit(userId, slots) {
    const container = document.querySelector(`.app-checkboxes[data-user-id="${userId}"]`);
    const chips     = container.querySelectorAll('.app-chip');
    let count = 0;
    chips.forEach(chip => {
        const cb    = chip.querySelector('.app-cb');
        const color = chip.style.color || '#7c3aed';
        if (cb.checked) {
            count++;
            if (count > slots) {
                cb.checked = false;
                applyChipStyle(chip, false, color);
            }
        }
    });
}

// ── Update slot count display
function updateSlotCount(userId) {
    const container = document.querySelector(`.app-checkboxes[data-user-id="${userId}"]`);
    const checked   = container.querySelectorAll('.app-cb:checked').length;
    const countEl   = container.closest('td').querySelector('.slot-count');
    if (countEl) countEl.textContent = checked;
}

// ── Save tenant plan + apps via AJAX
function saveTenant(userId, btn) {
    const row       = document.querySelector(`.tenant-row[data-user-id="${userId}"]`);
    const plan      = row.querySelector('.plan-select').value;
    const container = row.querySelector(`.app-checkboxes[data-user-id="${userId}"]`);
    const apps      = Array.from(container.querySelectorAll('.app-cb:checked')).map(cb => cb.value);

    btn.disabled    = true;
    btn.innerHTML   = '<i class="fas fa-spinner fa-spin"></i> Saving…';

    fetch('{{ route("superadmin.plan-modules.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ user_id: userId, plan: plan, selected_apps: apps }),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Save';
        if (data.success) {
            showAlert('Saved — ' + data.message, 'success');
            btn.innerHTML = '<i class="fas fa-check"></i> Saved';
            setTimeout(() => { btn.innerHTML = '<i class="fas fa-save"></i> Save'; }, 2000);
        } else {
            showAlert(data.message || 'Save failed.', 'error');
        }
    })
    .catch(err => {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Save';
        showAlert('Network error. Please try again.', 'error');
        console.error(err);
    });
}

// ── Show top alert banner
function showAlert(msg, type) {
    const el = document.getElementById('sa-alert');
    const styles = {
        success: { bg: 'rgba(34,197,94,0.08)',  border: 'rgba(34,197,94,0.25)',  color: '#22c55e', icon: 'fa-check-circle' },
        warn:    { bg: 'rgba(234,179,8,0.08)',   border: 'rgba(234,179,8,0.25)',  color: '#eab308', icon: 'fa-exclamation-triangle' },
        error:   { bg: 'rgba(239,68,68,0.08)',   border: 'rgba(239,68,68,0.25)',  color: '#ef4444', icon: 'fa-times-circle' },
    };
    const s = styles[type] || styles.success;
    el.style.cssText = `display:flex;align-items:center;gap:0.5rem;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;font-size:0.85rem;background:${s.bg};border:1px solid ${s.border};color:${s.color};`;
    el.innerHTML = `<i class="fas ${s.icon}"></i> ${msg}`;
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    setTimeout(() => { el.style.display = 'none'; }, 4000);
}

// ── Row hover effect
document.querySelectorAll('.tenant-row').forEach(row => {
    row.addEventListener('mouseenter', () => row.style.background = 'rgba(255,255,255,0.025)');
    row.addEventListener('mouseleave', () => row.style.background = '');
});
</script>
@endsection
