<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_desc',
        'status',
        'show_in_menu',
        'sort_order',
    ];

    protected $casts = [
        'show_in_menu' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getPublicUrlAttribute(): string
    {
        $username = $this->owner?->username;
        return $username ? url('/' . $username . '/page/' . $this->slug) : '#';
    }
}
