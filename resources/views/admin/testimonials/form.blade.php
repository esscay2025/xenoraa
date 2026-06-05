@extends('layouts.admin')
@section('title', $testimonial ? 'Edit Testimonial' : 'Add Testimonial')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ $testimonial ? 'Edit Testimonial' : 'Add Testimonial' }}</h4>
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<form action="{{ $testimonial ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($testimonial) @method('PUT') @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Client Name <span class="text-danger">*</span></label>
                            <input type="text" name="client_name" class="form-control" value="{{ old('client_name', $testimonial->client_name ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Client Email</label>
                            <input type="email" name="client_email" class="form-control" value="{{ old('client_email', $testimonial->client_email ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <input type="text" name="client_company" class="form-control" value="{{ old('client_company', $testimonial->client_company ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Designation</label>
                            <input type="text" name="client_designation" class="form-control" value="{{ old('client_designation', $testimonial->client_designation ?? '') }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Review <span class="text-danger">*</span></label>
                        <textarea name="review" class="form-control" rows="5" required>{{ old('review', $testimonial->review ?? '') }}</textarea>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Video Testimonial URL</label>
                        <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $testimonial->video_url ?? '') }}" placeholder="https://youtube.com/...">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Rating <span class="text-danger">*</span></label>
                        <select name="rating" class="form-select">
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ old('rating', $testimonial->rating ?? 5) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="approved" {{ old('status', $testimonial->status ?? 'approved') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ old('status', $testimonial->status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ old('status', $testimonial->status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Client Photo</label>
                        @if($testimonial && $testimonial->client_photo)
                        <div class="mb-2"><img src="{{ $testimonial->client_photo }}" class="rounded-circle" style="width:60px;height:60px;object-fit:cover;"></div>
                        @endif
                        <input type="file" name="client_photo" class="form-control" accept="image/*">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="isFeatured" {{ old('is_featured', $testimonial->is_featured ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isFeatured">Featured Testimonial</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg"></i> {{ $testimonial ? 'Update' : 'Save' }} Testimonial</button>
        </div>
    </div>
</form>
@endsection
