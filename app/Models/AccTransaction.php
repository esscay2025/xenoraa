<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccTransaction extends Model
{
    use HasFactory;
    protected $table = 'acc_transactions';
    protected $fillable = [
        'user_id','tenant_owner_id','bank_account_id','chart_account_id',
        'reference_number','type','amount','transaction_date','description',
        'category','payee','source','source_id','is_reconciled','notes',
    ];
    protected $casts = [
        'amount'           => 'decimal:2',
        'transaction_date' => 'date',
        'is_reconciled'    => 'boolean',
    ];

    public function bankAccount()  { return $this->belongsTo(AccBankAccount::class, 'bank_account_id'); }
    public function chartAccount() { return $this->belongsTo(AccChartOfAccount::class, 'chart_account_id'); }

    public static function generateReference(int $userId): string
    {
        $count = static::where('user_id', $userId)->count() + 1;
        return 'TXN-' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }
}
