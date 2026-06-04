<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmDeal extends Model
{
    protected $fillable = [
        'user_id', 'account_id', 'contact_id', 'lead_id', 'title', 'value', 'currency',
        'stage', 'probability', 'expected_close', 'closed_at', 'notes', 'lost_reason',
    ];

    protected $casts = [
        'expected_close' => 'date',
        'closed_at'      => 'date',
        'value'          => 'decimal:2',
    ];

    const STAGES = [
        'prospecting'   => ['label' => 'Prospecting',   'color' => '#6366f1', 'prob' => 10],
        'qualification' => ['label' => 'Qualification', 'color' => '#8b5cf6', 'prob' => 20],
        'proposal'      => ['label' => 'Proposal',      'color' => '#f59e0b', 'prob' => 50],
        'negotiation'   => ['label' => 'Negotiation',   'color' => '#f97316', 'prob' => 75],
        'closed_won'    => ['label' => 'Closed Won',    'color' => '#22c55e', 'prob' => 100],
        'closed_lost'   => ['label' => 'Closed Lost',   'color' => '#ef4444', 'prob' => 0],
    ];

    public function tenant()  { return $this->belongsTo(User::class, 'user_id'); }
    public function account() { return $this->belongsTo(CrmAccount::class, 'account_id'); }
    public function contact() { return $this->belongsTo(CrmContact::class, 'contact_id'); }
    public function lead()    { return $this->belongsTo(CrmLead::class, 'lead_id'); }

    public function getStageLabelAttribute(): string
    {
        return self::STAGES[$this->stage]['label'] ?? ucfirst($this->stage);
    }

    public function getStageColorAttribute(): string
    {
        return self::STAGES[$this->stage]['color'] ?? '#6366f1';
    }
}
