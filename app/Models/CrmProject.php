<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmProject extends Model
{
    protected $table = 'crm_projects';
    protected $fillable = ['user_id', 'account_id', 'deal_id', 'name', 'description', 'start_date', 'end_date', 'status', 'budget', 'cost'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'budget' => 'decimal:2', 'cost' => 'decimal:2'];

    const STATUSES = ['planning' => 'Planning', 'active' => 'Active', 'on_hold' => 'On Hold', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
    const STATUS_COLORS = ['planning' => 'secondary', 'active' => 'success', 'on_hold' => 'warning', 'completed' => 'primary', 'cancelled' => 'danger'];

    public function tenant()  { return $this->belongsTo(User::class, 'user_id'); }
    public function account() { return $this->belongsTo(CrmAccount::class, 'account_id'); }
    public function deal()    { return $this->belongsTo(CrmDeal::class, 'deal_id'); }
    public function tasks()   { return $this->hasMany(CrmProjectTask::class, 'project_id'); }

    public function getProgressAttribute(): int
    {
        $total = $this->tasks()->count();
        if ($total === 0) return 0;
        $done = $this->tasks()->where('status', 'completed')->count();
        return (int) round(($done / $total) * 100);
    }
}
