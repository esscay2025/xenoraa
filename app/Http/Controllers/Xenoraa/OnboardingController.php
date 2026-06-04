<?php

namespace App\Http\Controllers\Xenoraa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * Welcome screen shown after registration.
     */
    public function welcome()
    {
        $user = Auth::user();
        return view('xenoraa.onboarding.welcome', compact('user'));
    }

    /**
     * Profile setup step.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('xenoraa.onboarding.profile', compact('user'));
    }

    /**
     * Save profile setup.
     */
    public function saveProfile(Request $request)
    {
        $request->validate([
            'profession' => ['nullable', 'string', 'max:100'],
            'site_title' => ['nullable', 'string', 'max:100'],
            'bio'        => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        $user->update([
            'profession' => $request->profession,
            'site_title' => $request->site_title ?? $user->name,
            'bio'        => $request->bio,
        ]);

        return redirect()->route('onboarding.complete');
    }

    /**
     * Onboarding complete screen.
     */
    public function complete()
    {
        $user = Auth::user();
        return view('xenoraa.onboarding.complete', compact('user'));
    }

    /**
     * API: Check username availability.
     */
    public function checkUsername(Request $request)
    {
        $username = strtolower($request->get('username', ''));

        if (strlen($username) < 3) {
            return response()->json(['available' => false, 'reason' => 'too_short']);
        }

        $reserved = ['admin', 'staff', 'superadmin', 'api', 'auth', 'login', 'register',
                     'logout', 'dashboard', 'profile', 'chat', 'forum', 'calendar',
                     'shop', 'newsletter', 'chatbot', 'xenoraa', 'solutions', 'about',
                     'blog', 'jobs', 'support', 'help', 'www', 'mail', 'ftp', 'root'];

        if (in_array($username, $reserved)) {
            return response()->json(['available' => false, 'reason' => 'reserved']);
        }

        $exists = \App\Models\User::where('username', $username)->exists();
        return response()->json(['available' => !$exists]);
    }
}
