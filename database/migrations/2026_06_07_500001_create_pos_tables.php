<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // POS Sessions — tracks each cashier shift
        Schema::create('pos_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');   // owner tenant
            $table->unsignedBigInteger('cashier_id');  // user who opened session
            $table->string('session_number', 20)->unique();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->decimal('opening_cash', 12, 2)->default(0);
            $table->decimal('closing_cash', 12, 2)->nullable();
            $table->decimal('expected_cash', 12, 2)->nullable();
            $table->decimal('cash_difference', 12, 2)->nullable();
            $table->integer('total_orders')->default(0);
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->decimal('total_discount', 12, 2)->default(0);
            $table->decimal('total_tax', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['cashier_id']);
        });

        // POS Orders — each sale transaction
        Schema::create('pos_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('session_id')->nullable();
            $table->unsignedBigInteger('cashier_id');
            $table->string('order_number', 30)->unique();
            $table->enum('status', ['completed', 'refunded', 'void'])->default('completed');
            // Customer info (walk-in or named)
            $table->string('customer_name', 150)->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->string('customer_email', 150)->nullable();
            // Financials
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->string('discount_type', 10)->default('fixed'); // fixed | percent
            $table->decimal('discount_value', 10, 2)->default(0);  // raw input value
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('change_due', 12, 2)->default(0);
            // Payment
            $table->enum('payment_method', ['cash', 'card', 'upi', 'split'])->default('cash');
            $table->decimal('cash_paid', 12, 2)->default(0);
            $table->decimal('card_paid', 12, 2)->default(0);
            $table->decimal('upi_paid', 12, 2)->default(0);
            $table->string('upi_reference', 100)->nullable();
            $table->string('card_reference', 100)->nullable();
            // Notes
            $table->text('notes')->nullable();
            $table->text('refund_reason')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['session_id']);
            $table->index(['created_at']);
        });

        // POS Order Items
        Schema::create('pos_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pos_order_id');
            $table->unsignedBigInteger('product_id')->nullable(); // null if product deleted
            $table->string('product_name', 255);  // snapshot at time of sale
            $table->string('product_sku', 100)->nullable();
            $table->decimal('unit_price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->integer('quantity');
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2);
            $table->timestamps();

            $table->index(['pos_order_id']);
            $table->index(['product_id']);

            $table->foreign('pos_order_id')->references('id')->on('pos_orders')->onDelete('cascade');
        });

        // Add 'pos' module to Role::availableModules (handled in Role model)
        // Seed store_manager role for each existing tenant
        // (done in seeder migration below)
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_order_items');
        Schema::dropIfExists('pos_orders');
        Schema::dropIfExists('pos_sessions');
    }
};
