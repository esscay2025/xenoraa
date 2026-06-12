<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmProjectIssue extends Model
{
    protected $table = 'crm_project_issues';

    protected $fillable = [
        'project_id',
        'task_id',
        'title',
        'description',
        'severity',
        'status',
        'due_date',
        'assigned_to',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(CrmProject::class, 'project_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(CrmProjectTask::class, 'task_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
