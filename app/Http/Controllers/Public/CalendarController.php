<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\UserNote;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────
    private function userId(): ?int
    {
        return auth()->id();
    }

    private function sessionId(): string
    {
        return session()->getId();
    }

    private function eventQuery()
    {
        $q = CalendarEvent::query();
        if ($this->userId()) {
            $q->where('user_id', $this->userId());
        } else {
            $q->whereNull('user_id')->where('session_id', $this->sessionId());
        }
        return $q;
    }

    private function noteQuery()
    {
        $q = UserNote::query();
        if ($this->userId()) {
            $q->where('user_id', $this->userId());
        } else {
            $q->whereNull('user_id')->where('session_id', $this->sessionId());
        }
        return $q;
    }

    // ─────────────────────────────────────────────
    // Calendar Index Page
    // ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $year  = (int) $request->get('year',  now()->year);
        $month = (int) $request->get('month', now()->month);

        // Clamp to valid range
        $month = max(1, min(12, $month));
        $year  = max(2020, min(2035, $year));

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth   = Carbon::create($year, $month, 1)->endOfMonth();

        $events = $this->eventQuery()
            ->whereBetween('event_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->get()
            ->groupBy(fn($e) => $e->event_date->format('Y-m-d'));

        $notes = $this->noteQuery()
            ->orderByDesc('is_pinned')
            ->orderByDesc('updated_at')
            ->get();

        $prevMonth = Carbon::create($year, $month, 1)->subMonth();
        $nextMonth = Carbon::create($year, $month, 1)->addMonth();

        return view('portfolio.calendar', compact(
            'year', 'month', 'startOfMonth', 'endOfMonth',
            'events', 'notes', 'prevMonth', 'nextMonth'
        ));
    }

    // ─────────────────────────────────────────────
    // Calendar Events CRUD
    // ─────────────────────────────────────────────
    public function storeEvent(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'event_date'  => 'required|date',
            'event_time'  => 'nullable|date_format:H:i',
            'color'       => 'nullable|in:blue,green,red,yellow,purple',
            'is_reminder' => 'nullable|boolean',
        ]);

        $data['user_id']    = $this->userId();
        $data['session_id'] = $this->sessionId();
        $data['color']      = $data['color'] ?? 'blue';

        CalendarEvent::create($data);

        return back()->with('success', 'Event added successfully!');
    }

    public function updateEvent(Request $request, CalendarEvent $event)
    {
        $this->authorizeOwner($event);

        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'event_date'  => 'required|date',
            'event_time'  => 'nullable|date_format:H:i',
            'color'       => 'nullable|in:blue,green,red,yellow,purple',
            'is_reminder' => 'nullable|boolean',
        ]);

        $event->update($data);

        return back()->with('success', 'Event updated!');
    }

    public function destroyEvent(CalendarEvent $event)
    {
        $this->authorizeOwner($event);
        $event->delete();
        return back()->with('success', 'Event deleted.');
    }

    // ─────────────────────────────────────────────
    // Notes CRUD
    // ─────────────────────────────────────────────
    public function storeNote(Request $request)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:200',
            'content'   => 'nullable|string|max:5000',
            'color'     => 'nullable|in:default,yellow,blue,green,pink',
            'is_pinned' => 'nullable|boolean',
        ]);

        $data['user_id']    = $this->userId();
        $data['session_id'] = $this->sessionId();
        $data['color']      = $data['color'] ?? 'default';

        UserNote::create($data);

        return back()->with('success', 'Note saved!');
    }

    public function updateNote(Request $request, UserNote $note)
    {
        $this->authorizeOwner($note);

        $data = $request->validate([
            'title'     => 'required|string|max:200',
            'content'   => 'nullable|string|max:5000',
            'color'     => 'nullable|in:default,yellow,blue,green,pink',
            'is_pinned' => 'nullable|boolean',
        ]);

        $note->update($data);

        return back()->with('success', 'Note updated!');
    }

    public function destroyNote(UserNote $note)
    {
        $this->authorizeOwner($note);
        $note->delete();
        return back()->with('success', 'Note deleted.');
    }

    public function togglePin(UserNote $note)
    {
        $this->authorizeOwner($note);
        $note->update(['is_pinned' => !$note->is_pinned]);
        return back();
    }

    // ─────────────────────────────────────────────
    // Ownership guard
    // ─────────────────────────────────────────────
    private function authorizeOwner($model)
    {
        if ($this->userId()) {
            if ($model->user_id !== $this->userId()) {
                abort(403);
            }
        } else {
            if ($model->session_id !== $this->sessionId()) {
                abort(403);
            }
        }
    }
}
