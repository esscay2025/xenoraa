<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    private function tenantId(): int
    {
        return auth()->id();
    }

    public function index()
    {
        $tenantId = $this->tenantId();
        $settings = SiteSetting::where('user_id', $tenantId)->pluck('value', 'key')->toArray();
        $socialLinks = SocialLink::where('user_id', $tenantId)->orderBy('sort_order')->get();
        return view('admin.settings.index', compact('settings', 'socialLinks'));
    }

    public function update(Request $request)
    {
        $tenantId = $this->tenantId();

        $keys = [
            'site_name', 'site_tagline', 'site_description',
            'contact_phone', 'contact_email', 'contact_website',
            'color_accent', 'color_bg',
            'footer_tagline', 'footer_copyright',
            'chatbot_enabled',
            'profile_title', 'profile_about', 'profile_booking_link',
            'profile_years', 'profile_clients', 'profile_projects', 'profile_revenue',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::setValueForTenant($tenantId, $key, $request->input($key));
            }
        }

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096']);
            $path = $request->file('logo')->store('logos', 'public');
            SiteSetting::setValueForTenant($tenantId, 'logo_path', Storage::url($path));
        }

        if ($request->hasFile('favicon')) {
            $request->validate(['favicon' => 'image|mimes:jpeg,png,jpg,gif,ico|max:1024']);
            $path = $request->file('favicon')->store('favicons', 'public');
            SiteSetting::setValueForTenant($tenantId, 'favicon_path', Storage::url($path));
        }

        if ($request->hasFile('profile_photo')) {
            $request->validate(['profile_photo' => 'image|mimes:jpeg,png,jpg,gif|max:4096']);
            $path = $request->file('profile_photo')->store('avatars', 'public');
            auth()->user()->update(['avatar' => $path]);
        }

        SiteSetting::clearTenantCache($tenantId);

        return redirect()->route('admin.settings.index')->with('success', 'Site settings updated successfully.');
    }

    public function updateSocial(Request $request, SocialLink $social)
    {
        if ($social->user_id !== $this->tenantId()) {
            abort(403);
        }
        $request->validate([
            'url'       => 'nullable|url|max:500',
            'is_active' => 'nullable|boolean',
        ]);
        $social->update([
            'url'       => $request->input('url'),
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        return redirect()->route('admin.settings.index')->with('success', 'Social link updated.');
    }

    public function storeSocial(Request $request)
    {
        $tenantId = $this->tenantId();
        $request->validate([
            'platform'   => 'required|string|max:100',
            'url'        => 'nullable|url|max:500',
            'icon_class' => 'nullable|string|max:100',
        ]);
        $maxOrder = SocialLink::where('user_id', $tenantId)->max('sort_order') ?? 0;
        SocialLink::create([
            'user_id'    => $tenantId,
            'platform'   => $request->platform,
            'url'        => $request->url ?? '',
            'icon_class' => $request->icon_class ?? 'fas fa-link',
            'is_active'  => true,
            'sort_order' => $maxOrder + 1,
        ]);
        return redirect()->route('admin.settings.index')->with('success', 'Social link added.');
    }

    public function destroySocial(SocialLink $social)
    {
        if ($social->user_id !== $this->tenantId()) {
            abort(403);
        }
        $social->delete();
        return redirect()->route('admin.settings.index')->with('success', 'Social link removed.');
    }

    public function changePassword(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'current_password'      => 'required|string',
            'new_password'          => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return redirect()->route('admin.settings.index', ['#change-password'])
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->with('password_error', 'The current password is incorrect.')
                ->withFragment('change-password');
        }

        $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->new_password)]);

        return redirect()->to(route('admin.settings.index') . '#change-password')
            ->with('password_success', 'Password updated successfully.');
    }
}
