<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['user_id', 'key', 'value'];

    protected static $cachedSettings = null;
    protected static $cachedTenantSettings = [];

    // ─── Legacy (global) helpers ─────────────────────────────────
    public static function getSettings()
    {
        if (self::$cachedSettings === null) {
            try {
                self::$cachedSettings = self::whereNull('user_id')->pluck('value', 'key')->toArray();
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
        $setting = self::updateOrCreate(['key' => $key, 'user_id' => null], ['value' => $value]);
        self::$cachedSettings = null;
        return $setting;
    }

    // ─── Tenant-scoped helpers ────────────────────────────────────
    public static function forTenant(int $userId)
    {
        return static::where('user_id', $userId);
    }

    public static function getSettingsForTenant(int $userId): array
    {
        if (!isset(self::$cachedTenantSettings[$userId])) {
            try {
                self::$cachedTenantSettings[$userId] = self::where('user_id', $userId)
                    ->pluck('value', 'key')
                    ->toArray();
            } catch (\Exception $e) {
                self::$cachedTenantSettings[$userId] = [];
            }
        }
        return self::$cachedTenantSettings[$userId];
    }

    public static function getValueForTenant(int $userId, string $key, $default = null)
    {
        $settings = self::getSettingsForTenant($userId);
        return $settings[$key] ?? $default;
    }

    public static function setValueForTenant(int $userId, string $key, $value)
    {
        $setting = self::updateOrCreate(
            ['user_id' => $userId, 'key' => $key],
            ['value' => $value]
        );
        // Bust cache
        unset(self::$cachedTenantSettings[$userId]);
        return $setting;
    }

    public static function clearTenantCache(int $userId): void
    {
        unset(self::$cachedTenantSettings[$userId]);
    }
}
