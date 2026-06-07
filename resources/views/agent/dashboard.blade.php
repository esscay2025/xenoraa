@extends('layouts.agent')
@section('title', 'Agent Dashboard')
@section('content')

{{-- Header --}}
<div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:2rem;flex-wrap:wrap;">
    <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#22c55e,#16a34a);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:800;color:#fff;flex-shrink:0;box-shadow:0 0 24px rgba(34,197,94,0.3);">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0;">Welcome back, {{ auth()->user()->name }}</h1>
        <div style="display:flex;gap:0.75rem;margin-top:0.4rem;flex-wrap:wrap;align-items:center;">
            <span style="font-family:monospace;background:rgba(34,197,94,0.15);color:#22c55e;padding:0.2rem 0.6rem;border-radius:6px;font-size:0.8rem;font-weight:700;border:1px solid rgba(34,197,94,0.25);">{{ $agent->agent_code }}</span>
            @if($agent->company_name)<span style="font-size:0.8rem;color:#a1a1aa;"><i class="fas fa-building" style="margin-right:0.3rem;"></i>{{ $agent->company_name }}</span>@endif
            @if($agent->city)<span style="font-size:0.8rem;color:#71717a;"><i class="fas fa-map-marker-alt" style="margin-right:0.3rem;"></i>{{ $agent->city }}, {{ $agent->state }}</span>@endif
            <span style="background:rgba(34,197,94,0.1);color:#22c55e;padding:0.15rem 0.5rem;border-radius:20px;font-size:0.7rem;font-weight:700;border:1px solid rgba(34,197,94,0.2);text-transform:uppercase;">{{ $agent->status }}</span>
        </div>
    </div>
    <div style="margin-left:auto;display:flex;gap:0.75rem;">
        <a href="{{ route('agent.create-customer') }}" class="ag-btn-primary">
            <i class="fas fa-user-plus"></i> Create Customer
        </a>
    </div>
</div>

{{-- Commission Summary Banner --}}
<div style="background:linear-gradient(135deg,rgba(34,197,94,0.08),rgba(34,197,94,0.03));border:1px solid rgba(34,197,94,0.15);border-radius:16px;padding:1.5rem 2rem;margin-bottom:2rem;display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;">
    @foreach([
        ['Total Earned', '₹'.number_format($stats['total_earned'],2), '#22c55e', 'coins', 'All-time commission'],
        ['Pending', '₹'.number_format($stats['pending_commission'],2), '#f59e0b', 'clock', 'Awaiting payment'],
        ['Total Paid', '₹'.number_format($stats['total_paid'],2), '#3b82f6', 'check-circle', 'Already paid out'],
        ['Commission Rate', $agent->commission_rate.'%', '#a855f7', 'percentage', 'Per subscription'],
    ] as [$label,$val,$color,$icon,$sub])
    <div style="display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:{{ $color }}22;display:flex;align-items:center;justify-content:center;color:{{ $color }};font-size:1.2rem;flex-shrink:0;border:1px solid {{ $color }}33;">
            <i class="fas fa-{{ $icon }}"></i>
        </div>
        <div>
            <div style="font-size:0.7rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.2rem;">{{ $label }}</div>
            <div style="font-size:1.35rem;font-weight:800;color:{{ $color }};">{{ $val }}</div>
            <div style="font-size:0.7rem;color:#52525b;">{{ $sub }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Subscriber Stats --}}
<div class="ag-stat-grid" style="grid-template-columns:repeat(3,1fr);">
    @foreach([
        ['Total Subscribers', $stats['total_subscribers'], '#7c3aed', 'users', 'All customers created'],
        ['Active Subscribers', $stats['active_subscribers'], '#22c55e', 'user-check', 'Currently active'],
        ['Available Quota', $stats['available_quota'], '#3b82f6', 'ticket-alt', 'Subscriptions left to sell'],
    ] as [$label,$val,$color,$icon,$sub])
    <div class="ag-stat-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <div style="font-size:0.72rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">{{ $label }}</div>
                <div style="font-size:2.25rem;font-weight:800;color:#fff;line-height:1;">{{ $val }}</div>
                <div style="font-size:0.72rem;color:#52525b;margin-top:0.4rem;">{{ $sub }}</div>
            </div>
            <div style="width:44px;height:44px;border-radius:12px;background:{{ $color }}22;display:flex;align-items:center;justify-content:center;color:{{ $color }};font-size:1.1rem;border:1px solid {{ $color }}33;">
                <i class="fas fa-{{ $icon }}"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Main Content Grid --}}
