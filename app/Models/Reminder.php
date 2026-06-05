<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'remind_at',
        'type', 'is_sent', 'related_type', 'related_id',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    public const TYPES = [
        'once' => 'One Time',
        'daily' => 'Daily',
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('is_sent', false)
                     ->where('remind_at', '>=', now())
                     ->orderBy('remind_at');
    }

    public function scopeOverdue($query)
    {
        return $query->where('is_sent', false)
                     ->where('remind_at', '<', now());
    }
}
