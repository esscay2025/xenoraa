@extends('layouts.admin')
@section('title', 'Contacts')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Contacts</h1><p class="page-subtitle">People in your CRM</p></div>
    <a href="{{ route('admin.newcrm.contacts.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Contact</a>
</div>

<form method="GET" class="card" style="padding:1rem;margin-bottom:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
    <div style="flex:1;min-width:200px"><input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..." class="form-control"></div>
    <select name="source" class="form-control" style="width:160px">
        <option value="">All Sources</option>
        @foreach(['manual','ai_chatbot','website_form','referral','linkedin','cold_outreach','other'] as $s)
        <option value="{{ $s }}" {{ request('source')==$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
        @endforeach
    </select>
    <select name="account_id" class="form-control" style="width:180px">
        <option value="">All Accounts</option>
        @foreach($accounts as $acc)<option value="{{ $acc->id }}" {{ request('account_id')==$acc->id?'selected':'' }}>{{ $acc->name }}</option>@endforeach
    </select>
    <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    @if(request()->hasAny(['search','source','account_id']))<a href="{{ route('admin.newcrm.contacts') }}" class="btn btn-secondary">Clear</a>@endif
</form>

<div class="card">
    <div class="card-body" style="padding:0">
        <table class="table">
            <thead><tr><th>Name</th><th>Account</th><th>Email / Phone</th><th>Job Title</th><th>Source</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($contacts as $c)
                <tr>
                    <td><div style="font-weight:600">{{ $c->first_name }} {{ $c->last_name }}</div></td>
                    <td>{{ $c->account?->name ?? '—' }}</td>
                    <td><div>{{ $c->email }}</div><div style="font-size:.78rem;color:var(--text-muted)">{{ $c->phone }}</div></td>
                    <td>{{ $c->job_title ?? '—' }}</td>
                    <td>
                        @php $srcColors = ['ai_chatbot'=>'#6366f1','manual'=>'#6b7280','website_form'=>'#3b82f6','referral'=>'#22c55e','linkedin'=>'#0ea5e9']; @endphp
                        <span class="badge" style="background:{{ $srcColors[$c->source] ?? '#6b7280' }}22;color:{{ $srcColors[$c->source] ?? '#6b7280' }}">{{ ucwords(str_replace('_',' ',$c->source)) }}</span>
                    </td>
                    <td><span class="badge" style="background:{{ $c->status==='active' ? '#22c55e22' : '#ef444422' }};color:{{ $c->status==='active' ? '#22c55e' : '#ef4444' }}">{{ ucfirst($c->status) }}</span></td>
                    <td>
                        <div style="display:flex;gap:.5rem">
                            <a href="{{ route('admin.newcrm.contacts.edit', $c) }}" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.newcrm.contacts.destroy', $c) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#ef444422;color:#ef4444;border:none;cursor:pointer"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-muted)">No contacts yet. <a href="{{ route('admin.newcrm.contacts.create') }}">Add one</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $contacts->withQueryString()->links() }}
@endsection
