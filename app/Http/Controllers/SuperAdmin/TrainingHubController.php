<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotTraining;
use App\Models\ChatbotConversation;
use App\Models\CrmLead;
use Illuminate\Http\Request;

class TrainingHubController extends Controller
{
    // ── AI Training ──────────────────────────────────────────────────────────

    public function training(Request $request)
    {
        $search   = $request->input('search');
        $category = $request->input('category');

        $q = ChatbotTraining::whereNull('user_id'); // Xenoraa platform training only
        if ($search) {
            $q->where(fn($q) => $q->where('question', 'like', "%{$search}%")
                                   ->orWhere('answer', 'like', "%{$search}%"));
        }
        if ($category) {
            $q->where('category', $category);
        }

        $trainings  = $q->orderBy('category')->orderBy('sort_order')->paginate(25)->withQueryString();
        $categories = ChatbotTraining::whereNull('user_id')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        $stats = [
            'total'    => ChatbotTraining::whereNull('user_id')->count(),
            'active'   => ChatbotTraining::whereNull('user_id')->where('is_active', true)->count(),
            'inactive' => ChatbotTraining::whereNull('user_id')->where('is_active', false)->count(),
            'cats'     => $categories->count(),
        ];

        return view('superadmin.training-hub.training', compact('trainings', 'categories', 'stats', 'search', 'category'));
    }

    public function trainingStore(Request $request)
    {
        $data = $request->validate([
            'category'   => 'required|string|max:100',
            'question'   => 'required|string|max:1000',
            'answer'     => 'required|string|max:5000',
            'is_active'  => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        ChatbotTraining::create(array_merge($data, [
            'user_id'    => null, // Platform-level
            'is_active'  => $request->boolean('is_active', true),
            'sort_order' => $data['sort_order'] ?? 0,
        ]));

        return back()->with('success', 'Training entry added successfully.');
    }

    public function trainingUpdate(Request $request, int $id)
    {
        $entry = ChatbotTraining::whereNull('user_id')->findOrFail($id);
        $data  = $request->validate([
            'category'   => 'required|string|max:100',
            'question'   => 'required|string|max:1000',
            'answer'     => 'required|string|max:5000',
            'is_active'  => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);
        $entry->update(array_merge($data, [
            'is_active'  => $request->boolean('is_active', true),
            'sort_order' => $data['sort_order'] ?? $entry->sort_order,
        ]));
        return back()->with('success', 'Training entry updated.');
    }

    public function trainingDestroy(int $id)
    {
        ChatbotTraining::whereNull('user_id')->findOrFail($id)->delete();
        return back()->with('success', 'Training entry deleted.');
    }

    public function trainingToggle(int $id)
    {
        $entry = ChatbotTraining::whereNull('user_id')->findOrFail($id);
        $entry->update(['is_active' => !$entry->is_active]);
        return response()->json(['is_active' => $entry->is_active]);
    }

    // ── AI Conversations ─────────────────────────────────────────────────────

    public function conversations(Request $request)
    {
        $intent  = $request->input('intent'); // sales, support, general
        $search  = $request->input('search');
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        // Get unique sessions for Xenoraa platform (user_id IS NULL)
        $sessionsQ = ChatbotConversation::whereNull('user_id')
            ->selectRaw('session_id, MIN(created_at) as started_at, MAX(created_at) as last_at, COUNT(*) as message_count, lead_id')
            ->groupBy('session_id', 'lead_id')
            ->orderByDesc('last_at');

        if ($dateFrom) $sessionsQ->whereDate('created_at', '>=', $dateFrom);
        if ($dateTo)   $sessionsQ->whereDate('created_at', '<=', $dateTo);

        $sessions = $sessionsQ->paginate(20)->withQueryString();

        // Load leads for sessions
        $leadIds = $sessions->pluck('lead_id')->filter()->unique();
        $leads   = CrmLead::whereIn('id', $leadIds)->get()->keyBy('id');

        // Filter by intent via lead notes
        if ($intent) {
            $sessions = $sessions->filter(function ($s) use ($leads, $intent) {
                $lead = $leads[$s->lead_id] ?? null;
                return $lead && str_contains(strtolower($lead->notes ?? ''), $intent);
            });
        }

        $stats = [
            'total_sessions'  => ChatbotConversation::whereNull('user_id')->distinct('session_id')->count('session_id'),
            'total_messages'  => ChatbotConversation::whereNull('user_id')->count(),
            'leads_captured'  => CrmLead::whereNull('user_id')->where('source', 'xenoraa_chat')->count(),
            'today_sessions'  => ChatbotConversation::whereNull('user_id')->whereDate('created_at', today())->distinct('session_id')->count('session_id'),
        ];

        return view('superadmin.training-hub.conversations', compact('sessions', 'leads', 'stats', 'intent', 'search', 'dateFrom', 'dateTo'));
    }

    public function conversationDetail(string $sessionId)
    {
        $messages = ChatbotConversation::where('session_id', $sessionId)
            ->orderBy('created_at')
            ->get();

        $lead = $messages->first()?->lead_id
            ? CrmLead::find($messages->first()->lead_id)
            : null;

        return view('superadmin.training-hub.conversation-detail', compact('messages', 'lead', 'sessionId'));
    }

    public function conversationDestroy(string $sessionId)
    {
        ChatbotConversation::where('session_id', $sessionId)->delete();
        return back()->with('success', 'Conversation deleted.');
    }
}
