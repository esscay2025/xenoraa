@extends('layouts.admin')
@section('title', $lead ? 'Edit Lead' : 'New Lead')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">{{ $lead ? 'Edit Lead' : 'New Lead' }}</h1></div>
    <a href="{{ route('admin.newcrm.leads') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="{{ $lead ? route('admin.newcrm.leads.update', $lead) : route('admin.newcrm.leads.store') }}">
    @csrf @if($lead) @method('PUT') @endif
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Lead Information</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $lead?->name) }}" class="form-control" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $lead?->email) }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $lead?->phone) }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Company</label>
                    <input type="text" name="company" value="{{ old('company', $lead?->company) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Message / Notes</label>
                    <textarea name="message" class="form-control" rows="4">{{ old('message', $lead?->message) }}</textarea>
                </div>
            </div>
        </div>

        <div>
            <div class="card" style="margin-bottom:1.5rem">
                <div class="card-header"><h3 class="card-title">Classification</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Source *</label>
                        <select name="source" class="form-control" required>
                            @foreach(['manual','ai_chatbot','website_form','referral','linkedin','cold_outreach','other'] as $s)
                            <option value="{{ $s }}" {{ old('source', $lead?->source ?? 'manual') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" required>
                            @foreach(['new','contacted','qualified','proposal','converted','lost'] as $s)
                            <option value="{{ $s }}" {{ old('status', $lead?->status ?? 'new') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Priority *</label>
                        <select name="priority" class="form-control" required>
                            @foreach(['low','medium','high','urgent'] as $p)
                            <option value="{{ $p }}" {{ old('priority', $lead?->priority ?? 'medium') == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deal Value (₹)</label>
                        <input type="number" name="deal_value" value="{{ old('deal_value', $lead?->deal_value) }}" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account</label>
                        <select name="account_id" class="form-control">
                            <option value="">— None —</option>
                            @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ old('account_id', $lead?->account_id) == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">
                <i class="fas fa-save"></i> {{ $lead ? 'Update Lead' : 'Create Lead' }}
            </button>
        </div>
    </div>
</form>
@endsection
