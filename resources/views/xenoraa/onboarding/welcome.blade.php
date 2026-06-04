<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Xenoraa!</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --xn-black: #0a0a0a; --xn-card: #161616; --xn-border: #2a2a2a;
            --xn-purple: #7c3aed; --xn-cyan: #06b6d4; --xn-pink: #ec4899;
            --xn-text: #f5f5f5; --xn-muted: #888;
        }
        body {
            font-family: 'Inter', sans-serif; background: var(--xn-black); color: var(--xn-text);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 2rem; position: relative; overflow: hidden;
        }
        body::before {
            content: ''; position: fixed; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(ellipse at 30% 40%, rgba(124,58,237,0.1) 0%, transparent 50%),
                        radial-gradient(ellipse at 70% 60%, rgba(6,182,212,0.07) 0%, transparent 50%);
            pointer-events: none; z-index: 0;
        }
        .card {
            position: relative; z-index: 1; width: 100%; max-width: 560px;
            background: var(--xn-card); border: 1px solid var(--xn-border);
            border-radius: 24px; padding: 3rem 2.5rem; text-align: center; overflow: hidden;
        }
        .card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--xn-purple), var(--xn-cyan), var(--xn-pink));
        }
        .emoji { font-size: 3.5rem; margin-bottom: 1.5rem; display: block; }
        .title {
            font-family: 'Space Grotesk', sans-serif; font-size: 2rem; font-weight: 700;
            letter-spacing: -0.03em; margin-bottom: 0.75rem;
        }
        .title span {
            background: linear-gradient(135deg, var(--xn-purple), var(--xn-cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .subtitle { color: var(--xn-muted); font-size: 1rem; line-height: 1.7; margin-bottom: 2.5rem; }

        /* Trial info */
        .trial-box {
            background: rgba(124,58,237,0.1); border: 1px solid rgba(124,58,237,0.25);
            border-radius: 14px; padding: 1.25rem 1.5rem; margin-bottom: 2.5rem; text-align: left;
        }
        .trial-box h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem; color: #a78bfa; }
        .trial-items { list-style: none; display: flex; flex-direction: column; gap: 0.5rem; }
        .trial-items li { display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: #ccc; }
        .trial-items li::before { content: '✓'; color: #4ade80; font-weight: 700; flex-shrink: 0; }

        /* Profile URL */
        .profile-url {
            background: #111; border: 1px solid var(--xn-border); border-radius: 10px;
            padding: 0.75rem 1rem; font-size: 0.9rem; color: #888; margin-bottom: 2.5rem;
            font-family: monospace;
        }
        .profile-url span { color: var(--xn-cyan); }

        /* Buttons */
        .btn-primary {
            display: inline-block; width: 100%; background: linear-gradient(135deg, var(--xn-purple), #6d28d9);
            color: #fff; border: none; border-radius: 12px; padding: 0.9rem 1.5rem;
            font-size: 1rem; font-weight: 600; font-family: 'Space Grotesk', sans-serif;
            cursor: pointer; text-decoration: none; transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
            margin-bottom: 1rem;
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 8px 25px rgba(124,58,237,0.35); }

        .btn-secondary {
            display: inline-block; width: 100%; background: transparent;
            color: var(--xn-muted); border: 1px solid var(--xn-border); border-radius: 12px;
            padding: 0.9rem 1.5rem; font-size: 0.95rem; font-weight: 500;
            cursor: pointer; text-decoration: none; transition: border-color 0.2s, color 0.2s;
        }
        .btn-secondary:hover { border-color: #444; color: #ccc; }
    </style>
</head>
<body>
<div class="card">
    <span class="emoji">🎉</span>
    <h1 class="title">Welcome to <span>Xenoraa</span>, {{ explode(' ', $user->name)[0] }}!</h1>
    <p class="subtitle">
        Your account is ready. You're now on a <strong>14-day free trial</strong> of the
        <strong>{{ ucfirst($user->plan ?? 'Starter') }}</strong> plan — no credit card needed.
    </p>

    <div class="trial-box">
        <h3>Your trial includes:</h3>
        <ul class="trial-items">
            <li>Full portfolio and blog management</li>
            <li>AI-powered chatbot for lead capture</li>
            <li>CRM with conversation tracking</li>
            <li>Newsletter and subscriber tools</li>
            @if(in_array($user->plan ?? 'starter', ['professional', 'business']))
            <li>Custom domain mapping</li>
            @endif
            @if(($user->plan ?? 'starter') === 'business')
            <li>E-commerce store with product management</li>
            @endif
        </ul>
    </div>

    <div class="profile-url">
        Your profile: <span>xenoraa.com/{{ $user->username }}</span>
    </div>

    <a href="{{ route('onboarding.profile') }}" class="btn-primary">Set Up Your Profile →</a>
    <a href="{{ route('user.dashboard') }}" class="btn-secondary">Skip for now, go to dashboard</a>
</div>
</body>
</html>
