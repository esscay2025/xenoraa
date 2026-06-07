<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentAssignedSubscription extends Model
{
    protected $table = 'agent_assigned_subscriptions';

    protected $fillable = [
        'agent_id', 'customer_user_id', 'allotment_id', 'plan',
        'duration_months', 'starts_at', 'expires_at', 'status',
        'plan_price', 'commission_rate', 'commission_amount', 'commission_status', 'commission_paid_at',
    ];

    protected $casts = [
        'starts_at'           => 'date',
        'expires_at'          => 'date',
        'commission_paid_at'  => 'date',
        'plan_price'          => 'decimal:2',
        'commission_rate'     => 'decimal:2',
        'commission_amount'   => 'decimal:2',
        'duration_months'     => 'integer',
    ];

    // ── Plan prices ────────────────────────────────────────────────────────
    public static array $planPrices = [
        'starter'      => 499,
        'professional' => 999,
        'business'     => 1999,
    ];

    public static function planPrice(string $plan, int $months = 1): float
    {
        return (static::$planPrices[$plan] ?? 499) * $months;
    }

    // ── Relationships ──────────────────────────────────────────────────────

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }

    public function allotment()
    {
        return $this->belongsTo(AgentSubscriptionAllotment::class, 'allotment_id');
    }
}
