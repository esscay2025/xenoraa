@extends('layouts.admin')
@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <p class="text-sm text-muted">Manage users and their access permissions</p>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add User</a>
</div>

<!-- Filters -->
<form method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <input type="text" name="search" class="form-control" style="max-width: 250px;" placeholder="Search by name or email..." value="{{ request('search') }}">
    <select name="role" class="form-control" style="max-width: 150px;" onchange="this.form.submit()">
        <option value="">All Roles</option>
        @foreach($roles as $role)
        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-outline">Search</button>
    @if(request()->hasAny(['search','role']))
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Clear</a>
    @endif
</form>

<div class="card" style="padding: 0;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--bg-secondary); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-user" style="font-size: 0.8rem; color: var(--text-secondary);"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; font-size: 0.875rem;">{{ $user->name }}</div>
                                @if($user->id === auth()->id())<span class="badge badge-info" style="font-size: 0.65rem;">You</span>@endif
                            </div>
                        </div>
                    </td>
                    <td><span class="text-sm text-secondary">{{ $user->email }}</span></td>
                    <td>
                        <span class="badge {{ $user->role?->name === 'admin' ? 'badge-danger' : ($user->role?->name === 'staff' ? 'badge-warning' : 'badge-info') }}">
                            {{ ucfirst($user->role?->name ?? 'visitor') }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                            {{ ucfirst($user->status ?? 'active') }}
                        </span>
                    </td>
                    <td><span class="text-sm text-muted">{{ $user->created_at->format('M d, Y') }}</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline btn-xs"><i class="fas fa-edit"></i></a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top: 1.5rem;">{{ $users->links() }}</div>
@endsection
