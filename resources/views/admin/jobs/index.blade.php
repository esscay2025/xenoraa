@extends('layouts.admin')
@section('title', 'Recruitment')
@section('page-title', 'Recruitment')

@section('content')
{{-- Tab Header --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div style="display: flex; gap: 0; border: 1px solid var(--border); border-radius: 10px; overflow: hidden; background: var(--bg-card);">
        <a href="{{ route('admin.jobs.index', ['tab' => 'listings']) }}"
           style="padding: 0.55rem 1.25rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 0.4rem; border-right: 1px solid var(--border); transition: background 0.15s;
           {{ $tab === 'listings' ? 'background: var(--primary); color: #fff;' : 'color: var(--text-secondary);' }}">
            <i class="fas fa-briefcase" style="font-size:0.8rem;"></i> Job Listings
        </a>
        <a href="{{ route('admin.jobs.index', ['tab' => 'applications']) }}"
           style="padding: 0.55rem 1.25rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 0.4rem; transition: background 0.15s;
           {{ $tab === 'applications' ? 'background: var(--primary); color: #fff;' : 'color: var(--text-secondary);' }}">
            <i class="fas fa-users" style="font-size:0.8rem;"></i> Applications
            @if($applications->total() > 0)<span style="background: rgba(255,255,255,0.2); border-radius: 20px; padding: 0.1rem 0.5rem; font-size: 0.75rem; margin-left: 0.25rem;">{{ $applications->total() }}</span>@endif
        </a>
    </div>
    @if($tab === 'listings')
    <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Post a Job</a>
    @endif
</div>

{{-- ── TAB: JOB LISTINGS ──────────────────────────────────────────── --}}
@if($tab === 'listings')

<form method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <input type="hidden" name="tab" value="listings">
    <select name="status" class="form-control" style="max-width: 150px;" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
        <option value="filled" {{ request('status') === 'filled' ? 'selected' : '' }}>Filled</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
</form>

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
                        <a href="{{ route('admin.jobs.index', ['tab' => 'applications']) }}" class="btn btn-outline btn-xs">
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

{{-- ── TAB: APPLICATIONS ──────────────────────────────────────────── --}}
@elseif($tab === 'applications')

<div class="card" style="padding: 0;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Job</th>
                    <th>Contact</th>
                    <th>Resume</th>
                    <th>Status</th>
                    <th>Applied</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td>
                        <div style="font-weight: 500;">{{ $app->applicant_name }}</div>
                        @if($app->applicant_phone)<div class="text-xs text-muted">{{ $app->applicant_phone }}</div>@endif
                    </td>
                    <td><span class="text-sm text-secondary">{{ $app->job?->title ?? '—' }}</span></td>
                    <td><span class="text-sm text-secondary">{{ $app->applicant_email }}</span></td>
                    <td>
                        @if($app->resume_path)
                        <a href="{{ asset('storage/' . $app->resume_path) }}" class="btn btn-outline btn-xs" target="_blank">
                            <i class="fas fa-file-download"></i> Download
                        </a>
                        @else
                        <span class="text-muted text-xs">No resume</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.applications.status', $app) }}">
                            @csrf @method('PATCH')
                            <select name="status" class="form-control" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; width: auto;" onchange="this.form.submit()">
                                @foreach(['applied','reviewing','interviewing','offered','rejected'] as $status)
                                <option value="{{ $status }}" {{ $app->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td><span class="text-sm text-muted">{{ $app->created_at->format('M d, Y') }}</span></td>
                    <td>
                        @if($app->cover_letter)
                        <button onclick="document.getElementById('cover-{{ $app->id }}').style.display='flex'" class="btn btn-outline btn-xs">
                            <i class="fas fa-envelope"></i> Cover Letter
                        </button>
                        <div id="cover-{{ $app->id }}" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center; padding: 2rem;" onclick="this.style.display='none'">
                            <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 2rem; max-width: 600px; width: 100%; max-height: 80vh; overflow-y: auto;" onclick="event.stopPropagation()">
                                <h3 style="margin-bottom: 1rem;">Cover Letter — {{ $app->applicant_name }}</h3>
                                <p style="line-height: 1.7; color: var(--text-secondary);">{{ $app->cover_letter }}</p>
                                <button onclick="document.getElementById('cover-{{ $app->id }}').style.display='none'" class="btn btn-outline btn-sm" style="margin-top: 1rem;">Close</button>
                            </div>
                        </div>
                        @else
                        <span class="text-muted text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-muted);">No applications yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top: 1.5rem;">{{ $applications->links() }}</div>

@endif
@endsection
