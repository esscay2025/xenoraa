<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'file_path', 'file_name',
        'file_type', 'file_size', 'category', 'is_public',
        'download_count', 'sort_order',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'file_size' => 'integer',
        'download_count' => 'integer',
    ];

    public const CATEGORIES = [
        'brochure' => 'Brochure',
        'company_profile' => 'Company Profile',
        'resume' => 'Resume / CV',
        'product_catalog' => 'Product Catalog',
        'certificate' => 'Certificate',
        'other' => 'Other',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
