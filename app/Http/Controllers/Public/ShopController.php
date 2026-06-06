<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Resolve the current tenant from the HTTP host or username.
     */
    protected function resolveTenant(Request $request, ?string $username = null): ?User
    {
        $host       = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');

        // Custom domain (e.g. gopi.blog)
        if ($host !== $mainDomain && $host !== 'www.' . $mainDomain) {
            $tenant = User::where('custom_domain', $host)
                ->orWhere('custom_domain', 'www.' . $host)
                ->first();
            if ($tenant) return $tenant;
        }

        // Username-based route (xenoraa.com/priya/shop)
        if ($username) {
            return User::where('username', $username)->first();
        }

        return null;
    }

    /**
     * Shop for custom-domain tenants: gopi.blog/shop
     */
    public function index(Request $request)
    {
        $tenant   = $this->resolveTenant($request);
        $tenantId = $tenant?->id;
        return $this->renderShop($request, $tenantId, $tenant);
    }

    /**
     * Tenant-specific shop: xenoraa.com/{username}/shop
     */
    public function tenantIndex(Request $request, string $username)
    {
        $tenant   = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;
        return $this->renderShop($request, $tenantId, $tenant);
    }

    /**
     * Shared shop rendering logic — always scoped to a tenant.
     */
    protected function renderShop(Request $request, ?int $tenantId, ?User $tenant)
    {
        $catQuery = ProductCategory::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true);
        if ($tenantId) {
            $catQuery->where('user_id', $tenantId);
        }
        $categories = $catQuery
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        $query = Product::with('category')->where('is_active', true);
        if ($tenantId) {
            $query->where('user_id', $tenantId);
        }

        // Category filter
        if ($request->category) {
            $cat = ProductCategory::where('slug', $request->category)
                ->when($tenantId, fn($q) => $q->where('user_id', $tenantId))
                ->first();
            if ($cat) {
                $childIds = $cat->children->pluck('id')->push($cat->id);
                $query->whereIn('category_id', $childIds);
            }
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('short_description', 'like', "%{$request->search}%");
            });
        }

        // Sort
        match ($request->sort) {
            'price_asc'  => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
            'name'       => $query->orderBy('name'),
            'featured'   => $query->orderByDesc('is_featured'),
            default      => $query->orderByDesc('is_featured')->orderBy('sort_order'),
        };

        $products = $query->paginate(12)->withQueryString();

        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->when($tenantId, fn($q) => $q->where('user_id', $tenantId))
            ->take(4)
            ->get();

        $currentCategory = $request->category
            ? ProductCategory::where('slug', $request->category)
                ->when($tenantId, fn($q) => $q->where('user_id', $tenantId))
                ->first()
            : null;

        return view('portfolio.shop', compact(
            'categories', 'products', 'featuredProducts', 'currentCategory', 'tenant'
        ));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->increment('views');

        $relatedProducts = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->when($product->user_id, fn($q) => $q->where('user_id', $product->user_id))
            ->take(4)
            ->get();

        $approvedReviews = $product->reviews()
            ->where('is_approved', true)
            ->latest()
            ->get();

        $avgRating = $approvedReviews->avg('rating');

        return view('portfolio.shop-product', compact(
            'product', 'relatedProducts', 'approvedReviews', 'avgRating'
        ));
    }
}
