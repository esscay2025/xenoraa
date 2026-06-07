<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        // ---- Ensure roles exist (already seeded by migration 200001, but be safe) ----
        $roles = [
            ['name' => 'superadmin', 'display_name' => 'Super Admin',   'description' => 'Full access to all modules'],
            ['name' => 'staff',      'display_name' => 'Staff',          'description' => 'Platform operations staff'],
            ['name' => 'agent',      'display_name' => 'Agent / Dealer', 'description' => 'Dealer / reseller agent'],
        ];
        foreach ($roles as $role) {
            DB::table('sa_roles')->updateOrInsert(
                ['name' => $role['name']],
                array_merge($role, ['created_at' => $now, 'updated_at' => $now])
            );
        }

        // ---- Permissions (using correct column names: key, group, label) ----
        $permissions = [
            // Customers
            ['key' => 'customers.view',        'group' => 'Customers',     'label' => 'View Customers'],
            ['key' => 'customers.create',      'group' => 'Customers',     'label' => 'Create Customers'],
            ['key' => 'customers.edit',        'group' => 'Customers',     'label' => 'Edit Customers'],
            ['key' => 'customers.delete',      'group' => 'Customers',     'label' => 'Delete Customers'],
            ['key' => 'customers.assign',      'group' => 'Customers',     'label' => 'Assign Subscriptions'],
            ['key' => 'customers.impersonate', 'group' => 'Customers',     'label' => 'Impersonate Customers'],
            // Agents
            ['key' => 'agents.view',           'group' => 'Agents',        'label' => 'View Agents'],
            ['key' => 'agents.create',         'group' => 'Agents',        'label' => 'Create Agents'],
            ['key' => 'agents.edit',           'group' => 'Agents',        'label' => 'Edit Agents'],
            ['key' => 'agents.delete',         'group' => 'Agents',        'label' => 'Delete Agents'],
            ['key' => 'agents.allot',          'group' => 'Agents',        'label' => 'Allot Subscriptions'],
            ['key' => 'agents.commissions',    'group' => 'Agents',        'label' => 'Pay Commissions'],
            // Staff
            ['key' => 'staff.view',            'group' => 'Staff',         'label' => 'View Staff'],
            ['key' => 'staff.create',          'group' => 'Staff',         'label' => 'Create Staff'],
            ['key' => 'staff.edit',            'group' => 'Staff',         'label' => 'Edit Staff'],
            ['key' => 'staff.delete',          'group' => 'Staff',         'label' => 'Delete Staff'],
            // Subscriptions
            ['key' => 'subscriptions.view',    'group' => 'Subscriptions', 'label' => 'View Subscriptions'],
            ['key' => 'subscriptions.manage',  'group' => 'Subscriptions', 'label' => 'Manage Subscriptions'],
            ['key' => 'revenue.view',          'group' => 'Subscriptions', 'label' => 'View Revenue'],
            // Domains
            ['key' => 'domains.view',          'group' => 'Domains',       'label' => 'View Domains'],
            ['key' => 'domains.manage',        'group' => 'Domains',       'label' => 'Manage Domains'],
            // Content
            ['key' => 'blog.view',             'group' => 'Content',       'label' => 'View Blog'],
            ['key' => 'blog.manage',           'group' => 'Content',       'label' => 'Manage Blog'],
            ['key' => 'showcase.view',         'group' => 'Content',       'label' => 'View Showcase'],
            // Settings
            ['key' => 'settings.view',         'group' => 'Settings',      'label' => 'View Settings'],
            ['key' => 'settings.manage',       'group' => 'Settings',      'label' => 'Manage Settings'],
            // Analytics
            ['key' => 'analytics.view',        'group' => 'Analytics',     'label' => 'View Analytics'],
            // Themes
            ['key' => 'themes.view',           'group' => 'Themes',        'label' => 'View Themes'],
            ['key' => 'themes.manage',         'group' => 'Themes',        'label' => 'Manage Themes'],
            // Logs
            ['key' => 'logs.view',             'group' => 'Logs',          'label' => 'View Logs'],
        ];

        foreach ($permissions as $perm) {
            DB::table('sa_permissions')->updateOrInsert(
                ['key' => $perm['key']],
                array_merge($perm, ['created_at' => $now, 'updated_at' => $now])
            );
        }

        // ---- Assign ALL permissions to superadmin role ----
        $superadminRoleId = DB::table('sa_roles')->where('name', 'superadmin')->value('id');
        $staffRoleId      = DB::table('sa_roles')->where('name', 'staff')->value('id');
        $allPermIds       = DB::table('sa_permissions')->pluck('id');

        foreach ($allPermIds as $permId) {
            DB::table('sa_role_permissions')->updateOrInsert(
                ['sa_role_id' => $superadminRoleId, 'sa_permission_id' => $permId],
                ['created_at' => $now]
            );
        }

        // ---- Staff gets most permissions except delete and settings.manage ----
        $staffPermKeys = [
            'customers.view','customers.create','customers.edit','customers.assign','customers.impersonate',
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
                ['created_at' => $now]
            );
        }
    }

    public function down(): void
    {
        DB::table('sa_role_permissions')->truncate();
        DB::table('sa_permissions')->truncate();
    }
};
