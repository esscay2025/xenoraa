<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error | Xenoraa</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #050508; color: #f0f0f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .bg-grid { position: fixed; inset: 0; z-index: 0; background-image: linear-gradient(rgba(239,68,68,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(239,68,68,0.03) 1px, transparent 1px); background-size: 60px 60px; }
        .bg-glow { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 600px; height: 600px; background: radial-gradient(circle, rgba(239,68,68,0.08) 0%, transparent 70%); z-index: 0; pointer-events: none; }
        .container { position: relative; z-index: 1; text-align: center; padding: 2rem; max-width: 560px; }
        .error-code { font-family: 'Space Grotesk', sans-serif; font-size: clamp(6rem, 20vw, 10rem); font-weight: 800; line-height: 1; background: linear-gradient(135deg, #ef4444, #f97316); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: -0.04em; margin-bottom: 0.5rem; }
        .error-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; font-weight: 700; color: #f0f0f5; margin-bottom: 0.75rem; }
        .error-desc { font-size: 1rem; color: #6b6b8a; line-height: 1.6; margin-bottom: 2rem; }
        .actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 0.75rem 1.5rem; background: #7c3aed; color: #fff; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: background 0.2s; }
        .btn-primary:hover { background: #6d28d9; }
        .btn-outline { display: inline-flex; align-items: center; gap: 8px; padding: 0.75rem 1.5rem; background: transparent; color: #9090aa; border: 1px solid #1e1e2e; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; }
        .btn-outline:hover { border-color: #7c3aed; color: #a78bfa; }
        .logo { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 2.5rem; text-decoration: none; }
        .logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #7c3aed, #06b6d4); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1rem; color: #fff; }
        .logo-text { font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; font-weight: 700; color: #f0f0f5; }
        .status-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5; padding: 0.4rem 0.9rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem; }
        .dot { width: 6px; height: 6px; border-radius: 50%; background: #ef4444; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.3} }
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
        <div class="status-badge"><div class="dot"></div> System Error Detected</div>
        <div class="error-code">500</div>
        <h1 class="error-title">Internal Server Error</h1>
        <p class="error-desc">Something went wrong on our end. Our team has been notified and is working to fix it. Please try again in a few moments.</p>
        <div class="actions">
            <a href="{{ url('/') }}" class="btn-primary">Go Home</a>
            <a href="javascript:location.reload()" class="btn-outline">Try Again</a>
        </div>
    </div>
</body>
</html>
