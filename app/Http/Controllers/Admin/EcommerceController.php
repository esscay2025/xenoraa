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

    // ─── Import / Export / Template ──────────────────────────────────────────

    /**
     * Download a blank Excel template for product import.
     */
    public function productTemplate()
    {
        $headers = [
            'name', 'short_description', 'description', 'category_name',
            'price', 'sale_price', 'cost_price', 'sku',
            'stock_quantity', 'stock_status', 'type',
            'weight', 'dimensions', 'featured_image',
            'meta_title', 'meta_description', 'is_active', 'is_featured',
        ];
        $notes = [
            'Required. Product name.',
            'Optional. Short description (max 500 chars).',
            'Optional. Full description.',
            'Optional. Category name (must already exist).',
            'Required. Selling price (numeric).',
            'Optional. Sale/discounted price (numeric).',
            'Optional. Cost price (numeric).',
            'Optional. SKU code.',
            'Optional. Stock quantity (integer).',
            'Required. in_stock | out_of_stock | pre_order',
            'Required. simple | digital | service',
            'Optional. Weight in kg (numeric).',
            'Optional. e.g. 10x5x3 cm',
            'Optional. Image URL.',
            'Optional. SEO title.',
            'Optional. SEO description.',
            'Optional. 1 = Active, 0 = Inactive (default 1).',
            'Optional. 1 = Featured, 0 = Normal (default 0).',
        ];

        // Build CSV content (works without PhpSpreadsheet)
        $rows = [];
        $rows[] = $headers;
        $rows[] = $notes;
        // Sample row
        $rows[] = [
            'Sample Product', 'A great product', 'Full description here',
            'Electronics', '999.00', '799.00', '500.00', 'SKU-001',
            '50', 'in_stock', 'simple', '0.5', '10x5x3', '',
            'Sample Product | My Store', 'Buy the best sample product.', '1', '0',
        ];

        $filename = 'products_import_template_' . date('Ymd') . '.csv';
        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export all tenant products to CSV.
     */
    public function productExport()
    {
        $tid      = $this->tenantId();
        $products = Product::with('category')
            ->where('user_id', $tid)
            ->orderBy('name')
            ->get();

        $filename = 'products_export_' . date('Ymd_His') . '.csv';
        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');
            // Header row
            fputcsv($handle, [
                'id', 'name', 'short_description', 'description', 'category_name',
                'price', 'sale_price', 'cost_price', 'sku',
                'stock_quantity', 'stock_status', 'type',
                'weight', 'dimensions', 'featured_image',
                'meta_title', 'meta_description', 'is_active', 'is_featured',
                'created_at',
            ]);
            foreach ($products as $p) {
                fputcsv($handle, [
                    $p->id,
                    $p->name,
                    $p->short_description,
                    $p->description,
                    $p->category?->name ?? '',
                    $p->price,
                    $p->sale_price,
                    $p->cost_price,
                    $p->sku,
                    $p->stock_quantity,
                    $p->stock_status,
                    $p->type,
                    $p->weight,
                    $p->dimensions,
                    $p->featured_image,
                    $p->meta_title,
                    $p->meta_description,
                    $p->is_active ? 1 : 0,
                    $p->is_featured ? 1 : 0,
                    $p->created_at?->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Import products from an uploaded CSV/Excel file.
     */
    public function productImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $tid  = $this->tenantId();
        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());

        // Read rows from CSV or Excel
        $rows = [];
        if (in_array($ext, ['csv', 'txt'])) {
            $handle = fopen($file->getRealPath(), 'r');
            while (($row = fgetcsv($handle)) !== false) {
                $rows[] = $row;
            }
            fclose($handle);
        } elseif (in_array($ext, ['xlsx', 'xls'])) {
            // Try PhpSpreadsheet if available
            if (class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
                $sheet       = $spreadsheet->getActiveSheet();
                foreach ($sheet->toArray() as $row) {
                    $rows[] = $row;
                }
            } else {
                return back()->with('error', 'Excel import requires PhpSpreadsheet. Please use CSV format instead.');
            }
        }

        if (empty($rows) || count($rows) < 2) {
            return back()->with('error', 'The file is empty or has no data rows.');
        }

        // Map header row to column indices
        $headerRow = array_map('strtolower', array_map('trim', $rows[0]));
        $col       = array_flip($headerRow);

        $imported = 0;
        $errors   = [];
        $limit    = 500;

        // Skip header row (index 0) and optional notes row if 2nd row looks like notes
        $startRow = 1;
        if (isset($rows[1][0]) && stripos((string)$rows[1][0], 'required') !== false) {
            $startRow = 2; // skip the notes row from template
        }

        foreach (array_slice($rows, $startRow) as $i => $row) {
            $lineNum = $i + $startRow + 1;
            if ($imported >= $limit) {
                $errors[] = "Row {$lineNum}: Import limit of {$limit} products reached. Remaining rows skipped.";
                break;
            }

            $name = trim($row[$col['name'] ?? 0] ?? '');
            if (empty($name)) {
                $errors[] = "Row {$lineNum}: Skipped — 'name' is required.";
                continue;
            }

            $price = (float)($row[$col['price'] ?? 4] ?? 0);
            if ($price <= 0) {
                $errors[] = "Row {$lineNum}: Skipped — 'price' must be greater than 0.";
                continue;
            }

            // Resolve category
            $categoryId = null;
            $catName    = trim($row[$col['category_name'] ?? 3] ?? '');
            if ($catName) {
                $cat = ProductCategory::firstOrCreate(
                    ['name' => $catName, 'user_id' => $tid],
                    ['slug' => Str::slug($catName)]
                );
                $categoryId = $cat->id;
            }

            $stockStatus = trim($row[$col['stock_status'] ?? 9] ?? 'in_stock');
            if (!in_array($stockStatus, ['in_stock', 'out_of_stock', 'pre_order'])) {
                $stockStatus = 'in_stock';
            }
            $type = trim($row[$col['type'] ?? 10] ?? 'simple');
            if (!in_array($type, ['simple', 'digital', 'service'])) {
                $type = 'simple';
            }

            Product::create([
                'user_id'           => $tid,
                'name'              => $name,
                'slug'              => $this->uniqueSlug($name),
                'short_description' => trim($row[$col['short_description'] ?? 1] ?? ''),
                'description'       => trim($row[$col['description'] ?? 2] ?? ''),
                'category_id'       => $categoryId,
                'price'             => $price,
                'sale_price'        => ($row[$col['sale_price'] ?? 5] ?? '') !== '' ? (float)$row[$col['sale_price'] ?? 5] : null,
                'cost_price'        => ($row[$col['cost_price'] ?? 6] ?? '') !== '' ? (float)$row[$col['cost_price'] ?? 6] : null,
                'sku'               => trim($row[$col['sku'] ?? 7] ?? '') ?: null,
                'stock_quantity'    => ($row[$col['stock_quantity'] ?? 8] ?? '') !== '' ? (int)$row[$col['stock_quantity'] ?? 8] : null,
                'stock_status'      => $stockStatus,
                'type'              => $type,
                'weight'            => ($row[$col['weight'] ?? 11] ?? '') !== '' ? (float)$row[$col['weight'] ?? 11] : null,
                'dimensions'        => trim($row[$col['dimensions'] ?? 12] ?? '') ?: null,
                'featured_image'    => trim($row[$col['featured_image'] ?? 13] ?? '') ?: null,
                'meta_title'        => trim($row[$col['meta_title'] ?? 14] ?? '') ?: null,
                'meta_description'  => trim($row[$col['meta_description'] ?? 15] ?? '') ?: null,
                'is_active'         => (int)($row[$col['is_active'] ?? 16] ?? 1) === 1,
                'is_featured'       => (int)($row[$col['is_featured'] ?? 17] ?? 0) === 1,
                'manage_stock'      => true,
            ]);
            $imported++;
        }

        $msg = "Successfully imported {$imported} product" . ($imported !== 1 ? 's' : '') . '.';
        if (!empty($errors)) {
            return redirect()->route('admin.ecommerce.products')
                ->with('success', $msg)
                ->with('import_errors', $errors);
        }
        return redirect()->route('admin.ecommerce.products')->with('success', $msg);
    }
}
