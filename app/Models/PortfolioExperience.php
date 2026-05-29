<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'role',
        'start_date',
        'end_date',
        'is_current',
        'description'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];
}
