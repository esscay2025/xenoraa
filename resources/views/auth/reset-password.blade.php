@extends('layouts.app')
@section('title', 'Reset Password | Gopi K')
@push('styles')
<style>
    .auth-page { min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 3rem 1rem; }
    .auth-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 440px; }
    .auth-card h1 { font-size: 1.75rem; font-weight: 700; margin: 0 0 0.5rem; }
    .auth-card p.subtitle { color: var(--text-secondary); font-size: 0.9rem; margin: 0 0 2rem; }
    .btn-block { width: 100%; justify-content: center; padding: 0.75rem 1.25rem; font-size: 0.95rem; }
</style>
@endpush
@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h1>Reset Password</h1>
        <p class="subtitle">Choose a strong new password for your account.</p>

        @if ($errors->any())
            <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                <i class="fas fa-exclamation-circle"></i>
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $request->email) }}" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">New Password</label>
                <input id="password" name="password" type="password" class="form-control" placeholder="Minimum 8 characters" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Repeat your password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-lock"></i> Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
