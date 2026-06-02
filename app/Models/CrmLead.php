<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmLead extends Model
{
    protected $fillable = [
        'name', 'email', 'mobile', 'user_id', 'source', 'status',
        'priority', 'summary', 'notes', 'assigned_to', 'last_contacted_at',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(CrmRequirement::class, 'lead_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(ChatbotConversation::class, 'lead_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'new'           => 'badge-info',
            'contacted'     => 'badge-primary',
            'qualified'     => 'badge-warning',
            'proposal_sent' => 'badge-purple',
            'won'           => 'badge-success',
            'lost'          => 'badge-danger',
            default         => 'badge-secondary',
        };
    }
}
