@extends('layouts.superadmin')
@section('title', 'Customers')
@section('content')
<div class="sa-content">
    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0;">Customers</h1>
            <p style="font-size:0.8rem;color:#71717a;margin:0.25rem 0 0;">All tenant accounts on the platform</p>
        </div>
        @if(auth()->user()->hasSaPermission('customers.create'))
        <a href="{{ route('superadmin.customers.create') }}" class="sa-btn-primary">
            <i class="fas fa-plus"></i> Create Customer
        </a>
        @endif
    </div>

    {{-- Filters --}}
    <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:1.5rem;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, username…"
               style="flex:1;min-width:200px;background:#1a1a1a;border:1px solid #27272a;color:#fff;padding:0.6rem 1rem;border-radius:8px;font-size:0.85rem;">
        <select name="plan" style="background:#1a1a1a;border:1px solid #27272a;color:#fff;padding:0.6rem 1rem;border-radius:8px;font-size:0.85rem;">
            <option value="">All Plans</option>
            <option value="starter" {{ request('plan')=='starter'?'selected':'' }}>Starter</option>
            <option value="professional" {{ request('plan')=='professional'?'selected':'' }}>Professional</option>
            <option value="business" {{ request('plan')=='business'?'selected':'' }}>Business</option>
        </select>
        <select name="status" style="background:#1a1a1a;border:1px solid #27272a;color:#fff;padding:0.6rem 1rem;border-radius:8px;font-size:0.85rem;">
            <option value="">All Status</option>
            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
            <option value="suspended" {{ request('status')=='suspended'?'selected':'' }}>Suspended</option>
        </select>
        <button type="submit" style="background:#7c3aed;color:#fff;border:none;padding:0.6rem 1.25rem;border-radius:8px;font-size:0.85rem;cursor:pointer;">Filter</button>
        @if(request()->hasAny(['search','plan','status']))
        <a href="{{ route('superadmin.customers.index') }}" style="background:#27272a;color:#a1a1aa;padding:0.6rem 1rem;border-radius:8px;font-size:0.85rem;text-decoration:none;">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="sa-card">
        <table class="sa-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Username</th>
                    <th>Profession</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Plan Expires</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="width:36px;height:36px;border-radius:50%;background:#7c3aed;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:0.875rem;flex-shrink:0;overflow:hidden;">
                                @if($c->avatar)<img src="{{ asset('storage/'.$c->avatar) }}" style="width:100%;height:100%;object-fit:cover;">@else{{ strtoupper(substr($c->name,0,1)) }}@endif
                            </div>
                            <div>
                                <div style="font-weight:600;color:#fff;font-size:0.85rem;">{{ $c->name }}</div>
                                <div style="font-size:0.72rem;color:#71717a;">{{ $c->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td><a href="{{ $c->getProfileUrl() }}" target="_blank" style="color:#a78bfa;text-decoration:none;font-size:0.82rem;">@{{ $c->username }}</a></td>
                    <td style="font-size:0.82rem;text-transform:capitalize;">{{ $c->profession ?? '—' }}</td>
                    <td>
                        @php $planColors = ['starter'=>'#3b82f6','professional'=>'#8b5cf6','business'=>'#f59e0b']; @endphp
                        <span style="background:{{ $planColors[$c->plan??'starter']??'#3b82f6' }}22;color:{{ $planColors[$c->plan??'starter']??'#3b82f6' }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.72rem;font-weight:700;text-transform:capitalize;">{{ $c->plan ?? 'starter' }}</span>
                    </td>
                    <td>
                        @php $sc = $c->status ?? 'active'; $scColors = ['active'=>'#22c55e','inactive'=>'#f59e0b','suspended'=>'#ef4444']; @endphp
                        <span style="background:{{ $scColors[$sc]??'#22c55e' }}22;color:{{ $scColors[$sc]??'#22c55e' }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.72rem;font-weight:700;text-transform:capitalize;">{{ $sc }}</span>
                    </td>
                    <td style="font-size:0.8rem;color:{{ $c->plan_expires_at && $c->plan_expires_at->isPast() ? '#ef4444' : '#a1a1aa' }};">
                        {{ $c->plan_expires_at ? $c->plan_expires_at->format('d M Y') : '—' }}
                    </td>
                    <td style="font-size:0.8rem;color:#71717a;">{{ $c->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:0.5rem;">
                            <a href="{{ route('superadmin.customers.show', $c->id) }}" style="background:#27272a;color:#a1a1aa;padding:0.35rem 0.75rem;border-radius:6px;font-size:0.75rem;text-decoration:none;" title="View"><i class="fas fa-eye"></i></a>
                            @if(auth()->user()->hasSaPermission('customers.edit'))
                            <a href="{{ route('superadmin.customers.edit', $c->id) }}" style="background:#27272a;color:#a1a1aa;padding:0.35rem 0.75rem;border-radius:6px;font-size:0.75rem;text-decoration:none;" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif
                            @if(auth()->user()->hasSaPermission('customers.impersonate'))
                            <a href="{{ route('superadmin.users.impersonate', $c->id) }}" style="background:#27272a;color:#f59e0b;padding:0.35rem 0.75rem;border-radius:6px;font-size:0.75rem;text-decoration:none;" title="Impersonate"><i class="fas fa-user-secret"></i></a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:3rem;color:#71717a;">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($customers->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #27272a;">{{ $customers->links() }}</div>
        @endif
    </div>
</div>
@endsection
