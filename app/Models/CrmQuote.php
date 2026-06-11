<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmQuote extends Model
{
    protected $table = 'crm_quotes';
    protected $fillable = [
        'user_id', 'owner_id', 'account_id', 'contact_id', 'deal_id',
        'quote_number', 'subject', 'stage', 'team', 'carrier', 'valid_until',
        'subtotal', 'discount_amount', 'tax_amount', 'adjustment', 'grand_total', 'total',
        'line_items', 'terms', 'notes',
        'bill_country', 'bill_building', 'bill_street', 'bill_city', 'bill_state', 'bill_zip',
        'ship_country', 'ship_building', 'ship_street', 'ship_city', 'ship_state', 'ship_zip',
    ];
    protected $casts = [
        'valid_until'     => 'date',
        'subtotal'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'adjustment'      => 'decimal:2',
        'grand_total'     => 'decimal:2',
        'total'           => 'decimal:2',
        'line_items'      => 'array',
    ];

    const STAGES = ['draft' => 'Draft', 'negotiation' => 'Negotiation', 'delivered' => 'Delivered', 'accepted' => 'Accepted', 'declined' => 'Declined'];

    public function tenant()  { return $this->belongsTo(User::class, 'user_id'); }
    public function owner()   { return $this->belongsTo(User::class, 'owner_id'); }
    public function account() { return $this->belongsTo(CrmAccount::class, 'account_id'); }
    public function contact() { return $this->belongsTo(CrmContact::class, 'contact_id'); }
    public function deal()    { return $this->belongsTo(CrmDeal::class, 'deal_id'); }
    public function items()   { return $this->morphMany(CrmInventoryItem::class, 'itemable'); }

    public static function generateNumber(int $userId): string
    {
        $count = static::where('user_id', $userId)->count() + 1;
        return 'QT-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
