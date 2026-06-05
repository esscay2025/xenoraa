<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'day_of_week', 'start_time', 'end_time',
        'duration_minutes', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const DAYS = [
        0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday',
        3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDayNameAttribute()
    {
        return self::DAYS[$this->day_of_week] ?? 'Unknown';
    }
}
