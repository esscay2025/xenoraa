@extends('layouts.app')

@section('title', 'My Profile — gopi.blog')

@section('content')
<div style="max-width:760px;margin:3rem auto;padding:0 1.5rem;">

    <div style="margin-bottom:2rem;">
        <a href="{{ route('user.dashboard') }}" style="color:#6366f1;text-decoration:none;font-size:0.875rem;"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <h1 style="font-size:1.75rem;font-weight:700;color:var(--text-primary);margin:0.5rem 0 0.25rem;">My Profile</h1>
        <p style="color:var(--text-secondary);margin:0;">Manage your account information and security settings.</p>
    </div>

    {{-- Success Messages --}}
    @if(session('status') === 'profile-updated')
        <div style="background:#064e3b;border:1px solid #10b981;color:#6ee7b7;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;">
            <i class="fas fa-check-circle" style="margin-right:6px;"></i> Profile updated successfully.
        </div>
    @endif
    @if(session('status') === 'password-updated')
        <div style="background:#064e3b;border:1px solid #10b981;color:#6ee7b7;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;">
            <i class="fas fa-check-circle" style="margin-right:6px;"></i> Password updated successfully.
        </div>
    @endif

    {{-- Profile Information --}}
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1.75rem;margin-bottom:1.5rem;">
        <h2 style="font-size:1rem;font-weight:600;color:var(--text-primary);margin:0 0 0.25rem;">Profile Information</h2>
        <p style="font-size:0.875rem;color:var(--text-secondary);margin:0 0 1.5rem;">Update your name and email address.</p>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PATCH')

            <div style="margin-bottom:1.25rem;">
                <label for="name" style="display:block;font-size:0.8rem;font-weight:500;color:var(--text-secondary);margin-bottom:0.4rem;">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    style="width:100%;background:var(--bg-primary);border:1px solid var(--border);color:var(--text-primary);padding:0.65rem 0.875rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='var(--border)'">
                @error('name') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
            </div>

            <div style="margin-bottom:1.5rem;">
                <label for="email" style="display:block;font-size:0.8rem;font-weight:500;color:var(--text-secondary);margin-bottom:0.4rem;">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    style="width:100%;background:var(--bg-primary);border:1px solid var(--border);color:var(--text-primary);padding:0.65rem 0.875rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='var(--border)'">
                @error('email') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                style="background:#6366f1;color:#fff;padding:0.6rem 1.5rem;border-radius:8px;border:none;cursor:pointer;font-weight:600;font-size:0.875rem;">
                <i class="fas fa-save" style="margin-right:6px;"></i>Save Changes
            </button>
        </form>
    </div>

    {{-- Update Password --}}
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1.75rem;margin-bottom:1.5rem;">
        <h2 style="font-size:1rem;font-weight:600;color:var(--text-primary);margin:0 0 0.25rem;">Update Password</h2>
        <p style="font-size:0.875rem;color:var(--text-secondary);margin:0 0 1.5rem;">Use a long, random password to keep your account secure.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf @method('PUT')

            <div style="margin-bottom:1.25rem;">
                <label for="current_password" style="display:block;font-size:0.8rem;font-weight:500;color:var(--text-secondary);margin-bottom:0.4rem;">Current Password</label>
                <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                    style="width:100%;background:var(--bg-primary);border:1px solid var(--border);color:var(--text-primary);padding:0.65rem 0.875rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='var(--border)'">
                @error('current_password', 'updatePassword') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
            </div>

            <div style="margin-bottom:1.25rem;">
                <label for="password" style="display:block;font-size:0.8rem;font-weight:500;color:var(--text-secondary);margin-bottom:0.4rem;">New Password</label>
                <input type="password" id="password" name="password" autocomplete="new-password"
                    style="width:100%;background:var(--bg-primary);border:1px solid var(--border);color:var(--text-primary);padding:0.65rem 0.875rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='var(--border)'">
                @error('password', 'updatePassword') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
            </div>

            <div style="margin-bottom:1.5rem;">
                <label for="password_confirmation" style="display:block;font-size:0.8rem;font-weight:500;color:var(--text-secondary);margin-bottom:0.4rem;">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                    style="width:100%;background:var(--bg-primary);border:1px solid var(--border);color:var(--text-primary);padding:0.65rem 0.875rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='var(--border)'">
                @error('password_confirmation', 'updatePassword') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                style="background:#6366f1;color:#fff;padding:0.6rem 1.5rem;border-radius:8px;border:none;cursor:pointer;font-weight:600;font-size:0.875rem;">
                <i class="fas fa-lock" style="margin-right:6px;"></i>Update Password
            </button>
        </form>
    </div>

    {{-- Delete Account --}}
    <div style="background:var(--bg-card);border:1px solid #450a0a;border-radius:12px;padding:1.75rem;">
        <h2 style="font-size:1rem;font-weight:600;color:#ef4444;margin:0 0 0.25rem;">Delete Account</h2>
        <p style="font-size:0.875rem;color:var(--text-secondary);margin:0 0 1.5rem;">Once your account is deleted, all data will be permanently removed. This action cannot be undone.</p>

        <button onclick="document.getElementById('deleteModal').style.display='flex'"
            style="background:transparent;color:#ef4444;border:1px solid #450a0a;padding:0.6rem 1.5rem;border-radius:8px;cursor:pointer;font-weight:600;font-size:0.875rem;">
            <i class="fas fa-trash" style="margin-right:6px;"></i>Delete My Account
        </button>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:var(--bg-card);border:1px solid #450a0a;border-radius:16px;padding:2rem;width:90%;max-width:440px;">
        <h3 style="color:#ef4444;font-size:1rem;font-weight:600;margin:0 0 0.75rem;">Are you sure?</h3>
        <p style="color:var(--text-secondary);font-size:0.875rem;margin:0 0 1.5rem;">This will permanently delete your account and all associated data.</p>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf @method('DELETE')
            <div style="margin-bottom:1.25rem;">
                <label style="display:block;font-size:0.8rem;color:var(--text-secondary);margin-bottom:0.4rem;">Enter your password to confirm</label>
                <input type="password" name="password" required placeholder="Your current password"
                    style="width:100%;background:var(--bg-primary);border:1px solid var(--border);color:var(--text-primary);padding:0.65rem 0.875rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                @error('password', 'userDeletion') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
            </div>
            <div style="display:flex;gap:0.75rem;">
                <button type="submit" style="flex:1;background:#dc2626;color:#fff;padding:0.6rem;border-radius:8px;border:none;cursor:pointer;font-weight:600;">Delete Account</button>
                <button type="button" onclick="document.getElementById('deleteModal').style.display='none'"
                    style="background:#374151;color:#e2e8f0;padding:0.6rem 1.25rem;border-radius:8px;border:none;cursor:pointer;">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
