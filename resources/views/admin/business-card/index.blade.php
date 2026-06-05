@extends('layouts.admin')
@section('title', 'Digital Business Card')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Digital Business Card</h4>
        <p class="text-muted mb-0">Create your shareable digital business card with QR code</p>
    </div>
    @if($card)
    <div class="d-flex gap-2">
        <a href="{{ route('admin.business-card.vcard') }}" class="btn btn-outline-primary"><i class="bi bi-download"></i> Download vCard</a>
        @if($card->whatsapp)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $card->whatsapp) }}?text={{ urlencode('Hi! Here is my digital business card: ' . url('/' . auth()->user()->username . '/card')) }}" target="_blank" class="btn btn-success"><i class="bi bi-whatsapp"></i> Share</a>
        @endif
    </div>
    @endif
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.business-card.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h6 class="text-primary mb-3"><i class="bi bi-person"></i> Personal Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Display Name <span class="text-danger">*</span></label>
                            <input type="text" name="display_name" class="form-control" value="{{ old('display_name', $card->display_name ?? auth()->user()->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Designation / Job Title</label>
                            <input type="text" name="designation" class="form-control" value="{{ old('designation', $card->designation ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <input type="text" name="company" class="form-control" value="{{ old('company', $card->company ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $card->email ?? auth()->user()->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $card->phone ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $card->whatsapp ?? '') }}" placeholder="+91 98765 43210">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control" value="{{ old('website', $card->website ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Theme Color</label>
                            <input type="color" name="theme_color" class="form-control form-control-color" value="{{ old('theme_color', $card->theme_color ?? '#6366f1') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $card->address ?? '') }}</textarea>
                        </div>
                    </div>

                    <h6 class="text-primary mb-3"><i class="bi bi-share"></i> Social Links</h6>
                    @php $social = $card && $card->social_links ? (is_array($card->social_links) ? $card->social_links : json_decode($card->social_links, true)) : []; @endphp
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-linkedin text-primary"></i> LinkedIn</label>
                            <input type="url" name="linkedin" class="form-control" value="{{ old('linkedin', $social['linkedin'] ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-twitter-x"></i> Twitter / X</label>
                            <input type="url" name="twitter" class="form-control" value="{{ old('twitter', $social['twitter'] ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-instagram text-danger"></i> Instagram</label>
                            <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $social['instagram'] ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-facebook text-primary"></i> Facebook</label>
                            <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $social['facebook'] ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-youtube text-danger"></i> YouTube</label>
                            <input type="url" name="youtube" class="form-control" value="{{ old('youtube', $social['youtube'] ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-github"></i> GitHub</label>
                            <input type="url" name="github" class="form-control" value="{{ old('github', $social['github'] ?? '') }}">
                        </div>
                    </div>

                    <h6 class="text-primary mb-3"><i class="bi bi-image"></i> Photos</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Profile Photo</label>
                            @if($card && $card->photo)
                            <div class="mb-2"><img src="{{ $card->photo }}" class="rounded-circle" style="width:60px;height:60px;object-fit:cover;"></div>
                            @endif
                            <input type="file" name="photo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Logo</label>
                            @if($card && $card->logo)
                            <div class="mb-2"><img src="{{ $card->logo }}" style="height:40px;"></div>
                            @endif
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Business Card</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        @if($card)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-qr-code"></i> QR Code</h6></div>
            <div class="card-body text-center">
                <div class="p-3 bg-white rounded border d-inline-block mb-3">
                    {!! $qrSvg ?? '<p class="text-muted">QR code will appear here</p>' !!}
                </div>
                <p class="text-muted small">Scan to view your digital business card</p>
                <div class="d-grid gap-2">
                    <a href="{{ url('/' . auth()->user()->username . '/card') }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-box-arrow-up-right"></i> View Public Card</a>
                    <button class="btn btn-outline-secondary btn-sm" onclick="navigator.clipboard.writeText('{{ url('/' . auth()->user()->username . '/card') }}')"><i class="bi bi-clipboard"></i> Copy Link</button>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-transparent"><h6 class="mb-0">Preview</h6></div>
            <div class="card-body text-center" style="background: {{ $card->theme_color ?? '#6366f1' }}; border-radius: 0 0 0.5rem 0.5rem;">
                @if($card->photo)
                <img src="{{ $card->photo }}" class="rounded-circle border border-3 border-white mb-2" style="width:80px;height:80px;object-fit:cover;">
                @endif
                <h6 class="text-white mb-0">{{ $card->display_name }}</h6>
                <small class="text-white-50">{{ $card->designation }}</small>
                @if($card->company)
                <p class="text-white-50 small mb-0">{{ $card->company }}</p>
                @endif
            </div>
        </div>
        @else
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="bi bi-person-vcard display-1 text-muted"></i>
                <p class="text-muted mt-3">Fill in your details to generate your digital business card and QR code.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
