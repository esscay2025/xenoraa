<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumTopic;
use App\Models\ForumReply;
use Illuminate\Http\Request;

class ForumAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = ForumTopic::with(['user', 'replies'])
            ->withCount('replies');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            if ($request->status === 'pinned') $query->where('is_pinned', true);
            if ($request->status === 'locked') $query->where('is_locked', true);
        }

        $topics = $query->orderByDesc('is_pinned')->orderByDesc('created_at')->paginate(20);

        $stats = [
            'total_topics'  => ForumTopic::count(),
            'total_replies' => ForumReply::where('is_deleted', false)->count(),
            'pinned'        => ForumTopic::where('is_pinned', true)->count(),
            'locked'        => ForumTopic::where('is_locked', true)->count(),
        ];

        return view('admin.forum.index', compact('topics', 'stats'));
    }

    public function show(ForumTopic $topic)
    {
        $topic->load(['user', 'allReplies.user']);
        return view('admin.forum.show', compact('topic'));
    }

    public function pin(ForumTopic $topic)
    {
        $topic->update(['is_pinned' => !$topic->is_pinned]);
        $action = $topic->is_pinned ? 'pinned' : 'unpinned';
        return back()->with('success', "Topic has been {$action}.");
    }

    public function lock(ForumTopic $topic)
    {
        $topic->update(['is_locked' => !$topic->is_locked]);
        $action = $topic->is_locked ? 'locked' : 'unlocked';
        return back()->with('success', "Topic has been {$action}.");
    }

    public function destroy(ForumTopic $topic)
    {
        $topic->allReplies()->delete();
        $topic->delete();
        return redirect()->route('admin.forum.index')->with('success', 'Topic and all replies deleted.');
    }

    public function destroyReply(ForumReply $reply)
    {
        $reply->update(['is_deleted' => true]);
        return back()->with('success', 'Reply removed.');
    }

    public function approveReply(ForumReply $reply)
    {
        $reply->update(['is_deleted' => false]);
        return back()->with('success', 'Reply restored.');
    }
}
