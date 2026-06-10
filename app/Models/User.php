<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'sa_role_id',
        'created_by_sa',
        'tenant_owner_id',
        'status',
        'username',
        'plan',
        'custom_domain',
        'profession',
        'site_title',
        'bio',
        'avatar',
        'trial_ends_at',
        'plan_expires_at',
        'profile_template',
        'onboarding_completed',
        'phone',
        'city',
        'website',
        'module_permissions',
        'plan_billing',
        'payment_id',
        'business_info',
        'business_info_ai',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'trial_ends_at'     => 'datetime',
        'plan_expires_at'   => 'datetime',
        'onboarding_completed' => 'boolean',
        'module_permissions'   => 'array',
        'business_info_ai'     => 'array',
    ];

    // =============================================
    // TENANT RELATIONSHIPS
    // =============================================

    /**
     * The admin/tenant owner who created this sub-user.
     */
    public function tenantOwner()
    {
        return $this->belongsTo(User::class, 'tenant_owner_id');
    }

    /**
     * Sub-users (staff, visitors) belonging to this tenant admin.
     */
    public function subUsers()
    {
        return $this->hasMany(User::class, 'tenant_owner_id');
    }

    /**
     * Returns the tenant owner ID for scoping queries.
     * Admin = their own ID. Staff/visitor = their tenant_owner_id.
     */
    public function getTenantId(): int
    {
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return $this->id;
        }
        return $this->tenant_owner_id ?? $this->id;
    }

    public function getProfileTemplate(): string
    {
        return $this->profile_template ?? 'default';
    }

    // =============================================
    // ROLE HELPERS
    // =============================================

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role && $this->role->name === $role;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isStaff(): bool
    {
        return $this->hasRole('staff');
    }

    public function isVisitor(): bool
    {
        return $this->hasRole('visitor');
    }

    public function isAdminStaff(): bool
    {
        return $this->hasRole('admin_staff');
    }

    /**
     * Check if this user has access to a specific module.
     * Admin/SuperAdmin = always yes.
     * Staff with '*' role modules = yes.
     * Admin staff = check module_permissions (user override) then role modules.
     */
    public function hasModuleAccess(string $module): bool
    {
        // Admins and superadmins have access to everything
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return true;
        }

        // Check user-level overrides first
        $userModules = $this->module_permissions ?? null;
        if ($userModules !== null) {
            return in_array('*', $userModules) || in_array($module, $userModules);
        }

        // Fall back to role-level modules
        if ($this->role) {
            return $this->role->hasModule($module);
        }

        return false;
    }

    /**
     * Get the list of modules this user can access.
     */
    public function accessibleModules(): array
    {
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return ['*'];
        }
        $userModules = $this->module_permissions ?? null;
        if ($userModules !== null) {
            return $userModules;
        }
        return $this->role?->modules ?? [];
    }

    public function isSuperAdmin(): bool
    {
        $superAdminEmails = config('xenoraa.superadmin_emails', []);
        return in_array($this->email, $superAdminEmails) || $this->hasRole('superadmin');
    }

    // =============================================
    // SUPER-ADMIN ROLE HELPERS
    // =============================================

    public function saRole()
    {
        return $this->belongsTo(\App\Models\SaRole::class, 'sa_role_id');
    }

    public function isSaStaff(): bool
    {
        return $this->saRole && $this->saRole->name === 'staff';
    }

    public function isSaAgent(): bool
    {
        return $this->saRole && $this->saRole->name === 'agent';
    }

    public function isSaUser(): bool
    {
        return $this->isSuperAdmin() || $this->isSaStaff() || $this->isSaAgent();
    }

    /**
     * Check if this user has a specific super-admin permission.
     * SuperAdmins always return true.
     * Staff/Agents check their role permissions + user-level overrides.
     */
    public function hasSaPermission(string $key): bool
    {
        if ($this->isSuperAdmin()) return true;
        if (!$this->saRole) return false;

        // Check user-level override
        $override = \Illuminate\Support\Facades\DB::table('sa_user_permissions')
            ->join('sa_permissions', 'sa_permissions.id', '=', 'sa_user_permissions.sa_permission_id')
            ->where('sa_user_permissions.user_id', $this->id)
            ->where('sa_permissions.key', $key)
            ->first();
        if ($override !== null) return (bool) $override->granted;

        // Check role permissions
        return $this->saRole->hasPermission($key);
    }

    public function agentProfile()
    {
        return $this->hasOne(\App\Models\Agent::class, 'user_id');
    }

    // =============================================
    // PLAN HELPERS
    // =============================================

    public function getPlan(): string
    {
        return $this->plan ?? 'starter';
    }

    public function isOnPlan(string $plan): bool
    {
        return $this->getPlan() === $plan;
    }

    public function canUseFeature(string $feature): bool
    {
        $plan = $this->getPlan();
        $plans = config('xenoraa.plans', []);
        return $plans[$plan]['features'][$feature] ?? false;
    }

    /**
     * Check if the tenant's subscription plan includes a given module.
     * SuperAdmins and tenant owners on the 'business' plan always get everything.
     * For other plans, check the plan_modules config.
     */
    public function planHasModule(string $module): bool
    {
        // SuperAdmins and tenant owners on business plan get all modules
        if ($this->isSuperAdmin()) {
            return true;
        }
        $plan = $this->getPlan();
        // Business Pro gets everything
        if ($plan === 'business') {
            return true;
        }
        $planModules = config('xenoraa.plan_modules', []);
        $allowed = $planModules[$plan] ?? [];
        return in_array($module, $allowed) || in_array('*', $allowed);
    }

    public function isTrialing(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    // =============================================
    // PROFILE URL HELPERS
    // =============================================

    public function getProfileUrl(): string
    {
        if ($this->custom_domain) {
            return 'https://' . $this->custom_domain;
        }
        if ($this->username) {
            return 'https://' . config('xenoraa.main_domain', 'xenoraa.com') . '/' . $this->username;
        }
        return '#';
    }

    // =============================================
    // RELATIONSHIPS
    // =============================================

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function crmLeads()
    {
        return $this->hasMany(CrmLead::class);
    }
}
