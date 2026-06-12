<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AccJournalLine extends Model
{
    protected $table = 'acc_journal_lines';
    protected $fillable = ['journal_entry_id','chart_account_id','description','debit','credit'];
    protected $casts = ['debit' => 'decimal:2', 'credit' => 'decimal:2'];

    public function journalEntry() { return $this->belongsTo(AccJournalEntry::class, 'journal_entry_id'); }
    public function chartAccount() { return $this->belongsTo(AccChartOfAccount::class, 'chart_account_id'); }
}
