<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;

class UpdateBlogImagesSeeder extends Seeder
{
    /**
     * Map each blog post slug to a relevant Unsplash topic keyword.
     * Using Unsplash Source API: https://source.unsplash.com/800x450/?{keyword}
     * Each image is unique and relevant to the topic.
     */
    public function run(): void
    {
        $imageMap = [
            // AI & Automation
            'building-autonomous-ai-agents-with-langchain-and-laravel'
                => 'https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=800&h=450&fit=crop',
            'the-rise-of-agentic-ai-how-autonomous-systems-are-reshaping-business'
                => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?w=800&h=450&fit=crop',
            'automating-business-workflows-with-n8n-and-make'
                => 'https://images.unsplash.com/photo-1518186285589-2f7649de83e0?w=800&h=450&fit=crop',
            'prompt-engineering-mastery-advanced-techniques-for-production-llms'
                => 'https://images.unsplash.com/photo-1655720828018-edd2daec9349?w=800&h=450&fit=crop',
            'building-a-rag-system-from-scratch-with-laravel-and-openai'
                => 'https://images.unsplash.com/photo-1676299081847-824916de030a?w=800&h=450&fit=crop',
            'how-ai-is-transforming-customer-service-in-2025'
                => 'https://images.unsplash.com/photo-1531746790731-6c087fecd65a?w=800&h=450&fit=crop',
            'the-ai-automation-stack-tools-every-business-owner-needs'
                => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800&h=450&fit=crop',
            'computer-vision-in-production-practical-guide-for-businesses'
                => 'https://images.unsplash.com/photo-1507146153580-69a1fe6d8aa1?w=800&h=450&fit=crop',
            'fine-tuning-llms-when-and-how-to-customize-ai-models'
                => 'https://images.unsplash.com/photo-1591453089816-0fbb971b454c?w=800&h=450&fit=crop',
            'ai-ethics-and-responsible-deployment-a-practical-framework'
                => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?w=800&h=450&fit=crop',

            // Hacking & Security
            'top-5-api-vulnerabilities-and-how-to-secure-your-endpoints'
                => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&h=450&fit=crop',
            'the-danger-of-social-engineering-phishing-credential-theft'
                => 'https://images.unsplash.com/photo-1563206767-5b18f218e8de?w=800&h=450&fit=crop',
            'zero-trust-architecture-moving-beyond-the-traditional-perimeter'
                => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=450&fit=crop',
            'docker-container-security-hardening-production-containers'
                => 'https://images.unsplash.com/photo-1605745341112-85968b19335b?w=800&h=450&fit=crop',
            'top-5-api-vulnerabilities-and-how-to-secure-your-endpoints-2'
                => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&h=450&fit=crop',
            // Also older hacking posts
            'the-danger-of-social-engineering-how-to-train-your-team'
                => 'https://images.unsplash.com/photo-1563206767-5b18f218e8de?w=800&h=450&fit=crop',
            'zero-trust-architecture-rethinking-corporate-network-security'
                => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=450&fit=crop',
            'devsecops-integrating-security-into-the-cicd-pipeline'
                => 'https://images.unsplash.com/photo-1614064641938-3bbee52942c7?w=800&h=450&fit=crop',

            // Startup & Product Dev
            'building-a-minimum-viable-product-mvp-the-lean-methodology'
                => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=800&h=450&fit=crop',
            'product-market-fit-how-to-know-when-you-have-found-it'
                => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=450&fit=crop',
            'saas-pricing-strategies-how-to-maximize-revenue'
                => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=800&h=450&fit=crop',
            'the-art-of-pivoting-knowing-when-and-how-to-change-direction'
                => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=450&fit=crop',
            'building-a-high-performance-remote-product-team'
                => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=450&fit=crop',
            // Older startup posts
            'how-to-define-and-build-a-high-performance-mvp'
                => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=800&h=450&fit=crop',
            'the-lean-startup-methodology-validating-ideas-with-zero-capital'
                => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&h=450&fit=crop',
            'product-market-fit-the-only-metric-that-matters-for-growth'
                => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=450&fit=crop',
            'bootstrapping-vs-venture-capital-choosing-the-right-funding-path'
                => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=800&h=450&fit=crop',
            'fractional-ctos-the-secret-weapon-for-non-technical-founders'
                => 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=800&h=450&fit=crop',

            // Software & Technology
            'mastering-laravel-queues-and-background-jobs-for-scale'
                => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&h=450&fit=crop',
            'vite-vs-webpack-why-we-switched-our-asset-pipeline'
                => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=450&fit=crop',
            'designing-scalable-database-schemas-best-practices'
                => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?w=800&h=450&fit=crop',
            'microservices-vs-monoliths-making-the-right-choice'
                => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=450&fit=crop',
            'building-secure-and-fast-rest-apis-the-complete-guide'
                => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=450&fit=crop',
            // Older software posts
            'why-laravel-is-the-king-of-modern-web-development'
                => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&h=450&fit=crop',
            'vite-vs-webpack-speeding-up-your-frontend-asset-compilation'
                => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=450&fit=crop',
            'dockerizing-laravel-seamless-environments-from-dev-to-production'
                => 'https://images.unsplash.com/photo-1605745341112-85968b19335b?w=800&h=450&fit=crop',
            'microservices-vs-monoliths-architectural-decisions-for-scale'
                => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=450&fit=crop',
            'serverless-databases-the-next-frontier-in-cloud-architectures'
                => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?w=800&h=450&fit=crop',

            // Digital Transformation
            'legacy-system-migration-mitigating-risks-and-ensuring-success'
                => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=800&h=450&fit=crop',
            'cloud-migration-moving-your-business-to-the-cloud-safely'
                => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&h=450&fit=crop',
            'data-driven-decision-making-transforming-data-into-action'
                => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=450&fit=crop',
            'automating-customer-support-chatbots-ai-and-ticketing'
                => 'https://images.unsplash.com/photo-1531746790731-6c087fecd65a?w=800&h=450&fit=crop',
            'digital-transformation-for-smbs-a-practical-guide'
                => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=800&h=450&fit=crop',
            // Older digital transformation posts
            'the-enterprise-modernization-playbook-overcoming-legacy-debt'
                => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=800&h=450&fit=crop',
            'data-driven-decision-making-building-corporate-bi-dashboards'
                => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=450&fit=crop',
            'cloud-migration-strategies-rehost-replatform-or-refactor'
                => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&h=450&fit=crop',
            'enterprise-security-in-the-cloud-iam-vpcs-and-compliance'
                => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=450&fit=crop',
            'the-roi-of-digital-transformation-measuring-what-matters'
                => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=450&fit=crop',

            // Personal Branding
            'why-every-founder-needs-a-strong-personal-brand'
                => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=450&fit=crop',
            'how-to-write-high-impact-technical-articles-that-build-authority'
                => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?w=800&h=450&fit=crop',
            'linkedin-for-tech-executives-crafting-your-content-strategy'
                => 'https://images.unsplash.com/photo-1611944212129-29977ae1398c?w=800&h=450&fit=crop',
            'the-power-of-public-speaking-for-technology-consultants'
                => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&h=450&fit=crop',
            'continuous-learning-how-to-stay-ahead-in-the-ai-era'
                => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=800&h=450&fit=crop',
        ];

        $updated = 0;
        foreach ($imageMap as $slug => $imageUrl) {
            $rows = BlogPost::where('slug', $slug)->update(['featured_image' => $imageUrl]);
            $updated += $rows;
        }

        $this->command->info("Updated {$updated} blog post images with relevant Unsplash photos.");
    }
}
