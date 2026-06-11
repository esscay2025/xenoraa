<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── crm_price_books ──────────────────────────────────────────────
        Schema::table('crm_price_books', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_price_books', 'pricing_model'))
                $table->string('pricing_model')->nullable();
            if (!Schema::hasColumn('crm_price_books', 'currency'))
                $table->string('currency', 10)->nullable();
        });

        // ── crm_quotes ───────────────────────────────────────────────────
        Schema::table('crm_quotes', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_quotes', 'owner_id'))
                $table->unsignedBigInteger('owner_id')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'team'))
                $table->string('team')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'carrier'))
                $table->string('carrier')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'bill_country'))
                $table->string('bill_country')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'bill_building'))
                $table->string('bill_building')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'bill_street'))
                $table->string('bill_street')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'bill_city'))
                $table->string('bill_city')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'bill_state'))
                $table->string('bill_state')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'bill_zip'))
                $table->string('bill_zip')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'ship_country'))
                $table->string('ship_country')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'ship_building'))
                $table->string('ship_building')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'ship_street'))
                $table->string('ship_street')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'ship_city'))
                $table->string('ship_city')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'ship_state'))
                $table->string('ship_state')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'ship_zip'))
                $table->string('ship_zip')->nullable();
            if (!Schema::hasColumn('crm_quotes', 'adjustment'))
                $table->decimal('adjustment', 12, 2)->default(0);
            if (!Schema::hasColumn('crm_quotes', 'grand_total'))
                $table->decimal('grand_total', 12, 2)->default(0);
            if (!Schema::hasColumn('crm_quotes', 'line_items'))
                $table->json('line_items')->nullable();
        });

        // ── crm_sales_orders ─────────────────────────────────────────────
        Schema::table('crm_sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_sales_orders', 'owner_id'))
                $table->unsignedBigInteger('owner_id')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'deal_id'))
                $table->unsignedBigInteger('deal_id')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'customer_no'))
                $table->string('customer_no')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'purchase_order'))
                $table->string('purchase_order')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'carrier'))
                $table->string('carrier')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'sales_commission'))
                $table->decimal('sales_commission', 8, 2)->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'excise_duty'))
                $table->decimal('excise_duty', 8, 2)->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'pending'))
                $table->decimal('pending', 12, 2)->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'bill_country'))
                $table->string('bill_country')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'bill_building'))
                $table->string('bill_building')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'bill_street'))
                $table->string('bill_street')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'bill_city'))
                $table->string('bill_city')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'bill_state'))
                $table->string('bill_state')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'bill_zip'))
                $table->string('bill_zip')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'ship_country'))
                $table->string('ship_country')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'ship_building'))
                $table->string('ship_building')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'ship_street'))
                $table->string('ship_street')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'ship_city'))
                $table->string('ship_city')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'ship_state'))
                $table->string('ship_state')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'ship_zip'))
                $table->string('ship_zip')->nullable();
            if (!Schema::hasColumn('crm_sales_orders', 'adjustment'))
                $table->decimal('adjustment', 12, 2)->default(0);
            if (!Schema::hasColumn('crm_sales_orders', 'grand_total'))
                $table->decimal('grand_total', 12, 2)->default(0);
            if (!Schema::hasColumn('crm_sales_orders', 'line_items'))
                $table->json('line_items')->nullable();
        });

        // ── crm_purchase_orders ──────────────────────────────────────────
        Schema::table('crm_purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_purchase_orders', 'owner_id'))
                $table->unsignedBigInteger('owner_id')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'contact_id'))
                $table->unsignedBigInteger('contact_id')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'requisition_no'))
                $table->string('requisition_no')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'tracking_no'))
                $table->string('tracking_no')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'po_date'))
                $table->date('po_date')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'carrier'))
                $table->string('carrier')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'sales_commission'))
                $table->decimal('sales_commission', 8, 2)->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'excise_duty'))
                $table->decimal('excise_duty', 8, 2)->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'bill_country'))
                $table->string('bill_country')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'bill_building'))
                $table->string('bill_building')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'bill_street'))
                $table->string('bill_street')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'bill_city'))
                $table->string('bill_city')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'bill_state'))
                $table->string('bill_state')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'bill_zip'))
                $table->string('bill_zip')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'ship_country'))
                $table->string('ship_country')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'ship_building'))
                $table->string('ship_building')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'ship_street'))
                $table->string('ship_street')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'ship_city'))
                $table->string('ship_city')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'ship_state'))
                $table->string('ship_state')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'ship_zip'))
                $table->string('ship_zip')->nullable();
            if (!Schema::hasColumn('crm_purchase_orders', 'adjustment'))
                $table->decimal('adjustment', 12, 2)->default(0);
            if (!Schema::hasColumn('crm_purchase_orders', 'grand_total'))
                $table->decimal('grand_total', 12, 2)->default(0);
            if (!Schema::hasColumn('crm_purchase_orders', 'line_items'))
                $table->json('line_items')->nullable();
        });

        // ── crm_invoices ─────────────────────────────────────────────────
        Schema::table('crm_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_invoices', 'owner_id'))
                $table->unsignedBigInteger('owner_id')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'deal_id'))
                $table->unsignedBigInteger('deal_id')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'purchase_order_id'))
                $table->unsignedBigInteger('purchase_order_id')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'invoice_date'))
                $table->date('invoice_date')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'sales_commission'))
                $table->decimal('sales_commission', 8, 2)->nullable();
            if (!Schema::hasColumn('crm_invoices', 'excise_duty'))
                $table->decimal('excise_duty', 8, 2)->nullable();
            if (!Schema::hasColumn('crm_invoices', 'bill_country'))
                $table->string('bill_country')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'bill_building'))
                $table->string('bill_building')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'bill_street'))
                $table->string('bill_street')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'bill_city'))
                $table->string('bill_city')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'bill_state'))
                $table->string('bill_state')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'bill_zip'))
                $table->string('bill_zip')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'ship_country'))
                $table->string('ship_country')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'ship_building'))
                $table->string('ship_building')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'ship_street'))
                $table->string('ship_street')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'ship_city'))
                $table->string('ship_city')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'ship_state'))
                $table->string('ship_state')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'ship_zip'))
                $table->string('ship_zip')->nullable();
            if (!Schema::hasColumn('crm_invoices', 'adjustment'))
                $table->decimal('adjustment', 12, 2)->default(0);
            if (!Schema::hasColumn('crm_invoices', 'grand_total'))
                $table->decimal('grand_total', 12, 2)->default(0);
            if (!Schema::hasColumn('crm_invoices', 'line_items'))
                $table->json('line_items')->nullable();
        });

        // ── crm_vendors ──────────────────────────────────────────────────
        Schema::table('crm_vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_vendors', 'owner_id'))
                $table->unsignedBigInteger('owner_id')->nullable();
            if (!Schema::hasColumn('crm_vendors', 'fax'))
                $table->string('fax')->nullable();
            if (!Schema::hasColumn('crm_vendors', 'gl_account'))
                $table->string('gl_account')->nullable();
            if (!Schema::hasColumn('crm_vendors', 'email_opt_out'))
                $table->boolean('email_opt_out')->default(false);
            if (!Schema::hasColumn('crm_vendors', 'bill_country'))
                $table->string('bill_country')->nullable();
            if (!Schema::hasColumn('crm_vendors', 'bill_building'))
                $table->string('bill_building')->nullable();
            if (!Schema::hasColumn('crm_vendors', 'bill_street'))
                $table->string('bill_street')->nullable();
            if (!Schema::hasColumn('crm_vendors', 'bill_city'))
                $table->string('bill_city')->nullable();
            if (!Schema::hasColumn('crm_vendors', 'bill_state'))
                $table->string('bill_state')->nullable();
            if (!Schema::hasColumn('crm_vendors', 'bill_zip'))
                $table->string('bill_zip')->nullable();
        });

        // ── crm_products (NEW TABLE) ──────────────────────────────────────
        if (!Schema::hasTable('crm_products')) {
            Schema::create('crm_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('owner_id')->nullable();
                $table->unsignedBigInteger('vendor_id')->nullable();
                $table->string('name');
                $table->string('product_code')->nullable();
                $table->string('product_category')->nullable();
                $table->string('manufacturer')->nullable();
                $table->boolean('is_active')->default(true);
                $table->date('sales_start_date')->nullable();
                $table->date('sales_end_date')->nullable();
                $table->date('support_start_date')->nullable();
                $table->date('support_end_date')->nullable();
                // Price Information
                $table->decimal('unit_price', 12, 2)->default(0);
                $table->decimal('tax', 8, 2)->nullable();
                $table->decimal('commission_rate', 8, 2)->nullable();
                $table->boolean('taxable')->default(false);
                // Stock Information
                $table->string('usage_unit')->nullable();
                $table->string('box')->nullable();
                $table->integer('qty_in_stock')->default(0);
                $table->string('handler')->nullable();
                $table->integer('qty_ordered')->default(0);
                $table->integer('reorder_level')->default(0);
                $table->integer('qty_in_demand')->default(0);
                // Description
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_products');
    }
};
