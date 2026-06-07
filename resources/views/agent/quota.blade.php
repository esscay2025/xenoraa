@extends('layouts.agent')
@section('title', 'My Quota')
@section('content')

<div style="margin-bottom:2rem;">
    <h2 style="font-size:1.35rem;font-weight:800;color:#fff;margin:0;">My Quota</h2>
    <p style="font-size:0.85rem;color:#71717a;margin-top:0.25rem;">Subscription allotments assigned to you by the administrator</p>
</div>

@if($allotments->isEmpty())
<div style="text-align:center;padding:4rem;background:#111;border:1px solid #27272a;border-radius:12px;">
    <i class="fas fa-ticket-alt" style="font-size:3rem;color:#27272a;margin-bottom:1rem;display:block;"></i>
    <h3 style="color:#fff;font-size:1.1rem;margin-bottom:0.5rem;">No Quota Assigned</h3>
    <p style="color:#71717a;font-size:0.875rem;">Contact your administrator to get subscription quota assigned.</p>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;">
    @foreach($allotments as $allot)
    @php
        $pct = $allot->quantity > 0 ? min(100, ($allot->used / $allot->quantity) * 100) : 0;
        $remaining = $allot->quantity - $allot->used;
        $planColors = ['starter'=>'#3b82f6','professional'=>'#8b5cf6','business'=>'#f59e0b'];
        $planColor = $planColors[$allot->plan] ?? '#3b82f6';
        $isExpired = $allot->isExpired();
        $isExhausted = $remaining <= 0;
    @endphp
    <div style="background:#111;border:1px solid {{ $isExpired || $isExhausted ? '#ef444433' : $planColor.'33' }};border-radius:16px;padding:1.5rem;position:relative;overflow:hidden;">
        {{-- Plan badge --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:{{ $planColor }}22;display:flex;align-items:center;justify-content:center;color:{{ $planColor }};font-size:1.1rem;border:1px solid {{ $planColor }}33;">
                    <i class="fas fa-{{ $allot->plan === 'starter' ? 'seedling' : ($allot->plan === 'professional' ? 'briefcase' : 'building') }}"></i>
                </div>
                <div>
                    <div style="font-size:1rem;font-weight:800;color:#fff;text-transform:capitalize;">{{ $allot->plan }}</div>
                    <div style="font-size:0.72rem;color:#71717a;">Plan</div>
                </div>
            </div>
            @if($isExpired)
            <span style="background:rgba(239,68,68,0.1);color:#ef4444;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.7rem;font-weight:700;border:1px solid rgba(239,68,68,0.2);">EXPIRED</span>
            @elseif($isExhausted)
            <span style="background:rgba(239,68,68,0.1);color:#ef4444;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.7rem;font-weight:700;border:1px solid rgba(239,68,68,0.2);">EXHAUSTED</span>
            @else
            <span style="background:rgba(34,197,94,0.1);color:#22c55e;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.7rem;font-weight:700;border:1px solid rgba(34,197,94,0.2);">ACTIVE</span>
            @endif
        </div>

        {{-- Progress --}}
        <div style="margin-bottom:1rem;">
            <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
                <span style="font-size:0.8rem;color:#a1a1aa;">Usage</span>
                <span style="font-size:0.8rem;font-weight:700;color:{{ $isExhausted ? '#ef4444' : '#fff' }};">{{ $allot->used }} / {{ $allot->quantity }}</span>
            </div>
            <div style="height:8px;background:#27272a;border-radius:4px;overflow:hidden;">
                <div style="height:8px;background:{{ $isExhausted ? '#ef4444' : $planColor }};border-radius:4px;width:{{ $pct }}%;transition:width 0.5s;"></div>
            </div>
        </div>

        {{-- Stats --}}
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.75rem;margin-bottom:1rem;">
            <div style="background:#0d0d0d;border-radius:8px;padding:0.75rem;text-align:center;">
                <div style="font-size:1.25rem;font-weight:800;color:#fff;">{{ $allot->quantity }}</div>
                <div style="font-size:0.65rem;color:#71717a;text-transform:uppercase;letter-spacing:0.05em;">Total</div>
            </div>
            <div style="background:#0d0d0d;border-radius:8px;padding:0.75rem;text-align:center;">
                <div style="font-size:1.25rem;font-weight:800;color:#a1a1aa;">{{ $allot->used }}</div>
                <div style="font-size:0.65rem;color:#71717a;text-transform:uppercase;letter-spacing:0.05em;">Used</div>
            </div>
            <div style="background:#0d0d0d;border-radius:8px;padding:0.75rem;text-align:center;">
                <div style="font-size:1.25rem;font-weight:800;color:{{ $remaining > 0 ? '#22c55e' : '#ef4444' }};">{{ $remaining }}</div>
                <div style="font-size:0.65rem;color:#71717a;text-transform:uppercase;letter-spacing:0.05em;">Left</div>
            </div>
        </div>

        {{-- Metadata --}}
        <div style="border-top:1px solid #27272a;padding-top:0.875rem;display:flex;justify-content:space-between;align-items:center;">
            <div style="font-size:0.75rem;color:#71717a;">
                <i class="fas fa-calendar-plus" style="margin-right:0.3rem;"></i>
                Assigned {{ \Carbon\Carbon::parse($allot->created_at)->format('d M Y') }}
            </div>
            @if($allot->expires_at)
            <div style="font-size:0.75rem;color:{{ $isExpired ? '#ef4444' : '#71717a' }};">
                <i class="fas fa-calendar-times" style="margin-right:0.3rem;"></i>
                {{ $isExpired ? 'Expired' : 'Expires' }} {{ \Carbon\Carbon::parse($allot->expires_at)->format('d M Y') }}
            </div>
            @else
            <div style="font-size:0.75rem;color:#52525b;"><i class="fas fa-infinity" style="margin-right:0.3rem;"></i> No expiry</div>
            @endif
        </div>
        @if($allot->notes)
        <div style="margin-top:0.75rem;background:#0d0d0d;border-radius:8px;padding:0.75rem;font-size:0.78rem;color:#71717a;">
            <i class="fas fa-sticky-note" style="margin-right:0.3rem;"></i>{{ $allot->notes }}
        </div>
        @endif
    </div>
    @endforeach
</div>
@endif

@endsection
