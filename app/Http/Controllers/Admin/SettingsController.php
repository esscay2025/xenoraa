<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getSettings();
        $socialLinks = SocialLink::orderBy('order')->get();
        return view('admin.settings.index', compact('settings', 'socialLinks'));
    }

    public function update(Request $request)
    {
        $keys = [
            'owner_name',
            'company_name',
            'tagline',
            'location',
            'founded_year',
            'contact_phone',
            'contact_email',
            'contact_website',
            'hero_title',
            'hero_subtitle',
            'hero_description',
            'about_title',
            'about_text_1',
            'about_text_2',
            'footer_tagline',
            'skills',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::setValue($key, $request->input($key));
            }
        }

        // Handle logo upload using PIL (no Intervention Image needed)
        if ($request->hasFile('logo')) {
            $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ]);

            $file = $request->file('logo');
            $tmpPath = $file->getPathname();
            $destPath = public_path('images/gopi-logo-nav.png');

            // Use Python PIL to process the logo
            $pythonScript = <<<PYTHON
import sys
from PIL import Image

src = sys.argv[1]
dest_nav = sys.argv[2]
pub_dir = sys.argv[3]

img = Image.open(src).convert('RGBA')
img.save(dest_nav)

# Create square version (pad to square with transparent bg)
w, h = img.size
size = max(w, h)
sq = Image.new('RGBA', (size, size), (0, 0, 0, 0))
sq.paste(img, ((size - w) // 2, (size - h) // 2), img)
sq.save(pub_dir + '/images/gopi-logo-square.png')

# Favicon sizes
for px in [32, 64, 180]:
    fav = sq.resize((px, px), Image.LANCZOS)
    if px == 180:
        fav.save(pub_dir + '/apple-touch-icon.png')
    else:
        fav.save(pub_dir + '/favicon-' + str(px) + '.png')

print('OK')
PYTHON;

            $scriptFile = storage_path('app/process_logo.py');
            file_put_contents($scriptFile, $pythonScript);

            $result = shell_exec("python3.11 " . escapeshellarg($scriptFile) . " " .
                escapeshellarg($tmpPath) . " " .
                escapeshellarg($destPath) . " " .
                escapeshellarg(public_path()) . " 2>&1");

            if (trim($result) !== 'OK') {
                // Fallback: just copy the file as-is
                copy($tmpPath, $destPath);
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
            'url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $social->update([
            'url'       => $request->input('url'),
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Social link updated successfully.');
    }
}
