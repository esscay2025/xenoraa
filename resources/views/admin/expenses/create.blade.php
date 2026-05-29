@extends('layouts.admin')
@section('title', 'Add Expense')
@section('page-title', 'Add Expense')

@section('content')
<div style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.expenses.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="form-group">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-control" placeholder="e.g., Office Supplies, Travel, Software License" value="{{ old('title') }}" required>
                @error('title')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Amount (₹) *</label>
                    <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" min="0" value="{{ old('amount') }}" required>
                    @error('amount')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Expense Date *</label>
                    <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-control" required>
                        <option value="personal" {{ old('type') === 'personal' ? 'selected' : '' }}>Personal</option>
                        <option value="business" {{ old('type') === 'business' ? 'selected' : '' }}>Business</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Optional notes about this expense...">{{ old('description') }}</textarea>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Receipt <span style="color: var(--text-muted); font-weight: 400;">(JPG, PNG, PDF — max 5MB)</span></label>
                <input type="file" name="receipt" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            </div>
        </div>
        <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-save"></i> Save Expense</button>
            <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
