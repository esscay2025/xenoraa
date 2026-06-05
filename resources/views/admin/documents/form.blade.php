@extends('layouts.admin')
@section('title', $document ? 'Edit Document' : 'Upload Document')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ $document ? 'Edit Document' : 'Upload Document' }}</h4>
    <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ $document ? route('admin.documents.update', $document) : route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($document) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $document->title ?? '') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $document->description ?? '') }}</textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                @foreach(\App\Models\Document::CATEGORIES as $key => $label)
                                <option value="{{ $key }}" {{ old('category', $document->category ?? 'other') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Visibility</label>
                            <select name="is_public" class="form-select">
                                <option value="1" {{ old('is_public', $document->is_public ?? 1) == 1 ? 'selected' : '' }}>Public (visible on site)</option>
                                <option value="0" {{ old('is_public', $document->is_public ?? 1) == 0 ? 'selected' : '' }}>Private (admin only)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">File {{ $document ? '(leave empty to keep current)' : '' }} <span class="text-danger">{{ $document ? '' : '*' }}</span></label>
                        @if($document)
                        <div class="mb-2 p-2 bg-light rounded">
                            <small><i class="bi bi-file-earmark"></i> Current: <strong>{{ $document->file_name }}</strong> ({{ $document->file_size_formatted }})</small>
                        </div>
                        @endif
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" {{ $document ? '' : 'required' }}>
                        <small class="text-muted">Max 20MB. Supported: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, images</small>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> {{ $document ? 'Update' : 'Upload' }} Document</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
