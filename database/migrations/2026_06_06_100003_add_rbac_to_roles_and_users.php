<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add modules JSON column to roles (default modules for this role)
        if (!Schema::hasColumn('roles', 'modules')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->json('modules')->nullable()->after('description')
                      ->comment('Default module list for this role');
                $table->unsignedBigInteger('tenant_owner_id')->nullable()->after('modules')
                      ->comment('NULL = system role, set = tenant-created role');
                $table->index('tenant_owner_id');
            });
        }

        // Add module_permissions JSON column to users (individual overrides)
        if (!Schema::hasColumn('users', 'module_permissions')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('module_permissions')->nullable()->after('status')
                      ->comment('Per-user module access overrides (null = use role defaults)');
            });
        }

        // Rename 'visitor' role to 'admin_staff'
        DB::table('roles')->where('name', 'visitor')->update([
            'name'         => 'admin_staff',
            'display_name' => 'Admin Staff',
            'description'  => 'Tenant-created staff user with configurable module access',
        ]);

        // Set default modules for system roles
        DB::table('roles')->where('name', 'admin')->update([
            'modules' => json_encode(['*']), // all modules
        ]);
        DB::table('roles')->where('name', 'staff')->update([
            'modules' => json_encode(['*']), // all modules (legacy staff)
        ]);
        DB::table('roles')->where('name', 'admin_staff')->update([
            'modules' => json_encode([]), // no modules by default
        ]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('roles', 'modules')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn(['modules', 'tenant_owner_id']);
            });
        }
        if (Schema::hasColumn('users', 'module_permissions')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('module_permissions');
            });
        }
        DB::table('roles')->where('name', 'admin_staff')->update([
            'name'         => 'visitor',
            'display_name' => 'Website Visitor',
            'description'  => 'Website visitor with limited access',
        ]);
    }
};
