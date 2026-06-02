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
    // ─── Dashboard ────────────────────────────────────────────────────────────

    public function dashboard()
    {
        $stats = [
            'total_products'    => Product::count(),
            'active_products'   => Product::where('is_active', true)->count(),
            'featured_products' => Product::where('is_featured', true)->count(),
            'total_categories'  => ProductCategory::count(),
            'out_of_stock'      => Product::where('stock_status', 'out_of_stock')->count(),
            'pending_reviews'   => ProductReview::where('is_approved', false)->count(),
        ];

        $recentProducts = Product::with('category')->latest()->take(5)->get();
        $categories     = ProductCategory::withCount('products')->whereNull('parent_id')->get();

        return view('admin.ecommerce.dashboard', compact('stats', 'recentProducts', 'categories'));
    }

    // ─── Categories ───────────────────────────────────────────────────────────

    public function categoriesIndex()
    {
        $categories = ProductCategory::with(['parent', 'children'])
            ->withCount('products')
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

        $validated['slug'] = Str::slug($validated['name']);
        // Ensure unique slug
        $base = $validated['slug'];
        $i = 1;
        while (ProductCategory::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $base . '-' . $i++;
        }

        ProductCategory::create($validated);
        return back()->with('success', 'Category created successfully.');
    }

    public function categoryUpdate(Request $request, ProductCategory $category)
    {
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
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products. Move products first.');
        }
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    // ─── Products ─────────────────────────────────────────────────────────────

    public function productsIndex(Request $request)
    {
        $query = Product::with('category')->latest();

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
        $categories = ProductCategory::orderBy('name')->get();

        return view('admin.ecommerce.products', compact('products', 'categories'));
    }

    public function productCreate()
    {
        $categories = ProductCategory::orderBy('name')->get();
        return view('admin.ecommerce.product-form', compact('categories'));
    }

    public function productStore(Request $request)
    {
        $validated = $this->validateProduct($request);
        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active', true);
        $validated['manage_stock'] = $request->boolean('manage_stock');

        Product::create($validated);
        return redirect()->route('admin.ecommerce.products')->with('success', 'Product created successfully.');
    }

    public function productEdit(Product $product)
    {
        $categories = ProductCategory::orderBy('name')->get();
        return view('admin.ecommerce.product-form', compact('product', 'categories'));
    }

    public function productUpdate(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product->id);
        $validated['is_featured']  = $request->boolean('is_featured');
        $validated['is_active']    = $request->boolean('is_active', true);
        $validated['manage_stock'] = $request->boolean('manage_stock');

        $product->update($validated);
        return redirect()->route('admin.ecommerce.products')->with('success', 'Product updated successfully.');
    }

    public function productDestroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    public function productToggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        return back()->with('success', 'Product featured status updated.');
    }

    // ─── Reviews ──────────────────────────────────────────────────────────────

    public function reviewsIndex()
    {
        $reviews = ProductReview::with('product')->latest()->paginate(20);
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
        $i = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
