<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosOrder extends Model
{
    protected $fillable = [
        'tenant_id', 'session_id', 'cashier_id', 'order_number', 'status',
        'customer_name', 'customer_phone', 'customer_email',
        'subtotal', 'discount_amount', 'discount_type', 'discount_value',
        'tax_rate', 'tax_amount', 'total', 'amount_paid', 'change_due',
        'payment_method', 'cash_paid', 'card_paid', 'upi_paid',
        'upi_reference', 'card_reference', 'notes', 'refund_reason',
    ];

    protected $casts = [
        'subtotal'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_value'  => 'decimal:2',
        'tax_rate'        => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'total'           => 'decimal:2',
        'amount_paid'     => 'decimal:2',
        'change_due'      => 'decimal:2',
        'cash_paid'       => 'decimal:2',
        'card_paid'       => 'decimal:2',
        'upi_paid'        => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PosOrderItem::class, 'pos_order_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(PosSession::class, 'session_id');
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public static function generateNumber(): string
    {
        $last = static::whereDate('created_at', today())->count() + 1;
        return 'POS-' . date('Ymd') . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}
