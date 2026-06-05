@extends('layouts.admin')
@section('title', 'Testimonials & Reviews')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Testimonials & Reviews</h4>
        <p class="text-muted mb-0">Manage client feedback and social proof</p>
    </div>
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Testimonial</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="display-6 fw-bold text-primary">{{ $stats['total'] }}</div>
            <small class="text-muted">Total</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="display-6 fw-bold text-success">{{ $stats['approved'] }}</div>
            <small class="text-muted">Approved</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="display-6 fw-bold text-warning">{{ $stats['pending'] }}</div>
            <small class="text-muted">Pending</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="display-6 fw-bold text-info">{{ $stats['avg_rating'] ? number_format($stats['avg_rating'], 1) : '—' }}</div>
            <small class="text-muted">Avg Rating</small>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@if($testimonials->isEmpty())
<div class="card border-0 shadow-sm text-center py-5">
    <div class="card-body">
        <i class="bi bi-chat-quote display-1 text-muted"></i>
        <h5 class="mt-3">No Testimonials Yet</h5>
        <p class="text-muted">Add client reviews to build social proof on your site.</p>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">Add First Testimonial</a>
    </div>
</div>
@else
<div class="row g-4">
    @foreach($testimonials as $t)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    @if($t->client_photo)
                    <img src="{{ $t->client_photo }}" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">
                    @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#e2e8f0;"><i class="bi bi-person text-muted"></i></div>
                    @endif
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $t->client_name }}</h6>
                        <small class="text-muted">{{ $t->client_designation }}{{ $t->client_company ? ' at ' . $t->client_company : '' }}</small>
                    </div>
                    <span class="badge bg-{{ $t->status === 'approved' ? 'success' : ($t->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($t->status) }}</span>
                </div>
                <div class="mb-2">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $t->rating ? '-fill' : '' }} text-warning"></i>
                    @endfor
                </div>
                <p class="mb-3">{{ Str::limit($t->review, 200) }}</p>
                @if($t->video_url)
                <p class="mb-2"><i class="bi bi-camera-video text-primary"></i> <a href="{{ $t->video_url }}" target="_blank">Video Testimonial</a></p>
                @endif
                <div class="d-flex gap-2">
                    @if($t->status === 'pending')
                    <form action="{{ route('admin.testimonials.approve', $t) }}" method="POST">@csrf @method('PATCH')<button class="btn btn-sm btn-success"><i class="bi bi-check"></i> Approve</button></form>
                    <form action="{{ route('admin.testimonials.reject', $t) }}" method="POST">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-x"></i> Reject</button></form>
                    @endif
                    <a href="{{ route('admin.testimonials.edit', $t) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
