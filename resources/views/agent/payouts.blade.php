@extends('layouts.agent')
@section('title', 'Payout History')
@section('content')

<div style="margin-bottom:2rem;">
    <h2 style="font-size:1.35rem;font-weight:800;color:#fff;margin:0;">Payout History</h2>
    <p style="font-size:0.85rem;color:#71717a;margin-top:0.25rem;">All commission payouts received from Xenoraa</p>
</div>

{{-- Summary --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:2rem;">
    <div style="background:#111;border:1px solid rgba(34,197,94,0.2);border-radius:12px;padding:1.25rem;">
        <div style="font-size:0.7rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">Total Paid Out</div>
        <div style="font-size:1.75rem;font-weight:800;color:#22c55e;">₹{{ number_format($stats['total_paid'],2) }}</div>
    </div>
    <div style="background:#111;border:1px solid rgba(245,158,11,0.2);border-radius:12px;padding:1.25rem;">
        <div style="font-size:0.7rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">Pending Commission</div>
        <div style="font-size:1.75rem;font-weight:800;color:#f59e0b;">₹{{ number_format($stats['pending_commission'],2) }}</div>
    </div>
    <div style="background:#111;border:1px solid rgba(59,130,246,0.2);border-radius:12px;padding:1.25rem;">
        <div style="font-size:0.7rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">Total Payouts</div>
        <div style="font-size:1.75rem;font-weight:800;color:#3b82f6;">{{ $payouts->total() }}</div>
    </div>
</div>

<div class="ag-card">
    <div class="ag-card-header">
        <span class="ag-card-title"><i class="fas fa-wallet" style="color:#22c55e;margin-right:0.5rem;"></i> Payout Records</span>
    </div>
    @if($payouts->isEmpty())
    <div style="text-align:center;padding:4rem;color:#71717a;">
        <i class="fas fa-wallet" style="font-size:2.5rem;display:block;margin-bottom:1rem;color:#27272a;"></i>
        No payouts yet. Commission will be paid once approved by the administrator.
    </div>
    @else
    <table class="ag-table">
        <thead>
            <tr>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Reference No</th>
                <th>Processed By</th>
                <th>Paid Date</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payouts as $payout)
            <tr>
                <td style="font-size:1rem;font-weight:800;color:#22c55e;">₹{{ number_format($payout->amount,2) }}</td>
                <td>
                    <span style="background:rgba(34,197,94,0.1);color:#22c55e;padding:0.2rem 0.6rem;border-radius:6px;font-size:0.75rem;font-weight:600;text-transform:capitalize;">
                        {{ str_replace('_',' ', $payout->payment_method) }}
                    </span>
                </td>
                <td style="font-size:0.82rem;color:#a1a1aa;font-family:monospace;">{{ $payout->reference_no ?? '—' }}</td>
                <td style="font-size:0.82rem;color:#a1a1aa;">{{ $payout->processedBy?->name ?? 'Admin' }}</td>
                <td style="font-size:0.82rem;color:#71717a;">{{ $payout->paid_at ? \Carbon\Carbon::parse($payout->paid_at)->format('d M Y') : '—' }}</td>
                <td style="font-size:0.78rem;color:#71717a;">{{ $payout->notes ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($payouts->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid #27272a;">{{ $payouts->links() }}</div>
    @endif
    @endif
</div>

@endsection
