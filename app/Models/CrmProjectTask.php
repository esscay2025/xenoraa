<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmProjectTask extends Model
{
    protected $table = 'crm_project_tasks';
    protected $fillable = ['project_id', 'name', 'description', 'due_date', 'priority', 'status', 'assigned_to'];
    protected $casts = ['due_date' => 'date'];

    const STATUSES = ['todo' => 'To Do', 'in_progress' => 'In Progress', 'testing' => 'Testing', 'completed' => 'Completed'];
    const STATUS_COLORS = ['todo' => 'secondary', 'in_progress' => 'warning', 'testing' => 'info', 'completed' => 'success'];
    const PRIORITIES = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'];

    public function project()  { return $this->belongsTo(CrmProject::class, 'project_id'); }
    public function assignee() { return $this->belongsTo(User::class, 'assigned_to'); }
}
