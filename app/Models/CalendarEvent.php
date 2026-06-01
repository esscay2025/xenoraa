<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'title', 'description',
        'event_date', 'event_time', 'color', 'is_reminder',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_reminder' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
