@extends('layouts.admin')
@section('title', 'Add User')
@section('page-title', 'Add User')

@section('content')
<div style="max-width: 500px;">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="card">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control" placeholder="John Doe" value="{{ old('name') }}" required>
                @error('name')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control" placeholder="john@example.com" value="{{ old('email') }}" required>
                @error('email')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Role *</label>
                <select name="role_id" class="form-control" required>
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-muted" style="margin-top: 0.4rem;">
                    <strong>Admin:</strong> Full access | <strong>Staff:</strong> Blog, Expenses | <strong>Visitor:</strong> Blog read-only
                </p>
                @error('role_id')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required>
                @error('password')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Confirm Password *</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
            </div>
        </div>
        <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-user-plus"></i> Create User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
