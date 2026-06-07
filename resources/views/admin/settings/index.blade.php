@extends('layouts.admin')
@section('title', 'Site Settings')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
    <div>
        <h1 style="font-size:1.75rem;font-weight:800;margin:0;">Site Settings</h1>
        <p style="color:var(--text-secondary);margin:0.25rem 0 0;">Configure your site identity, branding, footer, and social channels.</p>
    </div>
</div>

{{-- Tab Navigation --}}
<div style="display:flex;gap:0.5rem;border-bottom:1px solid var(--border);margin-bottom:2rem;overflow-x:auto;">
    <button class="settings-tab active" data-tab="branding">
        <i class="fas fa-palette"></i> Branding
    </button>
    <button class="settings-tab" data-tab="footer">
        <i class="fas fa-shoe-prints"></i> Footer
    </button>
    <button class="settings-tab" data-tab="social">
        <i class="fas fa-share-alt"></i> Social Media & Channels
    </button>
    <button class="settings-tab" data-tab="contact">
        <i class="fas fa-address-card"></i> Contact Info
    </button>
    <button class="settings-tab" data-tab="advanced">
        <i class="fas fa-sliders-h"></i> Advanced
    </button>
    <button class="settings-tab" data-tab="change-password">
        <i class="fas fa-lock"></i> Change Password
    </button>
    <button class="settings-tab" data-tab="subscription">
        <i class="fas fa-credit-card"></i> Subscription
    </button>
</div>

{{-- ── BRANDING TAB ── --}}
<div class="settings-panel active" id="tab-branding">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid-2" style="align-items:start;gap:2rem;">
            <div class="card">
                <h3 style="font-size:1rem;font-weight:700;margin:0 0 1.25rem;border-bottom:1px solid var(--border);padding-bottom:0.75rem;">Site Identity</h3>
                <div class="form-group">
                    <label class="form-label">Site Name <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? auth()->user()->name }}" required placeholder="e.g. Gopi K | Portfolio">
                </div>
                <div class="form-group">
                    <label class="form-label">Tagline</label>
                    <input type="text" name="site_tagline" class="form-control" value="{{ $settings['site_tagline'] ?? '' }}" placeholder="e.g. Consultant & Entrepreneur">
                </div>
                <div class="form-group">
                    <label class="form-label">Site Description (for SEO)</label>
                    <textarea name="site_description" class="form-control" rows="3" placeholder="Brief description of your site for search engines...">{{ $settings['site_description'] ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Accent Color</label>
                    <div style="display:flex;gap:0.75rem;align-items:center;">
                        <input type="color" name="color_accent" value="{{ $settings['color_accent'] ?? '#6366f1' }}" style="width:48px;height:36px;border:1px solid var(--border);border-radius:6px;background:none;cursor:pointer;padding:2px;">
                        <input type="text" name="color_accent_text" class="form-control" value="{{ $settings['color_accent'] ?? '#6366f1' }}" placeholder="#6366f1" style="flex:1;" oninput="document.querySelector('[name=color_accent]').value=this.value">
                    </div>
                </div>
            </div>
            <div class="card">
                <h3 style="font-size:1rem;font-weight:700;margin:0 0 1.25rem;border-bottom:1px solid var(--border);padding-bottom:0.75rem;">Logo & Favicon</h3>
                <div class="form-group">
                    <label class="form-label">Current Logo</label>
                    @if(!empty($settings['logo_path']))
                        <div style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;padding:1rem;margin-bottom:0.75rem;display:flex;align-items:center;justify-content:center;">
                            <img src="{{ $settings['logo_path'] }}" alt="Logo" style="max-height:60px;max-width:200px;">
                        </div>
                    @else
                        <p class="text-muted text-sm" style="margin-bottom:0.75rem;">No custom logo uploaded. Using default.</p>
                    @endif
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    <p class="text-muted text-xs" style="margin-top:0.3rem;">PNG with transparent background recommended. Max 4MB.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Favicon</label>
                    @if(!empty($settings['favicon_path']))
                        <div style="margin-bottom:0.75rem;">
                            <img src="{{ $settings['favicon_path'] }}" alt="Favicon" style="width:32px;height:32px;border:1px solid var(--border);border-radius:4px;">
                        </div>
                    @endif
                    <input type="file" name="favicon" class="form-control" accept="image/*,.ico">
                    <p class="text-muted text-xs" style="margin-top:0.3rem;">ICO or PNG, 32x32 or 64x64 px. Max 1MB.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Profile Photo</label>
                    @if(auth()->user()->avatar)
                        <div style="margin-bottom:0.75rem;">
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile" style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid var(--border);">
                        </div>
                    @endif
                    <input type="file" name="profile_photo" class="form-control" accept="image/*">
                </div>
            </div>
        </div>
        <div style="margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Branding Settings</button>
        </div>
    </form>
</div>

{{-- ── FOOTER TAB ── --}}
<div class="settings-panel" id="tab-footer">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid-2" style="align-items:start;gap:2rem;">
            <div class="card">
                <h3 style="font-size:1rem;font-weight:700;margin:0 0 1.25rem;border-bottom:1px solid var(--border);padding-bottom:0.75rem;">Footer Content</h3>
                <div class="form-group">
                    <label class="form-label">Footer Tagline</label>
                    <textarea name="footer_tagline" class="form-control" rows="3" placeholder="A short description shown in the footer...">{{ $settings['footer_tagline'] ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Copyright Text</label>
                    <input type="text" name="footer_copyright" class="form-control" value="{{ $settings['footer_copyright'] ?? '' }}" placeholder="e.g. © 2025 Gopi K. All rights reserved.">
                    <p class="text-muted text-xs" style="margin-top:0.3rem;">Leave blank to use the auto-generated copyright with your site name.</p>
                </div>
            </div>
            <div class="card">
                <h3 style="font-size:1rem;font-weight:700;margin:0 0 1.25rem;border-bottom:1px solid var(--border);padding-bottom:0.75rem;">Contact Details (Footer)</h3>
                <div class="form-group">
                    <label class="form-label">Contact Email</label>
                    <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? '' }}" placeholder="hello@example.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Phone</label>
                    <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '' }}" placeholder="+91 98765 43210">
                </div>
                <div class="form-group">
                    <label class="form-label">Website URL</label>
                    <input type="url" name="contact_website" class="form-control" value="{{ $settings['contact_website'] ?? '' }}" placeholder="https://yoursite.com">
                </div>
            </div>
        </div>
        <div style="margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Footer Settings</button>
        </div>
    </form>
