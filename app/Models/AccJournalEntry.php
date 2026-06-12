<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccJournalEntry extends Model
{
    use HasFactory;
    protected $table = 'acc_journal_entries';
    protected $fillable = [
        'user_id','tenant_owner_id','journal_number','entry_date',
        'narration','reference','status','total_debit','total_credit',
    ];
    protected $casts = [
        'entry_date'   => 'date',
        'total_debit'  => 'decimal:2',
        'total_credit' => 'decimal:2',
    ];

    public function lines() { return $this->hasMany(AccJournalLine::class, 'journal_entry_id'); }

    public static function generateNumber(int $userId): string
    {
        $count = static::where('user_id', $userId)->count() + 1;
        return 'JE-' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }

    public function recalculateTotals(): void
    {
        $this->total_debit  = $this->lines()->sum('debit');
        $this->total_credit = $this->lines()->sum('credit');
        $this->saveQuietly();
    }

    public function isBalanced(): bool
    {
        return abs((float)$this->total_debit - (float)$this->total_credit) < 0.01;
    }
}
