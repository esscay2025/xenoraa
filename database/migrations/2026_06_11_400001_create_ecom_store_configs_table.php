<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ecom_store_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            // ── General ──────────────────────────────────────────────────────
            $table->string('store_name')->nullable();
            $table->text('store_description')->nullable();
            $table->string('store_address_line1')->nullable();
            $table->string('store_address_line2')->nullable();
            $table->string('store_city')->nullable();
            $table->string('store_state')->nullable();
            $table->string('store_postcode')->nullable();
            $table->string('store_country', 10)->default('IN');
            $table->string('store_email')->nullable();
            $table->string('store_phone')->nullable();
            $table->string('currency', 10)->default('INR');
            $table->string('currency_position', 20)->default('left');
            $table->string('thousand_separator', 5)->default(',');
            $table->string('decimal_separator', 5)->default('.');
            $table->tinyInteger('decimal_places')->default(2);
            // ── Products ─────────────────────────────────────────────────────
            $table->string('weight_unit', 10)->default('kg');
            $table->string('dimension_unit', 10)->default('cm');
            $table->boolean('enable_reviews')->default(true);
            $table->boolean('reviews_verified_only')->default(false);
            $table->boolean('enable_ratings')->default(true);
            $table->string('shop_page_display', 30)->default('products');
            $table->integer('products_per_page')->default(12);
            $table->string('default_product_sorting', 50)->default('menu_order');
            $table->boolean('enable_ajax_add_to_cart')->default(true);
            $table->boolean('enable_wishlist')->default(false);
            $table->boolean('enable_compare')->default(false);
            // ── Shipping ─────────────────────────────────────────────────────
            $table->boolean('enable_shipping')->default(true);
            $table->string('shipping_calculation', 30)->default('per_order');
            $table->boolean('hide_shipping_until_address')->default(false);
            $table->boolean('enable_free_shipping')->default(false);
            $table->decimal('free_shipping_min_amount', 12, 2)->nullable();
            $table->boolean('enable_flat_rate')->default(true);
            $table->decimal('flat_rate_cost', 12, 2)->default(0);
            $table->boolean('enable_local_pickup')->default(false);
            $table->string('local_pickup_address')->nullable();
            $table->json('shipping_zones')->nullable();
            // ── Payments ─────────────────────────────────────────────────────
            $table->boolean('enable_cod')->default(true);
            $table->string('cod_title')->default('Cash on Delivery');
            $table->text('cod_description')->nullable();
            $table->boolean('enable_razorpay')->default(false);
            $table->string('razorpay_key_id')->nullable();
            $table->text('razorpay_key_secret')->nullable(); // encrypted
            $table->boolean('razorpay_test_mode')->default(true);
            $table->boolean('enable_stripe')->default(false);
            $table->string('stripe_publishable_key')->nullable();
            $table->text('stripe_secret_key')->nullable(); // encrypted
            $table->boolean('stripe_test_mode')->default(true);
            $table->boolean('enable_paypal')->default(false);
            $table->string('paypal_email')->nullable();
            $table->boolean('paypal_sandbox')->default(true);
            $table->boolean('enable_bank_transfer')->default(false);
            $table->text('bank_transfer_details')->nullable();
            $table->boolean('enable_upi')->default(false);
            $table->string('upi_id')->nullable();
            // ── Accounts & Privacy ───────────────────────────────────────────
            $table->boolean('allow_guest_checkout')->default(true);
            $table->boolean('allow_account_creation_checkout')->default(true);
            $table->boolean('allow_account_creation_shop')->default(true);
            $table->boolean('auto_generate_username')->default(true);
            $table->boolean('auto_generate_password')->default(false);
            $table->boolean('erasure_request_removes_orders')->default(false);
            $table->boolean('erasure_request_removes_downloads')->default(false);
            $table->boolean('allow_bulk_remove_personal_data')->default(false);
            $table->text('privacy_policy_text')->nullable();
            $table->text('checkout_privacy_policy_text')->nullable();
            $table->text('registration_privacy_policy_text')->nullable();
            // ── Site Visibility ──────────────────────────────────────────────
            $table->string('catalog_visibility', 30)->default('visible');
            $table->boolean('hide_out_of_stock')->default(false);
            $table->string('stock_display_format', 30)->default('always');
            $table->boolean('enable_breadcrumbs')->default(true);
            $table->boolean('enable_lightbox')->default(true);
            $table->boolean('enable_zoom')->default(true);
            $table->boolean('enable_gallery_slider')->default(true);
            $table->boolean('redirect_add_to_cart', )->default(false);
            $table->string('cart_redirect_after_add', 30)->default('same_page');
            // ── Point of Sale (Store Details) ────────────────────────────────
            $table->string('pos_store_name')->nullable();
            $table->string('pos_receipt_header')->nullable();
            $table->text('pos_receipt_footer')->nullable();
            $table->string('pos_tax_number')->nullable();
            $table->boolean('pos_print_receipt_auto')->default(false);
            $table->string('pos_receipt_logo')->nullable();
            $table->boolean('pos_enable_barcode')->default(false);
            $table->string('pos_barcode_field', 30)->default('sku');
            $table->boolean('pos_enable_customer_display')->default(false);
            $table->string('pos_default_customer')->nullable();
            $table->string('pos_tax_display', 20)->default('excl');
            // ── Advanced ─────────────────────────────────────────────────────
            $table->boolean('enable_coupons')->default(true);
            $table->boolean('calc_discounts_sequentially')->default(false);
            $table->boolean('enable_order_notes')->default(true);
            $table->integer('hold_stock_minutes')->default(60);
            $table->boolean('notify_low_stock')->default(true);
            $table->integer('low_stock_threshold')->default(5);
            $table->boolean('notify_no_stock')->default(true);
            $table->integer('no_stock_threshold')->default(0);
            $table->boolean('hide_out_of_stock_items')->default(false);
            $table->string('stock_format', 20)->default('always');
            $table->boolean('enable_taxes')->default(true);
            $table->string('tax_based_on', 30)->default('shipping');
            $table->string('shipping_tax_class', 30)->default('standard');
            $table->boolean('prices_include_tax')->default(false);
            $table->boolean('display_cart_taxes_inline')->default(false);
            $table->string('checkout_page_id')->nullable();
            $table->string('cart_page_id')->nullable();
            $table->string('myaccount_page_id')->nullable();
            $table->string('terms_page_id')->nullable();
            $table->boolean('force_ssl_checkout')->default(true);
            $table->boolean('unforce_ssl_checkout')->default(false);
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecom_store_configs');
    }
};