</div>

{{-- ── SOCIAL MEDIA TAB ── --}}
<div class="settings-panel" id="tab-social">
    <div class="card" style="margin-bottom:1.5rem;">
        <h3 style="font-size:1rem;font-weight:700;margin:0 0 1.25rem;border-bottom:1px solid var(--border);padding-bottom:0.75rem;">Social Media & Channels</h3>
        <p class="text-secondary text-sm" style="margin-bottom:1.5rem;">These links appear in your site footer and profile. Make sure to include the full URL.</p>

        @if($socialLinks->isEmpty())
            <div style="text-align:center;padding:2rem;color:var(--text-muted);">
                <i class="fas fa-share-alt" style="font-size:2rem;margin-bottom:0.75rem;display:block;"></i>
                <p>No social links configured yet. Add your first one below.</p>
            </div>
        @else
        <div style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1.5rem;">
            @foreach($socialLinks as $social)
            <form method="POST" action="{{ route('admin.settings.social.update', $social) }}" style="display:flex;gap:0.75rem;align-items:center;">
                @csrf @method('PATCH')
                <div style="width:40px;height:40px;background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="{{ $social->icon_class }}" style="color:var(--text-secondary);"></i>
                </div>
                <div style="flex:0 0 130px;">
                    <span style="font-size:0.875rem;font-weight:600;color:var(--text-primary);">{{ ucfirst($social->platform) }}</span>
                </div>
                <input type="url" name="url" class="form-control" value="{{ $social->url }}" placeholder="https://..." style="flex:1;">
                <label style="display:flex;align-items:center;gap:0.4rem;white-space:nowrap;cursor:pointer;font-size:0.875rem;color:var(--text-secondary);">
                    <input type="checkbox" name="is_active" {{ $social->is_active ? 'checked' : '' }} style="width:16px;height:16px;"> Active
                </label>
                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                <a href="{{ route('admin.settings.social.destroy', $social) }}"
                   onclick="return confirm('Remove this social link?')"
                   class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#fca5a5;border:1px solid rgba(239,68,68,0.3);">
                    <i class="fas fa-trash"></i>
                </a>
            </form>
            @endforeach
        </div>
        @endif

        {{-- Add new social link --}}
        <div style="border-top:1px solid var(--border);padding-top:1.25rem;">
            <h4 style="font-size:0.875rem;font-weight:600;margin:0 0 1rem;color:var(--text-secondary);">Add New Social Channel</h4>
            <form method="POST" action="{{ route('admin.settings.social.store') }}" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:0.75rem;align-items:end;">
                @csrf
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Platform</label>
                    <select name="platform" class="form-control" onchange="updateIcon(this)">
                        <option value="linkedin">LinkedIn</option>
                        <option value="twitter">Twitter / X</option>
                        <option value="instagram">Instagram</option>
                        <option value="facebook">Facebook</option>
                        <option value="youtube">YouTube</option>
                        <option value="github">GitHub</option>
                        <option value="tiktok">TikTok</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="telegram">Telegram</option>
                        <option value="website">Website</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">URL</label>
                    <input type="url" name="url" class="form-control" placeholder="https://...">
                </div>
                <input type="hidden" name="icon_class" id="iconClassInput" value="fab fa-linkedin">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Icon Preview</label>
                    <div style="height:40px;background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;display:flex;align-items:center;padding:0 0.75rem;gap:0.5rem;font-size:0.875rem;color:var(--text-secondary);">
                        <i id="iconPreview" class="fab fa-linkedin"></i> <span id="iconLabel">LinkedIn</span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="height:40px;"><i class="fas fa-plus"></i> Add</button>
            </form>
        </div>
    </div>
