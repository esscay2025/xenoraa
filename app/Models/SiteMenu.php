<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'url',
        'target',
        'icon',
        'parent_id',
        'sort_order',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function children()
    {
        return $this->hasMany(SiteMenu::class, 'parent_id')->orderBy('sort_order');
    }

    public function parent()
    {
        return $this->belongsTo(SiteMenu::class, 'parent_id');
    }
}
