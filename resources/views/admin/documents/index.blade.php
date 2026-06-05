@extends('layouts.admin')
@section('title', 'Documents & Downloads')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Documents & Downloads</h4>
        <p class="text-muted mb-0">Manage files available for download on your site</p>
    </div>
    <a href="{{ route('admin.documents.create') }}" class="btn btn-primary"><i class="bi bi-upload"></i> Upload Document</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="display-6 fw-bold text-primary">{{ $stats['total'] }}</div>
            <small class="text-muted">Total Files</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="display-6 fw-bold text-success">{{ $stats['public'] }}</div>
            <small class="text-muted">Public</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="display-6 fw-bold text-warning">{{ $stats['private'] }}</div>
            <small class="text-muted">Private</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="display-6 fw-bold text-info">{{ $stats['downloads'] }}</div>
            <small class="text-muted">Downloads</small>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@if($documents->isEmpty())
<div class="card border-0 shadow-sm text-center py-5">
    <div class="card-body">
        <i class="bi bi-file-earmark-arrow-up display-1 text-muted"></i>
        <h5 class="mt-3">No Documents Yet</h5>
        <p class="text-muted">Upload files like PDFs, presentations, or resources for your visitors.</p>
        <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">Upload First Document</a>
    </div>
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>File</th>
                    <th>Category</th>
                    <th>Size</th>
                    <th>Downloads</th>
                    <th>Visibility</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                @php
                    $icons = ['application/pdf' => 'file-earmark-pdf text-danger', 'application/msword' => 'file-earmark-word text-primary', 'application/zip' => 'file-earmark-zip text-warning', 'image/' => 'file-earmark-image text-success'];
                    $icon = 'file-earmark text-secondary';
                    foreach ($icons as $type => $i) { if (str_contains($doc->file_type ?? '', $type)) { $icon = $i; break; } }
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-{{ $icon }} fs-4"></i>
                            <div>
                                <strong>{{ $doc->title }}</strong><br>
                                <small class="text-muted">{{ $doc->file_name }}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-dark">{{ \App\Models\Document::CATEGORIES[$doc->category] ?? ucfirst($doc->category) }}</span></td>
                    <td><small>{{ $doc->file_size_formatted }}</small></td>
                    <td><i class="bi bi-download"></i> {{ $doc->download_count }}</td>
                    <td><span class="badge bg-{{ $doc->is_public ? 'success' : 'secondary' }}">{{ $doc->is_public ? 'Public' : 'Private' }}</span></td>
                    <td>
                        <a href="{{ $doc->file_path }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.documents.edit', $doc) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.documents.destroy', $doc) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
