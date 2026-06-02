<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CrmLead;
use App\Models\CrmRequirement;
use App\Models\ChatbotConversation;
use App\Models\ChatbotTraining;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI\Client as OpenAIClient;

class ChatbotController extends Controller
{
    /**
     * Handle an incoming chat message from the popup widget.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message'    => 'required|string|max:2000',
            'session_id' => 'required|string|max:100',
            'lead_id'    => 'nullable|integer',
        ]);

        $sessionId = $request->session_id;
        $userMessage = trim($request->message);
        $leadId = $request->lead_id;

        // ── Load or create lead ──────────────────────────────────────────────
        $lead = $leadId ? CrmLead::find($leadId) : null;

        // ── Save user message ────────────────────────────────────────────────
        ChatbotConversation::create([
            'lead_id'    => $lead?->id,
            'session_id' => $sessionId,
            'role'       => 'user',
            'message'    => $userMessage,
        ]);

        // ── Load conversation history for this session ───────────────────────
        $history = ChatbotConversation::where('session_id', $sessionId)
            ->orderBy('created_at')
            ->get();

        // ── Build system prompt ──────────────────────────────────────────────
        $trainingData = ChatbotTraining::where('is_active', true)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->map(fn($t) => "Category: {$t->category}\nQ: {$t->question}\nA: {$t->answer}")
            ->implode("\n\n");

        $systemPrompt = <<<PROMPT
You are Gopi K's AI assistant on gopi.blog. You represent Gopi K personally — a technology entrepreneur, automation expert, and founder of Go Esscay Solutions based in Chennai, India.

YOUR PERSONALITY:
- Warm, professional, and knowledgeable
- Speak as if you ARE Gopi's representative
- Be conversational but focused on understanding the visitor's needs
- Always guide the conversation toward capturing their requirements

YOUR PRIMARY GOAL:
Capture the visitor's business requirements, pain points, and what they need help with. Then summarize it clearly.

CONVERSATION FLOW:
1. If you don't have the visitor's name yet, ask for it first.
2. If you don't have their mobile number, ask for it (email is optional if they're logged in).
3. Once you have their basic info, start the requirements conversation.
4. Ask about: what they want to automate, problems they face, current tools they use, budget range, timeline.
5. At the end, summarize the requirements and tell them Gopi will review and send a scope document.

SERVICES GOPI OFFERS:
- AI Solutions & Automation (chatbots, workflow automation, AI integrations)
- Custom Application Development (web apps, mobile apps, SaaS products)
- Digital Transformation (legacy modernization, cloud migration)
- Startup Product Development (MVP, product strategy, technical co-founder)
- Branding & Digital Presence (websites, personal branding, digital marketing)

KNOWLEDGE BASE FROM TRAINING:
{$trainingData}

IMPORTANT RULES:
- Keep responses concise (2-4 sentences max unless explaining something complex)
- Always move the conversation forward with a question
- If the visitor shares a requirement, acknowledge it and dig deeper
- Never make up pricing — say "Gopi will provide a detailed quote after reviewing your requirements"
- If asked something outside your knowledge, say "I'll make sure Gopi personally addresses this"
PROMPT;

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
                'model'       => 'gpt-4.1-mini',
                'messages'    => $messages,
                'max_tokens'  => 400,
                'temperature' => 0.7,
            ]);
            $aiReply = $response->choices[0]->message->content;
        } catch (\Exception $e) {
            Log::error('Chatbot OpenAI error: ' . $e->getMessage());
            $aiReply = "I'm having a small technical hiccup. Please try again in a moment, or feel free to reach out to Gopi directly at gopi@outlook.in.";
        }

        // ── Save AI reply ────────────────────────────────────────────────────
        ChatbotConversation::create([
            'lead_id'    => $lead?->id,
            'session_id' => $sessionId,
            'role'       => 'assistant',
            'message'    => $aiReply,
        ]);

        // ── Extract and save visitor info if captured ────────────────────────
        $extractedData = $this->extractVisitorInfo($history->pluck('message', 'role')->toArray(), $userMessage);

        if ($extractedData && !$lead) {
            $lead = $this->createOrUpdateLead($extractedData, $sessionId, $request->user());
            // Update all messages in this session with the lead_id
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
     * Initialize a chat session — returns session info and greeting.
     */
    public function init(Request $request)
    {
        $sessionId = 'cb_' . uniqid() . '_' . time();
        $user = $request->user();

        $greeting = "Hi there! 👋 I'm Gopi's AI assistant. I'm here to understand your business needs and help connect you with the right solutions.\n\nBefore we dive in, could I get your name?";

        if ($user) {
            $greeting = "Hi {$user->name}! 👋 Great to see you here. I'm Gopi's AI assistant.\n\nI'd love to understand what you're looking to build or automate. What's on your mind?";

            // Check if we have their mobile
            if (!$user->mobile) {
                $greeting = "Hi {$user->name}! 👋 Great to see you here. I'm Gopi's AI assistant.\n\nQuick question — could you share your mobile number so Gopi can reach you directly? Then let's talk about what you need!";
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
            'session_id' => 'required|string',
            'name'       => 'required|string|max:100',
            'email'      => 'nullable|email|max:150',
            'mobile'     => 'nullable|string|max:20',
        ]);

        $lead = CrmLead::updateOrCreate(
            ['email' => $request->email ?: null, 'mobile' => $request->mobile ?: null],
            [
                'name'    => $request->name,
                'source'  => 'chatbot',
                'status'  => 'new',
                'user_id' => $request->user()?->id,
            ]
        );

        // Link all session messages to this lead
        ChatbotConversation::where('session_id', $request->session_id)->update(['lead_id' => $lead->id]);

        // Update user mobile if logged in
        if ($request->user() && $request->mobile && !$request->user()->mobile) {
            $request->user()->update(['mobile' => $request->mobile]);
        }

        return response()->json(['lead_id' => $lead->id, 'success' => true]);
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    private function extractVisitorInfo(array $messages, string $latestMessage): ?array
    {
        // Simple heuristic: look for email/phone patterns in messages
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

    private function createOrUpdateLead(?array $data, string $sessionId, $user = null): CrmLead
    {
        $attrs = [
            'name'    => $data['name'] ?? ($user?->name ?? 'Unknown Visitor'),
            'email'   => $data['email'] ?? $user?->email,
            'mobile'  => $data['mobile'] ?? null,
            'source'  => 'chatbot',
            'status'  => 'new',
            'user_id' => $user?->id,
        ];

        if ($attrs['email']) {
            return CrmLead::firstOrCreate(['email' => $attrs['email']], $attrs);
        }

        return CrmLead::create($attrs);
    }

    private function maybeExtractRequirement(CrmLead $lead, string $message, $history): void
    {
        // Only save if message looks like a requirement (contains problem/need keywords)
        $keywords = ['need', 'want', 'automate', 'build', 'develop', 'create', 'problem', 'issue', 'facing', 'help', 'solution', 'system', 'app', 'website', 'integrate', 'manage', 'track'];
        $lower = strtolower($message);
        $hasKeyword = false;
        foreach ($keywords as $kw) {
            if (str_contains($lower, $kw)) { $hasKeyword = true; break; }
        }

        if ($hasKeyword && strlen($message) > 80) {
            CrmRequirement::create([
                'lead_id'     => $lead->id,
                'requirement' => $message,
                'category'    => $this->guessCategory($lower),
            ]);

            // Update lead summary
            $lead->update(['summary' => substr($message, 0, 200)]);
        }
    }

    private function guessCategory(string $text): string
    {
        if (str_contains($text, 'automat') || str_contains($text, 'ai ') || str_contains($text, 'bot')) return 'automation';
        if (str_contains($text, 'app') || str_contains($text, 'website') || str_contains($text, 'develop')) return 'custom_app';
        if (str_contains($text, 'brand') || str_contains($text, 'marketing') || str_contains($text, 'social')) return 'branding';
        if (str_contains($text, 'startup') || str_contains($text, 'mvp') || str_contains($text, 'product')) return 'startup';
        if (str_contains($text, 'transform') || str_contains($text, 'digital') || str_contains($text, 'cloud')) return 'digital_transformation';
        return 'general';
    }
}
