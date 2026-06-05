<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'display_name', 'designation', 'company',
        'email', 'phone', 'whatsapp', 'website', 'address',
        'photo', 'logo', 'theme_color', 'social_links',
        'qr_code_path', 'is_active',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_active' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getShareUrlAttribute()
    {
        $user = $this->owner;
        if ($user && $user->custom_domain) {
            return 'https://' . $user->custom_domain . '/card';
        }
        return url('/' . ($user->username ?? $user->id) . '/card');
    }
}
