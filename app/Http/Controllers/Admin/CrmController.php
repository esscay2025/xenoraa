<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmLead;
use App\Models\CrmRequirement;
use App\Models\ChatbotConversation;
use App\Models\ChatbotTraining;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    // ─── Leads ────────────────────────────────────────────────────────────────

    public function leadsIndex(Request $request)
    {
        $query = CrmLead::with(['requirements'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->priority) {
            $query->where('priority', $request->priority);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('mobile', 'like', "%{$request->search}%");
            });
        }

        $leads = $query->paginate(20);

        $stats = [
            'total'         => CrmLead::count(),
            'new'           => CrmLead::where('status', 'new')->count(),
            'qualified'     => CrmLead::where('status', 'qualified')->count(),
            'proposal_sent' => CrmLead::where('status', 'proposal_sent')->count(),
            'won'           => CrmLead::where('status', 'won')->count(),
        ];

        return view('admin.crm.leads-index', compact('leads', 'stats'));
    }

    public function leadShow(CrmLead $lead)
    {
        $lead->load(['requirements', 'conversations' => function ($q) {
            $q->orderBy('created_at');
        }]);
        return view('admin.crm.lead-show', compact('lead'));
    }

    public function leadUpdate(Request $request, CrmLead $lead)
    {
        $validated = $request->validate([
            'status'   => 'required|in:new,contacted,qualified,proposal_sent,won,lost',
            'priority' => 'required|in:low,medium,high',
            'notes'    => 'nullable|string',
            'assigned_to' => 'nullable|string|max:100',
        ]);

        if ($validated['status'] !== $lead->status) {
            $validated['last_contacted_at'] = now();
        }

        $lead->update($validated);

        return back()->with('success', 'Lead updated successfully.');
    }

    public function leadDestroy(CrmLead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.crm.leads')->with('success', 'Lead deleted.');
    }

    // ─── Requirements ─────────────────────────────────────────────────────────

    public function requirementsIndex()
    {
        $requirements = CrmRequirement::with('lead')->latest()->paginate(20);
        return view('admin.crm.requirements', compact('requirements'));
    }

    public function markScopeSent(CrmRequirement $requirement)
    {
        $requirement->update([
            'scope_sent'    => true,
            'scope_sent_at' => now(),
        ]);
        // Update lead status to proposal_sent
        $requirement->lead->update(['status' => 'proposal_sent', 'last_contacted_at' => now()]);
        return back()->with('success', 'Scope marked as sent and lead status updated.');
    }

    // ─── Chatbot Training ─────────────────────────────────────────────────────

    public function trainingIndex()
    {
        $trainings = ChatbotTraining::orderBy('category')->orderBy('sort_order')->get();
        $categories = ['greeting', 'services', 'pricing', 'process', 'faq', 'objection', 'general'];
        return view('admin.crm.training', compact('trainings', 'categories'));
    }

    public function trainingStore(Request $request)
    {
        $validated = $request->validate([
            'category'   => 'required|string|max:50',
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        ChatbotTraining::create($validated);
        return back()->with('success', 'Training entry added successfully.');
    }

    public function trainingUpdate(Request $request, ChatbotTraining $training)
    {
        $validated = $request->validate([
            'category'   => 'required|string|max:50',
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'is_active'  => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $training->update($validated);
        return back()->with('success', 'Training entry updated.');
    }

    public function trainingDestroy(ChatbotTraining $training)
    {
        $training->delete();
        return back()->with('success', 'Training entry deleted.');
    }

    // ─── Conversations ────────────────────────────────────────────────────────

    public function conversationsIndex()
    {
        $conversations = ChatbotConversation::with('lead')
            ->select('session_id', \DB::raw('MIN(created_at) as started_at'), \DB::raw('COUNT(*) as message_count'), 'lead_id')
            ->groupBy('session_id', 'lead_id')
            ->orderByDesc('started_at')
            ->paginate(20);

        return view('admin.crm.conversations', compact('conversations'));
    }

    public function conversationShow($sessionId)
    {
        $messages = ChatbotConversation::where('session_id', $sessionId)
            ->orderBy('created_at')
            ->get();
        $lead = $messages->first()?->lead;
        return view('admin.crm.conversation-show', compact('messages', 'lead', 'sessionId'));
    }

    public function conversationDestroy($sessionId)
    {
        ChatbotConversation::where('session_id', $sessionId)->delete();
        return redirect()->route('admin.crm.conversations')->with('success', 'Conversation deleted.');
    }
}
