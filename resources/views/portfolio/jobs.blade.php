@extends('layouts.app')
@section('title', 'Jobs & Careers | Gopi K')

@section('content')
<div class="container" style="padding-top: 3rem; padding-bottom: 3rem;">
    <div style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">Open Positions</h1>
        <p style="color: var(--text-secondary);">Join Go Esscay Solutions and help us build the future of business technology.</p>
    </div>

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('jobs') }}" style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search jobs..." class="form-control" style="max-width: 250px;">
        <select name="type" class="form-control" style="max-width: 180px;">
            <option value="">All Types</option>
            <option value="Full-time" {{ request('type') === 'Full-time' ? 'selected' : '' }}>Full-time</option>
            <option value="Part-time" {{ request('type') === 'Part-time' ? 'selected' : '' }}>Part-time</option>
            <option value="Contract" {{ request('type') === 'Contract' ? 'selected' : '' }}>Contract</option>
            <option value="Remote" {{ request('type') === 'Remote' ? 'selected' : '' }}>Remote</option>
        </select>
        <button type="submit" class="btn btn-outline">Filter</button>
        @if(request()->hasAny(['search', 'type']))
        <a href="{{ route('jobs') }}" class="btn btn-outline">Clear</a>
        @endif
    </form>

    @if($jobs->count() > 0)
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        @foreach($jobs as $job)
        <a href="{{ route('jobs.show', $job->slug) }}" style="text-decoration: none; color: inherit; display: block; background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; transition: all 0.2s;" onmouseover="this.style.borderColor='#444'" onmouseout="this.style.borderColor='var(--border)'">
            <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
                <div style="flex: 1;">
                    <h2 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 0.5rem;">{{ $job->title }}</h2>
                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 0.75rem;">
                        <span style="font-size: 0.875rem; color: var(--text-secondary);"><i class="fas fa-map-marker-alt" style="margin-right: 0.3rem;"></i>{{ $job->location }}</span>
                        <span style="font-size: 0.875rem; color: var(--text-secondary);"><i class="fas fa-clock" style="margin-right: 0.3rem;"></i>{{ $job->type }}</span>
                        @if($job->salary_range)
                        <span style="font-size: 0.875rem; color: var(--text-secondary);"><i class="fas fa-money-bill-wave" style="margin-right: 0.3rem;"></i>{{ $job->salary_range }}</span>
                        @endif
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0;">{{ Str::limit(strip_tags($job->description), 150) }}</p>
                </div>
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                    <span class="badge badge-success">Active</span>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $job->created_at->diffForHumans() }}</span>
                    <span style="font-size: 0.875rem; color: var(--info);">Apply Now <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <div style="margin-top: 2rem;">{{ $jobs->links() }}</div>
    @else
    <div style="text-align: center; padding: 4rem 2rem;">
        <i class="fas fa-briefcase" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem; display: block;"></i>
        <h3 style="color: var(--text-secondary);">No open positions at the moment</h3>
        <p class="text-muted">Check back soon for new opportunities.</p>
    </div>
    @endif
</div>
@endsection
