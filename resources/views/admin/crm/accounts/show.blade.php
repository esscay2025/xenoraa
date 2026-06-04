@extends('layouts.admin')
@section('title', $account->name)
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $account->name }}</h1>
        <p class="page-subtitle">{{ $account->industry ?? '' }} &bull; {{ ucfirst($account->type) }}</p>
    </div>
    <div style="display:flex;gap:.75rem">
        <a href="{{ route('admin.newcrm.accounts.edit', $account) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
        <a href="{{ route('admin.newcrm.accounts') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Account Info</h3></div>
        <div class="card-body">
            <table style="width:100%;border-collapse:collapse">
                @foreach([['Email',$account->email],['Phone',$account->phone],['Website',$account->website],['City',$account->city],['Country',$account->country],['Employees',$account->employees],['Annual Revenue',$account->annual_revenue ? '₹'.number_format($account->annual_revenue,0) : null]] as [$label,$val])
                @if($val)
                <tr style="border-bottom:1px solid var(--border-color)">
                    <td style="padding:.5rem 0;color:var(--text-muted);width:40%">{{ $label }}</td>
                    <td style="padding:.5rem 0;font-weight:500">{{ $val }}</td>
                </tr>
                @endif
                @endforeach
            </table>
            @if($account->notes)
            <div style="margin-top:1rem;padding:.75rem;background:var(--bg-hover);border-radius:.5rem;font-size:.875rem">{{ $account->notes }}</div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Quick Stats</h3></div>
        <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div style="text-align:center;padding:1rem;background:var(--bg-hover);border-radius:.75rem">
                <div style="font-size:2rem;font-weight:700;color:#8b5cf6">{{ $account->contacts->count() }}</div>
                <div style="color:var(--text-muted);font-size:.875rem">Contacts</div>
            </div>
            <div style="text-align:center;padding:1rem;background:var(--bg-hover);border-radius:.75rem">
                <div style="font-size:2rem;font-weight:700;color:#f97316">{{ $account->deals->count() }}</div>
                <div style="color:var(--text-muted);font-size:.875rem">Deals</div>
            </div>
            <div style="text-align:center;padding:1rem;background:var(--bg-hover);border-radius:.75rem">
                <div style="font-size:2rem;font-weight:700;color:#f59e0b">{{ $account->leads->count() }}</div>
                <div style="color:var(--text-muted);font-size:.875rem">Leads</div>
            </div>
            <div style="text-align:center;padding:1rem;background:var(--bg-hover);border-radius:.75rem">
                <div style="font-size:2rem;font-weight:700;color:#22c55e">₹{{ number_format($account->deals->where('stage','closed_won')->sum('value'),0) }}</div>
                <div style="color:var(--text-muted);font-size:.875rem">Won</div>
            </div>
        </div>
    </div>
</div>

{{-- Contacts --}}
<div class="card" style="margin-bottom:1.5rem">
    <div class="card-header" style="display:flex;justify-content:space-between">
        <h3 class="card-title">Contacts</h3>
        <a href="{{ route('admin.newcrm.contacts.create') }}?account_id={{ $account->id }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Add</a>
    </div>
    <div class="card-body" style="padding:0">
        @forelse($account->contacts as $c)
        <div style="padding:.75rem 1.25rem;border-bottom:1px solid var(--border-color);display:flex;justify-content:space-between">
            <div><div style="font-weight:600">{{ $c->first_name }} {{ $c->last_name }}</div><div style="font-size:.78rem;color:var(--text-muted)">{{ $c->job_title }} &bull; {{ $c->email }}</div></div>
            <a href="{{ route('admin.newcrm.contacts.edit', $c) }}" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
        </div>
        @empty
        <div style="padding:1.5rem;text-align:center;color:var(--text-muted)">No contacts linked.</div>
        @endforelse
    </div>
</div>

{{-- Activities --}}
<div class="card">
    <div class="card-header"><h3 class="card-title">Recent Activities</h3></div>
    <div class="card-body" style="padding:0">
        @forelse($activities as $act)
        @php $typeInfo = \App\Models\CrmActivity::TYPES[$act->type] ?? ['icon'=>'fa-circle','color'=>'#6366f1']; @endphp
        <div style="padding:.75rem 1.25rem;border-bottom:1px solid var(--border-color);display:flex;gap:1rem;align-items:center">
            <div style="width:32px;height:32px;border-radius:50%;background:{{ $typeInfo['color'] }}22;display:flex;align-items:center;justify-content:center;color:{{ $typeInfo['color'] }};flex-shrink:0">
                <i class="fas {{ $typeInfo['icon'] }} fa-sm"></i>
            </div>
            <div style="flex:1"><div style="font-weight:600;font-size:.875rem">{{ $act->subject }}</div><div style="font-size:.75rem;color:var(--text-muted)">{{ $act->created_at->diffForHumans() }}</div></div>
            <span style="font-size:.7rem;padding:.2rem .5rem;border-radius:999px;background:{{ $act->status==='completed' ? '#22c55e22' : '#f59e0b22' }};color:{{ $act->status==='completed' ? '#22c55e' : '#f59e0b' }}">{{ ucfirst($act->status) }}</span>
        </div>
        @empty
        <div style="padding:1.5rem;text-align:center;color:var(--text-muted)">No activities yet.</div>
        @endforelse
    </div>
</div>
@endsection
