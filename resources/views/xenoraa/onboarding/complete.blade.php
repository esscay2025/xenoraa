<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're All Set — Xenoraa</title>
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
            padding: 2rem;
        }
        body::before {
            content: ''; position: fixed; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(ellipse at 30% 40%, rgba(124,58,237,0.1) 0%, transparent 50%),
                        radial-gradient(ellipse at 70% 60%, rgba(6,182,212,0.07) 0%, transparent 50%);
            pointer-events: none; z-index: 0;
        }
        .card {
            position: relative; z-index: 1; width: 100%; max-width: 540px;
            background: var(--xn-card); border: 1px solid var(--xn-border);
            border-radius: 24px; padding: 3rem 2.5rem; text-align: center; overflow: hidden;
        }
        .card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--xn-purple), var(--xn-cyan), var(--xn-pink));
        }
        .emoji { font-size: 3.5rem; margin-bottom: 1.5rem; display: block; }
        .title { font-family: 'Space Grotesk', sans-serif; font-size: 2rem; font-weight: 700; letter-spacing: -0.03em; margin-bottom: 0.75rem; }
        .title span { background: linear-gradient(135deg, var(--xn-purple), var(--xn-cyan)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .subtitle { color: var(--xn-muted); font-size: 1rem; line-height: 1.7; margin-bottom: 2.5rem; }

        .quick-links { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 2rem; }
        .quick-link {
            display: flex; flex-direction: column; align-items: flex-start; gap: 4px;
            background: #111; border: 1px solid var(--xn-border); border-radius: 12px;
            padding: 1rem 1.25rem; text-decoration: none; transition: border-color 0.2s, background 0.2s;
        }
        .quick-link:hover { border-color: #444; background: #1a1a1a; }
        .quick-link-icon { font-size: 1.4rem; margin-bottom: 0.25rem; }
        .quick-link-title { font-size: 0.9rem; font-weight: 600; color: #fff; }
        .quick-link-desc { font-size: 0.75rem; color: var(--xn-muted); }

        .btn-dashboard {
            display: inline-block; width: 100%; background: linear-gradient(135deg, var(--xn-purple), #6d28d9);
            color: #fff; border: none; border-radius: 12px; padding: 0.9rem 1.5rem;
            font-size: 1rem; font-weight: 600; font-family: 'Space Grotesk', sans-serif;
            cursor: pointer; text-decoration: none; transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
        }
        .btn-dashboard:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 8px 25px rgba(124,58,237,0.35); }

        .profile-url {
            background: #111; border: 1px solid var(--xn-border); border-radius: 10px;
            padding: 0.75rem 1rem; font-size: 0.9rem; color: #888; margin-bottom: 2rem;
            font-family: monospace; display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        }
        .profile-url span { color: var(--xn-cyan); }
        .copy-btn {
            background: rgba(124,58,237,0.2); border: 1px solid rgba(124,58,237,0.3);
            color: #a78bfa; border-radius: 6px; padding: 0.25rem 0.75rem;
            font-size: 0.75rem; cursor: pointer; white-space: nowrap; font-family: 'Inter', sans-serif;
            transition: background 0.2s;
        }
        .copy-btn:hover { background: rgba(124,58,237,0.35); }
    </style>
</head>
<body>
<div class="card">
    <span class="emoji">🚀</span>
    <h1 class="title">You're all set, <span>{{ explode(' ', $user->name)[0] }}</span>!</h1>
    <p class="subtitle">
        Your Xenoraa profile is live. Start customising your portfolio, add blog posts, and let your AI assistant capture leads for you.
    </p>

    <div class="profile-url">
        <span>xenoraa.com/{{ $user->username }}</span>
        <button class="copy-btn" onclick="navigator.clipboard.writeText('https://xenoraa.com/{{ $user->username }}').then(()=>this.textContent='Copied!')">Copy</button>
    </div>

    <div class="quick-links">
        <a href="{{ route('admin.dashboard') }}" class="quick-link">
            <span class="quick-link-icon">🏠</span>
            <span class="quick-link-title">Dashboard</span>
            <span class="quick-link-desc">Manage everything</span>
        </a>
        <a href="{{ route('admin.blog.index') }}" class="quick-link">
            <span class="quick-link-icon">✍️</span>
            <span class="quick-link-title">Write a Blog Post</span>
            <span class="quick-link-desc">Share your knowledge</span>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="quick-link">
            <span class="quick-link-icon">⚙️</span>
            <span class="quick-link-title">Site Settings</span>
            <span class="quick-link-desc">Customise your profile</span>
        </a>
        <a href="{{ route('admin.crm.leads') }}" class="quick-link">
            <span class="quick-link-icon">💬</span>
            <span class="quick-link-title">CRM Leads</span>
            <span class="quick-link-desc">View enquiries</span>
        </a>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="btn-dashboard">Go to Dashboard →</a>
</div>
</body>
</html>
