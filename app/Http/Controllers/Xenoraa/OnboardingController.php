<?php

namespace App\Http\Controllers\Xenoraa;

use App\Http\Controllers\Controller;
use App\Mail\XenoraaWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OnboardingController extends Controller
{
    // Profession → template mapping
    protected $templateMap = [
        'doctor'             => 'doctor',
        'advocate'           => 'advocate',
        'politician'         => 'politician',
        'consultant'         => 'consultant',
        'entrepreneur'       => 'entrepreneur',
        'influencer'         => 'influencer',
        'software_developer' => 'default',
        'designer'           => 'default',
        'educator'           => 'default',
        'freelancer'         => 'default',
        'other'              => 'default',
    ];

    /**
     * Welcome screen shown after registration (step 1).
     */
    public function welcome()
    {
        $user = Auth::user();
        if ($user->onboarding_completed) {
            return redirect()->route('dashboard');
        }
        return view('xenoraa.onboarding.welcome', compact('user'));
    }

    /**
     * Profile setup step (step 2) — profession-aware form.
     */
    public function profile()
    {
        $user = Auth::user();
        if ($user->onboarding_completed) {
            return redirect()->route('dashboard');
        }
        return view('xenoraa.onboarding.profile', compact('user'));
    }

    /**
     * Save profile setup and apply profession template.
     */
    public function saveProfile(Request $request)
    {
        $request->validate([
            'site_title' => 'required|string|max:100',
            'tagline'    => 'nullable|string|max:200',
            'bio'        => 'nullable|string|max:1000',
            'phone'      => 'nullable|string|max:20',
            'city'       => 'nullable|string|max:100',
            'profession' => 'nullable|string|max:50',
        ]);

        $user       = Auth::user();
        $profession = $request->profession ?? $user->profession ?? 'other';
        $template   = $this->templateMap[$profession] ?? 'default';

        // Update user record
        $user->update([
            'profession'       => $profession,
            'profile_template' => $template,
        ]);

        // Update site settings (shared table — update first record or create)
        try {
            \App\Models\SiteSetting::updateOrCreate(
                [],
                [
                    'site_name'   => $request->site_title,
                    'tagline'     => $request->tagline,
                    'description' => $request->bio,
                    'phone'       => $request->phone,
                ]
            );
        } catch (\Exception $e) {
            // Continue silently if site settings update fails
        }

        return redirect()->route('onboarding.complete');
    }

    /**
     * Completion step (step 3) — marks onboarding done.
     */
    public function complete()
    {
        $user = Auth::user();

        // Mark onboarding as completed
        try {
            $user->update(['onboarding_completed' => true]);
        } catch (\Exception $e) {
            // Column may not exist yet — silently continue
        }

        return view('xenoraa.onboarding.complete', compact('user'));
    }

    /**
     * API: Check username availability (AJAX).
     */
    public function checkUsername(Request $request)
    {
        $username = strtolower(trim($request->get('username', '')));

        if (strlen($username) < 3) {
            return response()->json(['available' => false, 'reason' => 'too_short', 'message' => 'Username must be at least 3 characters.']);
        }

        if (!preg_match('/^[a-z0-9_\-]+$/', $username)) {
            return response()->json(['available' => false, 'reason' => 'invalid', 'message' => 'Only letters, numbers, hyphens, and underscores allowed.']);
        }

        $reserved = ['admin', 'staff', 'superadmin', 'api', 'auth', 'login', 'register',
                     'logout', 'dashboard', 'profile', 'chat', 'forum', 'calendar',
                     'shop', 'newsletter', 'chatbot', 'xenoraa', 'solutions', 'about',
                     'blog', 'jobs', 'support', 'help', 'www', 'mail', 'ftp', 'root',
                     'billing', 'payment', 'pricing', 'features', 'showcase', 'get-started'];

        if (in_array($username, $reserved)) {
            return response()->json(['available' => false, 'reason' => 'reserved', 'message' => 'This username is reserved.']);
        }

        $exists = \App\Models\User::where('username', $username)->exists();
        return response()->json([
            'available' => !$exists,
            'message'   => $exists ? 'Username is already taken.' : 'Username is available!',
        ]);
    }
}
