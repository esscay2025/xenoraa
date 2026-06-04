<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmLead extends Model
{
    protected $fillable = [
        // Legacy fields (AI chatbot / old CRM)
        'name', 'email', 'mobile', 'user_id', 'source', 'status',
        'priority', 'summary', 'notes', 'assigned_to', 'last_contacted_at',
        // New CRM fields
        'phone', 'company', 'message', 'account_id', 'contact_id',
        'deal_value', 'tenant_owner_id',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
        'deal_value'        => 'decimal:2',
    ];

    // Accessor: phone falls back to mobile for backward compat
    public function getPhoneAttribute($value): ?string
    {
        return $value ?? $this->attributes['mobile'] ?? null;
    }

    // Accessor: message falls back to summary
    public function getMessageAttribute($value): ?string
    {
        return $value ?? $this->attributes['summary'] ?? null;
    }

    // Normalise status: map legacy 'won' → 'converted', 'proposal_sent' → 'proposal'
    public function getStatusAttribute($value): string
    {
        return match($value) {
            'won'           => 'converted',
            'proposal_sent' => 'proposal',
            default         => $value ?? 'new',
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(CrmAccount::class, 'account_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(CrmContact::class, 'contact_id');
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
            'new'       => 'badge-info',
            'contacted' => 'badge-primary',
            'qualified' => 'badge-warning',
            'proposal'  => 'badge-purple',
            'converted' => 'badge-success',
            'lost'      => 'badge-danger',
            default     => 'badge-secondary',
        };
    }
}
