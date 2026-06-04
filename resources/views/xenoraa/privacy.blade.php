@extends('layouts.xenoraa')
@section('title', 'Privacy Policy — Xenoraa')
@section('meta_description', 'Xenoraa Privacy Policy — how we collect, use, and protect your personal information.')

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
            <h1 class="xn-legal-title">Privacy Policy</h1>
            <p class="xn-legal-meta">Last updated: {{ date('F d, Y') }} &nbsp;·&nbsp; Effective: {{ date('F d, Y') }}</p>
        </div>

        <div class="xn-legal-body">

            <div class="xn-legal-highlight">
                <strong style="color:#fff;">Summary:</strong> Xenoraa collects only the information necessary to provide our services. We do not sell your personal data. You retain full ownership of your content. We use industry-standard security practices to protect your information.
            </div>

            <h2>1. Introduction</h2>
            <p>Welcome to Xenoraa ("we," "our," or "us"). Xenoraa is a multi-tenant SaaS platform that enables professionals to create and manage their digital identity at <strong style="color:#fff;">xenoraa.com/username</strong> or via a custom domain.</p>
            <p>This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform at <a href="https://xenoraa.com">xenoraa.com</a>. By creating an account or using our services, you consent to the practices described in this policy.</p>

            <h2>2. Information We Collect</h2>

            <h3>2.1 Information You Provide</h3>
            <ul>
                <li><strong style="color:#e4e4e7;">Account Information:</strong> Name, email address, username, password, and profession when you register.</li>
                <li><strong style="color:#e4e4e7;">Profile Content:</strong> Bio, profile photo, work experience, social media links, and any other content you add to your profile.</li>
                <li><strong style="color:#e4e4e7;">Business Content:</strong> Blog posts, job listings, CRM leads, expense records, and e-commerce products you create on the platform.</li>
                <li><strong style="color:#e4e4e7;">Payment Information:</strong> Billing details processed securely through Razorpay. We do not store your card details on our servers.</li>
                <li><strong style="color:#e4e4e7;">Communications:</strong> Messages you send to our support team or through the platform's contact forms.</li>
            </ul>

            <h3>2.2 Information Collected Automatically</h3>
            <ul>
                <li><strong style="color:#e4e4e7;">Usage Data:</strong> Pages visited, features used, time spent, and actions taken within the platform.</li>
                <li><strong style="color:#e4e4e7;">Device Information:</strong> Browser type, operating system, IP address, and device identifiers.</li>
                <li><strong style="color:#e4e4e7;">Cookies:</strong> Session cookies for authentication and preference cookies for user experience. See Section 7 for details.</li>
                <li><strong style="color:#e4e4e7;">Log Data:</strong> Server logs including request timestamps, error logs, and access records.</li>
            </ul>

            <h3>2.3 Information from Third Parties</h3>
            <ul>
                <li><strong style="color:#e4e4e7;">Payment Processors:</strong> Razorpay may share transaction status and basic billing information with us.</li>
                <li><strong style="color:#e4e4e7;">AI Services:</strong> When you use the AI chatbot feature, queries are processed through OpenAI's API. Please review <a href="https://openai.com/privacy" target="_blank">OpenAI's Privacy Policy</a>.</li>
            </ul>

            <h2>3. How We Use Your Information</h2>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Create and manage your account and tenant profile.</li>
                <li>Provide, operate, and improve the Xenoraa platform and its features.</li>
                <li>Process payments and manage your subscription.</li>
                <li>Send transactional emails (account confirmation, password reset, subscription alerts).</li>
                <li>Send platform updates and newsletters (you may unsubscribe at any time).</li>
                <li>Provide customer support and respond to your inquiries.</li>
                <li>Monitor platform performance, security, and prevent abuse.</li>
                <li>Comply with legal obligations.</li>
            </ul>
            <p>We do <strong style="color:#fff;">not</strong> sell, rent, or trade your personal information to third parties for marketing purposes.</p>

            <h2>4. Data Isolation and Multi-Tenancy</h2>
            <p>Xenoraa is a multi-tenant platform. Each tenant's data (blog posts, CRM leads, contacts, expenses, etc.) is logically isolated using tenant identifiers. Your data is accessible only to:</p>
            <ul>
                <li>You (the account owner / admin).</li>
                <li>Sub-users you explicitly invite to your workspace.</li>
                <li>Xenoraa's super administrators for platform maintenance and support purposes only.</li>
            </ul>
            <p>Tenant data is never shared between separate tenant accounts.</p>

            <h2>5. Data Sharing and Disclosure</h2>
            <p>We may share your information in the following limited circumstances:</p>
            <ul>
                <li><strong style="color:#e4e4e7;">Service Providers:</strong> Trusted third-party vendors (hosting, email delivery, payment processing, AI services) who process data on our behalf under strict data processing agreements.</li>
                <li><strong style="color:#e4e4e7;">Legal Requirements:</strong> When required by applicable law, court order, or government authority.</li>
                <li><strong style="color:#e4e4e7;">Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets, with prior notice to affected users.</li>
                <li><strong style="color:#e4e4e7;">Protection of Rights:</strong> To protect the rights, property, or safety of Xenoraa, our users, or the public.</li>
            </ul>

            <h2>6. Data Retention</h2>
            <p>We retain your personal data for as long as your account is active or as needed to provide services. If you delete your account:</p>
            <ul>
                <li>Your profile and public content will be removed within 30 days.</li>
                <li>Backup copies may persist for up to 90 days before permanent deletion.</li>
                <li>We may retain anonymized, aggregated data indefinitely for analytics.</li>
                <li>Financial records are retained for 7 years as required by Indian tax law.</li>
            </ul>

            <h2>7. Cookies</h2>
            <p>We use the following types of cookies:</p>
            <ul>
                <li><strong style="color:#e4e4e7;">Essential Cookies:</strong> Required for authentication and session management. Cannot be disabled.</li>
                <li><strong style="color:#e4e4e7;">Preference Cookies:</strong> Remember your settings (e.g., sidebar state, theme preferences).</li>
                <li><strong style="color:#e4e4e7;">Analytics Cookies:</strong> Help us understand how users interact with the platform (anonymized).</li>
            </ul>
            <p>You can control cookies through your browser settings. Disabling essential cookies will prevent you from logging in.</p>

            <h2>8. Security</h2>
            <p>We implement industry-standard security measures to protect your data, including:</p>
            <ul>
                <li>HTTPS/TLS encryption for all data in transit.</li>
                <li>Bcrypt password hashing — we never store plain-text passwords.</li>
                <li>Regular security audits and vulnerability assessments.</li>
                <li>Access controls limiting data access to authorised personnel only.</li>
            </ul>
            <p>No system is completely secure. If you discover a security vulnerability, please report it to <a href="mailto:support@xenoraa.com">support@xenoraa.com</a>.</p>

            <h2>9. Your Rights</h2>
            <p>Depending on your jurisdiction, you may have the following rights:</p>
            <ul>
                <li><strong style="color:#e4e4e7;">Access:</strong> Request a copy of the personal data we hold about you.</li>
                <li><strong style="color:#e4e4e7;">Correction:</strong> Request correction of inaccurate or incomplete data.</li>
                <li><strong style="color:#e4e4e7;">Deletion:</strong> Request deletion of your account and associated data.</li>
                <li><strong style="color:#e4e4e7;">Portability:</strong> Request an export of your data in a machine-readable format.</li>
                <li><strong style="color:#e4e4e7;">Opt-out:</strong> Unsubscribe from marketing communications at any time.</li>
            </ul>
            <p>To exercise these rights, contact us at <a href="mailto:support@xenoraa.com">support@xenoraa.com</a>.</p>

            <h2>10. Children's Privacy</h2>
            <p>Xenoraa is not directed at individuals under the age of 18. We do not knowingly collect personal information from minors. If we become aware that a minor has provided us with personal data, we will delete it promptly.</p>

            <h2>11. International Data Transfers</h2>
            <p>Xenoraa is operated from India. If you access our services from outside India, your data may be transferred to and processed in India. By using our services, you consent to this transfer.</p>

            <h2>12. Changes to This Policy</h2>
            <p>We may update this Privacy Policy periodically. When we make material changes, we will notify you by email or by displaying a prominent notice on the platform. Your continued use of Xenoraa after such changes constitutes acceptance of the updated policy.</p>

            <h2>13. Contact Us</h2>
            <p>If you have questions, concerns, or requests regarding this Privacy Policy, please contact us:</p>
            <ul>
                <li><strong style="color:#e4e4e7;">Email:</strong> <a href="mailto:support@xenoraa.com">support@xenoraa.com</a></li>
                <li><strong style="color:#e4e4e7;">Website:</strong> <a href="https://xenoraa.com">xenoraa.com</a></li>
            </ul>

        </div>

        {{-- Navigation --}}
        <div style="margin-top:4rem;padding-top:2rem;border-top:1px solid #1a1a1a;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
            <a href="{{ route('legal.terms') }}" style="color:#a855f7;text-decoration:none;font-size:0.875rem;">Terms of Service →</a>
            <a href="{{ url('/') }}" style="color:#52525b;text-decoration:none;font-size:0.875rem;">← Back to Xenoraa</a>
        </div>
    </div>
</section>
@endsection
