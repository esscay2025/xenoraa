<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * New users are automatically assigned the 'visitor' role
     * and added to the newsletter subscriber list.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Assign visitor role by default
        $visitorRole = Role::where('name', 'visitor')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $visitorRole?->id,
            'status' => 'active',
        ]);

        // Auto-subscribe new user to newsletter (if not already subscribed)
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

        return redirect()->route('home');
    }
}
