<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaItem extends Model
{
    use HasFactory;

    protected $table = 'media_gallery';

    protected $fillable = [
        'user_id', 'title', 'description', 'type', 'file_path',
        'video_url', 'thumbnail', 'album', 'is_public', 'sort_order',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->whereIn('type', ['video', 'youtube']);
    }

    public function getYoutubeThumbnailAttribute()
    {
        if ($this->type !== 'youtube' || !$this->video_url) return null;
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->video_url, $matches);
        return isset($matches[1]) ? "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg" : null;
    }
}
