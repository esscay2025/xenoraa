<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmProjectTimeLog extends Model
{
    protected $table = 'crm_project_time_logs';

    protected $fillable = [
        'project_id',
        'task_id',
        'logged_by',
        'log_date',
        'hours',
        'notes',
    ];

    protected $casts = [
        'log_date' => 'date',
        'hours'    => 'float',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(CrmProject::class, 'project_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(CrmProjectTask::class, 'task_id');
    }

    public function logger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_by');
    }
}
