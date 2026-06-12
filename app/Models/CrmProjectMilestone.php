<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmProjectMilestone extends Model
{
    protected $table = 'crm_project_milestones';

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'target_date',
        'status',
    ];

    protected $casts = [
        'target_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(CrmProject::class, 'project_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(CrmProjectTask::class, 'milestone_id');
    }
}
