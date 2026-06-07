<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ─── 1. FORECASTS ───────────────────────────────────────────────────────
        Schema::create('crm_forecasts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->integer('year');
            $table->integer('quarter'); // 1, 2, 3, 4
            $table->decimal('target_amount', 15, 2)->default(0);
            $table->decimal('achieved_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'year', 'quarter']);
        });

        // ─── 2. INVENTORY: VENDORS ──────────────────────────────────────────────
        Schema::create('crm_vendors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('category')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
            $table->index('user_id');
        });

        // ─── 3. INVENTORY: PRICE BOOKS ──────────────────────────────────────────
        Schema::create('crm_price_books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('pricing_percentage', 5, 2)->default(0); // markup/discount relative to base product price
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('user_id');
        });

        // ─── 4. INVENTORY: QUOTES ───────────────────────────────────────────────
        Schema::create('crm_quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('deal_id')->nullable();
            $table->string('quote_number')->unique();
            $table->string('subject');
            $table->string('stage')->default('draft'); // draft, negotiation, delivered, accepted, declined
            $table->date('valid_until')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('account_id');
        });

        // ─── 5. INVENTORY: SALES ORDERS ─────────────────────────────────────────
        Schema::create('crm_sales_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('quote_id')->nullable();
            $table->string('so_number')->unique();
            $table->string('subject');
            $table->string('status')->default('draft'); // draft, approved, packing, shipped, delivered, cancelled
            $table->date('delivery_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('account_id');
        });

        // ─── 6. INVENTORY: PURCHASE ORDERS ──────────────────────────────────────
        Schema::create('crm_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('po_number')->unique();
            $table->string('subject');
            $table->string('status')->default('draft'); // draft, ordered, received, cancelled
            $table->date('expected_delivery')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('vendor_id');
        });

        // ─── 7. INVENTORY: INVOICES ─────────────────────────────────────────────
        Schema::create('crm_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('sales_order_id')->nullable();
            $table->string('invoice_number')->unique();
            $table->string('subject');
            $table->string('status')->default('unpaid'); // unpaid, partially_paid, paid, overdue, void
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('account_id');
        });

        // ─── 8. CRM INVENTORY ITEM DETAILS (Polymorphic for Quotes, SO, PO, Invoices) ───
        Schema::create('crm_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('itemable_type'); // CrmQuote, CrmSalesOrder, CrmPurchaseOrder, CrmInvoice
            $table->unsignedBigInteger('itemable_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('line_total', 15, 2);
            $table->timestamps();
            $table->index(['itemable_type', 'itemable_id']);
        });

        // ─── 9. SUPPORT: CASES ──────────────────────────────────────────────────
        Schema::create('crm_cases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->string('case_number')->unique();
            $table->string('subject');
            $table->string('priority')->default('medium'); // low, medium, high, critical
            $table->string('status')->default('new'); // new, assigned, in_progress, pending_customer, resolved, closed
            $table->string('type')->nullable(); // question, problem, feature_request, other
            $table->string('origin')->default('web'); // web, email, phone, chat
            $table->text('description')->nullable();
            $table->text('resolution')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable(); // user_id of staff
            $table->timestamps();
            $table->index('user_id');
            $table->index('account_id');
        });

        // ─── 10. SUPPORT: SOLUTIONS ─────────────────────────────────────────────
        Schema::create('crm_solutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->string('title');
            $table->text('question')->nullable();
            $table->text('answer');
            $table->string('category')->nullable();
            $table->boolean('is_public')->default(true);
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->index('user_id');
        });

        // ─── 11. SERVICES ───────────────────────────────────────────────────────
        Schema::create('crm_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('duration_minutes')->default(60);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('user_id');
        });

        Schema::create('crm_service_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->dateTime('booking_time');
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled, no_show
            $table->decimal('price', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('service_id');
        });

        // ─── 12. PROJECTS ───────────────────────────────────────────────────────
        Schema::create('crm_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('deal_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('planning'); // planning, active, on_hold, completed, cancelled
            $table->decimal('budget', 15, 2)->default(0);
            $table->decimal('cost', 15, 2)->default(0);
            $table->timestamps();
            $table->index('user_id');
            $table->index('account_id');
        });

        Schema::create('crm_project_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high
            $table->string('status')->default('todo'); // todo, in_progress, testing, completed
            $table->unsignedBigInteger('assigned_to')->nullable(); // user_id of staff
            $table->timestamps();
            $table->index('project_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_project_tasks');
        Schema::dropIfExists('crm_projects');
        Schema::dropIfExists('crm_service_bookings');
        Schema::dropIfExists('crm_services');
        Schema::dropIfExists('crm_solutions');
        Schema::dropIfExists('crm_cases');
        Schema::dropIfExists('crm_inventory_items');
        Schema::dropIfExists('crm_invoices');
        Schema::dropIfExists('crm_purchase_orders');
        Schema::dropIfExists('crm_sales_orders');
        Schema::dropIfExists('crm_quotes');
        Schema::dropIfExists('crm_price_books');
        Schema::dropIfExists('crm_vendors');
        Schema::dropIfExists('crm_forecasts');
    }
};
