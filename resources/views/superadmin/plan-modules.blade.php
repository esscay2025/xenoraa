@extends('layouts.superadmin')
@section('title', 'Plan Module Access — Super Admin')
@section('page_title', 'Plan Module Access')

@section('content')
<div class="sa-content">

    {{-- Page Header --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap;">
        <div>
            <h2 style="font-size:1.25rem;font-weight:700;margin-bottom:0.25rem;">Plan Module Access</h2>
            <p style="font-size:0.8rem;color:#71717a;">Control which modules are available for each subscription plan. Changes take effect immediately for all tenants on that plan.</p>
        </div>
        <a href="{{ route('superadmin.subscriptions') }}" class="sa-action-btn">
            <i class="fas fa-arrow-left"></i> Back to Subscriptions
        </a>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
    <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);color:#22c55e;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;font-size:0.85rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.plan-modules.save') }}">
        @csrf

        {{-- Plan Cards Grid --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.25rem;margin-bottom:1.5rem;">

            @foreach($plans as $planKey => $plan)
            @php
                $planMods   = $planModules[$planKey] ?? [];
                $isTopPlan  = $planKey === 'business_pro';
                $accentColor = match($planKey) {
                    'starter'      => '#71717a',
                    'professional' => '#7c3aed',
                    'business_pro' => '#eab308',
                    default        => '#7c3aed',
                };
                $accentGlow = match($planKey) {
                    'starter'      => 'rgba(113,113,122,0.12)',
                    'professional' => 'rgba(124,58,237,0.12)',
                    'business_pro' => 'rgba(234,179,8,0.12)',
                    default        => 'rgba(124,58,237,0.12)',
                };
                $planLabel = match($planKey) {
                    'starter'      => 'STARTER',
                    'professional' => 'PROFESSIONAL',
                    'business_pro' => 'BUSINESS PRO',
                    default        => strtoupper($planKey),
                };
            @endphp

            <div class="sa-card" style="border-color:{{ $accentColor }}33;display:flex;flex-direction:column;">

                {{-- Card Header --}}
                <div style="padding:1.25rem 1.5rem;border-bottom:1px solid {{ $accentColor }}22;background:{{ $accentGlow }};position:relative;overflow:hidden;">
                    {{-- Glow orb --}}
                    <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;background:{{ $accentColor }}1a;border-radius:50%;filter:blur(20px);pointer-events:none;"></div>

                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;position:relative;">
                        <div>
                            <span style="display:inline-block;font-size:0.6rem;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;color:{{ $accentColor }};background:{{ $accentGlow }};border:1px solid {{ $accentColor }}44;padding:0.15rem 0.6rem;border-radius:4px;margin-bottom:0.5rem;">
                                {{ $planLabel }}
                            </span>
                            <div style="font-size:1rem;font-weight:700;color:#fff;">{{ $plan['name'] }}</div>
                            @if($isTopPlan)
                            <div style="font-size:0.72rem;color:#a1a1aa;margin-top:0.25rem;display:flex;align-items:center;gap:0.3rem;">
                                <i class="fas fa-infinity" style="color:{{ $accentColor }};font-size:0.65rem;"></i> All modules always included
                            </div>
                            @endif
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <div style="font-size:1.1rem;font-weight:800;color:{{ $accentColor }};">
                                ₹{{ number_format($plan['price_monthly']) }}<span style="font-size:0.7rem;font-weight:500;color:#71717a;">/mo</span>
                            </div>
                            <div style="font-size:0.72rem;color:#52525b;">₹{{ number_format($plan['price_yearly']) }}/yr</div>
                        </div>
                    </div>

                    {{-- Module count badge --}}
                    @php $enabledCount = $isTopPlan ? count($allModules) : count($planMods); @endphp
                    <div style="margin-top:0.75rem;display:flex;align-items:center;gap:0.5rem;">
                        <div style="flex:1;height:4px;background:#1a1a1a;border-radius:2px;overflow:hidden;">
                            <div style="height:100%;width:{{ round(($enabledCount / max(count($allModules),1)) * 100) }}%;background:{{ $accentColor }};border-radius:2px;transition:width 0.4s;"></div>
                        </div>
                        <span style="font-size:0.7rem;font-weight:700;color:{{ $accentColor }};white-space:nowrap;">{{ $enabledCount }} / {{ count($allModules) }}</span>
                    </div>
                </div>

                {{-- Module Toggles --}}
                <div style="flex:1;overflow-y:auto;max-height:480px;">
                    @foreach($allModules as $modKey => $mod)
                    @php
                        $isEnabled = $isTopPlan || in_array($modKey, $planMods);
                    @endphp
                    <label
                        for="mod_{{ $planKey }}_{{ $modKey }}"
                        style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.25rem;border-bottom:1px solid #1a1a1a;cursor:{{ $isTopPlan ? 'default' : 'pointer' }};transition:background 0.15s;{{ $isEnabled ? 'background:rgba(255,255,255,0.02);' : '' }}"
                        onmouseover="{{ !$isTopPlan ? 'this.style.background=\"rgba(255,255,255,0.04)\"' : '' }}"
                        onmouseout="{{ !$isTopPlan ? 'this.style.background=\"'.($isEnabled ? 'rgba(255,255,255,0.02)' : 'transparent').'\"' : '' }}"
                    >
                        {{-- Checkbox --}}
                        <div style="position:relative;flex-shrink:0;">
                            <input
                                type="checkbox"
                                name="{{ $planKey }}[]"
                                value="{{ $modKey }}"
                                id="mod_{{ $planKey }}_{{ $modKey }}"
                                {{ $isEnabled ? 'checked' : '' }}
                                {{ $isTopPlan ? 'disabled' : '' }}
                                onchange="updateCounter('{{ $planKey }}')"
                                style="display:none;"
                            >
                            <div class="xn-toggle" data-plan="{{ $planKey }}" data-mod="{{ $modKey }}" data-checked="{{ $isEnabled ? '1' : '0' }}" data-locked="{{ $isTopPlan ? '1' : '0' }}"
                                style="width:36px;height:20px;border-radius:10px;background:{{ $isEnabled ? $accentColor : '#222' }};border:1px solid {{ $isEnabled ? $accentColor : '#333' }};position:relative;transition:all 0.2s;{{ $isTopPlan ? 'opacity:0.6;' : 'cursor:pointer;' }}"
                                onclick="{{ !$isTopPlan ? 'toggleModule(this)' : '' }}"
                            >
                                <div style="position:absolute;top:2px;left:{{ $isEnabled ? '18px' : '2px' }};width:14px;height:14px;border-radius:50%;background:#fff;transition:left 0.2s;box-shadow:0 1px 3px rgba(0,0,0,0.4);"></div>
                            </div>
                        </div>

                        {{-- Icon --}}
                        <div style="width:30px;height:30px;border-radius:8px;background:{{ $isEnabled ? $accentGlow : 'rgba(255,255,255,0.03)' }};border:1px solid {{ $isEnabled ? $accentColor.'33' : '#222' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all 0.2s;">
                            <i class="fas {{ $mod['icon'] }}" style="font-size:0.75rem;color:{{ $isEnabled ? $accentColor : '#52525b' }};transition:color 0.2s;"></i>
                        </div>

                        {{-- Label --}}
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:0.8rem;font-weight:600;color:{{ $isEnabled ? '#fff' : '#71717a' }};transition:color 0.2s;">{{ $mod['label'] }}</div>
                            <div style="font-size:0.68rem;color:#52525b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $mod['desc'] }}</div>
                        </div>

                        {{-- Locked icon for top plan --}}
                        @if($isTopPlan)
                        <i class="fas fa-lock" style="font-size:0.65rem;color:{{ $accentColor }};flex-shrink:0;opacity:0.6;"></i>
                        @endif
                    </label>
                    @endforeach
                </div>

                {{-- Hidden inputs for top plan --}}
                @if($isTopPlan)
                @foreach($allModules as $modKey => $mod)
                <input type="hidden" name="{{ $planKey }}[]" value="{{ $modKey }}">
                @endforeach
                @endif

            </div>
            @endforeach
        </div>

        {{-- Module Reference Legend --}}
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header">
                <span class="sa-card-title"><i class="fas fa-info-circle" style="color:#7c3aed;margin-right:0.5rem;"></i>Module Reference</span>
                <span style="font-size:0.72rem;color:#52525b;">{{ count($allModules) }} modules available</span>
            </div>
            <div style="padding:1.25rem;display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:0.75rem;">
                @foreach($allModules as $modKey => $mod)
                <div style="display:flex;align-items:flex-start;gap:0.6rem;padding:0.6rem;border-radius:8px;background:rgba(255,255,255,0.02);border:1px solid #1a1a1a;">
                    <div style="width:28px;height:28px;border-radius:7px;background:rgba(124,58,237,0.1);border:1px solid rgba(124,58,237,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas {{ $mod['icon'] }}" style="font-size:0.7rem;color:#a855f7;"></i>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;font-weight:600;color:#e4e4e7;">{{ $mod['label'] }}</div>
                        <div style="font-size:0.65rem;color:#52525b;line-height:1.4;">{{ $mod['desc'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Save Button --}}
        <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
            <a href="{{ route('superadmin.subscriptions') }}" class="sa-action-btn" style="padding:0.6rem 1.25rem;">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="sa-btn-primary" style="padding:0.6rem 1.5rem;font-size:0.875rem;">
                <i class="fas fa-save"></i> Save Module Access
            </button>
        </div>

    </form>
</div>

<script>
function toggleModule(el) {
    const isChecked = el.dataset.checked === '1';
    const planKey   = el.dataset.plan;
    const modKey    = el.dataset.mod;
    const newState  = !isChecked;
    const accentMap = {
        starter: '#71717a',
        professional: '#7c3aed',
        business_pro: '#eab308',
    };
    const accent = accentMap[planKey] || '#7c3aed';

    // Update toggle visual
    el.style.background      = newState ? accent : '#222';
    el.style.borderColor     = newState ? accent : '#333';
    el.querySelector('div').style.left = newState ? '18px' : '2px';
    el.dataset.checked = newState ? '1' : '0';

    // Update hidden checkbox
    const cb = document.getElementById('mod_' + planKey + '_' + modKey);
    if (cb) cb.checked = newState;

    // Update icon and label colours
    const row   = el.closest('label');
    const icon  = row.querySelector('.fa-fw, [class*="fa-"]');
    const label = row.querySelectorAll('div')[3]?.querySelector('div:first-child');
    const iconWrap = row.querySelectorAll('div')[2];
    if (iconWrap) {
        iconWrap.style.background   = newState ? 'rgba(124,58,237,0.1)' : 'rgba(255,255,255,0.03)';
        iconWrap.style.borderColor  = newState ? accent + '33' : '#222';
    }
    if (icon) icon.style.color = newState ? accent : '#52525b';
    if (label) label.style.color = newState ? '#fff' : '#71717a';

    updateCounter(planKey);
}

function updateCounter(planKey) {
    const checked = document.querySelectorAll('input[name="' + planKey + '[]"]:checked').length;
    const total   = document.querySelectorAll('input[name="' + planKey + '[]"]').length;
    // Update progress bar
    const card = document.querySelector('[data-plan-key="' + planKey + '"]');
    if (card) {
        const bar = card.querySelector('.plan-progress-bar');
        if (bar) bar.style.width = Math.round((checked / total) * 100) + '%';
        const cnt = card.querySelector('.plan-count');
        if (cnt) cnt.textContent = checked + ' / ' + total;
    }
}
</script>
@endsection
