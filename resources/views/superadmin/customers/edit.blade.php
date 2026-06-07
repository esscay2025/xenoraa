@extends('layouts.superadmin')
@section('title', 'Edit Customer')
@section('content')
<div class="sa-content" style="max-width:760px;">
    <div style="margin-bottom:2rem;">
        <a href="{{ route('superadmin.customers.show', $customer->id) }}" style="color:#a78bfa;text-decoration:none;font-size:0.8rem;"><i class="fas fa-arrow-left"></i> Back to {{ $customer->name }}</a>
        <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0.5rem 0 0.25rem;">Edit Customer</h1>
    </div>

    @if(session('success'))<div style="background:#22c55e22;border:1px solid #22c55e;color:#86efac;padding:0.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.85rem;">{{ session('success') }}</div>@endif

    <form method="POST" action="{{ route('superadmin.customers.update', $customer->id) }}">
        @csrf @method('PUT')
        <div class="sa-card" style="margin-bottom:1.5rem;">
            <div class="sa-card-header"><span class="sa-card-title">Account Details</span></div>
            <div style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label class="sa-label">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="sa-input">
                    @error('name')<span class="sa-err">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="sa-label">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" required class="sa-input">
                    @error('email')<span class="sa-err">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="sa-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="sa-input">
                </div>
                <div>
                    <label class="sa-label">City</label>
                    <input type="text" name="city" value="{{ old('city', $customer->city) }}" class="sa-input">
                </div>
                <div>
                    <label class="sa-label">Plan *</label>
                    <select name="plan" required class="sa-input">
                        @foreach(['starter','professional','business'] as $p)
                        <option value="{{ $p }}" {{ old('plan',$customer->plan)==$p?'selected':'' }}>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="sa-label">Status *</label>
                    <select name="status" required class="sa-input">
                        @foreach(['active','inactive','suspended'] as $s)
                        <option value="{{ $s }}" {{ old('status',$customer->status)==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="sa-label">Plan Expires At</label>
                    <input type="date" name="plan_expires_at" value="{{ old('plan_expires_at', $customer->plan_expires_at?->format('Y-m-d')) }}" class="sa-input">
                </div>
            </div>
        </div>
        <div style="display:flex;gap:1rem;justify-content:flex-end;">
            <a href="{{ route('superadmin.customers.show', $customer->id) }}" style="background:#27272a;color:#a1a1aa;padding:0.75rem 1.5rem;border-radius:8px;text-decoration:none;font-size:0.875rem;">Cancel</a>
            <button type="submit" class="sa-btn-primary">Save Changes</button>
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
