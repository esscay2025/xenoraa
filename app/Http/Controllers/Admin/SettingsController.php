<?php

namespace App\Http/Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getSettings();
        $socialLinks = SocialLink::all();
        return view('admin.settings.index', compact('settings', 'socialLinks'));
    }

    public function update(Request $request)
    {
        $keys = [
            'owner_name',
            'company_name',
            'location',
            'founded_year',
            'contact_phone',
            'contact_website',
            'hero_title',
            'hero_subtitle',
            'hero_description',
            'about_title',
            'about_text_1',
            'about_text_2',
            'footer_tagline'
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::setValue($key, $request->input($key));
            }
        }

        // Handle logo upload if any
        if ($request->hasFile('logo')) {
            $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
            $file = $request->file('logo');
            // Save directly to public/images/gopi-logo-nav.png
            $file->move(public_path('images'), 'gopi-logo-nav.png');
            
            // Re-generate square version & favicon
            try {
                $path = public_path('images/gopi-logo-nav.png');
                $logo = \Image::make($path);
                
                // Save square
                $bg_sq = \Image::canvas($logo->height(), $logo->height(), '#000000');
                $logo_resized = clone $logo;
                $bg_sq->insert($logo_resized, 'center');
                $bg_sq->save(public_path('images/gopi-logo-square.png'));
                
                // Save favicons
                $fav32 = clone $logo;
                $fav32->fit(32, 32)->save(public_path('favicon-32.png'));
                
                $fav64 = clone $logo;
                $fav64->fit(64, 64)->save(public_path('favicon-64.png'));
                
                $apple = clone $logo;
                $apple->fit(180, 180)->save(public_path('apple-touch-icon.png'));
            } catch (\Exception $e) {
                // If Intervention Image is not available, use basic GD or fallback
                shell_exec("python3.11 -c \"
from PIL import Image
logo = Image.open('" . public_path('images/gopi-logo-nav.png') . "')
bg_sq = Image.new('RGB', (logo.size[1], logo.size[1]), (0, 0, 0))
x_offset = (logo.size[1] - logo.size[0]) // 2 if logo.size[0] < logo.size[1] else 0
y_offset = (logo.size[0] - logo.size[1]) // 2 if logo.size[1] < logo.size[0] else 0
if logo.mode == 'RGBA':
    bg_sq.paste(logo, (x_offset, y_offset), mask=logo.split()[3])
else:
    bg_sq.paste(logo, (x_offset, y_offset))
bg_sq.save('" . public_path('images/gopi-logo-square.png') . "')

# Favicons
width, height = logo.size
gk_width = int(height * 1.1)
gk_crop = logo.crop((0, 0, gk_width, height))
for size in [32, 64, 180]:
    bg = Image.new('RGBA', (size, size), (0, 0, 0, 255))
    gk_resized = gk_crop.resize((size, size), Image.LANCZOS)
    if gk_resized.mode == 'RGBA':
        bg.paste(gk_resized, (0, 0), mask=gk_resized.split()[3])
    else:
        bg.paste(gk_resized, (0, 0))
    bg = bg.convert('RGB')
    if size == 180:
        bg.save('" . public_path('apple-touch-icon.png') . "')
    else:
        bg.save('" . public_path('favicon-' . size . '.png') . "')
\"");
            }
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $request->validate([
                'profile_photo' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
            ]);
            $file = $request->file('profile_photo');
            $file->move(public_path('images'), 'gopi-profile.png');
        }

        return redirect()->route('admin.settings.index')->with('success', 'Site settings updated successfully.');
    }

    public function updateSocial(Request $request, SocialLink $social)
    {
        $request->validate([
            'url' => 'required|url',
            'is_active' => 'boolean'
        ]);

        $social->update([
            'url' => $request->input('url'),
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Social link updated successfully.');
    }
}
