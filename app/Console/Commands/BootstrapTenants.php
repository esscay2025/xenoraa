<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use App\Services\TenantBootstrapService;
use Illuminate\Console\Command;

class BootstrapTenants extends Command
{
    protected $signature   = 'xenoraa:bootstrap-tenants {--force : Wipe and rebuild all pages/menus} {--user= : Only bootstrap a specific user ID}';
    protected $description = 'Provision default pages, menus, site settings, and chatbot training for all tenant admin users.';

    public function handle(): int
    {
        $service = new TenantBootstrapService();
        $force   = $this->option('force');
        $userId  = $this->option('user');

        // Find admin role
        $adminRole = Role::where('name', 'admin')->first();
        if (!$adminRole) {
            $this->error('Admin role not found.');
            return 1;
        }

        $query = User::where('role_id', $adminRole->id);
        if ($userId) {
            $query->where('id', $userId);
        }

        $tenants = $query->get();

        if ($tenants->isEmpty()) {
            $this->warn('No tenant admin users found.');
            return 0;
        }

        $this->info('Bootstrapping ' . $tenants->count() . ' tenant(s)...');
        $bar = $this->output->createProgressBar($tenants->count());
        $bar->start();

        foreach ($tenants as $tenant) {
            try {
                if ($force) {
                    $service->resetToDefault($tenant);
                } else {
                    $service->bootstrapNewTenant($tenant);
                }
                $this->line('');
                $this->info("  ✓ {$tenant->name} ({$tenant->email}) — bootstrapped");
            } catch (\Throwable $e) {
                $this->line('');
                $this->error("  ✗ {$tenant->name} ({$tenant->email}) — {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->line('');
        $this->info('Done!');

        return 0;
    }
}
