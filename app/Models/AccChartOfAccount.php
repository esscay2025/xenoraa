<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccChartOfAccount extends Model
{
    use HasFactory;
    protected $table = 'acc_chart_of_accounts';
    protected $fillable = [
        'user_id','tenant_owner_id','code','name','type','sub_type',
        'description','opening_balance','is_system','is_active',
    ];
    protected $casts = ['is_system' => 'boolean', 'is_active' => 'boolean', 'opening_balance' => 'decimal:2'];

    public function transactions() { return $this->hasMany(AccTransaction::class, 'chart_account_id'); }
    public function journalLines() { return $this->hasMany(AccJournalLine::class, 'chart_account_id'); }

    public static function typeLabel(string $type): string
    {
        return match($type) {
            'asset'     => 'Asset',
            'liability' => 'Liability',
            'equity'    => 'Equity',
            'income'    => 'Income',
            'expense'   => 'Expense',
            default     => ucfirst($type),
        };
    }
}
