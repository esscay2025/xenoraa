<?php

namespace App\Http\Controllers\Xenoraa;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantProfileController extends Controller
{
    /**
     * Resolve tenant from request:
     * - If custom domain (e.g., gopi.blog) → find user by custom_domain
     * - If xenoraa.com/{username} → find user by username
     */
    private function resolveTenant(Request $request, string $username = null): ?User
    {
        $host = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');

        // Custom domain access (e.g., gopi.blog)
        if ($host !== $mainDomain && $host !== 'www.' . $mainDomain) {
            return User::where('custom_domain', $host)
                ->orWhere('custom_domain', 'www.' . $host)
                ->first();
        }

        // Username-based access (xenoraa.com/gopi)
        if ($username) {
            return User::where('username', $username)->first();
        }

        return null;
    }

    /**
     * Public Profile Page — xenoraa.com/gopi or gopi.blog
     */
    public function profile(Request $request, string $username = null)
    {
        $tenant = $this->resolveTenant($request, $username);

        if (!$tenant) {
            abort(404, 'Profile not found');
        }

        // Get tenant's data
        $blogPosts = DB::table('blog_posts')
            ->where('user_id', $tenant->id)
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        $portfolioItems = DB::table('portfolio_experiences')
            ->where('user_id', $tenant->id)
            ->latest()
            ->take(6)
            ->get();

        $siteSettings = DB::table('site_settings')
            ->pluck('value', 'key')
            ->toArray();

        return view('tenant.profile', compact('tenant', 'blogPosts', 'portfolioItems', 'siteSettings'));
    }

    /**
     * Tenant Blog — xenoraa.com/gopi/blog
     */
    public function blog(Request $request, string $username = null)
    {
        $tenant = $this->resolveTenant($request, $username);

        if (!$tenant) {
            abort(404);
        }

        $posts = DB::table('blog_posts')
            ->where('user_id', $tenant->id)
            ->where('status', 'published')
            ->latest()
            ->paginate(12);

        return view('tenant.blog', compact('tenant', 'posts'));
    }

    /**
     * Tenant Blog Post — xenoraa.com/gopi/blog/my-post
     */
    public function blogPost(Request $request, string $username, string $slug)
    {
        $tenant = $this->resolveTenant($request, $username);

        if (!$tenant) {
            abort(404);
        }

        $post = DB::table('blog_posts')
            ->where('user_id', $tenant->id)
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$post) {
            abort(404);
        }

        return view('tenant.blog-post', compact('tenant', 'post'));
    }

    /**
     * Tenant Shop — xenoraa.com/gopi/shop
     */
    public function shop(Request $request, string $username = null)
    {
        $tenant = $this->resolveTenant($request, $username);

        if (!$tenant) {
            abort(404);
        }

        $products = DB::table('products')
            ->where('user_id', $tenant->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('tenant.shop', compact('tenant', 'products'));
    }
}
