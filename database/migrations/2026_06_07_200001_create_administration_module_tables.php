<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Super-admin roles (staff / agent / superadmin) ──────────────
        Schema::create('sa_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();           // superadmin | staff | agent
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->json('permissions')->nullable();    // array of permission keys
            $table->timestamps();
        });

        // ── 2. Super-admin users (staff + agents share the users table, distinguished by sa_role_id) ──
        // We add columns to the users table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('sa_role_id')->nullable()->after('role_id');
            $table->unsignedBigInteger('created_by_sa')->nullable()->after('sa_role_id'); // which superadmin/staff created this user
            $table->foreign('sa_role_id')->references('id')->on('sa_roles')->nullOnDelete();
        });

        // ── 3. Agent profiles ─────────────────────────────────────────────
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // FK to users table
            $table->string('agent_code')->unique();          // e.g. AGT-0001
            $table->string('company_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('India');
            $table->string('bank_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(10.00); // percentage
            $table->integer('subscription_quota')->default(0);         // total allotted
            $table->integer('subscriptions_used')->default(0);         // how many assigned
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // ── 4. Subscription allotments per agent ─────────────────────────
        // Tracks each batch of subscriptions assigned to an agent
        Schema::create('agent_subscription_allotments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('assigned_by');      // superadmin/staff user id
            $table->string('plan');                         // starter | professional | business
            $table->integer('quantity');                    // number of subscriptions allotted
            $table->integer('used')->default(0);
            $table->date('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('agent_id')->references('id')->on('agents')->cascadeOnDelete();
            $table->foreign('assigned_by')->references('id')->on('users');
        });

        // ── 5. Subscriptions assigned by agents to customers ─────────────
        Schema::create('agent_assigned_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('customer_user_id');  // the tenant user
            $table->unsignedBigInteger('allotment_id')->nullable(); // which allotment batch
            $table->string('plan');
            $table->integer('duration_months')->default(1);
            $table->date('starts_at');
            $table->date('expires_at');
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->decimal('plan_price', 10, 2)->default(0);
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->enum('commission_status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending');
            $table->date('commission_paid_at')->nullable();
            $table->timestamps();
            $table->foreign('agent_id')->references('id')->on('agents')->cascadeOnDelete();
            $table->foreign('customer_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('allotment_id')->references('id')->on('agent_subscription_allotments')->nullOnDelete();
        });

        // ── 6. Commission payouts ─────────────────────────────────────────
        Schema::create('agent_commission_payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('processed_by');     // superadmin user id
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();   // bank_transfer | upi | cheque
            $table->string('reference_no')->nullable();
            $table->date('paid_at');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('agent_id')->references('id')->on('agents')->cascadeOnDelete();
            $table->foreign('processed_by')->references('id')->on('users');
        });

        // ── 7. Super-admin permissions (granular) ─────────────────────────
        Schema::create('sa_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();        // e.g. customers.create
            $table->string('group');                // customers | agents | staff | subscriptions | settings
            $table->string('label');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // ── 8. Pivot: sa_role <-> sa_permission ───────────────────────────
        Schema::create('sa_role_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('sa_role_id');
            $table->unsignedBigInteger('sa_permission_id');
            $table->primary(['sa_role_id', 'sa_permission_id']);
            $table->foreign('sa_role_id')->references('id')->on('sa_roles')->cascadeOnDelete();
            $table->foreign('sa_permission_id')->references('id')->on('sa_permissions')->cascadeOnDelete();
        });

        // ── 9. User-level permission overrides ────────────────────────────
        Schema::create('sa_user_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sa_permission_id');
            $table->boolean('granted')->default(true); // can deny too
            $table->primary(['user_id', 'sa_permission_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('sa_permission_id')->references('id')->on('sa_permissions')->cascadeOnDelete();
        });

        // ── 10. Seed default roles ────────────────────────────────────────
        $now = now();
        DB::table('sa_roles')->insert([
            ['name' => 'superadmin', 'display_name' => 'Super Admin',  'description' => 'Full access to all platform features.',             'permissions' => json_encode(['*']),  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'staff',      'display_name' => 'Staff',         'description' => 'Handles day-to-day super admin operations.',        'permissions' => json_encode([]),     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'agent',      'display_name' => 'Agent / Dealer','description' => 'Sells subscriptions and earns commission.',         'permissions' => json_encode([]),     'created_at' => $now, 'updated_at' => $now],
        ]);

        // ── 11. Seed permissions ──────────────────────────────────────────
        $permissions = [
            // Customers
            ['key' => 'customers.view',   'group' => 'customers',     'label' => 'View Customers',          'description' => 'View the customer list and profiles'],
            ['key' => 'customers.create', 'group' => 'customers',     'label' => 'Create Customers',        'description' => 'Create new tenant customer accounts'],
            ['key' => 'customers.edit',   'group' => 'customers',     'label' => 'Edit Customers',          'description' => 'Edit customer profiles and settings'],
            ['key' => 'customers.delete', 'group' => 'customers',     'label' => 'Delete Customers',        'description' => 'Delete customer accounts'],
            ['key' => 'customers.impersonate', 'group' => 'customers','label' => 'Impersonate Customers',   'description' => 'Log in as a customer for support'],
            // Subscriptions
            ['key' => 'subscriptions.view',   'group' => 'subscriptions', 'label' => 'View Subscriptions',  'description' => 'View subscription list and details'],
            ['key' => 'subscriptions.assign', 'group' => 'subscriptions', 'label' => 'Assign Subscriptions','description' => 'Assign subscription plans to customers'],
            ['key' => 'subscriptions.cancel', 'group' => 'subscriptions', 'label' => 'Cancel Subscriptions','description' => 'Cancel active subscriptions'],
            // Agents
            ['key' => 'agents.view',     'group' => 'agents', 'label' => 'View Agents',          'description' => 'View agent list and profiles'],
            ['key' => 'agents.create',   'group' => 'agents', 'label' => 'Create Agents',        'description' => 'Create new agent accounts'],
            ['key' => 'agents.edit',     'group' => 'agents', 'label' => 'Edit Agents',          'description' => 'Edit agent profiles and commission rates'],
            ['key' => 'agents.allot',    'group' => 'agents', 'label' => 'Allot Subscriptions',  'description' => 'Assign subscription quota to agents'],
            ['key' => 'agents.commissions', 'group' => 'agents', 'label' => 'Manage Commissions','description' => 'Approve and pay agent commissions'],
            // Staff
            ['key' => 'staff.view',   'group' => 'staff', 'label' => 'View Staff',   'description' => 'View staff list'],
            ['key' => 'staff.create', 'group' => 'staff', 'label' => 'Create Staff', 'description' => 'Create new staff accounts'],
            ['key' => 'staff.edit',   'group' => 'staff', 'label' => 'Edit Staff',   'description' => 'Edit staff profiles and permissions'],
            ['key' => 'staff.delete', 'group' => 'staff', 'label' => 'Delete Staff', 'description' => 'Delete staff accounts'],
            // Revenue
            ['key' => 'revenue.view', 'group' => 'revenue', 'label' => 'View Revenue', 'description' => 'View revenue and MRR reports'],
            // Settings
            ['key' => 'settings.view',   'group' => 'settings', 'label' => 'View Settings',   'description' => 'View platform settings'],
            ['key' => 'settings.update', 'group' => 'settings', 'label' => 'Update Settings', 'description' => 'Update platform settings'],
            // Domains
            ['key' => 'domains.view',   'group' => 'domains', 'label' => 'View Domains',   'description' => 'View custom domain list'],
            ['key' => 'domains.update', 'group' => 'domains', 'label' => 'Update Domains', 'description' => 'Update custom domain settings'],
            // Analytics
            ['key' => 'analytics.view', 'group' => 'analytics', 'label' => 'View Analytics', 'description' => 'View platform analytics and charts'],
            // Themes
            ['key' => 'themes.view',   'group' => 'themes', 'label' => 'View Themes',   'description' => 'View theme store'],
            ['key' => 'themes.manage', 'group' => 'themes', 'label' => 'Manage Themes', 'description' => 'Create, edit, and delete themes'],
            // Logs
            ['key' => 'logs.view', 'group' => 'logs', 'label' => 'View Logs', 'description' => 'View activity logs'],
        ];

        foreach ($permissions as &$p) {
            $p['created_at'] = $now;
            $p['updated_at'] = $now;
        }
        DB::table('sa_permissions')->insert($permissions);

        // Assign all permissions to superadmin role
        $superadminRoleId = DB::table('sa_roles')->where('name', 'superadmin')->value('id');
        $allPermIds = DB::table('sa_permissions')->pluck('id');
        foreach ($allPermIds as $permId) {
            DB::table('sa_role_permissions')->insert([
                'sa_role_id'       => $superadminRoleId,
                'sa_permission_id' => $permId,
            ]);
        }

        // Assign basic permissions to staff role
        $staffRoleId = DB::table('sa_roles')->where('name', 'staff')->value('id');
        $staffPerms = ['customers.view', 'customers.create', 'customers.edit', 'subscriptions.view', 'subscriptions.assign', 'domains.view', 'analytics.view'];
        $staffPermIds = DB::table('sa_permissions')->whereIn('key', $staffPerms)->pluck('id');
        foreach ($staffPermIds as $permId) {
            DB::table('sa_role_permissions')->insert([
                'sa_role_id'       => $staffRoleId,
                'sa_permission_id' => $permId,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sa_user_permissions');
        Schema::dropIfExists('sa_role_permissions');
        Schema::dropIfExists('sa_permissions');
        Schema::dropIfExists('agent_commission_payouts');
        Schema::dropIfExists('agent_assigned_subscriptions');
        Schema::dropIfExists('agent_subscription_allotments');
        Schema::dropIfExists('agents');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sa_role_id']);
            $table->dropColumn(['sa_role_id', 'created_by_sa']);
        });
        Schema::dropIfExists('sa_roles');
    }
};
