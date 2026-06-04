<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found | Xenoraa</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #050508;
            color: #f0f0f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: 0;
            background-image: linear-gradient(rgba(124,58,237,0.04) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(124,58,237,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
        }
        .bg-glow {
            position: fixed; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(124,58,237,0.12) 0%, transparent 70%);
            z-index: 0; pointer-events: none;
        }
        .container {
            position: relative; z-index: 1;
            text-align: center;
            padding: 2rem;
            max-width: 560px;
        }
        .error-code {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(6rem, 20vw, 10rem);
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #7c3aed, #a78bfa, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.04em;
            margin-bottom: 0.5rem;
        }
        .error-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #f0f0f5;
            margin-bottom: 0.75rem;
        }
        .error-desc {
            font-size: 1rem;
            color: #6b6b8a;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 0.75rem 1.5rem;
            background: #7c3aed;
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.2s;
        }
        .btn-primary:hover { background: #6d28d9; }
        .btn-outline {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 0.75rem 1.5rem;
            background: transparent;
            color: #9090aa;
            border: 1px solid #1e1e2e;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .btn-outline:hover { border-color: #7c3aed; color: #a78bfa; }
        .logo {
            display: inline-flex; align-items: center; gap: 8px;
            margin-bottom: 2.5rem;
            text-decoration: none;
        }
        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #7c3aed, #06b6d4);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 800; font-size: 1rem; color: #fff;
        }
        .logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.1rem; font-weight: 700; color: #f0f0f5;
        }
    </style>
</head>
<body>
    <div class="bg-grid"></div>
    <div class="bg-glow"></div>
    <div class="container">
        <a href="{{ url('/') }}" class="logo">
            <div class="logo-icon">X</div>
            <span class="logo-text">xenoraa</span>
        </a>
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-desc">The page you're looking for doesn't exist or has been moved. Let's get you back on track.</p>
        <div class="actions">
            <a href="{{ url('/') }}" class="btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Go Home
            </a>
            <a href="javascript:history.back()" class="btn-outline">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                Go Back
            </a>
        </div>
    </div>
</body>
</html>
