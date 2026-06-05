@extends('layouts.admin')
@section('title', 'Portfolio / Projects — Site Builder')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb" style="font-size:0.82rem;margin-bottom:0.35rem;">
            <ol class="breadcrumb mb-0" style="background:none;padding:0;">
                <li class="breadcrumb-item"><a href="{{ route('admin.site.index') }}" style="color:var(--accent);"><i class="fas fa-paint-brush"></i> Site Builder</a></li>
                <li class="breadcrumb-item active">Portfolio</li>
            </ol>
        </nav>
        <h4 class="mb-1">Portfolio / Projects</h4>
        <p class="text-muted mb-0">Showcase your work and achievements on your site</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.site.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Site Builder</a>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Project</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@if($projects->isEmpty())
<div class="card border-0 shadow-sm text-center py-5">
    <div class="card-body">
        <i class="bi bi-folder2-open display-1 text-muted"></i>
        <h5 class="mt-3">No Projects Yet</h5>
        <p class="text-muted">Start building your portfolio by adding your first project.</p>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">Add Your First Project</a>
    </div>
</div>
@else
<div class="row g-4">
    @foreach($projects as $project)
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            @if($project->featured_image)
            <img src="{{ $project->featured_image }}" class="card-img-top" style="height:180px;object-fit:cover;" alt="{{ $project->title }}">
            @else
            <div class="card-img-top d-flex align-items-center justify-content-center" style="height:180px;background:linear-gradient(135deg,#667eea,#764ba2);">
                <i class="bi bi-code-slash text-white display-4"></i>
            </div>
            @endif
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="card-title mb-0">{{ $project->title }}</h6>
                    @if($project->is_featured)
                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i></span>
                    @endif
                </div>
                @if($project->short_description)
                <p class="text-muted small mb-2">{{ Str::limit($project->short_description, 80) }}</p>
                @endif
                @if($project->technology_used)
                <div class="mb-2">
                    @foreach(array_slice(explode(',', $project->technology_used), 0, 3) as $tech)
                    <span class="badge bg-light text-dark border me-1">{{ trim($tech) }}</span>
                    @endforeach
                    @if(count(explode(',', $project->technology_used)) > 3)
                    <span class="badge bg-light text-dark border">+{{ count(explode(',', $project->technology_used)) - 3 }}</span>
                    @endif
                </div>
                @endif
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'in_progress' ? 'info' : 'secondary') }}">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                    <div>
                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this project?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
