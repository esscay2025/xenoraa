<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaPermission extends Model
{
    protected $table = 'sa_permissions';

    protected $fillable = ['key', 'group', 'label', 'description'];

    public function saRoles()
    {
        return $this->belongsToMany(SaRole::class, 'sa_role_permissions', 'sa_permission_id', 'sa_role_id');
    }

    /**
     * All permissions grouped by group key.
     */
    public static function grouped(): array
    {
        $all = static::orderBy('group')->orderBy('label')->get();
        $groups = [];
        foreach ($all as $perm) {
            $groups[$perm->group][] = $perm;
        }
        return $groups;
    }
}
