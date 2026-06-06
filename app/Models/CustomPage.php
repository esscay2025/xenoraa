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
        'sections',
        'meta_title',
        'meta_desc',
        'status',
        'show_in_menu',
        'sort_order',
    ];

    protected $casts = [
        'show_in_menu' => 'boolean',
        'sections'     => 'array',
    ];

    /**
     * System slugs that map to clean tenant URLs (not /page/{slug}).
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
     * Default section definitions per page_type.
     * Each section: key, label, icon, enabled (default), data (editable fields).
     */
    public static function defaultSections(string $pageType): array
    {
        return match ($pageType) {
            'home' => [
                ['key' => 'hero',         'label' => 'Hero / Banner',       'icon' => 'fas fa-image',          'enabled' => true,  'data' => ['heading' => '', 'subheading' => '', 'cta_text' => 'Get Started', 'cta_url' => '', 'image' => '', 'bg_color' => '']],
                ['key' => 'about',        'label' => 'About Snippet',       'icon' => 'fas fa-user',           'enabled' => true,  'data' => ['heading' => 'About Me', 'text' => '', 'image' => '']],
                ['key' => 'services',     'label' => 'Services / Features', 'icon' => 'fas fa-cogs',           'enabled' => true,  'data' => ['heading' => 'What I Do', 'subheading' => '']],
                ['key' => 'blog',         'label' => 'Latest Blog Posts',   'icon' => 'fas fa-newspaper',      'enabled' => true,  'data' => ['heading' => 'Latest Articles', 'count' => 3]],
                ['key' => 'portfolio',    'label' => 'Portfolio Preview',   'icon' => 'fas fa-briefcase',      'enabled' => false, 'data' => ['heading' => 'My Work', 'count' => 6]],
                ['key' => 'shop',         'label' => 'Shop / Products',     'icon' => 'fas fa-shopping-bag',   'enabled' => false, 'data' => ['heading' => 'Shop', 'count' => 4]],
                ['key' => 'forum',        'label' => 'Community Forum',     'icon' => 'fas fa-comments',       'enabled' => false, 'data' => ['heading' => 'Community']],
                ['key' => 'testimonials', 'label' => 'Testimonials',        'icon' => 'fas fa-quote-left',     'enabled' => false, 'data' => ['heading' => 'What People Say']],
                ['key' => 'jobs',         'label' => 'Job Openings',        'icon' => 'fas fa-briefcase',      'enabled' => false, 'data' => ['heading' => 'Open Positions', 'count' => 3]],
                ['key' => 'contact',      'label' => 'Contact / CTA',       'icon' => 'fas fa-envelope',       'enabled' => true,  'data' => ['heading' => "Let's Connect", 'text' => '']],
            ],
            'about' => [
                ['key' => 'hero',           'label' => 'About Hero',           'icon' => 'fas fa-image',          'enabled' => true,  'data' => ['heading' => 'About Me', 'subheading' => '', 'image' => '']],
                ['key' => 'bio',            'label' => 'Biography / Story',    'icon' => 'fas fa-align-left',     'enabled' => true,  'data' => ['heading' => 'My Story', 'text' => '', 'image' => '']],
                ['key' => 'skills',         'label' => 'Skills & Expertise',   'icon' => 'fas fa-bolt',           'enabled' => true,  'data' => ['heading' => 'Skills']],
                ['key' => 'experience',     'label' => 'Work Experience',      'icon' => 'fas fa-briefcase',      'enabled' => true,  'data' => ['heading' => 'Experience']],
                ['key' => 'education',      'label' => 'Education',            'icon' => 'fas fa-graduation-cap', 'enabled' => true,  'data' => ['heading' => 'Education']],
                ['key' => 'certifications', 'label' => 'Certifications',       'icon' => 'fas fa-certificate',    'enabled' => false, 'data' => ['heading' => 'Certifications']],
                ['key' => 'languages',      'label' => 'Languages',            'icon' => 'fas fa-language',       'enabled' => false, 'data' => ['heading' => 'Languages']],
                ['key' => 'social',         'label' => 'Social Links',         'icon' => 'fas fa-share-alt',      'enabled' => true,  'data' => ['heading' => 'Connect']],
            ],
            'blog' => [
                ['key' => 'hero',       'label' => 'Blog Hero / Banner',   'icon' => 'fas fa-image',    'enabled' => true,  'data' => ['heading' => 'Blog', 'subheading' => '']],
                ['key' => 'categories', 'label' => 'Category Filter Bar',  'icon' => 'fas fa-tags',     'enabled' => true,  'data' => []],
                ['key' => 'posts',      'label' => 'Blog Posts Grid',      'icon' => 'fas fa-th',       'enabled' => true,  'data' => ['posts_per_page' => 9, 'show_search' => true]],
                ['key' => 'newsletter', 'label' => 'Newsletter Signup',    'icon' => 'fas fa-envelope', 'enabled' => false, 'data' => ['heading' => 'Stay Updated', 'text' => '']],
            ],
            'shop' => [
                ['key' => 'hero',       'label' => 'Shop Hero / Banner',   'icon' => 'fas fa-image',          'enabled' => true,  'data' => ['heading' => 'Shop', 'subheading' => '', 'image' => '']],
                ['key' => 'categories', 'label' => 'Category Navigation',  'icon' => 'fas fa-th-list',        'enabled' => true,  'data' => []],
                ['key' => 'featured',   'label' => 'Featured Products',    'icon' => 'fas fa-star',           'enabled' => true,  'data' => ['heading' => 'Featured', 'count' => 4]],
                ['key' => 'products',   'label' => 'Products Grid',        'icon' => 'fas fa-shopping-bag',   'enabled' => true,  'data' => ['products_per_page' => 12, 'show_filters' => true]],
                ['key' => 'newsletter', 'label' => 'Newsletter Signup',    'icon' => 'fas fa-envelope',       'enabled' => false, 'data' => ['heading' => 'Stay Updated']],
            ],
            'contact' => [
                ['key' => 'hero',   'label' => 'Contact Hero',    'icon' => 'fas fa-image',      'enabled' => true,  'data' => ['heading' => 'Contact Me', 'subheading' => '']],
                ['key' => 'form',   'label' => 'Contact Form',    'icon' => 'fas fa-envelope',   'enabled' => true,  'data' => ['email' => '', 'phone' => '', 'address' => '']],
                ['key' => 'map',    'label' => 'Map / Location',  'icon' => 'fas fa-map-marker', 'enabled' => false, 'data' => ['embed_url' => '']],
                ['key' => 'social', 'label' => 'Social Links',    'icon' => 'fas fa-share-alt',  'enabled' => true,  'data' => []],
            ],
            'services' => [
                ['key' => 'hero',    'label' => 'Services Hero',  'icon' => 'fas fa-image',  'enabled' => true,  'data' => ['heading' => 'Services', 'subheading' => '']],
                ['key' => 'list',    'label' => 'Services List',  'icon' => 'fas fa-list',   'enabled' => true,  'data' => ['heading' => 'What I Offer', 'layout' => 'grid']],
                ['key' => 'process', 'label' => 'How It Works',   'icon' => 'fas fa-cogs',   'enabled' => false, 'data' => ['heading' => 'My Process']],
                ['key' => 'pricing', 'label' => 'Pricing Table',  'icon' => 'fas fa-tag',    'enabled' => false, 'data' => ['heading' => 'Pricing']],
                ['key' => 'cta',     'label' => 'Call to Action', 'icon' => 'fas fa-rocket', 'enabled' => true,  'data' => ['heading' => "Let's Work Together", 'text' => '', 'button_text' => 'Contact Me', 'button_url' => '/contact']],
            ],
            'portfolio' => [
                ['key' => 'hero',     'label' => 'Portfolio Hero',    'icon' => 'fas fa-image',      'enabled' => true,  'data' => ['heading' => 'My Work', 'subheading' => '']],
                ['key' => 'filter',   'label' => 'Category Filter',   'icon' => 'fas fa-filter',     'enabled' => true,  'data' => []],
                ['key' => 'projects', 'label' => 'Projects Grid',     'icon' => 'fas fa-th',         'enabled' => true,  'data' => ['columns' => 3]],
                ['key' => 'cta',      'label' => 'Hire Me CTA',       'icon' => 'fas fa-rocket',     'enabled' => false, 'data' => ['heading' => 'Hire Me', 'text' => '', 'button_text' => 'Get in Touch']],
            ],
            default => [
                ['key' => 'hero',    'label' => 'Page Hero',    'icon' => 'fas fa-image',      'enabled' => true,  'data' => ['heading' => '', 'subheading' => '']],
                ['key' => 'content', 'label' => 'Main Content', 'icon' => 'fas fa-align-left', 'enabled' => true,  'data' => []],
            ],
        };
    }

    /**
     * Get merged sections: defaults overridden by saved config.
     */
    public function getMergedSectionsAttribute(): array
    {
        $defaults = self::defaultSections($this->page_type ?? 'custom');
        $saved    = $this->sections ?? [];
        $savedMap = collect($saved)->keyBy('key')->toArray();

        return array_map(function ($section) use ($savedMap) {
            if (isset($savedMap[$section['key']])) {
                $s = $savedMap[$section['key']];
                $section['enabled'] = $s['enabled'] ?? $section['enabled'];
                $section['data']    = array_merge($section['data'] ?? [], $s['data'] ?? []);
            }
            return $section;
        }, $defaults);
    }

    /**
     * Check if a specific section is enabled.
     */
    public function isSectionEnabled(string $key): bool
    {
        $sections = $this->merged_sections;
        foreach ($sections as $section) {
            if ($section['key'] === $key) {
                return (bool) ($section['enabled'] ?? false);
            }
        }
        return false;
    }

    /**
     * Get data for a specific section.
     */
    public function getSectionData(string $key): array
    {
        $sections = $this->merged_sections;
        foreach ($sections as $section) {
            if ($section['key'] === $key) {
                return $section['data'] ?? [];
            }
        }
        return [];
    }

    /**
     * Generate the correct public URL for this page.
     */
    public function getPublicUrlAttribute(): string
    {
        $owner = $this->owner;
        if (!$owner) return '#';

        $customDomain = $owner->custom_domain ?? null;
        if ($customDomain) {
            $base = 'https://' . $customDomain;
        } else {
            $base = url('/' . $owner->username);
        }

        $slug = $this->slug;

        if (array_key_exists($slug, self::SYSTEM_SLUGS)) {
            $segment = self::SYSTEM_SLUGS[$slug];
            if ($segment === '') return $base;
            return $base . '/' . $segment;
        }

        if ($customDomain) {
            return $base . '/page/' . $slug;
        }
        return url('/' . $owner->username . '/page/' . $slug);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true)->orderBy('sort_order');
    }
}
