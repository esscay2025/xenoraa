<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmForecast extends Model
{
    protected $table = 'crm_forecasts';
    protected $fillable = ['user_id', 'year', 'quarter', 'target_amount', 'achieved_amount', 'notes'];
    protected $casts = ['target_amount' => 'decimal:2', 'achieved_amount' => 'decimal:2'];

    public function tenant() { return $this->belongsTo(User::class, 'user_id'); }

    public function getAchievementPercentAttribute(): float
    {
        if ($this->target_amount <= 0) return 0;
        return round(($this->achieved_amount / $this->target_amount) * 100, 1);
    }
}
