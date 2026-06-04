@extends('layouts.xenoraa')
@section('title', 'Get Started — Xenoraa')
@section('meta_description', 'Create your Xenoraa account and start building your digital identity today. Free 14-day trial.')

@section('styles')
<style>
.xn-auth-section {
    min-height: calc(100vh - 72px);
    display: flex; align-items: center;
    background: #000;
    position: relative; overflow: hidden;
}
.xn-auth-bg {
    position: absolute; inset: 0;
    background-image: url('/images/xenoraa/hero-bg.jpg');
    background-size: cover; background-position: center;
    opacity: 0.15;
}
.xn-auth-container {
    position: relative; z-index: 2;
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 5rem; align-items: center;
    max-width: 1100px; margin: 0 auto;
    padding: 4rem;
    width: 100%;
}
.xn-auth-card {
    background: rgba(13,13,13,0.95);
    border: 1px solid #1f1f1f;
    border-radius: 20px; padding: 3rem;
    backdrop-filter: blur(20px);
}
.xn-auth-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.75rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; }
.xn-auth-subtitle { font-size: 0.875rem; color: #71717a; margin-bottom: 2rem; }
.xn-form-group { margin-bottom: 1.25rem; }
.xn-form-label { display: block; font-size: 0.8rem; font-weight: 600; color: #a1a1aa; margin-bottom: 0.5rem; }
.xn-form-input {
    width: 100%; padding: 0.75rem 1rem;
    background: #111; border: 1px solid #222;
    border-radius: 8px; color: #fff;
    font-size: 0.875rem; font-family: 'Inter', sans-serif;
    transition: all 0.2s; outline: none;
}
.xn-form-input:focus { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,0.1); }
.xn-form-input::placeholder { color: #3f3f46; }
.xn-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.xn-form-submit {
    width: 100%; padding: 0.875rem;
    background: #7c3aed; color: #fff;
    border: none; border-radius: 8px;
    font-size: 0.9rem; font-weight: 700;
    cursor: pointer; transition: all 0.2s;
    font-family: 'Inter', sans-serif;
    margin-top: 0.5rem;
}
.xn-form-submit:hover { background: #6d28d9; transform: translateY(-1px); }
.xn-form-divider { display: flex; align-items: center; gap: 1rem; margin: 1.5rem 0; }
.xn-form-divider::before, .xn-form-divider::after { content: ''; flex: 1; height: 1px; background: #1a1a1a; }
.xn-form-divider span { font-size: 0.75rem; color: #3f3f46; }
.xn-plan-select { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1.25rem; }
.xn-plan-option { border: 1px solid #1f1f1f; border-radius: 8px; padding: 0.875rem; cursor: pointer; transition: all 0.2s; text-align: center; }
.xn-plan-option:hover, .xn-plan-option.selected { border-color: #7c3aed; background: rgba(124,58,237,0.08); }
.xn-plan-option-name { font-size: 0.75rem; font-weight: 700; color: #a1a1aa; }
.xn-plan-option-price { font-size: 0.65rem; color: #52525b; margin-top: 0.2rem; }
.xn-benefits { list-style: none; display: flex; flex-direction: column; gap: 1rem; }
.xn-benefits li { display: flex; align-items: flex-start; gap: 0.875rem; }
.xn-benefit-icon { width: 32px; height: 32px; background: rgba(124,58,237,0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #a855f7; font-size: 0.875rem; flex-shrink: 0; }
.xn-benefit-title { font-weight: 600; color: #fff; font-size: 0.875rem; margin-bottom: 0.15rem; }
.xn-benefit-desc { font-size: 0.775rem; color: #71717a; line-height: 1.5; }
@media(max-width:1024px){.xn-auth-container{grid-template-columns:1fr;padding:2rem;max-width:520px;}}
@media(max-width:768px){.xn-auth-card{padding:2rem;}.xn-form-row{grid-template-columns:1fr;}.xn-plan-select{grid-template-columns:1fr;}}
</style>
@endsection

@section('content')
<section class="xn-auth-section">
    <div class="xn-auth-bg"></div>
    <div class="xn-auth-container">
        {{-- Left: Benefits --}}
        <div>
            <div class="xn-label">Get Started</div>
            <h1 class="xn-heading-lg" style="margin-bottom:1rem;">Start Building Your<br><span style="color:#a855f7;">Digital Identity</span></h1>
            <p class="xn-body" style="margin-bottom:2.5rem;">Join professionals who are already using Xenoraa to showcase their expertise and manage their business.</p>
            <ul class="xn-benefits">
                <li>
                    <div class="xn-benefit-icon"><i class="fas fa-bolt"></i></div>
                    <div>
                        <div class="xn-benefit-title">Setup in 2 Minutes</div>
                        <div class="xn-benefit-desc">Your profile goes live instantly. No technical knowledge required.</div>
                    </div>
                </li>
                <li>
                    <div class="xn-benefit-icon"><i class="fas fa-globe"></i></div>
                    <div>
                        <div class="xn-benefit-title">Your Own URL</div>
                        <div class="xn-benefit-desc">Get xenoraa.com/yourname instantly. Map your custom domain anytime.</div>
                    </div>
                </li>
                <li>
                    <div class="xn-benefit-icon"><i class="fas fa-robot"></i></div>
                    <div>
                        <div class="xn-benefit-title">AI-Powered from Day One</div>
                        <div class="xn-benefit-desc">Your AI assistant starts capturing leads and engaging visitors immediately.</div>
                    </div>
                </li>
                <li>
                    <div class="xn-benefit-icon"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <div class="xn-benefit-title">14-Day Free Trial</div>
                        <div class="xn-benefit-desc">No credit card required. Full access to all features for 14 days.</div>
                    </div>
                </li>
            </ul>
        </div>

        {{-- Right: Registration Form --}}
        <div class="xn-auth-card">
            <div class="xn-auth-title">Create Your Account</div>
            <div class="xn-auth-subtitle">Start your 14-day free trial. No credit card required.</div>

            @if($errors->any())
            <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:1rem;margin-bottom:1.5rem;font-size:0.825rem;color:#f87171;">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="xn-form-row">
                    <div class="xn-form-group">
                        <label class="xn-form-label">First Name *</label>
                        <input type="text" name="first_name" class="xn-form-input" placeholder="Gopi" required value="{{ old('first_name') }}">
                    </div>
                    <div class="xn-form-group">
                        <label class="xn-form-label">Last Name</label>
                        <input type="text" name="last_name" class="xn-form-input" placeholder="K" value="{{ old('last_name') }}">
                    </div>
                </div>
                <div class="xn-form-group">
                    <label class="xn-form-label">Full Name (Display) *</label>
                    <input type="text" name="name" class="xn-form-input" placeholder="Gopi K." required value="{{ old('name') }}">
                </div>
                <div class="xn-form-group">
                    <label class="xn-form-label">Email Address *</label>
                    <input type="email" name="email" class="xn-form-input" placeholder="gopi@example.com" required value="{{ old('email') }}">
                </div>
                <div class="xn-form-group">
                    <label class="xn-form-label">Your Profession / Role</label>
                    <input type="text" name="profession" class="xn-form-input" placeholder="e.g. Business Owner, Consultant, Doctor..." value="{{ old('profession') }}">
                </div>
                <div class="xn-form-group">
                    <label class="xn-form-label">Choose Your Plan</label>
                    <div class="xn-plan-select">
                        <div class="xn-plan-option selected" onclick="selectPlan(this,'starter')">
                            <div class="xn-plan-option-name">Starter</div>
                            <div class="xn-plan-option-price">₹499/mo</div>
                        </div>
                        <div class="xn-plan-option" onclick="selectPlan(this,'professional')">
                            <div class="xn-plan-option-name" style="color:#a855f7;">Professional</div>
                            <div class="xn-plan-option-price">₹999/mo</div>
                        </div>
                        <div class="xn-plan-option" onclick="selectPlan(this,'business')">
                            <div class="xn-plan-option-name">Business Pro</div>
                            <div class="xn-plan-option-price">₹1,999/mo</div>
                        </div>
                    </div>
                    <input type="hidden" name="plan" id="selectedPlan" value="starter">
                </div>
                <div class="xn-form-group">
                    <label class="xn-form-label">Password *</label>
                    <input type="password" name="password" class="xn-form-input" placeholder="Minimum 8 characters" required>
                </div>
                <div class="xn-form-group">
                    <label class="xn-form-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="xn-form-input" placeholder="Repeat your password" required>
                </div>
                <button type="submit" class="xn-form-submit">Create My Account 🚀</button>
            </form>

            <div class="xn-form-divider"><span>Already have an account?</span></div>
            <a href="{{ route('login') }}" style="display:block;text-align:center;color:#a855f7;text-decoration:none;font-size:0.875rem;font-weight:600;">Sign In →</a>
            <p style="text-align:center;font-size:0.75rem;color:#3f3f46;margin-top:1.5rem;">By signing up, you agree to our <a href="#" style="color:#52525b;">Terms of Service</a> and <a href="#" style="color:#52525b;">Privacy Policy</a>.</p>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
function selectPlan(el, plan) {
    document.querySelectorAll('.xn-plan-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('selectedPlan').value = plan;
}
</script>
@endsection
