<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\UserNote;
use App\Models\User;
use Illuminate\Http\Request;

class CalendarAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = CalendarEvent::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('month')) {
            $query->whereMonth('event_date', date('m', strtotime($request->month)))
                  ->whereYear('event_date', date('Y', strtotime($request->month)));
        }

        $events = $query->orderBy('event_date')->paginate(30);

        $notes = UserNote::with('user')->orderByDesc('created_at')->limit(20)->get();

        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        $stats = [
            'total_events' => CalendarEvent::count(),
            'total_notes'  => UserNote::count(),
            'users_active' => CalendarEvent::distinct('user_id')->count('user_id'),
            'upcoming'     => CalendarEvent::where('event_date', '>=', now())->count(),
        ];

        return view('admin.calendar.index', compact('events', 'notes', 'users', 'stats'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'event_date' => 'required|date',
            'user_id'    => 'nullable|exists:users,id',
        ]);

        CalendarEvent::create([
            'user_id'     => $request->user_id ?: auth()->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'event_date'  => $request->event_date,
            'event_time'  => $request->event_time,
            'color'       => $request->color ?? '#3b82f6',
            'is_reminder' => $request->boolean('is_reminder'),
        ]);

        return back()->with('success', 'Event added successfully.');
    }

    public function storeNote(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        UserNote::create([
            'user_id'   => $request->user_id ?: auth()->id(),
            'title'     => $request->title,
            'content'   => $request->content,
            'color'     => $request->color ?? '#f59e0b',
            'is_pinned' => $request->boolean('is_pinned'),
        ]);

        return back()->with('success', 'Note added successfully.');
    }

    public function destroy(CalendarEvent $event)
    {
        $event->delete();
        return back()->with('success', 'Calendar event deleted.');
    }

    public function destroyNote(UserNote $note)
    {
        $note->delete();
        return back()->with('success', 'Note deleted.');
    }
}
