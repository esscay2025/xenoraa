@extends('layouts.agent')
@section('title', 'My Customers')
@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;">
    <div>
        <h2 style="font-size:1.35rem;font-weight:800;color:#fff;margin:0;">My Customers</h2>
        <p style="font-size:0.85rem;color:#71717a;margin-top:0.25rem;">All customers you have onboarded</p>
    </div>
    <a href="{{ route('agent.create-customer') }}" class="ag-btn-primary">
        <i class="fas fa-user-plus"></i> Create Customer
    </a>
</div>

{{-- Search & Filter --}}
<div style="display:flex;gap:0.75rem;margin-bottom:1.5rem;flex-wrap:wrap;">
    <form method="GET" style="display:flex;gap:0.75rem;flex:1;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:0.5rem;background:#111;border:1px solid #27272a;border-radius:8px;padding:0.5rem 1rem;flex:1;min-width:200px;">
            <i class="fas fa-search" style="color:#3f3f46;font-size:0.8rem;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customers..." style="background:none;border:none;outline:none;color:#fff;font-size:0.825rem;font-family:inherit;width:100%;">
        </div>
        <select name="plan" style="background:#111;border:1px solid #27272a;border-radius:8px;padding:0.5rem 1rem;color:#a1a1aa;font-size:0.825rem;font-family:inherit;outline:none;">
            <option value="">All Plans</option>
            <option value="starter" {{ request('plan')=='starter'?'selected':'' }}>Starter</option>
            <option value="professional" {{ request('plan')=='professional'?'selected':'' }}>Professional</option>
            <option value="business" {{ request('plan')=='business'?'selected':'' }}>Business</option>
        </select>
        <select name="status" style="background:#111;border:1px solid #27272a;border-radius:8px;padding:0.5rem 1rem;color:#a1a1aa;font-size:0.825rem;font-family:inherit;outline:none;">
            <option value="">All Status</option>
            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="expired" {{ request('status')=='expired'?'selected':'' }}>Expired</option>
            <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
        </select>
        <button type="submit" class="ag-btn-outline"><i class="fas fa-filter"></i> Filter</button>
        @if(request()->hasAny(['search','plan','status']))
        <a href="{{ route('agent.my-customers') }}" class="ag-btn-outline"><i class="fas fa-times"></i> Clear</a>
        @endif
    </form>
</div>

<div class="ag-card">
    <div class="ag-card-header">
        <span class="ag-card-title"><i class="fas fa-users" style="color:#22c55e;margin-right:0.5rem;"></i> Customer List</span>
        <span style="font-size:0.75rem;color:#71717a;">{{ $subscribers->total() }} total</span>
    </div>
    <table class="ag-table">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Plan</th>
                <th>Duration</th>
                <th>Subscription Period</th>
                <th>Commission</th>
                <th>Commission Status</th>
                <th>Sub Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subscribers as $sub)
            <tr>
                <td>
                    <div style="font-weight:600;color:#fff;font-size:0.85rem;">{{ $sub->customer?->name ?? 'Unknown' }}</div>
                    <div style="font-size:0.72rem;color:#71717a;">{{ $sub->customer?->email }}</div>
                    @if($sub->customer?->phone)<div style="font-size:0.72rem;color:#52525b;">{{ $sub->customer->phone }}</div>@endif
                </td>
                <td><span class="ag-badge ag-badge-{{ $sub->plan }}" style="text-transform:capitalize;">{{ $sub->plan }}</span></td>
                <td style="font-size:0.8rem;color:#a1a1aa;">{{ $sub->duration_months }} month{{ $sub->duration_months > 1 ? 's' : '' }}</td>
                <td style="font-size:0.78rem;color:#71717a;">
                    {{ \Carbon\Carbon::parse($sub->starts_at)->format('d M Y') }}<br>
                    <span style="color:#52525b;">to {{ \Carbon\Carbon::parse($sub->expires_at)->format('d M Y') }}</span>
                </td>
                <td>
                    <div style="font-size:0.9rem;font-weight:700;color:#22c55e;">₹{{ number_format($sub->commission_amount,2) }}</div>
                    <div style="font-size:0.7rem;color:#71717a;">{{ $sub->commission_rate }}% of ₹{{ number_format($sub->plan_price,0) }}</div>
                </td>
                <td><span class="ag-badge ag-badge-{{ $sub->commission_status }}" style="text-transform:capitalize;">{{ $sub->commission_status }}</span></td>
                <td><span class="ag-badge ag-badge-{{ $sub->status }}" style="text-transform:capitalize;">{{ $sub->status }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:3rem;color:#71717a;">
                    <i class="fas fa-users" style="font-size:2rem;margin-bottom:0.75rem;display:block;color:#27272a;"></i>
                    No customers found.<br>
                    <a href="{{ route('agent.create-customer') }}" style="color:#22c55e;text-decoration:none;font-weight:600;">Create your first customer →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($subscribers->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid #27272a;">{{ $subscribers->appends(request()->query())->links() }}</div>
    @endif
</div>

@endsection
