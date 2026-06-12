<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class AccBankAccount extends Model
{
    use HasFactory;
    protected $table = 'acc_bank_accounts';
    protected $fillable = [
        'user_id','tenant_owner_id','name','account_type','bank_name',
        'account_number','ifsc_code','currency','opening_balance',
        'current_balance','opening_date','is_active','notes',
    ];
    protected $casts = [
        'is_active'       => 'boolean',
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'opening_date'    => 'date',
    ];
    public function transactions() { return $this->hasMany(AccTransaction::class, 'bank_account_id'); }
    public function income()       { return $this->hasMany(AccIncome::class, 'bank_account_id'); }
    public function expenses()     { return $this->hasMany(AccExpense::class, 'bank_account_id'); }
    public function getTypeIconAttribute(): string
    {
        return match($this->account_type) {
            'bank'        => 'fa-university',
            'cash'        => 'fa-money-bill-wave',
            'credit_card' => 'fa-credit-card',
            'savings'     => 'fa-piggy-bank',
            'wallet'      => 'fa-wallet',
            default       => 'fa-university',
        };
    }
    public function getTypeLabelAttribute(): string
    {
        return match($this->account_type) {
            'bank'        => 'Bank Account',
            'cash'        => 'Cash',
            'credit_card' => 'Credit Card',
            'savings'     => 'Savings Account',
            'wallet'      => 'Digital Wallet',
            default       => ucfirst($this->account_type),
        };
    }
    /** Recalculate and save current_balance from transactions */
    public function recalculateBalance(): void
    {
        $credits = $this->transactions()->where('type', 'credit')->sum('amount');
        $debits  = $this->transactions()->where('type', 'debit')->sum('amount');
        $this->current_balance = $this->opening_balance + $credits - $debits;
        $this->saveQuietly();
    }
}
