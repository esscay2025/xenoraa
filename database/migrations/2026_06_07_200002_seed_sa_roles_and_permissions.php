<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ---- Roles ----
        $roles = [
            ['name' => 'superadmin', 'display_name' => 'Super Admin', 'description' => 'Full access to all modules'],
            ['name' => 'staff',      'display_name' => 'Staff',       'description' => 'Platform operations staff'],
            ['name' => 'agent',      'display_name' => 'Agent',       'description' => 'Dealer / reseller agent'],
        ];
        foreach ($roles as $role) {
            DB::table('sa_roles')->updateOrInsert(['name' => $role['name']], array_merge($role, ['created_at' => now(), 'updated_at' => now()]));
        }

        // ---- Permissions ----
        $permissions = [
            // Customers
            ['key' => 'customers.view',   'display_name' => 'View Customers',   'module' => 'Customers'],
            ['key' => 'customers.create', 'display_name' => 'Create Customers', 'module' => 'Customers'],
            ['key' => 'customers.edit',   'display_name' => 'Edit Customers',   'module' => 'Customers'],
            ['key' => 'customers.delete', 'display_name' => 'Delete Customers', 'module' => 'Customers'],
            ['key' => 'customers.assign', 'display_name' => 'Assign Subscriptions', 'module' => 'Customers'],
            // Agents
            ['key' => 'agents.view',        'display_name' => 'View Agents',        'module' => 'Agents'],
            ['key' => 'agents.create',      'display_name' => 'Create Agents',      'module' => 'Agents'],
            ['key' => 'agents.edit',        'display_name' => 'Edit Agents',        'module' => 'Agents'],
            ['key' => 'agents.delete',      'display_name' => 'Delete Agents',      'module' => 'Agents'],
            ['key' => 'agents.allot',       'display_name' => 'Allot Subscriptions','module' => 'Agents'],
            ['key' => 'agents.commissions', 'display_name' => 'Pay Commissions',    'module' => 'Agents'],
            // Staff
            ['key' => 'staff.view',   'display_name' => 'View Staff',   'module' => 'Staff'],
            ['key' => 'staff.create', 'display_name' => 'Create Staff', 'module' => 'Staff'],
            ['key' => 'staff.edit',   'display_name' => 'Edit Staff',   'module' => 'Staff'],
            ['key' => 'staff.delete', 'display_name' => 'Delete Staff', 'module' => 'Staff'],
            // Subscriptions
            ['key' => 'subscriptions.view',   'display_name' => 'View Subscriptions',   'module' => 'Subscriptions'],
            ['key' => 'subscriptions.manage', 'display_name' => 'Manage Subscriptions', 'module' => 'Subscriptions'],
            ['key' => 'revenue.view',         'display_name' => 'View Revenue',         'module' => 'Subscriptions'],
            // Domains
            ['key' => 'domains.view',   'display_name' => 'View Domains',   'module' => 'Domains'],
            ['key' => 'domains.manage', 'display_name' => 'Manage Domains', 'module' => 'Domains'],
            // Content
            ['key' => 'blog.view',    'display_name' => 'View Blog',    'module' => 'Content'],
            ['key' => 'blog.manage',  'display_name' => 'Manage Blog',  'module' => 'Content'],
            ['key' => 'showcase.view','display_name' => 'View Showcase','module' => 'Content'],
            // Settings
            ['key' => 'settings.view',   'display_name' => 'View Settings',   'module' => 'Settings'],
            ['key' => 'settings.manage', 'display_name' => 'Manage Settings', 'module' => 'Settings'],
            // Analytics
            ['key' => 'analytics.view', 'display_name' => 'View Analytics', 'module' => 'Analytics'],
            // Themes
            ['key' => 'themes.view',   'display_name' => 'View Themes',   'module' => 'Themes'],
            ['key' => 'themes.manage', 'display_name' => 'Manage Themes', 'module' => 'Themes'],
            // Logs
            ['key' => 'logs.view', 'display_name' => 'View Logs', 'module' => 'Logs'],
        ];

        foreach ($permissions as $perm) {
            DB::table('sa_permissions')->updateOrInsert(
                ['key' => $perm['key']],
                array_merge($perm, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // ---- Assign ALL permissions to superadmin role ----
        $superadminRoleId = DB::table('sa_roles')->where('name', 'superadmin')->value('id');
        $staffRoleId      = DB::table('sa_roles')->where('name', 'staff')->value('id');
        $allPermIds       = DB::table('sa_permissions')->pluck('id');

        // Superadmin gets all permissions
        foreach ($allPermIds as $permId) {
            DB::table('sa_role_permissions')->updateOrInsert(
                ['sa_role_id' => $superadminRoleId, 'sa_permission_id' => $permId],
                ['created_at' => now()]
            );
        }

        // Staff gets most permissions except delete and settings.manage
        $staffPermKeys = [
            'customers.view','customers.create','customers.edit','customers.assign',
            'agents.view','agents.edit','agents.allot','agents.commissions',
            'subscriptions.view','subscriptions.manage','revenue.view',
            'domains.view','domains.manage',
            'blog.view','blog.manage','showcase.view',
            'settings.view','analytics.view',
            'themes.view','logs.view',
        ];
        $staffPermIds = DB::table('sa_permissions')->whereIn('key', $staffPermKeys)->pluck('id');
        foreach ($staffPermIds as $permId) {
            DB::table('sa_role_permissions')->updateOrInsert(
                ['sa_role_id' => $staffRoleId, 'sa_permission_id' => $permId],
                ['created_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('sa_role_permissions')->truncate();
        DB::table('sa_permissions')->truncate();
        DB::table('sa_roles')->truncate();
    }
};
