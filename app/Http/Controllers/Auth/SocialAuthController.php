<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect to OAuth provider
     */
    public function redirect(string $provider)
    {
        $this->validateProvider($provider);

        // Check if credentials are configured
        if (!$this->hasCredentials($provider)) {
            return redirect()->route('login')
                ->with('error', ucfirst($provider) . ' login is not configured yet. Please use email/password to sign in.');
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Could not connect to ' . ucfirst($provider) . '. Please try again or use email/password.');
        }
    }

    /**
     * Handle OAuth callback
     */
    public function callback(string $provider)
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Authentication failed. Please try again.');
        }

        // Find or create user
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            // Create new user with visitor role
            $visitorRole = Role::where('name', 'visitor')->first();

            $user = User::create([
                'name'              => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                'email'             => $socialUser->getEmail(),
                'password'          => bcrypt(Str::random(24)),
                'role_id'           => $visitorRole?->id,
                'email_verified_at' => now(), // OAuth users are considered verified
            ]);
        }

        Auth::login($user, true);

        // Redirect based on role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isStaff()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    /**
     * Check if OAuth credentials are configured for the provider
     */
    private function hasCredentials(string $provider): bool
    {
        $clientId = config("services.{$provider}.client_id");
        return !empty($clientId)
            && $clientId !== 'your-google-client-id'
            && $clientId !== 'your-facebook-client-id'
            && $clientId !== null;
    }

    /**
     * Validate the provider is supported
     */
    private function validateProvider(string $provider): void
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404, 'OAuth provider not supported.');
        }
    }
}