<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;">

    {{-- Subscribers Table --}}
    <div class="ag-card">
        <div class="ag-card-header">
            <span class="ag-card-title"><i class="fas fa-users" style="color:#22c55e;margin-right:0.5rem;"></i> My Customers</span>
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <span style="font-size:0.75rem;color:#71717a;">{{ $subscribers->total() }} total</span>
                <a href="{{ route('agent.my-customers') }}" style="font-size:0.75rem;color:#22c55e;text-decoration:none;">View all →</a>
            </div>
        </div>
        <table class="ag-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Plan</th>
                    <th>Duration</th>
                    <th>Commission</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $sub)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#fff;font-size:0.85rem;">{{ $sub->customer?->name ?? 'Unknown' }}</div>
                        <div style="font-size:0.72rem;color:#71717a;">{{ $sub->customer?->email }}</div>
                    </td>
                    <td>
                        <span class="ag-badge ag-badge-{{ $sub->plan }}" style="text-transform:capitalize;">{{ $sub->plan }}</span>
                    </td>
                    <td style="font-size:0.8rem;color:#a1a1aa;">{{ $sub->duration_months }}mo</td>
                    <td>
                        <div style="font-size:0.85rem;font-weight:700;color:#22c55e;">₹{{ number_format($sub->commission_amount,2) }}</div>
                        <div style="font-size:0.7rem;color:#71717a;">{{ $sub->commission_rate }}%</div>
                    </td>
                    <td>
                        @php $cs=$sub->commission_status; @endphp
                        <span class="ag-badge ag-badge-{{ $cs }}" style="text-transform:capitalize;">{{ $cs }}</span>
                    </td>
                    <td style="font-size:0.75rem;color:#71717a;">{{ \Carbon\Carbon::parse($sub->starts_at)->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:3rem;color:#71717a;">
                        <i class="fas fa-users" style="font-size:2rem;margin-bottom:0.75rem;display:block;color:#27272a;"></i>
                        No customers yet.<br>
                        <a href="{{ route('agent.create-customer') }}" style="color:#22c55e;text-decoration:none;font-weight:600;">Create your first customer →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($subscribers->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #27272a;">{{ $subscribers->links() }}</div>
        @endif
    </div>

    {{-- Right Column --}}
    <div>
        {{-- Quota Cards --}}
        <div class="ag-card" style="margin-bottom:1.5rem;">
            <div class="ag-card-header">
                <span class="ag-card-title"><i class="fas fa-ticket-alt" style="color:#3b82f6;margin-right:0.5rem;"></i> My Quota</span>
                <a href="{{ route('agent.quota') }}" style="font-size:0.75rem;color:#3b82f6;text-decoration:none;">Details →</a>
            </div>
            <div style="padding:1.25rem;">
                @forelse($allotments as $allot)
                @php
                    $pct = $allot->quantity > 0 ? min(100, ($allot->used / $allot->quantity) * 100) : 0;
                    $remaining = $allot->quantity - $allot->used;
                    $planColors = ['starter'=>'#3b82f6','professional'=>'#8b5cf6','business'=>'#f59e0b'];
                    $planColor = $planColors[$allot->plan] ?? '#3b82f6';
                @endphp
                <div style="background:#0d0d0d;border:1px solid #27272a;border-radius:10px;padding:1rem;margin-bottom:0.75rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <span style="width:8px;height:8px;border-radius:50%;background:{{ $planColor }};display:inline-block;"></span>
                            <span style="font-size:0.82rem;font-weight:700;color:#fff;text-transform:capitalize;">{{ $allot->plan }}</span>
                        </div>
                        <span style="font-size:0.8rem;font-weight:700;color:{{ $remaining <= 0 ? '#ef4444' : '#22c55e' }};">
                            {{ $remaining }} left
                        </span>
                    </div>
                    <div style="height:6px;background:#27272a;border-radius:3px;overflow:hidden;">
                        <div style="height:6px;background:{{ $planColor }};border-radius:3px;width:{{ $pct }}%;transition:width 0.5s;"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-top:0.5rem;">
                        <span style="font-size:0.7rem;color:#71717a;">{{ $allot->used }} used / {{ $allot->quantity }} total</span>
                        @if($allot->expires_at)
                        <span style="font-size:0.7rem;color:{{ $allot->isExpired() ? '#ef4444' : '#71717a' }};">
                            {{ $allot->isExpired() ? 'Expired' : 'Exp: '.\Carbon\Carbon::parse($allot->expires_at)->format('d M Y') }}
                        </span>
                        @endif
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:2rem;color:#71717a;font-size:0.85rem;">
                    <i class="fas fa-ticket-alt" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;color:#27272a;"></i>
                    No quota assigned yet.<br>Contact your administrator.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Payouts --}}
        <div class="ag-card">
            <div class="ag-card-header">
                <span class="ag-card-title"><i class="fas fa-wallet" style="color:#22c55e;margin-right:0.5rem;"></i> Recent Payouts</span>
                <a href="{{ route('agent.payouts') }}" style="font-size:0.75rem;color:#22c55e;text-decoration:none;">All →</a>
            </div>
            <div>
                @forelse($payouts as $payout)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.875rem 1.25rem;border-bottom:1px solid #1a1a1a;">
                    <div>
                        <div style="font-size:0.9rem;font-weight:700;color:#22c55e;">₹{{ number_format($payout->amount,2) }}</div>
                        <div style="font-size:0.72rem;color:#71717a;margin-top:0.15rem;">
                            {{ ucfirst($payout->payment_method) }}
                            @if($payout->paid_at) · {{ \Carbon\Carbon::parse($payout->paid_at)->format('d M Y') }} @endif
                        </div>
                        @if($payout->reference_no)
                        <div style="font-size:0.7rem;color:#52525b;">Ref: {{ $payout->reference_no }}</div>
                        @endif
                    </div>
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(34,197,94,0.1);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-check" style="color:#22c55e;font-size:0.8rem;"></i>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:2rem;color:#71717a;font-size:0.85rem;">
                    <i class="fas fa-wallet" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;color:#27272a;"></i>
                    No payouts yet.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
