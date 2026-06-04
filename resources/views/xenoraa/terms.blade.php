@extends('layouts.xenoraa')
@section('title', 'Terms of Service — Xenoraa')
@section('meta_description', 'Xenoraa Terms of Service — the rules and guidelines governing your use of the Xenoraa platform.')

@section('styles')
<style>
.xn-legal-section {
    padding: 120px 0 80px;
    background: #000;
    min-height: 100vh;
}
.xn-legal-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 2rem;
}
.xn-legal-header {
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #1a1a1a;
}
.xn-legal-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 2.5rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 0.75rem;
}
.xn-legal-meta {
    font-size: 0.875rem;
    color: #52525b;
}
.xn-legal-body h2 {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #fff;
    margin: 2.5rem 0 1rem;
}
.xn-legal-body h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #a1a1aa;
    margin: 1.5rem 0 0.75rem;
}
.xn-legal-body p {
    color: #71717a;
    font-size: 0.9375rem;
    line-height: 1.8;
    margin-bottom: 1rem;
}
.xn-legal-body ul {
    color: #71717a;
    font-size: 0.9375rem;
    line-height: 1.8;
    margin: 0.75rem 0 1rem 1.5rem;
}
.xn-legal-body ul li {
    margin-bottom: 0.5rem;
}
.xn-legal-body a {
    color: #a855f7;
    text-decoration: none;
}
.xn-legal-body a:hover {
    text-decoration: underline;
}
.xn-legal-highlight {
    background: rgba(124,58,237,0.08);
    border: 1px solid rgba(124,58,237,0.2);
    border-radius: 8px;
    padding: 1.25rem 1.5rem;
    margin: 1.5rem 0;
    color: #a1a1aa;
    font-size: 0.875rem;
    line-height: 1.7;
}
</style>
@endsection

