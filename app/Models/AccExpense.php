<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccExpense extends Model
{
    use HasFactory;
    protected $table = 'acc_expenses';
    protected $fillable = [
        'user_id','tenant_owner_id','bank_account_id','expense_number','title',
        'category','amount','tax_amount','expense_date','vendor_name',
        'reference','purchase_order_id','is_billable','billable_project_id',
        'receipt_path','status','is_recurring','recurring_frequency',
        'next_recurring_date','notes',
    ];
    protected $casts = [
        'amount'              => 'decimal:2',
        'tax_amount'          => 'decimal:2',
        'expense_date'        => 'date',
        'next_recurring_date' => 'date',
        'is_billable'         => 'boolean',
        'is_recurring'        => 'boolean',
    ];

    public function bankAccount() { return $this->belongsTo(AccBankAccount::class, 'bank_account_id'); }

    public function getTotalAttribute(): float
    {
        return (float) $this->amount + (float) $this->tax_amount;
    }

    public static function generateNumber(int $userId): string
    {
        $count = static::where('user_id', $userId)->count() + 1;
        return 'EXP-' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }

    public static function categories(): array
    {
        return [
            'Salaries & Wages', 'Rent & Utilities', 'Software & Subscriptions',
            'Marketing & Advertising', 'Travel & Transport', 'Office Supplies',
            'Professional Services', 'Equipment & Hardware', 'Insurance',
            'Bank Charges', 'Taxes & Duties', 'Miscellaneous',
        ];
    }
}
