@extends('layouts.admin')
@section('title', 'Job Listings')
@section('page-title', 'Job Listings')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <p class="text-sm text-muted">Manage job postings and applications</p>
    <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Post a Job</a>
</div>

<div class="card" style="padding: 0;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Applications</th>
                    <th>Status</th>
                    <th>Posted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr>
                    <td>
                        <div style="font-weight: 500;">{{ $job->title }}</div>
                        @if($job->salary_range)<div class="text-xs text-muted">{{ $job->salary_range }}</div>@endif
                    </td>
                    <td><span class="text-sm text-secondary">{{ $job->location }}</span></td>
                    <td><span class="badge badge-info">{{ $job->type }}</span></td>
                    <td>
                        <a href="{{ route('admin.jobs.applications', $job) }}" class="btn btn-outline btn-xs">
                            <i class="fas fa-users"></i> {{ $job->applications_count }}
                        </a>
                    </td>
                    <td>
                        <span class="badge {{ $job->status === 'active' ? 'badge-success' : ($job->status === 'filled' ? 'badge-info' : 'badge-secondary') }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </td>
                    <td><span class="text-sm text-muted">{{ $job->created_at->format('M d, Y') }}</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('jobs.show', $job->slug) }}" class="btn btn-outline btn-xs" target="_blank"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-outline btn-xs"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" onsubmit="return confirm('Delete this job?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-muted);">No jobs posted yet. <a href="{{ route('admin.jobs.create') }}" style="color: white;">Post your first job.</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top: 1.5rem;">{{ $jobs->links() }}</div>
@endsection
