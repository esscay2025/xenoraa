@extends('layouts.xenoraa')
@section('title', 'Pricing — Xenoraa')
@section('meta_description', 'Simple, transparent pricing for Xenoraa. Start free and scale as you grow. No hidden fees.')

@section('styles')
<style>
.xn-page-hero { padding: 5rem 4rem 4rem; background: linear-gradient(180deg, #0a0a0a 0%, #000 100%); border-bottom: 1px solid #1a1a1a; text-align: center; }
.xn-toggle-wrap { display: flex; align-items: center; justify-content: center; gap: 1rem; margin: 2rem 0; }
.xn-toggle-label { font-size: 0.875rem; color: #a1a1aa; }
.xn-toggle { position: relative; width: 48px; height: 26px; }
.xn-toggle input { opacity: 0; width: 0; height: 0; }
.xn-toggle-slider { position: absolute; inset: 0; background: #222; border-radius: 26px; cursor: pointer; transition: 0.3s; }
.xn-toggle-slider::before { content: ''; position: absolute; width: 20px; height: 20px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: 0.3s; }
.xn-toggle input:checked + .xn-toggle-slider { background: #7c3aed; }
.xn-toggle input:checked + .xn-toggle-slider::before { transform: translateX(22px); }
.xn-save-badge { background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.3); color: #a855f7; font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 100px; }
.xn-pricing-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; max-width: 1100px; margin: 0 auto; }
.xn-pricing-full {
    background: #0d0d0d; border: 1px solid #1f1f1f;
    border-radius: 20px; padding: 2.5rem;
    position: relative; transition: all 0.3s;
}
.xn-pricing-full:hover { border-color: #7c3aed; transform: translateY(-6px); box-shadow: 0 30px 60px rgba(124,58,237,0.1); }
.xn-pricing-full.popular { border-color: #7c3aed; background: linear-gradient(180deg, rgba(124,58,237,0.1) 0%, #0d0d0d 50%); }
.xn-popular-tag { position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, #7c3aed, #a855f7); color: #fff; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; padding: 0.3rem 1.25rem; border-radius: 100px; white-space: nowrap; }
.xn-plan-name { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #a855f7; margin-bottom: 0.5rem; }
.xn-plan-price { font-family: 'Space Grotesk', sans-serif; font-size: 3.5rem; font-weight: 900; color: #fff; line-height: 1; }
.xn-plan-price sup { font-size: 1.25rem; vertical-align: top; margin-top: 0.75rem; color: #a1a1aa; }
.xn-plan-price sub { font-size: 1rem; font-weight: 400; color: #71717a; }
.xn-plan-yearly { font-size: 0.8rem; color: #52525b; margin: 0.5rem 0 1rem; }
.xn-plan-desc { font-size: 0.875rem; color: #71717a; line-height: 1.65; padding-bottom: 1.5rem; border-bottom: 1px solid #1a1a1a; margin-bottom: 1.5rem; }
.xn-plan-features { list-style: none; display: flex; flex-direction: column; gap: 0.875rem; margin-bottom: 2rem; }
.xn-plan-features li { display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.825rem; color: #a1a1aa; line-height: 1.5; }
.xn-plan-features li i.fa-check { color: #7c3aed; font-size: 0.75rem; margin-top: 3px; flex-shrink: 0; }
.xn-plan-features li i.fa-times { color: #3f3f46; font-size: 0.75rem; margin-top: 3px; flex-shrink: 0; }
.xn-plan-features li.disabled { color: #3f3f46; }
.xn-compare-table { width: 100%; border-collapse: collapse; margin-top: 3rem; }
.xn-compare-table th { padding: 1rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #52525b; border-bottom: 1px solid #1a1a1a; }
.xn-compare-table th:not(:first-child) { text-align: center; }
.xn-compare-table td { padding: 1rem 1.5rem; font-size: 0.875rem; color: #a1a1aa; border-bottom: 1px solid #111; }
.xn-compare-table td:not(:first-child) { text-align: center; }
.xn-compare-table tr:hover td { background: rgba(255,255,255,0.02); }
.xn-compare-table .section-row td { background: #0a0a0a; color: #52525b; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; padding: 0.75rem 1.5rem; }
.xn-check-yes { color: #7c3aed; font-size: 1rem; }
.xn-check-no { color: #2a2a2a; font-size: 1rem; }
.xn-plan-col-header { font-family: 'Space Grotesk', sans-serif; font-size: 0.9rem; font-weight: 700; color: #fff; }
.xn-plan-col-price { font-size: 0.75rem; color: #71717a; }
@media(max-width:1024px){
    .xn-pricing-grid{grid-template-columns:1fr;max-width:420px;}
    .xn-page-hero{padding:3rem 2rem 2.5rem;}
    .xn-compare-table{display:none;}
}
@media(max-width:768px){.xn-page-hero{padding:3rem 1.5rem 2rem;}}
</style>
@endsection

@section('content')
<section class="xn-page-hero">
    <div class="xn-container">
        <div class="xn-label">Pricing</div>
        <h1 class="xn-heading-xl">Simple, Transparent<br><span style="color:#a855f7;">Pricing</span></h1>
        <p class="xn-body-lg" style="max-width:520px;margin:1.25rem auto 0;">Start free and scale as you grow. No hidden fees, no long-term contracts. Cancel anytime.</p>
        <div class="xn-toggle-wrap">
            <span class="xn-toggle-label">Monthly</span>
            <label class="xn-toggle">
                <input type="checkbox" id="billingToggle" onchange="toggleBilling()">
                <span class="xn-toggle-slider"></span>
            </label>
            <span class="xn-toggle-label">Yearly <span class="xn-save-badge">Save 20%</span></span>
        </div>
    </div>
</section>

<section class="xn-section" style="background:#000;">
    <div class="xn-container">
        <div class="xn-pricing-grid">
            {{-- Starter --}}
            <div class="xn-pricing-full">
                <div class="xn-plan-name">Starter</div>
                <div class="xn-plan-price">
                    <sup>₹</sup><span class="price-monthly">499</span><span class="price-yearly" style="display:none;">416</span><sub>/mo</sub>
                </div>
                <div class="xn-plan-yearly"><span class="yearly-note" style="display:none;">₹4,999/year — save ₹989</span><span class="monthly-note">Billed monthly</span></div>
                <div class="xn-plan-desc">Perfect for individuals building their professional online presence for the first time.</div>
                <ul class="xn-plan-features">
                    <li><i class="fas fa-check"></i> Personal Profile Website</li>
                    <li><i class="fas fa-check"></i> xenoraa.com/username URL</li>
                    <li><i class="fas fa-check"></i> Portfolio Showcase (up to 10)</li>
                    <li><i class="fas fa-check"></i> Blog Publishing (up to 20 posts)</li>
                    <li><i class="fas fa-check"></i> Notes & Reminders</li>
                    <li><i class="fas fa-check"></i> Basic Calendar</li>
                    <li><i class="fas fa-check"></i> Basic Analytics</li>
                    <li><i class="fas fa-check"></i> Email Support</li>
                    <li class="disabled"><i class="fas fa-times"></i> CRM & Lead Management</li>
                    <li class="disabled"><i class="fas fa-times"></i> Custom Domain</li>
                    <li class="disabled"><i class="fas fa-times"></i> AI Assistant</li>
                    <li class="disabled"><i class="fas fa-times"></i> E-Commerce Store</li>
                </ul>
                <button onclick="startCheckout('starter','Starter')" class="xn-btn-ghost" style="width:100%;text-align:center;display:block;padding:0.875rem;font-size:0.9rem;cursor:pointer;font-family:'Inter',sans-serif;">Get Started Free</button>
            </div>

            {{-- Professional --}}
            <div class="xn-pricing-full popular">
                <div class="xn-popular-tag">⭐ Most Popular</div>
                <div class="xn-plan-name">Professional</div>
                <div class="xn-plan-price">
                    <sup>₹</sup><span class="price-monthly">999</span><span class="price-yearly" style="display:none;">833</span><sub>/mo</sub>
                </div>
                <div class="xn-plan-yearly"><span class="yearly-note" style="display:none;">₹9,999/year — save ₹1,989</span><span class="monthly-note">Billed monthly</span></div>
                <div class="xn-plan-desc">Designed for consultants, advocates, doctors, coaches, and creators who need full professional tools.</div>
                <ul class="xn-plan-features">
                    <li><i class="fas fa-check"></i> Everything in Starter</li>
                    <li><i class="fas fa-check"></i> CRM & Lead Management</li>
                    <li><i class="fas fa-check"></i> Custom Domain Mapping</li>
                    <li><i class="fas fa-check"></i> AI Content Assistant</li>
                    <li><i class="fas fa-check"></i> AI Chat Widget</li>
                    <li><i class="fas fa-check"></i> Unlimited Blog Posts</li>
                    <li><i class="fas fa-check"></i> Newsletter & Subscribers</li>
                    <li><i class="fas fa-check"></i> Advanced Analytics</li>
                    <li><i class="fas fa-check"></i> Priority Support</li>
                    <li><i class="fas fa-check"></i> Expense Tracking</li>
                    <li class="disabled"><i class="fas fa-times"></i> E-Commerce Store</li>
                    <li class="disabled"><i class="fas fa-times"></i> Team Members</li>
                </ul>
                <button onclick="startCheckout('professional','Professional')" class="xn-btn-primary" style="width:100%;text-align:center;display:block;padding:0.875rem;font-size:0.9rem;cursor:pointer;font-family:'Inter',sans-serif;">Get Started</button>
            </div>

            {{-- Business Pro --}}
            <div class="xn-pricing-full">
                <div class="xn-plan-name">Business Pro</div>
                <div class="xn-plan-price">
                    <sup>₹</sup><span class="price-monthly">1,999</span><span class="price-yearly" style="display:none;">1,666</span><sub>/mo</sub>
                </div>
                <div class="xn-plan-yearly"><span class="yearly-note" style="display:none;">₹19,999/year — save ₹3,989</span><span class="monthly-note">Billed monthly</span></div>
                <div class="xn-plan-desc">Built for growing professionals, founders, and organizations who need the complete Xenoraa ecosystem.</div>
                <ul class="xn-plan-features">
                    <li><i class="fas fa-check"></i> Everything in Professional</li>
                    <li><i class="fas fa-check"></i> E-Commerce Store (unlimited)</li>
                    <li><i class="fas fa-check"></i> Team Members (up to 5)</li>
                    <li><i class="fas fa-check"></i> Automation Workflows</li>
                    <li><i class="fas fa-check"></i> API Access</li>
                    <li><i class="fas fa-check"></i> White Label Branding</li>
                    <li><i class="fas fa-check"></i> Job Board Module</li>
                    <li><i class="fas fa-check"></i> Community Forum</li>
                    <li><i class="fas fa-check"></i> Dedicated Support</li>
                    <li><i class="fas fa-check"></i> Custom Integrations</li>
                    <li><i class="fas fa-check"></i> SLA Guarantee</li>
                    <li><i class="fas fa-check"></i> Onboarding Assistance</li>
                </ul>
                <button onclick="startCheckout('business','Business Pro')" class="xn-btn-ghost" style="width:100%;text-align:center;display:block;padding:0.875rem;font-size:0.9rem;cursor:pointer;font-family:'Inter',sans-serif;">Get Started</button>
            </div>
        </div>

        {{-- Comparison Table --}}
        <div style="margin-top:5rem;">
            <h2 class="xn-heading-md" style="text-align:center;margin-bottom:0.5rem;">Full Feature <span style="color:#a855f7;">Comparison</span></h2>
            <p style="text-align:center;color:#71717a;font-size:0.875rem;margin-bottom:2rem;">See exactly what's included in each plan</p>
            <table class="xn-compare-table">
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th><div class="xn-plan-col-header">Starter</div><div class="xn-plan-col-price">₹499/mo</div></th>
                        <th><div class="xn-plan-col-header" style="color:#a855f7;">Professional</div><div class="xn-plan-col-price">₹999/mo</div></th>
                        <th><div class="xn-plan-col-header">Business Pro</div><div class="xn-plan-col-price">₹1,999/mo</div></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="section-row"><td colspan="4">Profile & Branding</td></tr>
                    <tr><td>Personal Profile Website</td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>xenoraa.com/username URL</td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Custom Domain Mapping</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Portfolio Projects</td><td>Up to 10</td><td>Unlimited</td><td>Unlimited</td></tr>
                    <tr class="section-row"><td colspan="4">Content & Publishing</td></tr>
                    <tr><td>Blog Posts</td><td>Up to 20</td><td>Unlimited</td><td>Unlimited</td></tr>
                    <tr><td>Newsletter & Subscribers</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Community Forum</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr class="section-row"><td colspan="4">Business Tools</td></tr>
                    <tr><td>CRM & Lead Management</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Calendar & Scheduling</td><td>Basic</td><td>Advanced</td><td>Advanced</td></tr>
                    <tr><td>Notes & Knowledge Hub</td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Expense Tracking</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>E-Commerce Store</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td>Unlimited</td></tr>
                    <tr><td>Job Board</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr class="section-row"><td colspan="4">AI & Automation</td></tr>
                    <tr><td>AI Chat Widget</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>AI Content Assistant</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Automation Workflows</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>API Access</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr class="section-row"><td colspan="4">Support</td></tr>
                    <tr><td>Email Support</td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Priority Support</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                    <tr><td>Dedicated Support</td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-times xn-check-no"></i></td><td><i class="fas fa-check xn-check-yes"></i></td></tr>
                </tbody>
            </table>
        </div>

        {{-- FAQ --}}
        <div style="margin-top:5rem;max-width:700px;margin-left:auto;margin-right:auto;">
            <h2 class="xn-heading-md" style="text-align:center;margin-bottom:2.5rem;">Frequently Asked <span style="color:#a855f7;">Questions</span></h2>
            @php
            $faqs = [
                ['q'=>'Can I change my plan later?','a'=>'Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately.'],
                ['q'=>'Is there a free trial?','a'=>'Yes! You can start with a 14-day free trial on any plan. No credit card required.'],
                ['q'=>'Can I use my own domain?','a'=>'Yes, on the Professional and Business Pro plans you can map your own custom domain (e.g., gopi.blog) to your Xenoraa profile.'],
                ['q'=>'What happens to my data if I cancel?','a'=>'Your data is retained for 30 days after cancellation. You can export all your data at any time.'],
                ['q'=>'Do you offer refunds?','a'=>'Yes, we offer a 30-day money-back guarantee on all paid plans.'],
            ];
            @endphp
            @foreach($faqs as $faq)
            <div style="border-bottom:1px solid #1a1a1a;padding:1.5rem 0;">
                <div style="font-weight:600;color:#fff;margin-bottom:0.5rem;">{{ $faq['q'] }}</div>
                <div style="font-size:0.875rem;color:#71717a;line-height:1.65;">{{ $faq['a'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="xn-section" style="background:#050505;text-align:center;">
    <div class="xn-container">
        <h2 class="xn-heading-lg" style="max-width:600px;margin:0 auto 1rem;">Start Building Your<br><span style="color:#a855f7;">Digital Identity Today</span></h2>
        <p class="xn-body" style="max-width:480px;margin:0 auto 2.5rem;">Join professionals who are already using Xenoraa to build their brand and grow their business.</p>
        <a href="{{ route('xenoraa.get-started') }}" class="xn-btn-primary-lg">Start Free Trial 🚀</a>
        <p style="margin-top:1rem;font-size:0.8rem;color:#3f3f46;">No credit card required · 14-day free trial · Cancel anytime</p>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
let currentBilling = 'monthly';

function toggleBilling() {
    const yearly = document.getElementById('billingToggle').checked;
    currentBilling = yearly ? 'yearly' : 'monthly';
    document.querySelectorAll('.price-monthly').forEach(el => el.style.display = yearly ? 'none' : 'inline');
    document.querySelectorAll('.price-yearly').forEach(el => el.style.display = yearly ? 'inline' : 'none');
    document.querySelectorAll('.yearly-note').forEach(el => el.style.display = yearly ? 'inline' : 'none');
    document.querySelectorAll('.monthly-note').forEach(el => el.style.display = yearly ? 'none' : 'inline');
}

async function startCheckout(plan, planName) {
    try {
        const res = await fetch('/payment/create-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ plan: plan, billing: currentBilling })
        });
        const data = await res.json();
        if (!data.success) { alert(data.message || 'Failed to initiate payment. Please try again.'); return; }

        const options = {
            key: data.key_id,
            amount: data.amount,
            currency: data.currency,
            name: 'Xenoraa',
            description: planName + ' Plan — ' + (currentBilling === 'yearly' ? 'Annual' : 'Monthly'),
            order_id: data.order_id,
            prefill: { name: data.user_name, email: data.user_email },
            theme: { color: '#7c3aed' },
            handler: async function(response) {
                const verifyRes = await fetch('/payment/verify', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_signature: response.razorpay_signature,
                        plan: plan,
                        billing: currentBilling
                    })
                });
                const verifyData = await verifyRes.json();
                if (verifyData.success) {
                    window.location.href = '/payment/success?plan=' + plan + '&payment_id=' + response.razorpay_payment_id;
                } else {
                    window.location.href = '/payment/failed';
                }
            },
            modal: { ondismiss: function() { console.log('Payment cancelled'); } }
        };
        const rzp = new Razorpay(options);
        rzp.on('payment.failed', function() { window.location.href = '/payment/failed'; });
        rzp.open();
    } catch (err) {
        console.error('Checkout error:', err);
        alert('Something went wrong. Please try again.');
    }
}
</script>
@endsection
