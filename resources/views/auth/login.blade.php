<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In | Gopi K Portfolio</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --bg-primary: #0a0a0a; --bg-secondary: #111111; --bg-card: #1a1a1a; --text-primary: #ffffff; --text-secondary: #a0a0a0; --text-muted: #666666; --border: #2a2a2a; --border-light: #333333; --danger: #ef4444; }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-primary); color: var(--text-primary); margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .auth-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 420px; }
        .auth-logo { text-align: center; margin-bottom: 2rem; }
        .auth-logo a { font-size: 1.8rem; font-weight: 800; color: var(--text-primary); text-decoration: none; }
        .auth-logo a span { color: var(--text-secondary); }
        .auth-logo p { color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.5rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.4rem; }
        .form-control { width: 100%; padding: 0.625rem 0.875rem; background-color: var(--bg-secondary); border: 1px solid var(--border-light); border-radius: 8px; color: var(--text-primary); font-size: 0.9rem; transition: border-color 0.2s; }
        .form-control:focus { outline: none; border-color: #555; }
        .form-control::placeholder { color: var(--text-muted); }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem; padding: 0.625rem 1.25rem; border-radius: 8px; font-size: 0.9rem; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; width: 100%; }
        .btn-primary { background-color: var(--text-primary); color: var(--bg-primary); }
        .btn-primary:hover { background-color: #e0e0e0; }
        .alert-error { background-color: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.875rem; margin-bottom: 1rem; }
        .checkbox-label { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-secondary); cursor: pointer; }
        .checkbox-label input { accent-color: white; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <a href="{{ route('home') }}">Gopi<span>.K</span></a>
            <p>Sign in to your account</p>
        </div>

        @if($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
        @endif

        @if(session('status'))
        <div style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #86efac; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.875rem; margin-bottom: 1rem;">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                    <label class="form-label" for="password" style="margin-bottom: 0;">Password</label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: var(--text-muted); text-decoration: none;">Forgot password?</a>
                    @endif
                </div>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>

        @if(Route::has('register'))
        <p style="text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: var(--text-muted);">
            Don't have an account? <a href="{{ route('register') }}" style="color: var(--text-primary); text-decoration: none; font-weight: 600;">Sign Up</a>
        </p>
        @endif

        <p style="text-align: center; margin-top: 1rem;">
            <a href="{{ route('home') }}" style="font-size: 0.8rem; color: var(--text-muted); text-decoration: none;"><i class="fas fa-arrow-left"></i> Back to Portfolio</a>
        </p>
    </div>
</body>
</html>