</div>

{{-- ── CONTACT TAB ── --}}
<div class="settings-panel" id="tab-contact">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <h3 style="font-size:1rem;font-weight:700;margin:0 0 1.25rem;border-bottom:1px solid var(--border);padding-bottom:0.75rem;">Contact & Profile Details</h3>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Contact Email</label>
                    <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? '' }}" placeholder="hello@example.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Phone</label>
                    <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '' }}" placeholder="+91 98765 43210">
                </div>
                <div class="form-group">
                    <label class="form-label">Website</label>
                    <input type="url" name="contact_website" class="form-control" value="{{ $settings['contact_website'] ?? '' }}" placeholder="https://yoursite.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Booking / Calendar Link</label>
                    <input type="url" name="profile_booking_link" class="form-control" value="{{ $settings['profile_booking_link'] ?? '' }}" placeholder="https://calendly.com/...">
                </div>
            </div>
            <h4 style="font-size:0.875rem;font-weight:600;margin:1.5rem 0 1rem;color:var(--text-secondary);">Profile Stats (shown on homepage)</h4>
            <div class="grid-4">
                <div class="form-group">
                    <label class="form-label">Years Experience</label>
                    <input type="text" name="profile_years" class="form-control" value="{{ $settings['profile_years'] ?? '' }}" placeholder="10+">
                </div>
                <div class="form-group">
                    <label class="form-label">Clients Served</label>
                    <input type="text" name="profile_clients" class="form-control" value="{{ $settings['profile_clients'] ?? '' }}" placeholder="500+">
                </div>
                <div class="form-group">
                    <label class="form-label">Projects Completed</label>
                    <input type="text" name="profile_projects" class="form-control" value="{{ $settings['profile_projects'] ?? '' }}" placeholder="200+">
                </div>
                <div class="form-group">
                    <label class="form-label">Revenue Generated</label>
                    <input type="text" name="profile_revenue" class="form-control" value="{{ $settings['profile_revenue'] ?? '' }}" placeholder="₹10Cr+">
                </div>
            </div>
        </div>
        <div style="margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Contact Settings</button>
        </div>
    </form>
</div>

