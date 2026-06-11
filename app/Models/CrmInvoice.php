<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmInvoice extends Model
{
    protected $table = 'crm_invoices';
    protected $fillable = [
        'user_id', 'owner_id', 'account_id', 'contact_id', 'deal_id',
        'sales_order_id', 'purchase_order_id',
        'invoice_number', 'subject', 'status', 'due_date', 'invoice_date',
        'sales_commission', 'excise_duty',
        'subtotal', 'discount_amount', 'tax_amount', 'adjustment', 'grand_total', 'total',
        'amount_paid', 'balance_due', 'line_items', 'terms', 'notes',
        'bill_country', 'bill_building', 'bill_street', 'bill_city', 'bill_state', 'bill_zip',
        'ship_country', 'ship_building', 'ship_street', 'ship_city', 'ship_state', 'ship_zip',
    ];
    protected $casts = [
        'due_date'        => 'date',
        'invoice_date'    => 'date',
        'subtotal'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'adjustment'      => 'decimal:2',
        'grand_total'     => 'decimal:2',
        'total'           => 'decimal:2',
        'amount_paid'     => 'decimal:2',
        'balance_due'     => 'decimal:2',
        'line_items'      => 'array',
    ];

    const STATUSES = ['unpaid' => 'Unpaid', 'partially_paid' => 'Partially Paid', 'paid' => 'Paid', 'overdue' => 'Overdue', 'void' => 'Void'];
    const STATUS_COLORS = ['unpaid' => 'warning', 'partially_paid' => 'info', 'paid' => 'success', 'overdue' => 'danger', 'void' => 'secondary'];

    public function tenant()     { return $this->belongsTo(User::class, 'user_id'); }
    public function owner()      { return $this->belongsTo(User::class, 'owner_id'); }
    public function account()    { return $this->belongsTo(CrmAccount::class, 'account_id'); }
    public function contact()    { return $this->belongsTo(CrmContact::class, 'contact_id'); }
    public function deal()       { return $this->belongsTo(CrmDeal::class, 'deal_id'); }
    public function salesOrder() { return $this->belongsTo(CrmSalesOrder::class, 'sales_order_id'); }
    public function items()      { return $this->morphMany(CrmInventoryItem::class, 'itemable'); }

    public function getBalanceDueAttribute(): float
    {
        return max(0, (float)$this->total - (float)$this->amount_paid);
    }

    public static function generateNumber(int $userId): string
    {
        $count = static::where('user_id', $userId)->count() + 1;
        return 'INV-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
