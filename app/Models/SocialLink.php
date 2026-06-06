<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'platform', 'url', 'icon_class', 'is_active', 'sort_order'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
