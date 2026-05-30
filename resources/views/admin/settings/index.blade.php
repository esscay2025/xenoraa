@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0;">Site Settings</h1>
        <p style="color: var(--text-secondary); margin: 0.25rem 0 0;">Manage your portfolio details, contact info, logo, and social media links.</p>
    </div>
</div>

<div class="grid-2" style="align-items: start;">
    {{-- Left Side: Main Settings Form --}}
    <div class="card">
        <h2 style="font-size: 1.2rem; font-weight: 700; margin: 0 0 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem;">General & Content Settings</h2>
        
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Owner Name</label>
                    <input type="text" name="owner_name" class="form-control" value="{{ $settings['owner_name'] ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-control" value="{{ $settings['company_name'] ?? '' }}" required>
                </div>
            </div>

            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ $settings['location'] ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Founded Year</label>
                    <input type="text" name="founded_year" class="form-control" value="{{ $settings['founded_year'] ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Phone</label>
                    <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '' }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Contact Website</label>
                <input type="text" name="contact_website" class="form-control" value="{{ $settings['contact_website'] ?? '' }}">
            </div>

            <h3 style="font-size: 1rem; font-weight: 600; margin: 2rem 0 1rem; color: var(--text-secondary);">Hero Section</h3>
            
            <div class="form-group">
                <label class="form-label">Hero Title (Use \n for new line)</label>
                <input type="text" name="hero_title" class="form-control" value="{{ $settings['hero_title'] ?? '' }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Hero Subtitle</label>
                <input type="text" name="hero_subtitle" class="form-control" value="{{ $settings['hero_subtitle'] ?? '' }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Hero Description</label>
                <textarea name="hero_description" class="form-control" rows="3" required>{{ $settings['hero_description'] ?? '' }}</textarea>
            </div>

            <h3 style="font-size: 1rem; font-weight: 600; margin: 2rem 0 1rem; color: var(--text-secondary);">About Section</h3>

            <div class="form-group">
                <label class="form-label">About Title</label>
                <input type="text" name="about_title" class="form-control" value="{{ $settings['about_title'] ?? '' }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">About Paragraph 1</label>
                <textarea name="about_text_1" class="form-control" rows="4" required>{{ $settings['about_text_1'] ?? '' }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">About Paragraph 2</label>
                <textarea name="about_text_2" class="form-control" rows="4" required>{{ $settings['about_text_2'] ?? '' }}</textarea>
            </div>

            <h3 style="font-size: 1rem; font-weight: 600; margin: 2rem 0 1rem; color: var(--text-secondary);">Footer Section</h3>

            <div class="form-group">
                <label class="form-label">Footer Tagline</label>
                <textarea name="footer_tagline" class="form-control" rows="2" required>{{ $settings['footer_tagline'] ?? '' }}</textarea>
            </div>

            <h3 style="font-size: 1rem; font-weight: 600; margin: 2rem 0 1rem; color: var(--text-secondary);">Media Assets</h3>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Upload New Logo (White PNG with transparent bg recommended)</label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    <div style="margin-top: 0.5rem; background: #000; padding: 0.5rem; border-radius: 6px; display: inline-block;">
                        <span class="text-xs text-muted">Current Logo:</span><br>
                        <img src="{{ asset('images/gopi-logo-nav.png') }}?v={{ time() }}" alt="Current Logo" style="height: 30px; margin-top: 0.25rem;">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Upload New Profile Photo</label>
                    <input type="file" name="profile_photo" class="form-control" accept="image/*">
                    <div style="margin-top: 0.5rem; display: inline-block;">
                        <span class="text-xs text-muted">Current Photo:</span><br>
                        <img src="{{ asset('images/gopi-profile.png') }}?v={{ time() }}" alt="Current Profile" style="height: 50px; width: 50px; border-radius: 50%; object-fit: cover; margin-top: 0.25rem;">
                    </div>
                </div>
            </div>

            <div style="margin-top: 2rem; border-top: 1px solid var(--border); padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Save All Settings</button>
            </div>
        </form>
    </div>

    {{-- Right Side: Social Media Links --}}
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <h2 style="font-size: 1.2rem; font-weight: 700; margin: 0 0 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem;">Social Media & Channels</h2>
            
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @foreach($socialLinks as $social)
                <form method="POST" action="{{ route('admin.settings.social.update', $social->id) }}" style="border-bottom: 1px solid var(--border-light); padding-bottom: 1rem; margin-bottom: 1rem;">
                    @csrf
                    @method('PUT')
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                            <i class="{{ $social->icon_class }}" style="font-size: 1.1rem; width: 20px;"></i>
                            {{ ucfirst($social->platform) }}
                        </div>
                        <label class="flex items-center gap-2" style="cursor: pointer; font-size: 0.85rem;">
                            <input type="checkbox" name="is_active" value="1" {{ $social->is_active ? 'checked' : '' }} onchange="this.form.submit()">
                            Active
                        </label>
                    </div>

                    <div style="display: flex; gap: 0.5rem;">
                        <input type="url" name="url" class="form-control" value="{{ $social->url }}" required placeholder="https://...">
                        <button type="submit" class="btn btn-outline btn-sm" style="flex-shrink: 0;">Update</button>
                    </div>
                </form>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