@section('content')
<section class="xn-legal-section">
    <div class="xn-legal-container">
        <div class="xn-legal-header">
            <div class="xn-label" style="margin-bottom:1rem;">Legal</div>
            <h1 class="xn-legal-title">Terms of Service</h1>
            <p class="xn-legal-meta">Last updated: {{ date('F d, Y') }} &nbsp;·&nbsp; Effective: {{ date('F d, Y') }}</p>
        </div>

        <div class="xn-legal-body">

            <div class="xn-legal-highlight">
                <strong style="color:#fff;">Please read carefully.</strong> By creating an account or using Xenoraa, you agree to these Terms of Service. If you do not agree, please do not use our platform.
            </div>

            <h2>1. Acceptance of Terms</h2>
            <p>These Terms of Service ("Terms") constitute a legally binding agreement between you ("User," "Tenant," or "you") and Xenoraa ("we," "our," or "us") governing your access to and use of the Xenoraa platform at <a href="https://xenoraa.com">xenoraa.com</a> and any associated services.</p>
            <p>By registering an account, you confirm that you are at least 18 years of age, have the legal capacity to enter into this agreement, and accept these Terms in full.</p>

            <h2>2. Description of Service</h2>
            <p>Xenoraa is a multi-tenant SaaS (Software as a Service) platform that allows professionals to:</p>
            <ul>
                <li>Create and manage a digital identity at <strong style="color:#fff;">xenoraa.com/username</strong> or via a custom domain.</li>
                <li>Publish blog posts, manage a job board, operate an online store, and run a CRM.</li>
                <li>Use AI-powered tools including an intelligent chatbot for visitor engagement.</li>
                <li>Manage expenses, team members, and business operations.</li>
            </ul>
            <p>We reserve the right to modify, suspend, or discontinue any feature of the service at any time with reasonable notice.</p>

            <h2>3. Account Registration</h2>

            <h3>3.1 Account Creation</h3>
            <p>To use Xenoraa, you must register an account with a valid email address, a unique username, and a secure password. You are responsible for maintaining the confidentiality of your credentials.</p>

            <h3>3.2 Username Policy</h3>
            <p>Your chosen username becomes your public profile URL (xenoraa.com/username). Usernames must:</p>
            <ul>
                <li>Be between 3 and 30 characters.</li>
                <li>Contain only letters, numbers, and hyphens.</li>
                <li>Not impersonate another person, brand, or entity.</li>
                <li>Not be a reserved system word (admin, support, api, etc.).</li>
            </ul>
            <p>We reserve the right to reclaim usernames that violate these rules or have been inactive for an extended period.</p>

            <h3>3.3 Account Security</h3>
            <p>You are solely responsible for all activity that occurs under your account. Notify us immediately at <a href="mailto:support@xenoraa.com">support@xenoraa.com</a> if you suspect unauthorised access.</p>

            <h2>4. Subscription Plans and Payments</h2>

            <h3>4.1 Free Trial</h3>
            <p>New accounts receive a 14-day free trial with full access to platform features. No credit card is required to start the trial. At the end of the trial, you must subscribe to a paid plan to continue using the service.</p>

            <h3>4.2 Paid Plans</h3>
            <p>Xenoraa offers the following subscription tiers (pricing subject to change with 30 days' notice):</p>
            <ul>
                <li><strong style="color:#e4e4e7;">Starter:</strong> ₹499/month — Portfolio, Blog, AI Chatbot.</li>
                <li><strong style="color:#e4e4e7;">Professional:</strong> ₹999/month — Starter + Custom Domain + CRM + Job Board.</li>
                <li><strong style="color:#e4e4e7;">Business Pro:</strong> ₹1,999/month — Professional + E-commerce + Team Management + Priority Support.</li>
            </ul>

            <h3>4.3 Billing and Renewal</h3>
            <p>Subscriptions are billed monthly in advance. Payments are processed securely via Razorpay. By providing payment details, you authorise us to charge your payment method on each renewal date.</p>

            <h3>4.4 Refund Policy</h3>
            <p>We offer a 7-day money-back guarantee on your first payment. After this period, subscription fees are non-refundable. Unused days in a billing period are not refunded upon cancellation.</p>

            <h3>4.5 Cancellation</h3>
            <p>You may cancel your subscription at any time from your account settings. Cancellation takes effect at the end of the current billing period. Your data will be retained for 30 days after cancellation before permanent deletion.</p>

            <h2>5. Acceptable Use Policy</h2>
            <p>You agree not to use Xenoraa to:</p>
            <ul>
                <li>Publish, distribute, or transmit any content that is illegal, defamatory, obscene, or infringes on intellectual property rights.</li>
                <li>Harass, threaten, or harm any individual or group.</li>
                <li>Spread misinformation, spam, or engage in phishing activities.</li>
                <li>Attempt to gain unauthorised access to other users' accounts or data.</li>
                <li>Use automated bots or scrapers to extract data from the platform.</li>
                <li>Violate any applicable local, national, or international law or regulation.</li>
                <li>Impersonate any person, company, or entity.</li>
            </ul>
            <p>Violation of this policy may result in immediate account suspension or termination without refund.</p>

            <h2>6. Content Ownership and Licensing</h2>

            <h3>6.1 Your Content</h3>
            <p>You retain full ownership of all content you create and publish on Xenoraa (blog posts, profile information, media, etc.). By publishing content on the platform, you grant Xenoraa a limited, non-exclusive, royalty-free licence to host, display, and distribute your content solely for the purpose of providing the service.</p>

            <h3>6.2 Platform Content</h3>
            <p>All platform software, design, templates, and Xenoraa branding are owned by Xenoraa and protected by intellectual property laws. You may not copy, modify, or distribute our platform code or design without written permission.</p>

            <h3>6.3 User-Generated Content</h3>
            <p>You are solely responsible for the content you publish. We do not pre-screen content but reserve the right to remove any content that violates these Terms or applicable law.</p>

            <h2>7. Privacy</h2>
            <p>Your use of Xenoraa is also governed by our <a href="{{ route('legal.privacy') }}">Privacy Policy</a>, which is incorporated into these Terms by reference. Please review it carefully.</p>

            <h2>8. Multi-Tenancy and Data Isolation</h2>
            <p>Each Xenoraa account operates as an isolated tenant. Your data, content, and configurations are logically separated from other tenants. You acknowledge that:</p>
            <ul>
                <li>You are responsible for managing access permissions within your tenant workspace.</li>
                <li>Sub-users you invite operate under your tenant account and your responsibility.</li>
                <li>Xenoraa administrators may access tenant data only for support, security, or legal compliance purposes.</li>
            </ul>

            <h2>9. Custom Domains</h2>
            <p>Professional and Business Pro subscribers may connect a custom domain to their Xenoraa profile. You are responsible for:</p>
            <ul>
                <li>Owning or having the legal right to use the custom domain.</li>
                <li>Configuring DNS settings correctly as per our documentation.</li>
                <li>Renewing your domain registration independently of your Xenoraa subscription.</li>
            </ul>

            <h2>10. Service Availability</h2>
            <p>We strive to maintain 99.9% uptime but do not guarantee uninterrupted service. Scheduled maintenance will be communicated in advance. We are not liable for losses arising from service downtime beyond our reasonable control.</p>

            <h2>11. Limitation of Liability</h2>
            <p>To the maximum extent permitted by applicable law, Xenoraa shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including loss of profits, data, or goodwill, arising from your use of or inability to use the service.</p>
            <p>Our total liability for any claim arising from these Terms shall not exceed the amount you paid to Xenoraa in the 3 months preceding the claim.</p>

            <h2>12. Indemnification</h2>
            <p>You agree to indemnify and hold harmless Xenoraa, its officers, directors, employees, and agents from any claims, damages, losses, or expenses (including legal fees) arising from your use of the platform, violation of these Terms, or infringement of any third-party rights.</p>

            <h2>13. Termination</h2>
            <p>We may suspend or terminate your account at any time for:</p>
            <ul>
                <li>Violation of these Terms or our Acceptable Use Policy.</li>
                <li>Non-payment of subscription fees.</li>
                <li>Fraudulent, abusive, or illegal activity.</li>
            </ul>
            <p>Upon termination, your right to use the service ceases immediately. You may request a data export within 30 days of termination.</p>

            <h2>14. Governing Law</h2>
            <p>These Terms are governed by the laws of India. Any disputes arising from these Terms shall be subject to the exclusive jurisdiction of the courts in Chennai, Tamil Nadu, India.</p>

            <h2>15. Changes to Terms</h2>
            <p>We may update these Terms periodically. Material changes will be communicated via email or a prominent notice on the platform at least 14 days before taking effect. Your continued use of Xenoraa after such changes constitutes acceptance of the updated Terms.</p>

            <h2>16. Contact</h2>
            <p>For questions about these Terms, please contact us:</p>
            <ul>
                <li><strong style="color:#e4e4e7;">Email:</strong> <a href="mailto:support@xenoraa.com">support@xenoraa.com</a></li>
                <li><strong style="color:#e4e4e7;">Website:</strong> <a href="https://xenoraa.com">xenoraa.com</a></li>
            </ul>

        </div>

        {{-- Navigation --}}
        <div style="margin-top:4rem;padding-top:2rem;border-top:1px solid #1a1a1a;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
            <a href="{{ route('legal.privacy') }}" style="color:#a855f7;text-decoration:none;font-size:0.875rem;">Privacy Policy →</a>
            <a href="{{ url('/') }}" style="color:#52525b;text-decoration:none;font-size:0.875rem;">← Back to Xenoraa</a>
        </div>
    </div>
</section>
@endsection
