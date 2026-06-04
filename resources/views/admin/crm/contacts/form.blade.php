@extends('layouts.admin')
@section('title', $contact ? 'Edit Contact' : 'New Contact')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">{{ $contact ? 'Edit Contact' : 'New Contact' }}</h1></div>
    <a href="{{ route('admin.newcrm.contacts') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="{{ $contact ? route('admin.newcrm.contacts.update', $contact) : route('admin.newcrm.contacts.store') }}">
    @csrf @if($contact) @method('PUT') @endif
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Contact Details</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $contact?->first_name) }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $contact?->last_name) }}" class="form-control">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $contact?->email) }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $contact?->phone) }}" class="form-control">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">Job Title</label>
                        <input type="text" name="job_title" value="{{ old('job_title', $contact?->job_title) }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" value="{{ old('department', $contact?->department) }}" class="form-control">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" value="{{ old('city', $contact?->city) }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" value="{{ old('country', $contact?->country) }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $contact?->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div>
            <div class="card" style="margin-bottom:1.5rem">
                <div class="card-header"><h3 class="card-title">Classification</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Account</label>
                        <select name="account_id" class="form-control">
                            <option value="">— No Account —</option>
                            @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ old('account_id', $contact?->account_id ?? request('account_id')) == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Source *</label>
                        <select name="source" class="form-control" required>
                            @foreach(['manual','ai_chatbot','website_form','referral','linkedin','cold_outreach','other'] as $s)
                            <option value="{{ $s }}" {{ old('source', $contact?->source ?? 'manual') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" required>
                            @foreach(['active','inactive','unsubscribed'] as $s)
                            <option value="{{ $s }}" {{ old('status', $contact?->status ?? 'active') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">
                <i class="fas fa-save"></i> {{ $contact ? 'Update Contact' : 'Create Contact' }}
            </button>
        </div>
    </div>
</form>
@endsection
