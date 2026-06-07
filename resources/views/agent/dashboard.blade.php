@extends('layouts.superadmin')
@section('title', 'Agent Dashboard')
@section('content')
<div class="sa-content">
    {{-- Header --}}
    <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:2rem;flex-wrap:wrap;">
        <div style="width:64px;height:64px;border-radius:50%;background:#22c55e;display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:800;color:#fff;flex-shrink:0;">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0;">Welcome, {{ auth()->user()->name }}</h1>
            <div style="display:flex;gap:1rem;margin-top:0.25rem;flex-wrap:wrap;">
                <span style="font-size:0.8rem;color:#71717a;">Agent Dashboard</span>
                <span style="font-family:monospace;background:#22c55e22;color:#22c55e;padding:0.15rem 0.5rem;border-radius:4px;font-size:0.8rem;">{{ $agent->agent_code }}</span>
                @if($agent->company_name)<span style="font-size:0.8rem;color:#a1a1aa;">{{ $agent->company_name }}</span>@endif
            </div>
        </div>
        <div style="margin-left:auto;">
            <a href="{{ route('agent.create-customer') }}" class="sa-btn-primary" style="background:#22c55e;">
                <i class="fas fa-plus"></i> Create Customer
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:2rem;">
        @foreach([
            ['Total Subscribers', $stats['total_subscribers'], '#7c3aed', 'users'],
            ['Active Subscribers', $stats['active_subscribers'], '#22c55e', 'user-check'],
            ['Available Quota', $stats['available_quota'], '#3b82f6', 'ticket-alt'],
        ] as [$label,$val,$color,$icon])
        <div style="background:#111;border:1px solid #27272a;border-radius:12px;padding:1.5rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:{{ $color }}22;display:flex;align-items:center;justify-content:center;color:{{ $color }};font-size:1.1rem;"><i class="fas fa-{{ $icon }}"></i></div>
                <span style="font-size:0.72rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;">{{ $label }}</span>
            </div>
            <div style="font-size:2rem;font-weight:800;color:#fff;">{{ $val }}</div>
        </div>
        @endforeach
    </div>

    {{-- Commission Cards --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:2rem;">
        @foreach([
            ['Total Earned', '₹'.number_format($stats['total_earned'],2), '#22c55e', 'coins', 'All-time commission earned'],
            ['Pending', '₹'.number_format($stats['pending_commission'],2), '#f59e0b', 'clock', 'Awaiting approval & payment'],
            ['Total Paid', '₹'.number_format($stats['total_paid'],2), '#3b82f6', 'check-circle', 'Commission already paid out'],
        ] as [$label,$val,$color,$icon,$sub])
        <div style="background:#111;border:1px solid {{ $color }}44;border-radius:12px;padding:1.5rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:{{ $color }}22;display:flex;align-items:center;justify-content:center;color:{{ $color }};font-size:1.1rem;"><i class="fas fa-{{ $icon }}"></i></div>
                <div>
                    <div style="font-size:0.72rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;">{{ $label }}</div>
                    <div style="font-size:0.7rem;color:#52525b;">{{ $sub }}</div>
                </div>
            </div>
            <div style="font-size:1.75rem;font-weight:800;color:{{ $color }};">{{ $val }}</div>
        </div>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;">

        {{-- Subscribers Table --}}
        <div class="sa-card">
            <div class="sa-card-header">
                <span class="sa-card-title"><i class="fas fa-users" style="color:#7c3aed;margin-right:0.5rem;"></i> My Subscribers</span>
                <span style="font-size:0.75rem;color:#71717a;">{{ $subscribers->total() }} total</span>
            </div>
            <table class="sa-table">
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
                            @php $planC=['starter'=>'#3b82f6','professional'=>'#8b5cf6','business'=>'#f59e0b']; @endphp
                            <span style="background:{{ $planC[$sub->plan]??'#3b82f6' }}22;color:{{ $planC[$sub->plan]??'#3b82f6' }};padding:0.2rem 0.5rem;border-radius:20px;font-size:0.72rem;font-weight:700;text-transform:capitalize;">{{ $sub->plan }}</span>
                        </td>
                        <td style="font-size:0.8rem;color:#a1a1aa;">{{ $sub->duration_months }}mo</td>
                        <td style="font-size:0.85rem;font-weight:700;color:#22c55e;">₹{{ number_format($sub->commission_amount,2) }}</td>
                        <td>
                            @php $cs=$sub->commission_status; $csC=['pending'=>'#f59e0b','approved'=>'#3b82f6','paid'=>'#22c55e','cancelled'=>'#ef4444']; @endphp
                            <span style="background:{{ $csC[$cs]??'#f59e0b' }}22;color:{{ $csC[$cs]??'#f59e0b' }};padding:0.2rem 0.5rem;border-radius:20px;font-size:0.7rem;font-weight:700;text-transform:capitalize;">{{ $cs }}</span>
                        </td>
                        <td style="font-size:0.75rem;color:#71717a;">{{ $sub->starts_at }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:3rem;color:#71717a;">No subscribers yet. <a href="{{ route('agent.create-customer') }}" style="color:#a78bfa;">Create your first customer →</a></td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($subscribers->hasPages())
            <div style="padding:1rem 1.5rem;border-top:1px solid #27272a;">{{ $subscribers->links() }}</div>
            @endif
        </div>

        {{-- Right: Allotments + Payout History --}}
        <div>
            {{-- Allotments --}}
            <div class="sa-card" style="margin-bottom:1.5rem;">
                <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-ticket-alt" style="color:#3b82f6;margin-right:0.5rem;"></i> My Quota</span></div>
                <div style="padding:1.25rem;">
                    @forelse($allotments as $allot)
                    <div style="background:#111;border:1px solid #27272a;border-radius:10px;padding:1rem;margin-bottom:0.75rem;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
                            <span style="font-size:0.8rem;font-weight:700;color:#fff;text-transform:capitalize;">{{ $allot->plan }}</span>
                            <span style="font-size:0.75rem;color:{{ $allot->used >= $allot->quantity ? '#ef4444' : '#22c55e' }};">{{ $allot->quantity - $allot->used }} left</span>
                        </div>
                        <div style="height:6px;background:#27272a;border-radius:3px;">
                            @if($allot->quantity > 0)
                            <div style="height:6px;background:#7c3aed;border-radius:3px;width:{{ min(100, ($allot->used / $allot->quantity) * 100) }}%;"></div>
                            @endif
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-top:0.4rem;">
                            <span style="font-size:0.7rem;color:#71717a;">{{ $allot->used }} used / {{ $allot->quantity }} total</span>
                            @if($allot->expires_at)<span style="font-size:0.7rem;color:#71717a;">Exp: {{ \Carbon\Carbon::parse($allot->expires_at)->format('d M Y') }}</span>@endif
                        </div>
                    </div>
                    @empty
                    <div style="text-align:center;padding:1.5rem;color:#71717a;font-size:0.85rem;">No quota assigned yet. Contact your administrator.</div>
                    @endforelse
                </div>
            </div>

            {{-- Payout History --}}
            <div class="sa-card">
                <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-history" style="color:#22c55e;margin-right:0.5rem;"></i> Payout History</span></div>
                <div style="padding:0.5rem;">
                    @forelse($payouts as $payout)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:0.875rem 1rem;border-bottom:1px solid #27272a;">
                        <div>
                            <div style="font-size:0.82rem;font-weight:700;color:#22c55e;">₹{{ number_format($payout->amount,2) }}</div>
                            <div style="font-size:0.72rem;color:#71717a;">{{ $payout->payment_method }} · {{ $payout->paid_at }}</div>
                            @if($payout->reference_no)<div style="font-size:0.7rem;color:#52525b;">Ref: {{ $payout->reference_no }}</div>@endif
                        </div>
                        <i class="fas fa-check-circle" style="color:#22c55e;font-size:1.1rem;"></i>
                    </div>
                    @empty
                    <div style="text-align:center;padding:1.5rem;color:#71717a;font-size:0.85rem;">No payouts yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.sa-btn-primary{background:#7c3aed;color:#fff;border:none;padding:0.65rem 1.25rem;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;}
.sa-btn-primary:hover{background:#6d28d9;}
</style>
@endsection
