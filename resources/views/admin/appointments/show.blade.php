@extends('layouts.admin')
@section('title', 'Appointment Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Appointment Details</h4>
    <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @php
                    $colors = ['pending' => 'warning', 'confirmed' => 'primary', 'completed' => 'success', 'cancelled' => 'danger', 'no_show' => 'secondary'];
                @endphp
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="mb-1">{{ $appointment->client_name }}</h5>
                        <p class="text-muted mb-0">{{ $appointment->client_email }}</p>
                        @if($appointment->client_phone)
                        <p class="text-muted mb-0"><i class="bi bi-telephone"></i> {{ $appointment->client_phone }}</p>
                        @endif
                    </div>
                    <span class="badge bg-{{ $colors[$appointment->status] ?? 'secondary' }} fs-6">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block">Date</small>
                            <strong><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, M d, Y') }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block">Time</small>
                            <strong><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</strong>
                        </div>
                    </div>
                    @if($appointment->service_type)
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block">Service</small>
                            <strong><i class="bi bi-briefcase"></i> {{ $appointment->service_type }}</strong>
                        </div>
                    </div>
                    @endif
                    @if($appointment->meeting_link)
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block">Meeting Link</small>
                            <a href="{{ $appointment->meeting_link }}" target="_blank" class="text-primary"><i class="bi bi-camera-video"></i> Join Meeting</a>
                        </div>
                    </div>
                    @endif
                </div>

                @if($appointment->notes)
                <div class="mb-4">
                    <h6>Client Notes</h6>
                    <div class="p-3 bg-light rounded">{{ $appointment->notes }}</div>
                </div>
                @endif

                <div class="text-muted small">
                    <i class="bi bi-clock-history"></i> Booked on {{ $appointment->created_at->format('M d, Y h:i A') }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><h6 class="mb-0">Update Status</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.appointments.status', $appointment) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="no_show" {{ $appointment->status === 'no_show' ? 'selected' : '' }}>No Show</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meeting Link</label>
                        <input type="url" name="meeting_link" class="form-control" value="{{ $appointment->meeting_link }}" placeholder="https://meet.google.com/...">
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg"></i> Update</button>
                </form>
            </div>
        </div>
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-body text-center">
                <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Delete Appointment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
