<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Xenoraa</title>
    <meta name="description" content="Sign in to your Xenoraa account and manage your digital identity.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --xn-black:   #0a0a0a;
            --xn-dark:    #111111;
            --xn-card:    #161616;
            --xn-border:  #2a2a2a;
            --xn-purple:  #7c3aed;
            --xn-purple2: #6d28d9;
            --xn-pink:    #ec4899;
            --xn-cyan:    #06b6d4;
            --xn-text:    #f5f5f5;
            --xn-muted:   #888;
            --xn-input:   #1e1e1e;
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
            overflow: hidden;
        }

        /* Animated background */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at 20% 50%, rgba(124,58,237,0.08) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(6,182,212,0.06) 0%, transparent 50%),
                        radial-gradient(ellipse at 60% 80%, rgba(236,72,153,0.05) 0%, transparent 40%);
            animation: bgShift 12s ease-in-out infinite alternate;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes bgShift {
            0%   { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-2%, 2%) rotate(1deg); }
        }

        /* Grid overlay */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
            z-index: 0;
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            width: 100%;
            max-width: 1100px;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            gap: 4rem;
        }

        /* Left branding panel */
        .brand-panel {
            flex: 1;
            max-width: 460px;
            display: none;
        }

        @media (min-width: 900px) {
            .brand-panel { display: block; }
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 3rem;
        }

        .brand-logo img {
            height: 40px;
            filter: brightness(0) invert(1);
        }

        .brand-logo span {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #fff 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-headline {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.8rem;
            font-weight: 700;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
        }

        .brand-headline span {
            background: linear-gradient(135deg, var(--xn-purple) 0%, var(--xn-cyan) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-sub {
            color: var(--xn-muted);
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .brand-features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .brand-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #ccc;
            font-size: 0.95rem;
        }

        .brand-features li::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--xn-purple), var(--xn-cyan));
            flex-shrink: 0;
        }

        /* Login card */
        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--xn-card);
            border: 1px solid var(--xn-border);
            border-radius: 20px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--xn-purple), var(--xn-cyan), var(--xn-pink));
        }

        .card-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .card-logo img {
            height: 32px;
            filter: brightness(0) invert(1);
        }

        .card-logo span {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #fff 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 0.4rem;
        }

        .card-subtitle {
            color: var(--xn-muted);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* Alert */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.3);
            color: #86efac;
        }

        /* Form */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: #ccc;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            background: var(--xn-input);
            border: 1px solid var(--xn-border);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: var(--xn-text);
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--xn-purple);
            box-shadow: 0 0 0 3px rgba(124,58,237,0.15);
        }

        .form-input::placeholder { color: #555; }

        .form-input.error {
            border-color: rgba(239,68,68,0.5);
        }

        .field-error {
            color: #fca5a5;
            font-size: 0.8rem;
            margin-top: 0.35rem;
        }

        /* Remember & Forgot */
        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            color: #ccc;
            cursor: pointer;
        }

        .remember-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--xn-purple);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.875rem;
            color: var(--xn-purple);
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover { color: #a78bfa; }

        /* Submit button */
        .btn-signin {
            width: 100%;
            background: linear-gradient(135deg, var(--xn-purple) 0%, var(--xn-purple2) 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.85rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Space Grotesk', sans-serif;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
            letter-spacing: 0.01em;
            margin-bottom: 1.5rem;
        }

        .btn-signin:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(124,58,237,0.35);
        }

        .btn-signin:active { transform: translateY(0); }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--xn-border);
        }

        .divider span {
            color: var(--xn-muted);
            font-size: 0.8rem;
            white-space: nowrap;
        }

        /* Social buttons */
        .social-btns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--xn-input);
            border: 1px solid var(--xn-border);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            color: #ccc;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
            cursor: pointer;
        }

        .btn-social:hover {
            border-color: #444;
            background: #222;
            color: #fff;
        }

        .btn-social svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Sign up link */
        .signup-prompt {
            text-align: center;
            font-size: 0.875rem;
            color: var(--xn-muted);
        }

        .signup-prompt a {
            color: var(--xn-purple);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .signup-prompt a:hover { color: #a78bfa; }

        /* Back to portfolio link (for custom domain users) */
        .back-link {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.8rem;
            color: #555;
        }

        .back-link a {
            color: #666;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link a:hover { color: #888; }
    </style>
</head>
<body>
<div class="login-wrapper">

    {{-- Left branding panel (desktop only) --}}
    <div class="brand-panel">
        <div class="brand-logo">
            <img src="/images/xenoraa/logo.png" alt="Xenoraa">
            <span>Xenoraa</span>
        </div>
        <h1 class="brand-headline">
            Welcome back to your<br>
            <span>Digital Identity</span>
        </h1>
        <p class="brand-sub">
            Sign in to manage your portfolio, blog, CRM, AI assistant, and everything that makes your digital presence remarkable.
        </p>
        <ul class="brand-features">
            <li>AI-powered chatbot that captures leads 24/7</li>
            <li>Full CRM with conversation management</li>
            <li>Blog, portfolio, and e-commerce in one place</li>
            <li>Custom domain mapping for your brand</li>
            <li>Real-time analytics and newsletter tools</li>
        </ul>
    </div>

    {{-- Login card --}}
    <div class="login-card">
        <div class="card-logo">
            <img src="/images/xenoraa/logo.png" alt="Xenoraa">
            <span>Xenoraa</span>
        </div>

        <h2 class="card-title">Sign in</h2>
        <p class="card-subtitle">Enter your credentials to access your dashboard</p>

        {{-- Session status --}}
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required
                    autofocus
                    autocomplete="username"
                >
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-footer">
                <label class="remember-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn-signin">Sign In to Xenoraa</button>
        </form>

        {{-- Social login --}}
        @if(config('services.google.client_id') || config('services.github.client_id'))
        <div class="divider"><span>or continue with</span></div>
        <div class="social-btns">
            @if(config('services.google.client_id'))
            <a href="{{ route('social.redirect', 'google') }}" class="btn-social">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Google
            </a>
            @endif
            @if(config('services.github.client_id'))
            <a href="{{ route('social.redirect', 'github') }}" class="btn-social">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                </svg>
                GitHub
            </a>
            @endif
        </div>
        @endif

        <p class="signup-prompt">
            Don't have an account?
            <a href="{{ route('xenoraa.get-started') }}">Get started free</a>
        </p>

        @php $host = request()->getHost(); $mainDomain = config('xenoraa.main_domain', 'xenoraa.com'); @endphp
        @if($host !== $mainDomain && $host !== 'www.' . $mainDomain)
        <div class="back-link">
            <a href="https://xenoraa.com">Powered by Xenoraa</a>
        </div>
        @endif
    </div>

</div>
</body>
</html>
