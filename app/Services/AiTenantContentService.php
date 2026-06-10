<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * AiTenantContentService
 *
 * Generates realistic, profession-specific website content for a new tenant
 * using OpenAI GPT-4o-mini, based on business information provided during onboarding.
 */
class AiTenantContentService
{
    protected string $apiKey;
    protected string $apiBase;

    public function __construct()
    {
        $this->apiKey  = config('services.openai.api_key', env('OPENAI_API_KEY', ''));
        $this->apiBase = config('services.openai.api_base', env('OPENAI_API_BASE', 'https://api.openai.com/v1'));
    }

    /**
     * Generate and apply AI content to the tenant's site based on business info.
     *
     * @param  User   $user
     * @param  string $businessInfo  Raw text describing the business
     * @return array  Generated content summary
     */
    public function generateAndApply(User $user, string $businessInfo): array
    {
        $profession = $user->profession ?? 'business';
        $name       = $user->name;

        $prompt = $this->buildPrompt($name, $profession, $businessInfo);

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(60)
                ->post("{$this->apiBase}/chat/completions", [
                    'model'       => 'gpt-4o-mini',
                    'temperature' => 0.7,
                    'messages'    => [
                        [
                            'role'    => 'system',
                            'content' => 'You are an expert website content writer. Generate realistic, professional website content in JSON format only. No markdown, no explanation, only valid JSON.',
                        ],
                        [
                            'role'    => 'user',
                            'content' => $prompt,
                        ],
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning("AiTenantContentService: OpenAI API error for user {$user->id}: " . $response->body());
                return ['success' => false, 'error' => 'AI generation failed'];
            }

            $raw  = $response->json('choices.0.message.content', '{}');
            $data = json_decode($raw, true);

            if (!$data || !is_array($data)) {
                Log::warning("AiTenantContentService: Invalid JSON from OpenAI for user {$user->id}: " . $raw);
                return ['success' => false, 'error' => 'Invalid AI response'];
            }

            // Apply the generated content to the tenant's site settings
            $this->applyContent($user, $data);

            return ['success' => true, 'data' => $data];

        } catch (\Throwable $e) {
            Log::error("AiTenantContentService: Exception for user {$user->id}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Build the GPT prompt based on profession and business info.
     */
    protected function buildPrompt(string $name, string $profession, string $businessInfo): string
    {
        $professionContext = $this->getProfessionContext($profession);

        return <<<PROMPT
Generate professional website content for a {$professionContext} named "{$name}".

Business Information provided by the owner:
---
{$businessInfo}
---

Return ONLY a valid JSON object with these exact keys:
{
  "site_title": "Short catchy website title (max 60 chars)",
  "tagline": "One-line professional tagline (max 100 chars)",
  "about_heading": "Heading for the About section (max 60 chars)",
  "about_text": "2-3 paragraph professional about/bio text (300-500 words)",
  "hero_heading": "Hero section main heading (max 80 chars)",
  "hero_subheading": "Hero section subheading (max 150 chars)",
  "hero_cta": "Call-to-action button text (max 30 chars)",
  "services": [
    {"title": "Service name", "description": "2-3 sentence description", "icon": "fas fa-icon-name"},
    {"title": "Service name", "description": "2-3 sentence description", "icon": "fas fa-icon-name"},
    {"title": "Service name", "description": "2-3 sentence description", "icon": "fas fa-icon-name"},
    {"title": "Service name", "description": "2-3 sentence description", "icon": "fas fa-icon-name"}
  ],
  "testimonials": [
    {"name": "Client name", "role": "Client role/company", "text": "Testimonial text (2-3 sentences)"},
    {"name": "Client name", "role": "Client role/company", "text": "Testimonial text (2-3 sentences)"},
    {"name": "Client name", "role": "Client role/company", "text": "Testimonial text (2-3 sentences)"}
  ],
  "contact_heading": "Contact section heading",
  "contact_subheading": "Contact section subheading",
  "footer_tagline": "Short footer tagline (max 80 chars)",
  "meta_description": "SEO meta description (max 160 chars)",
  "keywords": "5-8 SEO keywords separated by commas",
  "chatbot_greeting": "AI chatbot greeting message for website visitors (max 100 chars)",
  "chatbot_about": "2-3 sentences for chatbot to know about this business for answering visitor questions"
}
PROMPT;
    }

    /**
     * Get profession-specific context for the prompt.
     */
    protected function getProfessionContext(string $profession): string
    {
        $contexts = [
            'ecommerce'          => 'e-commerce business / online store',
            'business'           => 'company / business organisation',
            'entrepreneur'       => 'entrepreneur / startup founder',
            'consultant'         => 'consultant / business advisor',
            'doctor'             => 'medical professional / doctor',
            'advocate'           => 'lawyer / legal professional',
            'educator'           => 'educator / trainer / coach',
            'freelancer'         => 'freelancer / independent professional',
            'influencer'         => 'content creator / influencer',
            'software_developer' => 'software developer / tech professional',
            'designer'           => 'designer / creative professional',
            'politician'         => 'politician / public leader',
        ];

        return $contexts[$profession] ?? 'professional / business owner';
    }

    /**
     * Apply generated content to the tenant's site settings and pages.
     */
    protected function applyContent(User $user, array $data): void
    {
        $userId = $user->id;

        // Update SiteSettings
        $settings = \App\Models\SiteSetting::where('user_id', $userId)->first();
        if ($settings) {
            $updates = [];
            if (!empty($data['site_title']))     $updates['site_name']         = $data['site_title'];
            if (!empty($data['tagline']))         $updates['tagline']           = $data['tagline'];
            if (!empty($data['meta_description'])) $updates['meta_description'] = $data['meta_description'];
            if (!empty($data['keywords']))        $updates['meta_keywords']     = $data['keywords'];
            if (!empty($data['chatbot_greeting'])) $updates['chatbot_greeting'] = $data['chatbot_greeting'];
            if (!empty($updates)) $settings->update($updates);
        }

        // Update chatbot training with business context
        if (!empty($data['chatbot_about'])) {
            $chatbotSettings = \App\Models\ChatbotSetting::where('user_id', $userId)->first();
            if ($chatbotSettings) {
                $existing = $chatbotSettings->training_data ?? '';
                $chatbotSettings->update([
                    'training_data' => $existing . "\n\n" . $data['chatbot_about'],
                ]);
            }
        }

        // Update the Home page content if it exists
        $homePage = \App\Models\Page::where('user_id', $userId)
            ->where('slug', 'home')
            ->first();

        if ($homePage) {
            $sections = $homePage->sections ?? [];

            // Update hero section
            foreach ($sections as &$section) {
                if (($section['type'] ?? '') === 'hero') {
                    if (!empty($data['hero_heading']))    $section['heading']    = $data['hero_heading'];
                    if (!empty($data['hero_subheading'])) $section['subheading'] = $data['hero_subheading'];
                    if (!empty($data['hero_cta']))        $section['cta_text']   = $data['hero_cta'];
                }
                if (($section['type'] ?? '') === 'about') {
                    if (!empty($data['about_heading'])) $section['heading'] = $data['about_heading'];
                    if (!empty($data['about_text']))    $section['content'] = $data['about_text'];
                }
                if (($section['type'] ?? '') === 'services' && !empty($data['services'])) {
                    $section['items'] = $data['services'];
                }
                if (($section['type'] ?? '') === 'testimonials' && !empty($data['testimonials'])) {
                    $section['items'] = $data['testimonials'];
                }
            }
            unset($section);

            $homePage->update(['sections' => $sections]);
        }

        // Update the About page if it exists
        $aboutPage = \App\Models\Page::where('user_id', $userId)
            ->where('slug', 'about')
            ->first();

        if ($aboutPage && !empty($data['about_text'])) {
            $sections = $aboutPage->sections ?? [];
            foreach ($sections as &$section) {
                if (in_array($section['type'] ?? '', ['about', 'text', 'content'])) {
                    $section['content'] = $data['about_text'];
                    break;
                }
            }
            unset($section);
            $aboutPage->update(['sections' => $sections]);
        }

        // Save the raw AI-generated data to the user's business_info_ai column
        $user->update([
            'business_info_ai' => json_encode($data),
        ]);

        Log::info("AiTenantContentService: Applied AI content for user {$user->id}");
    }
}
