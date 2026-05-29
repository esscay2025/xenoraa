@extends('layouts.admin')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div style="max-width: 500px;">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="card">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Role *</label>
                <select name="role_id" class="form-control" required>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status *</label>
                <select name="status" class="form-control" required>
                    <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">New Password <span style="color: var(--text-muted); font-weight: 400;">(leave blank to keep current)</span></label>
                <input type="password" name="password" class="form-control" placeholder="New password (optional)">
                @error('password')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
            </div>
        </div>
        <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-save"></i> Update User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
