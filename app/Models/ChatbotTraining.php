<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotTraining extends Model
{
    protected $table = 'chatbot_training';

    protected $fillable = ['user_id', 'category', 'question', 'answer', 'is_active', 'sort_order'];

    protected $attributes = [
        'category'   => 'general',
        'is_active'  => true,
        'sort_order' => 0,
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
