<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccIncome extends Model
{
    use HasFactory;
    protected $table = 'acc_income';
    protected $fillable = [
        'user_id','tenant_owner_id','bank_account_id','income_number','title',
        'category','amount','tax_amount','income_date','customer_name',
        'reference','invoice_id','status','is_recurring','recurring_frequency',
        'next_recurring_date','notes',
    ];
    protected $casts = [
        'amount'               => 'decimal:2',
        'tax_amount'           => 'decimal:2',
        'income_date'          => 'date',
        'next_recurring_date'  => 'date',
        'is_recurring'         => 'boolean',
    ];

    public function bankAccount() { return $this->belongsTo(AccBankAccount::class, 'bank_account_id'); }

    public function getTotalAttribute(): float
    {
        return (float) $this->amount + (float) $this->tax_amount;
    }

    public static function generateNumber(int $userId): string
    {
        $count = static::where('user_id', $userId)->count() + 1;
        return 'INC-' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }

    public static function categories(): array
    {
        return [
            'Service Revenue', 'Product Sales', 'Consulting Fees',
            'Retainer', 'Commission', 'Interest Income',
            'Rental Income', 'Other Income',
        ];
    }
}
