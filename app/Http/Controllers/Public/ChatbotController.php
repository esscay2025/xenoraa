<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CrmLead;
use App\Models\CrmRequirement;
use App\Models\ChatbotConversation;
use App\Models\ChatbotTraining;
use App\Models\User;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    // ── Tenant resolver ──────────────────────────────────────────────────────

    protected function resolveTenant(Request $request): ?User
    {
        $host       = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');

        // Xenoraa main site — no tenant (handled by Xenoraa AI widget separately)
        if ($host === $mainDomain || $host === 'www.' . $mainDomain) {
            // Still allow tenant_username override for embedded widgets
            $username = $request->header('X-Tenant-Username') ?? $request->input('tenant_username');
            if ($username && $username !== 'xenoraa') {
                return User::where('username', $username)->first();
            }
            return null; // Xenoraa platform AI
        }

        // Custom domain
        $tenant = User::where('custom_domain', $host)
            ->orWhere('custom_domain', 'www.' . $host)
            ->first();
        if ($tenant) return $tenant;

        // Username passed from JS
        $username = $request->header('X-Tenant-Username') ?? $request->input('tenant_username');
        if ($username) {
            return User::where('username', $username)->first();
        }

        return null;
    }

    // ── Main chat handler ────────────────────────────────────────────────────

    public function chat(Request $request)
    {
        $request->validate([
            'message'         => 'required|string|max:2000',
            'session_id'      => 'required|string|max:100',
            'lead_id'         => 'nullable|integer',
            'tenant_username' => 'nullable|string|max:100',
            'intent'          => 'nullable|in:sales,support,general',
        ]);

        $tenant    = $this->resolveTenant($request);
        $tenantId  = $tenant?->id;
        $sessionId = $request->session_id;
        $userMsg   = trim($request->message);
        $leadId    = $request->lead_id;
        $intent    = $request->input('intent', 'general');

        // Load existing lead
        $lead = $leadId ? CrmLead::find($leadId) : null;

        // Save user message
        ChatbotConversation::create([
            'lead_id'    => $lead?->id,
            'session_id' => $sessionId,
            'user_id'    => $tenantId,
            'role'       => 'user',
            'message'    => $userMsg,
        ]);

        // Load conversation history (last 20 messages to keep context manageable)
        $history = ChatbotConversation::where('session_id', $sessionId)
            ->orderBy('created_at')
            ->limit(20)
            ->get();

        // Load training data
        $trainingData = $this->loadTrainingData($tenantId, $intent);

        // Build system prompt
        $systemPrompt = $this->buildSystemPrompt($tenant, $trainingData, $intent);

        // Build OpenAI messages array
        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        foreach ($history as $msg) {
            $messages[] = [
                'role'    => $msg->role === 'user' ? 'user' : 'assistant',
                'content' => $msg->message,
            ];
        }

        // Call OpenAI
        $aiReply = $this->callOpenAI($messages, $tenant);

        // Save AI reply
        ChatbotConversation::create([
            'lead_id'    => $lead?->id,
            'session_id' => $sessionId,
            'user_id'    => $tenantId,
            'role'       => 'assistant',
            'message'    => $aiReply,
        ]);

        // Auto-extract visitor info and create/update lead
        $extracted = $this->extractVisitorInfo($history->pluck('message')->toArray(), $userMsg);
        if ($extracted && !$lead) {
            $lead = $this->createOrUpdateLead($extracted, $sessionId, $request->user(), $tenantId);
            ChatbotConversation::where('session_id', $sessionId)->update(['lead_id' => $lead->id]);
        }

        // Save requirement if message is substantial
        if ($lead && strlen($userMsg) > 40) {
            $this->maybeExtractRequirement($lead, $userMsg, $history);
        }

        return response()->json([
            'reply'   => $aiReply,
            'lead_id' => $lead?->id,
        ]);
    }

    // ── Init session ─────────────────────────────────────────────────────────

    public function init(Request $request)
    {
        $tenant    = $this->resolveTenant($request);
        $sessionId = 'cb_' . uniqid() . '_' . time();
        $user      = $request->user();

        if (!$tenant) {
            // Xenoraa platform AI
            $greeting = "Hi there! 👋 I'm **Xena**, Xenoraa's AI assistant.\n\nI can help you with sales enquiries, product demos, pricing, or technical support.\n\nBefore we begin, could I get your **name**?";
            if ($user) {
                $greeting = "Hi {$user->name}! 👋 I'm Xena, Xenoraa's AI assistant. How can I help you today — are you looking for sales information or technical support?";
            }
        } else {
            $aiName   = \App\Models\SiteSetting::getValueForTenant($tenant->id, 'ai_assistant_name', $tenant->name . ' AI');
            $template = \App\Models\SiteSetting::getValueForTenant($tenant->id, 'profile_template')
                ?? $tenant->profile_template ?? 'consultant';

            switch ($template) {
                case 'influencer':
                    $greeting = "Hi there! 👋 Welcome to {$aiName}'s page! I'm the AI assistant here. I can help with brand collaboration enquiries or any questions you have. What can I help you with?";
                    break;
                case 'advocate':
                    $greeting = "Hello! Welcome to {$aiName}'s legal practice. I'm the AI assistant here. I can help you understand our legal services or take details for a consultation. How can I assist you today?";
                    break;
                case 'doctor':
                    $greeting = "Hello! Welcome. I'm the AI assistant for {$aiName}. I can help with appointment enquiries and general information about our services. How can I help you?";
                    break;
                default:
                    $greeting = "Hi there! 👋 I'm {$aiName}'s AI assistant. I'm here to understand your needs and connect you with the right solutions.\n\nCould I start with your **name**?";
                    if ($user) {
                        $greeting = "Hi {$user->name}! 👋 I'm {$aiName}'s AI assistant. What can I help you with today?";
                    }
            }
        }

        return response()->json([
            'session_id' => $sessionId,
            'greeting'   => $greeting,
            'user'       => $user ? ['name' => $user->name, 'email' => $user->email] : null,
        ]);
    }

    // ── Save contact ─────────────────────────────────────────────────────────

    public function saveContact(Request $request)
    {
        $request->validate([
            'session_id'      => 'required|string',
            'name'            => 'required|string|max:100',
            'email'           => 'required|email|max:150',
            'mobile'          => 'nullable|string|max:20',
            'tenant_username' => 'nullable|string|max:100',
            'intent'          => 'nullable|in:sales,support,general',
        ]);

        $tenant   = $this->resolveTenant($request);
        $tenantId = $tenant?->id;
        $intent   = $request->input('intent', 'general');

        $lead = CrmLead::where('user_id', $tenantId)
            ->where(function ($q) use ($request) {
                $q->where('email', $request->email ?: null)
                  ->orWhere('mobile', $request->mobile ?: null);
            })
            ->first();

        if (!$lead) {
            $lead = CrmLead::create([
                'name'    => $request->name,
                'email'   => $request->email ?: null,
                'mobile'  => $request->mobile ?: null,
                'source'  => $tenantId ? 'chatbot' : 'xenoraa_chat',
                'status'  => 'new',
                'notes'   => $intent !== 'general' ? "Intent: {$intent}" : null,
                'user_id' => $tenantId,
            ]);
        } else {
            $lead->update(['name' => $request->name]);
        }

        ChatbotConversation::where('session_id', $request->session_id)->update([
            'lead_id' => $lead->id,
            'user_id' => $tenantId,
        ]);

        // Build a personalised first reply
        $aiName = $tenant
            ? \App\Models\SiteSetting::getValueForTenant($tenant->id, 'ai_assistant_name', $tenant->name . ' AI')
            : 'Xena';

        $intentMsg = match ($intent) {
            'sales'   => "I'd love to walk you through our plans and find the best fit for your business. What are you looking to achieve?",
            'support' => "I'm here to help! Could you describe the issue you're experiencing so I can assist you quickly?",
            default   => "How can I help you today?",
        };

        return response()->json([
            'lead_id' => $lead->id,
            'success' => true,
            'message' => "Thanks {$request->name}! {$intentMsg}",
        ]);
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    private function loadTrainingData(?int $tenantId, string $intent = 'general'): string
    {
        $q = ChatbotTraining::where('is_active', true)->orderBy('category')->orderBy('sort_order');

        if ($tenantId) {
            $q->where('user_id', $tenantId);
        } else {
            // Xenoraa platform training — user_id IS NULL (platform-level)
            $q->whereNull('user_id');
            // Filter by intent category if not general
            if ($intent !== 'general') {
                $q->where(function ($q2) use ($intent) {
                    $q2->where('category', $intent)
                       ->orWhere('category', 'general')
                       ->orWhere('category', 'xenoraa');
                });
            }
        }

        $data = $q->get()->map(fn($t) => "Q: {$t->question}\nA: {$t->answer}")->implode("\n\n");

        return $data ?: '';
    }

    private function buildSystemPrompt(?User $tenant, string $trainingData, string $intent = 'general'): string
    {
        $kb = $trainingData
            ? "\n\n---\nKNOWLEDGE BASE:\n{$trainingData}\n---"
            : '';

        // Xenoraa platform AI (no tenant)
        if (!$tenant) {
            $intentContext = match ($intent) {
                'sales'   => "The visitor is interested in purchasing or learning about Xenoraa's plans and features. Focus on understanding their business needs and recommending the right plan. Highlight value, ROI, and ease of getting started.",
                'support' => "The visitor needs technical help or has an issue with their Xenoraa account. Be patient, methodical, and solution-focused. Collect: account email, issue description, steps already tried.",
                default   => "Help the visitor with either sales or support. First understand what they need.",
            };

            return <<<PROMPT
You are **Xena**, the official AI assistant for **Xenoraa** — a powerful SaaS platform that helps professionals, businesses, and entrepreneurs build their digital presence with AI-powered websites, CRM, POS, e-commerce, and more.

YOUR ROLE:
{$intentContext}

YOUR PERSONALITY:
- Professional, warm, and confident
- Concise — 2-4 sentences per reply maximum
- Always end with ONE clear question or next step
- Never make up pricing — refer to xenoraa.com/pricing for exact details
- For complex issues, say "Let me connect you with our team" and collect their contact details

CONTACT COLLECTION:
- Always collect: Name, Email, Mobile (optional)
- For sales: also collect company name, team size, and what they want to build
- For support: also collect their account email and issue description
{$kb}

IMPORTANT:
- You represent Xenoraa — always be professional and on-brand
- Never discuss competitors negatively
- If unsure, say "Great question — let me get our team to follow up with you on that"
PROMPT;
        }

        // Tenant-specific AI
        $name       = $tenant->name;
        $email      = $tenant->email;
        $profession = $tenant->profession ?? 'professional services';
        $aiName     = \App\Models\SiteSetting::getValueForTenant($tenant->id, 'ai_assistant_name', $name . ' AI');
        $template   = \App\Models\SiteSetting::getValueForTenant($tenant->id, 'profile_template')
            ?? $tenant->profile_template ?? 'consultant';

        switch ($template) {
            case 'influencer':
                return <<<PROMPT
You are the AI assistant for **{$aiName}**, a lifestyle influencer and content creator.

YOUR ROLE:
- Help brands with collaboration enquiries
- Answer questions about {$name}'s content, platforms, and partnerships
- Collect: brand name, product/service, campaign type, timeline, and budget
- Direct serious enquiries to {$email}

YOUR PERSONALITY:
- Warm, friendly, enthusiastic, and authentic
- Keep responses to 2-3 sentences
- Always end with a helpful next step
{$kb}
PROMPT;

            case 'advocate':
                return <<<PROMPT
You are the AI assistant for **{$aiName}**, Senior Advocate.

YOUR ROLE:
- Help potential clients understand the legal services offered
- Collect details about their legal matter for consultation preparation
- Provide general legal information (NOT specific legal advice)

YOUR PERSONALITY:
- Professional, precise, empathetic, and reassuring
- Always clarify: "This is general information, not legal advice."
- Collect: nature of legal matter, jurisdiction, urgency, and contact details
- Keep responses to 2-4 sentences
- Direct consultation requests to {$email}
{$kb}
PROMPT;

            case 'doctor':
                return <<<PROMPT
You are the AI assistant for **Dr. {$aiName}**.

YOUR ROLE:
- Help patients with appointment enquiries
- Answer general questions about specialisation and services
- Collect patient details for appointment scheduling

IMPORTANT:
- Always state: "This is general information only. Please consult Dr. {$name} for medical advice."
- Never diagnose or prescribe
- Keep responses to 2-3 sentences
{$kb}
PROMPT;

            default:
                return <<<PROMPT
You are **{$aiName}**, the AI Business Assistant representing **{$name}** — {$profession}.

YOUR DUAL ROLE:
1. **Business Advisor**: Understand the visitor's needs and show how {$name}'s services solve their problems
2. **Requirements Gatherer**: Systematically collect — current challenges, desired outcomes, existing systems, team size, budget, and timeline

YOUR PERSONALITY:
- Warm, confident, and highly professional
- Empathetic — acknowledge their pain before offering solutions
- Ask smart, probing questions like a seasoned consultant
- Never be pushy — guide naturally
- Keep responses to 2-4 sentences maximum
- Always end with ONE clear question

YOUR GOAL:
Gather complete requirements, then summarise and say "{$name} will send a detailed proposal."

CONTACT: {$email}
{$kb}

RULES:
- Never make up pricing
- If asked about rates, say "{$name} will provide a detailed quote after reviewing your requirements"
PROMPT;
        }
    }

    private function callOpenAI(array $messages, ?User $tenant): string
    {
        try {
            $apiKey  = config('services.openai.api_key') ?: env('OPENAI_API_KEY');
            $apiBase = rtrim(config('services.openai.base_url') ?: env('OPENAI_API_BASE', 'https://api.openai.com/v1'), '/');
            $http    = new GuzzleClient(['timeout' => 30]);

            $res  = $http->post($apiBase . '/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'       => 'gpt-4o-mini',
                    'messages'    => $messages,
                    'max_tokens'  => 500,
                    'temperature' => 0.65,
                ],
            ]);

            $body = json_decode($res->getBody()->getContents(), true);
            return $body['choices'][0]['message']['content'] ?? 'I could not generate a response. Please try again.';

        } catch (\Exception $e) {
            Log::error('Chatbot OpenAI error: ' . $e->getMessage());
            $name  = $tenant?->name ?? 'Xenoraa';
            $email = $tenant?->email ?? 'support@xenoraa.com';
            return "I'm having a small technical hiccup right now. Please try again in a moment, or reach out directly to {$name} at {$email}.";
        }
    }

    private function extractVisitorInfo(array $messages, string $latest): ?array
    {
        $allText = implode(' ', $messages) . ' ' . $latest;
        $email   = null;
        $mobile  = null;
        $name    = null;

        if (preg_match('/[\w.+\-]+@[\w\-]+\.\w+/', $allText, $m)) {
            $email = $m[0];
        }
        if (preg_match('/(?:\+91[\s\-]?|0)?[6-9]\d{9}/', $allText, $m)) {
            $mobile = preg_replace('/[\s\-]/', '', $m[0]);
        }

        return ($email || $mobile) ? compact('name', 'email', 'mobile') : null;
    }

    private function createOrUpdateLead(?array $data, string $sessionId, $user = null, ?int $tenantId = null): CrmLead
    {
        $attrs = [
            'name'    => $data['name'] ?? ($user?->name ?? 'Website Visitor'),
            'email'   => $data['email'] ?? $user?->email,
            'mobile'  => $data['mobile'] ?? null,
            'source'  => $tenantId ? 'chatbot' : 'xenoraa_chat',
            'status'  => 'new',
            'user_id' => $tenantId,
        ];

        if ($attrs['email']) {
            return CrmLead::firstOrCreate(
                ['email' => $attrs['email'], 'user_id' => $tenantId],
                $attrs
            );
        }

        return CrmLead::create($attrs);
    }

    private function maybeExtractRequirement(CrmLead $lead, string $message, $history): void
    {
        $keywords = ['need', 'want', 'looking for', 'require', 'build', 'develop', 'automate',
                     'integrate', 'problem', 'issue', 'budget', 'timeline', 'help with', 'struggling'];
        $lower    = strtolower($message);

        foreach ($keywords as $kw) {
            if (str_contains($lower, $kw)) {
                CrmRequirement::firstOrCreate(
                    ['lead_id' => $lead->id, 'requirement' => substr($message, 0, 500)],
                    ['status' => 'new']
                );
                break;
            }
        }
    }
}
