<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'page_type',
        'content',
        'meta_title',
        'meta_desc',
        'status',
        'show_in_menu',
        'sort_order',
    ];

    protected $casts = [
        'show_in_menu' => 'boolean',
    ];

    /**
     * System slugs that map to clean tenant URLs (not /page/{slug}).
     * Keys are slugs, values are the route segment or null for root.
     */
    protected const SYSTEM_SLUGS = [
        'home'           => '',
        'about'          => 'about',
        'blog'           => 'blog',
        'jobs'           => 'jobs',
        'vacancies'      => 'jobs',
        'shop'           => 'shop',
        'contact'        => 'contact',
        'services'       => 'services',
        'solutions'      => 'solutions',
        'portfolio'      => 'portfolio',
        'practice-areas' => 'practice-areas',
        'case-studies'   => 'case-studies',
        'appointments'   => 'appointments',
        'ventures'       => 'ventures',
        'vision'         => 'vision',
        'initiatives'    => 'initiatives',
        'collaborations' => 'collaborations',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Generate the correct public URL for this page.
     *
     * - System slugs (home, about, blog, etc.) → /{username}/{segment} or /{username}
     * - Custom pages → /{username}/page/{slug}
     * - Custom domain tenants → uses the domain root
     */
    public function getPublicUrlAttribute(): string
    {
        $owner = $this->owner;
        if (!$owner) return '#';

        // Determine base URL — use custom domain if available
        $customDomain = $owner->custom_domain ?? null;
        if ($customDomain) {
            $base = 'https://' . $customDomain;
        } else {
            $base = url('/' . $owner->username);
        }

        $slug = $this->slug;

        // System slug → clean URL
        if (array_key_exists($slug, self::SYSTEM_SLUGS)) {
            $segment = self::SYSTEM_SLUGS[$slug];
            if ($segment === '') {
                return $base; // home
            }
            return $base . '/' . $segment;
        }

        // Custom page → /page/{slug}
        if ($customDomain) {
            return $base . '/page/' . $slug;
        }
        return url('/' . $owner->username . '/page/' . $slug);
    }

    /**
     * Scope to published pages only.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to pages that should appear in navigation.
     */
    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true)->orderBy('sort_order');
    }
}
