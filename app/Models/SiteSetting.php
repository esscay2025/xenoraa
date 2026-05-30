<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static $cachedSettings = null;

    public static function getSettings()
    {
        if (self::$cachedSettings === null) {
            try {
                self::$cachedSettings = self::pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                self::$cachedSettings = [];
            }
        }
        return self::$cachedSettings;
    }

    public static function getValue($key, $default = null)
    {
        $settings = self::getSettings();
        return $settings[$key] ?? $default;
    }

    public static function setValue($key, $value)
    {
        $setting = self::updateOrCreate(['key' => $key], ['value' => $value]);
        self::$cachedSettings = null; // Clear cache
        return $setting;
    }
}
