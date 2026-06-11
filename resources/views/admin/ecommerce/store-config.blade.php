@extends('layouts.admin')

@section('title', 'Store Config')

@section('content')
<div class="ec-page">

    {{-- Page Header --}}
    <div class="ec-page-header">
        <div>
            <h1 class="ec-page-title"><i class="fas fa-store-alt"></i> Store Config</h1>
            <p class="ec-page-subtitle">Configure your store settings, payments, shipping, and more.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="ec-alert ec-alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="ec-alert ec-alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- WooCommerce-style Settings Layout --}}
    <div class="sc-layout">

        {{-- Left Tab Navigation --}}
        <nav class="sc-nav">
            @php
            $tabs = [
                'general'    => ['icon' => 'fas fa-store',          'label' => 'General'],
                'products'   => ['icon' => 'fas fa-box-open',        'label' => 'Products'],
                'shipping'   => ['icon' => 'fas fa-shipping-fast',   'label' => 'Shipping'],
                'payments'   => ['icon' => 'fas fa-credit-card',     'label' => 'Payments'],
                'accounts'   => ['icon' => 'fas fa-user-shield',     'label' => 'Accounts & Privacy'],
                'visibility' => ['icon' => 'fas fa-eye',             'label' => 'Site Visibility'],
                'pos'        => ['icon' => 'fas fa-cash-register',   'label' => 'Point of Sale'],
                'advanced'   => ['icon' => 'fas fa-cogs',            'label' => 'Advanced'],
            ];
            @endphp
            @foreach($tabs as $key => $info)
            <a href="{{ route('admin.ecommerce.store-config', ['tab' => $key]) }}"
               class="sc-nav-item {{ $tab === $key ? 'active' : '' }}">
                <i class="{{ $info['icon'] }}"></i>
                <span>{{ $info['label'] }}</span>
            </a>
            @endforeach
        </nav>

        {{-- Right Content Panel --}}
        <div class="sc-content">
            <form method="POST" action="{{ route('admin.ecommerce.store-config.save') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tab" value="{{ $tab }}">

                {{-- ══════════════════════════════════════════════════════════ --}}
                {{-- TAB: GENERAL --}}
                {{-- ══════════════════════════════════════════════════════════ --}}
                @if($tab === 'general')
                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-map-marker-alt"></i> Store Address</div>
                    <div class="sc-section-desc">This is where your business is located. Tax rates and shipping rates will use this address.</div>
                    <div class="sc-grid-2">
                        <div class="ec-form-group">
                            <label class="ec-label">Store Name</label>
                            <input type="text" name="store_name" class="ec-input" value="{{ old('store_name', $config->store_name) }}" placeholder="My Online Store">
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Store Email</label>
                            <input type="email" name="store_email" class="ec-input" value="{{ old('store_email', $config->store_email) }}" placeholder="store@example.com">
                        </div>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Store Description</label>
                        <textarea name="store_description" class="ec-input" rows="2" placeholder="Brief description of your store">{{ old('store_description', $config->store_description) }}</textarea>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Address Line 1</label>
                        <input type="text" name="store_address_line1" class="ec-input" value="{{ old('store_address_line1', $config->store_address_line1) }}" placeholder="Street address">
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Address Line 2</label>
                        <input type="text" name="store_address_line2" class="ec-input" value="{{ old('store_address_line2', $config->store_address_line2) }}" placeholder="Apartment, suite, unit etc. (optional)">
                    </div>
                    <div class="sc-grid-3">
                        <div class="ec-form-group">
                            <label class="ec-label">City</label>
                            <input type="text" name="store_city" class="ec-input" value="{{ old('store_city', $config->store_city) }}">
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">State / Province</label>
                            <input type="text" name="store_state" class="ec-input" value="{{ old('store_state', $config->store_state) }}">
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Postcode / ZIP</label>
                            <input type="text" name="store_postcode" class="ec-input" value="{{ old('store_postcode', $config->store_postcode) }}">
                        </div>
                    </div>
                    <div class="sc-grid-2">
                        <div class="ec-form-group">
                            <label class="ec-label">Country</label>
                            <select name="store_country" class="ec-input">
                                @php $countries = ['IN'=>'India','US'=>'United States','GB'=>'United Kingdom','AU'=>'Australia','CA'=>'Canada','SG'=>'Singapore','AE'=>'UAE','DE'=>'Germany','FR'=>'France','JP'=>'Japan','CN'=>'China','BR'=>'Brazil','ZA'=>'South Africa','NG'=>'Nigeria','KE'=>'Kenya']; @endphp
                                @foreach($countries as $code => $name)
                                <option value="{{ $code }}" {{ $config->store_country == $code ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Store Phone</label>
                            <input type="text" name="store_phone" class="ec-input" value="{{ old('store_phone', $config->store_phone) }}" placeholder="+91 98765 43210">
                        </div>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-coins"></i> Currency Options</div>
                    <div class="sc-section-desc">The following options affect how prices are displayed on the frontend.</div>
                    <div class="sc-grid-2">
                        <div class="ec-form-group">
                            <label class="ec-label">Currency</label>
                            <select name="currency" class="ec-input">
                                @php $currencies = ['INR'=>'Indian Rupee (₹)','USD'=>'US Dollar ($)','EUR'=>'Euro (€)','GBP'=>'British Pound (£)','AUD'=>'Australian Dollar (A$)','CAD'=>'Canadian Dollar (C$)','SGD'=>'Singapore Dollar (S$)','AED'=>'UAE Dirham (AED)','JPY'=>'Japanese Yen (¥)','CNY'=>'Chinese Yuan (¥)','BRL'=>'Brazilian Real (R$)','ZAR'=>'South African Rand (R)']; @endphp
                                @foreach($currencies as $code => $name)
                                <option value="{{ $code }}" {{ $config->currency == $code ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Currency Position</label>
                            <select name="currency_position" class="ec-input">
                                <option value="left" {{ $config->currency_position=='left'?'selected':'' }}>Left (₹99)</option>
                                <option value="right" {{ $config->currency_position=='right'?'selected':'' }}>Right (99₹)</option>
                                <option value="left_space" {{ $config->currency_position=='left_space'?'selected':'' }}>Left with space (₹ 99)</option>
                                <option value="right_space" {{ $config->currency_position=='right_space'?'selected':'' }}>Right with space (99 ₹)</option>
                            </select>
                        </div>
                    </div>
                    <div class="sc-grid-3">
                        <div class="ec-form-group">
                            <label class="ec-label">Thousand Separator</label>
                            <input type="text" name="thousand_separator" class="ec-input" value="{{ old('thousand_separator', $config->thousand_separator) }}" maxlength="3">
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Decimal Separator</label>
                            <input type="text" name="decimal_separator" class="ec-input" value="{{ old('decimal_separator', $config->decimal_separator) }}" maxlength="3">
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Number of Decimals</label>
                            <input type="number" name="decimal_places" class="ec-input" value="{{ old('decimal_places', $config->decimal_places) }}" min="0" max="4">
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════════ --}}
                {{-- TAB: PRODUCTS --}}
                {{-- ══════════════════════════════════════════════════════════ --}}
                @elseif($tab === 'products')
                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-ruler-combined"></i> Measurements</div>
                    <div class="sc-section-desc">Define the units used for product weight and dimensions.</div>
                    <div class="sc-grid-2">
                        <div class="ec-form-group">
                            <label class="ec-label">Weight Unit</label>
                            <select name="weight_unit" class="ec-input">
                                <option value="kg" {{ $config->weight_unit=='kg'?'selected':'' }}>kg</option>
                                <option value="g" {{ $config->weight_unit=='g'?'selected':'' }}>g</option>
                                <option value="lbs" {{ $config->weight_unit=='lbs'?'selected':'' }}>lbs</option>
                                <option value="oz" {{ $config->weight_unit=='oz'?'selected':'' }}>oz</option>
                            </select>
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Dimension Unit</label>
                            <select name="dimension_unit" class="ec-input">
                                <option value="cm" {{ $config->dimension_unit=='cm'?'selected':'' }}>cm</option>
                                <option value="m" {{ $config->dimension_unit=='m'?'selected':'' }}>m</option>
                                <option value="mm" {{ $config->dimension_unit=='mm'?'selected':'' }}>mm</option>
                                <option value="in" {{ $config->dimension_unit=='in'?'selected':'' }}>in</option>
                                <option value="yd" {{ $config->dimension_unit=='yd'?'selected':'' }}>yd</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-star"></i> Reviews</div>
                    <div class="sc-section-desc">Control how product reviews and ratings work on your store.</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable product reviews</span>
                            <span class="sc-toggle-desc">Allow customers to post reviews on your products.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_reviews" value="1" {{ $config->enable_reviews ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Show "verified owner" label</span>
                            <span class="sc-toggle-desc">Only show reviews from verified purchasers.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="reviews_verified_only" value="1" {{ $config->reviews_verified_only ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable star ratings</span>
                            <span class="sc-toggle-desc">Allow customers to rate products with stars (1–5).</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_ratings" value="1" {{ $config->enable_ratings ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-th-large"></i> Shop Page</div>
                    <div class="sc-section-desc">Configure how products are displayed on your shop listing page.</div>
                    <div class="sc-grid-2">
                        <div class="ec-form-group">
                            <label class="ec-label">Shop Page Display</label>
                            <select name="shop_page_display" class="ec-input">
                                <option value="products" {{ $config->shop_page_display=='products'?'selected':'' }}>Show products</option>
                                <option value="subcategories" {{ $config->shop_page_display=='subcategories'?'selected':'' }}>Show categories</option>
                                <option value="both" {{ $config->shop_page_display=='both'?'selected':'' }}>Show both</option>
                            </select>
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Products Per Page</label>
                            <input type="number" name="products_per_page" class="ec-input" value="{{ old('products_per_page', $config->products_per_page) }}" min="1" max="100">
                        </div>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Default Product Sorting</label>
                        <select name="default_product_sorting" class="ec-input">
                            <option value="menu_order" {{ $config->default_product_sorting=='menu_order'?'selected':'' }}>Default sorting (custom ordering)</option>
                            <option value="popularity" {{ $config->default_product_sorting=='popularity'?'selected':'' }}>Sort by popularity</option>
                            <option value="rating" {{ $config->default_product_sorting=='rating'?'selected':'' }}>Sort by average rating</option>
                            <option value="date" {{ $config->default_product_sorting=='date'?'selected':'' }}>Sort by latest</option>
                            <option value="price" {{ $config->default_product_sorting=='price'?'selected':'' }}>Sort by price: low to high</option>
                            <option value="price-desc" {{ $config->default_product_sorting=='price-desc'?'selected':'' }}>Sort by price: high to low</option>
                        </select>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable AJAX add to cart</span>
                            <span class="sc-toggle-desc">Enables AJAX add to cart buttons on archives.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_ajax_add_to_cart" value="1" {{ $config->enable_ajax_add_to_cart ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable Wishlist</span>
                            <span class="sc-toggle-desc">Allow customers to save products to a wishlist.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_wishlist" value="1" {{ $config->enable_wishlist ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable Compare</span>
                            <span class="sc-toggle-desc">Allow customers to compare products side by side.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_compare" value="1" {{ $config->enable_compare ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════════ --}}
                {{-- TAB: SHIPPING --}}
                {{-- ══════════════════════════════════════════════════════════ --}}
                @elseif($tab === 'shipping')
                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-shipping-fast"></i> Shipping Options</div>
                    <div class="sc-section-desc">Enable and configure shipping methods for your store.</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable Shipping</span>
                            <span class="sc-toggle-desc">Enable shipping on your store.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_shipping" value="1" {{ $config->enable_shipping ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Shipping Calculation</label>
                        <select name="shipping_calculation" class="ec-input">
                            <option value="per_order" {{ $config->shipping_calculation=='per_order'?'selected':'' }}>Per order</option>
                            <option value="per_item" {{ $config->shipping_calculation=='per_item'?'selected':'' }}>Per item</option>
                            <option value="per_class" {{ $config->shipping_calculation=='per_class'?'selected':'' }}>Per shipping class</option>
                        </select>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Hide shipping costs until address is entered</span>
                            <span class="sc-toggle-desc">Do not show shipping estimates until the customer enters their address.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="hide_shipping_until_address" value="1" {{ $config->hide_shipping_until_address ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-gift"></i> Free Shipping</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable Free Shipping</span>
                            <span class="sc-toggle-desc">Offer free shipping when order meets a minimum amount.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_free_shipping" value="1" {{ $config->enable_free_shipping ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Minimum Order Amount for Free Shipping</label>
                        <input type="number" name="free_shipping_min_amount" class="ec-input" value="{{ old('free_shipping_min_amount', $config->free_shipping_min_amount) }}" min="0" step="0.01" placeholder="0.00">
                        <span class="ec-hint">Leave blank to offer free shipping on all orders.</span>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-tag"></i> Flat Rate Shipping</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable Flat Rate</span>
                            <span class="sc-toggle-desc">Charge a fixed amount per order for shipping.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_flat_rate" value="1" {{ $config->enable_flat_rate ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Flat Rate Cost</label>
                        <input type="number" name="flat_rate_cost" class="ec-input" value="{{ old('flat_rate_cost', $config->flat_rate_cost) }}" min="0" step="0.01" placeholder="0.00">
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-store"></i> Local Pickup</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable Local Pickup</span>
                            <span class="sc-toggle-desc">Allow customers to pick up orders from your store.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_local_pickup" value="1" {{ $config->enable_local_pickup ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Pickup Address</label>
                        <input type="text" name="local_pickup_address" class="ec-input" value="{{ old('local_pickup_address', $config->local_pickup_address) }}" placeholder="123 Main St, City, State">
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════════ --}}
                {{-- TAB: PAYMENTS --}}
                {{-- ══════════════════════════════════════════════════════════ --}}
                @elseif($tab === 'payments')
                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-money-bill-wave"></i> Cash on Delivery</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable Cash on Delivery</span>
                            <span class="sc-toggle-desc">Allow customers to pay when they receive the order.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_cod" value="1" {{ $config->enable_cod ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-grid-2">
                        <div class="ec-form-group">
                            <label class="ec-label">Payment Title</label>
                            <input type="text" name="cod_title" class="ec-input" value="{{ old('cod_title', $config->cod_title) }}" placeholder="Cash on Delivery">
                        </div>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Description</label>
                        <textarea name="cod_description" class="ec-input" rows="2" placeholder="Pay with cash upon delivery.">{{ old('cod_description', $config->cod_description) }}</textarea>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-bolt"></i> Razorpay</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable Razorpay</span>
                            <span class="sc-toggle-desc">Accept payments via Razorpay (UPI, Cards, NetBanking, Wallets).</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_razorpay" value="1" {{ $config->enable_razorpay ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Test Mode</span>
                            <span class="sc-toggle-desc">Use Razorpay test credentials. Disable for live payments.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="razorpay_test_mode" value="1" {{ $config->razorpay_test_mode ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-grid-2">
                        <div class="ec-form-group">
                            <label class="ec-label">Key ID</label>
                            <input type="text" name="razorpay_key_id" class="ec-input" value="{{ old('razorpay_key_id', $config->razorpay_key_id) }}" placeholder="rzp_test_...">
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Key Secret</label>
                            <input type="password" name="razorpay_key_secret" class="ec-input" value="{{ old('razorpay_key_secret', $config->razorpay_key_secret) }}" placeholder="••••••••••••••••" autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fab fa-stripe-s"></i> Stripe</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable Stripe</span>
                            <span class="sc-toggle-desc">Accept credit/debit card payments via Stripe.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_stripe" value="1" {{ $config->enable_stripe ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Test Mode</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="stripe_test_mode" value="1" {{ $config->stripe_test_mode ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-grid-2">
                        <div class="ec-form-group">
                            <label class="ec-label">Publishable Key</label>
                            <input type="text" name="stripe_publishable_key" class="ec-input" value="{{ old('stripe_publishable_key', $config->stripe_publishable_key) }}" placeholder="pk_test_...">
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Secret Key</label>
                            <input type="password" name="stripe_secret_key" class="ec-input" value="{{ old('stripe_secret_key', $config->stripe_secret_key) }}" placeholder="••••••••••••••••" autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fab fa-paypal"></i> PayPal</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable PayPal</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_paypal" value="1" {{ $config->enable_paypal ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Sandbox Mode</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="paypal_sandbox" value="1" {{ $config->paypal_sandbox ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">PayPal Email</label>
                        <input type="email" name="paypal_email" class="ec-input" value="{{ old('paypal_email', $config->paypal_email) }}" placeholder="paypal@example.com">
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-university"></i> Bank Transfer (NEFT / RTGS / IMPS)</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable Bank Transfer</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_bank_transfer" value="1" {{ $config->enable_bank_transfer ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Bank Account Details</label>
                        <textarea name="bank_transfer_details" class="ec-input" rows="4" placeholder="Bank Name: HDFC Bank&#10;Account Name: My Store&#10;Account Number: 1234567890&#10;IFSC: HDFC0001234">{{ old('bank_transfer_details', $config->bank_transfer_details) }}</textarea>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-qrcode"></i> UPI</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable UPI</span>
                            <span class="sc-toggle-desc">Accept payments via UPI (Google Pay, PhonePe, Paytm, etc.).</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_upi" value="1" {{ $config->enable_upi ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">UPI ID</label>
                        <input type="text" name="upi_id" class="ec-input" value="{{ old('upi_id', $config->upi_id) }}" placeholder="yourname@upi">
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════════ --}}
                {{-- TAB: ACCOUNTS & PRIVACY --}}
                {{-- ══════════════════════════════════════════════════════════ --}}
                @elseif($tab === 'accounts')
                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-user-cog"></i> Guest Checkout</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Allow guest checkout</span>
                            <span class="sc-toggle-desc">Allow customers to place orders without creating an account.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="allow_guest_checkout" value="1" {{ $config->allow_guest_checkout ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-user-plus"></i> Account Creation</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Allow account creation during checkout</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="allow_account_creation_checkout" value="1" {{ $config->allow_account_creation_checkout ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Allow account creation on shop page</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="allow_account_creation_shop" value="1" {{ $config->allow_account_creation_shop ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Auto-generate username</span>
                            <span class="sc-toggle-desc">Automatically generate a username from the customer's name/email.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="auto_generate_username" value="1" {{ $config->auto_generate_username ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Auto-generate password</span>
                            <span class="sc-toggle-desc">Automatically generate a strong password for new accounts.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="auto_generate_password" value="1" {{ $config->auto_generate_password ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-shield-alt"></i> Personal Data Erasure</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Remove personal data from orders on request</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="erasure_request_removes_orders" value="1" {{ $config->erasure_request_removes_orders ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Remove access to downloads on request</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="erasure_request_removes_downloads" value="1" {{ $config->erasure_request_removes_downloads ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Allow bulk personal data removal</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="allow_bulk_remove_personal_data" value="1" {{ $config->allow_bulk_remove_personal_data ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-file-alt"></i> Privacy Policy</div>
                    <div class="ec-form-group">
                        <label class="ec-label">Privacy Policy Page Text (shown on registration)</label>
                        <textarea name="registration_privacy_policy_text" class="ec-input" rows="3" placeholder="Your personal data will be used to support your experience throughout this website...">{{ old('registration_privacy_policy_text', $config->registration_privacy_policy_text) }}</textarea>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Checkout Privacy Policy Text</label>
                        <textarea name="checkout_privacy_policy_text" class="ec-input" rows="3" placeholder="Your personal data will be used to process your order...">{{ old('checkout_privacy_policy_text', $config->checkout_privacy_policy_text) }}</textarea>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════════ --}}
                {{-- TAB: SITE VISIBILITY --}}
                {{-- ══════════════════════════════════════════════════════════ --}}
                @elseif($tab === 'visibility')
                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-eye"></i> Catalog Visibility</div>
                    <div class="ec-form-group">
                        <label class="ec-label">Catalog Visibility</label>
                        <select name="catalog_visibility" class="ec-input">
                            <option value="visible" {{ $config->catalog_visibility=='visible'?'selected':'' }}>Shop and search results</option>
                            <option value="catalog" {{ $config->catalog_visibility=='catalog'?'selected':'' }}>Shop only</option>
                            <option value="search" {{ $config->catalog_visibility=='search'?'selected':'' }}>Search results only</option>
                            <option value="hidden" {{ $config->catalog_visibility=='hidden'?'selected':'' }}>Hidden</option>
                        </select>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Hide out of stock items from catalog</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="hide_out_of_stock" value="1" {{ $config->hide_out_of_stock ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Stock Display Format</label>
                        <select name="stock_display_format" class="ec-input">
                            <option value="always" {{ $config->stock_display_format=='always'?'selected':'' }}>Always show quantity remaining in stock</option>
                            <option value="low_amount" {{ $config->stock_display_format=='low_amount'?'selected':'' }}>Only show when low in stock</option>
                            <option value="no_amount" {{ $config->stock_display_format=='no_amount'?'selected':'' }}>Never show quantity</option>
                        </select>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-images"></i> Product Images</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable product image zoom</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_zoom" value="1" {{ $config->enable_zoom ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable product gallery lightbox</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_lightbox" value="1" {{ $config->enable_lightbox ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable gallery image slider</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_gallery_slider" value="1" {{ $config->enable_gallery_slider ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-shopping-cart"></i> Add to Cart Behaviour</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Redirect to cart after add to cart</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="redirect_add_to_cart" value="1" {{ $config->redirect_add_to_cart ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">After Adding to Cart, Redirect To</label>
                        <select name="cart_redirect_after_add" class="ec-input">
                            <option value="same_page" {{ $config->cart_redirect_after_add=='same_page'?'selected':'' }}>Stay on the same page</option>
                            <option value="cart" {{ $config->cart_redirect_after_add=='cart'?'selected':'' }}>Cart page</option>
                            <option value="checkout" {{ $config->cart_redirect_after_add=='checkout'?'selected':'' }}>Checkout page</option>
                        </select>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable breadcrumbs</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_breadcrumbs" value="1" {{ $config->enable_breadcrumbs ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════════ --}}
                {{-- TAB: POINT OF SALE --}}
                {{-- ══════════════════════════════════════════════════════════ --}}
                @elseif($tab === 'pos')
                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-store"></i> POS Store Details</div>
                    <div class="sc-section-desc">Configure the details that appear on POS receipts and the POS terminal.</div>
                    <div class="sc-grid-2">
                        <div class="ec-form-group">
                            <label class="ec-label">POS Store Name</label>
                            <input type="text" name="pos_store_name" class="ec-input" value="{{ old('pos_store_name', $config->pos_store_name) }}" placeholder="My Store POS">
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Tax / GST Number</label>
                            <input type="text" name="pos_tax_number" class="ec-input" value="{{ old('pos_tax_number', $config->pos_tax_number) }}" placeholder="27AABCU9603R1ZX">
                        </div>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Receipt Header</label>
                        <input type="text" name="pos_receipt_header" class="ec-input" value="{{ old('pos_receipt_header', $config->pos_receipt_header) }}" placeholder="Thank you for shopping with us!">
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Receipt Footer</label>
                        <textarea name="pos_receipt_footer" class="ec-input" rows="3" placeholder="Visit us again! | support@mystore.com | www.mystore.com">{{ old('pos_receipt_footer', $config->pos_receipt_footer) }}</textarea>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Receipt Logo</label>
                        @if($config->pos_receipt_logo)
                        <div style="margin-bottom:8px;"><img src="{{ asset('storage/'.$config->pos_receipt_logo) }}" style="max-height:60px;border-radius:6px;border:1px solid var(--border);" alt="Receipt Logo"></div>
                        @endif
                        <input type="file" name="pos_receipt_logo" class="ec-input" accept="image/*">
                        <span class="ec-hint">Recommended: 200×60px PNG with transparent background.</span>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-print"></i> Receipt Settings</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Auto-print receipt after sale</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="pos_print_receipt_auto" value="1" {{ $config->pos_print_receipt_auto ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Tax Display on Receipt</label>
                        <select name="pos_tax_display" class="ec-input">
                            <option value="excl" {{ $config->pos_tax_display=='excl'?'selected':'' }}>Excluding tax</option>
                            <option value="incl" {{ $config->pos_tax_display=='incl'?'selected':'' }}>Including tax</option>
                        </select>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-barcode"></i> Barcode Scanner</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable barcode scanning</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="pos_enable_barcode" value="1" {{ $config->pos_enable_barcode ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Barcode Field</label>
                        <select name="pos_barcode_field" class="ec-input">
                            <option value="sku" {{ $config->pos_barcode_field=='sku'?'selected':'' }}>SKU</option>
                            <option value="id" {{ $config->pos_barcode_field=='id'?'selected':'' }}>Product ID</option>
                            <option value="barcode" {{ $config->pos_barcode_field=='barcode'?'selected':'' }}>Custom Barcode Field</option>
                        </select>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-desktop"></i> Customer Display</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable customer-facing display</span>
                            <span class="sc-toggle-desc">Show a second screen to the customer during checkout.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="pos_enable_customer_display" value="1" {{ $config->pos_enable_customer_display ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Default Customer Name</label>
                        <input type="text" name="pos_default_customer" class="ec-input" value="{{ old('pos_default_customer', $config->pos_default_customer) }}" placeholder="Walk-in Customer">
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════════ --}}
                {{-- TAB: ADVANCED --}}
                {{-- ══════════════════════════════════════════════════════════ --}}
                @elseif($tab === 'advanced')
                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-tag"></i> Coupons</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable coupons</span>
                            <span class="sc-toggle-desc">Allow customers to apply coupon codes at checkout.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_coupons" value="1" {{ $config->enable_coupons ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Calculate coupon discounts sequentially</span>
                            <span class="sc-toggle-desc">Apply discounts one after another rather than on the original price.</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="calc_discounts_sequentially" value="1" {{ $config->calc_discounts_sequentially ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-boxes"></i> Inventory</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable order notes</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_order_notes" value="1" {{ $config->enable_order_notes ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Hold Stock (minutes)</label>
                        <input type="number" name="hold_stock_minutes" class="ec-input" value="{{ old('hold_stock_minutes', $config->hold_stock_minutes) }}" min="0">
                        <span class="ec-hint">Hold stock for unpaid orders. 0 = disable.</span>
                    </div>
                    <div class="sc-grid-2">
                        <div>
                            <div class="sc-toggle-row">
                                <div class="sc-toggle-info">
                                    <span class="sc-toggle-label">Notify when low on stock</span>
                                </div>
                                <label class="ec-toggle"><input type="checkbox" name="notify_low_stock" value="1" {{ $config->notify_low_stock ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                            </div>
                            <div class="ec-form-group">
                                <label class="ec-label">Low Stock Threshold</label>
                                <input type="number" name="low_stock_threshold" class="ec-input" value="{{ old('low_stock_threshold', $config->low_stock_threshold) }}" min="0">
                            </div>
                        </div>
                        <div>
                            <div class="sc-toggle-row">
                                <div class="sc-toggle-info">
                                    <span class="sc-toggle-label">Notify when out of stock</span>
                                </div>
                                <label class="ec-toggle"><input type="checkbox" name="notify_no_stock" value="1" {{ $config->notify_no_stock ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                            </div>
                            <div class="ec-form-group">
                                <label class="ec-label">Out of Stock Threshold</label>
                                <input type="number" name="no_stock_threshold" class="ec-input" value="{{ old('no_stock_threshold', $config->no_stock_threshold) }}" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Hide out of stock items from catalog</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="hide_out_of_stock_items" value="1" {{ $config->hide_out_of_stock_items ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-percent"></i> Tax</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Enable taxes</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="enable_taxes" value="1" {{ $config->enable_taxes ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Prices entered with tax</span>
                            <span class="sc-toggle-desc">Are product prices entered inclusive of tax?</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="prices_include_tax" value="1" {{ $config->prices_include_tax ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                    <div class="sc-grid-2">
                        <div class="ec-form-group">
                            <label class="ec-label">Calculate Tax Based On</label>
                            <select name="tax_based_on" class="ec-input">
                                <option value="shipping" {{ $config->tax_based_on=='shipping'?'selected':'' }}>Customer shipping address</option>
                                <option value="billing" {{ $config->tax_based_on=='billing'?'selected':'' }}>Customer billing address</option>
                                <option value="base" {{ $config->tax_based_on=='base'?'selected':'' }}>Store base address</option>
                            </select>
                        </div>
                        <div class="ec-form-group">
                            <label class="ec-label">Shipping Tax Class</label>
                            <select name="shipping_tax_class" class="ec-input">
                                <option value="standard" {{ $config->shipping_tax_class=='standard'?'selected':'' }}>Standard</option>
                                <option value="reduced" {{ $config->shipping_tax_class=='reduced'?'selected':'' }}>Reduced Rate</option>
                                <option value="zero" {{ $config->shipping_tax_class=='zero'?'selected':'' }}>Zero Rate</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-lock"></i> Checkout</div>
                    <div class="sc-toggle-row">
                        <div class="sc-toggle-info">
                            <span class="sc-toggle-label">Force secure checkout (HTTPS)</span>
                        </div>
                        <label class="ec-toggle"><input type="checkbox" name="force_ssl_checkout" value="1" {{ $config->force_ssl_checkout ? 'checked' : '' }}><span class="ec-toggle-slider"></span></label>
                    </div>
                </div>

                <div class="sc-section">
                    <div class="sc-section-title"><i class="fas fa-code"></i> Custom Code</div>
                    <div class="ec-form-group">
                        <label class="ec-label">Custom CSS</label>
                        <textarea name="custom_css" class="ec-input ec-code" rows="6" placeholder="/* Add custom CSS for your store frontend */">{{ old('custom_css', $config->custom_css) }}</textarea>
                    </div>
                    <div class="ec-form-group">
                        <label class="ec-label">Custom JavaScript</label>
                        <textarea name="custom_js" class="ec-input ec-code" rows="6" placeholder="// Add custom JavaScript for your store frontend">{{ old('custom_js', $config->custom_js) }}</textarea>
                    </div>
                </div>
                @endif

                {{-- Save Button --}}
                <div class="sc-save-bar">
                    <button type="submit" class="ec-btn ec-btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <span class="sc-save-hint">Changes will apply to your store immediately.</span>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
/* ── Store Config Layout ──────────────────────────────────────── */
.sc-layout {
    display: flex;
    gap: 0;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    min-height: 600px;
}

/* Left Nav */
.sc-nav {
    width: 220px;
    min-width: 220px;
    background: var(--bg-secondary);
    border-right: 1px solid var(--border);
    padding: 8px 0;
    display: flex;
    flex-direction: column;
}
.sc-nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 20px;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    border-left: 3px solid transparent;
    transition: all 0.15s;
}
.sc-nav-item i {
    width: 16px;
    text-align: center;
    font-size: 0.85rem;
    opacity: 0.7;
}
.sc-nav-item:hover {
    background: var(--bg-card);
    color: var(--text-primary);
}
.sc-nav-item.active {
    background: var(--bg-card);
    color: var(--accent);
    border-left-color: var(--accent);
    font-weight: 600;
}
.sc-nav-item.active i { opacity: 1; }

/* Right Content */
.sc-content {
    flex: 1;
    padding: 28px 32px;
    overflow-y: auto;
}

/* Section */
.sc-section {
    margin-bottom: 28px;
    padding-bottom: 28px;
    border-bottom: 1px solid var(--border);
}
.sc-section:last-of-type { border-bottom: none; }
.sc-section-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.sc-section-title i {
    color: var(--accent);
    font-size: 0.9rem;
}
.sc-section-desc {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 16px;
}

/* Grids */
.sc-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.sc-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

/* Toggle Row */
.sc-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    gap: 16px;
}
.sc-toggle-row:last-of-type { border-bottom: none; }
.sc-toggle-info { flex: 1; }
.sc-toggle-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
}
.sc-toggle-desc {
    display: block;
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-top: 2px;
}

/* Save Bar */
.sc-save-bar {
    display: flex;
    align-items: center;
    gap: 16px;
    padding-top: 20px;
    margin-top: 8px;
    border-top: 1px solid var(--border);
}
.sc-save-hint {
    font-size: 0.78rem;
    color: var(--text-muted);
}

/* Code textarea */
.ec-code {
    font-family: 'Fira Code', 'Courier New', monospace;
    font-size: 0.82rem;
}

/* Responsive */
@media (max-width: 768px) {
    .sc-layout { flex-direction: column; }
    .sc-nav { width: 100%; flex-direction: row; overflow-x: auto; border-right: none; border-bottom: 1px solid var(--border); padding: 0; }
    .sc-nav-item { padding: 10px 14px; border-left: none; border-bottom: 3px solid transparent; white-space: nowrap; }
    .sc-nav-item.active { border-left-color: transparent; border-bottom-color: var(--accent); }
    .sc-content { padding: 20px; }
    .sc-grid-2, .sc-grid-3 { grid-template-columns: 1fr; }
}
</style>
@endsection
