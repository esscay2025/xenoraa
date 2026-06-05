<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Todo;
use App\Models\Reminder;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    private function tenantId()
    {
        return auth()->user()->getTenantId();
    }

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'notes');
        $notes = Note::where('user_id', $this->tenantId())->orderByDesc('is_pinned')->orderByDesc('updated_at')->get();
        $todos = Todo::where('user_id', $this->tenantId())->orderBy('is_completed')->orderByDesc('created_at')->get();
        $reminders = Reminder::where('user_id', $this->tenantId())->orderBy('remind_at')->get();

        return view('admin.notes.index', compact('notes', 'todos', 'reminders', 'tab'));
    }

    // --- Notes ---
    public function storeNote(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'is_pinned' => 'boolean',
        ]);
        $validated['user_id'] = $this->tenantId();
        $validated['is_pinned'] = $request->boolean('is_pinned');
        Note::create($validated);
        return back()->with('success', 'Note created.');
    }

    public function updateNote(Request $request, Note $note)
    {
        abort_if($note->user_id !== $this->tenantId(), 403);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'is_pinned' => 'boolean',
        ]);
        $validated['is_pinned'] = $request->boolean('is_pinned');
        $note->update($validated);
        return back()->with('success', 'Note updated.');
    }

    public function destroyNote(Note $note)
    {
        abort_if($note->user_id !== $this->tenantId(), 403);
        $note->delete();
        return back()->with('success', 'Note deleted.');
    }

    // --- Todos ---
    public function storeTodo(Request $request)
    {
        $validated = $request->validate([
            'task' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
        ]);
        $validated['user_id'] = $this->tenantId();
        Todo::create($validated);
        return back()->with('success', 'Task added.');
    }

    public function toggleTodo(Todo $todo)
    {
        abort_if($todo->user_id !== $this->tenantId(), 403);
        $todo->update([
            'is_completed' => !$todo->is_completed,
            'completed_at' => !$todo->is_completed ? now() : null,
        ]);
        return back()->with('success', 'Task updated.');
    }

    public function destroyTodo(Todo $todo)
    {
        abort_if($todo->user_id !== $this->tenantId(), 403);
        $todo->delete();
        return back()->with('success', 'Task deleted.');
    }

    // --- Reminders ---
    public function storeReminder(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'remind_at' => 'required|date',
            'type' => 'nullable|in:once,daily,weekly,monthly',
        ]);
        $validated['user_id'] = $this->tenantId();
        $validated['type'] = $validated['type'] ?? 'once';
        Reminder::create($validated);
        return back()->with('success', 'Reminder set.');
    }

    public function dismissReminder(Reminder $reminder)
    {
        abort_if($reminder->user_id !== $this->tenantId(), 403);
        $reminder->update(['is_sent' => true]);
        return back()->with('success', 'Reminder dismissed.');
    }

    public function destroyReminder(Reminder $reminder)
    {
        abort_if($reminder->user_id !== $this->tenantId(), 403);
        $reminder->delete();
        return back()->with('success', 'Reminder deleted.');
    }
}
