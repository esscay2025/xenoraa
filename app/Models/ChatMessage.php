<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message', 'channel', 'is_deleted'];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_deleted', false);
    }

    public function scopeChannel($query, $channel = 'general')
    {
        return $query->where('channel', $channel);
    }
}
