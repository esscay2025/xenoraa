<?php

namespace Database\Seeders;

use App\Models\ChatbotTraining;
use Illuminate\Database\Seeder;

class XenoraaAiTrainingSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing platform training data
        ChatbotTraining::whereNull('user_id')->delete();

        $entries = [
            // ── XENORAA PLATFORM ─────────────────────────────────────────────
            ['category' => 'xenoraa', 'sort_order' => 1,
             'question' => 'What is Xenoraa?',
             'answer'   => 'Xenoraa is a powerful all-in-one SaaS platform that helps professionals, businesses, and entrepreneurs build their complete digital presence. With Xenoraa, you get an AI-powered website builder, CRM, POS system, e-commerce store, blog, job portal, chatbot, and much more — all in one place, under your own brand.'],

            ['category' => 'xenoraa', 'sort_order' => 2,
             'question' => 'Who is Xenoraa for?',
             'answer'   => 'Xenoraa is built for a wide range of professionals: consultants, lawyers, doctors, coaches, influencers, freelancers, retailers, and small-to-medium businesses. Whether you are a solo professional or a growing team, Xenoraa gives you the tools to manage your digital business efficiently.'],

            ['category' => 'xenoraa', 'sort_order' => 3,
             'question' => 'What makes Xenoraa different from other website builders?',
             'answer'   => 'Unlike basic website builders, Xenoraa is a complete business operating system. You get not just a website, but also a built-in CRM to manage leads and deals, a POS system for physical stores, e-commerce capabilities, an AI chatbot for your site, blog and content management, job portal, expense tracker, and detailed analytics — all integrated and managed from one dashboard.'],

            ['category' => 'xenoraa', 'sort_order' => 4,
             'question' => 'Is Xenoraa suitable for physical stores?',
             'answer'   => 'Absolutely! Xenoraa includes a full-featured Point of Sale (POS) system designed for physical store billing counters. It supports product scanning, cart management, multiple payment methods (cash, card, UPI), thermal receipt printing, session management, and real-time inventory sync with your online store.'],

            ['category' => 'xenoraa', 'sort_order' => 5,
             'question' => 'Can I manage my online store with Xenoraa?',
             'answer'   => 'Yes! Xenoraa includes a complete e-commerce module with product management, categories, inventory tracking, order management, and a public shop page. Your POS and online store share the same product catalog, so inventory is always in sync.'],

            // ── PRICING ───────────────────────────────────────────────────────
            ['category' => 'pricing', 'sort_order' => 1,
             'question' => 'How much does Xenoraa cost?',
             'answer'   => 'Xenoraa offers flexible plans to suit different needs. We have a Starter plan for individuals and small businesses, a Professional plan for growing businesses, and a Business plan for teams and enterprises. For the latest pricing details, please visit xenoraa.com/pricing or let me connect you with our sales team for a personalised quote.'],

            ['category' => 'pricing', 'sort_order' => 2,
             'question' => 'Is there a free trial?',
             'answer'   => 'Yes! Xenoraa offers a free trial so you can explore the platform before committing. You can sign up and start building your digital presence without any upfront payment. Our team is also happy to give you a personalised demo — just share your details and we will set one up.'],

            ['category' => 'pricing', 'sort_order' => 3,
             'question' => 'What is included in the Starter plan?',
             'answer'   => 'The Starter plan includes the core website builder, blog, basic CRM, AI chatbot, and essential modules to get your digital presence up and running. It is perfect for solo professionals and individuals who are just starting out. For a full feature comparison, visit xenoraa.com/pricing.'],

            ['category' => 'pricing', 'sort_order' => 4,
             'question' => 'What is included in the Professional plan?',
             'answer'   => 'The Professional plan includes everything in Starter plus advanced CRM with full sales pipeline, e-commerce store, POS system, job portal, expense management, advanced analytics, and priority support. It is ideal for growing businesses and professionals managing a team.'],

            ['category' => 'pricing', 'sort_order' => 5,
             'question' => 'What is included in the Business plan?',
             'answer'   => 'The Business plan is our most comprehensive offering, including all Professional features plus multi-user access, custom domain, advanced reporting, white-label options, API access, and dedicated account management. It is designed for established businesses and enterprises.'],

            ['category' => 'pricing', 'sort_order' => 6,
             'question' => 'Do you offer annual billing discounts?',
             'answer'   => 'Yes! Choosing annual billing gives you a significant discount compared to monthly billing — you essentially get several months free. Our sales team can share the exact savings based on the plan you choose. Would you like me to connect you with them?'],

            ['category' => 'pricing', 'sort_order' => 7,
             'question' => 'Can I upgrade or downgrade my plan?',
             'answer'   => 'Absolutely. You can upgrade your plan at any time from your account settings, and the change takes effect immediately. Downgrading is also possible at the end of your billing cycle. Our support team is happy to help you choose the right plan for your current needs.'],

            ['category' => 'pricing', 'sort_order' => 8,
             'question' => 'Is there a setup fee?',
             'answer'   => 'No, there is no setup fee. You simply choose your plan, sign up, and start building. Our onboarding process is designed to get you live quickly without any hidden costs.'],

            // ── SALES ─────────────────────────────────────────────────────────
            ['category' => 'sales', 'sort_order' => 1,
             'question' => 'I want to see a demo of Xenoraa',
             'answer'   => 'We would love to show you Xenoraa in action! A personalised demo is the best way to see how the platform can work for your specific business. Could you share your name, email, and a brief description of your business? Our team will schedule a demo at your convenience.'],

            ['category' => 'sales', 'sort_order' => 2,
             'question' => 'How do I get started with Xenoraa?',
             'answer'   => 'Getting started is simple: (1) Sign up at xenoraa.com, (2) Choose your plan, (3) Set up your profile and branding, (4) Start adding your content, products, or services. Our onboarding wizard guides you through each step. Most users are live within a day!'],

            ['category' => 'sales', 'sort_order' => 3,
             'question' => 'Can I migrate my existing website to Xenoraa?',
             'answer'   => 'Yes! Our team can help you migrate your existing content, products, and data to Xenoraa. We support migration from popular platforms. The process is straightforward and our support team will guide you through it. Would you like to discuss your migration needs?'],

            ['category' => 'sales', 'sort_order' => 4,
             'question' => 'Do you offer custom development or enterprise solutions?',
             'answer'   => 'Yes, we offer custom solutions for enterprise clients with specific requirements. This includes custom integrations, white-label options, dedicated infrastructure, and tailored feature development. Please share your requirements and our enterprise team will get in touch with a proposal.'],

            ['category' => 'sales', 'sort_order' => 5,
             'question' => 'Can I use my own domain with Xenoraa?',
             'answer'   => 'Yes! You can connect your own custom domain to your Xenoraa site. The setup is straightforward — you simply update your DNS settings to point to Xenoraa. Our support documentation and team will guide you through the process. Custom domains are available on Professional and Business plans.'],

            ['category' => 'sales', 'sort_order' => 6,
             'question' => 'Does Xenoraa support multiple users or team members?',
             'answer'   => 'Yes! Xenoraa supports multi-user access with role-based permissions. You can add team members as admins, staff, or assign specific module access (e.g., a store manager who only sees the POS). This is available on Professional and Business plans.'],

            ['category' => 'sales', 'sort_order' => 7,
             'question' => 'What payment methods does Xenoraa support for my online store?',
             'answer'   => 'Your Xenoraa online store and POS support multiple payment methods including cash, card, UPI, and split payments. For online payments, we integrate with popular payment gateways. Our team can advise on the best payment setup for your business.'],

            ['category' => 'sales', 'sort_order' => 8,
             'question' => 'Is Xenoraa suitable for a law firm or legal practice?',
             'answer'   => 'Absolutely! Xenoraa has a dedicated "Advocate" profile template designed for legal professionals. You get a professional website, client enquiry management through CRM, case tracking, appointment scheduling via the Services module, and an AI assistant that handles client queries professionally.'],

            ['category' => 'sales', 'sort_order' => 9,
             'question' => 'Can doctors or healthcare professionals use Xenoraa?',
             'answer'   => 'Yes! Xenoraa has a "Doctor" profile template for healthcare professionals. It includes appointment booking, patient enquiry management, service listings, and an AI assistant that handles patient queries while always directing them to consult the doctor for medical advice.'],

            ['category' => 'sales', 'sort_order' => 10,
             'question' => 'I am an influencer or content creator. Is Xenoraa for me?',
             'answer'   => 'Definitely! Xenoraa has an "Influencer" profile template built for content creators. You get a stunning portfolio site, brand collaboration management through CRM, media kit, blog, and an AI assistant that handles brand partnership enquiries on your behalf.'],

            // ── FEATURES ──────────────────────────────────────────────────────
            ['category' => 'features', 'sort_order' => 1,
             'question' => 'What CRM features does Xenoraa include?',
             'answer'   => 'Xenoraa includes a comprehensive CRM with: Sales (Leads, Contacts, Accounts, Deals, Forecasts), Activities (Tasks, Meetings, Calls), Inventory (Price Books, Quotes, Sales Orders, Purchase Orders, Invoices, Vendors), Support (Cases, Solutions), Services (Appointment management), Projects (Sales-to-project tracking), and detailed Analytics and Reports.'],

            ['category' => 'features', 'sort_order' => 2,
             'question' => 'Does Xenoraa have an AI chatbot for my website?',
             'answer'   => 'Yes! Every Xenoraa site comes with an AI-powered chatbot widget. You train it with your business information, FAQs, and services. The AI then handles visitor enquiries 24/7, captures leads automatically, and routes them to your CRM. It is powered by advanced AI and completely customisable.'],

            ['category' => 'features', 'sort_order' => 3,
             'question' => 'Can I build a blog with Xenoraa?',
             'answer'   => 'Yes! Xenoraa includes a full-featured blog module with rich text editing, categories, tags, comments, SEO settings, and a beautiful public-facing blog page. It is perfect for content marketing and establishing thought leadership.'],

            ['category' => 'features', 'sort_order' => 4,
             'question' => 'Does Xenoraa have a job portal?',
             'answer'   => 'Yes! Xenoraa includes a job portal where you can post job openings, receive applications, and manage the hiring process — all from your admin dashboard. Applicants can apply directly from your website.'],

            ['category' => 'features', 'sort_order' => 5,
             'question' => 'What analytics does Xenoraa provide?',
             'answer'   => 'Xenoraa provides detailed analytics including website traffic, visitor behaviour, CRM pipeline analytics, sales forecasts, revenue reports, POS session reports, chatbot conversation analytics, and more. All data is presented in clean, visual dashboards.'],

            ['category' => 'features', 'sort_order' => 6,
             'question' => 'Can I customise the look and feel of my Xenoraa site?',
             'answer'   => 'Yes! Xenoraa includes a Site Builder with theme selection, branding customisation (logo, colours, fonts), page management, menu builder, and section-based content editing. You can create a completely unique look without any coding knowledge.'],

            ['category' => 'features', 'sort_order' => 7,
             'question' => 'Does Xenoraa support e-commerce?',
             'answer'   => 'Yes! Xenoraa includes a complete e-commerce module: product catalogue, categories, inventory management, order processing, and a public shop page. It syncs with the POS system so your in-store and online inventory are always aligned.'],

            ['category' => 'features', 'sort_order' => 8,
             'question' => 'What is the Services module in Xenoraa?',
             'answer'   => 'The Services module helps service-based businesses streamline operations. You can list your services, manage bookings and appointments, track service delivery, and generate invoices — all in one place. It is ideal for consultants, doctors, lawyers, coaches, and any service professional.'],

            ['category' => 'features', 'sort_order' => 9,
             'question' => 'What is the Projects module in Xenoraa?',
             'answer'   => 'The Projects module bridges the gap between sales and project delivery. When a deal is won in CRM, you can convert it into a project, assign tasks, track progress, and manage delivery — all within Xenoraa. It is a unified sales and project management system.'],

            // ── ONBOARDING ────────────────────────────────────────────────────
            ['category' => 'onboarding', 'sort_order' => 1,
             'question' => 'How long does it take to set up Xenoraa?',
             'answer'   => 'Most users are live within a few hours to a day. The onboarding wizard guides you through: (1) Profile setup, (2) Branding and theme selection, (3) Adding your services or products, (4) Configuring your AI chatbot, (5) Going live. Our support team is available to help at every step.'],

            ['category' => 'onboarding', 'sort_order' => 2,
             'question' => 'Do I need technical knowledge to use Xenoraa?',
             'answer'   => 'No technical knowledge is required. Xenoraa is designed to be used by non-technical professionals. Everything is managed through intuitive dashboards with no coding required. If you can use a smartphone, you can use Xenoraa.'],

            ['category' => 'onboarding', 'sort_order' => 3,
             'question' => 'How do I set up my AI chatbot?',
             'answer'   => 'Setting up your AI chatbot is easy: (1) Go to Admin → AI Chatbot → Training, (2) Add your business information, FAQs, and service details, (3) The AI immediately uses this data to answer visitor questions. The more training data you add, the smarter and more accurate your chatbot becomes.'],

            ['category' => 'onboarding', 'sort_order' => 4,
             'question' => 'How do I connect my custom domain?',
             'answer'   => 'To connect your custom domain: (1) Go to Admin → Site → Domain, (2) Enter your domain name, (3) Update your domain\'s DNS settings to point to Xenoraa (we provide the exact records). The change typically propagates within a few hours. Our support team can walk you through it.'],

            ['category' => 'onboarding', 'sort_order' => 5,
             'question' => 'Can I import my existing products or contacts?',
             'answer'   => 'Yes! Xenoraa supports bulk import for products, contacts, and leads via CSV files. This makes migration from your existing systems quick and easy. Our support team can also assist with the import process.'],

            // ── SUPPORT ───────────────────────────────────────────────────────
            ['category' => 'support', 'sort_order' => 1,
             'question' => 'How do I contact Xenoraa support?',
             'answer'   => 'You can reach our support team through: (1) This AI chat — I can collect your details and route your query, (2) Email: support@xenoraa.com, (3) The Help & Support section in your admin dashboard. Our team typically responds within a few hours during business hours.'],

            ['category' => 'support', 'sort_order' => 2,
             'question' => 'I am getting a 500 error on my site',
             'answer'   => 'A 500 error usually indicates a server-side issue. Please: (1) Try refreshing the page, (2) Clear your browser cache, (3) If the error persists, note the exact URL and time it occurred, then contact our support team at support@xenoraa.com with these details. We will investigate and resolve it promptly.'],

            ['category' => 'support', 'sort_order' => 3,
             'question' => 'I cannot log in to my account',
             'answer'   => 'If you cannot log in, please try: (1) Using the "Forgot Password" link on the login page, (2) Checking that you are using the correct email address, (3) Clearing your browser cache and cookies. If you still cannot access your account, contact support@xenoraa.com with your registered email and we will help you regain access.'],

            ['category' => 'support', 'sort_order' => 4,
             'question' => 'My AI chatbot is not responding',
             'answer'   => 'If your AI chatbot is not responding, please check: (1) That your subscription is active, (2) That you have added training data (Admin → AI Chatbot → Training), (3) That the chatbot widget is enabled in your site settings. If the issue persists, contact our support team with your account email.'],

            ['category' => 'support', 'sort_order' => 5,
             'question' => 'How do I reset my password?',
             'answer'   => 'To reset your password: (1) Go to xenoraa.com/login, (2) Click "Forgot Password", (3) Enter your registered email address, (4) Check your email for the reset link, (5) Click the link and set a new password. If you do not receive the email within a few minutes, check your spam folder or contact support@xenoraa.com.'],

            ['category' => 'support', 'sort_order' => 6,
             'question' => 'My website is not showing my custom domain',
             'answer'   => 'Custom domain propagation can take up to 24-48 hours after DNS changes. Please: (1) Verify the DNS records are correctly set as instructed in Admin → Site → Domain, (2) Wait for propagation to complete, (3) Try accessing your domain from a different browser or device. If it still does not work after 48 hours, contact our support team.'],

            ['category' => 'support', 'sort_order' => 7,
             'question' => 'How do I cancel my subscription?',
             'answer'   => 'To cancel your subscription, please contact our support team at support@xenoraa.com or through your account dashboard. We will process your cancellation and ensure you retain access until the end of your current billing period. We would also love to understand why you are leaving so we can improve.'],

            ['category' => 'support', 'sort_order' => 8,
             'question' => 'I lost my data or something was accidentally deleted',
             'answer'   => 'Please contact our support team immediately at support@xenoraa.com with: (1) Your account email, (2) What data was lost, (3) When it happened. We maintain regular backups and may be able to restore your data. The sooner you contact us, the better the chances of recovery.'],

            ['category' => 'support', 'sort_order' => 9,
             'question' => 'How do I add a team member or staff user?',
             'answer'   => 'To add a team member: (1) Go to Admin → Users → Add User, (2) Enter their details and assign a role (Admin, Staff, or custom role), (3) For custom access, go to Admin → Roles to create a role with specific module permissions. The new user will receive a login invitation by email.'],

            ['category' => 'support', 'sort_order' => 10,
             'question' => 'My payment failed or I have a billing issue',
             'answer'   => 'For billing issues, please contact our support team at support@xenoraa.com with your account email and a description of the issue. We will investigate and resolve it promptly. Please do not share your card details over chat.'],

            // ── TECHNICAL ─────────────────────────────────────────────────────
            ['category' => 'technical', 'sort_order' => 1,
             'question' => 'Is Xenoraa secure?',
             'answer'   => 'Yes, security is a top priority at Xenoraa. We use industry-standard encryption (HTTPS/SSL), secure authentication, regular security audits, and follow best practices for data protection. Your data and your customers\' data are safe with us.'],

            ['category' => 'technical', 'sort_order' => 2,
             'question' => 'Where is my data stored?',
             'answer'   => 'Your data is stored on secure, enterprise-grade cloud infrastructure. We maintain regular backups to prevent data loss. For specific data residency requirements, please contact our team to discuss enterprise options.'],

            ['category' => 'technical', 'sort_order' => 3,
             'question' => 'Does Xenoraa have an API?',
             'answer'   => 'Yes, Xenoraa provides API access for integration with third-party tools and custom development. API access is available on Business plan and above. Contact our team for API documentation and integration support.'],

            ['category' => 'technical', 'sort_order' => 4,
             'question' => 'Is Xenoraa mobile-friendly?',
             'answer'   => 'Yes! All Xenoraa sites are fully responsive and mobile-optimised. The admin dashboard also works on mobile devices, so you can manage your business from anywhere. The POS system is designed to work on tablets for store counter use.'],

            ['category' => 'technical', 'sort_order' => 5,
             'question' => 'What browsers does Xenoraa support?',
             'answer'   => 'Xenoraa works on all modern browsers including Chrome, Firefox, Safari, and Edge. We recommend using the latest version of your browser for the best experience.'],

            // ── GENERAL ───────────────────────────────────────────────────────
            ['category' => 'general', 'sort_order' => 1,
             'question' => 'What is the refund policy?',
             'answer'   => 'We want you to be completely satisfied with Xenoraa. If you are not happy with your purchase, please contact our support team within the refund window specified in our terms of service. We will review your request and work to find a fair resolution.'],

            ['category' => 'general', 'sort_order' => 2,
             'question' => 'Do you have a referral or affiliate programme?',
             'answer'   => 'Yes! Xenoraa has an agent/affiliate programme where you can earn commissions by referring customers. Contact our team at support@xenoraa.com to learn more about joining the programme and the commission structure.'],

            ['category' => 'general', 'sort_order' => 3,
             'question' => 'Is Xenoraa available in multiple languages?',
             'answer'   => 'Xenoraa\'s admin interface is currently in English. However, your public-facing website can be in any language — you simply write your content in your preferred language. We are working on multi-language admin support for future releases.'],

            ['category' => 'general', 'sort_order' => 4,
             'question' => 'What currencies does Xenoraa support?',
             'answer'   => 'Xenoraa supports multiple currencies for your store and POS. You can configure your preferred currency (INR, USD, EUR, GBP, and more) in your account settings. For payment gateway currency support, this depends on your chosen payment provider.'],

            ['category' => 'general', 'sort_order' => 5,
             'question' => 'I want to speak to a human, not a bot',
             'answer'   => 'Absolutely! I completely understand. Please share your name, email, and a brief description of what you need, and I will make sure a member of our team contacts you directly. Alternatively, you can email us at support@xenoraa.com or sales@xenoraa.com.'],
        ];

        foreach ($entries as $entry) {
            ChatbotTraining::create(array_merge($entry, [
                'user_id'   => null,
                'is_active' => true,
            ]));
        }

        $this->command->info('✅ Xenoraa AI Training: ' . count($entries) . ' entries seeded successfully.');
    }
}
