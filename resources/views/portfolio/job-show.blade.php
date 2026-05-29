@extends('layouts.app')
@section('title', $job->title . ' | Jobs | Gopi K')

@section('content')
<div class="container" style="padding-top: 3rem; padding-bottom: 3rem;">

    <!-- Breadcrumb -->
    <div style="margin-bottom: 2rem; font-size: 0.875rem; color: var(--text-muted);">
        <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none;">Home</a>
        <span style="margin: 0 0.5rem;">/</span>
        <a href="{{ route('jobs') }}" style="color: var(--text-muted); text-decoration: none;">Jobs</a>
        <span style="margin: 0 0.5rem;">/</span>
        <span style="color: var(--text-secondary);">{{ $job->title }}</span>
    </div>

    <div class="grid-2" style="align-items: start; gap: 3rem;">
        <!-- Job Details -->
        <div>
            <div style="margin-bottom: 2rem;">
                <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 1rem;">{{ $job->title }}</h1>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
                    <span class="badge badge-success">Active</span>
                    <span style="font-size: 0.875rem; color: var(--text-secondary);"><i class="fas fa-map-marker-alt"></i> {{ $job->location }}</span>
                    <span style="font-size: 0.875rem; color: var(--text-secondary);"><i class="fas fa-clock"></i> {{ $job->type }}</span>
                    @if($job->salary_range)
                    <span style="font-size: 0.875rem; color: var(--text-secondary);"><i class="fas fa-money-bill-wave"></i> {{ $job->salary_range }}</span>
                    @endif
                </div>
                <p style="font-size: 0.875rem; color: var(--text-muted);">Posted {{ $job->created_at->diffForHumans() }}</p>
            </div>

            <div class="card" style="margin-bottom: 1.5rem;">
                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Job Description</h3>
                <div style="color: var(--text-secondary); line-height: 1.8; font-size: 0.95rem;">
                    {!! nl2br(e($job->description)) !!}
                </div>
            </div>

            @if($job->requirements)
            <div class="card">
                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Requirements</h3>
                <div style="color: var(--text-secondary); line-height: 1.8; font-size: 0.95rem;">
                    {!! nl2br(e($job->requirements)) !!}
                </div>
            </div>
            @endif
        </div>

        <!-- Application Form -->
        <div>
            <div class="card" style="position: sticky; top: 80px;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;">Apply for this Position</h2>

                @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('jobs.apply', $job->slug) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="applicant_name" class="form-control" placeholder="Your full name" value="{{ old('applicant_name', auth()->user()?->name) }}" required>
                        @error('applicant_name')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="applicant_email" class="form-control" placeholder="your@email.com" value="{{ old('applicant_email', auth()->user()?->email) }}" required>
                        @error('applicant_email')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="applicant_phone" class="form-control" placeholder="+91 XXXXX XXXXX" value="{{ old('applicant_phone') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Resume / CV * <span style="color: var(--text-muted); font-weight: 400;">(PDF, DOC, DOCX — max 5MB)</span></label>
                        <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx" required>
                        @error('resume')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cover Letter</label>
                        <textarea name="cover_letter" class="form-control" rows="4" placeholder="Tell us why you're a great fit for this role...">{{ old('cover_letter') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>
                </form>

                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); text-align: center;">
                    <p style="font-size: 0.8rem; color: var(--text-muted);">By applying, you agree to our privacy policy. Your resume will be securely stored.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
