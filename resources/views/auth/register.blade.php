<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up | Gopi K Portfolio</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('favicon-32.png') }}" sizes="32x32">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg-primary: #0a0a0a; --bg-secondary: #111111; --bg-card: #1a1a1a;
            --text-primary: #ffffff; --text-secondary: #a0a0a0; --text-muted: #666666;
            --border: #2a2a2a; --border-light: #333333; --danger: #ef4444;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif; background-color: var(--bg-primary);
            color: var(--text-primary); margin: 0; min-height: 100vh;
            display: flex; align-items: center; justify-content: center; padding: 2rem;
            background-image: radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.02) 0%, transparent 60%),
                              radial-gradient(ellipse at 80% 20%, rgba(255,255,255,0.015) 0%, transparent 50%);
        }
        .auth-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 440px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }
        .auth-logo { text-align: center; margin-bottom: 2rem; }
        .auth-logo img { height: 36px; }
        .auth-logo p { color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.75rem; margin-bottom: 0; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.4rem; }
        .form-control {
            width: 100%; padding: 0.625rem 0.875rem; background-color: var(--bg-secondary);
            border: 1px solid var(--border-light); border-radius: 8px; color: var(--text-primary);
            font-size: 0.9rem; transition: border-color 0.2s; font-family: inherit;
        }
        .form-control:focus { outline: none; border-color: #555; }
        .form-control::placeholder { color: var(--text-muted); }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.7rem 1.25rem; border-radius: 8px; font-size: 0.9rem; font-weight: 600;
            cursor: pointer; border: none; transition: all 0.2s; width: 100%;
            text-decoration: none; font-family: inherit;
        }
        .btn-primary { background-color: var(--text-primary); color: var(--bg-primary); }
        .btn-primary:hover { background-color: #e0e0e0; }
        .btn-google { background: #fff; color: #333; border: 1px solid #ddd; }
        .btn-google:hover { background: #f5f5f5; }
        .btn-facebook { background: #1877F2; color: white; border: 1px solid #1877F2; }
        .btn-facebook:hover { background: #166fe5; }
        .error-text { color: #fca5a5; font-size: 0.8rem; margin-top: 0.25rem; }
        .info-box {
            background: rgba(59,130,246,0.08); border: 1px solid rgba(59,130,246,0.25);
            color: #93c5fd; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.8rem; margin-bottom: 1.5rem;
        }
        .divider {
            display: flex; align-items: center; gap: 1rem; margin: 1.5rem 0;
            color: var(--text-muted); font-size: 0.8rem;
        }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
        .social-login-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/gopi-logo-nav.png') }}" alt="Gopi K" style="height: 36px; background: #000; padding: 4px 8px; border-radius: 6px;">
            </a>
            <p>Create your account</p>
        </div>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            New accounts get <strong>Visitor</strong> access — read blog posts and leave comments. Admin can grant additional permissions.
        </div>

        {{-- Social Signup --}}
        <div class="social-login-grid">
            <a href="{{ route('social.redirect', 'google') }}" class="btn btn-google">
                <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/>
                    <path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/>
                    <path d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/>
                    <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/>
                </svg>
                Google
            </a>
            <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-facebook">
                <i class="fab fa-facebook-f"></i>
                Facebook
            </a>
        </div>

        <div class="divider">or sign up with email</div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" value="{{ old('name') }}" required autofocus>
                @error('name')<p class="error-text">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required>
                @error('email')<p class="error-text">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Minimum 8 characters" required>
                @error('password')<p class="error-text">{{ $message }}</p>@enderror
            </div>
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: var(--text-muted);">
            Already have an account? <a href="{{ route('login') }}" style="color: var(--text-primary); text-decoration: none; font-weight: 600;">Sign In</a>
        </p>

        <p style="text-align: center; margin-top: 1rem;">
            <a href="{{ route('home') }}" style="font-size: 0.8rem; color: var(--text-muted); text-decoration: none;"><i class="fas fa-arrow-left"></i> Back to Portfolio</a>
        </p>
    </div>
</body>
</html>
