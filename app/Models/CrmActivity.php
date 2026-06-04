<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmActivity extends Model
{
    protected $fillable = [
        'user_id', 'type', 'subject', 'description', 'related_type', 'related_id',
        'due_at', 'completed_at', 'status',
    ];

    protected $casts = [
        'due_at'       => 'datetime',
        'completed_at' => 'datetime',
    ];

    const TYPES = [
        'call'    => ['label' => 'Call',    'icon' => 'fa-phone',        'color' => '#6366f1'],
        'email'   => ['label' => 'Email',   'icon' => 'fa-envelope',     'color' => '#3b82f6'],
        'meeting' => ['label' => 'Meeting', 'icon' => 'fa-calendar-alt', 'color' => '#8b5cf6'],
        'note'    => ['label' => 'Note',    'icon' => 'fa-sticky-note',  'color' => '#f59e0b'],
        'task'    => ['label' => 'Task',    'icon' => 'fa-tasks',        'color' => '#22c55e'],
        'demo'    => ['label' => 'Demo',    'icon' => 'fa-desktop',      'color' => '#f97316'],
    ];

    public function tenant() { return $this->belongsTo(User::class, 'user_id'); }

    public function related()
    {
        return $this->morphTo('related');
    }

    public function getTypeIconAttribute(): string
    {
        return self::TYPES[$this->type]['icon'] ?? 'fa-circle';
    }

    public function getTypeColorAttribute(): string
    {
        return self::TYPES[$this->type]['color'] ?? '#6366f1';
    }
}
