<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'short_description', 'description', 'category_id',
        'price', 'sale_price', 'cost_price', 'sku', 'stock_quantity',
        'manage_stock', 'stock_status', 'featured_image', 'gallery_images',
        'type', 'is_featured', 'is_active', 'weight', 'dimensions',
        'attributes', 'tags', 'meta_title', 'meta_description', 'sort_order',
    ];

    protected $casts = [
        'is_featured'    => 'boolean',
        'is_active'      => 'boolean',
        'manage_stock'   => 'boolean',
        'gallery_images' => 'array',
        'attributes'     => 'array',
        'tags'           => 'array',
        'price'          => 'decimal:2',
        'sale_price'     => 'decimal:2',
        'cost_price'     => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->sale_price && $this->sale_price < $this->price
            ? (float) $this->sale_price
            : (float) $this->price;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->sale_price && $this->sale_price < $this->price && $this->price > 0) {
            return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
