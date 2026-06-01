@extends('layouts.app')
@section('title', 'Calendar & Notes | Gopi K')
@section('description', 'Your personal calendar and notes workspace. Add reminders, events, and notes — no account required.')
@push('styles')
<style>
    /* ── PAGE LAYOUT ── */
    .cal-page { max-width: 1300px; margin: 0 auto; padding: 2rem 1.5rem 4rem; }
    .cal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
    .cal-header h1 { font-size: 1.75rem; font-weight: 800; margin: 0; }
    .cal-header p { color: var(--text-secondary); font-size: 0.9rem; margin: 0.25rem 0 0; }
    .cal-layout { display: grid; grid-template-columns: 1fr 340px; gap: 2rem; }

    /* ── CALENDAR ── */
    .cal-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
    .cal-nav { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); }
    .cal-nav h2 { font-size: 1.25rem; font-weight: 700; margin: 0; }
    .cal-nav-btns { display: flex; gap: 0.5rem; }
    .cal-nav-btn { background: var(--bg-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.4rem 0.75rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; text-decoration: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.3rem; }
    .cal-nav-btn:hover { background: var(--bg-hover); }
    .cal-nav-btn.today { background: var(--text-primary); color: var(--bg-primary); border-color: var(--text-primary); }
    .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); }
    .cal-day-header { text-align: center; padding: 0.75rem 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); }
    .cal-cell { min-height: 100px; padding: 0.5rem; border-right: 1px solid var(--border); border-bottom: 1px solid var(--border); position: relative; cursor: pointer; transition: background 0.15s; }
    .cal-cell:hover { background: var(--bg-hover); }
    .cal-cell:nth-child(7n) { border-right: none; }
    .cal-cell.other-month .cal-date { color: var(--text-muted); opacity: 0.4; }
    .cal-cell.today-cell { background: rgba(255,255,255,0.04); }
    .cal-date { font-size: 0.85rem; font-weight: 600; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin-bottom: 0.25rem; }
    .cal-cell.today-cell .cal-date { background: var(--text-primary); color: var(--bg-primary); }
    .cal-event-chip { font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 4px; margin-bottom: 0.15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer; }
    .chip-blue   { background: rgba(59,130,246,0.25); color: #93c5fd; }
    .chip-green  { background: rgba(34,197,94,0.25);  color: #86efac; }
    .chip-red    { background: rgba(239,68,68,0.25);  color: #fca5a5; }
    .chip-yellow { background: rgba(245,158,11,0.25); color: #fcd34d; }
    .chip-purple { background: rgba(168,85,247,0.25); color: #d8b4fe; }
    .cal-more { font-size: 0.7rem; color: var(--text-muted); }

    /* ── RIGHT PANEL ── */
    .right-panel { display: flex; flex-direction: column; gap: 1.5rem; }

    /* ── ADD EVENT FORM ── */
    .panel-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
    .panel-card-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    .panel-card-header h3 { font-size: 1rem; font-weight: 700; margin: 0; }
    .panel-card-body { padding: 1.25rem; }
    .color-picker { display: flex; gap: 0.5rem; margin-top: 0.25rem; }
    .color-dot { width: 24px; height: 24px; border-radius: 50%; cursor: pointer; border: 2px solid transparent; transition: transform 0.15s, border-color 0.15s; }
    .color-dot:hover, .color-dot.selected { transform: scale(1.2); border-color: white; }
    .color-dot.c-blue   { background: #3b82f6; }
    .color-dot.c-green  { background: #22c55e; }
    .color-dot.c-red    { background: #ef4444; }
    .color-dot.c-yellow { background: #f59e0b; }
    .color-dot.c-purple { background: #a855f7; }

    /* ── NOTES ── */
    .notes-list { display: flex; flex-direction: column; gap: 0.75rem; max-height: 500px; overflow-y: auto; padding-right: 0.25rem; }
    .note-card { border-radius: 10px; padding: 1rem; border: 1px solid var(--border); position: relative; }
    .note-card.nc-default { background: var(--bg-secondary); }
    .note-card.nc-yellow  { background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.3); }
    .note-card.nc-blue    { background: rgba(59,130,246,0.08); border-color: rgba(59,130,246,0.3); }
    .note-card.nc-green   { background: rgba(34,197,94,0.08);  border-color: rgba(34,197,94,0.3); }
    .note-card.nc-pink    { background: rgba(236,72,153,0.08); border-color: rgba(236,72,153,0.3); }
    .note-title { font-size: 0.9rem; font-weight: 700; margin: 0 0 0.25rem; }
    .note-content { font-size: 0.8rem; color: var(--text-secondary); margin: 0; white-space: pre-wrap; }
    .note-actions { display: flex; gap: 0.4rem; margin-top: 0.5rem; }
    .note-btn { background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 0.75rem; padding: 0.2rem 0.4rem; border-radius: 4px; transition: all 0.15s; }
    .note-btn:hover { background: var(--bg-hover); color: var(--text-primary); }
    .note-pin-badge { font-size: 0.65rem; background: rgba(255,255,255,0.1); padding: 0.1rem 0.4rem; border-radius: 10px; color: var(--text-muted); }

    /* ── MODAL ── */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 5000; display: none; align-items: center; justify-content: center; padding: 1rem; }
    .modal-overlay.open { display: flex; }
    .modal-box { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; width: 100%; max-width: 480px; max-height: 90vh; overflow-y: auto; }
    .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); }
    .modal-header h3 { font-size: 1.1rem; font-weight: 700; margin: 0; }
    .modal-close { background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.2rem; padding: 0.25rem; }
    .modal-body { padding: 1.5rem; }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
        .cal-layout { grid-template-columns: 1fr; }
        .cal-cell { min-height: 70px; }
    }
    @media (max-width: 600px) {
        .cal-cell { min-height: 50px; padding: 0.25rem; }
        .cal-event-chip { display: none; }
        .cal-date { font-size: 0.75rem; width: 24px; height: 24px; }
    }
</style>
@endpush
@section('content')
<div class="cal-page">
    <div class="cal-header">
        <div>
            <h1><i class="fas fa-calendar-alt" style="margin-right:0.5rem;opacity:0.8;"></i>Calendar & Notes</h1>
            <p>Your personal workspace. Add events, set reminders, and keep notes — all saved privately for you.</p>
        </div>
        @guest
        <div class="alert alert-info" style="margin:0;padding:0.6rem 1rem;font-size:0.85rem;background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.3);color:#93c5fd;border-radius:8px;">
            <i class="fas fa-info-circle"></i> You are using this as a guest. <a href="{{ route('register') }}" style="color:#93c5fd;font-weight:600;">Sign up</a> to save your data permanently.
        </div>
        @endguest
    </div>

    <div class="cal-layout">
        {{-- ── CALENDAR ── --}}
        <div>
            <div class="cal-card">
                <div class="cal-nav">
                    <div>
                        <h2>{{ Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</h2>
                    </div>
                    <div class="cal-nav-btns">
                        <a href="{{ route('calendar.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" class="cal-nav-btn">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <a href="{{ route('calendar.index') }}" class="cal-nav-btn today">Today</a>
                        <a href="{{ route('calendar.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" class="cal-nav-btn">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <div class="cal-grid">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                        <div class="cal-day-header">{{ $day }}</div>
                    @endforeach

                    @php
                        $today = now()->toDateString();
                        $firstDay = $startOfMonth->copy();
                        $startPad = $firstDay->dayOfWeek; // 0=Sun
                        $totalDays = $endOfMonth->day;
                        $prevMonthDays = $startOfMonth->copy()->subMonth()->daysInMonth;
                    @endphp

                    {{-- Padding from previous month --}}
                    @for($i = $startPad - 1; $i >= 0; $i--)
                        @php $d = $prevMonthDays - $i; @endphp
                        <div class="cal-cell other-month">
                            <div class="cal-date">{{ $d }}</div>
                        </div>
                    @endfor

                    {{-- Current month days --}}
                    @for($day = 1; $day <= $totalDays; $day++)
                        @php
                            $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $day);
                            $isToday = $dateKey === $today;
                            $dayEvents = $events->get($dateKey, collect());
                        @endphp
                        <div class="cal-cell {{ $isToday ? 'today-cell' : '' }}"
                             onclick="openAddEvent('{{ $dateKey }}')"
                             title="Click to add event on {{ $dateKey }}">
                            <div class="cal-date">{{ $day }}</div>
                            @foreach($dayEvents->take(3) as $ev)
                                <div class="cal-event-chip chip-{{ $ev->color }}"
                                     onclick="event.stopPropagation(); openViewEvent({{ $ev->id }}, '{{ addslashes($ev->title) }}', '{{ addslashes($ev->description ?? '') }}', '{{ $ev->event_date->format('Y-m-d') }}', '{{ $ev->event_time ?? '' }}', '{{ $ev->color }}')">
                                    {{ $ev->title }}
                                </div>
                            @endforeach
                            @if($dayEvents->count() > 3)
                                <div class="cal-more">+{{ $dayEvents->count() - 3 }} more</div>
                            @endif
                        </div>
                    @endfor

                    {{-- Padding for next month --}}
                    @php
                        $cellsFilled = $startPad + $totalDays;
                        $remaining = (7 - ($cellsFilled % 7)) % 7;
                    @endphp
                    @for($i = 1; $i <= $remaining; $i++)
                        <div class="cal-cell other-month">
                            <div class="cal-date">{{ $i }}</div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- ── RIGHT PANEL ── --}}
        <div class="right-panel">
            {{-- Add Event Form --}}
            <div class="panel-card">
                <div class="panel-card-header">
                    <h3><i class="fas fa-plus-circle" style="margin-right:0.4rem;"></i>Add Event</h3>
                </div>
                <div class="panel-card-body">
                    <form method="POST" action="{{ route('calendar.events.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control" placeholder="Meeting, Birthday, Deadline..." required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date *</label>
                            <input type="date" name="event_date" class="form-control" value="{{ now()->toDateString() }}" required id="quickEventDate">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Time (optional)</label>
                            <input type="time" name="event_time" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Optional details..."></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Color</label>
                            <div class="color-picker">
                                @foreach(['blue','green','red','yellow','purple'] as $c)
                                    <div class="color-dot c-{{ $c }} {{ $c === 'blue' ? 'selected' : '' }}"
                                         onclick="selectColor(this, '{{ $c }}', 'event_color')"
                                         title="{{ ucfirst($c) }}"></div>
                                @endforeach
                            </div>
                            <input type="hidden" name="color" id="event_color" value="blue">
                        </div>
                        <div class="form-group" style="display:flex;align-items:center;gap:0.5rem;">
                            <input type="checkbox" name="is_reminder" id="is_reminder" value="1" style="width:auto;">
                            <label for="is_reminder" class="form-label" style="margin:0;cursor:pointer;">Set as reminder</label>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                            <i class="fas fa-calendar-plus"></i> Add Event
                        </button>
                    </form>
                </div>
            </div>

            {{-- Notes Panel --}}
            <div class="panel-card">
                <div class="panel-card-header">
                    <h3><i class="fas fa-sticky-note" style="margin-right:0.4rem;"></i>Notes</h3>
                    <button class="btn btn-outline btn-sm" onclick="document.getElementById('addNoteForm').classList.toggle('hidden')">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
                <div class="panel-card-body">
                    {{-- Add Note Form --}}
                    <div id="addNoteForm" class="hidden" style="margin-bottom:1.25rem;padding-bottom:1.25rem;border-bottom:1px solid var(--border);">
                        <form method="POST" action="{{ route('calendar.notes.store') }}">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Title *</label>
                                <input type="text" name="title" class="form-control" placeholder="Note title..." required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Content</label>
                                <textarea name="content" class="form-control" rows="3" placeholder="Write your note..."></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Color</label>
                                <div class="color-picker">
                                    @foreach(['default','yellow','blue','green','pink'] as $c)
                                        <div class="color-dot c-{{ $c === 'default' ? 'blue' : $c }} {{ $c === 'default' ? 'selected' : '' }}"
                                             onclick="selectColor(this, '{{ $c }}', 'note_color')"
                                             title="{{ ucfirst($c) }}"
                                             style="{{ $c === 'default' ? 'background: var(--bg-secondary);' : '' }}"></div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="color" id="note_color" value="default">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">
                                <i class="fas fa-save"></i> Save Note
                            </button>
                        </form>
                    </div>

                    {{-- Notes List --}}
                    @if($notes->isEmpty())
                        <p class="text-secondary text-sm" style="text-align:center;padding:1rem 0;">No notes yet. Click "Add" to create your first note.</p>
                    @else
                        <div class="notes-list">
                            @foreach($notes as $note)
                            <div class="note-card nc-{{ $note->color }}">
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.5rem;">
                                    <p class="note-title">
                                        @if($note->is_pinned)<span class="note-pin-badge"><i class="fas fa-thumbtack"></i> Pinned</span> @endif
                                        {{ $note->title }}
                                    </p>
                                </div>
                                @if($note->content)
                                    <p class="note-content">{{ Str::limit($note->content, 120) }}</p>
                                @endif
                                <div class="note-actions">
                                    <form method="POST" action="{{ route('calendar.notes.pin', $note) }}" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="note-btn" title="{{ $note->is_pinned ? 'Unpin' : 'Pin' }}">
                                            <i class="fas fa-thumbtack"></i>
                                        </button>
                                    </form>
                                    <button class="note-btn" onclick="openEditNote({{ $note->id }}, '{{ addslashes($note->title) }}', '{{ addslashes($note->content ?? '') }}', '{{ $note->color }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('calendar.notes.destroy', $note) }}" style="display:inline;" onsubmit="return confirm('Delete this note?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="note-btn" style="color:#fca5a5;" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <span class="text-muted text-xs" style="margin-left:auto;">{{ $note->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── ADD EVENT MODAL ── --}}
<div class="modal-overlay" id="addEventModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-calendar-plus" style="margin-right:0.5rem;"></i>Add Event</h3>
            <button class="modal-close" onclick="closeModal('addEventModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('calendar.events.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" placeholder="Event title..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Date *</label>
                    <input type="date" name="event_date" class="form-control" id="modalEventDate" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Time (optional)</label>
                    <input type="time" name="event_time" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Optional notes about this event..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Color</label>
                    <div class="color-picker">
                        @foreach(['blue','green','red','yellow','purple'] as $c)
                            <div class="color-dot c-{{ $c }} {{ $c === 'blue' ? 'selected' : '' }}"
                                 onclick="selectColor(this, '{{ $c }}', 'modal_event_color')"
                                 title="{{ ucfirst($c) }}"></div>
                        @endforeach
                    </div>
                    <input type="hidden" name="color" id="modal_event_color" value="blue">
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1.25rem;">
                    <input type="checkbox" name="is_reminder" id="modal_is_reminder" value="1" style="width:auto;">
                    <label for="modal_is_reminder" class="form-label" style="margin:0;cursor:pointer;">Set as reminder</label>
                </div>
                <div style="display:flex;gap:0.75rem;">
                    <button type="button" class="btn btn-outline" style="flex:1;justify-content:center;" onclick="closeModal('addEventModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;"><i class="fas fa-plus"></i> Add Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── VIEW/DELETE EVENT MODAL ── --}}
<div class="modal-overlay" id="viewEventModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="viewEventTitle">Event Details</h3>
            <button class="modal-close" onclick="closeModal('viewEventModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p class="text-secondary text-sm" id="viewEventDate"></p>
            <p id="viewEventDesc" style="margin:1rem 0;color:var(--text-secondary);"></p>
            <div style="display:flex;gap:0.75rem;margin-top:1.5rem;">
                <button type="button" class="btn btn-outline" style="flex:1;justify-content:center;" onclick="closeModal('viewEventModal')">Close</button>
                <form method="POST" id="deleteEventForm" style="flex:1;" onsubmit="return confirm('Delete this event?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;"><i class="fas fa-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ── EDIT NOTE MODAL ── --}}
<div class="modal-overlay" id="editNoteModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Note</h3>
            <button class="modal-close" onclick="closeModal('editNoteModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editNoteForm">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" id="editNoteTitle" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Content</label>
                    <textarea name="content" id="editNoteContent" class="form-control" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Color</label>
                    <div class="color-picker" id="editNoteColorPicker">
                        @foreach(['default','yellow','blue','green','pink'] as $c)
                            <div class="color-dot c-{{ $c === 'default' ? 'blue' : $c }}"
                                 onclick="selectColor(this, '{{ $c }}', 'edit_note_color')"
                                 title="{{ ucfirst($c) }}"
                                 style="{{ $c === 'default' ? 'background: var(--bg-secondary);' : '' }}"
                                 data-color="{{ $c }}"></div>
                        @endforeach
                    </div>
                    <input type="hidden" name="color" id="edit_note_color" value="default">
                </div>
                <div style="display:flex;gap:0.75rem;">
                    <button type="button" class="btn btn-outline" style="flex:1;justify-content:center;" onclick="closeModal('editNoteModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ── Color picker
    function selectColor(el, color, inputId) {
        el.closest('.color-picker').querySelectorAll('.color-dot').forEach(d => d.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById(inputId).value = color;
    }

    // ── Modal helpers
    function openModal(id) { document.getElementById(id).classList.add('open'); }
    function closeModal(id) { document.getElementById(id).classList.remove('open'); }

    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    // ── Open add event modal with pre-filled date
    function openAddEvent(dateStr) {
        document.getElementById('modalEventDate').value = dateStr;
        // Reset color
        document.querySelectorAll('#addEventModal .color-dot').forEach(d => d.classList.remove('selected'));
        document.querySelector('#addEventModal .color-dot.c-blue').classList.add('selected');
        document.getElementById('modal_event_color').value = 'blue';
        openModal('addEventModal');
    }

    // ── Open view/delete event modal
    function openViewEvent(id, title, desc, date, time, color) {
        document.getElementById('viewEventTitle').textContent = title;
        document.getElementById('viewEventDate').textContent = date + (time ? ' at ' + time : '');
        document.getElementById('viewEventDesc').textContent = desc || 'No description.';
        document.getElementById('deleteEventForm').action = '/calendar/events/' + id;
        openModal('viewEventModal');
    }

    // ── Open edit note modal
    function openEditNote(id, title, content, color) {
        document.getElementById('editNoteTitle').value = title;
        document.getElementById('editNoteContent').value = content;
        document.getElementById('edit_note_color').value = color;
        document.getElementById('editNoteForm').action = '/calendar/notes/' + id;
        // Set color selection
        document.querySelectorAll('#editNoteColorPicker .color-dot').forEach(d => {
            d.classList.toggle('selected', d.dataset.color === color);
        });
        openModal('editNoteModal');
    }

    // ── Sync quick-add date with current month
    document.getElementById('quickEventDate').value = '{{ now()->toDateString() }}';
</script>
@endpush
@endsection
