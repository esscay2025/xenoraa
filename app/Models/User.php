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
    ];

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

    public function isSuperAdmin(): bool
    {
        $superAdminEmails = config('xenoraa.superadmin_emails', []);
        return in_array($this->email, $superAdminEmails) || $this->hasRole('superadmin');
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
