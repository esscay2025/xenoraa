<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    private function tenantId()
    {
        return auth()->user()->getTenantId();
    }

    public function index()
    {
        $appointments = Appointment::where('user_id', $this->tenantId())
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->get();
        $stats = [
            'total' => $appointments->count(),
            'upcoming' => $appointments->where('status', 'confirmed')->where('appointment_date', '>=', now()->toDateString())->count(),
            'pending' => $appointments->where('status', 'pending')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
        ];
        return view('admin.appointments.index', compact('appointments', 'stats'));
    }

    public function slots()
    {
        $slots = AppointmentSlot::where('user_id', $this->tenantId())
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
        return view('admin.appointments.slots', compact('slots'));
    }

    public function storeSlot(Request $request)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'duration_minutes' => 'required|integer|min:15|max:120',
        ]);

        $validated['user_id'] = $this->tenantId();
        AppointmentSlot::create($validated);

        return back()->with('success', 'Slot added successfully.');
    }

    public function destroySlot(AppointmentSlot $slot)
    {
        abort_if($slot->user_id !== $this->tenantId(), 403);
        $slot->delete();
        return back()->with('success', 'Slot removed.');
    }

    public function toggleSlot(AppointmentSlot $slot)
    {
        abort_if($slot->user_id !== $this->tenantId(), 403);
        $slot->update(['is_active' => !$slot->is_active]);
        return back()->with('success', 'Slot ' . ($slot->is_active ? 'activated' : 'deactivated') . '.');
    }

    public function show(Appointment $appointment)
    {
        abort_if($appointment->user_id !== $this->tenantId(), 403);
        return view('admin.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        abort_if($appointment->user_id !== $this->tenantId(), 403);
        $request->validate(['status' => 'required|in:pending,confirmed,completed,cancelled,no_show']);
        $appointment->update([
            'status' => $request->status,
            'meeting_link' => $request->meeting_link ?? $appointment->meeting_link,
        ]);
        return back()->with('success', 'Appointment status updated.');
    }

    public function destroy(Appointment $appointment)
    {
        abort_if($appointment->user_id !== $this->tenantId(), 403);
        $appointment->delete();
        return redirect()->route('admin.appointments.index')->with('success', 'Appointment deleted.');
    }
}