{{-- ── CHANGE PASSWORD TAB ── --}}
<div class="settings-panel" id="tab-change-password">
    <div class="card" style="max-width:520px;">
        <h3 style="font-size:1rem;font-weight:700;margin:0 0 1.25rem;border-bottom:1px solid var(--border);padding-bottom:0.75rem;"><i class="fas fa-lock" style="margin-right:0.5rem;"></i>Change Password</h3>
        @if(session('password_success'))
            <div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);color:#6ee7b7;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;font-size:0.875rem;">
                <i class="fas fa-check-circle"></i> {{ session('password_success') }}
            </div>
        @endif
        @if(session('password_error'))
            <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;font-size:0.875rem;">
                <i class="fas fa-exclamation-circle"></i> {{ session('password_error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('admin.settings.change-password') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Current Password <span style="color:var(--danger)">*</span></label>
                <input type="password" name="current_password" class="form-control" required placeholder="Enter your current password">
                @error('current_password') <p style="color:#fca5a5;font-size:0.8rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">New Password <span style="color:var(--danger)">*</span></label>
                <input type="password" name="new_password" class="form-control" required placeholder="Minimum 8 characters" minlength="8">
                @error('new_password') <p style="color:#fca5a5;font-size:0.8rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirm New Password <span style="color:var(--danger)">*</span></label>
                <input type="password" name="new_password_confirmation" class="form-control" required placeholder="Repeat new password">
            </div>
            <div style="margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-lock"></i> Update Password</button>
            </div>
        </form>
    </div>
</div>

{{-- ── SUBSCRIPTION TAB ── --}}
<div class="settings-panel" id="tab-subscription">
    @php
        $user = auth()->user();
        $planColors = ['starter'=>'#3b82f6','professional'=>'#8b5cf6','business'=>'#f59e0b'];
        $planColor  = $planColors[$user->plan] ?? '#6366f1';
        $planExpiry = $user->plan_expires_at ? \Carbon\Carbon::parse($user->plan_expires_at) : null;
        $daysLeft   = $planExpiry ? now()->diffInDays($planExpiry, false) : null;
        $planFeatures = [
            'starter'      => ['1 Professional Site','5 Pages','AI Chatbot','Blog Module','Basic Analytics','1 GB Storage'],
            'professional' => ['1 Professional Site','Unlimited Pages','AI Chatbot','Blog + Shop','Advanced Analytics','Custom Domain','5 GB Storage','Priority Support'],
            'business'     => ['1 Professional Site','Unlimited Pages','AI Chatbot','All Modules','Full Analytics','Custom Domain','20 GB Storage','Dedicated Support','White-label Option'],
        ];
        $features = $planFeatures[$user->plan] ?? $planFeatures['starter'];
    @endphp
    <div style="max-width:640px;">
        {{-- Plan Card --}}
        <div class="card" style="border-top:3px solid {{ $planColor }};margin-bottom:1.5rem;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;">
                <div>
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem;">
                        <span style="background:{{ $planColor }};color:#fff;font-size:0.7rem;font-weight:700;padding:0.25rem 0.75rem;border-radius:20px;text-transform:uppercase;letter-spacing:0.05em;">{{ $user->plan }}</span>
                        @if($user->status === 'active')
                            <span style="background:rgba(16,185,129,0.1);color:#6ee7b7;border:1px solid rgba(16,185,129,0.3);font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:20px;">Active</span>
                        @else
                            <span style="background:rgba(239,68,68,0.1);color:#fca5a5;border:1px solid rgba(239,68,68,0.3);font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:20px;">{{ ucfirst($user->status) }}</span>
                        @endif
                    </div>
                    <h2 style="font-size:1.5rem;font-weight:800;margin:0 0 0.25rem;text-transform:capitalize;">{{ ucfirst($user->plan) }} Plan</h2>
                    <p style="color:var(--text-secondary);font-size:0.875rem;margin:0;">Your current subscription</p>
                </div>
                <div style="text-align:right;">
                    @if($planExpiry)
                        <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:0.25rem;">Expires</div>
                        <div style="font-size:1rem;font-weight:700;color:{{ $daysLeft !== null && $daysLeft < 30 ? '#f59e0b' : 'var(--text-primary)' }};">{{ $planExpiry->format('d M Y') }}</div>
                        @if($daysLeft !== null)
                            <div style="font-size:0.8rem;color:{{ $daysLeft < 30 ? '#f59e0b' : 'var(--text-muted)' }};margin-top:0.2rem;">
                                @if($daysLeft > 0) {{ $daysLeft }} days remaining @elseif($daysLeft == 0) Expires today @else Expired {{ abs($daysLeft) }} days ago @endif
                            </div>
                        @endif
                    @else
                        <div style="font-size:0.875rem;color:var(--text-muted);">No expiry set</div>
                    @endif
                </div>
            </div>

            {{-- Progress bar for days remaining --}}
            @if($planExpiry && $daysLeft !== null && $daysLeft > 0)
                @php $totalDays = 365; $pct = min(100, round($daysLeft / $totalDays * 100)); @endphp
                <div style="margin-top:1.25rem;">
                    <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:var(--text-muted);margin-bottom:0.4rem;">
                        <span>Subscription period</span><span>{{ $pct }}% remaining</span>
                    </div>
                    <div style="background:var(--bg-secondary);border-radius:99px;height:6px;overflow:hidden;">
                        <div style="width:{{ $pct }}%;height:100%;background:{{ $planColor }};border-radius:99px;transition:width 0.5s;"></div>
                    </div>
                </div>
            @endif

            {{-- Features --}}
            <div style="margin-top:1.5rem;border-top:1px solid var(--border);padding-top:1.25rem;">
                <h4 style="font-size:0.8rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.75rem;">Included Features</h4>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.4rem;">
                    @foreach($features as $feat)
                        <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.8rem;color:var(--text-secondary);">
                            <i class="fas fa-check-circle" style="color:{{ $planColor }};font-size:0.75rem;"></i> {{ $feat }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="card">
            <h3 style="font-size:1rem;font-weight:700;margin:0 0 1.25rem;border-bottom:1px solid var(--border);padding-bottom:0.75rem;"><i class="fas fa-user-circle" style="margin-right:0.5rem;"></i>Account Details</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">Username</div>
                    <div style="font-size:0.9rem;font-weight:600;">@{{ $user->username ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">Email</div>
                    <div style="font-size:0.9rem;font-weight:600;">{{ $user->email }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">Profession</div>
                    <div style="font-size:0.9rem;font-weight:600;text-transform:capitalize;">{{ $user->profession ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">Member Since</div>
                    <div style="font-size:0.9rem;font-weight:600;">{{ $user->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>

        <div style="margin-top:1.5rem;padding:1rem;background:rgba(99,102,241,0.05);border:1px solid rgba(99,102,241,0.2);border-radius:10px;">
            <p style="margin:0;font-size:0.875rem;color:var(--text-secondary);"><i class="fas fa-info-circle" style="color:#6366f1;margin-right:0.5rem;"></i>To upgrade your plan or renew your subscription, please contact your account manager or reach out to <a href="mailto:support@xenoraa.com" style="color:#6366f1;">support@xenoraa.com</a>.</p>
        </div>
    </div>
</div>

{{-- ── ADVANCED TAB ── --}}
<div class="settings-panel" id="tab-advanced">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <h3 style="font-size:1rem;font-weight:700;margin:0 0 1.25rem;border-bottom:1px solid var(--border);padding-bottom:0.75rem;">Advanced Settings</h3>
            <div class="form-group">
                <label class="form-label">AI Chatbot</label>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                        <input type="checkbox" name="chatbot_enabled" value="1" {{ ($settings['chatbot_enabled'] ?? '1') == '1' ? 'checked' : '' }} style="width:18px;height:18px;">
                        <span class="text-sm">Enable AI Chatbot on your site</span>
                    </label>
                </div>
            </div>
        </div>
        <div style="margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Advanced Settings</button>
        </div>
    </form>
</div>

<style>
.settings-tab { background:none; border:none; border-bottom:2px solid transparent; padding:0.75rem 1.25rem; font-size:0.875rem; font-weight:600; color:var(--text-secondary); cursor:pointer; white-space:nowrap; display:inline-flex;align-items:center;gap:0.4rem;transition:all 0.2s; }
.settings-tab:hover { color:var(--text-primary); }
.settings-tab.active { color:var(--text-primary); border-bottom-color:var(--text-primary); }
.settings-panel { display:none; }
.settings-panel.active { display:block; }
</style>
<script>
document.querySelectorAll('.settings-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('tab-' + this.dataset.tab).classList.add('active');
    });
});

const platformIcons = {
    linkedin: { icon: 'fab fa-linkedin', label: 'LinkedIn' },
    twitter: { icon: 'fab fa-x-twitter', label: 'Twitter / X' },
    instagram: { icon: 'fab fa-instagram', label: 'Instagram' },
    facebook: { icon: 'fab fa-facebook', label: 'Facebook' },
    youtube: { icon: 'fab fa-youtube', label: 'YouTube' },
    github: { icon: 'fab fa-github', label: 'GitHub' },
    tiktok: { icon: 'fab fa-tiktok', label: 'TikTok' },
    whatsapp: { icon: 'fab fa-whatsapp', label: 'WhatsApp' },
    telegram: { icon: 'fab fa-telegram', label: 'Telegram' },
    website: { icon: 'fas fa-globe', label: 'Website' },
    other: { icon: 'fas fa-link', label: 'Other' },
};
function updateIcon(select) {
    const data = platformIcons[select.value] || platformIcons.other;
    document.getElementById('iconClassInput').value = data.icon;
    document.getElementById('iconPreview').className = data.icon;
    document.getElementById('iconLabel').textContent = data.label;
}

// Hash-based tab switching
const hash = window.location.hash.replace('#', '');
if (hash && document.querySelector('[data-tab="' + hash + '"]')) {
    document.querySelector('[data-tab="' + hash + '"]').click();
}
</script>
@endsection
