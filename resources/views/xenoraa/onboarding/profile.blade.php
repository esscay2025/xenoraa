<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Up Your Profile — Xenoraa</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --xn-black: #0a0a0a; --xn-card: #161616; --xn-border: #2a2a2a;
            --xn-purple: #7c3aed; --xn-cyan: #06b6d4; --xn-pink: #ec4899;
            --xn-text: #f5f5f5; --xn-muted: #888; --xn-input: #1e1e1e;
        }
        body {
            font-family: 'Inter', sans-serif; background: var(--xn-black); color: var(--xn-text);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 2rem; position: relative;
        }
        body::before {
            content: ''; position: fixed; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(ellipse at 30% 40%, rgba(124,58,237,0.08) 0%, transparent 50%);
            pointer-events: none; z-index: 0;
        }
        .card {
            position: relative; z-index: 1; width: 100%; max-width: 520px;
            background: var(--xn-card); border: 1px solid var(--xn-border);
            border-radius: 24px; padding: 2.5rem; overflow: hidden;
        }
        .card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--xn-purple), var(--xn-cyan), var(--xn-pink));
        }
        .step-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.3);
            border-radius: 50px; padding: 0.3rem 0.9rem;
            font-size: 0.78rem; color: #a78bfa; font-weight: 500; margin-bottom: 1.5rem;
        }
        .title { font-family: 'Space Grotesk', sans-serif; font-size: 1.6rem; font-weight: 700; letter-spacing: -0.02em; margin-bottom: 0.4rem; }
        .subtitle { color: var(--xn-muted); font-size: 0.9rem; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 500; color: #ccc; margin-bottom: 0.5rem; }
        .form-label .optional { color: #555; font-weight: 400; font-size: 0.78rem; }
        .form-input {
            width: 100%; background: var(--xn-input); border: 1px solid var(--xn-border);
            border-radius: 10px; padding: 0.75rem 1rem; color: var(--xn-text);
            font-size: 0.95rem; font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s; outline: none;
        }
        .form-input:focus { border-color: var(--xn-purple); box-shadow: 0 0 0 3px rgba(124,58,237,0.15); }
        .form-input::placeholder { color: #555; }
        textarea.form-input { resize: vertical; min-height: 100px; }
        .char-count { font-size: 0.75rem; color: #555; text-align: right; margin-top: 0.3rem; }
        .btn-primary {
            width: 100%; background: linear-gradient(135deg, var(--xn-purple), #6d28d9);
            color: #fff; border: none; border-radius: 12px; padding: 0.9rem 1.5rem;
            font-size: 1rem; font-weight: 600; font-family: 'Space Grotesk', sans-serif;
            cursor: pointer; transition: opacity 0.2s, transform 0.2s; margin-bottom: 0.75rem;
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-skip {
            display: block; width: 100%; text-align: center; padding: 0.75rem;
            color: var(--xn-muted); font-size: 0.875rem; text-decoration: none;
            transition: color 0.2s;
        }
        .btn-skip:hover { color: #ccc; }
        .alert-error {
            background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3);
            color: #fca5a5; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.875rem; margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="step-badge">Step 1 of 1 — Profile Setup</div>
    <h2 class="title">Tell us about yourself</h2>
    <p class="subtitle">This helps personalise your Xenoraa profile page at <strong>xenoraa.com/{{ $user->username }}</strong></p>

    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('onboarding.profile.save') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="site_title">Profile / Site Title <span class="optional">(optional)</span></label>
            <input id="site_title" type="text" name="site_title" class="form-input"
                value="{{ old('site_title', $user->name) }}"
                placeholder="e.g. Gopi K — Full Stack Developer">
        </div>

        <div class="form-group">
            <label class="form-label" for="profession">Profession / Role <span class="optional">(optional)</span></label>
            <input id="profession" type="text" name="profession" class="form-input"
                value="{{ old('profession', $user->profession ?? '') }}"
                placeholder="e.g. Software Developer, Business Owner, Designer">
        </div>

        <div class="form-group">
            <label class="form-label" for="bio">Short Bio <span class="optional">(optional)</span></label>
            <textarea id="bio" name="bio" class="form-input"
                placeholder="Tell visitors who you are and what you do..."
                maxlength="500"
                oninput="document.getElementById('bio-count').textContent = this.value.length">{{ old('bio', $user->bio ?? '') }}</textarea>
            <p class="char-count"><span id="bio-count">{{ strlen($user->bio ?? '') }}</span>/500</p>
        </div>

        <button type="submit" class="btn-primary">Save &amp; Continue →</button>
        <a href="{{ route('onboarding.complete') }}" class="btn-skip">Skip this step</a>
    </form>
</div>
</body>
</html>
