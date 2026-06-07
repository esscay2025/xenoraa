<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentCommissionPayout extends Model
{
    protected $table = 'agent_commission_payouts';

    protected $fillable = [
        'agent_id', 'processed_by', 'amount', 'payment_method', 'reference_no', 'paid_at', 'notes',
    ];

    protected $casts = [
        'paid_at' => 'date',
        'amount'  => 'decimal:2',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
