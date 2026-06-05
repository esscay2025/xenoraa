<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileLanguage extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'language', 'proficiency', 'sort_order'];

    public const PROFICIENCIES = [
        'basic' => 'Basic',
        'conversational' => 'Conversational',
        'professional' => 'Professional',
        'fluent' => 'Fluent',
        'native' => 'Native',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
