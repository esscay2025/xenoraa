@extends('layouts.admin')
@section('title', 'Availability Slots')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Availability Slots</h4>
        <p class="text-muted mb-0">Set your weekly availability for client bookings</p>
    </div>
    <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Appointments</a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Slot</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.appointments.slots.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Day of Week</label>
                        <select name="day_of_week" class="form-select" required>
                            <option value="0">Sunday</option>
                            <option value="1" selected>Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                            <option value="6">Saturday</option>
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" class="form-control" value="09:00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" class="form-control" value="10:00" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Session Duration (minutes)</label>
                        <select name="duration_minutes" class="form-select">
                            <option value="15">15 minutes</option>
                            <option value="30" selected>30 minutes</option>
                            <option value="45">45 minutes</option>
                            <option value="60">60 minutes</option>
                            <option value="90">90 minutes</option>
                            <option value="120">2 hours</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 w-100"><i class="bi bi-plus-lg"></i> Add Slot</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-calendar-week"></i> Weekly Schedule</h6></div>
            <div class="card-body p-0">
                @php
                    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    $grouped = $slots->groupBy('day_of_week');
                @endphp
                @if($slots->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-clock display-4 text-muted"></i>
                    <p class="text-muted mt-2">No slots configured yet. Add your first availability slot.</p>
                </div>
                @else
                <div class="list-group list-group-flush">
                    @foreach($days as $dayIndex => $dayName)
                        @if(isset($grouped[$dayIndex]))
                        <div class="list-group-item">
                            <h6 class="text-primary mb-2"><i class="bi bi-calendar3"></i> {{ $dayName }}</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($grouped[$dayIndex] as $slot)
                                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill {{ $slot->is_active ? 'bg-success bg-opacity-10 border border-success' : 'bg-secondary bg-opacity-10 border border-secondary' }}">
                                    <small class="{{ $slot->is_active ? 'text-success' : 'text-secondary' }}">
                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                        ({{ $slot->duration_minutes }}min)
                                    </small>
                                    <form action="{{ route('admin.appointments.slots.toggle', $slot) }}" method="POST" class="d-inline">@csrf @method('PATCH')
                                        <button class="btn btn-sm p-0 border-0 {{ $slot->is_active ? 'text-success' : 'text-secondary' }}" title="{{ $slot->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-{{ $slot->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.appointments.slots.destroy', $slot) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove slot?')">@csrf @method('DELETE')
                                        <button class="btn btn-sm p-0 border-0 text-danger"><i class="bi bi-x-circle"></i></button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
