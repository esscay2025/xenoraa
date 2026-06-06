<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'slug', 'name', 'category', 'description', 'tags',
        'accent_color', 'bg_color', 'preview_css', 'hero_title', 'hero_sub',
        'sections', 'best_for', 'profession_key', 'profession_keywords',
        'is_premium', 'is_active', 'thumbnail', 'demo_url', 'sort_order',
    ];

    protected $casts = [
        'tags'                => 'array',
        'sections'            => 'array',
        'profession_keywords' => 'array',
        'is_premium'          => 'boolean',
        'is_active'           => 'boolean',
    ];

    /**
     * Get themes that match a given profession string.
     * Matches against profession_key or any keyword in profession_keywords.
     */
    public static function forProfession(?string $profession): \Illuminate\Database\Eloquent\Collection
    {
        if (empty($profession)) {
            return static::where('is_active', true)->orderBy('sort_order')->get();
        }

        $lower = strtolower($profession);

        return static::where('is_active', true)
            ->get()
            ->filter(function (Theme $theme) use ($lower) {
                // Check profession_key first
                if ($theme->profession_key && str_contains($lower, $theme->profession_key)) {
                    return true;
                }
                // Check profession_keywords
                if ($theme->profession_keywords) {
                    foreach ($theme->profession_keywords as $keyword) {
                        if (str_contains($lower, strtolower($keyword))) {
                            return true;
                        }
                    }
                }
                return false;
            })
            ->sortBy('sort_order')
            ->values();
    }

    /**
     * Get the preview CSS as an associative array.
     */
    public function getPreviewStyles(): array
    {
        $styles = [];
        if ($this->preview_css) {
            foreach (explode(';', $this->preview_css) as $part) {
                [$key, $val] = array_pad(explode(':', $part, 2), 2, '');
                if ($key && $val) {
                    $styles[trim($key)] = trim($val);
                }
            }
        }
        return $styles;
    }
}
