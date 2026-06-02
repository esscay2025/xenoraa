<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        $query = Product::with('category')
            ->where('is_active', true);

        // Category filter
        if ($request->category) {
            $cat = ProductCategory::where('slug', $request->category)->first();
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
            ->take(4)
            ->get();

        $currentCategory = $request->category
            ? ProductCategory::where('slug', $request->category)->first()
            : null;

        return view('portfolio.shop', compact(
            'categories', 'products', 'featuredProducts', 'currentCategory'
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
