@extends('layouts.admin')
@section('title', 'Applications - ' . $job->title)
@section('page-title', 'Applications: ' . $job->title)

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <p class="text-sm text-muted">{{ $applications->total() }} application(s) for this position</p>
    </div>
    <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back to Jobs</a>
</div>

<div class="card" style="padding: 0;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Applicant</th>
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
                        <button onclick="document.getElementById('cover-{{ $app->id }}').style.display='block'" class="btn btn-outline btn-xs">
                            <i class="fas fa-envelope"></i> Cover Letter
                        </button>
                        <div id="cover-{{ $app->id }}" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.8); z-index:9999; display:none; align-items:center; justify-content:center; padding: 2rem;" onclick="this.style.display='none'">
                            <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 2rem; max-width: 600px; width: 100%; max-height: 80vh; overflow-y: auto;" onclick="event.stopPropagation()">
                                <h3 style="margin-bottom: 1rem;">Cover Letter - {{ $app->applicant_name }}</h3>
                                <p style="color: var(--text-secondary); line-height: 1.8;">{{ $app->cover_letter }}</p>
                                <button onclick="document.getElementById('cover-{{ $app->id }}').style.display='none'" class="btn btn-outline btn-sm" style="margin-top: 1rem;">Close</button>
                            </div>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">No applications yet for this position.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top: 1.5rem;">{{ $applications->links() }}</div>
@endsection
