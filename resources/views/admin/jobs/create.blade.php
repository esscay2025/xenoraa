@extends('layouts.admin')
@section('title', 'Post a Job')
@section('page-title', 'Post a Job')

@section('content')
<div style="max-width: 800px;">
    <form method="POST" action="{{ route('admin.jobs.store') }}">
        @csrf
        <div class="grid-2" style="align-items: start; gap: 2rem;">
            <div>
                <div class="form-group">
                    <label class="form-label">Job Title *</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g., Senior PHP Developer" value="{{ old('title') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Job Description *</label>
                    <textarea name="description" class="form-control" rows="8" placeholder="Describe the role, responsibilities, and what you're looking for..." required>{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Requirements</label>
                    <textarea name="requirements" class="form-control" rows="6" placeholder="List the required skills, experience, and qualifications...">{{ old('requirements') }}</textarea>
                </div>
            </div>
            <div>
                <div class="card">
                    <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Job Details</h3>
                    <div class="form-group">
                        <label class="form-label">Location *</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g., Chennai, India / Remote" value="{{ old('location') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Type *</label>
                        <select name="type" class="form-control" required>
                            <option value="Full-time" {{ old('type') === 'Full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="Part-time" {{ old('type') === 'Part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="Contract" {{ old('type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                            <option value="Remote" {{ old('type') === 'Remote' ? 'selected' : '' }}>Remote</option>
                            <option value="Internship" {{ old('type') === 'Internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Salary Range</label>
                        <input type="text" name="salary_range" class="form-control" placeholder="e.g., ₹3L - ₹6L per annum" value="{{ old('salary_range') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="filled" {{ old('status') === 'filled' ? 'selected' : '' }}>Filled</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Expires At</label>
                        <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                    </div>
                </div>
                <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-save"></i> Post Job</button>
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
