<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'display_name', 'description', 'modules', 'tenant_owner_id'];

    protected $casts = [
        'modules' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tenantOwner()
    {
        return $this->belongsTo(User::class, 'tenant_owner_id');
    }

    /**
     * Check if this role grants access to a specific module.
     * '*' means all modules.
     */
    public function hasModule(string $module): bool
    {
        $modules = $this->modules ?? [];
        return in_array('*', $modules) || in_array($module, $modules);
    }

    /**
     * All available modules in the platform.
     */
    public static function availableModules(): array
    {
        return [
            'dashboard'   => ['label' => 'Dashboard',        'icon' => 'fas fa-tachometer-alt'],
            'blog'        => ['label' => 'Blog / Content',   'icon' => 'fas fa-blog'],
            'forum'       => ['label' => 'Forum',            'icon' => 'fas fa-comments'],
            'ecommerce'   => ['label' => 'E-Commerce',       'icon' => 'fas fa-shopping-cart'],
            'jobs'        => ['label' => 'Jobs',             'icon' => 'fas fa-briefcase'],
            'crm'         => ['label' => 'CRM',              'icon' => 'fas fa-users'],
            'newsletter'  => ['label' => 'Newsletter',       'icon' => 'fas fa-envelope'],
            'calendar'    => ['label' => 'Calendar',         'icon' => 'fas fa-calendar'],
            'notes'       => ['label' => 'Notes',            'icon' => 'fas fa-sticky-note'],
            'site_builder'=> ['label' => 'Site Builder',     'icon' => 'fas fa-paint-brush'],
            'analytics'   => ['label' => 'Analytics',        'icon' => 'fas fa-chart-bar'],
            'chatbot'     => ['label' => 'Chatbot',          'icon' => 'fas fa-robot'],
            'settings'    => ['label' => 'Settings',         'icon' => 'fas fa-cog'],
            'users'       => ['label' => 'User Management',  'icon' => 'fas fa-user-cog'],
        ];
    }
}
