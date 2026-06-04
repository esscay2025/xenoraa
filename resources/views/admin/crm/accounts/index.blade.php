@extends('layouts.admin')
@section('title', 'Accounts')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Accounts</h1>
        <p class="page-subtitle">Companies and organisations in your CRM</p>
    </div>
    <a href="{{ route('admin.newcrm.accounts.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Account</a>
</div>

{{-- Filters --}}
<form method="GET" class="card" style="padding:1rem;margin-bottom:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
    <div style="flex:1;min-width:200px">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, industry..." class="form-control">
    </div>
    <select name="type" class="form-control" style="width:150px">
        <option value="">All Types</option>
        @foreach(['prospect','customer','partner','vendor'] as $t)
        <option value="{{ $t }}" {{ request('type')==$t?'selected':'' }}>{{ ucfirst($t) }}</option>
        @endforeach
    </select>
    <select name="status" class="form-control" style="width:130px">
        <option value="">All Status</option>
        <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
        <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
    </select>
    <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    @if(request()->hasAny(['search','type','status']))
    <a href="{{ route('admin.newcrm.accounts') }}" class="btn btn-secondary">Clear</a>
    @endif
</form>

<div class="card">
    <div class="card-body" style="padding:0">
        <table class="table">
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Type</th>
                    <th>Industry</th>
                    <th>Contacts</th>
                    <th>Deals</th>
                    <th>Leads</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $acc)
                <tr>
                    <td>
                        <div style="font-weight:600">{{ $acc->name }}</div>
                        <div style="font-size:.78rem;color:var(--text-muted)">{{ $acc->email ?? $acc->website }}</div>
                    </td>
                    <td>
                        @php $typeColors = ['prospect'=>'#6366f1','customer'=>'#22c55e','partner'=>'#3b82f6','vendor'=>'#f59e0b']; @endphp
                        <span class="badge" style="background:{{ $typeColors[$acc->type] ?? '#6b7280' }}22;color:{{ $typeColors[$acc->type] ?? '#6b7280' }}">{{ ucfirst($acc->type) }}</span>
                    </td>
                    <td>{{ $acc->industry ?? '—' }}</td>
                    <td><span style="color:#8b5cf6;font-weight:600">{{ $acc->contacts_count }}</span></td>
                    <td><span style="color:#f97316;font-weight:600">{{ $acc->deals_count }}</span></td>
                    <td><span style="color:#f59e0b;font-weight:600">{{ $acc->leads_count }}</span></td>
                    <td>
                        <span class="badge" style="background:{{ $acc->status==='active' ? '#22c55e22' : '#ef444422' }};color:{{ $acc->status==='active' ? '#22c55e' : '#ef4444' }}">{{ ucfirst($acc->status) }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:.5rem">
                            <a href="{{ route('admin.newcrm.accounts.show', $acc) }}" class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.newcrm.accounts.edit', $acc) }}" class="btn btn-sm btn-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.newcrm.accounts.destroy', $acc) }}" onsubmit="return confirm('Delete this account?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#ef444422;color:#ef4444;border:none;cursor:pointer" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--text-muted)">
                    No accounts yet. <a href="{{ route('admin.newcrm.accounts.create') }}">Create your first account</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $accounts->withQueryString()->links() }}
@endsection
