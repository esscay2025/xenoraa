<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmPurchaseOrder extends Model
{
    protected $table = 'crm_purchase_orders';
    protected $fillable = [
        'user_id', 'vendor_id', 'po_number', 'subject',
        'status', 'expected_delivery', 'subtotal', 'discount_amount', 'tax_amount', 'total', 'terms', 'notes',
    ];
    protected $casts = ['expected_delivery' => 'date', 'subtotal' => 'decimal:2', 'discount_amount' => 'decimal:2', 'tax_amount' => 'decimal:2', 'total' => 'decimal:2'];

    const STATUSES = ['draft' => 'Draft', 'ordered' => 'Ordered', 'received' => 'Received', 'cancelled' => 'Cancelled'];

    public function tenant() { return $this->belongsTo(User::class, 'user_id'); }
    public function vendor() { return $this->belongsTo(CrmVendor::class, 'vendor_id'); }
    public function items()  { return $this->morphMany(CrmInventoryItem::class, 'itemable'); }

    public static function generateNumber(int $userId): string
    {
        $count = static::where('user_id', $userId)->count() + 1;
        return 'PO-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
