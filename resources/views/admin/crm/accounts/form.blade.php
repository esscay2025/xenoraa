@extends('layouts.admin')
@section('title', $account ? 'Edit Account' : 'New Account')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $account ? 'Edit Account' : 'New Account' }}</h1>
        <p class="page-subtitle">{{ $account ? 'Update account details' : 'Add a new company or organisation' }}</p>
    </div>
    <a href="{{ route('admin.newcrm.accounts') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="{{ $account ? route('admin.newcrm.accounts.update', $account) : route('admin.newcrm.accounts.store') }}">
    @csrf
    @if($account) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
        <div>
            <div class="card" style="margin-bottom:1.5rem">
                <div class="card-header"><h3 class="card-title">Basic Information</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Account Name *</label>
                        <input type="text" name="name" value="{{ old('name', $account?->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                        <div class="form-group">
                            <label class="form-label">Type *</label>
                            <select name="type" class="form-control" required>
                                @foreach(['prospect','customer','partner','vendor'] as $t)
                                <option value="{{ $t }}" {{ old('type', $account?->type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Industry</label>
                            <input type="text" name="industry" value="{{ old('industry', $account?->industry) }}" class="form-control" placeholder="e.g. Technology, Healthcare">
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $account?->email) }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $account?->phone) }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" value="{{ old('website', $account?->website) }}" class="form-control" placeholder="https://example.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $account?->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Address</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Street Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $account?->address) }}</textarea>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="city" value="{{ old('city', $account?->city) }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" value="{{ old('country', $account?->country) }}" class="form-control" value="India">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card" style="margin-bottom:1.5rem">
                <div class="card-header"><h3 class="card-title">Company Details</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Annual Revenue (₹)</label>
                        <input type="number" name="annual_revenue" value="{{ old('annual_revenue', $account?->annual_revenue) }}" class="form-control" min="0" step="1000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Employees</label>
                        <input type="number" name="employees" value="{{ old('employees', $account?->employees) }}" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="active" {{ old('status', $account?->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $account?->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%">
                <i class="fas fa-save"></i> {{ $account ? 'Update Account' : 'Create Account' }}
            </button>
        </div>
    </div>
</form>
@endsection
