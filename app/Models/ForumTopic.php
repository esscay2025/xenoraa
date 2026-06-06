<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ForumTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_owner_id', 'user_id', 'title', 'slug', 'body', 'category',
        'tags', 'is_pinned', 'is_locked', 'views',
    ];

    protected $casts = [
        'is_pinned'  => 'boolean',
        'is_locked'  => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class, 'topic_id')->where('is_deleted', false);
    }

    public function allReplies()
    {
        return $this->hasMany(ForumReply::class, 'topic_id');
    }

    public function getTagsArrayAttribute()
    {
        return $this->tags ? array_filter(array_map('trim', explode(',', $this->tags))) : [];
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug . '%')->count();
        return $count ? $slug . '-' . ($count + 1) : $slug;
    }
}
