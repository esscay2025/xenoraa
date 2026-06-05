@extends('layouts.admin')
@section('title', 'Appointments')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Appointment Booking</h4>
        <p class="text-muted mb-0">Manage your appointments and bookings</p>
    </div>
    <a href="{{ route('admin.appointments.slots') }}" class="btn btn-primary"><i class="bi bi-clock"></i> Manage Slots</a>
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
            <div class="display-6 fw-bold text-success">{{ $stats['upcoming'] }}</div>
            <small class="text-muted">Upcoming</small>
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
            <div class="display-6 fw-bold text-info">{{ $stats['completed'] }}</div>
            <small class="text-muted">Completed</small>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@if($appointments->isEmpty())
<div class="card border-0 shadow-sm text-center py-5">
    <div class="card-body">
        <i class="bi bi-calendar-event display-1 text-muted"></i>
        <h5 class="mt-3">No Appointments Yet</h5>
        <p class="text-muted">Set up your availability slots so clients can book appointments.</p>
        <a href="{{ route('admin.appointments.slots') }}" class="btn btn-primary">Set Up Availability</a>
    </div>
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Client</th>
                    <th>Date & Time</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $a)
                <tr>
                    <td>
                        <strong>{{ $a->client_name }}</strong><br>
                        <small class="text-muted">{{ $a->client_email }}</small>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($a->appointment_date)->format('M d, Y') }}<br>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($a->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($a->end_time)->format('h:i A') }}</small>
                    </td>
                    <td>{{ $a->service_type ?? '—' }}</td>
                    <td>
                        @php
                            $colors = ['pending' => 'warning', 'confirmed' => 'primary', 'completed' => 'success', 'cancelled' => 'danger', 'no_show' => 'secondary'];
                        @endphp
                        <span class="badge bg-{{ $colors[$a->status] ?? 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $a->status)) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.appointments.show', $a) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        <form action="{{ route('admin.appointments.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
