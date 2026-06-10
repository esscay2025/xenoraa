<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     * Used as a fallback by some Breeze auth controllers.
     * The AuthenticatedSessionController and RedirectIfAuthenticated
     * middleware override this with role-aware logic.
     *
     * @var string
     */
    public const HOME = '/admin/dashboard';

    /**
     * Return the correct post-login URL for the given user.
     * This is the single source of truth for all auth redirects.
     */
    public static function homeForUser(?\App\Models\User $user): string
    {
        if (!$user) return '/login';

        // Super admin
        if ($user->isSuperAdmin()) {
            return '/xenoraa/dashboard';
        }

        // Tenant admin — use custom domain if set
        if ($user->isAdmin()) {
            $customDomain = $user->custom_domain ?? null;
            if ($customDomain) {
                return 'https://' . $customDomain . '/admin/dashboard';
            }
            return '/admin/dashboard';
        }

        // Staff
        if (method_exists($user, 'isStaff') && $user->isStaff()) {
            return '/admin/dashboard'; // Staff uses admin panel with module-gated sidebar
        }

        // Regular visitor / sub-user
        return '/dashboard';
    }

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
