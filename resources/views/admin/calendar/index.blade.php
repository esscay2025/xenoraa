@extends('layouts.admin')
@section('title', 'Calendar Events')
@section('page-title', 'Calendar Events')

@section('content')
{{-- Stats --}}
<div class="grid-4" style="margin-bottom: 2rem;">
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #3b82f6;">{{ $stats['total_events'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Total Events</div>
    </div>
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #22c55e;">{{ $stats['upcoming'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Upcoming Events</div>
    </div>
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #8b5cf6;">{{ $stats['total_notes'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">User Notes</div>
    </div>
    <div class="card" style="text-align:center; padding: 1.5rem;">
        <div style="font-size: 2rem; font-weight: 800; color: #f59e0b;">{{ $stats['users_active'] }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Active Users</div>
    </div>
</div>

<div class="grid-2" style="gap: 1.5rem; align-items: start;">
    {{-- Events Table --}}
    <div class="card">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
            <h2 style="font-size: 1rem; font-weight: 600; margin: 0;">Calendar Events ({{ $events->total() }})</h2>
            <button onclick="document.getElementById('modal-add-event').style.display='flex'" class="btn btn-primary" style="padding: 0.4rem 0.9rem; font-size: 0.8rem;"><i class="fas fa-plus"></i> Add Event</button>
        </div>
        {{-- Filter --}}
        <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border);">
            <form method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end;">
                <div style="flex: 1; min-width: 140px;">
                    <select name="user_id" style="background: var(--bg-card); border: 1px solid var(--border); color: white; padding: 0.5rem 0.75rem; border-radius: 8px; width: 100%; font-size: 0.85rem;">
                        <option value="">All Users</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Filter</button>
                <a href="{{ route('admin.calendar.index') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Reset</a>
            </form>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <th style="padding: 0.7rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Event</th>
                        <th style="padding: 0.7rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">User</th>
                        <th style="padding: 0.7rem 1rem; text-align: left; color: var(--text-muted); font-weight: 600;">Date</th>
                        <th style="padding: 0.7rem 1rem; text-align: center; color: var(--text-muted); font-weight: 600;">Del</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 0.7rem 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span style="width: 10px; height: 10px; border-radius: 50%; background: {{ $event->color ?? '#3b82f6' }}; flex-shrink: 0;"></span>
                                <span style="color: var(--text-primary); font-size: 0.85rem;">{{ Str::limit($event->title, 40) }}</span>
                            </div>
                            @if($event->description)
                            <div style="color: var(--text-muted); font-size: 0.75rem; margin-top: 0.2rem; padding-left: 1.25rem;">{{ Str::limit($event->description, 50) }}</div>
                            @endif
                        </td>
                        <td style="padding: 0.7rem 1rem; color: var(--text-secondary); font-size: 0.8rem;">{{ $event->user?->name ?? 'Guest' }}</td>
                        <td style="padding: 0.7rem 1rem; color: var(--text-muted); font-size: 0.8rem; white-space: nowrap;">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                        <td style="padding: 0.7rem 1rem; text-align: center;">
                            <form method="POST" action="{{ route('admin.calendar.destroy', $event) }}" style="display:inline;" onsubmit="return confirm('Delete this event?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: rgba(239,68,68,0.1); color: #ef4444; border: none; padding: 0.25rem 0.5rem; border-radius: 5px; cursor: pointer; font-size: 0.8rem;"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-muted);">No events found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($events->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border);">
            {{ $events->withQueryString()->links() }}
        </div>
        @endif
    </div>

    {{-- Notes --}}
    <div class="card">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
            <h2 style="font-size: 1rem; font-weight: 600; margin: 0;">Recent User Notes ({{ $notes->count() }})</h2>
            <button onclick="document.getElementById('modal-add-note').style.display='flex'" class="btn btn-primary" style="padding: 0.4rem 0.9rem; font-size: 0.8rem;"><i class="fas fa-plus"></i> Add Note</button>
        </div>
        <div style="padding: 1rem;">
            @forelse($notes as $note)
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 1rem; margin-bottom: 0.75rem; {{ $note->is_pinned ? 'border-color: rgba(245,158,11,0.3);' : '' }}">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                        {{ $note->user?->name ?? 'Guest' }} · {{ $note->created_at->format('M d') }}
                        @if($note->is_pinned)<span style="color: #f59e0b; margin-left: 0.4rem;">📌</span>@endif
                    </div>
                    <form method="POST" action="{{ route('admin.calendar.note.destroy', $note) }}" style="display:inline;" onsubmit="return confirm('Delete this note?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background: rgba(239,68,68,0.1); color: #ef4444; border: none; padding: 0.2rem 0.45rem; border-radius: 5px; cursor: pointer; font-size: 0.75rem;"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
                <p style="color: var(--text-secondary); font-size: 0.875rem; margin: 0; line-height: 1.6;">{{ Str::limit($note->content, 120) }}</p>
            </div>
            @empty
            <div style="padding: 2rem; text-align: center; color: var(--text-muted);">No notes yet.</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Add Event Modal --}}
