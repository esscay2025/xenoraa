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

        // Only update non-null values
        $lead->update(array_filter($validated, fn($v) => $v !== null));

        return back()->with('success', 'Lead updated successfully.');
    }

    public function leadDestroy(CrmLead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.crm.leads')->with('success', 'Lead deleted.');
    }

    /**
     * Send a reply email to the lead with a solutions PDF attachment.
     */
    public function sendReplyEmail(Request $request, CrmLead $lead)
    {
        $request->validate([
            'subject'     => 'required|string|max:200',
            'body'        => 'required|string',
        ]);

        if (!$lead->email) {
            return back()->with('error', 'This lead does not have an email address.');
        }

        try {
            // Generate PDF with solutions document
            $pdfPath = $this->generateSolutionsPdf($lead, $request->body);

            Mail::send([], [], function ($message) use ($lead, $request, $pdfPath) {
                $message->to($lead->email, $lead->name)
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($request->subject)
                    ->html($this->buildEmailHtml($lead, $request->body));

                if ($pdfPath && file_exists($pdfPath)) {
                    $message->attach($pdfPath, [
                        'as'   => 'Solutions-Proposal-' . str_replace(' ', '-', $lead->name) . '.pdf',
                        'mime' => 'application/pdf',
                    ]);
                }
            });

            // Clean up temp PDF
            if ($pdfPath && file_exists($pdfPath)) {
                @unlink($pdfPath);
            }

            // Update lead status to contacted
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

    // ─── Private Helpers ──────────────────────────────────────────────────────

    private function buildEmailHtml(CrmLead $lead, string $body): string
    {
        $bodyHtml = nl2br(e($body));
        $name = e($lead->name);
        return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background: #f8fafc; margin: 0; padding: 20px;">
  <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
    <div style="background: linear-gradient(135deg, #1e1b4b, #312e81); padding: 32px 40px; text-align: center;">
      <h1 style="color: #fff; font-size: 1.5rem; margin: 0; font-weight: 700;">Gopi K</h1>
      <p style="color: #a5b4fc; margin: 6px 0 0; font-size: 0.9rem;">Technology Entrepreneur · Go Esscay Solutions</p>
    </div>
    <div style="padding: 40px;">
      <p style="color: #1e293b; font-size: 1rem; margin: 0 0 20px;">Dear {$name},</p>
      <div style="color: #334155; font-size: 0.95rem; line-height: 1.8; margin-bottom: 28px;">{$bodyHtml}</div>
      <div style="background: #f1f5f9; border-left: 4px solid #6366f1; padding: 16px 20px; border-radius: 0 8px 8px 0; margin-bottom: 28px;">
        <p style="color: #475569; font-size: 0.875rem; margin: 0;">📎 Please find the attached <strong>Solutions Proposal PDF</strong> with detailed information tailored to your requirements.</p>
      </div>
      <p style="color: #334155; font-size: 0.95rem; line-height: 1.8; margin: 0 0 8px;">Looking forward to working with you!</p>
      <p style="color: #334155; font-size: 0.95rem; margin: 0;">Best regards,<br><strong>Gopi K</strong><br>Founder, Go Esscay Solutions<br>
      <a href="https://gopi.blog" style="color: #6366f1;">gopi.blog</a></p>
    </div>
    <div style="background: #f8fafc; padding: 20px 40px; text-align: center; border-top: 1px solid #e2e8f0;">
      <p style="color: #94a3b8; font-size: 0.75rem; margin: 0;">© 2025 Go Esscay Solutions · Chennai, India · <a href="https://gopi.blog" style="color: #6366f1;">gopi.blog</a></p>
    </div>
  </div>
</body>
</html>
HTML;
    }

    private function generateSolutionsPdf(CrmLead $lead, string $emailBody): ?string
    {
        try {
            $name = $lead->name;
            $email = $lead->email ?? 'N/A';
            $mobile = $lead->mobile ?? 'N/A';
            $summary = $lead->summary ?? 'Requirements gathered via AI chatbot conversation.';
            $date = now()->format('d M Y');

            $requirements = $lead->requirements->map(fn($r) => "• " . $r->requirement)->implode("\n");
            if (empty($requirements)) {
                $requirements = "• " . $summary;
            }

            $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: DejaVu Sans, Arial, sans-serif; color: #1e293b; margin: 0; padding: 0; }
  .header { background: #1e1b4b; color: #fff; padding: 40px; }
  .header h1 { font-size: 24px; margin: 0; }
  .header p { color: #a5b4fc; margin: 6px 0 0; font-size: 13px; }
  .content { padding: 40px; }
  .section { margin-bottom: 28px; }
  .section h2 { font-size: 14px; color: #6366f1; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 12px; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; }
  .info-row { display: flex; margin-bottom: 8px; }
  .info-label { font-size: 12px; color: #64748b; width: 120px; flex-shrink: 0; }
  .info-value { font-size: 13px; color: #1e293b; font-weight: 600; }
  .body-text { font-size: 13px; line-height: 1.8; color: #334155; white-space: pre-wrap; }
  .services { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; }
  .service-item { margin-bottom: 12px; }
  .service-title { font-size: 13px; font-weight: 700; color: #1e293b; }
  .service-desc { font-size: 12px; color: #64748b; margin-top: 2px; }
  .footer { background: #f8fafc; padding: 24px 40px; border-top: 1px solid #e2e8f0; text-align: center; }
  .footer p { font-size: 11px; color: #94a3b8; margin: 0; }
</style>
</head>
<body>
<div class="header">
  <h1>Solutions Proposal</h1>
  <p>Prepared by Gopi K · Go Esscay Solutions · gopi.blog</p>
</div>
<div class="content">
  <div class="section">
    <h2>Client Information</h2>
    <div class="info-row"><span class="info-label">Name:</span><span class="info-value">{$name}</span></div>
    <div class="info-row"><span class="info-label">Email:</span><span class="info-value">{$email}</span></div>
    <div class="info-row"><span class="info-label">Mobile:</span><span class="info-value">{$mobile}</span></div>
    <div class="info-row"><span class="info-label">Date:</span><span class="info-value">{$date}</span></div>
  </div>

  <div class="section">
    <h2>Requirements Summary</h2>
    <p class="body-text">{$summary}</p>
  </div>

  <div class="section">
    <h2>Detailed Requirements</h2>
    <p class="body-text">{$requirements}</p>
  </div>

  <div class="section">
    <h2>Our Message to You</h2>
    <p class="body-text">{$emailBody}</p>
  </div>

  <div class="section">
    <h2>Services We Offer</h2>
    <div class="services">
      <div class="service-item">
        <div class="service-title">AI Solutions &amp; Automation</div>
        <div class="service-desc">Chatbots, AI agents, workflow automation, RPA, intelligent document processing</div>
      </div>
      <div class="service-item">
        <div class="service-title">Custom Application Development</div>
        <div class="service-desc">Web apps, mobile apps (iOS/Android), SaaS platforms, ERP/CRM systems</div>
      </div>
      <div class="service-item">
        <div class="service-title">Digital Transformation</div>
        <div class="service-desc">Legacy modernization, cloud migration (AWS/GCP/Azure), DevOps</div>
      </div>
      <div class="service-item">
        <div class="service-title">Startup Product Development</div>
        <div class="service-desc">MVP development, product strategy, technical co-founder services</div>
      </div>
      <div class="service-item">
        <div class="service-title">Branding &amp; Digital Presence</div>
        <div class="service-desc">Corporate websites, SEO, social media strategy, digital marketing</div>
      </div>
    </div>
  </div>
</div>
<div class="footer">
  <p>Go Esscay Solutions · Chennai, India · gopi.blog · support@gopi.blog</p>
  <p style="margin-top:4px;">This proposal is confidential and prepared exclusively for {$name}</p>
</div>
</body>
</html>
HTML;

            $pdfPath = storage_path('app/temp/proposal_' . $lead->id . '_' . time() . '.pdf');
            @mkdir(dirname($pdfPath), 0755, true);

            // Use DomPDF if available, else wkhtmltopdf, else fpdf
            if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
                $pdf->save($pdfPath);
            } else {
                // Fallback: write HTML and convert with wkhtmltopdf
                $htmlPath = storage_path('app/temp/proposal_' . $lead->id . '.html');
                file_put_contents($htmlPath, $html);
                $cmd = "wkhtmltopdf --quiet --page-size A4 --margin-top 0 --margin-bottom 0 --margin-left 0 --margin-right 0 " . escapeshellarg($htmlPath) . " " . escapeshellarg($pdfPath) . " 2>&1";
                exec($cmd, $output, $code);
                @unlink($htmlPath);
                if ($code !== 0 || !file_exists($pdfPath)) {
                    return null;
                }
            }

            return $pdfPath;
        } catch (\Exception $e) {
            Log::error('PDF generation error: ' . $e->getMessage());
            return null;
        }
    }
}
