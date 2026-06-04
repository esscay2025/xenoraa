@extends('layouts.admin')
@section('title', 'Lead: ' . $lead->name)
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $lead->name }}</h1>
        <p class="page-subtitle">{{ $lead->company ?? $lead->email }}</p>
    </div>
    <div style="display:flex;gap:.75rem">
        <a href="{{ route('admin.newcrm.leads.edit', $lead) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
        <a href="{{ route('admin.newcrm.leads') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Lead Details</h3></div>
        <div class="card-body">
            @php
                $srcColors = ['ai_chatbot'=>'#6366f1','manual'=>'#6b7280','website_form'=>'#3b82f6','referral'=>'#22c55e','linkedin'=>'#0ea5e9','cold_outreach'=>'#f97316','other'=>'#6b7280'];
                $statusColors = ['new'=>'#6366f1','contacted'=>'#3b82f6','qualified'=>'#f59e0b','proposal'=>'#f97316','converted'=>'#22c55e','lost'=>'#ef4444'];
                $priColors = ['low'=>'#6b7280','medium'=>'#3b82f6','high'=>'#f59e0b','urgent'=>'#ef4444'];
            @endphp
            <table style="width:100%;border-collapse:collapse">
                @foreach([['Email',$lead->email],['Phone',$lead->phone],['Company',$lead->company]] as [$l,$v])
                @if($v)<tr style="border-bottom:1px solid var(--border-color)"><td style="padding:.5rem 0;color:var(--text-muted);width:35%">{{ $l }}</td><td style="padding:.5rem 0;font-weight:500">{{ $v }}</td></tr>@endif
                @endforeach
                <tr style="border-bottom:1px solid var(--border-color)">
                    <td style="padding:.5rem 0;color:var(--text-muted)">Source</td>
                    <td style="padding:.5rem 0"><span class="badge" style="background:{{ $srcColors[$lead->source ?? 'manual'] }}22;color:{{ $srcColors[$lead->source ?? 'manual'] }}">{{ ucwords(str_replace('_',' ',$lead->source ?? 'manual')) }}</span></td>
                </tr>
                <tr style="border-bottom:1px solid var(--border-color)">
                    <td style="padding:.5rem 0;color:var(--text-muted)">Status</td>
                    <td style="padding:.5rem 0"><span class="badge" style="background:{{ $statusColors[$lead->status] }}22;color:{{ $statusColors[$lead->status] }}">{{ ucfirst($lead->status) }}</span></td>
                </tr>
                <tr style="border-bottom:1px solid var(--border-color)">
                    <td style="padding:.5rem 0;color:var(--text-muted)">Priority</td>
                    <td style="padding:.5rem 0"><span class="badge" style="background:{{ $priColors[$lead->priority ?? 'medium'] }}22;color:{{ $priColors[$lead->priority ?? 'medium'] }}">{{ ucfirst($lead->priority ?? 'medium') }}</span></td>
                </tr>
                @if($lead->deal_value)
                <tr><td style="padding:.5rem 0;color:var(--text-muted)">Deal Value</td><td style="padding:.5rem 0;font-weight:700;color:#22c55e">₹{{ number_format($lead->deal_value,0) }}</td></tr>
                @endif
            </table>
            @if($lead->message)
            <div style="margin-top:1rem;padding:.75rem;background:var(--bg-hover);border-radius:.5rem;font-size:.875rem">{{ $lead->message }}</div>
            @endif
        </div>
    </div>

    {{-- Log Activity --}}
    <div class="card">
        <div class="card-header"><h3 class="card-title">Log Activity</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.newcrm.activities.store') }}">
                @csrf
                <input type="hidden" name="related_type" value="CrmLead">
                <input type="hidden" name="related_id" value="{{ $lead->id }}">
                <div class="form-group">
                    <label class="form-label">Activity Type</label>
                    <select name="type" class="form-control" required>
                        @foreach(\App\Models\CrmActivity::TYPES as $k => $info)
                        <option value="{{ $k }}">{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Subject *</label>
                    <input type="text" name="subject" class="form-control" required placeholder="e.g. Follow-up call">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">Due Date</label>
                        <input type="datetime-local" name="due_at" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%"><i class="fas fa-plus"></i> Log Activity</button>
            </form>
        </div>
    </div>
</div>

{{-- Activities Timeline --}}
<div class="card">
    <div class="card-header"><h3 class="card-title">Activity Timeline</h3></div>
    <div class="card-body" style="padding:0">
        @forelse($activities as $act)
        @php $typeInfo = \App\Models\CrmActivity::TYPES[$act->type] ?? ['icon'=>'fa-circle','color'=>'#6366f1']; @endphp
        <div style="padding:.75rem 1.25rem;border-bottom:1px solid var(--border-color);display:flex;gap:1rem;align-items:flex-start">
            <div style="width:36px;height:36px;border-radius:50%;background:{{ $typeInfo['color'] }}22;display:flex;align-items:center;justify-content:center;color:{{ $typeInfo['color'] }};flex-shrink:0;margin-top:.25rem">
                <i class="fas {{ $typeInfo['icon'] }}"></i>
            </div>
            <div style="flex:1">
                <div style="font-weight:600">{{ $act->subject }}</div>
                @if($act->description)<div style="font-size:.875rem;color:var(--text-secondary);margin-top:.25rem">{{ $act->description }}</div>@endif
                <div style="font-size:.75rem;color:var(--text-muted);margin-top:.25rem">{{ $act->created_at->format('d M Y, h:i A') }} @if($act->due_at) &bull; Due: {{ $act->due_at->format('d M Y') }}@endif</div>
            </div>
            <span style="font-size:.7rem;padding:.2rem .5rem;border-radius:999px;background:{{ $act->status==='completed' ? '#22c55e22' : '#f59e0b22' }};color:{{ $act->status==='completed' ? '#22c55e' : '#f59e0b' }}">{{ ucfirst($act->status) }}</span>
        </div>
        @empty
        <div style="padding:2rem;text-align:center;color:var(--text-muted)">No activities logged yet.</div>
        @endforelse
    </div>
</div>
@endsection
