<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductReview;
use App\Models\EcomMailConfig;
use App\Models\EcomMailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

    private function uniqueSlug(string $name, ?int $userId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;
        $query = Product::where('slug', $slug);
        if ($userId) $query->where('user_id', $userId);
        while ($query->exists()) {
            $slug = $base . '-' . $i++;
            $query = Product::where('slug', $slug);
            if ($userId) $query->where('user_id', $userId);
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

            $skuVal = trim($row[$col['sku'] ?? 7] ?? '') ?: null;
            // Check for duplicate SKU within this tenant
            if ($skuVal && Product::where('user_id', $tid)->where('sku', $skuVal)->exists()) {
                $errors[] = "Row {$lineNum}: Skipped — SKU '{$skuVal}' already exists for this account.";
                continue;
            }
            try {
                Product::create([
                    'user_id'           => $tid,
                    'name'              => $name,
                    'slug'              => $this->uniqueSlug($name, $tid),
                    'short_description' => trim($row[$col['short_description'] ?? 1] ?? ''),
                    'description'       => trim($row[$col['description'] ?? 2] ?? ''),
                    'category_id'       => $categoryId,
                    'price'             => $price,
                    'sale_price'        => ($row[$col['sale_price'] ?? 5] ?? '') !== '' ? (float)$row[$col['sale_price'] ?? 5] : null,
                    'cost_price'        => ($row[$col['cost_price'] ?? 6] ?? '') !== '' ? (float)$row[$col['cost_price'] ?? 6] : null,
                    'sku'               => $skuVal,
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
            } catch (\Exception $e) {
                $errors[] = "Row {$lineNum}: Failed to import '{$name}' — " . $e->getMessage();
            }
        }

        $msg = "Successfully imported {$imported} product" . ($imported !== 1 ? 's' : '') . '.';
        if (!empty($errors)) {
            return redirect()->route('admin.ecommerce.products')
                ->with('success', $msg)
                ->with('import_errors', $errors);
        }
        return redirect()->route('admin.ecommerce.products')->with('success', $msg);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // INTEGRATIONS — MAIL CONFIG
    // ═══════════════════════════════════════════════════════════════════════════

    public function mailConfigIndex()
    {
        $uid    = $this->tenantId();
        $config = EcomMailConfig::where('user_id', $uid)->first();
        return view('admin.ecommerce.integrations.mail-config', compact('config'));
    }

    public function mailConfigSave(Request $request)
    {
        $uid = $this->tenantId();
        $data = $request->validate([
            'mail_driver'     => 'required|in:smtp,sendmail,mailgun,ses',
            'mail_host'       => 'nullable|string|max:255',
            'mail_port'       => 'required|integer|min:1|max:65535',
            'mail_username'   => 'nullable|string|max:255',
            'mail_password'   => 'nullable|string|max:500',
            'mail_encryption' => 'required|in:tls,ssl,none',
            'from_address'    => 'required|email|max:255',
            'from_name'       => 'required|string|max:255',
            'reply_to'        => 'nullable|email|max:255',
            'is_active'       => 'sometimes|boolean',
        ]);

        $config = EcomMailConfig::firstOrNew(['user_id' => $uid]);
        $config->user_id        = $uid;
        $config->mail_driver    = $data['mail_driver'];
        $config->mail_host      = $data['mail_host'] ?? null;
        $config->mail_port      = $data['mail_port'];
        $config->mail_username  = $data['mail_username'] ?? null;
        $config->mail_encryption = $data['mail_encryption'];
        $config->from_address   = $data['from_address'];
        $config->from_name      = $data['from_name'];
        $config->reply_to       = $data['reply_to'] ?? null;
        $config->is_active      = $request->boolean('is_active');

        if (!empty($data['mail_password'])) {
            $config->mail_password = $data['mail_password'];
        }
        $config->save();

        return redirect()->route('admin.ecommerce.integrations.mail-config')
            ->with('success', 'Mail configuration saved successfully.');
    }

    public function mailConfigTest(Request $request)
    {
        $uid    = $this->tenantId();
        $config = EcomMailConfig::where('user_id', $uid)->first();

        if (!$config || !$config->is_active) {
            return response()->json(['success' => false, 'message' => 'No active mail configuration found.']);
        }

        try {
            $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                $config->mail_host,
                $config->mail_port,
                $config->mail_encryption === 'ssl'
            );
            $transport->setUsername($config->mail_username);
            $transport->setPassword($config->mail_password);

            $mailer = new \Symfony\Component\Mailer\Mailer($transport);
            $email  = (new \Symfony\Component\Mime\Email())
                ->from(new \Symfony\Component\Mime\Address($config->from_address, $config->from_name))
                ->to($config->from_address)
                ->subject('E-commerce Mail Config Test — ' . config('app.name'))
                ->html('<p>Your E-commerce mail configuration is working correctly.</p><p>Sent from: ' . config('app.url') . '</p>');

            $mailer->send($email);

            $config->update(['verified_at' => now(), 'last_error' => null]);
            return response()->json(['success' => true, 'message' => 'Test email sent successfully to ' . $config->from_address]);
        } catch (\Exception $e) {
            $config->update(['last_error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed: ' . $e->getMessage()]);
        }
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // SETTINGS — MAIL TEMPLATES
    // ═══════════════════════════════════════════════════════════════════════════

    public function mailTemplatesIndex()
    {
        $uid       = $this->tenantId();
        $templates = EcomMailTemplate::where('user_id', $uid)->orderBy('type')->orderBy('name')->get();
        $types     = EcomMailTemplate::$types;
        return view('admin.ecommerce.settings.mail-templates', compact('templates', 'types'));
    }

    public function mailTemplateCreate()
    {
        $types = EcomMailTemplate::$types;
        return view('admin.ecommerce.settings.create-mail-template', compact('types'));
    }

    public function mailTemplateStore(Request $request)
    {
        $uid  = $this->tenantId();
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'type'            => 'required|string',
            'subject'         => 'required|string|max:500',
            'body_html'       => 'required|string',
            'primary_color'   => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'font_family'     => 'nullable|string|max:100',
            'is_default'      => 'sometimes|boolean',
            'is_active'       => 'sometimes|boolean',
            'logo'            => 'nullable|image|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('ecom/mail-logos', 'public');
        }

        if ($request->boolean('is_default')) {
            EcomMailTemplate::where('user_id', $uid)->where('type', $data['type'])->update(['is_default' => false]);
        }

        EcomMailTemplate::create([
            'user_id'         => $uid,
            'name'            => $data['name'],
            'type'            => $data['type'],
            'subject'         => $data['subject'],
            'body_html'       => $data['body_html'],
            'logo_path'       => $logoPath,
            'primary_color'   => $data['primary_color'] ?? '#6366f1',
            'secondary_color' => $data['secondary_color'] ?? '#f8fafc',
            'font_family'     => $data['font_family'] ?? 'Inter, Arial, sans-serif',
            'is_default'      => $request->boolean('is_default'),
            'is_active'       => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.ecommerce.settings.mail-templates')
            ->with('success', 'Mail template created successfully.');
    }

    public function mailTemplateShow($id)
    {
        $uid      = $this->tenantId();
        $template = EcomMailTemplate::where('user_id', $uid)->findOrFail($id);
        return view('admin.ecommerce.settings.view-mail-template', compact('template'));
    }

    public function mailTemplateEdit($id)
    {
        $uid      = $this->tenantId();
        $template = EcomMailTemplate::where('user_id', $uid)->findOrFail($id);
        $types    = EcomMailTemplate::$types;
        return view('admin.ecommerce.settings.edit-mail-template', compact('template', 'types'));
    }

    public function mailTemplateUpdate(Request $request, $id)
    {
        $uid      = $this->tenantId();
        $template = EcomMailTemplate::where('user_id', $uid)->findOrFail($id);

        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'type'            => 'required|string',
            'subject'         => 'required|string|max:500',
            'body_html'       => 'required|string',
            'primary_color'   => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'font_family'     => 'nullable|string|max:100',
            'is_default'      => 'sometimes|boolean',
            'is_active'       => 'sometimes|boolean',
            'logo'            => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($template->logo_path) Storage::disk('public')->delete($template->logo_path);
            $data['logo_path'] = $request->file('logo')->store('ecom/mail-logos', 'public');
        }

        if ($request->boolean('is_default')) {
            EcomMailTemplate::where('user_id', $uid)->where('type', $data['type'])
                ->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $template->update([
            'name'            => $data['name'],
            'type'            => $data['type'],
            'subject'         => $data['subject'],
            'body_html'       => $data['body_html'],
            'logo_path'       => $data['logo_path'] ?? $template->logo_path,
            'primary_color'   => $data['primary_color'] ?? $template->primary_color,
            'secondary_color' => $data['secondary_color'] ?? $template->secondary_color,
            'font_family'     => $data['font_family'] ?? $template->font_family,
            'is_default'      => $request->boolean('is_default'),
            'is_active'       => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.ecommerce.settings.mail-templates')
            ->with('success', 'Mail template updated successfully.');
    }

    public function mailTemplateDestroy($id)
    {
        $uid      = $this->tenantId();
        $template = EcomMailTemplate::where('user_id', $uid)->findOrFail($id);
        if ($template->logo_path) Storage::disk('public')->delete($template->logo_path);
        $template->delete();
        return redirect()->route('admin.ecommerce.settings.mail-templates')
            ->with('success', 'Template deleted.');
    }

    public function mailTemplatePreview($id)
    {
        $uid      = $this->tenantId();
        $template = EcomMailTemplate::where('user_id', $uid)->findOrFail($id);
        return view('admin.ecommerce.settings.preview-mail-template', compact('template'));
    }

    public function mailTemplateToggle($id)
    {
        $uid      = $this->tenantId();
        $template = EcomMailTemplate::where('user_id', $uid)->findOrFail($id);
        $template->update(['is_active' => !$template->is_active]);
        return response()->json(['success' => true, 'is_active' => $template->is_active]);
    }

    public function mailTemplateLoadDefaults()
    {
        $uid = $this->tenantId();
        $existing = EcomMailTemplate::where('user_id', $uid)->pluck('type')->toArray();

        $defaults = $this->getDefaultEcomTemplates();
        $loaded   = 0;

        foreach ($defaults as $tpl) {
            if (!in_array($tpl['type'], $existing)) {
                EcomMailTemplate::create(array_merge($tpl, ['user_id' => $uid, 'is_default' => true, 'is_active' => true]));
                $loaded++;
            }
        }

        return redirect()->route('admin.ecommerce.settings.mail-templates')
            ->with('success', "Loaded {$loaded} default template(s) successfully.");
    }

    private function getDefaultEcomTemplates(): array
    {
        $accent = '#6366f1';
        $bg     = '#f8fafc';

        return [
            [
                'name'            => 'Order Confirmation',
                'type'            => 'order_confirmation',
                'subject'         => 'Your Order #{{order_number}} is Confirmed!',
                'primary_color'   => $accent,
                'secondary_color' => $bg,
                'font_family'     => 'Inter, Arial, sans-serif',
                'body_html'       => $this->tplOrderConfirmation($accent, $bg),
            ],
            [
                'name'            => 'Order Shipped',
                'type'            => 'order_shipped',
                'subject'         => 'Your Order #{{order_number}} Has Been Shipped!',
                'primary_color'   => '#0ea5e9',
                'secondary_color' => $bg,
                'font_family'     => 'Inter, Arial, sans-serif',
                'body_html'       => $this->tplOrderShipped('#0ea5e9', $bg),
            ],
            [
                'name'            => 'Order Delivered',
                'type'            => 'order_delivered',
                'subject'         => 'Your Order #{{order_number}} Has Been Delivered!',
                'primary_color'   => '#10b981',
                'secondary_color' => $bg,
                'font_family'     => 'Inter, Arial, sans-serif',
                'body_html'       => $this->tplOrderDelivered('#10b981', $bg),
            ],
            [
                'name'            => 'Order Cancelled',
                'type'            => 'order_cancelled',
                'subject'         => 'Your Order #{{order_number}} Has Been Cancelled',
                'primary_color'   => '#ef4444',
                'secondary_color' => $bg,
                'font_family'     => 'Inter, Arial, sans-serif',
                'body_html'       => $this->tplOrderCancelled('#ef4444', $bg),
            ],
            [
                'name'            => 'Payment Received',
                'type'            => 'payment_received',
                'subject'         => 'Payment Received for Order #{{order_number}}',
                'primary_color'   => '#10b981',
                'secondary_color' => $bg,
                'font_family'     => 'Inter, Arial, sans-serif',
                'body_html'       => $this->tplPaymentReceived('#10b981', $bg),
            ],
            [
                'name'            => 'Refund Processed',
                'type'            => 'refund_processed',
                'subject'         => 'Refund Processed for Order #{{order_number}}',
                'primary_color'   => '#f59e0b',
                'secondary_color' => $bg,
                'font_family'     => 'Inter, Arial, sans-serif',
                'body_html'       => $this->tplRefundProcessed('#f59e0b', $bg),
            ],
            [
                'name'            => 'Abandoned Cart Reminder',
                'type'            => 'cart_abandoned',
                'subject'         => 'You left something behind, {{customer_name}}!',
                'primary_color'   => '#f59e0b',
                'secondary_color' => $bg,
                'font_family'     => 'Inter, Arial, sans-serif',
                'body_html'       => $this->tplAbandonedCart('#f59e0b', $bg),
            ],
            [
                'name'            => 'Review Request',
                'type'            => 'review_request',
                'subject'         => 'How was your order? Share your experience!',
                'primary_color'   => $accent,
                'secondary_color' => $bg,
                'font_family'     => 'Inter, Arial, sans-serif',
                'body_html'       => $this->tplReviewRequest($accent, $bg),
            ],
            [
                'name'            => 'Welcome Email',
                'type'            => 'welcome',
                'subject'         => 'Welcome to {{store_name}}, {{customer_name}}!',
                'primary_color'   => $accent,
                'secondary_color' => $bg,
                'font_family'     => 'Inter, Arial, sans-serif',
                'body_html'       => $this->tplWelcome($accent, $bg),
            ],
            [
                'name'            => 'General Notification',
                'type'            => 'general',
                'subject'         => '{{subject}}',
                'primary_color'   => $accent,
                'secondary_color' => $bg,
                'font_family'     => 'Inter, Arial, sans-serif',
                'body_html'       => $this->tplGeneral($accent, $bg),
            ],
        ];
    }

    // ─── Template HTML Generators ─────────────────────────────────────────────

    private function emailWrapper(string $accent, string $bg, string $title, string $body): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{$title}</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Inter,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:32px 0;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);">
      <!-- Header -->
      <tr><td style="background:{$accent};padding:28px 40px;text-align:center;">
        {{#if logo_path}}<img src="{{logo_url}}" alt="{{store_name}}" style="max-height:48px;margin-bottom:8px;display:block;margin-left:auto;margin-right:auto;">{{/if}}
        <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:700;letter-spacing:-.3px;">{{store_name}}</h1>
      </td></tr>
      <!-- Body -->
      <tr><td style="padding:36px 40px;color:#1e293b;">
        {$body}
      </td></tr>
      <!-- Footer -->
      <tr><td style="background:{$bg};padding:20px 40px;text-align:center;border-top:1px solid #e2e8f0;">
        <p style="margin:0;font-size:12px;color:#94a3b8;">© {{year}} {{store_name}}. All rights reserved.</p>
        <p style="margin:4px 0 0;font-size:12px;color:#94a3b8;">{{store_address}}</p>
      </td></tr>
    </table>
  </td></tr>
</table>
</body>
</html>
HTML;
    }

    private function tplOrderConfirmation(string $accent, string $bg): string
    {
        $body = <<<HTML
<h2 style="margin:0 0 8px;font-size:20px;color:#1e293b;">Thank you for your order! 🎉</h2>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">Hi {{customer_name}}, your order has been confirmed and is being processed.</p>
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-radius:8px;padding:16px;margin-bottom:24px;">
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Order Number:</td><td style="padding:4px 0;font-size:14px;font-weight:600;color:#1e293b;text-align:right;">#{{order_number}}</td></tr>
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Order Date:</td><td style="padding:4px 0;font-size:14px;color:#1e293b;text-align:right;">{{order_date}}</td></tr>
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Payment Method:</td><td style="padding:4px 0;font-size:14px;color:#1e293b;text-align:right;">{{payment_method}}</td></tr>
  <tr><td style="padding:4px 0;font-size:14px;font-weight:700;color:#1e293b;">Total Amount:</td><td style="padding:4px 0;font-size:16px;font-weight:700;color:{$accent};text-align:right;">{{total_amount}}</td></tr>
</table>
<h3 style="margin:0 0 12px;font-size:15px;color:#1e293b;">Items Ordered</h3>
{{line_items}}
<div style="margin-top:24px;padding-top:16px;border-top:1px solid #e2e8f0;">
  <p style="margin:0;font-size:14px;color:#475569;">Shipping to: <strong>{{shipping_address}}</strong></p>
</div>
<div style="margin-top:24px;text-align:center;">
  <a href="{{order_url}}" style="background:{$accent};color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-size:14px;font-weight:600;display:inline-block;">View Order Details</a>
</div>
HTML;
        return $this->emailWrapper($accent, $bg, 'Order Confirmation', $body);
    }

    private function tplOrderShipped(string $accent, string $bg): string
    {
        $body = <<<HTML
<h2 style="margin:0 0 8px;font-size:20px;color:#1e293b;">Your order is on its way! 🚚</h2>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">Hi {{customer_name}}, great news! Your order #{{order_number}} has been shipped.</p>
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-radius:8px;padding:16px;margin-bottom:24px;">
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Tracking Number:</td><td style="padding:4px 0;font-size:14px;font-weight:600;color:#1e293b;text-align:right;">{{tracking_number}}</td></tr>
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Carrier:</td><td style="padding:4px 0;font-size:14px;color:#1e293b;text-align:right;">{{carrier}}</td></tr>
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Estimated Delivery:</td><td style="padding:4px 0;font-size:14px;color:#1e293b;text-align:right;">{{estimated_delivery}}</td></tr>
</table>
<div style="margin-top:24px;text-align:center;">
  <a href="{{tracking_url}}" style="background:{$accent};color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-size:14px;font-weight:600;display:inline-block;">Track Your Package</a>
</div>
HTML;
        return $this->emailWrapper($accent, $bg, 'Order Shipped', $body);
    }

    private function tplOrderDelivered(string $accent, string $bg): string
    {
        $body = <<<HTML
<h2 style="margin:0 0 8px;font-size:20px;color:#1e293b;">Your order has been delivered! ✅</h2>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">Hi {{customer_name}}, your order #{{order_number}} was delivered on {{delivery_date}}.</p>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">We hope you love your purchase! If you have any issues, please contact our support team.</p>
<div style="margin-top:24px;text-align:center;">
  <a href="{{review_url}}" style="background:{$accent};color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-size:14px;font-weight:600;display:inline-block;">Leave a Review</a>
</div>
HTML;
        return $this->emailWrapper($accent, $bg, 'Order Delivered', $body);
    }

    private function tplOrderCancelled(string $accent, string $bg): string
    {
        $body = <<<HTML
<h2 style="margin:0 0 8px;font-size:20px;color:#1e293b;">Your order has been cancelled</h2>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">Hi {{customer_name}}, your order #{{order_number}} has been cancelled.</p>
<table width="100%" cellpadding="0" cellspacing="0" style="background:#fef2f2;border-radius:8px;padding:16px;margin-bottom:24px;border:1px solid #fecaca;">
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Cancellation Reason:</td></tr>
  <tr><td style="padding:4px 0;font-size:14px;color:#1e293b;">{{cancellation_reason}}</td></tr>
</table>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">If a payment was made, a refund will be processed within 5–7 business days.</p>
<div style="margin-top:24px;text-align:center;">
  <a href="{{shop_url}}" style="background:{$accent};color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-size:14px;font-weight:600;display:inline-block;">Continue Shopping</a>
</div>
HTML;
        return $this->emailWrapper($accent, $bg, 'Order Cancelled', $body);
    }

    private function tplPaymentReceived(string $accent, string $bg): string
    {
        $body = <<<HTML
<h2 style="margin:0 0 8px;font-size:20px;color:#1e293b;">Payment Confirmed 💳</h2>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">Hi {{customer_name}}, we have received your payment for order #{{order_number}}.</p>
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4;border-radius:8px;padding:16px;margin-bottom:24px;border:1px solid #bbf7d0;">
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Transaction ID:</td><td style="padding:4px 0;font-size:14px;font-weight:600;color:#1e293b;text-align:right;">{{transaction_id}}</td></tr>
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Amount Paid:</td><td style="padding:4px 0;font-size:16px;font-weight:700;color:{$accent};text-align:right;">{{amount_paid}}</td></tr>
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Payment Date:</td><td style="padding:4px 0;font-size:14px;color:#1e293b;text-align:right;">{{payment_date}}</td></tr>
</table>
<div style="margin-top:24px;text-align:center;">
  <a href="{{order_url}}" style="background:{$accent};color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-size:14px;font-weight:600;display:inline-block;">View Order</a>
</div>
HTML;
        return $this->emailWrapper($accent, $bg, 'Payment Received', $body);
    }

    private function tplRefundProcessed(string $accent, string $bg): string
    {
        $body = <<<HTML
<h2 style="margin:0 0 8px;font-size:20px;color:#1e293b;">Refund Processed 💰</h2>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">Hi {{customer_name}}, your refund for order #{{order_number}} has been processed.</p>
<table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border-radius:8px;padding:16px;margin-bottom:24px;border:1px solid #fde68a;">
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Refund Amount:</td><td style="padding:4px 0;font-size:16px;font-weight:700;color:{$accent};text-align:right;">{{refund_amount}}</td></tr>
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Refund Method:</td><td style="padding:4px 0;font-size:14px;color:#1e293b;text-align:right;">{{refund_method}}</td></tr>
  <tr><td style="padding:4px 0;font-size:14px;color:#64748b;">Processing Time:</td><td style="padding:4px 0;font-size:14px;color:#1e293b;text-align:right;">5–7 business days</td></tr>
</table>
<p style="margin:0;color:#475569;font-size:14px;">If you have any questions about your refund, please contact our support team.</p>
HTML;
        return $this->emailWrapper($accent, $bg, 'Refund Processed', $body);
    }

    private function tplAbandonedCart(string $accent, string $bg): string
    {
        $body = <<<HTML
<h2 style="margin:0 0 8px;font-size:20px;color:#1e293b;">You left something behind! 🛒</h2>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">Hi {{customer_name}}, you have items waiting in your cart. Don't let them slip away!</p>
{{cart_items}}
<p style="margin:16px 0 20px;color:#475569;font-size:14px;">Complete your purchase before items sell out.</p>
<div style="margin-top:24px;text-align:center;">
  <a href="{{cart_url}}" style="background:{$accent};color:#fff;text-decoration:none;padding:14px 32px;border-radius:8px;font-size:15px;font-weight:700;display:inline-block;">Complete Your Purchase</a>
</div>
HTML;
        return $this->emailWrapper($accent, $bg, 'Abandoned Cart', $body);
    }

    private function tplReviewRequest(string $accent, string $bg): string
    {
        $body = <<<HTML
<h2 style="margin:0 0 8px;font-size:20px;color:#1e293b;">How was your experience? ⭐</h2>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">Hi {{customer_name}}, we hope you're enjoying your recent purchase from order #{{order_number}}!</p>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">Your feedback helps us improve and helps other shoppers make informed decisions. It only takes a minute!</p>
<div style="margin-top:24px;text-align:center;">
  <a href="{{review_url}}" style="background:{$accent};color:#fff;text-decoration:none;padding:14px 32px;border-radius:8px;font-size:15px;font-weight:700;display:inline-block;">Write a Review</a>
</div>
HTML;
        return $this->emailWrapper($accent, $bg, 'Review Request', $body);
    }

    private function tplWelcome(string $accent, string $bg): string
    {
        $body = <<<HTML
<h2 style="margin:0 0 8px;font-size:20px;color:#1e293b;">Welcome to {{store_name}}! 🎉</h2>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">Hi {{customer_name}}, thank you for creating an account with us. We're thrilled to have you!</p>
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
  <tr>
    <td width="33%" style="padding:12px;text-align:center;background:#f8fafc;border-radius:8px;margin:4px;">
      <div style="font-size:24px;margin-bottom:4px;">🛍️</div>
      <div style="font-size:13px;font-weight:600;color:#1e293b;">Shop Anytime</div>
      <div style="font-size:12px;color:#64748b;">Browse our full catalogue</div>
    </td>
    <td width="4%"></td>
    <td width="33%" style="padding:12px;text-align:center;background:#f8fafc;border-radius:8px;">
      <div style="font-size:24px;margin-bottom:4px;">📦</div>
      <div style="font-size:13px;font-weight:600;color:#1e293b;">Track Orders</div>
      <div style="font-size:12px;color:#64748b;">Real-time order updates</div>
    </td>
    <td width="4%"></td>
    <td width="33%" style="padding:12px;text-align:center;background:#f8fafc;border-radius:8px;">
      <div style="font-size:24px;margin-bottom:4px;">💬</div>
      <div style="font-size:13px;font-weight:600;color:#1e293b;">24/7 Support</div>
      <div style="font-size:12px;color:#64748b;">We're always here to help</div>
    </td>
  </tr>
</table>
<div style="margin-top:24px;text-align:center;">
  <a href="{{shop_url}}" style="background:{$accent};color:#fff;text-decoration:none;padding:14px 32px;border-radius:8px;font-size:15px;font-weight:700;display:inline-block;">Start Shopping</a>
</div>
HTML;
        return $this->emailWrapper($accent, $bg, 'Welcome', $body);
    }

    private function tplGeneral(string $accent, string $bg): string
    {
        $body = <<<HTML
<h2 style="margin:0 0 8px;font-size:20px;color:#1e293b;">{{heading}}</h2>
<p style="margin:0 0 20px;color:#475569;font-size:15px;">Hi {{customer_name}},</p>
<div style="color:#475569;font-size:15px;line-height:1.7;">
  {{message_body}}
</div>
{{#if cta_text}}
<div style="margin-top:28px;text-align:center;">
  <a href="{{cta_url}}" style="background:{$accent};color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-size:14px;font-weight:600;display:inline-block;">{{cta_text}}</a>
</div>
{{/if}}
HTML;
        return $this->emailWrapper($accent, $bg, 'General', $body);
    }

    // ─── Store Config ──────────────────────────────────────────────────────────

    /**
     * Show the Store Config page (all 8 tabs on one page).
     */
    public function storeConfigIndex(Request $request)
    {
        $tid    = $this->tenantId();
        $tab    = $request->get('tab', 'general');
        $config = \App\Models\EcomStoreConfig::firstOrCreate(
            ['user_id' => $tid],
            ['store_name' => auth()->user()->name ?? 'My Store']
        );
        return view('admin.ecommerce.store-config', compact('config', 'tab'));
    }

    /**
     * Save settings for a specific tab.
     */
    public function storeConfigSave(Request $request)
    {
        $tid    = $this->tenantId();
        $tab    = $request->input('tab', 'general');
        $config = \App\Models\EcomStoreConfig::firstOrCreate(['user_id' => $tid]);

        $tabFields = [
            'general' => [
                'store_name', 'store_description', 'store_address_line1', 'store_address_line2',
                'store_city', 'store_state', 'store_postcode', 'store_country',
                'store_email', 'store_phone', 'currency', 'currency_position',
                'thousand_separator', 'decimal_separator', 'decimal_places',
            ],
            'products' => [
                'weight_unit', 'dimension_unit', 'enable_reviews', 'reviews_verified_only',
                'enable_ratings', 'shop_page_display', 'products_per_page',
                'default_product_sorting', 'enable_ajax_add_to_cart', 'enable_wishlist', 'enable_compare',
            ],
            'shipping' => [
                'enable_shipping', 'shipping_calculation', 'hide_shipping_until_address',
                'enable_free_shipping', 'free_shipping_min_amount', 'enable_flat_rate',
                'flat_rate_cost', 'enable_local_pickup', 'local_pickup_address',
            ],
            'payments' => [
                'enable_cod', 'cod_title', 'cod_description',
                'enable_razorpay', 'razorpay_key_id', 'razorpay_key_secret', 'razorpay_test_mode',
                'enable_stripe', 'stripe_publishable_key', 'stripe_secret_key', 'stripe_test_mode',
                'enable_paypal', 'paypal_email', 'paypal_sandbox',
                'enable_bank_transfer', 'bank_transfer_details',
                'enable_upi', 'upi_id',
            ],
            'accounts' => [
                'allow_guest_checkout', 'allow_account_creation_checkout', 'allow_account_creation_shop',
                'auto_generate_username', 'auto_generate_password',
                'erasure_request_removes_orders', 'erasure_request_removes_downloads',
                'allow_bulk_remove_personal_data', 'privacy_policy_text',
                'checkout_privacy_policy_text', 'registration_privacy_policy_text',
            ],
            'visibility' => [
                'catalog_visibility', 'hide_out_of_stock', 'stock_display_format',
                'enable_breadcrumbs', 'enable_lightbox', 'enable_zoom', 'enable_gallery_slider',
                'redirect_add_to_cart', 'cart_redirect_after_add',
            ],
            'pos' => [
                'pos_store_name', 'pos_receipt_header', 'pos_receipt_footer', 'pos_tax_number',
                'pos_print_receipt_auto', 'pos_enable_barcode', 'pos_barcode_field',
                'pos_enable_customer_display', 'pos_default_customer', 'pos_tax_display',
            ],
            'advanced' => [
                'enable_coupons', 'calc_discounts_sequentially', 'enable_order_notes',
                'hold_stock_minutes', 'notify_low_stock', 'low_stock_threshold',
                'notify_no_stock', 'no_stock_threshold', 'hide_out_of_stock_items', 'stock_format',
                'enable_taxes', 'tax_based_on', 'shipping_tax_class', 'prices_include_tax',
                'display_cart_taxes_inline', 'force_ssl_checkout', 'unforce_ssl_checkout',
                'custom_css', 'custom_js',
            ],
        ];

        $fields = $tabFields[$tab] ?? [];

        $booleanFields = [
            'enable_reviews', 'reviews_verified_only', 'enable_ratings', 'enable_ajax_add_to_cart',
            'enable_wishlist', 'enable_compare', 'enable_shipping', 'hide_shipping_until_address',
            'enable_free_shipping', 'enable_flat_rate', 'enable_local_pickup',
            'enable_cod', 'enable_razorpay', 'razorpay_test_mode', 'enable_stripe', 'stripe_test_mode',
            'enable_paypal', 'paypal_sandbox', 'enable_bank_transfer', 'enable_upi',
            'allow_guest_checkout', 'allow_account_creation_checkout', 'allow_account_creation_shop',
            'auto_generate_username', 'auto_generate_password',
            'erasure_request_removes_orders', 'erasure_request_removes_downloads',
            'allow_bulk_remove_personal_data', 'hide_out_of_stock', 'enable_breadcrumbs',
            'enable_lightbox', 'enable_zoom', 'enable_gallery_slider', 'redirect_add_to_cart',
            'pos_print_receipt_auto', 'pos_enable_barcode', 'pos_enable_customer_display',
            'enable_coupons', 'calc_discounts_sequentially', 'enable_order_notes',
            'notify_low_stock', 'notify_no_stock', 'hide_out_of_stock_items',
            'enable_taxes', 'prices_include_tax', 'display_cart_taxes_inline',
            'force_ssl_checkout', 'unforce_ssl_checkout',
        ];

        $data = [];
        foreach ($fields as $field) {
            if (in_array($field, $booleanFields)) {
                $data[$field] = $request->boolean($field);
            } else {
                $data[$field] = $request->input($field);
            }
        }

        if ($tab === 'pos' && $request->hasFile('pos_receipt_logo')) {
            $path = $request->file('pos_receipt_logo')->store('ecom/pos-logos', 'public');
            $data['pos_receipt_logo'] = $path;
        }

        $config->update($data);

        return redirect()->route('admin.ecommerce.store-config', ['tab' => $tab])
                         ->with('success', 'Store settings saved successfully.');
    }
}
