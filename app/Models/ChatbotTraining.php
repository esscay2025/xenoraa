<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotTraining extends Model
{
    protected $fillable = ['category', 'question', 'answer', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];
}
