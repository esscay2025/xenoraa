@extends('layouts.admin')
@section('title', 'Notes & Productivity')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Notes & Productivity</h4>
        <p class="text-muted mb-0">Manage your notes, tasks, and reminders</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item"><a class="nav-link {{ $tab === 'notes' ? 'active' : '' }}" href="{{ route('admin.notes.index', ['tab' => 'notes']) }}"><i class="bi bi-sticky"></i> Notes <span class="badge bg-primary">{{ $notes->count() }}</span></a></li>
    <li class="nav-item"><a class="nav-link {{ $tab === 'todos' ? 'active' : '' }}" href="{{ route('admin.notes.index', ['tab' => 'todos']) }}"><i class="bi bi-check2-square"></i> Tasks <span class="badge bg-primary">{{ $todos->where('is_completed', false)->count() }}</span></a></li>
    <li class="nav-item"><a class="nav-link {{ $tab === 'reminders' ? 'active' : '' }}" href="{{ route('admin.notes.index', ['tab' => 'reminders']) }}"><i class="bi bi-bell"></i> Reminders <span class="badge bg-primary">{{ $reminders->where('is_sent', false)->count() }}</span></a></li>
</ul>

{{-- Notes Tab --}}
@if($tab === 'notes')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-plus-circle"></i> New Note</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.notes.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="title" class="form-control" placeholder="Note title..." required>
                    </div>
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="4" placeholder="Write your note..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Color</label>
                        <div class="d-flex gap-2">
                            @foreach(['#ffffff', '#fff3cd', '#d1ecf1', '#d4edda', '#f8d7da', '#e2e3e5'] as $color)
                            <label class="position-relative">
                                <input type="radio" name="color" value="{{ $color }}" class="position-absolute opacity-0" {{ $color === '#ffffff' ? 'checked' : '' }}>
                                <span class="d-inline-block rounded-circle border" style="width:24px;height:24px;background:{{ $color }};cursor:pointer;"></span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Add Note</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        @if($notes->isEmpty())
        <div class="text-center py-5"><i class="bi bi-sticky display-1 text-muted"></i><p class="text-muted mt-3">No notes yet. Create your first note.</p></div>
        @else
        <div class="row g-3">
            @foreach($notes as $note)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100" style="background:{{ $note->color ?? '#ffffff' }};">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="mb-1">{{ $note->is_pinned ? '📌 ' : '' }}{{ $note->title }}</h6>
                            <form action="{{ route('admin.notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm p-0 text-danger border-0"><i class="bi bi-x-lg"></i></button></form>
                        </div>
                        <p class="small mb-2">{{ Str::limit($note->content, 150) }}</p>
                        <small class="text-muted">{{ $note->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endif

{{-- Todos Tab --}}
@if($tab === 'todos')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-plus-circle"></i> New Task</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.todos.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="task" class="form-control" placeholder="Task name..." required>
                    </div>
                    <div class="mb-3">
                        <textarea name="description" class="form-control" rows="2" placeholder="Description (optional)"></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small">Due Date</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Add Task</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        @if($todos->isEmpty())
        <div class="text-center py-5"><i class="bi bi-check2-square display-1 text-muted"></i><p class="text-muted mt-3">No tasks yet. Add your first task.</p></div>
        @else
        <div class="card border-0 shadow-sm">
            <div class="list-group list-group-flush">
                @foreach($todos as $todo)
                <div class="list-group-item d-flex align-items-center gap-3">
                    <form action="{{ route('admin.todos.toggle', $todo) }}" method="POST">@csrf @method('PATCH')
                        <button class="btn btn-sm p-0 border-0"><i class="bi bi-{{ $todo->is_completed ? 'check-circle-fill text-success' : 'circle text-muted' }} fs-5"></i></button>
                    </form>
                    <div class="flex-grow-1">
                        <span class="{{ $todo->is_completed ? 'text-decoration-line-through text-muted' : '' }}">{{ $todo->task }}</span>
                        @if($todo->due_date)
                        <br><small class="text-{{ $todo->due_date < now()->toDateString() && !$todo->is_completed ? 'danger' : 'muted' }}"><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($todo->due_date)->format('M d, Y') }}</small>
                        @endif
                    </div>
                    @php $pColors = ['low' => 'success', 'medium' => 'warning', 'high' => 'danger']; @endphp
                    <span class="badge bg-{{ $pColors[$todo->priority] ?? 'secondary' }}">{{ ucfirst($todo->priority) }}</span>
                    <form action="{{ route('admin.todos.destroy', $todo) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endif

{{-- Reminders Tab --}}
@if($tab === 'reminders')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-plus-circle"></i> New Reminder</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.reminders.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="title" class="form-control" placeholder="Reminder title..." required>
                    </div>
                    <div class="mb-3">
                        <textarea name="description" class="form-control" rows="2" placeholder="Details (optional)"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Remind At</label>
                        <input type="datetime-local" name="remind_at" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Repeat</label>
                        <select name="type" class="form-select">
                            <option value="once">One Time</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Set Reminder</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        @if($reminders->isEmpty())
        <div class="text-center py-5"><i class="bi bi-bell display-1 text-muted"></i><p class="text-muted mt-3">No reminders set. Create your first reminder.</p></div>
        @else
        <div class="card border-0 shadow-sm">
            <div class="list-group list-group-flush">
                @foreach($reminders as $reminder)
                <div class="list-group-item d-flex align-items-center gap-3 {{ $reminder->is_sent ? 'opacity-50' : '' }}">
                    <i class="bi bi-bell{{ $reminder->is_sent ? '-slash' : '-fill text-warning' }} fs-5"></i>
                    <div class="flex-grow-1">
                        <strong>{{ $reminder->title }}</strong>
                        @if($reminder->description)<br><small class="text-muted">{{ $reminder->description }}</small>@endif
                        <br><small class="text-{{ $reminder->remind_at < now() && !$reminder->is_sent ? 'danger' : 'muted' }}"><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($reminder->remind_at)->format('M d, Y h:i A') }}
                        @if($reminder->type !== 'once') <span class="badge bg-info">{{ ucfirst($reminder->type) }}</span> @endif
                        </small>
                    </div>
                    @if(!$reminder->is_sent)
                    <form action="{{ route('admin.reminders.dismiss', $reminder) }}" method="POST">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-success" title="Dismiss"><i class="bi bi-check"></i></button></form>
                    @endif
                    <form action="{{ route('admin.reminders.destroy', $reminder) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endif
@endsection
