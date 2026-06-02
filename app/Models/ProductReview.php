<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'reviewer_name', 'reviewer_email',
        'rating', 'title', 'review', 'is_approved',
    ];

    protected $casts = ['is_approved' => 'boolean'];
}
