<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmSalesOrder extends Model
{
    protected $table = 'crm_sales_orders';
    protected $fillable = [
        'user_id', 'account_id', 'contact_id', 'quote_id', 'so_number', 'subject',
        'status', 'delivery_date', 'subtotal', 'discount_amount', 'tax_amount', 'total', 'terms', 'notes',
    ];
    protected $casts = ['delivery_date' => 'date', 'subtotal' => 'decimal:2', 'discount_amount' => 'decimal:2', 'tax_amount' => 'decimal:2', 'total' => 'decimal:2'];

    const STATUSES = ['draft' => 'Draft', 'approved' => 'Approved', 'packing' => 'Packing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'];

    public function tenant()  { return $this->belongsTo(User::class, 'user_id'); }
    public function account() { return $this->belongsTo(CrmAccount::class, 'account_id'); }
    public function contact() { return $this->belongsTo(CrmContact::class, 'contact_id'); }
    public function quote()   { return $this->belongsTo(CrmQuote::class, 'quote_id'); }
    public function items()   { return $this->morphMany(CrmInventoryItem::class, 'itemable'); }
    public function invoices(){ return $this->hasMany(CrmInvoice::class, 'sales_order_id'); }

    public static function generateNumber(int $userId): string
    {
        $count = static::where('user_id', $userId)->count() + 1;
        return 'SO-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
