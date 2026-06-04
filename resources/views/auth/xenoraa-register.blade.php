<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Started — Xenoraa</title>
    <meta name="description" content="Create your Xenoraa account and build your digital identity today.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --xn-black: #0a0a0a; --xn-dark: #111111; --xn-card: #161616;
            --xn-border: #2a2a2a; --xn-purple: #7c3aed; --xn-purple2: #6d28d9;
            --xn-pink: #ec4899; --xn-cyan: #06b6d4; --xn-text: #f5f5f5;
            --xn-muted: #888; --xn-input: #1e1e1e;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--xn-black);
            color: var(--xn-text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            padding: 2rem 1rem;
        }
        body::before {
            content: '';
            position: fixed;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(ellipse at 20% 50%, rgba(124,58,237,0.08) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(6,182,212,0.06) 0%, transparent 50%);
            animation: bgShift 12s ease-in-out infinite alternate;
            pointer-events: none; z-index: 0;
        }
        body::after {
            content: '';
            position: fixed; inset: 0;
            background-image: linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none; z-index: 0;
        }
        @keyframes bgShift { 0% { transform: translate(0,0); } 100% { transform: translate(-2%,2%); } }

        .register-card {
            position: relative; z-index: 1;
            width: 100%; max-width: 520px;
            background: var(--xn-card);
            border: 1px solid var(--xn-border);
            border-radius: 20px;
            padding: 2.5rem;
            overflow: hidden;
        }
        .register-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, var(--xn-purple), var(--xn-cyan), var(--xn-pink));
        }
        .card-logo {
            display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem;
        }
        .card-logo img { height: 32px; filter: brightness(0) invert(1); }
        .card-logo span {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.4rem; font-weight: 700; letter-spacing: -0.03em;
            background: linear-gradient(135deg, #fff 0%, #a78bfa 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .card-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.6rem; font-weight: 700; letter-spacing: -0.02em; margin-bottom: 0.4rem; }
        .card-subtitle { color: var(--xn-muted); font-size: 0.9rem; margin-bottom: 2rem; }

        /* Trial badge */
        .trial-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.3);
            border-radius: 50px; padding: 0.4rem 1rem;
            font-size: 0.8rem; color: #a78bfa; font-weight: 500;
            margin-bottom: 1.5rem;
        }
        .trial-badge::before {
            content: ''; width: 6px; height: 6px; border-radius: 50%;
            background: var(--xn-purple); animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }

        .alert { padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.875rem; margin-bottom: 1.5rem; }
        .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 480px) { .form-row { grid-template-columns: 1fr; } }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 500; color: #ccc; margin-bottom: 0.5rem; }
        .form-label .optional { color: #555; font-weight: 400; font-size: 0.78rem; }

        .input-wrapper { position: relative; }
        .input-prefix {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #555; font-size: 0.9rem; pointer-events: none;
        }
        .form-input {
            width: 100%; background: var(--xn-input); border: 1px solid var(--xn-border);
            border-radius: 10px; padding: 0.75rem 1rem; color: var(--xn-text);
            font-size: 0.95rem; font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s; outline: none;
        }
        .form-input.has-prefix { padding-left: 2.5rem; }
        .form-input:focus { border-color: var(--xn-purple); box-shadow: 0 0 0 3px rgba(124,58,237,0.15); }
        .form-input::placeholder { color: #555; }
        .form-input.error { border-color: rgba(239,68,68,0.5); }
        .field-error { color: #fca5a5; font-size: 0.8rem; margin-top: 0.35rem; }

        /* Username availability indicator */
        .username-status {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            font-size: 0.75rem; font-weight: 500;
        }
        .username-status.available { color: #4ade80; }
        .username-status.taken { color: #f87171; }
        .username-status.checking { color: #888; }

        .username-preview {
            font-size: 0.78rem; color: #555; margin-top: 0.35rem;
        }
        .username-preview span { color: #888; }

        /* Plan selector */
        .plan-selector { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1.25rem; }
        @media (max-width: 480px) { .plan-selector { grid-template-columns: 1fr; } }

        .plan-option { display: none; }
        .plan-label {
            display: flex; flex-direction: column; align-items: center; gap: 4px;
            padding: 0.85rem 0.5rem; border: 1px solid var(--xn-border); border-radius: 10px;
            cursor: pointer; transition: border-color 0.2s, background 0.2s;
            text-align: center;
        }
        .plan-label:hover { border-color: #444; background: #1a1a1a; }
        .plan-option:checked + .plan-label {
            border-color: var(--xn-purple); background: rgba(124,58,237,0.1);
        }
        .plan-name { font-size: 0.85rem; font-weight: 600; color: #fff; }
        .plan-price { font-size: 0.75rem; color: var(--xn-muted); }
        .plan-badge {
            font-size: 0.65rem; background: rgba(124,58,237,0.3); color: #a78bfa;
            padding: 1px 6px; border-radius: 4px; font-weight: 500;
        }

        .terms-row {
            display: flex; align-items: flex-start; gap: 10px;
            margin-bottom: 1.5rem; font-size: 0.85rem; color: #888;
        }
        .terms-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--xn-purple); margin-top: 2px; flex-shrink: 0; }
        .terms-row a { color: var(--xn-purple); text-decoration: none; }
        .terms-row a:hover { color: #a78bfa; }

        .btn-register {
            width: 100%; background: linear-gradient(135deg, var(--xn-purple) 0%, var(--xn-purple2) 100%);
            color: #fff; border: none; border-radius: 10px; padding: 0.85rem 1.5rem;
            font-size: 1rem; font-weight: 600; font-family: 'Space Grotesk', sans-serif;
            cursor: pointer; transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
            letter-spacing: 0.01em; margin-bottom: 1.5rem;
        }
        .btn-register:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 8px 25px rgba(124,58,237,0.35); }

        .signin-prompt { text-align: center; font-size: 0.875rem; color: var(--xn-muted); }
        .signin-prompt a { color: var(--xn-purple); text-decoration: none; font-weight: 500; }
        .signin-prompt a:hover { color: #a78bfa; }
    </style>
</head>
<body>
<div class="register-card">
    <div class="card-logo">
        <img src="/images/xenoraa/logo.png" alt="Xenoraa">
        <span>Xenoraa</span>
    </div>

    <div class="trial-badge">14-day free trial — no credit card required</div>

    <h2 class="card-title">Create your account</h2>
    <p class="card-subtitle">Build your digital identity in minutes</p>

    @if ($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="name">Full name</label>
                <input id="name" type="text" name="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                    value="{{ old('name') }}" placeholder="Gopi K" required autofocus autocomplete="name">
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <div class="input-wrapper">
                    <input id="username" type="text" name="username"
                        class="form-input {{ $errors->has('username') ? 'error' : '' }}"
                        value="{{ old('username') }}" placeholder="yourname"
                        pattern="[a-z0-9_-]{3,30}" title="3-30 characters, lowercase letters, numbers, hyphens, underscores"
                        required autocomplete="off"
                        oninput="checkUsername(this.value)">
                    <span class="username-status" id="username-status"></span>
                </div>
                <p class="username-preview">xenoraa.com/<span id="username-preview">yourname</span></p>
                @error('username') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email address</label>
            <input id="email" type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="username">
            @error('email') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input id="password" type="password" name="password"
                    class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                    placeholder="Min 8 characters" required autocomplete="new-password">
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    class="form-input" placeholder="Repeat password" required autocomplete="new-password">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Choose your plan <span class="optional">(upgrade anytime)</span></label>
            <div class="plan-selector">
                <div>
                    <input type="radio" name="plan" id="plan-starter" value="starter" class="plan-option"
                        {{ old('plan', 'starter') === 'starter' ? 'checked' : '' }}>
                    <label for="plan-starter" class="plan-label">
                        <span class="plan-name">Starter</span>
                        <span class="plan-price">Free trial</span>
                    </label>
                </div>
                <div>
                    <input type="radio" name="plan" id="plan-professional" value="professional" class="plan-option"
                        {{ old('plan') === 'professional' ? 'checked' : '' }}>
                    <label for="plan-professional" class="plan-label">
                        <span class="plan-name">Professional</span>
                        <span class="plan-price">₹999/mo</span>
                        <span class="plan-badge">Popular</span>
                    </label>
                </div>
                <div>
                    <input type="radio" name="plan" id="plan-business" value="business" class="plan-option"
                        {{ old('plan') === 'business' ? 'checked' : '' }}>
                    <label for="plan-business" class="plan-label">
                        <span class="plan-name">Business Pro</span>
                        <span class="plan-price">₹1,999/mo</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="terms-row">
            <input type="checkbox" id="terms" name="terms" required {{ old('terms') ? 'checked' : '' }}>
            <label for="terms">
                I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>. I understand my 14-day free trial starts today.
            </label>
        </div>

        <button type="submit" class="btn-register">Create Account &amp; Start Free Trial</button>
    </form>

    <p class="signin-prompt">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </p>
</div>

<script>
let usernameTimer;
function checkUsername(val) {
    const preview = document.getElementById('username-preview');
    const status = document.getElementById('username-status');
    const clean = val.toLowerCase().replace(/[^a-z0-9_-]/g, '');
    preview.textContent = clean || 'yourname';
    if (!clean || clean.length < 3) { status.textContent = ''; return; }
    status.textContent = '...'; status.className = 'username-status checking';
    clearTimeout(usernameTimer);
    usernameTimer = setTimeout(() => {
        fetch('/api/check-username?username=' + encodeURIComponent(clean))
            .then(r => r.json())
            .then(d => {
                if (d.available) {
                    status.textContent = '✓ Available'; status.className = 'username-status available';
                } else {
                    status.textContent = '✗ Taken'; status.className = 'username-status taken';
                }
            }).catch(() => { status.textContent = ''; });
    }, 500);
}
</script>
</body>
</html>
