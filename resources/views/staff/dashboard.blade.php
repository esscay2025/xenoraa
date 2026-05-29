@extends('layouts.app')
@section('title', 'Staff Dashboard')

@push('styles')
<style>
    .staff-sidebar { background: var(--bg-secondary); border-right: 1px solid var(--border); min-height: calc(100vh - 64px); padding: 1.5rem; width: 220px; flex-shrink: 0; }
    .staff-main { flex: 1; padding: 2rem; }
    .staff-layout { display: flex; min-height: calc(100vh - 64px); }
    .staff-nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; color: var(--text-secondary); text-decoration: none; font-size: 0.875rem; font-weight: 500; border-radius: 8px; transition: all 0.15s; margin-bottom: 0.25rem; }
    .staff-nav-link:hover, .staff-nav-link.active { color: var(--text-primary); background: var(--bg-hover); }
</style>
@endpush

@section('content')
<div class="staff-layout">
    <aside class="staff-sidebar">
        <p style="font-size: 0.7rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.75rem;">Staff Menu</p>
        <a href="{{ route('staff.dashboard') }}" class="staff-nav-link active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('staff.expenses.index') }}" class="staff-nav-link"><i class="fas fa-wallet"></i> My Expenses</a>
        <a href="{{ route('staff.expenses.create') }}" class="staff-nav-link"><i class="fas fa-plus-circle"></i> Add Expense</a>
        <a href="{{ route('blog') }}" class="staff-nav-link" target="_blank"><i class="fas fa-pen-nib"></i> Blog</a>
        <a href="{{ route('home') }}" class="staff-nav-link" target="_blank"><i class="fas fa-external-link-alt"></i> View Site</a>
    </aside>
    <div class="staff-main">
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Welcome, {{ auth()->user()->name }}</h1>
        <p class="text-secondary" style="margin-bottom: 2rem;">Staff Panel — You have access to Expense Manager.</p>

        <div class="grid-3" style="margin-bottom: 2rem;">
            @php
                $myExpenses = \App\Models\Expense::where('user_id', auth()->id());
            @endphp
            <div class="card">
                <div class="text-secondary text-sm">Total Expenses</div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">₹{{ number_format($myExpenses->sum('amount'), 0) }}</div>
            </div>
            <div class="card">
                <div class="text-secondary text-sm">Pending Approval</div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">{{ $myExpenses->where('status', 'pending')->count() }}</div>
            </div>
            <div class="card">
                <div class="text-secondary text-sm">Approved</div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">{{ $myExpenses->where('status', 'approved')->count() }}</div>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Recent Expenses</h3>
                <a href="{{ route('staff.expenses.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</a>
            </div>
            @php $recent = \App\Models\Expense::where('user_id', auth()->id())->with('category')->orderBy('expense_date', 'desc')->take(5)->get(); @endphp
            @forelse($recent as $expense)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
                <div>
                    <p style="font-size: 0.875rem; font-weight: 500; margin: 0;">{{ $expense->title }}</p>
                    <p class="text-xs text-muted" style="margin: 0;">{{ $expense->category?->name }} &bull; {{ $expense->expense_date->format('M d, Y') }}</p>
                </div>
                <div style="text-align: right;">
                    <p style="font-size: 0.875rem; font-weight: 600; margin: 0;">₹{{ number_format($expense->amount, 0) }}</p>
                    <span class="badge {{ $expense->status === 'approved' ? 'badge-success' : ($expense->status === 'pending' ? 'badge-warning' : 'badge-danger') }}" style="font-size: 0.65rem;">{{ $expense->status }}</span>
                </div>
            </div>
            @empty
            <p class="text-sm text-muted">No expenses yet. <a href="{{ route('staff.expenses.create') }}" style="color: white;">Add your first expense.</a></p>
            @endforelse
        </div>
    </div>
</div>
@endsection
