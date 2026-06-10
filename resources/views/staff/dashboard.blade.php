@extends('layouts.app')
@section('title', 'Staff Dashboard')
@push('styles')
<style>
    .staff-sidebar { background: var(--bg-secondary); border-right: 1px solid var(--border); min-height: calc(100vh - 64px); padding: 1.5rem; width: 240px; flex-shrink: 0; }
    .staff-main { flex: 1; padding: 2rem; overflow-y: auto; }
    .staff-layout { display: flex; min-height: calc(100vh - 64px); }
    .staff-nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; color: var(--text-secondary); text-decoration: none; font-size: 0.875rem; font-weight: 500; border-radius: 8px; transition: all 0.15s; margin-bottom: 0.25rem; }
    .staff-nav-link:hover, .staff-nav-link.active { color: var(--text-primary); background: var(--bg-hover); }
    .staff-nav-section { font-size: 0.7rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; margin: 1rem 0 0.5rem; padding: 0 0.75rem; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; }
    .stat-card .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .stat-card .stat-value { font-size: 1.75rem; font-weight: 800; color: var(--text-primary); line-height: 1; }
    .stat-card .stat-sub { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; }
    .expense-row { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid var(--border); }
    .expense-row:last-child { border-bottom: none; }
    .badge-status { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; }
    .badge-approved { background: rgba(34,197,94,0.15); color: #16a34a; }
    .badge-pending  { background: rgba(234,179,8,0.15); color: #ca8a04; }
    .badge-rejected { background: rgba(239,68,68,0.15); color: #dc2626; }
    .welcome-banner { background: linear-gradient(135deg, var(--accent, #6366f1) 0%, color-mix(in srgb, var(--accent, #6366f1) 70%, #000) 100%); border-radius: 16px; padding: 1.75rem 2rem; color: #fff; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .welcome-banner h1 { font-size: 1.5rem; font-weight: 800; margin: 0 0 0.25rem; }
    .welcome-banner p { font-size: 0.875rem; opacity: 0.85; margin: 0; }
    .welcome-banner .btn-white { background: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.3); padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600; text-decoration: none; white-space: nowrap; transition: background 0.15s; }
    .welcome-banner .btn-white:hover { background: rgba(255,255,255,0.35); }
</style>
@endpush
@section('content')
@php
    $staffUser = auth()->user();
    $tenantOwner = $staffUser->tenant_owner_id ? \App\Models\User::find($staffUser->tenant_owner_id) : null;
    $tenantSettings = $tenantOwner ? \App\Models\SiteSetting::where('user_id', $tenantOwner->id)->pluck('value', 'key')->toArray() : [];
    $tenantSiteName = $tenantSettings['site_name'] ?? ($tenantOwner?->name ?? 'Your Company');
    $tenantUsername = $tenantOwner?->username ?? '';
    $tenantCustomDomain = $tenantOwner?->custom_domain ?? null;
    $tenantBaseUrl = $tenantCustomDomain ? 'https://' . $tenantCustomDomain : url('/' . $tenantUsername);
    $tenantBlogUrl = $tenantBaseUrl . '/blog';
    $myExpenses = \App\Models\Expense::where('user_id', $staffUser->id);
@endphp
<div class="staff-layout">
    {{-- Sidebar --}}
    <aside class="staff-sidebar">
        <div style="margin-bottom: 1.5rem;">
            <p style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.25rem;">Staff Panel</p>
            <p style="font-size: 0.8rem; color: var(--text-secondary); margin: 0;">{{ $tenantSiteName }}</p>
        </div>
        <div class="staff-nav-section">Main</div>
        <a href="{{ route('staff.dashboard') }}" class="staff-nav-link active">
            <i class="fas fa-tachometer-alt" style="width:16px;text-align:center;"></i> Dashboard
        </a>
        <div class="staff-nav-section">Finance</div>
        <a href="{{ route('staff.expenses.index') }}" class="staff-nav-link">
            <i class="fas fa-wallet" style="width:16px;text-align:center;"></i> My Expenses
        </a>
        <a href="{{ route('staff.expenses.create') }}" class="staff-nav-link">
            <i class="fas fa-plus-circle" style="width:16px;text-align:center;"></i> Add Expense
        </a>
        <div class="staff-nav-section">Site</div>
        <a href="{{ $tenantBlogUrl }}" class="staff-nav-link" target="_blank">
            <i class="fas fa-pen-nib" style="width:16px;text-align:center;"></i> Blog
        </a>
        <a href="{{ $tenantBaseUrl }}" class="staff-nav-link" target="_blank">
            <i class="fas fa-external-link-alt" style="width:16px;text-align:center;"></i> View Site
        </a>
        <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid var(--border); margin-top: 2rem;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="staff-nav-link" style="width:100%; background:none; border:none; cursor:pointer; color: var(--text-secondary);">
                    <i class="fas fa-sign-out-alt" style="width:16px;text-align:center;"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="staff-main">
        {{-- Welcome Banner --}}
        <div class="welcome-banner">
            <div>
                <h1>Welcome back, {{ $staffUser->name }}!</h1>
                <p>Staff Panel &mdash; {{ $tenantSiteName }}</p>
            </div>
            <a href="{{ $tenantBaseUrl }}" target="_blank" class="btn-white">
                <i class="fas fa-external-link-alt"></i> View Site
            </a>
        </div>

        {{-- Stats --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <div class="stat-card">
                <div class="stat-label">Total Expenses</div>
                <div class="stat-value">₹{{ number_format($myExpenses->sum('amount'), 0) }}</div>
                <div class="stat-sub">All time</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pending</div>
                <div class="stat-value">{{ $myExpenses->where('status', 'pending')->count() }}</div>
                <div class="stat-sub">Awaiting approval</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Approved</div>
                <div class="stat-value">{{ $myExpenses->where('status', 'approved')->count() }}</div>
                <div class="stat-sub">This month</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Rejected</div>
                <div class="stat-value">{{ $myExpenses->where('status', 'rejected')->count() }}</div>
                <div class="stat-sub">Total</div>
            </div>
        </div>

        {{-- Recent Expenses --}}
        <div class="stat-card" style="margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Recent Expenses</h3>
                <a href="{{ route('staff.expenses.create') }}" class="btn btn-primary btn-sm" style="font-size:0.75rem; padding: 0.375rem 0.75rem;">
                    <i class="fas fa-plus"></i> Add Expense
                </a>
            </div>
            @php $recent = \App\Models\Expense::where('user_id', $staffUser->id)->with('category')->orderBy('expense_date', 'desc')->take(8)->get(); @endphp
            @forelse($recent as $expense)
            <div class="expense-row">
                <div>
                    <p style="font-size: 0.875rem; font-weight: 600; margin: 0; color: var(--text-primary);">{{ $expense->title }}</p>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0.125rem 0 0;">
                        {{ $expense->category?->name ?? 'Uncategorized' }} &bull; {{ $expense->expense_date->format('M d, Y') }}
                    </p>
                </div>
                <div style="text-align: right; flex-shrink: 0; margin-left: 1rem;">
                    <p style="font-size: 0.875rem; font-weight: 700; margin: 0; color: var(--text-primary);">₹{{ number_format($expense->amount, 0) }}</p>
                    <span class="badge-status badge-{{ $expense->status }}">{{ $expense->status }}</span>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                <i class="fas fa-wallet" style="font-size: 2rem; margin-bottom: 0.75rem; display: block;"></i>
                <p style="margin: 0 0 0.75rem;">No expenses yet.</p>
                <a href="{{ route('staff.expenses.create') }}" class="btn btn-primary btn-sm">Add your first expense</a>
            </div>
            @endforelse
        </div>

        {{-- Quick Actions --}}
        <div class="stat-card">
            <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 1rem;">Quick Actions</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                <a href="{{ route('staff.expenses.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> New Expense
                </a>
                <a href="{{ route('staff.expenses.index') }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-list"></i> All Expenses
                </a>
                <a href="{{ $tenantBaseUrl }}" target="_blank" class="btn btn-outline btn-sm">
                    <i class="fas fa-globe"></i> View Site
                </a>
                <a href="{{ $tenantBlogUrl }}" target="_blank" class="btn btn-outline btn-sm">
                    <i class="fas fa-pen-nib"></i> Blog
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
