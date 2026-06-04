<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\TenantBootstrapService;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show registration view — domain-aware:
     * - xenoraa.com → Xenoraa branded register page
     * - custom domain → portfolio register page
     */
    public function create(Request $request): View
    {
        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');

        if ($host === $mainDomain || $host === 'www.' . $mainDomain) {
            return view('auth.xenoraa-register');
        }

        return view('auth.register');
    }

    /**
     * Handle registration with username, plan selection, and 14-day trial.
     */
    public function store(Request $request): RedirectResponse
    {
        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');
        $isXenoraa = ($host === $mainDomain || $host === 'www.' . $mainDomain);

        // Validation rules
        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        // Extra fields for Xenoraa registration
        if ($isXenoraa) {
            $rules['username'] = [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-z0-9_-]+$/',
                'unique:users,username',
                // Reserved words
                function ($attribute, $value, $fail) {
                    $reserved = ['admin', 'staff', 'superadmin', 'api', 'auth', 'login', 'register',
                                 'logout', 'dashboard', 'profile', 'chat', 'forum', 'calendar',
                                 'shop', 'newsletter', 'chatbot', 'xenoraa', 'solutions', 'about',
                                 'blog', 'jobs', 'support', 'help', 'www', 'mail', 'ftp', 'root'];
                    if (in_array(strtolower($value), $reserved)) {
                        $fail('This username is reserved. Please choose another.');
                    }
                },
            ];
            $rules['plan'] = ['required', 'in:starter,professional,business'];
        }

        $validated = $request->validate($rules);

        // Assign role: Xenoraa signups get 'admin' (tenant owner); others get 'visitor'
        if ($isXenoraa) {
            $role = Role::where('name', 'admin')->first();
        } else {
            $role = Role::where('name', 'visitor')->first();
        }

        // Build user data
        $userData = [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $role?->id,
            'status'   => 'active',
        ];

        // Xenoraa-specific fields
        if ($isXenoraa) {
            $userData['username']      = strtolower($request->username);
            $userData['plan']          = $request->plan ?? 'starter';
            $userData['profession']    = $request->profession ?? null;
            $userData['trial_ends_at'] = now()->addDays(config('xenoraa.trial_days', 14));
        }

        $user = User::create($userData);

        // Auto-subscribe to newsletter
        NewsletterSubscriber::firstOrCreate(
            ['email' => $request->email],
            [
                'name'          => $request->name,
                'status'        => 'active',
                'token'         => Str::random(40),
                'subscribed_at' => now(),
            ]
        );

        event(new Registered($user));
        Auth::login($user);

        // Bootstrap default tenant site for Xenoraa registrations
        if ($isXenoraa) {
            try {
                $bootstrap = new TenantBootstrapService();
                $bootstrap->bootstrapNewTenant($user);
            } catch (\Throwable $e) {
                \Log::warning('TenantBootstrap failed for user ' . $user->id . ': ' . $e->getMessage());
            }
            return redirect()->route('onboarding.welcome');
        }

        return redirect()->route('home');
    }
}
