@extends('layouts.superadmin')
@section('title', 'Agents')
@section('content')
<div class="sa-content">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0;">Agents</h1>
            <p style="font-size:0.8rem;color:#71717a;margin:0.25rem 0 0;">Dealers who sell Xenoraa subscriptions and earn commissions</p>
        </div>
        @if(auth()->user()->hasSaPermission('agents.create'))
        <a href="{{ route('superadmin.agents.create') }}" class="sa-btn-primary"><i class="fas fa-plus"></i> Add Agent</a>
        @endif
    </div>

    {{-- Summary Stats --}}
    @php
        $totalAgents  = \App\Models\Agent::count();
        $activeAgents = \App\Models\Agent::where('status','active')->count();
        $totalSubs    = \App\Models\AgentAssignedSubscription::count();
        $pendingComm  = \App\Models\AgentAssignedSubscription::where('commission_status','pending')->sum('commission_amount');
    @endphp
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem;">
        @foreach([['Total Agents',$totalAgents,'#7c3aed','users'],['Active Agents',$activeAgents,'#22c55e','user-check'],['Total Subscriptions Sold',$totalSubs,'#3b82f6','credit-card'],['Pending Commission','₹'.number_format($pendingComm,2),'#f59e0b','coins']] as [$label,$val,$color,$icon])
        <div style="background:#111;border:1px solid #27272a;border-radius:12px;padding:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
                <div style="width:36px;height:36px;border-radius:8px;background:{{ $color }}22;display:flex;align-items:center;justify-content:center;color:{{ $color }};font-size:1rem;"><i class="fas fa-{{ $icon }}"></i></div>
                <span style="font-size:0.72rem;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;">{{ $label }}</span>
            </div>
            <div style="font-size:1.5rem;font-weight:800;color:#fff;">{{ $val }}</div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:1.5rem;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search agent name or email…" style="flex:1;min-width:200px;background:#1a1a1a;border:1px solid #27272a;color:#fff;padding:0.6rem 1rem;border-radius:8px;font-size:0.85rem;">
        <select name="status" style="background:#1a1a1a;border:1px solid #27272a;color:#fff;padding:0.6rem 1rem;border-radius:8px;font-size:0.85rem;">
            <option value="">All Status</option>
            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
            <option value="suspended" {{ request('status')=='suspended'?'selected':'' }}>Suspended</option>
        </select>
        <button type="submit" style="background:#7c3aed;color:#fff;border:none;padding:0.6rem 1.25rem;border-radius:8px;font-size:0.85rem;cursor:pointer;">Filter</button>
    </form>

    <div class="sa-card">
        <table class="sa-table">
            <thead>
                <tr>
                    <th>Agent</th>
                    <th>Code</th>
                    <th>Commission Rate</th>
                    <th>Quota</th>
                    <th>Sold</th>
                    <th>Pending Commission</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $agent)
                <tr>
                    <td>
                        <div>
                            <div style="font-weight:600;color:#fff;font-size:0.85rem;">{{ $agent->user->name }}</div>
                            <div style="font-size:0.72rem;color:#71717a;">{{ $agent->user->email }}</div>
                            @if($agent->company_name)<div style="font-size:0.72rem;color:#a78bfa;">{{ $agent->company_name }}</div>@endif
                        </div>
                    </td>
                    <td><span style="font-family:monospace;background:#27272a;padding:0.2rem 0.5rem;border-radius:4px;font-size:0.8rem;color:#a78bfa;">{{ $agent->agent_code }}</span></td>
                    <td style="font-size:0.9rem;font-weight:700;color:#22c55e;">{{ $agent->commission_rate }}%</td>
                    <td>
                        <div style="font-size:0.8rem;color:#fff;">{{ $agent->available_quota }} / {{ $agent->subscription_quota }}</div>
                        <div style="height:4px;background:#27272a;border-radius:2px;margin-top:4px;width:80px;">
                            @if($agent->subscription_quota > 0)
                            <div style="height:4px;background:#7c3aed;border-radius:2px;width:{{ min(100, ($agent->subscriptions_used / $agent->subscription_quota) * 100) }}%;"></div>
                            @endif
                        </div>
                    </td>
                    <td style="font-size:0.85rem;color:#fff;">{{ $agent->assigned_subscriptions_count ?? 0 }}</td>
                    <td style="font-size:0.85rem;color:#f59e0b;font-weight:700;">₹{{ number_format($agent->pending_commission, 2) }}</td>
                    <td>
                        @php $sc = $agent->status; $scColors = ['active'=>'#22c55e','inactive'=>'#f59e0b','suspended'=>'#ef4444']; @endphp
                        <span style="background:{{ $scColors[$sc]??'#22c55e' }}22;color:{{ $scColors[$sc]??'#22c55e' }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.72rem;font-weight:700;text-transform:capitalize;">{{ $sc }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.5rem;">
                            <a href="{{ route('superadmin.agents.show', $agent->id) }}" style="background:#27272a;color:#a1a1aa;padding:0.35rem 0.75rem;border-radius:6px;font-size:0.75rem;text-decoration:none;"><i class="fas fa-eye"></i></a>
                            @if(auth()->user()->hasSaPermission('agents.edit'))
                            <a href="{{ route('superadmin.agents.edit', $agent->id) }}" style="background:#27272a;color:#a1a1aa;padding:0.35rem 0.75rem;border-radius:6px;font-size:0.75rem;text-decoration:none;"><i class="fas fa-edit"></i></a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:3rem;color:#71717a;">No agents found.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($agents->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #27272a;">{{ $agents->links() }}</div>
        @endif
    </div>
</div>
<style>
.sa-btn-primary{background:#7c3aed;color:#fff;border:none;padding:0.65rem 1.25rem;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;}
.sa-btn-primary:hover{background:#6d28d9;}
</style>
@endsection
