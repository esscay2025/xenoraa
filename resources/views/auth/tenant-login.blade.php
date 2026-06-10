@php
    // Load tenant site settings for branding
    $tenantLoginSettings = \App\Models\SiteSetting::where('user_id', $tenant->id)
        ->pluck('value', 'key')
        ->toArray();
    $loginSiteName    = $tenantLoginSettings['site_name']    ?? $tenant->name;
    $loginTagline     = $tenantLoginSettings['site_tagline'] ?? ($tenant->profile_tagline ?? $tenant->profession ?? 'Member Portal');
    $loginLogoPath    = $tenantLoginSettings['logo_path']    ?? null;
    $loginFaviconPath = $tenantLoginSettings['favicon_path'] ?? null;
    $loginAccent      = $tenantLoginSettings['color_accent'] ?? '#22c55e';
    $loginAccentDark  = $tenantLoginSettings['color_primary'] ?? '#16a34a';
    $loginBg          = $tenantLoginSettings['color_bg']     ?? null;
    // Compute a slightly darker shade for gradient
    $loginBgStyle     = $loginBg ? "background: {$loginBg};" : '';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | {{ $loginSiteName }}</title>
    @if($loginFaviconPath)
    <link rel="shortcut icon" href="{{ $loginFaviconPath }}">
    <link rel="icon" type="image/png" href="{{ $loginFaviconPath }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --login-accent: {{ $loginAccent }};
            --login-accent-dark: {{ $loginAccentDark }};
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0f0f1a;
            {{ $loginBgStyle }}
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 2rem;
        }
        .login-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            backdrop-filter: blur(10px);
        }
        .tenant-logo-wrap {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .tenant-logo-wrap img {
            max-height: 64px;
            max-width: 200px;
            object-fit: contain;
        }
        .tenant-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--login-accent-dark), var(--login-accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin: 0 auto 1.5rem;
        }
        .tenant-name {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 0.5rem;
        }
        .tenant-tagline {
            text-align: center;
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        label {
            display: block;
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px;
            color: #f1f5f9;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }
        input:focus {
            outline: none;
            border-color: var(--login-accent);
        }
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .remember-row label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #94a3b8;
            font-size: 0.875rem;
            cursor: pointer;
            margin: 0;
        }
        .forgot-link {
            color: var(--login-accent);
            font-size: 0.875rem;
            text-decoration: none;
        }
        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, var(--login-accent-dark), var(--login-accent));
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-login:hover { opacity: 0.9; }
        .divider {
            text-align: center;
            color: #475569;
            font-size: 0.85rem;
            margin: 1.5rem 0;
            position: relative;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: rgba(255,255,255,0.08);
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .back-link a {
            color: var(--login-accent);
            text-decoration: none;
            font-size: 0.875rem;
        }
        .alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            color: #fca5a5;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }
        .alert-success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.3);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            color: #86efac;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }
        .powered-by {
            text-align: center;
            margin-top: 2rem;
            color: #475569;
            font-size: 0.8rem;
        }
        .powered-by a { color: var(--login-accent); text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            {{-- Logo or Avatar --}}
            @if($loginLogoPath)
                <div class="tenant-logo-wrap">
                    <img src="{{ $loginLogoPath }}" alt="{{ $loginSiteName }}">
                </div>
            @else
                <div class="tenant-avatar">
                    {{ strtoupper(substr($loginSiteName, 0, 1)) }}
                </div>
            @endif

            <h1 class="tenant-name">{{ $loginSiteName }}</h1>
            <p class="tenant-tagline">{{ $loginTagline }}</p>

            @if(session('status'))
                <div class="alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                {{-- Hidden field to remember which tenant this login is for --}}
                <input type="hidden" name="tenant_username" value="{{ $tenant->username }}">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           placeholder="your@email.com" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••" required>
                </div>
                <div class="remember-row">
                    <label>
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="back-link">
                <a href="{{ url('/' . $tenant->username) }}">
                    <i class="fas fa-arrow-left"></i> Back to {{ $loginSiteName }}
                </a>
            </div>
        </div>
        <div class="powered-by">
            Powered by <a href="https://xenoraa.com" target="_blank">Xenoraa</a>
        </div>
    </div>
</body>
</html>
