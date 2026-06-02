<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmRequirement extends Model
{
    protected $fillable = [
        'lead_id', 'requirement', 'category', 'budget_range',
        'timeline', 'pain_points', 'current_tools', 'scope_sent', 'scope_sent_at',
    ];

    protected $casts = [
        'scope_sent'     => 'boolean',
        'scope_sent_at'  => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }
}
