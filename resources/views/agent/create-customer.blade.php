@extends('layouts.superadmin')
@section('title', 'Create Customer')
@section('content')
<div class="sa-content" style="max-width:700px;">
    <div style="margin-bottom:2rem;">
        <a href="{{ route('agent.dashboard') }}" style="color:#a78bfa;text-decoration:none;font-size:0.8rem;"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0.5rem 0 0.25rem;">Create Customer</h1>
        <p style="font-size:0.8rem;color:#71717a;margin:0;">
            You have <strong style="color:#22c55e;">{{ $agent->available_quota }}</strong> subscription(s) available to assign.
        </p>
    </div>

    @if(session('error'))<div style="background:#ef444422;border:1px solid #ef4444;color:#fca5a5;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.85rem;">{{ session('error') }}</div>@endif

    <form method="POST" action="{{ route('agent.store-customer') }}">
        @csrf
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-user" style="color:#22c55e;margin-right:0.5rem;"></i> Customer Details</span></div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div><label class="sa-label">Full Name *</label><input type="text" name="name" value="{{ old('name') }}" required class="sa-input">@error('name')<span class="sa-err">{{ $message }}</span>@enderror</div>
                <div><label class="sa-label">Email *</label><input type="email" name="email" value="{{ old('email') }}" required class="sa-input">@error('email')<span class="sa-err">{{ $message }}</span>@enderror</div>
                <div><label class="sa-label">Username * <span style="font-weight:400;text-transform:none;">(lowercase, no spaces)</span></label><input type="text" name="username" value="{{ old('username') }}" required class="sa-input" pattern="[a-z0-9_]+">@error('username')<span class="sa-err">{{ $message }}</span>@enderror</div>
                <div><label class="sa-label">Password *</label><input type="password" name="password" required class="sa-input">@error('password')<span class="sa-err">{{ $message }}</span>@enderror</div>
                <div><label class="sa-label">Phone</label><input type="text" name="phone" value="{{ old('phone') }}" class="sa-input"></div>
                <div>
                    <label class="sa-label">Profession *</label>
                    <select name="profession" required class="sa-input">
                        <option value="">Select profession…</option>
                        @foreach($professions as $key => $label)
                        <option value="{{ $key }}" {{ old('profession')==$key?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="sa-label">Plan *</label>
                    <select name="plan" required class="sa-input">
                        @foreach($availablePlans as $p)
                        <option value="{{ $p }}" {{ old('plan')==$p?'selected':'' }}>{{ ucfirst($p) }}</option>
                        @endforeach
                        @if(empty($availablePlans))<option value="" disabled>No quota available</option>@endif
                    </select>
                    @error('plan')<span class="sa-err">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div style="background:#22c55e11;border:1px solid #22c55e44;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.82rem;color:#86efac;">
            <i class="fas fa-info-circle" style="margin-right:0.5rem;"></i>
            Your commission of <strong>{{ $agent->commission_rate }}%</strong> will be automatically calculated and tracked for this subscription.
        </div>

        <div style="display:flex;gap:1rem;justify-content:flex-end;">
            <a href="{{ route('agent.dashboard') }}" style="background:#27272a;color:#a1a1aa;padding:0.75rem 1.5rem;border-radius:8px;text-decoration:none;font-size:0.875rem;">Cancel</a>
            <button type="submit" class="sa-btn-primary" style="background:#22c55e;" {{ empty($availablePlans) ? 'disabled' : '' }}>Create Customer</button>
        </div>
    </form>
</div>
<style>
.sa-input{width:100%;background:#111;border:1px solid #27272a;color:#fff;padding:0.65rem 1rem;border-radius:8px;font-size:0.875rem;outline:none;box-sizing:border-box;}
.sa-input:focus{border-color:#22c55e;}
.sa-label{display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;}
.sa-err{color:#ef4444;font-size:0.75rem;}
.sa-btn-primary{background:#7c3aed;color:#fff;border:none;padding:0.75rem 1.5rem;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;}
.sa-btn-primary:hover{opacity:0.9;}
</style>
@endsection
