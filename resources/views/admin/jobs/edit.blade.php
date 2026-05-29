@extends('layouts.admin')
@section('title', 'Edit Job')
@section('page-title', 'Edit Job')

@section('content')
<div style="max-width: 800px;">
    <form method="POST" action="{{ route('admin.jobs.update', $job) }}">
        @csrf @method('PUT')
        <div class="grid-2" style="align-items: start; gap: 2rem;">
            <div>
                <div class="form-group">
                    <label class="form-label">Job Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $job->title) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Job Description *</label>
                    <textarea name="description" class="form-control" rows="8" required>{{ old('description', $job->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Requirements</label>
                    <textarea name="requirements" class="form-control" rows="6">{{ old('requirements', $job->requirements) }}</textarea>
                </div>
            </div>
            <div>
                <div class="card">
                    <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Job Details</h3>
                    <div class="form-group">
                        <label class="form-label">Location *</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $job->location) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Type *</label>
                        <select name="type" class="form-control" required>
                            @foreach(['Full-time','Part-time','Contract','Remote','Internship'] as $type)
                            <option value="{{ $type }}" {{ old('type', $job->type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Salary Range</label>
                        <input type="text" name="salary_range" class="form-control" value="{{ old('salary_range', $job->salary_range) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" required>
                            @foreach(['active','inactive','filled'] as $status)
                            <option value="{{ $status }}" {{ old('status', $job->status) === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Expires At</label>
                        <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at', $job->expires_at?->format('Y-m-d')) }}">
                    </div>
                </div>
                <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-save"></i> Update Job</button>
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
