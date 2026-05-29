@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<!-- Stats Grid -->
<div class="grid-4" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div class="stat-label">Total Users</div>
                <div class="stat-number">{{ $stats['total_users'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(59,130,246,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-users" style="color: #93c5fd; font-size: 1.25rem;"></i>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div class="stat-label">Published Posts</div>
                <div class="stat-number">{{ $stats['total_posts'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(34,197,94,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-pen-nib" style="color: #86efac; font-size: 1.25rem;"></i>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div class="stat-label">Active Jobs</div>
                <div class="stat-number">{{ $stats['active_jobs'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(245,158,11,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-briefcase" style="color: #fcd34d; font-size: 1.25rem;"></i>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div class="stat-label">Applications</div>
                <div class="stat-number">{{ $stats['total_applications'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(239,68,68,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-file-alt" style="color: #fca5a5; font-size: 1.25rem;"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 2rem; align-items: start;">
    <!-- Expense Summary -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div class="stat-label">Pending Expenses</div>
                <div class="stat-number">{{ $stats['pending_expenses'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(245,158,11,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-clock" style="color: #fcd34d; font-size: 1.25rem;"></i>
            </div>
        </div>
        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
            <div class="text-sm text-secondary">Total Expenses: <strong style="color: white;">₹{{ number_format($stats['total_expenses'], 2) }}</strong></div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Quick Actions</h3>
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <a href="{{ route('admin.blog.create') }}" class="btn btn-outline btn-sm" style="justify-content: flex-start;">
                <i class="fas fa-plus-circle"></i> New Blog Post
            </a>
            <a href="{{ route('admin.jobs.create') }}" class="btn btn-outline btn-sm" style="justify-content: flex-start;">
                <i class="fas fa-plus-circle"></i> Post a Job
            </a>
            <a href="{{ route('admin.expenses.create') }}" class="btn btn-outline btn-sm" style="justify-content: flex-start;">
                <i class="fas fa-plus-circle"></i> Add Expense
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-outline btn-sm" style="justify-content: flex-start;">
                <i class="fas fa-user-plus"></i> Add User
            </a>
        </div>
    </div>
</div>

<div class="grid-3" style="align-items: start;">
    <!-- Recent Posts -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Recent Posts</h3>
            <a href="{{ route('admin.blog.index') }}" class="btn btn-outline btn-xs">View All</a>
        </div>
        @forelse($recentPosts as $post)
        <div style="padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
            <p style="font-size: 0.875rem; font-weight: 500; margin: 0 0 0.25rem;">{{ Str::limit($post->title, 40) }}</p>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="badge {{ $post->status === 'published' ? 'badge-success' : 'badge-secondary' }}">{{ $post->status }}</span>
                <span class="text-xs text-muted">{{ $post->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @empty
        <p class="text-sm text-muted">No posts yet.</p>
        @endforelse
    </div>

    <!-- Recent Applications -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Recent Applications</h3>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline btn-xs">View All</a>
        </div>
        @forelse($recentApplications as $app)
        <div style="padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
            <p style="font-size: 0.875rem; font-weight: 500; margin: 0 0 0.25rem;">{{ $app->applicant_name }}</p>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="text-xs text-muted">{{ Str::limit($app->job?->title ?? 'N/A', 25) }}</span>
                <span class="badge badge-info">{{ $app->status }}</span>
            </div>
        </div>
        @empty
        <p class="text-sm text-muted">No applications yet.</p>
        @endforelse
    </div>

    <!-- Recent Expenses -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Recent Expenses</h3>
            <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline btn-xs">View All</a>
        </div>
        @forelse($recentExpenses as $expense)
        <div style="padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <p style="font-size: 0.875rem; font-weight: 500; margin: 0 0 0.25rem;">{{ Str::limit($expense->title, 30) }}</p>
                <span style="font-size: 0.875rem; font-weight: 600; color: {{ $expense->type === 'business' ? '#93c5fd' : '#86efac' }};">₹{{ number_format($expense->amount, 0) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="text-xs text-muted">{{ $expense->user?->name }}</span>
                <span class="badge {{ $expense->status === 'approved' ? 'badge-success' : ($expense->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">{{ $expense->status }}</span>
            </div>
        </div>
        @empty
        <p class="text-sm text-muted">No expenses yet.</p>
        @endforelse
    </div>
</div>

@endsection
