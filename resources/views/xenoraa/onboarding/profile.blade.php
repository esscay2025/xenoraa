<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Set Up Your Profile — Xenoraa</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@700;800&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: #050508; font-family: 'Inter', sans-serif; color: #e2e8f0; min-height: 100vh; display: flex; flex-direction: column; }
.ob-nav { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 2rem; border-bottom: 1px solid #0f0f0f; }
.ob-logo { font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; font-weight: 800; color: #fff; letter-spacing: -0.04em; }
.ob-logo span { color: #a855f7; }
.ob-steps { display: flex; gap: 0.5rem; align-items: center; }
.ob-step { width: 32px; height: 4px; border-radius: 2px; background: #1a1a1a; }
.ob-step.done { background: #7c3aed; }
.ob-step.active { background: #a855f7; }
.ob-main { flex: 1; display: flex; align-items: flex-start; justify-content: center; padding: 3rem 1.5rem; }
.ob-card { background: #0d0d0d; border: 1px solid #1a1a1a; border-radius: 20px; padding: 3rem; width: 100%; max-width: 660px; }
.ob-step-label { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #7c3aed; margin-bottom: 0.75rem; }
.ob-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.75rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; }
.ob-subtitle { font-size: 0.875rem; color: #52525b; margin-bottom: 2rem; line-height: 1.6; }
.ob-section-title { font-size: 0.8rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.875rem; }
.ob-profession-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.6rem; margin-bottom: 1.25rem; }
.ob-prof-option { border: 1px solid #1f1f1f; border-radius: 10px; padding: 0.875rem 0.5rem; cursor: pointer; transition: all 0.2s; text-align: center; }
.ob-prof-option:hover { border-color: rgba(124,58,237,0.4); background: rgba(124,58,237,0.05); }
.ob-prof-option.selected { border-color: #7c3aed; background: rgba(124,58,237,0.12); }
.ob-prof-icon { font-size: 1.4rem; margin-bottom: 0.35rem; }
.ob-prof-name { font-size: 0.65rem; font-weight: 600; color: #a1a1aa; }
.ob-template-preview { background: #111; border: 1px solid #1f1f1f; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.75rem; display: flex; align-items: center; gap: 1rem; }
.ob-template-icon { width: 40px; height: 40px; border-radius: 8px; background: rgba(124,58,237,0.15); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
.ob-template-name { font-size: 0.875rem; font-weight: 700; color: #e2e8f0; }
.ob-template-desc { font-size: 0.75rem; color: #52525b; margin-top: 0.2rem; }
.ob-divider { height: 1px; background: #1a1a1a; margin: 1.5rem 0; }
.ob-form-group { margin-bottom: 1.25rem; }
.ob-label { display: block; font-size: 0.8rem; font-weight: 600; color: #71717a; margin-bottom: 0.5rem; }
.ob-input, .ob-textarea {
    width: 100%; padding: 0.75rem 1rem;
    background: #111; border: 1px solid #1f1f1f;
    border-radius: 8px; color: #e2e8f0;
    font-size: 0.875rem; font-family: 'Inter', sans-serif;
    transition: all 0.2s; outline: none;
}
.ob-input:focus, .ob-textarea:focus { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,0.1); }
.ob-input::placeholder, .ob-textarea::placeholder { color: #3f3f46; }
.ob-textarea { resize: vertical; min-height: 100px; }
.ob-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.ob-submit { width: 100%; padding: 0.9rem; background: #7c3aed; color: #fff; border: none; border-radius: 8px; font-size: 0.9rem; font-weight: 700; cursor: pointer; transition: all 0.2s; font-family: 'Inter', sans-serif; margin-top: 0.5rem; }
.ob-submit:hover { background: #6d28d9; transform: translateY(-1px); }
.ob-skip { display: block; text-align: center; color: #3f3f46; font-size: 0.8rem; text-decoration: none; margin-top: 1rem; }
.ob-skip:hover { color: #71717a; }
@media(max-width:600px){ .ob-profession-grid{grid-template-columns:repeat(3,1fr);} .ob-row{grid-template-columns:1fr;} .ob-card{padding:2rem 1.25rem;} }
</style>
</head>
<body>
<nav class="ob-nav">
    <div class="ob-logo">xeno<span>raa</span></div>
    <div class="ob-steps">
        <div class="ob-step done"></div>
        <div class="ob-step active"></div>
        <div class="ob-step"></div>
    </div>
</nav>

<main class="ob-main">
    <div class="ob-card">
        <div class="ob-step-label">Step 2 of 3 — Profile Setup</div>
        <div class="ob-title">Tell Us About Yourself</div>
        <div class="ob-subtitle">Select your profession and fill in your details. We'll apply the best template for your field and make your profile at <strong style="color:#a855f7;">xenoraa.com/{{ $user->username }}</strong> stand out.</div>

        @if($errors->any())
        <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:1rem;margin-bottom:1.5rem;font-size:0.825rem;color:#f87171;">
            @foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('onboarding.profile.save') }}">
            @csrf

            {{-- Profession Grid --}}
            <div class="ob-section-title">Your Profession</div>
            <div class="ob-profession-grid" id="professionGrid">
                @php
                $professions = [
                    ['value'=>'ecommerce','icon'=>'🛒','name'=>'E-Commerce'],
                    ['value'=>'business','icon'=>'🏢','name'=>'Business'],
                    ['value'=>'doctor','icon'=>'🩺','name'=>'Doctor'],
                    ['value'=>'advocate','icon'=>'⚖️','name'=>'Advocate'],
                    ['value'=>'politician','icon'=>'🏛️','name'=>'Politician'],
                    ['value'=>'consultant','icon'=>'📊','name'=>'Consultant'],
                    ['value'=>'entrepreneur','icon'=>'🚀','name'=>'Entrepreneur'],
                    ['value'=>'influencer','icon'=>'⭐','name'=>'Influencer'],
                    ['value'=>'software_developer','icon'=>'💻','name'=>'Developer'],
                    ['value'=>'designer','icon'=>'🎨','name'=>'Designer'],
                    ['value'=>'educator','icon'=>'📚','name'=>'Educator'],
                    ['value'=>'freelancer','icon'=>'🧑‍💻','name'=>'Freelancer'],
                    ['value'=>'other','icon'=>'✨','name'=>'Other'],
                ];
                $currentProfession = old('profession', $user->profession ?? '');
                @endphp
                @foreach($professions as $p)
                <div class="ob-prof-option {{ $currentProfession === $p['value'] ? 'selected' : '' }}"
                     onclick="selectProfession('{{ $p['value'] }}', this)">
                    <div class="ob-prof-icon">{{ $p['icon'] }}</div>
                    <div class="ob-prof-name">{{ $p['name'] }}</div>
                </div>
                @endforeach
            </div>
            <input type="hidden" name="profession" id="professionInput" value="{{ old('profession', $user->profession ?? '') }}">

            {{-- Template Preview --}}
            <div class="ob-template-preview">
                <div class="ob-template-icon" id="templateIcon">🎨</div>
                <div>
                    <div class="ob-template-name" id="templateName">Select a profession above</div>
                    <div class="ob-template-desc" id="templateDesc">We'll apply the best design for your field automatically</div>
                </div>
            </div>

            <div class="ob-divider"></div>

            {{-- Basic Info --}}
            <div class="ob-form-group">
                <label class="ob-label">Profile / Site Title *</label>
                <input type="text" name="site_title" class="ob-input" placeholder="e.g. Dr. Ravi Kumar — Cardiologist" required value="{{ old('site_title', $user->name) }}">
            </div>
            <div class="ob-form-group">
                <label class="ob-label">Tagline</label>
                <input type="text" name="tagline" class="ob-input" placeholder="e.g. Helping businesses grow with technology" value="{{ old('tagline') }}">
            </div>
            <div class="ob-form-group">
                <label class="ob-label">About / Bio</label>
                <textarea name="bio" class="ob-textarea" placeholder="Write a short introduction about yourself and your work...">{{ old('bio') }}</textarea>
            </div>
            <div class="ob-row">
                <div class="ob-form-group">
                    <label class="ob-label">Phone</label>
                    <input type="text" name="phone" class="ob-input" placeholder="+91 98765 43210" value="{{ old('phone') }}">
                </div>
                <div class="ob-form-group">
                    <label class="ob-label">City / Location</label>
                    <input type="text" name="city" class="ob-input" placeholder="Chennai, India" value="{{ old('city') }}">
                </div>
            </div>

            <button type="submit" class="ob-submit">Save & Continue →</button>
            <a href="{{ route('onboarding.complete') }}" class="ob-skip">Skip this step</a>
        </form>
    </div>
</main>

<script>
const templates = {
    ecommerce:          { icon: '🛒', name: 'E-Commerce Store Template', desc: 'Complete online store layout with product categories, shop, cart, and customer support' },
    business:           { icon: '🏢', name: 'Business / Company Template', desc: 'Corporate website layout with services, divisions, team, and contact — ideal for real estate, travel, and companies' },
    doctor:             { icon: '🩺', name: 'Medical Professional Template', desc: 'Clinical layout with specializations, clinic hours, and appointment booking' },
    advocate:           { icon: '⚖️', name: 'Legal Professional Template', desc: 'Authoritative design with practice areas, case types, and consultation booking' },
    politician:         { icon: '🏛️', name: 'Public Leader Template', desc: 'Bold civic layout with manifesto, constituency info, and social channels' },
    consultant:         { icon: '📊', name: 'Business Advisor Template', desc: 'Professional layout with services, client stats, and strategy call booking' },
    entrepreneur:       { icon: '🚀', name: 'Startup Founder Template', desc: 'Dynamic layout with ventures, funding stats, and partnership CTA' },
    influencer:         { icon: '⭐', name: 'Content Creator Template', desc: 'Vibrant layout with social stats, collaboration types, and media kit' },
    software_developer: { icon: '💻', name: 'Developer Portfolio Template', desc: 'Technical layout with projects, skills, and GitHub integration' },
    designer:           { icon: '🎨', name: 'Creative Portfolio Template', desc: 'Visual-first layout with portfolio grid and client showcase' },
    educator:           { icon: '📚', name: 'Educator Profile Template', desc: 'Structured layout with courses, expertise, and student testimonials' },
    freelancer:         { icon: '🧑‍💻', name: 'Freelancer Portfolio Template', desc: 'Clean layout with services, rates, and client reviews' },
    other:              { icon: '✨', name: 'Professional Template', desc: 'Versatile layout that works for any profession' },
};

function selectProfession(value, el) {
    document.querySelectorAll('.ob-prof-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('professionInput').value = value;
    const t = templates[value] || templates['other'];
    document.getElementById('templateIcon').textContent = t.icon;
    document.getElementById('templateName').textContent = t.name;
    document.getElementById('templateDesc').textContent = t.desc;
}

// Init if already selected
const cur = document.getElementById('professionInput').value;
if (cur && templates[cur]) {
    const t = templates[cur];
    document.getElementById('templateIcon').textContent = t.icon;
    document.getElementById('templateName').textContent = t.name;
    document.getElementById('templateDesc').textContent = t.desc;
}
</script>
</body>
</html>
