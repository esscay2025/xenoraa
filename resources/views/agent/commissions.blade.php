@extends('layouts.agent')
@section('title', 'Commission')
@section('content')

<div style="margin-bottom:2rem;">
    <h2 style="font-size:1.35rem;font-weight:800;color:#fff;margin:0;">Commission Earnings</h2>
    <p style="font-size:0.85rem;color:#71717a;margin-top:0.25rem;">Track your commission earnings per subscription</p>
</div>

{{-- Summary --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem;">
    @foreach([
        ['Total Earned', '₹'.number_format($stats['total_earned'],2), '#22c55e', 'coins'],
        ['Pending', '₹'.number_format($stats['pending_commission'],2), '#f59e0b', 'clock'],
        ['Total Paid', '₹'.number_format($stats['total_paid'],2), '#3b82f6', 'check-circle'],
        ['Rate', $agent->commission_rate.'%', '#a855f7', 'percentage'],
    ] as [$label,$val,$color,$icon])
    <div style="background:#111;border:1px solid {{ $color }}33;border-radius:12px;padding:1.25rem;">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
            <div style="width:36px;height:36px;border-radius:10px;background:{{ $color }}22;display:flex;align-items:center;justify-content:center;color:{{ $color }};font-size:0.9rem;">
                <i class="fas fa-{{ $icon }}"></i>
            </div>
            <span style="font-size:0.7rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;">{{ $label }}</span>
        </div>
        <div style="font-size:1.6rem;font-weight:800;color:{{ $color }};">{{ $val }}</div>
    </div>
    @endforeach
</div>

{{-- Commission Table --}}
<div class="ag-card">
    <div class="ag-card-header">
        <span class="ag-card-title"><i class="fas fa-coins" style="color:#22c55e;margin-right:0.5rem;"></i> Commission Details</span>
    </div>
    <table class="ag-table">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Plan</th>
                <th>Plan Price</th>
                <th>Rate</th>
                <th>Commission</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($commissions as $sub)
            <tr>
                <td>
                    <div style="font-weight:600;color:#fff;font-size:0.85rem;">{{ $sub->customer?->name ?? 'Unknown' }}</div>
                    <div style="font-size:0.72rem;color:#71717a;">{{ $sub->customer?->email }}</div>
                </td>
                <td><span class="ag-badge ag-badge-{{ $sub->plan }}" style="text-transform:capitalize;">{{ $sub->plan }}</span></td>
                <td style="font-size:0.85rem;color:#a1a1aa;">₹{{ number_format($sub->plan_price,2) }}</td>
                <td style="font-size:0.85rem;color:#a1a1aa;">{{ $sub->commission_rate }}%</td>
                <td style="font-size:0.9rem;font-weight:700;color:#22c55e;">₹{{ number_format($sub->commission_amount,2) }}</td>
                <td>
                    @php
                        $cs = $sub->commission_status;
                        $csColors = ['pending'=>'#f59e0b','approved'=>'#3b82f6','paid'=>'#22c55e','cancelled'=>'#ef4444'];
                        $csColor = $csColors[$cs] ?? '#71717a';
                    @endphp
                    <span style="background:{{ $csColor }}22;color:{{ $csColor }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.7rem;font-weight:700;border:1px solid {{ $csColor }}33;text-transform:capitalize;">{{ $cs }}</span>
                </td>
                <td style="font-size:0.75rem;color:#71717a;">{{ \Carbon\Carbon::parse($sub->starts_at)->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:3rem;color:#71717a;">No commission records yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($commissions->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid #27272a;">{{ $commissions->links() }}</div>
    @endif
</div>

@endsection
