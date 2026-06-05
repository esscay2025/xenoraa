@extends('layouts.admin')
@section('title', $project ? 'Edit Project' : 'Add Project')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ $project ? 'Edit Project' : 'Add New Project' }}</h4>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<form action="{{ $project ? route('admin.projects.update', $project) : route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($project) @method('PUT') @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent"><h6 class="mb-0">Project Details</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Project Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $project->title ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="2" maxlength="500">{{ old('short_description', $project->short_description ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Description</label>
                        <textarea name="description" class="form-control" rows="6">{{ old('description', $project->description ?? '') }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Client Name</label>
                            <input type="text" name="client_name" class="form-control" value="{{ old('client_name', $project->client_name ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Project URL</label>
                            <input type="url" name="project_url" class="form-control" value="{{ old('project_url', $project->project_url ?? '') }}" placeholder="https://">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent"><h6 class="mb-0">Technology & Category</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Technologies Used</label>
                        <input type="text" name="technology_used" class="form-control" value="{{ old('technology_used', $project->technology_used ?? '') }}" placeholder="Laravel, React, MySQL (comma-separated)">
                        <small class="text-muted">Separate multiple technologies with commas</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" value="{{ old('category', $project->category ?? '') }}" placeholder="Web App, Mobile App, Design, etc.">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent"><h6 class="mb-0">Status & Dates</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="completed" {{ old('status', $project->status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="in_progress" {{ old('status', $project->status ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="planned" {{ old('status', $project->status ?? '') === 'planned' ? 'selected' : '' }}>Planned</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($project->start_date ?? null)?->format('Y-m-d')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($project->end_date ?? null)?->format('Y-m-d')) }}">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="isFeatured" {{ old('is_featured', $project->is_featured ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isFeatured">Featured Project</label>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent"><h6 class="mb-0">Images</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Featured Image</label>
                        @if($project && $project->featured_image)
                        <div class="mb-2"><img src="{{ $project->featured_image }}" class="img-fluid rounded" style="max-height:120px;"></div>
                        @endif
                        <input type="file" name="featured_image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gallery Images</label>
                        @if($project && $project->images)
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @foreach($project->images as $img)
                            <img src="{{ $img }}" class="rounded" style="width:60px;height:60px;object-fit:cover;">
                            @endforeach
                        </div>
                        @endif
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">You can select multiple images</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-4">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg"></i> {{ $project ? 'Update Project' : 'Create Project' }}</button>
    </div>
</form>
@endsection
