<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Subscription — Xenoraa</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --black: #0a0a0a; --card: #141414; --border: #222;
            --purple: #7c3aed; --cyan: #06b6d4; --green: #22c55e;
            --text: #f5f5f5; --muted: #888; --subtle: #3f3f46;
        }
        body {
            font-family: 'Inter', sans-serif; background: var(--black); color: var(--text);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 2rem;
        }
        .checkout-wrap {
            width: 100%; max-width: 960px; display: grid;
            grid-template-columns: 1fr 420px; gap: 2rem; align-items: start;
        }
        @media(max-width:768px) { .checkout-wrap { grid-template-columns: 1fr; } }

        /* Left: Order Summary */
        .summary-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 20px; padding: 2.5rem;
        }
        .logo { font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 2rem; }
        .logo span { color: #a855f7; }
        .plan-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.3);
            border-radius: 8px; padding: 0.4rem 0.9rem; font-size: 0.8rem; font-weight: 600; color: #a855f7;
            margin-bottom: 1.5rem;
        }
        .plan-name { font-family: 'Space Grotesk', sans-serif; font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; }
        .plan-price { font-size: 3rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem; }
        .plan-price span { font-size: 1.1rem; font-weight: 400; color: var(--muted); }
        .billing-toggle {
            display: flex; gap: 0.5rem; margin: 1.5rem 0;
        }
        .billing-btn {
            flex: 1; padding: 0.6rem; border-radius: 8px; border: 1px solid var(--border);
            background: transparent; color: var(--muted); font-size: 0.85rem; font-weight: 600;
            cursor: pointer; transition: all 0.2s; font-family: 'Inter', sans-serif;
        }
        .billing-btn.active { background: rgba(124,58,237,0.15); border-color: #7c3aed; color: #a855f7; }
        .save-badge {
            display: inline-block; background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3);
            border-radius: 4px; padding: 0.15rem 0.5rem; font-size: 0.7rem; font-weight: 700; color: #22c55e; margin-left: 0.4rem;
        }
        .features-list { list-style: none; margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem; }
        .features-list li { display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; color: #ccc; }
        .features-list li i { color: var(--green); font-size: 0.75rem; flex-shrink: 0; }
        .divider { border: none; border-top: 1px solid var(--border); margin: 1.5rem 0; }
        .total-row { display: flex; justify-content: space-between; align-items: center; }
        .total-label { font-size: 0.9rem; color: var(--muted); }
        .total-amount { font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; font-weight: 700; }
        .guarantee { display: flex; align-items: center; gap: 0.75rem; margin-top: 1.5rem; padding: 1rem; background: rgba(34,197,94,0.06); border: 1px solid rgba(34,197,94,0.15); border-radius: 10px; font-size: 0.8rem; color: #86efac; }

        /* Right: Payment Card */
        .payment-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 20px; padding: 2rem; position: sticky; top: 2rem;
        }
        .payment-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; }
        .payment-subtitle { font-size: 0.8rem; color: var(--muted); margin-bottom: 1.5rem; }
        .user-info { background: #111; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; margin-bottom: 1.5rem; }
        .user-info-row { display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 0.4rem; }
        .user-info-row:last-child { margin-bottom: 0; }
        .user-info-label { color: var(--muted); }
        .user-info-value { color: #fff; font-weight: 500; }
        .pay-btn {
            width: 100%; padding: 1rem; background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: #fff; border: none; border-radius: 12px; font-size: 1rem; font-weight: 700;
            font-family: 'Space Grotesk', sans-serif; cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .pay-btn:hover { background: linear-gradient(135deg, #6d28d9, #5b21b6); transform: translateY(-1px); box-shadow: 0 8px 25px rgba(124,58,237,0.35); }
        .pay-btn:disabled { background: #333; cursor: not-allowed; transform: none; box-shadow: none; }
        .pay-btn .spinner { width: 18px; height: 18px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .secure-note { display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: 1rem; font-size: 0.75rem; color: var(--muted); }
        .razorpay-logo { display: flex; align-items: center; justify-content: center; gap: 0.4rem; margin-top: 0.75rem; font-size: 0.7rem; color: var(--subtle); }
        .error-box { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.8rem; color: #f87171; display: none; }
        .success-box { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.8rem; color: #86efac; display: none; }
    </style>
</head>
<body>
<div class="checkout-wrap">
    {{-- Left: Order Summary --}}
    <div class="summary-card">
        <div class="logo">Xenoraa<span>.</span></div>

        <div class="plan-badge"><i class="fas fa-crown"></i> Selected Plan</div>
        <div class="plan-name" id="planName">{{ $planNames[$plan] }}</div>

        <div class="billing-toggle">
            <button class="billing-btn {{ $billing === 'monthly' ? 'active' : '' }}" onclick="setBilling('monthly')" id="btn-monthly">Monthly</button>
            <button class="billing-btn {{ $billing === 'yearly' ? 'active' : '' }}" onclick="setBilling('yearly')" id="btn-yearly">
                Yearly <span class="save-badge">Save 17%</span>
            </button>
        </div>

        <div class="plan-price" id="planPrice">
            ₹<span id="priceAmount">{{ $billing === 'yearly' ? $prices[$plan]['yearly'] : $prices[$plan]['monthly'] }}</span>
            <span>/ <span id="pricePeriod">{{ $billing === 'yearly' ? 'year' : 'month' }}</span></span>
        </div>

        @php
        $planModules = config('xenoraa.plan_modules.' . $plan, []);
        $moduleLabels = [
            'site_builder' => 'Site Builder & Professional Website',
            'ecommerce'    => 'E-Commerce / Online Shop',
            'blog'         => 'Blog Publishing',
            'jobs'         => 'Job Board',
            'forum'        => 'Community Forum',
            'crm'          => 'CRM & Lead Management',
            'ai_hub'       => 'AI Hub & AI Assistance',
            'pos'          => 'Point of Sale (POS)',
            'newsletter'   => 'Newsletter & Email Marketing',
            'calendar'     => 'Calendar & Notes',
            'analytics'    => 'Analytics & Insights',
            'content'      => 'Content Management',
            'recruitment'  => 'Recruitment Module',
            'agent'        => 'Agent / Reseller Access',
        ];
        @endphp

        <ul class="features-list">
            @foreach($planModules as $mod)
            @if(isset($moduleLabels[$mod]))
            <li><i class="fas fa-check-circle"></i> {{ $moduleLabels[$mod] }}</li>
            @endif
            @endforeach
            <li><i class="fas fa-check-circle"></i> Custom domain mapping</li>
            <li><i class="fas fa-check-circle"></i> SSL certificate included</li>
            <li><i class="fas fa-check-circle"></i> Priority support</li>
        </ul>

        <hr class="divider">
        <div class="total-row">
            <div class="total-label">Total due today</div>
            <div class="total-amount">₹<span id="totalAmount">{{ $billing === 'yearly' ? $prices[$plan]['yearly'] : $prices[$plan]['monthly'] }}</span></div>
        </div>

        <div class="guarantee">
            <i class="fas fa-shield-alt" style="font-size:1.2rem;flex-shrink:0;"></i>
            <span>30-day money-back guarantee. Cancel anytime. No hidden fees.</span>
        </div>
    </div>

    {{-- Right: Payment --}}
    <div class="payment-card">
        <div class="payment-title">Complete Payment</div>
        <div class="payment-subtitle">Secure checkout powered by Razorpay</div>

        <div class="user-info">
            <div class="user-info-row">
                <span class="user-info-label">Name</span>
                <span class="user-info-value">{{ $user->name }}</span>
            </div>
            <div class="user-info-row">
                <span class="user-info-label">Email</span>
                <span class="user-info-value">{{ $user->email }}</span>
            </div>
            <div class="user-info-row">
                <span class="user-info-label">Plan</span>
                <span class="user-info-value" id="summaryPlan">{{ $planNames[$plan] }}</span>
            </div>
            <div class="user-info-row">
                <span class="user-info-label">Billing</span>
                <span class="user-info-value" id="summaryBilling">{{ ucfirst($billing) }}</span>
            </div>
        </div>

        <div class="error-box" id="errorBox"></div>
        <div class="success-box" id="successBox"></div>

        <button class="pay-btn" id="payBtn" onclick="startPayment()">
            <span class="spinner" id="spinner"></span>
            <i class="fas fa-lock" id="lockIcon"></i>
            <span id="payBtnText">Pay ₹<span id="btnAmount">{{ $billing === 'yearly' ? $prices[$plan]['yearly'] : $prices[$plan]['monthly'] }}</span> Securely</span>
        </button>

        <div class="secure-note">
            <i class="fas fa-lock"></i> 256-bit SSL encrypted payment
        </div>
        <div class="razorpay-logo">
            <i class="fas fa-credit-card"></i> Powered by Razorpay
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
const PRICES = @json($prices);
const PLAN_NAMES = @json($planNames);
const RAZORPAY_KEY = '{{ $razorpayKey }}';
const CSRF = '{{ csrf_token() }}';

let currentPlan = '{{ $plan }}';
let currentBilling = '{{ $billing }}';

function setBilling(billing) {
    currentBilling = billing;
    document.getElementById('btn-monthly').classList.toggle('active', billing === 'monthly');
    document.getElementById('btn-yearly').classList.toggle('active', billing === 'yearly');
    updatePriceDisplay();
}

function updatePriceDisplay() {
    const price = PRICES[currentPlan][currentBilling];
    document.getElementById('priceAmount').textContent = price.toLocaleString('en-IN');
    document.getElementById('totalAmount').textContent = price.toLocaleString('en-IN');
    document.getElementById('btnAmount').textContent = price.toLocaleString('en-IN');
    document.getElementById('pricePeriod').textContent = currentBilling === 'yearly' ? 'year' : 'month';
    document.getElementById('summaryBilling').textContent = currentBilling.charAt(0).toUpperCase() + currentBilling.slice(1);
}

function startPayment() {
    const btn = document.getElementById('payBtn');
    const spinner = document.getElementById('spinner');
    const lockIcon = document.getElementById('lockIcon');
    const errorBox = document.getElementById('errorBox');
    const successBox = document.getElementById('successBox');

    btn.disabled = true;
    spinner.style.display = 'block';
    lockIcon.style.display = 'none';
    errorBox.style.display = 'none';
    successBox.style.display = 'none';

    fetch('{{ route('payment.create-order') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ plan: currentPlan, billing: currentBilling })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) throw new Error(data.message || 'Order creation failed');

        const options = {
            key: RAZORPAY_KEY,
            amount: data.amount,
            currency: 'INR',
            name: 'Xenoraa',
            description: PLAN_NAMES[currentPlan] + ' Plan — ' + currentBilling,
            order_id: data.order_id,
            prefill: {
                name: '{{ $user->name }}',
                email: '{{ $user->email }}',
            },
            theme: { color: '#7c3aed' },
            handler: function(response) {
                verifyPayment(response, data.order_id);
            },
            modal: {
                ondismiss: function() {
                    btn.disabled = false;
                    spinner.style.display = 'none';
                    lockIcon.style.display = 'inline';
                }
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(err => {
        errorBox.textContent = err.message || 'Payment initiation failed. Please try again.';
        errorBox.style.display = 'block';
        btn.disabled = false;
        spinner.style.display = 'none';
        lockIcon.style.display = 'inline';
    });
}

function verifyPayment(response, orderId) {
    const errorBox = document.getElementById('errorBox');
    const successBox = document.getElementById('successBox');
    const btn = document.getElementById('payBtn');
    const spinner = document.getElementById('spinner');

    fetch('{{ route('payment.verify') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_order_id:   response.razorpay_order_id,
            razorpay_signature:  response.razorpay_signature,
            plan:    currentPlan,
            billing: currentBilling,
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            successBox.textContent = '✓ Payment successful! Setting up your account…';
            successBox.style.display = 'block';
            setTimeout(() => { window.location.href = data.redirect; }, 1500);
        } else {
            throw new Error(data.message || 'Verification failed');
        }
    })
    .catch(err => {
        errorBox.textContent = err.message || 'Payment verification failed. Contact support.';
        errorBox.style.display = 'block';
        btn.disabled = false;
        spinner.style.display = 'none';
        document.getElementById('lockIcon').style.display = 'inline';
    });
}
</script>
</body>
</html>
