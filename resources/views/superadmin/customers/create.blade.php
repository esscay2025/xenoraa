@extends('layouts.superadmin')
@section('title', 'Create Customer')
@section('content')
<div class="sa-content" style="max-width:760px;">
    <div style="margin-bottom:2rem;">
        <a href="{{ route('superadmin.customers.index') }}" style="color:#a78bfa;text-decoration:none;font-size:0.8rem;"><i class="fas fa-arrow-left"></i> Back to Customers</a>
        <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0.5rem 0 0.25rem;">Create Customer</h1>
        <p style="font-size:0.8rem;color:#71717a;margin:0;">Create a new tenant account and optionally assign a subscription via an agent.</p>
    </div>

    @if(session('error'))<div style="background:#ef444422;border:1px solid #ef4444;color:#fca5a5;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.85rem;">{{ session('error') }}</div>@endif

    <form method="POST" action="{{ route('superadmin.customers.store') }}">
        @csrf

        {{-- Account Details --}}
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-user" style="color:#7c3aed;margin-right:0.5rem;"></i> Account Details</span></div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="sa-input" placeholder="e.g. Priya Sharma">
                    @error('name')<span style="color:#ef4444;font-size:0.75rem;">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="sa-input" placeholder="priya@example.com">
                    @error('email')<span style="color:#ef4444;font-size:0.75rem;">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Username * <span style="font-weight:400;text-transform:none;letter-spacing:0;">(lowercase, no spaces)</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" required class="sa-input" placeholder="priyasharma" pattern="[a-z0-9_]+">
                    @error('username')<span style="color:#ef4444;font-size:0.75rem;">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Password *</label>
                    <input type="password" name="password" required class="sa-input" placeholder="Min. 8 characters">
                    @error('password')<span style="color:#ef4444;font-size:0.75rem;">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="sa-input" placeholder="+91 98765 43210">
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">City</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="sa-input" placeholder="Mumbai">
                </div>
            </div>
        </div>

        {{-- Profession & Plan --}}
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-briefcase" style="color:#7c3aed;margin-right:0.5rem;"></i> Profession & Subscription</span></div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Profession *</label>
                    <select name="profession" required class="sa-input">
                        <option value="">Select profession…</option>
                        @foreach($professions as $key => $label)
                        <option value="{{ $key }}" {{ old('profession')==$key?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('profession')<span style="color:#ef4444;font-size:0.75rem;">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Plan *</label>
                    <select name="plan" required class="sa-input">
                        <option value="starter" {{ old('plan','starter')=='starter'?'selected':'' }}>Starter — ₹499/mo</option>
                        <option value="professional" {{ old('plan')=='professional'?'selected':'' }}>Professional — ₹999/mo</option>
                        <option value="business" {{ old('plan')=='business'?'selected':'' }}>Business — ₹1,999/mo</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Duration (months)</label>
                    <input type="number" name="duration_months" value="{{ old('duration_months', 1) }}" min="1" max="24" class="sa-input">
                </div>
            </div>
        </div>

        {{-- Agent Assignment (optional) --}}
        @if($agents->count() > 0)
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-handshake" style="color:#7c3aed;margin-right:0.5rem;"></i> Agent Assignment <span style="font-weight:400;font-size:0.75rem;color:#71717a;">(optional)</span></span></div>
            <div style="padding:1.5rem;">
                <label style="display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Assign to Agent</label>
                <select name="agent_id" class="sa-input">
                    <option value="">No agent (direct sale)</option>
                    @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ old('agent_id')==$agent->id?'selected':'' }}>
                        {{ $agent->user->name }} ({{ $agent->agent_code }}) — {{ $agent->commission_rate }}% commission — {{ $agent->available_quota }} quota available
                    </option>
                    @endforeach
                </select>
                <p style="font-size:0.75rem;color:#71717a;margin-top:0.5rem;">If an agent is selected, their commission will be automatically calculated and tracked.</p>
            </div>
        </div>
        @endif

        <div style="display:flex;gap:1rem;justify-content:flex-end;">
            <a href="{{ route('superadmin.customers.index') }}" style="background:#27272a;color:#a1a1aa;padding:0.75rem 1.5rem;border-radius:8px;text-decoration:none;font-size:0.875rem;">Cancel</a>
            <button type="submit" class="sa-btn-primary">Create Customer</button>
        </div>
    </form>
</div>

<style>
.sa-input { width:100%;background:#111;border:1px solid #27272a;color:#fff;padding:0.65rem 1rem;border-radius:8px;font-size:0.875rem;outline:none;box-sizing:border-box; }
.sa-input:focus { border-color:#7c3aed; }
.sa-btn-primary { background:#7c3aed;color:#fff;border:none;padding:0.75rem 1.5rem;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none; }
.sa-btn-primary:hover { background:#6d28d9; }
</style>
@endsection
