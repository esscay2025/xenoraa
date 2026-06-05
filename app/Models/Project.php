<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'short_description',
        'client_name', 'project_url', 'technology_used', 'category',
        'featured_image', 'images', 'videos', 'start_date', 'end_date',
        'status', 'is_featured', 'sort_order',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'is_featured' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTechnologiesAttribute()
    {
        return $this->technology_used ? explode(',', $this->technology_used) : [];
    }
}
