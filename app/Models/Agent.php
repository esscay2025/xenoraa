<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agents';

    protected $fillable = [
        'user_id', 'agent_code', 'company_name', 'phone', 'city', 'state', 'country',
        'bank_name', 'bank_account_no', 'bank_ifsc', 'pan_number', 'gst_number',
        'commission_rate', 'subscription_quota', 'subscriptions_used', 'status', 'notes',
    ];

    protected $casts = [
        'commission_rate'     => 'decimal:2',
        'subscription_quota'  => 'integer',
        'subscriptions_used'  => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function allotments()
    {
        return $this->hasMany(AgentSubscriptionAllotment::class);
    }

    public function assignedSubscriptions()
    {
        return $this->hasMany(AgentAssignedSubscription::class);
    }

    public function commissionPayouts()
    {
        return $this->hasMany(AgentCommissionPayout::class);
    }

    // ── Computed ───────────────────────────────────────────────────────────

    public function getAvailableQuotaAttribute(): int
    {
        return max(0, $this->subscription_quota - $this->subscriptions_used);
    }

    public function getPendingCommissionAttribute(): float
    {
        return (float) $this->assignedSubscriptions()
            ->where('commission_status', 'pending')
            ->sum('commission_amount');
    }

    public function getTotalCommissionEarnedAttribute(): float
    {
        return (float) $this->assignedSubscriptions()
            ->whereIn('commission_status', ['approved', 'paid'])
            ->sum('commission_amount');
    }

    public function getTotalCommissionPaidAttribute(): float
    {
        return (float) $this->commissionPayouts()->sum('amount');
    }

    public function getActiveSubscribersCountAttribute(): int
    {
        return $this->assignedSubscriptions()->where('status', 'active')->count();
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    public static function generateAgentCode(): string
    {
        $last = static::orderByDesc('id')->first();
        $next = $last ? ($last->id + 1) : 1;
        return 'AGT-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
