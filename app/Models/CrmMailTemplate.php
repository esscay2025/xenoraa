<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CrmMailTemplate extends Model
{
    protected $table = 'crm_mail_templates';

    protected $fillable = [
        'user_id', 'name', 'slug', 'type', 'subject',
        'logo_path', 'primary_color', 'secondary_color', 'font_family',
        'header_text', 'body_html', 'footer_text',
        'show_logo', 'show_footer', 'is_default', 'is_active',
    ];

    protected $casts = [
        'show_logo'   => 'boolean',
        'show_footer' => 'boolean',
        'is_default'  => 'boolean',
        'is_active'   => 'boolean',
    ];

    // Auto-generate slug from name
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Available template types
    public static function types(): array
    {
        return [
            'invoice'        => 'Invoice',
            'quote'          => 'Quote',
            'sales_order'    => 'Sales Order',
            'purchase_order' => 'Purchase Order',
            'general'        => 'General',
            'all_in_one'     => 'All-in-One',
        ];
    }
}
