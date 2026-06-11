<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmPurchaseOrder extends Model
{
    protected $table = 'crm_purchase_orders';
    protected $fillable = [
        'user_id', 'owner_id', 'vendor_id', 'contact_id',
        'po_number', 'subject', 'status', 'expected_delivery',
        'requisition_no', 'tracking_no', 'po_date', 'carrier',
        'sales_commission', 'excise_duty',
        'subtotal', 'discount_amount', 'tax_amount', 'adjustment', 'grand_total', 'total',
        'line_items', 'terms', 'notes',
        'bill_country', 'bill_building', 'bill_street', 'bill_city', 'bill_state', 'bill_zip',
        'ship_country', 'ship_building', 'ship_street', 'ship_city', 'ship_state', 'ship_zip',
    ];
    protected $casts = [
        'expected_delivery' => 'date',
        'po_date'           => 'date',
        'subtotal'          => 'decimal:2',
        'discount_amount'   => 'decimal:2',
        'tax_amount'        => 'decimal:2',
        'adjustment'        => 'decimal:2',
        'grand_total'       => 'decimal:2',
        'total'             => 'decimal:2',
        'line_items'        => 'array',
    ];

    const STATUSES = ['draft' => 'Draft', 'ordered' => 'Ordered', 'received' => 'Received', 'cancelled' => 'Cancelled'];

    public function tenant()  { return $this->belongsTo(User::class, 'user_id'); }
    public function owner()   { return $this->belongsTo(User::class, 'owner_id'); }
    public function vendor()  { return $this->belongsTo(CrmVendor::class, 'vendor_id'); }
    public function contact() { return $this->belongsTo(CrmContact::class, 'contact_id'); }
    public function items()   { return $this->morphMany(CrmInventoryItem::class, 'itemable'); }

    public static function generateNumber(int $userId): string
    {
        $count = static::where('user_id', $userId)->count() + 1;
        return 'PO-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
