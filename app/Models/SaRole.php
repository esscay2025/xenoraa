<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaRole extends Model
{
    use HasFactory;

    protected $table = 'sa_roles';

    protected $fillable = ['name', 'display_name', 'description', 'permissions'];

    protected $casts = ['permissions' => 'array'];

    // ── Relationships ──────────────────────────────────────────────────────

    public function users()
    {
        return $this->hasMany(User::class, 'sa_role_id');
    }

    public function saPermissions()
    {
        return $this->belongsToMany(SaPermission::class, 'sa_role_permissions', 'sa_role_id', 'sa_permission_id');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    public function hasPermission(string $key): bool
    {
        $perms = $this->permissions ?? [];
        if (in_array('*', $perms)) return true;
        return $this->saPermissions()->where('key', $key)->exists();
    }

    public static function superadmin(): ?self
    {
        return static::where('name', 'superadmin')->first();
    }

    public static function staff(): ?self
    {
        return static::where('name', 'staff')->first();
    }

    public static function agent(): ?self
    {
        return static::where('name', 'agent')->first();
    }
}
