@extends('layouts.superadmin')
@section('title', 'Add Agent')
@section('content')
<div class="sa-content" style="max-width:800px;">
    <div style="margin-bottom:2rem;">
        <a href="{{ route('superadmin.agents.index') }}" style="color:#a78bfa;text-decoration:none;font-size:0.8rem;"><i class="fas fa-arrow-left"></i> Back to Agents</a>
        <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0.5rem 0 0.25rem;">Add New Agent</h1>
        <p style="font-size:0.8rem;color:#71717a;margin:0;">Create an agent account. Agents can sell subscriptions and earn commissions.</p>
    </div>

    @if(session('error'))<div style="background:#ef444422;border:1px solid #ef4444;color:#fca5a5;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.85rem;">{{ session('error') }}</div>@endif

    <form method="POST" action="{{ route('superadmin.agents.store') }}">
        @csrf

        {{-- Personal Info --}}
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-user" style="color:#7c3aed;margin-right:0.5rem;"></i> Personal Information</span></div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div><label class="sa-label">Full Name *</label><input type="text" name="name" value="{{ old('name') }}" required class="sa-input" placeholder="Agent full name">@error('name')<span class="sa-err">{{ $message }}</span>@enderror</div>
                <div><label class="sa-label">Email *</label><input type="email" name="email" value="{{ old('email') }}" required class="sa-input" placeholder="agent@example.com">@error('email')<span class="sa-err">{{ $message }}</span>@enderror</div>
                <div><label class="sa-label">Password *</label><input type="password" name="password" required class="sa-input" placeholder="Min. 8 characters">@error('password')<span class="sa-err">{{ $message }}</span>@enderror</div>
                <div><label class="sa-label">Phone</label><input type="text" name="phone" value="{{ old('phone') }}" class="sa-input" placeholder="+91 98765 43210"></div>
                <div><label class="sa-label">Company / Agency Name</label><input type="text" name="company_name" value="{{ old('company_name') }}" class="sa-input" placeholder="Optional"></div>
                <div><label class="sa-label">City</label><input type="text" name="city" value="{{ old('city') }}" class="sa-input"></div>
                <div><label class="sa-label">State</label><input type="text" name="state" value="{{ old('state') }}" class="sa-input"></div>
                <div><label class="sa-label">Country</label><input type="text" name="country" value="{{ old('country','India') }}" class="sa-input"></div>
            </div>
        </div>

        {{-- Commission --}}
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-percent" style="color:#22c55e;margin-right:0.5rem;"></i> Commission Settings</span></div>
            <div style="padding:1.5rem;">
                <div style="max-width:300px;">
                    <label class="sa-label">Commission Rate (%) *</label>
                    <input type="number" name="commission_rate" value="{{ old('commission_rate', 10) }}" min="0" max="100" step="0.5" required class="sa-input">
                    <p style="font-size:0.75rem;color:#71717a;margin-top:0.5rem;">Percentage of the subscription plan price the agent earns per sale.</p>
                    @error('commission_rate')<span class="sa-err">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- Banking --}}
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header"><span class="sa-card-title"><i class="fas fa-university" style="color:#3b82f6;margin-right:0.5rem;"></i> Banking Details <span style="font-weight:400;font-size:0.75rem;color:#71717a;">(for commission payouts)</span></span></div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div><label class="sa-label">PAN Number</label><input type="text" name="pan_number" value="{{ old('pan_number') }}" class="sa-input" placeholder="ABCDE1234F"></div>
                <div><label class="sa-label">GST Number</label><input type="text" name="gst_number" value="{{ old('gst_number') }}" class="sa-input" placeholder="Optional"></div>
                <div><label class="sa-label">Bank Name</label><input type="text" name="bank_name" value="{{ old('bank_name') }}" class="sa-input" placeholder="HDFC Bank"></div>
                <div><label class="sa-label">Account Number</label><input type="text" name="bank_account_no" value="{{ old('bank_account_no') }}" class="sa-input"></div>
                <div><label class="sa-label">IFSC Code</label><input type="text" name="bank_ifsc" value="{{ old('bank_ifsc') }}" class="sa-input" placeholder="HDFC0001234"></div>
            </div>
        </div>

        <div style="display:flex;gap:1rem;justify-content:flex-end;">
            <a href="{{ route('superadmin.agents.index') }}" style="background:#27272a;color:#a1a1aa;padding:0.75rem 1.5rem;border-radius:8px;text-decoration:none;font-size:0.875rem;">Cancel</a>
            <button type="submit" class="sa-btn-primary">Create Agent</button>
        </div>
    </form>
</div>
<style>
.sa-input{width:100%;background:#111;border:1px solid #27272a;color:#fff;padding:0.65rem 1rem;border-radius:8px;font-size:0.875rem;outline:none;box-sizing:border-box;}
.sa-input:focus{border-color:#7c3aed;}
.sa-label{display:block;font-size:0.75rem;font-weight:700;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;}
.sa-err{color:#ef4444;font-size:0.75rem;}
.sa-btn-primary{background:#7c3aed;color:#fff;border:none;padding:0.75rem 1.5rem;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;}
.sa-btn-primary:hover{background:#6d28d9;}
</style>
@endsection
