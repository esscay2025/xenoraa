@extends('layouts.agent')
@section('title', 'My Profile')
@section('content')

<div style="margin-bottom:2rem;">
    <h2 style="font-size:1.35rem;font-weight:800;color:#fff;margin:0;">My Profile</h2>
    <p style="font-size:0.85rem;color:#71717a;margin-top:0.25rem;">Your agent profile and bank details</p>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">

    {{-- Profile Card --}}
    <div class="ag-card">
        <div class="ag-card-header">
            <span class="ag-card-title"><i class="fas fa-user-circle" style="color:#22c55e;margin-right:0.5rem;"></i> Agent Information</span>
        </div>
        <div style="padding:1.5rem;">
            <div style="display:flex;align-items:center;gap:1.25rem;margin-bottom:2rem;padding-bottom:1.5rem;border-bottom:1px solid #27272a;">
                <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#22c55e,#16a34a);display:flex;align-items:center;justify-content:center;font-size:1.75rem;font-weight:800;color:#fff;flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                </div>
                <div>
                    <div style="font-size:1.1rem;font-weight:800;color:#fff;">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.82rem;color:#71717a;margin-top:0.2rem;">{{ auth()->user()->email }}</div>
                    <div style="display:flex;gap:0.5rem;margin-top:0.5rem;flex-wrap:wrap;">
                        <span style="font-family:monospace;background:rgba(34,197,94,0.15);color:#22c55e;padding:0.2rem 0.6rem;border-radius:6px;font-size:0.78rem;font-weight:700;border:1px solid rgba(34,197,94,0.25);">{{ $agent->agent_code }}</span>
                        <span style="background:rgba(34,197,94,0.1);color:#22c55e;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.7rem;font-weight:700;border:1px solid rgba(34,197,94,0.2);text-transform:uppercase;">{{ $agent->status }}</span>
                    </div>
                </div>
            </div>

            @php
                $fields = [
                    ['Company', $agent->company_name, 'building'],
                    ['Phone', $agent->phone, 'phone'],
                    ['City', $agent->city, 'map-marker-alt'],
                    ['State', $agent->state, 'map'],
                    ['Country', $agent->country, 'globe'],
                    ['Commission Rate', $agent->commission_rate.'%', 'percentage'],
                    ['Subscription Quota', $agent->subscription_quota, 'ticket-alt'],
                    ['Subscriptions Used', $agent->subscriptions_used, 'check-circle'],
                ];
            @endphp
            @foreach($fields as [$label, $value, $icon])
            <div style="display:flex;align-items:center;gap:1rem;padding:0.75rem 0;border-bottom:1px solid #1a1a1a;">
                <div style="width:32px;height:32px;border-radius:8px;background:#1a1a1a;display:flex;align-items:center;justify-content:center;color:#71717a;font-size:0.75rem;flex-shrink:0;">
                    <i class="fas fa-{{ $icon }}"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.7rem;color:#71717a;text-transform:uppercase;letter-spacing:0.06em;">{{ $label }}</div>
                    <div style="font-size:0.875rem;color:{{ $value ? '#fff' : '#52525b' }};font-weight:{{ $value ? '500' : '400' }};">{{ $value ?: '—' }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Bank Details --}}
    <div>
        <div class="ag-card" style="margin-bottom:1.5rem;">
            <div class="ag-card-header">
                <span class="ag-card-title"><i class="fas fa-university" style="color:#3b82f6;margin-right:0.5rem;"></i> Bank Details</span>
            </div>
            <div style="padding:1.5rem;">
                @php
                    $bankFields = [
                        ['Bank Name', $agent->bank_name, 'university'],
                        ['Account Number', $agent->bank_account_no ? str_repeat('*',strlen($agent->bank_account_no)-4).substr($agent->bank_account_no,-4) : null, 'credit-card'],
                        ['IFSC Code', $agent->bank_ifsc, 'code'],
                        ['PAN Number', $agent->pan_number, 'id-card'],
                        ['GST Number', $agent->gst_number, 'file-invoice'],
                    ];
                @endphp
                @foreach($bankFields as [$label, $value, $icon])
                <div style="display:flex;align-items:center;gap:1rem;padding:0.75rem 0;border-bottom:1px solid #1a1a1a;">
                    <div style="width:32px;height:32px;border-radius:8px;background:#1a1a1a;display:flex;align-items:center;justify-content:center;color:#71717a;font-size:0.75rem;flex-shrink:0;">
                        <i class="fas fa-{{ $icon }}"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:0.7rem;color:#71717a;text-transform:uppercase;letter-spacing:0.06em;">{{ $label }}</div>
                        <div style="font-size:0.875rem;color:{{ $value ? '#fff' : '#52525b' }};font-family:{{ in_array($label,['Account Number','IFSC Code','PAN Number','GST Number']) ? 'monospace' : 'inherit' }};">{{ $value ?: '—' }}</div>
                    </div>
                </div>
                @endforeach
                <div style="margin-top:1rem;background:rgba(59,130,246,0.05);border:1px solid rgba(59,130,246,0.15);border-radius:8px;padding:0.875rem;font-size:0.8rem;color:#71717a;">
                    <i class="fas fa-info-circle" style="color:#3b82f6;margin-right:0.4rem;"></i>
                    To update your bank details, please contact your Xenoraa administrator.
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($agent->notes)
        <div class="ag-card">
            <div class="ag-card-header">
                <span class="ag-card-title"><i class="fas fa-sticky-note" style="color:#f59e0b;margin-right:0.5rem;"></i> Notes from Admin</span>
            </div>
            <div style="padding:1.25rem;font-size:0.875rem;color:#a1a1aa;line-height:1.6;">{{ $agent->notes }}</div>
        </div>
        @endif
    </div>
</div>

@endsection
