<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class EcomStoreConfig extends Model
{
    protected $table = 'ecom_store_configs';

    protected $fillable = [
        'user_id',
        // General
        'store_name', 'store_description', 'store_address_line1', 'store_address_line2',
        'store_city', 'store_state', 'store_postcode', 'store_country',
        'store_email', 'store_phone', 'currency', 'currency_position',
        'thousand_separator', 'decimal_separator', 'decimal_places',
        // Products
        'weight_unit', 'dimension_unit', 'enable_reviews', 'reviews_verified_only',
        'enable_ratings', 'shop_page_display', 'products_per_page',
        'default_product_sorting', 'enable_ajax_add_to_cart', 'enable_wishlist', 'enable_compare',
        // Shipping
        'enable_shipping', 'shipping_calculation', 'hide_shipping_until_address',
        'enable_free_shipping', 'free_shipping_min_amount', 'enable_flat_rate',
        'flat_rate_cost', 'enable_local_pickup', 'local_pickup_address', 'shipping_zones',
        // Payments
        'enable_cod', 'cod_title', 'cod_description',
        'enable_razorpay', 'razorpay_key_id', 'razorpay_key_secret', 'razorpay_test_mode',
        'enable_stripe', 'stripe_publishable_key', 'stripe_secret_key', 'stripe_test_mode',
        'enable_paypal', 'paypal_email', 'paypal_sandbox',
        'enable_bank_transfer', 'bank_transfer_details',
        'enable_upi', 'upi_id',
        // Accounts & Privacy
        'allow_guest_checkout', 'allow_account_creation_checkout', 'allow_account_creation_shop',
        'auto_generate_username', 'auto_generate_password',
        'erasure_request_removes_orders', 'erasure_request_removes_downloads',
        'allow_bulk_remove_personal_data', 'privacy_policy_text',
        'checkout_privacy_policy_text', 'registration_privacy_policy_text',
        // Site Visibility
        'catalog_visibility', 'hide_out_of_stock', 'stock_display_format',
        'enable_breadcrumbs', 'enable_lightbox', 'enable_zoom', 'enable_gallery_slider',
        'redirect_add_to_cart', 'cart_redirect_after_add',
        // POS
        'pos_store_name', 'pos_receipt_header', 'pos_receipt_footer', 'pos_tax_number',
        'pos_print_receipt_auto', 'pos_receipt_logo', 'pos_enable_barcode', 'pos_barcode_field',
        'pos_enable_customer_display', 'pos_default_customer', 'pos_tax_display',
        // Advanced
        'enable_coupons', 'calc_discounts_sequentially', 'enable_order_notes',
        'hold_stock_minutes', 'notify_low_stock', 'low_stock_threshold',
        'notify_no_stock', 'no_stock_threshold', 'hide_out_of_stock_items', 'stock_format',
        'enable_taxes', 'tax_based_on', 'shipping_tax_class', 'prices_include_tax',
        'display_cart_taxes_inline', 'checkout_page_id', 'cart_page_id',
        'myaccount_page_id', 'terms_page_id', 'force_ssl_checkout', 'unforce_ssl_checkout',
        'custom_css', 'custom_js',
    ];

    protected $casts = [
        'enable_reviews'                      => 'boolean',
        'reviews_verified_only'               => 'boolean',
        'enable_ratings'                      => 'boolean',
        'enable_ajax_add_to_cart'             => 'boolean',
        'enable_wishlist'                     => 'boolean',
        'enable_compare'                      => 'boolean',
        'enable_shipping'                     => 'boolean',
        'hide_shipping_until_address'         => 'boolean',
        'enable_free_shipping'                => 'boolean',
        'enable_flat_rate'                    => 'boolean',
        'enable_local_pickup'                 => 'boolean',
        'shipping_zones'                      => 'array',
        'enable_cod'                          => 'boolean',
        'enable_razorpay'                     => 'boolean',
        'razorpay_test_mode'                  => 'boolean',
        'enable_stripe'                       => 'boolean',
        'stripe_test_mode'                    => 'boolean',
        'enable_paypal'                       => 'boolean',
        'paypal_sandbox'                      => 'boolean',
        'enable_bank_transfer'                => 'boolean',
        'enable_upi'                          => 'boolean',
        'allow_guest_checkout'                => 'boolean',
        'allow_account_creation_checkout'     => 'boolean',
        'allow_account_creation_shop'         => 'boolean',
        'auto_generate_username'              => 'boolean',
        'auto_generate_password'              => 'boolean',
        'erasure_request_removes_orders'      => 'boolean',
        'erasure_request_removes_downloads'   => 'boolean',
        'allow_bulk_remove_personal_data'     => 'boolean',
        'hide_out_of_stock'                   => 'boolean',
        'enable_breadcrumbs'                  => 'boolean',
        'enable_lightbox'                     => 'boolean',
        'enable_zoom'                         => 'boolean',
        'enable_gallery_slider'               => 'boolean',
        'redirect_add_to_cart'                => 'boolean',
        'pos_print_receipt_auto'              => 'boolean',
        'pos_enable_barcode'                  => 'boolean',
        'pos_enable_customer_display'         => 'boolean',
        'enable_coupons'                      => 'boolean',
        'calc_discounts_sequentially'         => 'boolean',
        'enable_order_notes'                  => 'boolean',
        'notify_low_stock'                    => 'boolean',
        'notify_no_stock'                     => 'boolean',
        'hide_out_of_stock_items'             => 'boolean',
        'enable_taxes'                        => 'boolean',
        'prices_include_tax'                  => 'boolean',
        'display_cart_taxes_inline'           => 'boolean',
        'force_ssl_checkout'                  => 'boolean',
        'unforce_ssl_checkout'                => 'boolean',
        'decimal_places'                      => 'integer',
        'products_per_page'                   => 'integer',
        'hold_stock_minutes'                  => 'integer',
        'low_stock_threshold'                 => 'integer',
        'no_stock_threshold'                  => 'integer',
    ];

    // Encrypt sensitive payment keys on get/set
    public function getRazorpayKeySecretAttribute($value)
    {
        try { return $value ? Crypt::decryptString($value) : null; } catch (\Exception $e) { return $value; }
    }
    public function setRazorpayKeySecretAttribute($value)
    {
        $this->attributes['razorpay_key_secret'] = $value ? Crypt::encryptString($value) : null;
    }
    public function getStripeSecretKeyAttribute($value)
    {
        try { return $value ? Crypt::decryptString($value) : null; } catch (\Exception $e) { return $value; }
    }
    public function setStripeSecretKeyAttribute($value)
    {
        $this->attributes['stripe_secret_key'] = $value ? Crypt::encryptString($value) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
