<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CrmLead;
use App\Models\CrmRequirement;
use App\Models\ChatbotConversation;
use App\Models\ChatbotTraining;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Resolve the tenant from the request (domain or username header).
     */
    protected function resolveTenant(Request $request): ?User
    {
        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');

        // Custom domain (e.g. gopi.blog)
        if ($host !== $mainDomain && $host !== 'www.' . $mainDomain) {
            $tenant = User::where('custom_domain', $host)
                ->orWhere('custom_domain', 'www.' . $host)
                ->first();
            if ($tenant) return $tenant;
        }

        // Username passed from JS (X-Tenant-Username header or tenant_username field)
        $username = $request->header('X-Tenant-Username') ?? $request->input('tenant_username');
        if ($username) {
            return User::where('username', $username)->first();
        }

        return null;
    }

    /**
     * Handle an incoming chat message from the popup widget.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message'         => 'required|string|max:2000',
            'session_id'      => 'required|string|max:100',
            'lead_id'         => 'nullable|integer',
            'tenant_username' => 'nullable|string|max:100',
        ]);

        $tenant = $this->resolveTenant($request);
        $tenantId = $tenant?->id;

        $sessionId   = $request->session_id;
        $userMessage = trim($request->message);
        $leadId      = $request->lead_id;

        // ── Load or create lead ──────────────────────────────────────────────
        $lead = $leadId ? CrmLead::find($leadId) : null;

        // ── Save user message ────────────────────────────────────────────────
        ChatbotConversation::create([
            'lead_id'    => $lead?->id,
            'session_id' => $sessionId,
            'user_id'    => $tenantId,
            'role'       => 'user',
            'message'    => $userMessage,
        ]);

        // ── Load conversation history for this session ───────────────────────
        $history = ChatbotConversation::where('session_id', $sessionId)
            ->orderBy('created_at')
            ->get();

        // ── Load tenant-specific training data ───────────────────────────────
        $trainingQuery = ChatbotTraining::where('is_active', true)
            ->orderBy('category')
            ->orderBy('sort_order');

        if ($tenantId) {
            $trainingQuery->where('user_id', $tenantId);
        }

        $trainingData = $trainingQuery->get()
            ->map(fn($t) => "Category: {$t->category}\nQ: {$t->question}\nA: {$t->answer}")
            ->implode("\n\n");

        // ── Build tenant-specific system prompt ──────────────────────────────
        $systemPrompt = $this->buildSystemPrompt($tenant, $trainingData);

        // ── Build messages array for OpenAI ──────────────────────────────────
        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        foreach ($history as $msg) {
            $messages[] = [
                'role'    => $msg->role === 'user' ? 'user' : 'assistant',
                'content' => $msg->message,
            ];
        }

        // ── Call OpenAI ──────────────────────────────────────────────────────
        try {
            $client = \OpenAI::client(config('services.openai.api_key'));
            $response = $client->chat()->create([
                'model'       => 'gpt-4o-mini',
                'messages'    => $messages,
                'max_tokens'  => 400,
                'temperature' => 0.7,
            ]);
            $aiReply = $response->choices[0]->message->content;
        } catch (\Exception $e) {
            Log::error('Chatbot OpenAI error: ' . $e->getMessage());
            $tenantName = $tenant?->name ?? 'the team';
            $tenantEmail = $tenant?->email ?? 'support@xenoraa.com';
            $aiReply = "I'm having a small technical hiccup. Please try again in a moment, or feel free to reach out to {$tenantName} directly at {$tenantEmail}.";
        }

        // ── Save AI reply ────────────────────────────────────────────────────
        ChatbotConversation::create([
            'lead_id'    => $lead?->id,
            'session_id' => $sessionId,
            'user_id'    => $tenantId,
            'role'       => 'assistant',
            'message'    => $aiReply,
        ]);

        // ── Extract and save visitor info if captured ────────────────────────
        $extractedData = $this->extractVisitorInfo($history->pluck('message', 'role')->toArray(), $userMessage);

        if ($extractedData && !$lead) {
            $lead = $this->createOrUpdateLead($extractedData, $sessionId, $request->user(), $tenantId);
            ChatbotConversation::where('session_id', $sessionId)->update(['lead_id' => $lead->id]);
        }

        // ── Check if requirements should be saved ────────────────────────────
        if ($lead && strlen($userMessage) > 50) {
            $this->maybeExtractRequirement($lead, $userMessage, $history);
        }

        return response()->json([
            'reply'   => $aiReply,
            'lead_id' => $lead?->id,
        ]);
    }

    /**
     * Build a tenant-specific system prompt.
     */
    private function buildSystemPrompt(?User $tenant, string $trainingData): string
    {
        if (!$tenant) {
            // Generic fallback
            return "You are a helpful AI assistant. Answer questions politely and professionally.\n\nKNOWLEDGE BASE:\n{$trainingData}";
        }

        $name       = $tenant->name;
        $email      = $tenant->email;
        $profession = $tenant->profession ?? 'professional';
        $tagline    = $tenant->profile_tagline ?? '';
        $username   = $tenant->username ?? '';

        // Use AI assistant name from site_settings if set
        $aiName = \App\Models\SiteSetting::getValueForTenant($tenant->id, 'ai_assistant_name', $name . ' AI');
        $name   = $aiName; // Override name with AI identity

        // Determine persona based on profession/template — read from site_settings first
        $template = \App\Models\SiteSetting::getValueForTenant($tenant->id, 'profile_template')
            ?? $tenant->profile_template
            ?? 'consultant';

        switch ($template) {
            case 'influencer':
                return <<<PROMPT
You are {$name}'s AI assistant on their personal website. You represent {$name} — a lifestyle influencer and content creator.

YOUR ROLE:
- Help brands and followers with collaboration enquiries
- Answer questions about {$name}'s content, platforms, and partnerships
- Collect brand collaboration details (brand name, campaign brief, budget, timeline)
- Direct serious enquiries to {$email}

YOUR PERSONALITY:
- Warm, friendly, and enthusiastic
- Authentic and relatable — match the influencer's tone
- Helpful and responsive

KNOWLEDGE BASE FROM TRAINING:
{$trainingData}

IMPORTANT RULES:
- Keep responses concise and friendly (2-3 sentences max)
- For collaboration enquiries, collect: brand name, product/service, campaign type, timeline, and budget
- Always end with a helpful next step
- Never make up rates or statistics
PROMPT;

            case 'advocate':
                return <<<PROMPT
You are the AI assistant for {$name}, Senior Advocate. You represent {$name}'s legal practice.

YOUR ROLE:
- Help potential clients understand the legal services offered
- Collect details about their legal matter to help {$name} prepare for a consultation
- Schedule consultation requests
- Provide general legal information (NOT specific legal advice)

YOUR PERSONALITY:
- Professional, precise, and reassuring
- Empathetic — legal matters are stressful; acknowledge that
- Clear and jargon-free unless the client uses legal terms

KNOWLEDGE BASE FROM TRAINING:
{$trainingData}

IMPORTANT RULES:
- Always clarify: "This is general information, not legal advice. Please consult {$name} for advice specific to your matter."
- Collect: nature of legal matter, jurisdiction, urgency, and contact details
- Keep responses concise (2-4 sentences)
- Direct all consultation requests to {$email}
PROMPT;

            case 'doctor':
                return <<<PROMPT
You are the AI assistant for Dr. {$name}. You help patients with appointment enquiries and general health information.

YOUR ROLE:
- Help patients book appointments
- Answer general questions about the doctor's specialisation and services
- Collect patient details for appointment scheduling

IMPORTANT DISCLAIMER:
- Always state: "This is general information only. Please consult Dr. {$name} for medical advice specific to your condition."
- Never diagnose or prescribe

KNOWLEDGE BASE FROM TRAINING:
{$trainingData}
PROMPT;

            default:
                // Entrepreneur / IT Professional / General
                return <<<PROMPT
You are {$name}'s AI Business Assistant. You represent {$name} — {$profession}.

YOUR DUAL ROLE:
1. BUSINESS SALES PERSON: Understand the visitor's needs and position {$name}'s services as the perfect solution.
2. BUSINESS ANALYST: Systematically gather requirements — current problems, desired outcomes, existing systems, team size, budget, and timeline.

YOUR PERSONALITY:
- Warm, confident, and highly professional
- Empathetic — show you understand their pain before offering solutions
- Ask smart, probing questions like a seasoned consultant
- Never be pushy — guide naturally toward sharing requirements

YOUR PRIMARY GOAL:
Gather complete requirements from the visitor. Then summarize and tell them {$name} will send a detailed scope document.

KNOWLEDGE BASE FROM TRAINING:
{$trainingData}

IMPORTANT RULES:
- Keep responses concise (2-4 sentences max)
- Always end with ONE clear question to move the conversation forward
- Never make up pricing — say "{$name} will provide a detailed quote after reviewing your requirements"
- Contact: {$email}
PROMPT;
        }
    }

    /**
     * Initialize a chat session — returns session info and greeting.
     */
    public function init(Request $request)
    {
        $tenant = $this->resolveTenant($request);
        $sessionId = 'cb_' . uniqid() . '_' . time();
        $user = $request->user();

        // Use AI assistant name from site_settings if set
        $tenantName = $tenant
            ? (\App\Models\SiteSetting::getValueForTenant($tenant->id, 'ai_assistant_name', $tenant->name . ' AI'))
            : 'AI Assistant';
        // Read template from site_settings first
        $template = $tenant
            ? (\App\Models\SiteSetting::getValueForTenant($tenant->id, 'profile_template') ?? $tenant->profile_template ?? 'consultant')
            : 'consultant';

        if ($template === 'influencer') {
            $greeting = "Hi there! 👋 Welcome to {$tenantName}'s page! I'm {$tenantName}'s AI assistant. I can help with brand collaboration enquiries or any questions you have. What can I help you with today?";
            if ($user) {
                $greeting = "Hi {$user->name}! 👋 Great to see you here. I'm {$tenantName}'s AI assistant. How can I help you today?";
            }
        } elseif ($template === 'advocate') {
            $greeting = "Hello! Welcome to {$tenantName}'s legal practice. I'm the AI assistant here. I can help you understand our legal services or take details for a consultation. How can I assist you today?";
            if ($user) {
                $greeting = "Hello {$user->name}! Welcome to {$tenantName}'s legal practice. How can I assist you with your legal matter today?";
            }
        } else {
            $greeting = "Hi there! 👋 I'm {$tenantName}'s AI assistant. I'm here to understand your needs and help connect you with the right solutions.\n\nBefore we dive in, could I get your name?";
            if ($user) {
                $greeting = "Hi {$user->name}! 👋 Great to see you here. I'm {$tenantName}'s AI assistant.\n\nI'd love to understand what you're looking to build or achieve. What's on your mind?";
            }
        }

        return response()->json([
            'session_id' => $sessionId,
            'greeting'   => $greeting,
            'user'       => $user ? ['name' => $user->name, 'email' => $user->email] : null,
        ]);
    }

    /**
     * Save visitor contact info explicitly.
     */
    public function saveContact(Request $request)
    {
        $request->validate([
            'session_id'      => 'required|string',
            'name'            => 'required|string|max:100',
            'email'           => 'required|email|max:150',
            'mobile'          => 'nullable|string|max:20',
            'tenant_username' => 'nullable|string|max:100',
        ]);

        $tenant = $this->resolveTenant($request);
        $tenantId = $tenant?->id;

        // Scope lead lookup to this tenant to prevent cross-tenant bleed
        $lead = CrmLead::where('user_id', $tenantId)
            ->where(function($q) use ($request) {
                $q->where('email', $request->email ?: null)
                  ->orWhere('mobile', $request->mobile ?: null);
            })
            ->first();

        if (!$lead) {
            $lead = CrmLead::create([
                'name'    => $request->name,
                'email'   => $request->email ?: null,
                'mobile'  => $request->mobile ?: null,
                'source'  => 'chatbot',
                'status'  => 'new',
                'user_id' => $tenantId,
            ]);
        } else {
            $lead->update(['name' => $request->name, 'source' => 'chatbot']);
        }

        ChatbotConversation::where('session_id', $request->session_id)->update([
            'lead_id' => $lead->id,
            'user_id' => $tenantId,
        ]);

        if ($request->user() && $request->mobile && !$request->user()->mobile) {
            $request->user()->update(['mobile' => $request->mobile]);
        }

        return response()->json(['lead_id' => $lead->id, 'success' => true]);
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    private function extractVisitorInfo(array $messages, string $latestMessage): ?array
    {
        $allText = implode(' ', array_values($messages)) . ' ' . $latestMessage;

        $email  = null;
        $mobile = null;
        $name   = null;

        if (preg_match('/[\w.+-]+@[\w-]+\.\w+/', $allText, $m)) {
            $email = $m[0];
        }
        if (preg_match('/(?:\+91|0)?[6-9]\d{9}/', $allText, $m)) {
            $mobile = $m[0];
        }

        if ($email || $mobile) {
            return compact('name', 'email', 'mobile');
        }

        return null;
    }

    private function createOrUpdateLead(?array $data, string $sessionId, $user = null, ?int $tenantId = null): CrmLead
    {
        $attrs = [
            'name'    => $data['name'] ?? ($user?->name ?? 'Unknown Visitor'),
            'email'   => $data['email'] ?? $user?->email,
            'mobile'  => $data['mobile'] ?? null,
            'source'  => 'chatbot',
            'status'  => 'new',
            'user_id' => $tenantId,
        ];

        if ($attrs['email']) {
            return CrmLead::firstOrCreate(['email' => $attrs['email']], $attrs);
        }

        return CrmLead::create($attrs);
    }

    private function maybeExtractRequirement(CrmLead $lead, string $message, $history): void
    {
        // Simple heuristic: if message contains business requirement keywords
        $keywords = ['need', 'want', 'looking for', 'require', 'build', 'develop', 'automate', 'integrate', 'problem', 'issue', 'budget', 'timeline'];
        $lower = strtolower($message);
        $hasKeyword = false;
        foreach ($keywords as $kw) {
            if (str_contains($lower, $kw)) {
                $hasKeyword = true;
                break;
            }
        }

        if ($hasKeyword) {
            CrmRequirement::firstOrCreate(
                ['lead_id' => $lead->id, 'requirement' => substr($message, 0, 500)],
                ['status' => 'new']
            );
        }
    }
}
