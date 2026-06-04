<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome to Xenoraa</title>
<style>
  body { margin:0; padding:0; background:#0a0a0a; font-family:'Helvetica Neue',Arial,sans-serif; color:#e2e8f0; }
  .wrapper { max-width:600px; margin:0 auto; padding:2rem 1rem; }
  .header { text-align:center; padding:2.5rem 2rem 2rem; background:linear-gradient(135deg,#0f0f0f,#1a0a2e); border:1px solid #1a1a1a; border-radius:16px 16px 0 0; }
  .logo { font-size:2rem; font-weight:900; letter-spacing:-0.05em; color:#fff; }
  .logo span { color:#a855f7; }
  .tagline { font-size:0.8rem; color:#52525b; margin-top:0.25rem; letter-spacing:0.1em; text-transform:uppercase; }
  .body { background:#111; border:1px solid #1a1a1a; border-top:none; padding:2.5rem 2rem; }
  .greeting { font-size:1.5rem; font-weight:700; color:#fff; margin-bottom:0.75rem; }
  .text { font-size:0.9rem; color:#94a3b8; line-height:1.7; margin-bottom:1.25rem; }
  .url-box { background:#0a0a0a; border:1px solid #7c3aed; border-radius:10px; padding:1.25rem 1.5rem; margin:1.5rem 0; text-align:center; }
  .url-label { font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:#52525b; margin-bottom:0.5rem; }
  .url-value { font-size:1.1rem; font-weight:700; color:#a855f7; }
  .credentials { background:#0a0a0a; border:1px solid #1f1f1f; border-radius:10px; padding:1.5rem; margin:1.5rem 0; }
  .cred-title { font-size:0.75rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:#52525b; margin-bottom:1rem; }
  .cred-row { display:flex; justify-content:space-between; align-items:center; padding:0.6rem 0; border-bottom:1px solid #1a1a1a; }
  .cred-row:last-child { border-bottom:none; }
  .cred-key { font-size:0.8rem; color:#71717a; }
  .cred-val { font-size:0.875rem; font-weight:600; color:#e2e8f0; }
  .payment-box { background:rgba(34,197,94,0.05); border:1px solid rgba(34,197,94,0.15); border-radius:10px; padding:1.5rem; margin:1.5rem 0; }
  .payment-title { font-size:0.75rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:#22c55e; margin-bottom:1rem; }
  .payment-row { display:flex; justify-content:space-between; padding:0.5rem 0; border-bottom:1px solid rgba(34,197,94,0.08); font-size:0.85rem; }
  .payment-row:last-child { border-bottom:none; }
  .payment-key { color:#64748b; }
  .payment-val { color:#e2e8f0; font-weight:600; }
  .cta { text-align:center; margin:2rem 0; }
  .cta-btn { display:inline-block; padding:0.875rem 2.5rem; background:#7c3aed; color:#fff; text-decoration:none; border-radius:8px; font-weight:700; font-size:0.9rem; }
  .steps { margin:1.5rem 0; }
  .step { display:flex; gap:1rem; margin-bottom:1rem; }
  .step-num { width:28px; height:28px; background:rgba(124,58,237,0.15); border:1px solid rgba(124,58,237,0.3); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; color:#a855f7; flex-shrink:0; }
  .step-text { font-size:0.85rem; color:#94a3b8; line-height:1.5; padding-top:0.3rem; }
  .step-text strong { color:#e2e8f0; }
  .footer { background:#0a0a0a; border:1px solid #1a1a1a; border-top:none; border-radius:0 0 16px 16px; padding:1.5rem 2rem; text-align:center; }
  .footer-text { font-size:0.75rem; color:#3f3f46; line-height:1.6; }
  .footer-link { color:#52525b; text-decoration:none; }
  .divider { height:1px; background:#1a1a1a; margin:1.5rem 0; }
  .plan-badge { display:inline-block; background:rgba(124,58,237,0.15); border:1px solid rgba(124,58,237,0.3); color:#a855f7; padding:0.25rem 0.75rem; border-radius:100px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <div class="logo">xeno<span>raa</span></div>
    <div class="tagline">Build Your Digital Identity</div>
  </div>

  <div class="body">
    <div class="greeting">Welcome, {{ $user->name }}! 🎉</div>
    <p class="text">Your Xenoraa account has been successfully created. You're all set to build your professional digital identity. Here are your account details — please keep them safe.</p>

    <div class="url-box">
      <div class="url-label">Your Profile URL</div>
      <div class="url-value">xenoraa.com/{{ $user->username }}</div>
    </div>

    <div class="credentials">
      <div class="cred-title">🔐 Login Credentials</div>
      <div class="cred-row"><span class="cred-key">Email</span><span class="cred-val">{{ $user->email }}</span></div>
      @if($plainPassword)
      <div class="cred-row"><span class="cred-key">Password</span><span class="cred-val">{{ $plainPassword }}</span></div>
      @endif
      <div class="cred-row"><span class="cred-key">Plan</span><span class="cred-val"><span class="plan-badge">{{ ucfirst($user->plan ?? 'starter') }}</span></span></div>
      <div class="cred-row"><span class="cred-key">Trial Ends</span><span class="cred-val">{{ $user->trial_ends_at ? \Carbon\Carbon::parse($user->trial_ends_at)->format('d M Y') : 'N/A' }}</span></div>
    </div>

    @if($paymentDetails)
    <div class="payment-box">
      <div class="payment-title">✅ Payment Confirmed</div>
      <div class="payment-row"><span class="payment-key">Payment ID</span><span class="payment-val">{{ $paymentDetails['payment_id'] ?? 'N/A' }}</span></div>
      <div class="payment-row"><span class="payment-key">Order ID</span><span class="payment-val">{{ $paymentDetails['order_id'] ?? 'N/A' }}</span></div>
      <div class="payment-row"><span class="payment-key">Amount</span><span class="payment-val">{{ $paymentDetails['amount'] ?? 'N/A' }}</span></div>
      <div class="payment-row"><span class="payment-key">Plan</span><span class="payment-val">{{ ucfirst($paymentDetails['plan'] ?? $user->plan ?? 'starter') }}</span></div>
      <div class="payment-row"><span class="payment-key">Date</span><span class="payment-val">{{ now()->format('d M Y, h:i A') }}</span></div>
    </div>
    @endif

    <div class="divider"></div>

    <p class="text" style="font-weight:600;color:#e2e8f0;">Next Steps to Go Live:</p>
    <div class="steps">
      <div class="step">
        <div class="step-num">1</div>
        <div class="step-text"><strong>Complete your profile</strong> — Add your photo, bio, and contact details to go live.</div>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <div class="step-text"><strong>Your URL is ready</strong> — Share <strong>xenoraa.com/{{ $user->username }}</strong> with your clients right away.</div>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <div class="step-text"><strong>Map your domain</strong> (optional) — Connect your own domain like yourname.com from Settings.</div>
      </div>
      <div class="step">
        <div class="step-num">4</div>
        <div class="step-text"><strong>Activate AI Assistant</strong> — Your AI chatbot is ready to capture leads 24/7.</div>
      </div>
    </div>

    <div class="cta">
      <a href="{{ config('app.url') }}/login" class="cta-btn">Go to My Dashboard →</a>
    </div>

    <p class="text" style="font-size:0.8rem;text-align:center;">Need help? Reply to this email or contact us at <a href="mailto:support@xenoraa.com" style="color:#a855f7;">support@xenoraa.com</a></p>
  </div>

  <div class="footer">
    <p class="footer-text">
      You received this email because you signed up for Xenoraa.<br>
      <a href="{{ config('app.url') }}" class="footer-link">xenoraa.com</a> · <a href="mailto:support@xenoraa.com" class="footer-link">support@xenoraa.com</a>
    </p>
  </div>
</div>
</body>
</html>
