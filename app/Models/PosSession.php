<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosSession extends Model
{
    protected $fillable = [
        'tenant_id', 'cashier_id', 'session_number', 'status',
        'opening_cash', 'closing_cash', 'expected_cash', 'cash_difference',
        'total_orders', 'total_sales', 'total_discount', 'total_tax',
        'notes', 'opened_at', 'closed_at',
    ];

    protected $casts = [
        'opening_cash'    => 'decimal:2',
        'closing_cash'    => 'decimal:2',
        'expected_cash'   => 'decimal:2',
        'cash_difference' => 'decimal:2',
        'total_sales'     => 'decimal:2',
        'total_discount'  => 'decimal:2',
        'total_tax'       => 'decimal:2',
        'opened_at'       => 'datetime',
        'closed_at'       => 'datetime',
    ];

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(PosOrder::class, 'session_id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public static function generateNumber(): string
    {
        return 'SES-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }
}
