<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentSubscriptionAllotment extends Model
{
    protected $table = 'agent_subscription_allotments';

    protected $fillable = [
        'agent_id', 'assigned_by', 'plan', 'quantity', 'used', 'expires_at', 'notes',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'quantity'   => 'integer',
        'used'       => 'integer',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignedSubscriptions()
    {
        return $this->hasMany(AgentAssignedSubscription::class, 'allotment_id');
    }

    public function getRemainingAttribute(): int
    {
        return max(0, $this->quantity - $this->used);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
