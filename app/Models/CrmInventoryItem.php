<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmInventoryItem extends Model
{
    protected $table = 'crm_inventory_items';
    protected $fillable = [
        'itemable_type', 'itemable_id', 'product_id', 'product_name',
        'quantity', 'unit_price', 'discount_amount', 'tax_rate', 'tax_amount', 'line_total',
    ];
    protected $casts = [
        'unit_price' => 'decimal:2', 'discount_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2', 'tax_amount' => 'decimal:2', 'line_total' => 'decimal:2',
    ];

    public function itemable() { return $this->morphTo(); }
    public function product() { return $this->belongsTo(Product::class, 'product_id'); }
}
