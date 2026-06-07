<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmService extends Model
{
    protected $table = 'crm_services';
    protected $fillable = ['user_id', 'name', 'description', 'price', 'duration_minutes', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'price' => 'decimal:2'];

    public function tenant()   { return $this->belongsTo(User::class, 'user_id'); }
    public function bookings() { return $this->hasMany(CrmServiceBooking::class, 'service_id'); }

    public function getDurationLabelAttribute(): string
    {
        $h = intdiv($this->duration_minutes, 60);
        $m = $this->duration_minutes % 60;
        if ($h > 0 && $m > 0) return "{$h}h {$m}m";
        if ($h > 0) return "{$h}h";
        return "{$m}m";
    }
}
