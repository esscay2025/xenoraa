<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EcommerceController extends Controller
{
    // ─── Tenant Helper ────────────────────────────────────────────────────────

    private function tenantId(): int
    {
        return auth()->id();
    }

    // ─── Dashboard ────────────────────────────────────────────────────────────

    public function dashboard()
    {
        $tid = $this->tenantId();

        $stats = [
            'total_products'    => Product::where('user_id', $tid)->count(),
            'active_products'   => Product::where('user_id', $tid)->where('is_active', true)->count(),
            'featured_products' => Product::where('user_id', $tid)->where('is_featured', true)->count(),
            'total_categories'  => ProductCategory::where('user_id', $tid)->count(),
            'out_of_stock'      => Product::where('user_id', $tid)->where('stock_status', 'out_of_stock')->count(),
            'pending_reviews'   => ProductReview::whereHas('product', fn($q) => $q->where('user_id', $tid))
                                    ->where('is_approved', false)->count(),
        ];

        $recentProducts = Product::with('category')->where('user_id', $tid)->latest()->take(5)->get();
        $categories     = ProductCategory::withCount('products')->where('user_id', $tid)->whereNull('parent_id')->get();

        return view('admin.ecommerce.dashboard', compact('stats', 'recentProducts', 'categories'));
    }

    // ─── Categories ───────────────────────────────────────────────────────────

    public function categoriesIndex()
    {
        $tid = $this->tenantId();
        $categories = ProductCategory::with(['parent', 'children'])
            ->withCount('products')
            ->where('user_id', $tid)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();
        return view('admin.ecommerce.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:50',
            'parent_id'   => 'nullable|exists:product_categories,id',
            'sort_order'  => 'nullable|integer',
        ]);

        $validated['slug']    = Str::slug($validated['name']);
        $validated['user_id'] = $this->tenantId();

        // Ensure unique slug per tenant
        $base = $validated['slug'];
        $i    = 1;
        while (ProductCategory::where('slug', $validated['slug'])->where('user_id', $validated['user_id'])->exists()) {
            $validated['slug'] = $base . '-' . $i++;
        }

        ProductCategory::create($validated);
        return back()->with('success', 'Category created successfully.');
    }

    public function categoryUpdate(Request $request, ProductCategory $category)
    {
        abort_if($category->user_id !== $this->tenantId(), 403);

        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:50',
            'parent_id'   => 'nullable|exists:product_categories,id',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $category->update($validated);
        return back()->with('success', 'Category updated.');
    }

    public function categoryDestroy(ProductCategory $category)
    {
        abort_if($category->user_id !== $this->tenantId(), 403);

        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products. Move products first.');
        }
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    // ─── Products ─────────────────────────────────────────────────────────────

    public function productsIndex(Request $request)
    {
        $tid   = $this->tenantId();
        $query = Product::with('category')->where('user_id', $tid)->latest();

        if ($request->category) {
            $query->where('category_id', $request->category);
        }
        if ($request->status) {
            $query->where('stock_status', $request->status);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        $products   = $query->paginate(20);
        $categories = ProductCategory::where('user_id', $tid)->orderBy('name')->get();

        return view('admin.ecommerce.products', compact('products', 'categories'));
    }

    public function productCreate()
    {
        $categories = ProductCategory::where('user_id', $this->tenantId())->orderBy('name')->get();
        return view('admin.ecommerce.product-form', compact('categories'));
    }

    public function productStore(Request $request)
    {
        $validated = $this->validateProduct($request);
        $validated['slug']         = $this->uniqueSlug($validated['name']);
        $validated['is_featured']  = $request->boolean('is_featured');
        $validated['is_active']    = $request->boolean('is_active', true);
        $validated['manage_stock'] = $request->boolean('manage_stock');
        $validated['user_id']      = $this->tenantId();

        Product::create($validated);
        return redirect()->route('admin.ecommerce.products')->with('success', 'Product created successfully.');
    }

    public function productEdit(Product $product)
    {
        abort_if($product->user_id !== $this->tenantId(), 403);
        $categories = ProductCategory::where('user_id', $this->tenantId())->orderBy('name')->get();
        return view('admin.ecommerce.product-form', compact('product', 'categories'));
    }

    public function productUpdate(Request $request, Product $product)
    {
        abort_if($product->user_id !== $this->tenantId(), 403);

        $validated = $this->validateProduct($request, $product->id);
        $validated['is_featured']  = $request->boolean('is_featured');
        $validated['is_active']    = $request->boolean('is_active', true);
        $validated['manage_stock'] = $request->boolean('manage_stock');

        $product->update($validated);
        return redirect()->route('admin.ecommerce.products')->with('success', 'Product updated successfully.');
    }

    public function productDestroy(Product $product)
    {
        abort_if($product->user_id !== $this->tenantId(), 403);
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    public function productToggleFeatured(Product $product)
    {
        abort_if($product->user_id !== $this->tenantId(), 403);
        $product->update(['is_featured' => !$product->is_featured]);
        return back()->with('success', 'Product featured status updated.');
    }

    // ─── Reviews ──────────────────────────────────────────────────────────────

    public function reviewsIndex()
    {
        $tid     = $this->tenantId();
        $reviews = ProductReview::with(['product', 'user'])
            ->whereHas('product', fn($q) => $q->where('user_id', $tid))
            ->latest()
            ->paginate(20);
        return view('admin.ecommerce.reviews', compact('reviews'));
    }

    public function reviewApprove(ProductReview $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review approved.');
    }

    public function reviewDestroy(ProductReview $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'              => 'required|string|max:200',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'category_id'       => 'nullable|exists:product_categories,id',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0',
            'cost_price'        => 'nullable|numeric|min:0',
            'sku'               => 'nullable|string|max:100',
            'stock_quantity'    => 'nullable|integer|min:0',
            'stock_status'      => 'required|in:in_stock,out_of_stock,pre_order',
            'featured_image'    => 'nullable|string|max:500',
            'type'              => 'required|in:simple,digital,service',
            'weight'            => 'nullable|numeric',
            'dimensions'        => 'nullable|string|max:100',
            'meta_title'        => 'nullable|string|max:200',
            'meta_description'  => 'nullable|string|max:500',
            'sort_order'        => 'nullable|integer',
        ]);
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
