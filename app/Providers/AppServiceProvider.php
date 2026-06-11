<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Key behaviour: when a request arrives on a custom tenant domain (e.g. gopi.blog),
     * we force Laravel's URL generator to use that domain so that route() helpers,
     * redirect()->route(), etc. all produce URLs on the correct domain.
     *
     * This means gopi.blog/admin/dashboard, gopi.blog/about, etc. all work
     * seamlessly without any hardcoded domain logic in controllers or views.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Custom domain URL forcing — runs on every request
        $this->app->booted(function () {
            $request = request();
            if (!$request) {
                return;
            }

            $host      = $request->getHost();
            $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');

            // Only apply custom domain logic when NOT on the main xenoraa.com domain
            if ($host && $host !== $mainDomain && $host !== 'www.' . $mainDomain) {
                // Normalise: strip leading www. for DB lookup
                $bareHost = preg_replace('/^www\./', '', $host);

                try {
                // Check if this host belongs to a tenant (match bare or www variant)
                $tenant = User::where('custom_domain', $host)
                    ->orWhere('custom_domain', 'www.' . $bareHost)
                    ->orWhere('custom_domain', $bareHost)
                    ->first();

                if ($tenant) {
                    // Force all route() and url() calls to use this custom domain
                    URL::forceRootUrl('https://' . $host);
                }
                } catch (\Exception $e) {
                    // Table may not exist yet (e.g., during migrate:fresh)
                }
            }
        });
    }
}
