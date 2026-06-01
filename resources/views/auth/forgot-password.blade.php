@extends('layouts.app')
@section('title', 'Forgot Password | Gopi K')
@push('styles')
<style>
    .auth-page { min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 3rem 1rem; }
    .auth-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 440px; }
    .auth-card h1 { font-size: 1.75rem; font-weight: 700; margin: 0 0 0.5rem; }
    .auth-card p.subtitle { color: var(--text-secondary); font-size: 0.9rem; margin: 0 0 2rem; line-height: 1.6; }
    .auth-divider { display: flex; align-items: center; gap: 1rem; margin: 1.5rem 0; }
    .auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
    .auth-divider span { color: var(--text-muted); font-size: 0.8rem; }
    .btn-block { width: 100%; justify-content: center; padding: 0.75rem 1.25rem; font-size: 0.95rem; }
</style>
@endpush
@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h1>Forgot Password?</h1>
        <p class="subtitle">No problem. Enter your email address and we will send you a password reset link so you can choose a new one.</p>

        @if (session('status'))
            <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                <i class="fas fa-exclamation-circle"></i>
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-paper-plane"></i> Send Reset Link
            </button>
        </form>

        <div class="auth-divider"><span>or</span></div>

        <div style="text-align: center;">
            <a href="{{ route('login') }}" style="color: var(--text-secondary); font-size: 0.875rem; text-decoration: none;">
                <i class="fas fa-arrow-left" style="margin-right: 0.4rem;"></i> Back to Sign In
            </a>
        </div>
    </div>
</div>
@endsection
