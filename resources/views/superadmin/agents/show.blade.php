@extends('layouts.superadmin')
@section('title', $agent->user->name)
@section('content')
<div class="sa-content">
    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('superadmin.agents.index') }}" style="color:#a78bfa;text-decoration:none;font-size:0.8rem;"><i class="fas fa-arrow-left"></i> Back to Agents</a>
    </div>

    @if(session('success'))<div style="background:#22c55e22;border:1px solid #22c55e;color:#86efac;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.85rem;">{{ session('success') }}</div>@endif
    @if(session('error'))<div style="background:#ef444422;border:1px solid #ef4444;color:#fca5a5;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.85rem;">{{ session('error') }}</div>@endif

    {{-- Header --}}
    <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:2rem;flex-wrap:wrap;">
        <div style="width:64px;height:64px;border-radius:50%;background:#7c3aed;display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:800;color:#fff;flex-shrink:0;">{{ strtoupper(substr($agent->user->name,0,1)) }}</div>
        <div style="flex:1;">
            <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0;">{{ $agent->user->name }}</h1>
            <div style="display:flex;gap:1rem;margin-top:0.25rem;flex-wrap:wrap;">
                <span style="font-size:0.8rem;color:#71717a;">{{ $agent->user->email }}</span>
                <span style="font-family:monospace;background:#7c3aed22;color:#a78bfa;padding:0.15rem 0.5rem;border-radius:4px;font-size:0.8rem;">{{ $agent->agent_code }}</span>
                @if($agent->company_name)<span style="font-size:0.8rem;color:#a1a1aa;">{{ $agent->company_name }}</span>@endif
            </div>
        </div>
        @if(auth()->user()->hasSaPermission('agents.edit'))
        <a href="{{ route('superadmin.agents.edit', $agent->id) }}" style="background:#27272a;color:#a1a1aa;padding:0.6rem 1.25rem;border-radius:8px;font-size:0.85rem;text-decoration:none;"><i class="fas fa-edit"></i> Edit</a>
        @endif
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;margin-bottom:2rem;">
        @foreach([
            ['Commission Rate', $agent->commission_rate.'%', '#22c55e', 'percent'],
            ['Total Earned', '₹'.number_format($totalEarned,2), '#7c3aed', 'coins'],
            ['Pending', '₹'.number_format($pendingCommission,2), '#f59e0b', 'clock'],
            ['Total Paid', '₹'.number_format($totalPaid,2), '#3b82f6', 'check-circle'],
            ['Active Subscribers', $activeSubscribers, '#ec4899', 'users'],
        ] as [$label,$val,$color,$icon])
        <div style="background:#111;border:1px solid #27272a;border-radius:12px;padding:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.75rem;">
                <div style="width:30px;height:30px;border-radius:6px;background:{{ $color }}22;display:flex;align-items:center;justify-content:center;color:{{ $color }};font-size:0.85rem;"><i class="fas fa-{{ $icon }}"></i></div>
                <span style="font-size:0.68rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;">{{ $label }}</span>
            </div>
            <div style="font-size:1.25rem;font-weight:800;color:#fff;">{{ $val }}</div>
        </div>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">

        {{-- Left: Allot Subscriptions + Allotment History --}}
        <div>
            @if(auth()->user()->hasSaPermission('agents.allot'))
            <div class="sa-card" style="margin-bottom:1.5rem;">
                <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-plus-circle" style="color:#22c55e;margin-right:0.5rem;"></i> Allot Subscriptions</span></div>
                <form method="POST" action="{{ route('superadmin.agents.allot', $agent->id) }}" style="padding:1.5rem;display:grid;gap:1rem;">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label class="sa-label">Plan</label>
                            <select name="plan" class="sa-input">
                                <option value="starter">Starter</option>
                                <option value="professional">Professional</option>
                                <option value="business">Business</option>
                            </select>
                        </div>
                        <div>
                            <label class="sa-label">Quantity</label>
                            <input type="number" name="quantity" value="10" min="1" max="10000" class="sa-input">
                        </div>
                    </div>
                    <div>
                        <label class="sa-label">Expires At (optional)</label>
                        <input type="date" name="expires_at" class="sa-input">
                    </div>
                    <div>
                        <label class="sa-label">Notes</label>
                        <input type="text" name="notes" class="sa-input" placeholder="Optional note">
                    </div>
                    <button type="submit" class="sa-btn-primary" style="width:100%;justify-content:center;">Allot Subscriptions</button>
                </form>
            </div>
            @endif

            {{-- Allotment History --}}
            <div class="sa-card">
                <div class="sa-card-header"><span class="sa-card-title">Allotment History</span></div>
                <table class="sa-table">
                    <thead><tr><th>Plan</th><th>Qty</th><th>Used</th><th>Expires</th><th>By</th></tr></thead>
                    <tbody>
                        @forelse($agent->allotments as $allot)
                        <tr>
                            <td style="text-transform:capitalize;font-size:0.82rem;">{{ $allot->plan }}</td>
                            <td style="font-size:0.82rem;">{{ $allot->quantity }}</td>
                            <td style="font-size:0.82rem;color:{{ $allot->used >= $allot->quantity ? '#ef4444' : '#22c55e' }};">{{ $allot->used }}</td>
                            <td style="font-size:0.75rem;color:#71717a;">{{ $allot->expires_at ? \Carbon\Carbon::parse($allot->expires_at)->format('d M Y') : '—' }}</td>
                            <td style="font-size:0.75rem;color:#71717a;">{{ $allot->assignedBy?->name ?? 'System' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;padding:2rem;color:#71717a;">No allotments yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right: Pay Commission + Recent Subscribers --}}
        <div>
            @if(auth()->user()->hasSaPermission('agents.commissions'))
            <div class="sa-card" style="margin-bottom:1.5rem;">
                <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-money-bill-wave" style="color:#22c55e;margin-right:0.5rem;"></i> Pay Commission</span></div>
                <form method="POST" action="{{ route('superadmin.agents.pay-commission', $agent->id) }}" style="padding:1.5rem;display:grid;gap:1rem;">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label class="sa-label">Amount (₹) *</label>
                            <input type="number" name="amount" value="{{ number_format($pendingCommission, 2, '.', '') }}" min="0.01" step="0.01" class="sa-input" required>
                        </div>
                        <div>
                            <label class="sa-label">Payment Method *</label>
                            <select name="payment_method" class="sa-input">
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="upi">UPI</option>
                                <option value="cheque">Cheque</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="sa-label">Reference / UTR Number</label>
                        <input type="text" name="reference_no" class="sa-input" placeholder="Transaction reference">
                    </div>
                    <div>
                        <label class="sa-label">Notes</label>
                        <input type="text" name="notes" class="sa-input" placeholder="Optional note">
                    </div>
                    <button type="submit" class="sa-btn-primary" style="width:100%;justify-content:center;background:#22c55e;">
                        <i class="fas fa-check"></i> Process Payout
                    </button>
                </form>
            </div>
            @endif

            {{-- Recent Subscribers --}}
            <div class="sa-card">
                <div class="sa-card-header"><span class="sa-card-title">Recent Subscribers</span></div>
                <table class="sa-table">
                    <thead><tr><th>Customer</th><th>Plan</th><th>Commission</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($recentSubscriptions as $sub)
                        <tr>
                            <td>
                                <div style="font-size:0.82rem;font-weight:600;color:#fff;">{{ $sub->customer?->name ?? 'Unknown' }}</div>
                                <div style="font-size:0.72rem;color:#71717a;">{{ $sub->starts_at }}</div>
                            </td>
                            <td style="font-size:0.8rem;text-transform:capitalize;">{{ $sub->plan }}</td>
                            <td style="font-size:0.82rem;color:#22c55e;font-weight:700;">₹{{ number_format($sub->commission_amount, 2) }}</td>
                            <td>
                                @php $cs=$sub->commission_status; $csC=['pending'=>'#f59e0b','approved'=>'#3b82f6','paid'=>'#22c55e','cancelled'=>'#ef4444']; @endphp
                                <span style="background:{{ $csC[$cs]??'#f59e0b' }}22;color:{{ $csC[$cs]??'#f59e0b' }};padding:0.15rem 0.5rem;border-radius:20px;font-size:0.7rem;font-weight:700;text-transform:capitalize;">{{ $cs }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:2rem;color:#71717a;">No subscribers yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
.sa-input{width:100%;background:#111;border:1px solid #27272a;color:#fff;padding:0.65rem 1rem;border-radius:8px;font-size:0.875rem;outline:none;box-sizing:border-box;}
.sa-input:focus{border-color:#7c3aed;}
.sa-label{display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;}
.sa-btn-primary{background:#7c3aed;color:#fff;border:none;padding:0.65rem 1.25rem;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;}
.sa-btn-primary:hover{background:#6d28d9;}
</style>
@endsection
