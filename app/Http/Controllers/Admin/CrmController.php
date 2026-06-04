<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmLead;
use App\Models\CrmRequirement;
use App\Models\ChatbotConversation;
use App\Models\ChatbotTraining;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CrmController extends Controller
{
    private function tenantId(): int
    {
        return auth()->user()->getTenantId();
    }

    // ─── Leads ────────────────────────────────────────────────────────────────

    public function leadsIndex(Request $request)
    {
        $tid = $this->tenantId();
        $query = CrmLead::with(['requirements'])->where('user_id', $tid)->latest();

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
            'total'         => CrmLead::where('user_id', $tid)->count(),
            'new'           => CrmLead::where('user_id', $tid)->where('status', 'new')->count(),
            'qualified'     => CrmLead::where('user_id', $tid)->where('status', 'qualified')->count(),
            'proposal_sent' => CrmLead::where('user_id', $tid)->where('status', 'proposal_sent')->count(),
            'won'           => CrmLead::where('user_id', $tid)->where('status', 'won')->count(),
        ];

        return view('admin.crm.leads-index', compact('leads', 'stats'));
    }

    public function leadShow(CrmLead $lead)
    {
        abort_if($lead->user_id !== $this->tenantId(), 403);
        $lead->load(['requirements', 'conversations' => function ($q) {
            $q->orderBy('created_at');
        }]);
        return view('admin.crm.lead-show', compact('lead'));
    }

    public function leadUpdate(Request $request, CrmLead $lead)
    {
        abort_if($lead->user_id !== $this->tenantId(), 403);

        $validated = $request->validate([
            'name'        => 'nullable|string|max:200',
            'email'       => 'nullable|email|max:200',
            'mobile'      => 'nullable|string|max:30',
            'status'      => 'required|in:new,contacted,qualified,proposal_sent,won,lost',
            'priority'    => 'required|in:low,medium,high',
            'notes'       => 'nullable|string',
            'assigned_to' => 'nullable|string|max:100',
            'summary'     => 'nullable|string|max:1000',
        ]);

        if (isset($validated['status']) && $validated['status'] !== $lead->status) {
            $validated['last_contacted_at'] = now();
        }

        $lead->update(array_filter($validated, fn($v) => $v !== null));

        return back()->with('success', 'Lead updated successfully.');
    }

    public function leadDestroy(CrmLead $lead)
    {
        abort_if($lead->user_id !== $this->tenantId(), 403);
        $lead->delete();
        return redirect()->route('admin.crm.leads')->with('success', 'Lead deleted.');
    }

    /**
     * Send a reply email to the lead — uses tenant's own name and domain.
     */
    public function sendReplyEmail(Request $request, CrmLead $lead)
    {
        abort_if($lead->user_id !== $this->tenantId(), 403);

        $request->validate([
            'subject' => 'required|string|max:200',
            'body'    => 'required|string',
        ]);

        if (!$lead->email) {
            return back()->with('error', 'This lead does not have an email address.');
        }

        try {
            $pdfPath = $this->generateSolutionsPdf($lead, $request->body);

            Mail::send([], [], function ($message) use ($lead, $request, $pdfPath) {
                $message->to($lead->email, $lead->name)
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($request->subject)
                    ->html($this->buildEmailHtml($lead, $request->body));

                if ($pdfPath && file_exists($pdfPath)) {
                    $message->attach($pdfPath, [
                        'as'   => 'Proposal-' . str_replace(' ', '-', $lead->name) . '.pdf',
                        'mime' => 'application/pdf',
                    ]);
                }
            });

            if ($pdfPath && file_exists($pdfPath)) {
                @unlink($pdfPath);
            }

            if ($lead->status === 'new') {
                $lead->update(['status' => 'contacted', 'last_contacted_at' => now()]);
            }

            return back()->with('success', 'Reply email sent successfully to ' . $lead->email);
        } catch (\Exception $e) {
            Log::error('CRM Reply Email Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    // ─── Requirements ─────────────────────────────────────────────────────────

    public function requirementsIndex()
    {
        $tid = $this->tenantId();
        $requirements = CrmRequirement::with('lead')
            ->whereHas('lead', fn($q) => $q->where('user_id', $tid))
            ->latest()
            ->paginate(20);
        return view('admin.crm.requirements', compact('requirements'));
    }

    public function markScopeSent(CrmRequirement $requirement)
    {
        abort_if($requirement->lead->user_id !== $this->tenantId(), 403);
        $requirement->update([
            'scope_sent'    => true,
            'scope_sent_at' => now(),
        ]);
        $requirement->lead->update(['status' => 'proposal_sent', 'last_contacted_at' => now()]);
        return back()->with('success', 'Scope marked as sent and lead status updated.');
    }

    // ─── Chatbot Training ─────────────────────────────────────────────────────

    public function trainingIndex()
    {
        $tid = $this->tenantId();
        $trainings = ChatbotTraining::where('user_id', $tid)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();
        $categories = ['Greetings', 'About', 'Services', 'Pricing', 'Process', 'FAQ', 'Objection', 'Contact', 'Disclaimer', 'Collaborations', 'Content', 'General'];
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

        $validated['user_id'] = $this->tenantId();
        $validated['is_active'] = true;

        ChatbotTraining::create($validated);
        return back()->with('success', 'Training entry added successfully.');
    }

    public function trainingUpdate(Request $request, ChatbotTraining $training)
    {
        abort_if($training->user_id !== $this->tenantId(), 403);

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
        abort_if($training->user_id !== $this->tenantId(), 403);
        $training->delete();
        return back()->with('success', 'Training entry deleted.');
    }

    // ─── Conversations ────────────────────────────────────────────────────────

    public function conversationsIndex()
    {
        $tid = $this->tenantId();
        $conversations = ChatbotConversation::with('lead')
            ->where('user_id', $tid)
            ->select('session_id', DB::raw('MIN(created_at) as started_at'), DB::raw('COUNT(*) as message_count'), 'lead_id', 'user_id')
            ->groupBy('session_id', 'lead_id', 'user_id')
            ->orderByDesc('started_at')
            ->paginate(20);

        return view('admin.crm.conversations', compact('conversations'));
    }

    public function conversationShow($sessionId)
    {
        $tid = $this->tenantId();
        $messages = ChatbotConversation::where('session_id', $sessionId)
            ->where('user_id', $tid)
            ->orderBy('created_at')
            ->get();

        if ($messages->isEmpty()) {
            abort(403);
        }

        $lead = $messages->first()?->lead;
        return view('admin.crm.conversation-show', compact('messages', 'lead', 'sessionId'));
    }

    public function conversationReply(Request $request, $sessionId)
    {
        $tid = $this->tenantId();
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $firstMsg = ChatbotConversation::where('session_id', $sessionId)->where('user_id', $tid)->first();
        if (!$firstMsg) abort(403);

        $lead = $firstMsg?->lead;
        $tenant = auth()->user();

        ChatbotConversation::create([
            'lead_id'    => $lead?->id,
            'session_id' => $sessionId,
            'user_id'    => $tid,
            'role'       => 'assistant',
            'message'    => '[Admin Reply] ' . $request->message,
        ]);

        if ($lead && $lead->email) {
            try {
                $body = $request->message;
                $name = $lead->name ?? 'Visitor';
                $tenantName = $tenant->name;
                $tenantEmail = $tenant->email;

                Mail::send([], [], function ($message) use ($lead, $name, $body, $tenantName) {
                    $message->to($lead->email, $name)
                        ->from(config('mail.from.address'), config('mail.from.name'))
                        ->subject('Reply from ' . $tenantName)
                        ->html($this->buildEmailHtml($lead, $body));
                });

                if ($lead->status === 'new') {
                    $lead->update(['status' => 'contacted', 'last_contacted_at' => now()]);
                }
            } catch (\Exception $e) {
                Log::error('Conversation reply email error: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Reply sent' . ($lead?->email ? ' and emailed to ' . $lead->email : '') . '.');
    }

    public function conversationDestroy($sessionId)
    {
        $tid = $this->tenantId();
        ChatbotConversation::where('session_id', $sessionId)->where('user_id', $tid)->delete();
        return redirect()->route('admin.crm.conversations')->with('success', 'Conversation deleted.');
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    private function buildEmailHtml(CrmLead $lead, string $body): string
    {
        $bodyHtml = nl2br(e($body));
        $name = e($lead->name);
        $tenantName = e(auth()->user()->name ?? 'Team');

        return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background: #f8fafc; margin: 0; padding: 20px;">
  <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
    <div style="background: linear-gradient(135deg, #1e1b4b, #312e81); padding: 32px 40px; text-align: center;">
      <h1 style="color: #fff; font-size: 1.5rem; margin: 0; font-weight: 700;">{$tenantName}</h1>
    </div>
    <div style="padding: 32px 40px;">
      <p style="color: #374151; font-size: 1rem;">Dear {$name},</p>
      <div style="color: #374151; font-size: 1rem; line-height: 1.7;">{$bodyHtml}</div>
      <p style="color: #374151; margin-top: 24px;">Warm regards,<br><strong>{$tenantName}</strong></p>
    </div>
  </div>
</body>
</html>
HTML;
    }

    private function generateSolutionsPdf(CrmLead $lead, string $body): ?string
    {
        try {
            $pdf = new \FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Proposal for ' . $lead->name, 0, 1, 'C');
            $pdf->SetFont('Arial', '', 12);
            $pdf->MultiCell(0, 8, $body);
            $path = sys_get_temp_dir() . '/proposal_' . time() . '.pdf';
            $pdf->Output('F', $path);
            return $path;
        } catch (\Exception $e) {
            return null;
        }
    }
}
