<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode | Xenoraa</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #050508; color: #f0f0f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .bg-grid { position: fixed; inset: 0; z-index: 0; background-image: linear-gradient(rgba(124,58,237,0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(124,58,237,0.04) 1px, transparent 1px); background-size: 60px 60px; }
        .bg-glow { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 700px; height: 700px; background: radial-gradient(circle, rgba(124,58,237,0.1) 0%, transparent 70%); z-index: 0; pointer-events: none; }
        .container { position: relative; z-index: 1; text-align: center; padding: 2rem; max-width: 560px; }
        .logo { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 2.5rem; text-decoration: none; }
        .logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #7c3aed, #06b6d4); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1rem; color: #fff; }
        .logo-text { font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; font-weight: 700; color: #f0f0f5; }
        .maintenance-icon { font-size: 4rem; margin-bottom: 1.5rem; display: block; }
        .maintenance-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(124,58,237,0.12); border: 1px solid rgba(124,58,237,0.25); color: #a78bfa; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem; }
        .dot { width: 6px; height: 6px; border-radius: 50%; background: #7c3aed; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.3} }
        .error-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.75rem; font-weight: 700; color: #f0f0f5; margin-bottom: 0.75rem; }
        .error-desc { font-size: 1rem; color: #6b6b8a; line-height: 1.6; margin-bottom: 2rem; }
        .progress-bar { height: 3px; background: #1e1e2e; border-radius: 2px; overflow: hidden; margin-bottom: 2rem; }
        .progress-fill { height: 100%; width: 60%; background: linear-gradient(90deg, #7c3aed, #06b6d4); border-radius: 2px; animation: progress 3s ease-in-out infinite; }
        @keyframes progress { 0%{width:20%} 50%{width:80%} 100%{width:20%} }
        .contact { font-size: 0.875rem; color: #6b6b8a; }
        .contact a { color: #a78bfa; text-decoration: none; }
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
        <div class="maintenance-badge"><div class="dot"></div> Scheduled Maintenance</div>
        <span class="maintenance-icon">🔧</span>
        <h1 class="error-title">We're Under Maintenance</h1>
        <p class="error-desc">Xenoraa is currently undergoing scheduled maintenance to bring you an even better experience. We'll be back shortly.</p>
        <div class="progress-bar"><div class="progress-fill"></div></div>
        <p class="contact">Questions? Contact us at <a href="mailto:support@xenoraa.com">support@xenoraa.com</a></p>
    </div>
</body>
</html>