<div id="modal-add-event" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:2rem; width:100%; max-width:480px; position:relative;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
            <h3 style="font-size:1.1rem; font-weight:700; margin:0;"><i class="fas fa-calendar-plus" style="color:#3b82f6; margin-right:0.5rem;"></i> Add Calendar Event</h3>
            <button onclick="document.getElementById('modal-add-event').style.display='none'" style="background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:1.2rem;"><i class="fas fa-times"></i></button>
        </div>
        @if(session('success'))<div style="background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.3); color:#22c55e; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:0.85rem;"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
        <form method="POST" action="{{ route('admin.calendar.events.store') }}">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.4rem;">Event Title *</label>
                <input type="text" name="title" required style="width:100%; background:var(--bg-dark); border:1px solid var(--border); color:white; padding:0.6rem 0.9rem; border-radius:8px; font-size:0.9rem;" placeholder="e.g. Team Meeting">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div>
                    <label style="display:block; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.4rem;">Date *</label>
                    <input type="date" name="event_date" required style="width:100%; background:var(--bg-dark); border:1px solid var(--border); color:white; padding:0.6rem 0.9rem; border-radius:8px; font-size:0.9rem;">
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.4rem;">Time</label>
                    <input type="time" name="event_time" style="width:100%; background:var(--bg-dark); border:1px solid var(--border); color:white; padding:0.6rem 0.9rem; border-radius:8px; font-size:0.9rem;">
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div>
                    <label style="display:block; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.4rem;">Assign to User</label>
                    <select name="user_id" style="width:100%; background:var(--bg-dark); border:1px solid var(--border); color:white; padding:0.6rem 0.9rem; border-radius:8px; font-size:0.85rem;">
                        <option value="">— Self —</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.4rem;">Color</label>
                    <input type="color" name="color" value="#3b82f6" style="width:100%; height:38px; background:var(--bg-dark); border:1px solid var(--border); border-radius:8px; cursor:pointer;">
                </div>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.4rem;">Description</label>
                <textarea name="description" rows="2" style="width:100%; background:var(--bg-dark); border:1px solid var(--border); color:white; padding:0.6rem 0.9rem; border-radius:8px; font-size:0.85rem; resize:vertical;" placeholder="Optional description..."></textarea>
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.5rem;">
                <input type="checkbox" name="is_reminder" id="is_reminder" value="1" style="width:16px; height:16px;">
                <label for="is_reminder" style="font-size:0.85rem; color:var(--text-secondary); cursor:pointer;">Set as reminder</label>
            </div>
            <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
                <button type="button" onclick="document.getElementById('modal-add-event').style.display='none'" style="background:rgba(255,255,255,0.05); border:1px solid var(--border); color:var(--text-secondary); padding:0.6rem 1.2rem; border-radius:8px; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding:0.6rem 1.5rem;"><i class="fas fa-save"></i> Save Event</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Note Modal --}}
<div id="modal-add-note" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:2rem; width:100%; max-width:480px; position:relative;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
            <h3 style="font-size:1.1rem; font-weight:700; margin:0;"><i class="fas fa-sticky-note" style="color:#f59e0b; margin-right:0.5rem;"></i> Add Note</h3>
            <button onclick="document.getElementById('modal-add-note').style.display='none'" style="background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:1.2rem;"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.calendar.notes.store') }}">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.4rem;">Title (optional)</label>
                <input type="text" name="title" style="width:100%; background:var(--bg-dark); border:1px solid var(--border); color:white; padding:0.6rem 0.9rem; border-radius:8px; font-size:0.9rem;" placeholder="Note title...">
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.4rem;">Note Content *</label>
                <textarea name="content" required rows="4" style="width:100%; background:var(--bg-dark); border:1px solid var(--border); color:white; padding:0.6rem 0.9rem; border-radius:8px; font-size:0.85rem; resize:vertical;" placeholder="Write your note here..."></textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div>
                    <label style="display:block; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.4rem;">Assign to User</label>
                    <select name="user_id" style="width:100%; background:var(--bg-dark); border:1px solid var(--border); color:white; padding:0.6rem 0.9rem; border-radius:8px; font-size:0.85rem;">
                        <option value="">— Self —</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.4rem;">Color</label>
                    <input type="color" name="color" value="#f59e0b" style="width:100%; height:38px; background:var(--bg-dark); border:1px solid var(--border); border-radius:8px; cursor:pointer;">
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.5rem;">
                <input type="checkbox" name="is_pinned" id="is_pinned" value="1" style="width:16px; height:16px;">
                <label for="is_pinned" style="font-size:0.85rem; color:var(--text-secondary); cursor:pointer;">Pin this note</label>
            </div>
            <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
                <button type="button" onclick="document.getElementById('modal-add-note').style.display='none'" style="background:rgba(255,255,255,0.05); border:1px solid var(--border); color:var(--text-secondary); padding:0.6rem 1.2rem; border-radius:8px; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding:0.6rem 1.5rem;"><i class="fas fa-save"></i> Save Note</button>
            </div>
        </form>
    </div>
</div>
@endsection
