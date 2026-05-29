@extends('layouts.admin')
@section('title', 'Edit Expense')
@section('page-title', 'Edit Expense')

@section('content')
<div style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.expenses.update', $expense) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="card">
            <div class="form-group">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $expense->title) }}" required>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Amount (₹) *</label>
                    <input type="number" name="amount" class="form-control" step="0.01" min="0" value="{{ old('amount', $expense->amount) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Expense Date *</label>
                    <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $expense->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-control" required>
                        <option value="personal" {{ old('type', $expense->type) === 'personal' ? 'selected' : '' }}>Personal</option>
                        <option value="business" {{ old('type', $expense->type) === 'business' ? 'selected' : '' }}>Business</option>
                    </select>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $expense->description) }}</textarea>
            </div>
        </div>
        <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-save"></i> Update Expense</button>
            <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
