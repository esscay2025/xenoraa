@extends('layouts.admin')
@section('title', 'Expense Manager')
@section('page-title', 'Expense Manager')

@section('content')
<!-- Summary Cards -->
<div class="grid-4" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-label">Personal Expenses</div>
        <div class="stat-number" style="font-size: 1.5rem;">₹{{ number_format($totalPersonal, 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Business Expenses</div>
        <div class="stat-number" style="font-size: 1.5rem;">₹{{ number_format($totalBusiness, 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Expenses</div>
        <div class="stat-number" style="font-size: 1.5rem;">₹{{ number_format($totalPersonal + $totalBusiness, 0) }}</div>
    </div>
    <div style="display: flex; align-items: center; justify-content: center;">
        <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary" style="width: 100%; justify-content: center;">
            <i class="fas fa-plus"></i> Add Expense
        </a>
    </div>
</div>

<!-- Filters -->
<form method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <select name="type" class="form-control" style="max-width: 150px;" onchange="this.form.submit()">
        <option value="">All Types</option>
        <option value="personal" {{ request('type') === 'personal' ? 'selected' : '' }}>Personal</option>
        <option value="business" {{ request('type') === 'business' ? 'selected' : '' }}>Business</option>
    </select>
    <select name="status" class="form-control" style="max-width: 150px;" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
    </select>
    <select name="category_id" class="form-control" style="max-width: 180px;" onchange="this.form.submit()">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <input type="date" name="date_from" class="form-control" style="max-width: 160px;" value="{{ request('date_from') }}">
    <input type="date" name="date_to" class="form-control" style="max-width: 160px;" value="{{ request('date_to') }}">
    <button type="submit" class="btn btn-outline">Filter</button>
    @if(request()->hasAny(['type','status','category_id','date_from','date_to']))
    <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline">Clear</a>
    @endif
</form>

<div class="card" style="padding: 0;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Submitted By</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr>
                    <td>
                        <div style="font-weight: 500;">{{ $expense->title }}</div>
                        @if($expense->description)<div class="text-xs text-muted">{{ Str::limit($expense->description, 40) }}</div>@endif
                    </td>
                    <td><span class="text-sm text-secondary">{{ $expense->category?->name ?? '—' }}</span></td>
                    <td><span style="font-weight: 600; color: {{ $expense->type === 'business' ? '#93c5fd' : '#86efac' }};">₹{{ number_format($expense->amount, 2) }}</span></td>
                    <td><span class="badge {{ $expense->type === 'business' ? 'badge-info' : 'badge-success' }}">{{ ucfirst($expense->type) }}</span></td>
                    <td><span class="text-sm text-muted">{{ $expense->expense_date->format('M d, Y') }}</span></td>
                    <td><span class="text-sm text-secondary">{{ $expense->user?->name ?? '—' }}</span></td>
                    <td>
                        <span class="badge {{ $expense->status === 'approved' ? 'badge-success' : ($expense->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">
                            {{ ucfirst($expense->status) }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                            @if($expense->receipt_path)
                            <a href="{{ asset('storage/' . $expense->receipt_path) }}" class="btn btn-outline btn-xs" target="_blank"><i class="fas fa-receipt"></i></a>
                            @endif
                            <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-outline btn-xs"><i class="fas fa-edit"></i></a>
                            @if($expense->status === 'pending' && auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('admin.expenses.approve', $expense) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-xs"><i class="fas fa-check"></i></button>
                            </form>
                            <form method="POST" action="{{ route('admin.expenses.reject', $expense) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-times"></i></button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}" onsubmit="return confirm('Delete this expense?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-muted);">No expenses found. <a href="{{ route('admin.expenses.create') }}" style="color: white;">Add your first expense.</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top: 1.5rem;">{{ $expenses->links() }}</div>
@endsection
