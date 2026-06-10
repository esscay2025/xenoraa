<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CrmProduct extends Model {
    protected $table = 'crm_products';
    protected $fillable = [
        'user_id','owner_id','vendor_id','name','product_code','product_category',
        'manufacturer','is_active','sales_start_date','sales_end_date',
        'support_start_date','support_end_date','unit_price','tax','commission_rate',
        'taxable','usage_unit','box','qty_in_stock','handler','qty_ordered',
        'reorder_level','qty_in_demand','description','image',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'taxable' => 'boolean',
        'unit_price' => 'decimal:2',
        'tax' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'qty_in_stock' => 'integer',
        'reorder_level' => 'integer',
    ];
    public function vendor() { return $this->belongsTo(CrmVendor::class, 'vendor_id'); }
    public function owner() { return $this->belongsTo(\App\Models\User::class, 'owner_id'); }
}
